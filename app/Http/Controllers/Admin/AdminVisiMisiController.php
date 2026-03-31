<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Misi;
use App\Models\ProfilLaboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminVisiMisiController extends Controller
{
    /**
     * Display the vision, mission, and laboratory profile.
     */
    public function index()
    {
        $profil = ProfilLaboratorium::first();
        $misis = Misi::orderBy('created_at')->get();
        $selectedMisi = null;

        if ($profil) {
            $selectedMisi = Misi::find($profil->misiId);
        }

        return view('admin.visimisi.index', compact('profil', 'misis', 'selectedMisi'));
    }

    /**
     * Update the laboratory profile.
     */
    public function updateProfil(Request $request)
    {
        $request->validate([
            'namaLaboratorium'    => 'required|string|max:255',
            'tentangLaboratorium' => 'required|string',
            'visi'                => 'required|string',
            'misiId'              => 'nullable|exists:misi,id',
            'jumlah_komputer'     => 'required|integer|min:1|max:9999',
        ]);

        $data = $request->only(['namaLaboratorium', 'tentangLaboratorium', 'visi', 'jumlah_komputer']);
        if ($request->misiId) {
            $data['misiId'] = $request->misiId;
        }

        $profil = ProfilLaboratorium::first();
        if ($profil) {
            $profil->update($data);
        } else {
            ProfilLaboratorium::create($data);
        }

        return redirect()->route('admin.visimisi.index')
            ->with('success', 'Profil laboratorium berhasil diperbarui');
    }

    /**
     * Store a new mission point.
     */
    public function storeMisi(Request $request)
    {
        $request->validate([
            'pointMisi' => 'required|string|max:255',
        ]);

        $misi = Misi::create([
            'id' => Str::uuid(),
            'pointMisi' => $request->pointMisi,
        ]);

        // If this is the first mission, update the profile to use it
        $profil = ProfilLaboratorium::first();
        if ($profil && !$profil->misiId) {
            $profil->update(['misiId' => $misi->id]);
        }

        return redirect()->route('admin.visimisi.index')
            ->with('success', 'Poin misi berhasil ditambahkan');
    }

    /**
     * Update a mission point.
     */
    public function updateMisi(Request $request, Misi $misi)
    {
        $request->validate([
            'pointMisi' => 'required|string|max:255',
        ]);

        $misi->update([
            'pointMisi' => $request->pointMisi,
        ]);

        return redirect()->route('admin.visimisi.index')
            ->with('success', 'Poin misi berhasil diperbarui');
    }

    /**
     * Delete a mission point.
     */
    public function destroyMisi(Misi $misi)
    {
        // Check if any profile is using this mission
        $profil = ProfilLaboratorium::where('misiId', $misi->id)->first();
        if ($profil) {
            return redirect()->route('admin.visimisi.index')
                ->with('error', 'Tidak dapat menghapus misi yang sedang digunakan di profil laboratorium');
        }

        $misi->delete();

        return redirect()->route('admin.visimisi.index')
            ->with('success', 'Poin misi berhasil dihapus');
    }
}
