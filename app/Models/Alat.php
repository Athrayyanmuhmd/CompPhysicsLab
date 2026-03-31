<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Log;

class Alat extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'alat';

    protected $fillable = [
        'nama',
        'kode',
        'deskripsi',
        'image_url',
        'jumlah_tersedia',
        'jumlah_dipinjam',
        'jumlah_rusak',
        'nama_kategori',
        'stok',
        'harga'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'jumlah_tersedia' => 'integer',
        'jumlah_dipinjam' => 'integer',
        'jumlah_rusak' => 'integer',
        'stok' => 'integer'
    ];

    /**
     * Relationship dengan KategoriAlat
     */
    public function kategoriAlat()
    {
        return $this->belongsTo(KategoriAlat::class, 'nama_kategori', 'nama_kategori');
    }

    /**
     * Scope untuk pencarian alat
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('kode', 'like', '%' . $search . '%')
                        ->orWhere('deskripsi', 'like', '%' . $search . '%');
        }
        return $query;
    }

    /**
     * Scope untuk filter berdasarkan kategori
     */
    public function scopeByKategori($query, $kategori)
    {
        if ($kategori) {
            return $query->where('nama_kategori', $kategori);
        }
        return $query;
    }

    /**
     * Scope untuk alat yang tersedia
     */
    public function scopeAvailable($query)
    {
        return $query->where('jumlah_tersedia', '>', 0);
    }

    /**
     * Scope for filtering by status - FIXED VERSION
     */
    public function scopeByStatus($query, $status)
    {
        return match($status) {
            'available' => $query->where('jumlah_tersedia', '>', 0),
            'borrowed' => $query->where('jumlah_dipinjam', '>', 0),
            'maintenance' => $query->where('jumlah_rusak', '>', 0)
                                  ->where('jumlah_tersedia', '=', 0)
                                  ->where('jumlah_dipinjam', '=', 0), // Hanya maintenance jika SEMUA rusak
            'unavailable' => $query->where('jumlah_tersedia', '=', 0)
                                  ->where('jumlah_dipinjam', '=', 0)
                                  ->where('jumlah_rusak', '=', 0), // Tidak ada stok sama sekali
            default => $query
        };
    }

    /**
     * Accessor untuk mendapatkan URL gambar yang benar
     * Menangani path lama (public/images/equipment/) dan baru (storage/equipment/)
     */
    public function getImageAssetAttribute()
    {
        if (empty($this->image_url)) {
            return asset('images/equipment/default.jpg');
        }

        // Uploaded via Storage disk (e.g., 'equipment/filename.jpg')
        if (str_starts_with($this->image_url, 'equipment/')) {
            return asset('storage/' . $this->image_url);
        }

        // Seeder/legacy data (just filename, e.g., 'oscilloscope.jpeg')
        return asset('images/equipment/' . $this->image_url);
    }

    /**
     * Accessor untuk total alat
     */
    public function getTotalAlatAttribute()
    {
        return $this->jumlah_tersedia + $this->jumlah_dipinjam + $this->jumlah_rusak;
    }

    /**
     * Accessor untuk status ketersediaan
     */
    public function getStatusKetersediaanAttribute()
    {
        if ($this->jumlah_tersedia > 0) {
            return 'Tersedia';
        } elseif ($this->jumlah_dipinjam > 0) {
            return 'Dipinjam';
        } else {
            return 'Tidak Tersedia';
        }
    }

    /**
     * Get status for filter purposes - FIXED VERSION
     * Prioritas status: Tersedia > Dipinjam > Maintenance > Unavailable
     */
    public function getStatusForFilter()
    {
        // Jika ada yang tersedia, status = available (meskipun ada yang rusak/dipinjam)
        if ($this->jumlah_tersedia > 0) {
            return 'available';
        }

        // Jika tidak ada yang tersedia tapi ada yang dipinjam, status = borrowed
        if ($this->jumlah_dipinjam > 0) {
            return 'borrowed';
        }

        // Jika tidak ada yang tersedia dan tidak ada yang dipinjam, tapi ada yang rusak = maintenance
        if ($this->jumlah_rusak > 0) {
            return 'maintenance';
        }

        // Jika tidak ada stok sama sekali = unavailable
        return 'unavailable';
    }

    /**
     * Get human-readable status label - UPDATED
     */
    public function getStatusLabel()
    {
        return match($this->getStatusForFilter()) {
            'available' => 'Tersedia',
            'borrowed' => 'Dipinjam',
            'maintenance' => 'Maintenance',
            'unavailable' => 'Tidak Tersedia',
            default => 'Unknown'
        };
    }

    /**
     * Get status icon for display
     */
    public function getStatusIcon()
    {
        return match($this->getStatusForFilter()) {
            'available' => 'check-circle',
            'borrowed' => 'hand-holding',
            'maintenance' => 'wrench',
            'unavailable' => 'times-circle',
            default => 'question-circle'
        };
    }

    /**
     * Get detailed status information for display
     */
    public function getDetailedStatus()
    {
        $status = [];

        if ($this->jumlah_tersedia > 0) {
            $status[] = "{$this->jumlah_tersedia} tersedia";
        }

        if ($this->jumlah_dipinjam > 0) {
            $status[] = "{$this->jumlah_dipinjam} dipinjam";
        }

        if ($this->jumlah_rusak > 0) {
            $status[] = "{$this->jumlah_rusak} rusak";
        }

        return implode(', ', $status) ?: 'Tidak ada stok';
    }

    /**
     * Get category icon based on category name
     */
    public function getCategoryIcon()
    {
        return match($this->nama_kategori) {
            'Elektronika' => 'microchip',
            'Pengukuran' => 'ruler',
            'Generator' => 'bolt',
            'Power' => 'battery-full',
            'Analisis' => 'chart-line',
            'Optik' => 'eye',
            'Mekanik' => 'cog',
            'Thermal' => 'thermometer-half',
            default => 'tools'
        };
    }

    /**
     * Check if alat dapat dipinjam
     */
    public function canBeBorrowed($jumlah = 1)
    {
        return $this->jumlah_tersedia >= $jumlah;
    }

    /**
     * Get available quantity for borrowing
     */
    public function getAvailableQuantity()
    {
        return $this->jumlah_tersedia;
    }

    /**
     * Check if item has any stock available (not counting damaged items)
     */
    public function hasAvailableStock()
    {
        return $this->jumlah_tersedia > 0;
    }

    /**
     * Get borrowing status with detailed message
     */
    public function getBorrowingStatus()
    {
        if ($this->jumlah_tersedia > 0) {
            return [
                'can_borrow' => true,
                'message' => "Tersedia {$this->jumlah_tersedia} unit untuk dipinjam",
                'available_quantity' => $this->jumlah_tersedia
            ];
        }

        if ($this->jumlah_dipinjam > 0 && $this->jumlah_rusak == 0) {
            return [
                'can_borrow' => false,
                'message' => "Semua unit sedang dipinjam ({$this->jumlah_dipinjam} unit)",
                'available_quantity' => 0
            ];
        }

        if ($this->jumlah_rusak > 0 && $this->jumlah_dipinjam == 0) {
            return [
                'can_borrow' => false,
                'message' => "Semua unit sedang maintenance ({$this->jumlah_rusak} unit rusak)",
                'available_quantity' => 0
            ];
        }

        if ($this->jumlah_dipinjam > 0 && $this->jumlah_rusak > 0) {
            return [
                'can_borrow' => false,
                'message' => "{$this->jumlah_dipinjam} unit dipinjam, {$this->jumlah_rusak} unit rusak",
                'available_quantity' => 0
            ];
        }

        return [
            'can_borrow' => false,
            'message' => "Tidak ada stok tersedia",
            'available_quantity' => 0
        ];
    }

    /**
     * Kurangi stok saat dipinjam
     */
    public function pinjam($jumlah = 1)
    {
        if ($this->canBeBorrowed($jumlah)) {
            $this->jumlah_tersedia -= $jumlah;
            $this->jumlah_dipinjam += $jumlah;

            Log::info("Alat dipinjam", [
                'alat_id' => $this->id,
                'alat_nama' => $this->nama,
                'jumlah_dipinjam' => $jumlah,
                'stok_tersisa' => $this->jumlah_tersedia,
                'total_dipinjam' => $this->jumlah_dipinjam
            ]);

            return $this->save();
        }

        Log::warning("Gagal meminjam alat - stok tidak cukup", [
            'alat_id' => $this->id,
            'alat_nama' => $this->nama,
            'jumlah_diminta' => $jumlah,
            'jumlah_tersedia' => $this->jumlah_tersedia
        ]);

        return false;
    }

    /**
     * Kembalikan stok saat dikembalikan (method lama untuk compatibility)
     */
    public function kembalikan($jumlah = 1)
    {
        if ($this->jumlah_dipinjam >= $jumlah) {
            $this->jumlah_dipinjam -= $jumlah;
            $this->jumlah_tersedia += $jumlah;

            Log::info("Alat dikembalikan (method lama)", [
                'alat_id' => $this->id,
                'alat_nama' => $this->nama,
                'jumlah_dikembalikan' => $jumlah,
                'kondisi' => 'baik (default)'
            ]);

            return $this->save();
        }
        return false;
    }

    /**
     * Tandai alat sebagai rusak
     */
    public function tandaiRusak($jumlah = 1, $dari = 'tersedia')
    {
        if ($dari === 'tersedia' && $this->jumlah_tersedia >= $jumlah) {
            $this->jumlah_tersedia -= $jumlah;
            $this->jumlah_rusak += $jumlah;

            Log::info("Alat ditandai rusak dari stok tersedia", [
                'alat_id' => $this->id,
                'alat_nama' => $this->nama,
                'jumlah' => $jumlah
            ]);

            return $this->save();
        } elseif ($dari === 'dipinjam' && $this->jumlah_dipinjam >= $jumlah) {
            $this->jumlah_dipinjam -= $jumlah;
            $this->jumlah_rusak += $jumlah;

            Log::info("Alat ditandai rusak dari stok dipinjam", [
                'alat_id' => $this->id,
                'alat_nama' => $this->nama,
                'jumlah' => $jumlah
            ]);

            return $this->save();
        }
        return false;
    }

    /**
     * Return item with condition check (method lama - tetap dipertahankan)
     */
    public function returnItem($jumlah = 1, $kondisi = 'baik')
    {
        // Validasi input
        if ($this->jumlah_dipinjam < $jumlah) {
            Log::warning("Attempt to return more items than borrowed", [
                'alat_id' => $this->id,
                'alat_nama' => $this->nama,
                'jumlah_dipinjam' => $this->jumlah_dipinjam,
                'jumlah_dikembalikan' => $jumlah
            ]);
            return false;
        }

        if (!in_array($kondisi, ['baik', 'rusak'])) {
            Log::warning("Invalid condition for return", [
                'alat_id' => $this->id,
                'kondisi' => $kondisi,
                'valid_conditions' => ['baik', 'rusak']
            ]);
            $kondisi = 'baik';
        }

        // Update stok
        $this->jumlah_dipinjam -= $jumlah;

        if ($kondisi === 'baik') {
            $this->jumlah_tersedia += $jumlah;
        } else { // rusak
            $this->jumlah_rusak += $jumlah;
        }

        // Validasi konsistensi stok
        if (!$this->validateStockConsistency()) {
            Log::error("Stock consistency validation failed", [
                'alat_id' => $this->id,
                'stok_total' => $this->stok,
                'calculated_total' => $this->jumlah_tersedia + $this->jumlah_dipinjam + $this->jumlah_rusak
            ]);
            return false;
        }

        Log::info("Alat dikembalikan", [
            'alat_id' => $this->id,
            'alat_nama' => $this->nama,
            'jumlah' => $jumlah,
            'kondisi' => $kondisi,
            'stok_baru' => [
                'tersedia' => $this->jumlah_tersedia,
                'dipinjam' => $this->jumlah_dipinjam,
                'rusak' => $this->jumlah_rusak
            ]
        ]);

        return $this->save();
    }

    /**
     * NEW: Return items with partial conditions
     * @param int $baikQty - Jumlah dalam kondisi baik
     * @param int $rusakQty - Jumlah dalam kondisi rusak
     * @return bool
     */
    public function returnItemPartial($baikQty = 0, $rusakQty = 0)
    {
        $totalReturned = $baikQty + $rusakQty;

        // Validasi: pastikan ada cukup item yang sedang dipinjam
        if ($this->jumlah_dipinjam < $totalReturned) {
            Log::warning("Attempt to return more items than borrowed (partial)", [
                'alat_id' => $this->id,
                'alat_nama' => $this->nama,
                'jumlah_dipinjam' => $this->jumlah_dipinjam,
                'total_dikembalikan' => $totalReturned,
                'baik' => $baikQty,
                'rusak' => $rusakQty
            ]);
            return false;
        }

        // Validasi input tidak negatif
        if ($baikQty < 0 || $rusakQty < 0) {
            Log::warning("Negative quantities not allowed", [
                'alat_id' => $this->id,
                'baik' => $baikQty,
                'rusak' => $rusakQty
            ]);
            return false;
        }

        // Store original values untuk rollback jika perlu
        $originalTersedia = $this->jumlah_tersedia;
        $originalDipinjam = $this->jumlah_dipinjam;
        $originalRusak = $this->jumlah_rusak;

        // Kurangi dari jumlah yang dipinjam
        $this->jumlah_dipinjam -= $totalReturned;

        // Update stok berdasarkan kondisi
        if ($baikQty > 0) {
            $this->jumlah_tersedia += $baikQty;
        }

        if ($rusakQty > 0) {
            $this->jumlah_rusak += $rusakQty;
        }

        // Validasi konsistensi stok
        if (!$this->validateStockConsistency()) {
            // Rollback ke nilai original
            $this->jumlah_tersedia = $originalTersedia;
            $this->jumlah_dipinjam = $originalDipinjam;
            $this->jumlah_rusak = $originalRusak;

            Log::error("Stock consistency validation failed (partial return)", [
                'alat_id' => $this->id,
                'attempted_changes' => [
                    'baik' => $baikQty,
                    'rusak' => $rusakQty,
                    'total' => $totalReturned
                ]
            ]);
            return false;
        }

        Log::info("Partial return completed successfully", [
            'alat_id' => $this->id,
            'alat_nama' => $this->nama,
            'baik_dikembalikan' => $baikQty,
            'rusak_dikembalikan' => $rusakQty,
            'total_dikembalikan' => $totalReturned,
            'stok_setelah_return' => [
                'tersedia' => $this->jumlah_tersedia,
                'dipinjam' => $this->jumlah_dipinjam,
                'rusak' => $this->jumlah_rusak,
                'total' => $this->total_alat
            ]
        ]);

        return $this->save();
    }

    /**
     * NEW: Validate stock consistency
     * Memastikan total stok tidak melebihi stok yang seharusnya
     */
    private function validateStockConsistency()
    {
        $calculatedTotal = $this->jumlah_tersedia + $this->jumlah_dipinjam + $this->jumlah_rusak;

        // Total tidak boleh melebihi stok yang ditetapkan
        if ($calculatedTotal > $this->stok) {
            return false;
        }

        // Semua nilai harus non-negatif
        if ($this->jumlah_tersedia < 0 || $this->jumlah_dipinjam < 0 || $this->jumlah_rusak < 0) {
            return false;
        }

        return true;
    }

    /**
     * NEW: Get return condition options for UI
     */
    public static function getReturnConditionOptions()
    {
        return [
            'baik' => [
                'label' => 'Baik',
                'description' => 'Kondisi normal, dapat digunakan kembali',
                'icon' => 'check-circle',
                'color' => 'green'
            ],
            'rusak' => [
                'label' => 'Rusak',
                'description' => 'Perlu perbaikan atau penggantian',
                'icon' => 'exclamation-triangle',
                'color' => 'red'
            ]
        ];
    }

    /**
     * NEW: Get condition label for display
     */
    public static function getConditionLabel($condition)
    {
        $options = self::getReturnConditionOptions();
        return $options[$condition]['label'] ?? 'Unknown';
    }

    /**
     * Borrow method (alias for pinjam for compatibility)
     */
    public function borrow($jumlah = 1)
    {
        return $this->pinjam($jumlah);
    }

    /**
     * Repair damaged items
     */
    public function repairItems($jumlah = 1)
    {
        if ($this->jumlah_rusak >= $jumlah) {
            $this->jumlah_rusak -= $jumlah;
            $this->jumlah_tersedia += $jumlah;

            Log::info("Alat berhasil diperbaiki", [
                'alat_id' => $this->id,
                'alat_nama' => $this->nama,
                'jumlah_diperbaiki' => $jumlah,
                'jumlah_rusak_tersisa' => $this->jumlah_rusak,
                'jumlah_tersedia_baru' => $this->jumlah_tersedia
            ]);

            return $this->save();
        }

        Log::warning("Tidak cukup alat rusak untuk diperbaiki", [
            'alat_id' => $this->id,
            'jumlah_rusak' => $this->jumlah_rusak,
            'jumlah_yang_akan_diperbaiki' => $jumlah
        ]);

        return false;
    }

    /**
     * NEW: Write off damaged items - permanently remove from stock
     */
    public function writeOffDamagedItems($jumlah = 1)
    {
        if ($this->jumlah_rusak >= $jumlah) {
            $this->jumlah_rusak -= $jumlah;
            $this->stok -= $jumlah; // Permanent removal from total stock

            Log::info("Alat rusak dihapus permanent dari inventory", [
                'alat_id' => $this->id,
                'alat_nama' => $this->nama,
                'jumlah_dihapus' => $jumlah,
                'stok_baru' => $this->stok,
                'jumlah_rusak_tersisa' => $this->jumlah_rusak
            ]);

            return $this->save();
        }

        return false;
    }

    /**
     * Get stock percentage breakdown
     */
    public function getStockBreakdown()
    {
        $total = $this->stok;

        if ($total == 0) {
            return [
                'available' => 0,
                'borrowed' => 0,
                'damaged' => 0
            ];
        }

        return [
            'available' => round(($this->jumlah_tersedia / $total) * 100, 1),
            'borrowed' => round(($this->jumlah_dipinjam / $total) * 100, 1),
            'damaged' => round(($this->jumlah_rusak / $total) * 100, 1)
        ];
    }

    /**
     * NEW: Get detailed breakdown of item conditions
     */
    public function getConditionBreakdown()
    {
        return [
            'baik' => [
                'jumlah' => $this->jumlah_tersedia,
                'percentage' => $this->stok > 0 ? round(($this->jumlah_tersedia / $this->stok) * 100, 1) : 0,
                'status' => 'available'
            ],
            'dipinjam' => [
                'jumlah' => $this->jumlah_dipinjam,
                'percentage' => $this->stok > 0 ? round(($this->jumlah_dipinjam / $this->stok) * 100, 1) : 0,
                'status' => 'borrowed'
            ],
            'rusak' => [
                'jumlah' => $this->jumlah_rusak,
                'percentage' => $this->stok > 0 ? round(($this->jumlah_rusak / $this->stok) * 100, 1) : 0,
                'status' => 'damaged'
            ]
        ];
    }

    /**
     * NEW: Get stock health status
     */
    public function getStockHealth()
    {
        $breakdown = $this->getConditionBreakdown();

        $health = 'excellent';
        if ($breakdown['rusak']['percentage'] > 20) {
            $health = 'poor';
        } elseif ($breakdown['rusak']['percentage'] > 10) {
            $health = 'fair';
        } elseif ($breakdown['rusak']['percentage'] > 5) {
            $health = 'good';
        }

        return [
            'status' => $health,
            'damaged_percentage' => $breakdown['rusak']['percentage'],
            'available_percentage' => $breakdown['baik']['percentage'],
            'utilization_rate' => $breakdown['dipinjam']['percentage'],
            'recommendations' => $this->getHealthRecommendations($health, $breakdown)
        ];
    }

    /**
     * NEW: Get health recommendations based on stock condition
     */
    private function getHealthRecommendations($health, $breakdown)
    {
        $recommendations = [];

        if ($health === 'poor') {
            $recommendations[] = 'Segera lakukan maintenance pada alat rusak';
            $recommendations[] = 'Pertimbangkan untuk mengganti alat yang tidak dapat diperbaiki';
        }

        if ($breakdown['baik']['percentage'] < 30) {
            $recommendations[] = 'Stok tersedia rendah, pertimbangkan untuk menambah inventori';
        }

        if ($breakdown['dipinjam']['percentage'] > 80) {
            $recommendations[] = 'Tingkat peminjaman tinggi, monitor jadwal pengembalian';
        }

        return $recommendations;
    }

    /**
     * NEW: Batch update stock - useful for inventory adjustments
     */
    public function batchUpdateStock(array $updates)
    {
        $oldValues = [
            'tersedia' => $this->jumlah_tersedia,
            'dipinjam' => $this->jumlah_dipinjam,
            'rusak' => $this->jumlah_rusak
        ];

        // Apply updates
        if (isset($updates['tersedia'])) {
            $this->jumlah_tersedia = max(0, (int)$updates['tersedia']);
        }
        if (isset($updates['dipinjam'])) {
            $this->jumlah_dipinjam = max(0, (int)$updates['dipinjam']);
        }
        if (isset($updates['rusak'])) {
            $this->jumlah_rusak = max(0, (int)$updates['rusak']);
        }

        // Validate consistency
        if (!$this->validateStockConsistency()) {
            // Rollback to old values
            $this->jumlah_tersedia = $oldValues['tersedia'];
            $this->jumlah_dipinjam = $oldValues['dipinjam'];
            $this->jumlah_rusak = $oldValues['rusak'];

            Log::error("Batch stock update failed - consistency check", [
                'alat_id' => $this->id,
                'attempted_updates' => $updates,
                'reverted_to' => $oldValues
            ]);

            return false;
        }

        Log::info("Batch stock update successful", [
            'alat_id' => $this->id,
            'alat_nama' => $this->nama,
            'old_values' => $oldValues,
            'new_values' => [
                'tersedia' => $this->jumlah_tersedia,
                'dipinjam' => $this->jumlah_dipinjam,
                'rusak' => $this->jumlah_rusak
            ]
        ]);

        return $this->save();
    }
}
