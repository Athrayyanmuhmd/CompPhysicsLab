<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScheduleAvailability;
use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AdminScheduleController extends Controller
{
    /**
     * Display schedule availability management
     */
    public function index(Request $request)
    {
        $currentMonth = $request->input('month', now()->format('Y-m'));
        $selectedDate = Carbon::parse($currentMonth . '-01');

        // Get all days in the month
        $daysInMonth = $selectedDate->daysInMonth;
        $monthData = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = $selectedDate->copy()->day($day);
            $dateStr = $date->format('Y-m-d');

            // Skip if date is weekend (Saturday or Sunday)
            if ($date->isWeekend()) {
                $monthData[$day] = [
                    'date' => $date,
                    'status' => 'closed',
                    'available_slots' => 0,
                    'total_slots' => 0,
                    'visits_count' => 0
                ];
                continue;
            }

            $slotsWithStatus = ScheduleAvailability::getAllSlotsWithStatus($dateStr);
            $availableCount = count(array_filter($slotsWithStatus, function($slot) {
                return $slot['available'];
            }));

            $visitsCount = Kunjungan::forDate($dateStr)
                                  ->whereIn('status', [Kunjungan::STATUS_PENDING, Kunjungan::STATUS_PROCESSING])
                                  ->count();

            $monthData[$day] = [
                'date' => $date,
                'status' => $availableCount == 0 ? 'full' : ($availableCount < 3 ? 'limited' : 'available'),
                'available_slots' => $availableCount,
                'total_slots' => count($slotsWithStatus),
                'visits_count' => $visitsCount
            ];
        }

        $stats = ScheduleAvailability::getAvailabilityStats(
            $selectedDate->startOfMonth()->format('Y-m-d'),
            $selectedDate->endOfMonth()->format('Y-m-d')
        );

        // Offset: Sen=0, Sel=1, Rab=2, Kam=3, Jum=4, Sab=5, Min=6
        $firstDayOffset = ($selectedDate->copy()->startOfMonth()->dayOfWeek + 6) % 7;

        return view('admin.schedule.index', compact('monthData', 'selectedDate', 'stats', 'firstDayOffset'));
    }

    /**
     * Show schedule for specific date
     */
    public function show(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $selectedDate = Carbon::parse($date);

        if ($selectedDate->isWeekend()) {
            return response()->json([
                'success' => false,
                'html' => '<div class="text-center py-8 text-red-500">
                            <i class="fas fa-times-circle text-4xl mb-3"></i>
                            <p class="text-lg font-medium">Laboratorium Tutup</p>
                            <p class="text-sm">Laboratorium tutup pada hari Sabtu dan Minggu</p>
                        </div>'
            ]);
        }

        $slotsWithStatus = ScheduleAvailability::getAllSlotsWithStatus($date);
        $visits = Kunjungan::forDate($date)
                          ->orderBy('waktu_kunjungan')
                          ->get();

        // Return partial view content for AJAX
        if ($request->ajax() || $request->wantsJson()) {
            $html = view('admin.schedule.show', compact('selectedDate', 'slotsWithStatus', 'visits'))->render();
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        }

        // Return full view for direct access
        return view('admin.schedule.show', compact('selectedDate', 'slotsWithStatus', 'visits'));
    }

    /**
     * Update slot availability
     */
    public function updateSlot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'time_slot' => 'required|string',
            'is_available' => 'required|boolean',
            'reason' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $date = $request->input('date');
            $timeSlot = $request->input('time_slot');

            // Check if there's already a visit at this time
            if (!$request->input('is_available')) {
                $existingVisit = Kunjungan::where('tanggal_kunjungan', $date)
                                         ->where('waktu_kunjungan', $timeSlot)
                                         ->whereIn('status', [Kunjungan::STATUS_PENDING, Kunjungan::STATUS_PROCESSING])
                                         ->exists();

                if ($existingVisit) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak dapat memblokir slot ini karena sudah ada kunjungan terjadwal.'
                    ], 422);
                }
            }

            $scheduleAvailability = ScheduleAvailability::setSlotAvailability(
                $date,
                $timeSlot,
                $request->input('is_available'),
                $request->input('reason'),
                $request->input('notes')
            );

            return response()->json([
                'success' => true,
                'message' => 'Status slot berhasil diperbarui.',
                'data' => $scheduleAvailability
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui status slot.'
            ], 500);
        }
    }

    /**
     * Batch update slots for a date
     */
    public function batchUpdateSlots(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'slots' => 'required|array',
            'slots.*' => 'string',
            'action' => 'required|in:enable,disable',
            'reason' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $date = $request->input('date');
            $slots = $request->input('slots');
            $isAvailable = $request->input('action') === 'enable';
            $reason = $request->input('reason');
            $notes = $request->input('notes');

            $updated = 0;
            $errors = [];

            foreach ($slots as $timeSlot) {
                // Check if there's already a visit at this time (only when disabling)
                if (!$isAvailable) {
                    $existingVisit = Kunjungan::where('tanggal_kunjungan', $date)
                                             ->where('waktu_kunjungan', $timeSlot)
                                             ->whereIn('status', [Kunjungan::STATUS_PENDING, Kunjungan::STATUS_PROCESSING])
                                             ->exists();

                    if ($existingVisit) {
                        $errors[] = "Slot {$timeSlot} sudah ada kunjungan terjadwal";
                        continue;
                    }
                }

                ScheduleAvailability::setSlotAvailability($date, $timeSlot, $isAvailable, $reason, $notes);
                $updated++;
            }

            $message = "Berhasil memperbarui {$updated} slot.";
            if (!empty($errors)) {
                $message .= " Gagal: " . implode(', ', $errors);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'updated' => $updated,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui slot.'
            ], 500);
        }
    }

    /**
     * Get calendar data for schedule management
     */
    public function getCalendarData(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');

        $events = [];
        $startDate = Carbon::parse($start);
        $endDate = Carbon::parse($end);

        // Generate events for each day in the range
        while ($startDate->lte($endDate)) {
            $dateStr = $startDate->format('Y-m-d');

            // Skip weekends
            if (!$startDate->isWeekend()) {
                $slotsWithStatus = ScheduleAvailability::getAllSlotsWithStatus($dateStr);
                $availableCount = count(array_filter($slotsWithStatus, function($slot) {
                    return $slot['available'];
                }));

                $totalSlots = count($slotsWithStatus);
                $visitsCount = Kunjungan::forDate($dateStr)->count();

                $color = '#10b981'; // Green for available
                if ($availableCount == 0) {
                    $color = '#ef4444'; // Red for full
                } elseif ($availableCount < 4) { // Less than half available
                    $color = '#f59e0b'; // Yellow for limited
                }

                $events[] = [
                    'id' => 'schedule-' . $dateStr,
                    'title' => "{$availableCount}/{$totalSlots} tersedia",
                    'start' => $dateStr,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'textColor' => '#ffffff',
                    'allDay' => true,
                    'extendedProps' => [
                        'type' => 'schedule',
                        'date' => $dateStr,
                        'available_slots' => $availableCount,
                        'total_slots' => $totalSlots,
                        'visits_count' => $visitsCount
                    ]
                ];
            }

            $startDate->addDay();
        }

        return response()->json($events);
    }

    /**
     * Copy schedule from one date to another
     */
    public function copySchedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'source_date' => 'required|date',
            'target_dates' => 'required|array',
            'target_dates.*' => 'date',
            'overwrite' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $sourceDate = $request->input('source_date');
            $targetDates = $request->input('target_dates');
            $overwrite = $request->input('overwrite', false);

            // Get source schedule
            $sourceSchedules = ScheduleAvailability::forDate($sourceDate)->get();

            if ($sourceSchedules->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada pengaturan jadwal pada tanggal sumber.'
                ], 422);
            }

            $copiedCount = 0;
            $skippedCount = 0;

            foreach ($targetDates as $targetDate) {
                $targetCarbon = Carbon::parse($targetDate);

                // Skip weekends
                if ($targetCarbon->isWeekend()) {
                    $skippedCount++;
                    continue;
                }

                foreach ($sourceSchedules as $sourceSchedule) {
                    // Check if target already has setting
                    $existing = ScheduleAvailability::forDate($targetDate)
                                                   ->where('time_slot', $sourceSchedule->time_slot)
                                                   ->first();

                    if ($existing && !$overwrite) {
                        continue;
                    }

                    // Check if there's existing visit at target time (only when setting to unavailable)
                    if (!$sourceSchedule->is_available) {
                        $existingVisit = Kunjungan::where('tanggal_kunjungan', $targetDate)
                                                 ->where('waktu_kunjungan', $sourceSchedule->time_slot)
                                                 ->whereIn('status', [Kunjungan::STATUS_PENDING, Kunjungan::STATUS_PROCESSING])
                                                 ->exists();

                        if ($existingVisit) {
                            continue;
                        }
                    }

                    ScheduleAvailability::setSlotAvailability(
                        $targetDate,
                        $sourceSchedule->time_slot,
                        $sourceSchedule->is_available,
                        $sourceSchedule->reason,
                        $sourceSchedule->notes
                    );
                }

                $copiedCount++;
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil menyalin jadwal ke {$copiedCount} tanggal. {$skippedCount} tanggal dilewati.",
                'copied' => $copiedCount,
                'skipped' => $skippedCount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyalin jadwal.'
            ], 500);
        }
    }
}
