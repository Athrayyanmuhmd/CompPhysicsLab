<?php

namespace App\Http\Controllers;

use App\Models\ProfilLaboratorium;
use App\Models\Misi;
use App\Models\Gambar;
use App\Models\Alat;
use App\Models\Kunjungan;
use App\Models\BiodataPengurus;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $articleController;

    public function __construct()
    {
        $this->articleController = new ArticleController();
    }

    public function index()
    {
        // Ambil data artikel dari ArticleController
        $featuredArticles = $this->articleController->getFeaturedArticles();

        if (is_object($featuredArticles) && method_exists($featuredArticles, 'toArray')) {
            $featuredArticles = $featuredArticles;
        }

        // TAMBAHAN: Ambil gambar untuk gallery (EXCLUDE PENGURUS)
        $galleryImages = $this->getGalleryImages();

        $profil = ProfilLaboratorium::with('misi')->first();
        $misis = Misi::all();

        // Dummy fallback data...
        if (!$profil) {
            $profil = (object) [
                'namaLaboratorium' => 'Fisika Komputasi',
                'tentangLaboratorium' => 'Laboratorium Fisika Komputasi merupakan fasilitas unggulan yang berkomitmen untuk mengembangkan penelitian dan pendidikan di bidang fisika dengan teknologi terdepan.',
                'visi' => 'Menjadi laboratorium fisika terdepan di Indonesia yang berkontribusi dalam penelitian dan pengembangan ilmu fisika untuk kemajuan bangsa.',
                'jumlah_komputer' => 40,
            ];
        }

        if ($misis->isEmpty()) {
            $misis = collect([
                (object) ['pointMisi' => 'Menyediakan fasilitas penelitian fisika berkualitas tinggi'],
                (object) ['pointMisi' => 'Mengembangkan sumber daya manusia di bidang fisika'],
                (object) ['pointMisi' => 'Berkolaborasi dalam penelitian bertaraf internasional'],
            ]);
        }

        if (empty($featuredArticles) || count($featuredArticles) == 0) {
            $featuredArticles = [
                [
                    'id' => 1,
                    'title' => 'Pengembangan Sistem Monitoring Seismik Real-time',
                    'excerpt' => 'Laboratorium berhasil mengembangkan sistem monitoring aktivitas seismik yang dapat memberikan peringatan dini dengan akurasi tinggi.',
                    'author' => 'Dr. Ahmad Rahman',
                    'date' => now()->subDays(7)->format('Y-m-d'),
                    'image' => asset('images/article/article-1.jpeg'),
                    'slug' => 'pengembangan-sistem-monitoring-seismik'
                ],
                [
                    'id' => 2,
                    'title' => 'Inovasi Metode Praktikum Fisika Modern',
                    'excerpt' => 'Penerapan teknologi AR dan VR dalam praktikum fisika modern memberikan pengalaman belajar yang lebih interaktif.',
                    'author' => 'Prof. Siti Nurhaliza',
                    'date' => now()->subDays(10)->format('Y-m-d'),
                    'image' => asset('images/article/article-2.jpg'),
                    'slug' => 'inovasi-metode-praktikum-fisika'
                ],
                [
                    'id' => 3,
                    'title' => 'Kerjasama Penelitian dengan Universitas Tokyo',
                    'excerpt' => 'Program pertukaran peneliti dan mahasiswa dalam bidang fisika material menghasilkan publikasi internasional berkualitas tinggi.',
                    'author' => 'Dr. Rizki Pratama',
                    'date' => now()->subDays(14)->format('Y-m-d'),
                    'image' => asset('images/article/article-1.jpeg'),
                    'slug' => 'kerjasama-penelitian-universitas-tokyo'
                ]
            ];
        }

        // Hitung statistik
        $totalKomputer = $profil->jumlah_komputer ?? 40;
        $totalKunjunganPerTahun = Kunjungan::whereYear('tanggal_kunjungan', now()->year)->count();
        $totalStaf = BiodataPengurus::count();

        return view('home', compact(
            'featuredArticles',
            'profil',
            'misis',
            'galleryImages',
            'totalKomputer',
            'totalKunjunganPerTahun',
            'totalStaf'
        ));
    }

    private function getGalleryImages()
    {
        try {
            // Ambil gambar unik (per URL): prioritas FASILITAS dulu, sisanya ACARA, maks 6
            $images = Gambar::whereIn('kategori', ['FASILITAS', 'ACARA'])
                            ->with('artikel')
                            ->latest()
                            ->get()
                            ->sortByDesc(fn($img) => $img->kategori === 'FASILITAS' ? 1 : 0)
                            ->unique('url')
                            ->take(6)
                            ->map(fn($image) => [
                                'url'      => $image->url_lengkap,
                                'kategori' => $image->kategori,
                                'title'    => $this->getImageTitle($image),
                            ]);

            return $images;

        } catch (\Exception $e) {
            return collect([]);
        }
    }

    /**
     * Method untuk mendapatkan title gambar (TANPA PENGURUS)
     */
    private function getImageTitle($image)
    {
        switch ($image->kategori) {
            case 'FASILITAS':
                return 'Fasilitas Laboratorium';
            case 'ACARA':
                // Cek apakah ada relasi artikel
                if ($image->artikel && $image->artikel->nama_acara) {
                    return $image->artikel->nama_acara;
                }
                return 'Kegiatan Laboratorium';
            default:
                return 'Laboratorium Fisika Komputasi';
        }
    }

    public function about()
    {
        return view('about');
    }

    public function equipment()
    {
        return view('equipment');
    }

    public function services()
    {
        return view('services');
    }

    public function contact()
    {
        return view('contact');
    }
}
