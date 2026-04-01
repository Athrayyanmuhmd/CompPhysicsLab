<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Kunjungan extends Model
{
    use HasFactory;

    protected $table = 'kunjungan';
    public $incrementing = false;
    protected $keyType = 'string';

    // Status constants
    const STATUS_PENDING = 'PENDING';
    const STATUS_PROCESSING = 'PROCESSING';
    const STATUS_COMPLETED = 'COMPLETED';
    const STATUS_CANCELLED = 'CANCELLED';

    protected $fillable = [
        'namaPengunjung',
        'email',
        'phone',
        'institution',
        'tujuan',
        'tanggal_kunjungan',
        'waktu_kunjungan',
        'jumlahPengunjung',
        'catatan_tambahan',
        'dokumen_surat',
        'status',
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
        'waktu_kunjungan' => 'datetime:H:i',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });

        // Delete file when model is deleted
        static::deleting(function ($model) {
            if ($model->dokumen_surat && Storage::disk('public')->exists($model->dokumen_surat)) {
                Storage::disk('public')->delete($model->dokumen_surat);
            }
        });
    }

    // Get document URL
    public function getDocumentUrlAttribute()
    {
        if ($this->dokumen_surat) {
            return Storage::disk('public')->url($this->dokumen_surat);
        }
        return null;
    }

    // Get tracking URL
    public function getTrackingUrlAttribute()
    {
        return route('visit.track', ['id' => $this->id]);
    }

    // Generate WhatsApp message template
    public function getWhatsAppMessageAttribute()
    {
        $message = "*🔬 PENGAJUAN KUNJUNGAN LABORATORIUM FISIKA KOMPUTASI*\n\n";
        $message .= "📋 *INFORMASI PENGUNJUNG*\n";
        $message .= "👤 Nama: {$this->namaPengunjung}\n";
        $message .= "🏢 Institusi: {$this->institution}\n";
        $message .= "📧 Email: {$this->email}\n";
        $message .= "📱 Telepon: {$this->phone}\n\n";

        $message .= "📅 *DETAIL KUNJUNGAN*\n";
        $message .= "🎯 Tujuan: {$this->tujuan}\n";
        $message .= "📆 Tanggal: {$this->formatted_date}\n";
        $message .= "⏰ Waktu: {$this->formatted_time}\n";
        $message .= "👥 Jumlah Peserta: {$this->jumlahPengunjung} orang\n\n";

        if ($this->catatan_tambahan) {
            $message .= "📝 *CATATAN TAMBAHAN*\n";
            $message .= "{$this->catatan_tambahan}\n\n";
        }

        $message .= "🔗 *LINK TRACKING*\n";
        $message .= "{$this->tracking_url}\n\n";

        $message .= "⚠️ *MOHON DIPROSES SEGERA*\n";
        $message .= "Status: MENUNGGU KONFIRMASI\n";
        $message .= "ID Pengajuan: " . substr($this->id, 0, 8);

        return $message;
    }

    // Available time slots (Senin-Jumat, 08:00-18:00 WIB)
    public static function getDefaultTimeSlots()
    {
        return [
            '08:00' => '08:00 - 09:00',
            '09:00' => '09:00 - 10:00',
            '10:00' => '10:00 - 11:00',
            '11:00' => '11:00 - 12:00',
            '13:00' => '13:00 - 14:00', // Break 12:00-13:00 untuk makan siang
            '14:00' => '14:00 - 15:00',
            '15:00' => '15:00 - 16:00',
            '16:00' => '16:00 - 17:00',
            '17:00' => '17:00 - 18:00',
        ];
    }

    // Get available time slots for a specific date
    public static function getAvailableTimeSlots($date)
    {
        $allSlots = self::getDefaultTimeSlots();
        $selectedDate = Carbon::parse($date);

        // Check if it's weekend (Saturday or Sunday)
        if ($selectedDate->isWeekend()) {
            return [];
        }

        // Get booked slots for the date
        $bookedSlots = self::where('tanggal_kunjungan', $date)
                          ->whereIn('status', [self::STATUS_PENDING, self::STATUS_PROCESSING])
                          ->pluck('waktu_kunjungan')
                          ->map(function ($time) {
                              return Carbon::parse($time)->format('H:i');
                          })
                          ->toArray();

        // Remove unavailable slots from available slots
        return array_diff_key($allSlots, array_flip($bookedSlots));
    }

    // Check if a specific time slot is available
 public static function isTimeSlotAvailable($date, $time, $excludeId = null)
{
    $query = self::where('tanggal_kunjungan', $date)
               ->where('waktu_kunjungan', $time)
               ->whereIn('status', [self::STATUS_PENDING, self::STATUS_PROCESSING]);

    if ($excludeId) {
        $query->where('id', '!=', $excludeId);
    }

    return !$query->exists();
}

    // Scope for filtering by date
    public function scopeForDate($query, $date)
    {
        return $query->where('tanggal_kunjungan', $date);
    }

    // Scope for filtering by status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope for filtering by date range
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_kunjungan', [$startDate, $endDate]);
    }

    // Get formatted date
    public function getFormattedDateAttribute()
    {
        return $this->tanggal_kunjungan->format('d M Y');
    }

    // Get formatted time
    public function getFormattedTimeAttribute()
    {
        return Carbon::parse($this->waktu_kunjungan)->format('H:i');
    }

    // Get formatted date time
    public function getFormattedDateTimeAttribute()
    {
        return $this->tanggal_kunjungan->format('d M Y') . ' - ' . $this->getFormattedTimeAttribute();
    }

    // Get status badge color
    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_PROCESSING => 'bg-blue-100 text-blue-800',
            self::STATUS_COMPLETED => 'bg-green-100 text-green-800',
            self::STATUS_CANCELLED => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Get status text
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_PROCESSING => 'Diproses',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
            default => $this->status
        };
    }

    // Get all possible statuses
    public static function getAllStatuses()
    {
        return [
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_PROCESSING => 'Diproses',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
        ];
    }

    // Get visits for calendar (formatted for FullCalendar)
    public static function getCalendarEvents($startDate = null, $endDate = null)
    {
        $query = self::query();

        if ($startDate && $endDate) {
            $query->inDateRange($startDate, $endDate);
        }

        return $query->get()->map(function ($visit) {
            $color = match($visit->status) {
                self::STATUS_PENDING => '#f59e0b',
                self::STATUS_PROCESSING => '#3b82f6',
                self::STATUS_COMPLETED => '#10b981',
                self::STATUS_CANCELLED => '#ef4444',
                default => '#6b7280'
            };

            return [
                'id' => $visit->id,
                'title' => $visit->namaPengunjung . ' (' . $visit->jumlahPengunjung . ' orang)',
                'start' => $visit->tanggal_kunjungan->format('Y-m-d') . 'T' . $visit->getFormattedTimeAttribute(),
                'end' => $visit->tanggal_kunjungan->format('Y-m-d') . 'T' . Carbon::parse($visit->waktu_kunjungan)->addHour()->format('H:i'),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'institution' => $visit->institution,
                    'email' => $visit->email,
                    'phone' => $visit->phone,
                    'tujuan' => $visit->tujuan,
                    'status' => $visit->status,
                    'statusText' => $visit->statusText,
                    'participants' => $visit->jumlahPengunjung,
                    'notes' => $visit->catatan_tambahan,
                    'document' => $visit->document_url,
                ]
            ];
        });
    }

    // Get statistics
    public static function getStatistics($startDate = null, $endDate = null)
    {
        $query = self::query();

        if ($startDate && $endDate) {
            $query->inDateRange($startDate, $endDate);
        }

        $total = $query->count();
        $pending = $query->clone()->byStatus(self::STATUS_PENDING)->count();
        $processing = $query->clone()->byStatus(self::STATUS_PROCESSING)->count();
        $completed = $query->clone()->byStatus(self::STATUS_COMPLETED)->count();
        $cancelled = $query->clone()->byStatus(self::STATUS_CANCELLED)->count();

        return [
            'total' => $total,
            'pending' => $pending,
            'processing' => $processing,
            'completed' => $completed,
            'cancelled' => $cancelled,
            'totalParticipants' => $query->sum('jumlahPengunjung'),
        ];
    }
}
