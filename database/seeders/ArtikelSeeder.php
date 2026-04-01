<?php
// database/seeders/ArtikelSeeder.php
// REPLACE isi file ArtikelSeeder.php yang sudah ada dengan code ini

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artikel;
use App\Models\Gambar;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ArtikelSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🚀 Starting ArtikelSeeder...');

        // JANGAN gunakan truncate() - langsung insert saja
        // Karena table sudah fresh dari migrate:fresh

        $artikels = [
            [
                'nama_acara' => 'Pengembangan Sistem Monitoring Seismik Real-time',
                'deskripsi' => 'Laboratorium berhasil mengembangkan sistem monitoring aktivitas seismik yang dapat memberikan peringatan dini dengan akurasi tinggi. Sistem ini menggunakan teknologi sensor terbaru dan algoritma machine learning untuk analisis data seismik. Laboratorium Fisika Komputasi telah berhasil mengembangkan sistem monitoring aktivitas seismik real-time yang revolusioner. Sistem ini menggunakan teknologi sensor terbaru dan algoritma machine learning untuk memberikan peringatan dini gempa bumi dengan akurasi tinggi.',
                'penulis' => 'Dr. Ahmad Rahman',
                'tanggal_acara' => Carbon::now()->subDays(7),
                'image' => 'article/article-1.jpeg'
            ],
            [
                'nama_acara' => 'Inovasi Metode Praktikum Fisika Modern',
                'deskripsi' => 'Penerapan teknologi AR dan VR dalam praktikum fisika modern memberikan pengalaman belajar yang lebih interaktif dan mendalam bagi mahasiswa. Metode ini telah terbukti meningkatkan pemahaman konsep fisika secara signifikan. Dalam era digital ini, pendidikan fisika mengalami transformasi besar dengan penerapan teknologi Augmented Reality (AR) dan Virtual Reality (VR) dalam praktikum fisika modern.',
                'penulis' => 'Prof. Siti Nurhaliza',
                'tanggal_acara' => Carbon::now()->subDays(10),
                'image' => 'article/article-2.jpg'
            ],
            [
                'nama_acara' => 'Kerjasama Penelitian dengan Universitas Tokyo',
                'deskripsi' => 'Program pertukaran peneliti dan mahasiswa dalam bidang fisika material menghasilkan publikasi internasional berkualitas tinggi. Kolaborasi ini membuka peluang penelitian yang lebih luas di tingkat global. Kerjasama internasional dalam bidang penelitian fisika material telah memberikan dampak signifikan bagi perkembangan ilmu pengetahuan.',
                'penulis' => 'Dr. Rizki Pratama',
                'tanggal_acara' => Carbon::now()->subDays(14),
                'image' => 'article/article-2.jpg'
            ],
            [
                'nama_acara' => 'Workshop Kalibrasi Alat Ukur Geofisika',
                'deskripsi' => 'Pelatihan kalibrasi alat ukur geofisika untuk meningkatkan akurasi pengukuran dalam penelitian. Workshop ini dihadiri oleh peneliti dan teknisi dari berbagai institusi. Akurasi pengukuran merupakan aspek fundamental dalam penelitian geofisika yang memerlukan pemahaman mendalam tentang teknik kalibrasi.',
                'penulis' => 'Dr. Maya Sari',
                'tanggal_acara' => Carbon::now()->subDays(17),
                'image' => 'article/article-2.jpg'
            ],
            [
                'nama_acara' => 'Publikasi Penelitian di Jurnal Internasional',
                'deskripsi' => 'Tim peneliti laboratorium berhasil mempublikasikan hasil penelitian tentang dinamika struktur bumi di jurnal Nature Geoscience. Publikasi ini merupakan pencapaian bergengsi bagi institusi. Publikasi di jurnal internasional bereputasi tinggi merupakan indikator kualitas penelitian yang telah dilakukan.',
                'penulis' => 'Prof. Dr. Budi Santoso',
                'tanggal_acara' => Carbon::now()->subDays(22),
                'image' => 'article/article-2.jpg'
            ],
        ];

        foreach ($artikels as $index => $artikelData) {
            try {
                // Create artikel dengan UUID manual
                $artikel = Artikel::create([
                    'id' => (string) Str::uuid(),
                    'nama_acara' => $artikelData['nama_acara'],
                    'deskripsi' => $artikelData['deskripsi'],
                    'penulis' => $artikelData['penulis'],
                    'tanggal_acara' => $artikelData['tanggal_acara']
                ]);

                $this->command->line("✅ Created artikel: {$artikel->nama_acara}");

                // Create gambar untuk artikel
                $gambar = Gambar::create([
                    'id' => (string) Str::uuid(),
                    'acara_id' => $artikel->id,
                    'url' => $artikelData['image'],
                    'kategori' => 'ACARA'
                ]);

                $this->command->line("   🖼️  Created image: {$gambar->url}");

                // Tambahkan gambar tambahan untuk artikel pertama
                if ($index === 0) {
                    $extraGambar = Gambar::create([
                        'id' => (string) Str::uuid(),
                        'acara_id' => $artikel->id,
                        'url' => 'article/article-gallery-1.jpg',
                        'kategori' => 'ACARA'
                    ]);
                    $this->command->line("   🖼️  Created extra image: {$extraGambar->url}");
                }

            } catch (\Exception $e) {
                $this->command->error("❌ Failed to create artikel: {$artikelData['nama_acara']}");
                $this->command->line("   Error: " . $e->getMessage());
            }
        }

        // Show summary
        $totalArtikel = Artikel::count();
        $totalGambar = Gambar::where('kategori', 'ACARA')->count();

        $this->command->line('');
        $this->command->info("🎉 ArtikelSeeder completed!");
        $this->command->line("📊 Total artikel: {$totalArtikel}");
        $this->command->line("🖼️  Total gambar: {$totalGambar}");

        // Test first article
        $firstArtikel = Artikel::with('gambarUtama')->first();
        if ($firstArtikel && $firstArtikel->gambarUtama) {
            $this->command->line("🎯 First article: {$firstArtikel->nama_acara}");
            $this->command->line("🖼️  First image: {$firstArtikel->gambarUtama->url}");

            // Check if image file exists
            $imagePath = public_path('images/' . str_replace('images/', '', $firstArtikel->gambarUtama->url));
            if (file_exists($imagePath)) {
                $this->command->line("✅ Image file exists");
            } else {
                $this->command->warn("⚠️  Image file missing: {$imagePath}");
                $this->command->line("💡 Create sample images in: public/images/article/");
            }
        }
    }
}
