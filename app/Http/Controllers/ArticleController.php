<?php
// app/Http/Controllers/ArticleController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artikel;
use App\Models\Gambar;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Artikel::with(['gambarUtama'])
                           ->orderBy('tanggal_acara', 'desc')
                           ->get()
                           ->map(function ($artikel) {
                               return [
                                   'id' => $artikel->id,
                                   'title' => $artikel->nama_acara,
                                   'excerpt' => Str::limit($artikel->deskripsi, 150),
                                   'content' => $artikel->deskripsi,
                                   'author' => $artikel->penulis ?? 'Admin',
                                   'date' => $artikel->tanggal_acara->format('Y-m-d'),
                                   'image' => $this->getCorrectImageUrl($artikel),
                                   'slug' => Str::slug($artikel->nama_acara),
                                   'featured' => $this->isFeatured($artikel)
                               ];
                           });

        return view('articles.index', compact('articles'));
    }

    public function show($id)
    {
        $artikel = Artikel::with(['gambar', 'gambarUtama'])->find($id);

        if (!$artikel) {
            abort(404, 'Artikel tidak ditemukan');
        }

        $article = [
            'id' => $artikel->id,
            'title' => $artikel->nama_acara,
            'excerpt' => Str::limit($artikel->deskripsi, 150),
            'content' => $this->formatContent($artikel->deskripsi),
            'author' => $artikel->penulis ?? 'Admin',
            'date' => $artikel->tanggal_acara->format('Y-m-d'),
            'image' => $this->getCorrectImageUrl($artikel),
            'slug' => Str::slug($artikel->nama_acara),
            'featured' => $this->isFeatured($artikel),
            'gallery' => $artikel->gambar->map(function($gambar) {
                return $this->getCorrectGambarUrl($gambar);
            })
        ];

        return view('articles.show', compact('article'));
    }

    public function getFeaturedArticles($limit = 3)
    {
        $articles = Artikel::with(['gambarUtama'])
                           ->orderBy('tanggal_acara', 'desc')
                           ->take($limit)
                           ->get()
                           ->map(function ($artikel) {
                               return [
                                   'id' => $artikel->id,
                                   'title' => $artikel->nama_acara,
                                   'excerpt' => Str::limit($artikel->deskripsi, 120),
                                   'author' => $artikel->penulis ?? 'Admin',
                                   'date' => $artikel->tanggal_acara->format('Y-m-d'),
                                   'image' => $this->getCorrectImageUrl($artikel),
                                   'slug' => Str::slug($artikel->nama_acara)
                               ];
                           });

        return $articles->toArray();
    }

    public function latest()
    {
        $articles = Artikel::with(['gambarUtama'])
                           ->orderBy('tanggal_acara', 'desc')
                           ->take(5)
                           ->get()
                           ->map(function ($artikel) {
                               return [
                                   'id' => $artikel->id,
                                   'title' => $artikel->nama_acara,
                                   'excerpt' => Str::limit($artikel->deskripsi, 100),
                                   'author' => $artikel->penulis ?? 'Admin',
                                   'date' => $artikel->tanggal_acara->format('Y-m-d'),
                                   'image' => $this->getCorrectImageUrl($artikel)
                               ];
                           });

        return response()->json($articles);
    }

    public function featured()
    {
        $featuredArticles = $this->getFeaturedArticles();
        return response()->json($featuredArticles);
    }

    // PERBAIKAN UTAMA: Method untuk mendapatkan URL gambar yang benar
    private function getCorrectImageUrl($artikel)
    {
        if ($artikel->gambarUtama) {
            $url = $artikel->gambarUtama->url;



            // Jika sudah URL lengkap (http/https), return as is
            if (str_starts_with($url, 'http')) {
                return $url;
            }

            // Jika sudah dimulai dengan 'storage/', hapus storage/ terlebih dahulu
            if (str_starts_with($url, 'storage/')) {
                $url = str_replace('storage/', '', $url);
            }

            // Pastikan file benar-benar ada di storage
            if (Storage::disk('public')->exists($url)) {
                $fullUrl = asset('storage/' . $url);
                return $fullUrl;
            }


        }

        // Default image
        return asset('images/article/default.jpg');
    }

    private function getCorrectGambarUrl($gambar)
    {
        $url = $gambar->url;

        // Jika sudah URL lengkap (http/https), return as is
        if (str_starts_with($url, 'http')) {
            return $url;
        }

        // Jika sudah dimulai dengan 'storage/', hapus storage/ terlebih dahulu
        if (str_starts_with($url, 'storage/')) {
            $url = str_replace('storage/', '', $url);
        }

        // Pastikan file benar-benar ada di storage
        if (Storage::disk('public')->exists($url)) {
            return asset('storage/' . $url);
        }

        // Default image jika file tidak ditemukan
        return asset('images/article/default.jpg');
    }

    private function isFeatured($artikel)
    {
        return $artikel->tanggal_acara->diffInDays(now()) <= 30;
    }

    private function formatContent($content)
    {
        $formatted = '<p>' . str_replace(["\r\n", "\n", "\r"], '</p><p>', $content) . '</p>';
        $formatted = str_replace('<p></p>', '', $formatted);
        return $formatted;
    }

    public function search(Request $request)
    {
        $query = $request->get('query');

        $articles = Artikel::with(['gambarUtama'])
                           ->where('nama_acara', 'LIKE', '%' . $query . '%')
                           ->orWhere('deskripsi', 'LIKE', '%' . $query . '%')
                           ->orWhere('penulis', 'LIKE', '%' . $query . '%')
                           ->orderBy('tanggal_acara', 'desc')
                           ->get()
                           ->map(function ($artikel) {
                               return [
                                   'id' => $artikel->id,
                                   'title' => $artikel->nama_acara,
                                   'excerpt' => Str::limit($artikel->deskripsi, 150),
                                   'author' => $artikel->penulis ?? 'Admin',
                                   'date' => $artikel->tanggal_acara->format('Y-m-d'),
                                   'image' => $this->getCorrectImageUrl($artikel)
                               ];
                           });

        return response()->json($articles);
    }
}
