<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\KategoriAlat;
use App\Models\Peminjaman;
use App\Models\PeminjamanItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class EquipmentLoanController extends Controller
{
    /**
     * Display equipment loan page with filters
     */
    public function index()
    {
        // Get equipment with search and filter
        $search = request('search');
        $kategori = request('kategori', 'all');
        $status = request('status', 'all');

        $equipments = Alat::with('kategoriAlat')
                         ->search($search)
                         ->when($kategori !== 'all', function($query) use ($kategori) {
                             return $query->byKategori($kategori);
                         })
                         ->when($status !== 'all', function($query) use ($status) {
                             return $query->byStatus($status); // Use the fixed byStatus scope
                         })
                         ->orderBy('nama')
                         ->get()
                         ->map(function($alat) {
                             return $this->formatEquipmentForView($alat);
                         });

        // Get categories from database
        $categories = ['all' => 'Semua Kategori'];
        $dbCategories = KategoriAlat::orderBy('nama_kategori')->get();
        foreach ($dbCategories as $cat) {
            $categories[$cat->nama_kategori] = $cat->nama_kategori;
        }

        return view('services.equipment-loan', compact('equipments', 'categories'));
    }

    /**
     * Show equipment detail
     */
    public function show($id)
    {
        $alat = Alat::with('kategoriAlat')->find($id);

        if (!$alat) {
            abort(404, 'Alat tidak ditemukan');
        }

        $equipment = $this->formatEquipmentForView($alat);

        return view('services.equipment-detail', compact('equipment'));
    }

    /**
     * Process loan request with WhatsApp integration
     */
    public function requestLoan(Request $request)
    {
        // Check if request is AJAX
        $isAjax = $request->ajax() || $request->wantsJson();

        // Basic validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'student_id' => 'required|string|max:20',
            'email' => 'required|email',
            'phone' => 'required|string|max:15',
            'purpose' => 'required|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'borrower_type' => 'required|in:mahasiswa_usk,eksternal',
        ], [
            'name.required' => 'Nama lengkap wajib diisi',
            'student_id.required' => 'NIM/NIP wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'phone.required' => 'Nomor telepon wajib diisi',
            'purpose.required' => 'Tujuan penggunaan wajib diisi',
            'start_date.required' => 'Tanggal mulai wajib diisi',
            'start_date.after_or_equal' => 'Tanggal mulai tidak boleh di masa lalu',
            'end_date.required' => 'Tanggal selesai wajib diisi',
            'end_date.after' => 'Tanggal selesai harus setelah tanggal mulai',
            'borrower_type.required' => 'Status peminjam wajib dipilih',
            'instansi.required_if' => 'Instansi wajib diisi untuk peminjam eksternal'
        ]);

        if ($validator->fails()) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terdapat kesalahan dalam pengisian form.',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam pengisian form. Silakan periksa kembali.');
        }

        // Check if this is a bulk request
        if ($request->has('equipment_ids') && $request->has('equipment_quantities')) {
            return $this->processBulkLoan($request, $isAjax);
        }

        if ($isAjax) {
            return response()->json([
                'success' => false,
                'message' => 'Data alat tidak valid.'
            ], 422);
        }

        return redirect()->back()->withErrors(['equipment' => 'Data alat tidak valid.']);
    }

    /**
     * Process bulk loan request
     */
    private function processBulkLoan(Request $request, $isAjax = false)
    {
        // Additional validation for bulk requests
        $bulkValidator = Validator::make($request->all(), [
            'equipment_ids' => 'required|string',
            'equipment_quantities' => 'required|string'
        ]);

        if ($bulkValidator->fails()) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data alat tidak valid.',
                    'errors' => $bulkValidator->errors()
                ], 422);
            }

            return redirect()->back()->withErrors(['equipment_ids' => 'Data alat tidak valid.']);
        }

        try {
            // Parse equipment data
            $equipmentIds = json_decode($request->equipment_ids, true);
            $equipmentQuantities = json_decode($request->equipment_quantities, true);

            if (!is_array($equipmentIds) || empty($equipmentIds) || !is_array($equipmentQuantities)) {
                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data alat tidak valid.'
                    ], 422);
                }

                return redirect()->back()->withErrors(['equipment_ids' => 'Data alat tidak valid.']);
            }

            DB::beginTransaction();

            // Validate all equipment availability
            $unavailableEquipment = [];
            $totalUnits = 0;
            $equipmentDetails = [];

            foreach ($equipmentIds as $alatId) {
                $alat = Alat::find($alatId);
                if (!$alat) {
                    $unavailableEquipment[] = "Alat dengan ID {$alatId} tidak ditemukan";
                    continue;
                }

                $requestedQuantity = $equipmentQuantities[$alatId] ?? 1;
                $totalUnits += $requestedQuantity;

                // Check if equipment can be borrowed
                if (!$alat->canBeBorrowed($requestedQuantity)) {
                    $unavailableEquipment[] = "{$alat->nama}: Diminta {$requestedQuantity} unit, tersedia {$alat->jumlah_tersedia} unit";
                } else {
                    $equipmentDetails[] = [
                        'alat' => $alat,
                        'quantity' => $requestedQuantity
                    ];
                }
            }

            if (!empty($unavailableEquipment)) {
                DB::rollBack();

                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => "Ketersediaan tidak mencukupi:\n" . implode("\n", $unavailableEquipment)
                    ], 422);
                }

                return redirect()->back()->withErrors([
                    'availability' => "Ketersediaan tidak mencukupi:\n" . implode("\n", $unavailableEquipment)
                ])->withInput();
            }

            // Create peminjaman record
            $peminjaman = Peminjaman::create([
                'namaPeminjam' => $request->name,
                'student_id' => $request->student_id,
                'email' => $request->email,
                'noHp' => $request->phone,
                'instansi' => $request->borrower_type === 'eksternal' ? $request->instansi : null,
                'tujuanPeminjaman' => $request->purpose,
                'tanggal_pinjam' => $request->start_date,
                'tanggal_pengembalian' => $request->end_date,
                'status' => Peminjaman::STATUS_PENDING,
                'is_mahasiswa_usk' => $request->borrower_type === 'mahasiswa_usk'
            ]);

            // Create peminjaman items
            foreach ($equipmentDetails as $detail) {
                PeminjamanItem::create([
                    'peminjamanId' => $peminjaman->id,
                    'alat_id' => $detail['alat']->id,
                    'jumlah' => $detail['quantity']
                ]);
            }

            DB::commit();

            // Generate reference number and summary
            $referenceNumber = $peminjaman->reference_number;
            $trackingUrl = route('equipment.track', ['id' => $peminjaman->id]);
            $equipmentSummary = collect($equipmentDetails)->map(function($detail) {
                return "{$detail['alat']->nama} ({$detail['quantity']} unit)";
            })->implode(', ');

            // Log the request
            Log::info('Equipment Loan Request Created', [
                'peminjaman_id' => $peminjaman->id,
                'reference_number' => $referenceNumber,
                'borrower' => $request->name,
                'equipment_count' => count($equipmentDetails),
                'total_units' => $totalUnits
            ]);

            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'message' => 'Permintaan peminjaman berhasil disimpan!',
                    'reference_id' => $referenceNumber,
                    'tracking_url' => $trackingUrl,
                    'loan_data' => [
                        'id' => $peminjaman->id,
                        'reference_number' => $referenceNumber,
                        'name' => $peminjaman->namaPeminjam,
                        'student_id' => $peminjaman->student_id,
                        'instansi' => $peminjaman->instansi,
                        'email' => $peminjaman->email,
                        'phone' => $peminjaman->noHp,
                        'start_date' => $peminjaman->tanggal_pinjam->format('d M Y'),
                        'end_date' => $peminjaman->tanggal_pengembalian->format('d M Y'),
                        'equipment_count' => count($equipmentDetails),
                        'total_units' => $totalUnits,
                        'equipment_summary' => $equipmentSummary,
                        'purpose' => $peminjaman->tujuanPeminjaman,
                        'is_mahasiswa_usk' => $peminjaman->is_mahasiswa_usk,
                        'created_at' => $peminjaman->created_at->format('d M Y H:i')
                    ]
                ]);
            }

            return redirect()->back()->with('success',
                "Permintaan peminjaman berhasil dikirim!\n\n" .
                "Detail:\n" .
                "- Nomor Referensi: {$referenceNumber}\n" .
                "- " . count($equipmentDetails) . " jenis alat\n" .
                "- {$totalUnits} unit total\n" .
                "- Periode: " . Carbon::parse($request->start_date)->format('d M Y') . " - " . Carbon::parse($request->end_date)->format('d M Y') . "\n" .
                "- Alat: {$equipmentSummary}\n\n" .
                "Kami akan menghubungi Anda dalam 1x24 jam untuk konfirmasi dan penjadwalan briefing.\n\n" .
                "Link tracking: {$trackingUrl}"
            );

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Equipment Loan Request Failed', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi admin.'
                ], 500);
            }

            return redirect()->back()
                ->withErrors(['system' => 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi admin.'])
                ->withInput();
        }
    }

    /**
     * Track loan status
     */
    public function track($id)
    {
        try {
            $peminjaman = Peminjaman::with(['items.alat'])->findOrFail($id);
            return view('services.equipment-tracking', compact('peminjaman'));
        } catch (\Exception $e) {
            abort(404, 'Peminjaman tidak ditemukan');
        }
    }

    // =====================================
    // PRIVATE METHODS
    // =====================================

    /**
     * Format equipment data for view - FIXED VERSION
     */
    private function formatEquipmentForView($alat)
    {
        // Use the model's fixed getStatusForFilter method for consistency
        $status = $alat->getStatusForFilter();

        return [
            'id' => $alat->id,
            'name' => $alat->nama,
            'model' => $alat->kode,
            'image' => $this->getEquipmentImageUrl($alat->image_url),
            'category' => $alat->nama_kategori,
            'status' => $status,
            'quantity_total' => $alat->stok,
            'quantity_available' => $alat->jumlah_tersedia,
            'quantity_borrowed' => $alat->jumlah_dipinjam,
            'quantity_damaged' => $alat->jumlah_rusak,
            'description' => $alat->deskripsi,
            'specifications' => $this->extractSpecifications($alat->deskripsi),
            'loan_duration' => '1-7 hari',
            'icon' => $this->getCategoryIcon($alat->nama_kategori),
            'price' => $alat->harga,
            'detailed_status' => method_exists($alat, 'getDetailedStatus') ? $alat->getDetailedStatus() : null,
            'borrowing_status' => method_exists($alat, 'getBorrowingStatus') ? $alat->getBorrowingStatus() : null,
        ];
    }

    /**
     * Get proper image URL for equipment
     * Handles both old (public/images/equipment/) and new (storage/equipment/) paths
     */
    private function getEquipmentImageUrl($imageUrl)
    {
        if (empty($imageUrl)) {
            return asset('images/equipment/default.jpg');
        }

        // If stored via Storage disk (e.g., 'equipment/filename.jpg')
        if (str_starts_with($imageUrl, 'equipment/')) {
            return asset('storage/' . $imageUrl);
        }

        // Old seeder data (just filename, e.g., 'oscilloscope.jpeg')
        return asset('images/equipment/' . $imageUrl);
    }

    /**
     * Extract specifications from description
     */
    private function extractSpecifications($description)
    {
        $specs = [];

        if (strpos($description, 'Spesifikasi:') !== false) {
            $parts = explode('Spesifikasi:', $description);
            if (count($parts) > 1) {
                $specText = trim($parts[1]);
                $specs = array_map('trim', explode(',', $specText));
                $specs = array_filter($specs);
            }
        }

        return $specs;
    }

    /**
     * Get category icon
     */
    private function getCategoryIcon($category)
    {
        $icons = [
            'Elektronika' => 'fas fa-microchip',
            'Pengukuran' => 'fas fa-ruler',
            'Generator' => 'fas fa-bolt',
            'Power' => 'fas fa-battery-full',
            'Analisis' => 'fas fa-chart-line',
            'Optik' => 'fas fa-eye',
            'Mekanik' => 'fas fa-cog',
            'Thermal' => 'fas fa-thermometer-half'
        ];

        return $icons[$category] ?? 'fas fa-cog';
    }
}
