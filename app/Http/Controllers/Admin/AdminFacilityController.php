<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Gambar; // TAMBAHAN
use Illuminate\Support\Facades\Storage;

class AdminFacilityController extends Controller
{
    public function index()
    {
        $facility = Facility::first();

        if (!$facility) {
            $facility = $this->createDefaultFacility();
        }

        return view('admin.facilities.index', compact('facility'));
    }

    public function edit()
    {
        $facility = Facility::first();

        if (!$facility) {
            $facility = $this->createDefaultFacility();
        }

        return view('admin.facilities.edit', compact('facility'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'facility_points' => 'required|array|min:1',
            'facility_points.*' => 'required|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'delete_images' => 'nullable|array'
        ]);

        $facility = Facility::first();

        if (!$facility) {
            $facility = new Facility();
        }

        $facility->title = $request->title;
        $facility->description = $request->description;
        $facility->facility_points = $request->facility_points;

        // Handle existing images
        $existingImages = $facility->images ?? [];

        // Pastikan $existingImages adalah array
        if (is_string($existingImages)) {
            $existingImages = json_decode($existingImages, true) ?? [];
        }

        // TAMBAHAN: Delete selected images dari storage DAN tabel gambar
        if ($request->delete_images) {
            foreach ($request->delete_images as $imageToDelete) {
                if (($key = array_search($imageToDelete, $existingImages)) !== false) {
                    unset($existingImages[$key]);

                    // Hapus dari storage
                    if (Storage::disk('public')->exists($imageToDelete)) {
                        Storage::disk('public')->delete($imageToDelete);
                    }

                    // TAMBAHAN: Hapus dari tabel gambar
                    Gambar::where('url', $imageToDelete)
                          ->where('kategori', 'FASILITAS')
                          ->delete();
                }
            }
            $existingImages = array_values($existingImages); // Re-index array
        }

        // TAMBAHAN: Handle new image uploads dan simpan ke tabel gambar
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('facilities', 'public');
                $existingImages[] = $path;

                // TAMBAHAN: Simpan ke tabel gambar
                Gambar::create([
                    'facility_id' => $facility->id ?? null,
                    'url' => $path,
                    'kategori' => 'FASILITAS'
                ]);
            }
        }

        $facility->images = $existingImages;
        $facility->save();

        // TAMBAHAN: Update facility_id di tabel gambar jika facility baru dibuat
        if ($facility->wasRecentlyCreated) {
            Gambar::where('kategori', 'FASILITAS')
                  ->whereNull('facility_id')
                  ->update(['facility_id' => $facility->id]);
        }

        return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil diperbarui!');
    }

    private function createDefaultFacility()
    {
        return Facility::create([
            'title' => 'Fasilitas Laboratorium Fisika Komputasi',
            'description' => 'Laboratorium Fisika Komputasi dilengkapi dengan berbagai fasilitas modern untuk mendukung kegiatan praktikum dan pembelajaran mahasiswa.',
            'facility_points' => [
                'Ruang laboratorium yang luas dan nyaman',
                'Peralatan praktikum lengkap dan modern',
                'Kapasitas hingga 40 mahasiswa',
                'Sistem ventilasi dan pencahayaan yang baik',
                'Area demonstrasi untuk dosen',
                'Meja praktikum yang ergonomis',
                'Fasilitas penyimpanan alat yang aman',
                'Akses internet untuk penelusuran data'
            ],
            'images' => []
        ]);
    }
}
