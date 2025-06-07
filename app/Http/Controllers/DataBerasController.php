<?php

namespace App\Http\Controllers;

use App\Models\Beras;
use Illuminate\Http\Request;

class DataBerasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = 'Data Beras';
        $beras = Beras::all();
        return view('dashboard.pages.data-beras')->with(compact('page', 'beras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi input dari pengguna
            $request->validate([
                'nama_beras' => 'required|string|max:255',
                'kualitas' => 'required|string|max:255',
            ]);

            // Menyimpan data beras
            Beras::create([
                'nama_beras' => $request->nama_beras,
                'kualitas' => $request->kualitas,
            ]);

            // Redirect kembali dengan pesan sukses
            return redirect()->back()->with('success', 'Data Beras berhasil ditambah.');
        } catch (\Exception $e) {
            // Menangani error dan mengirim pesan error ke view
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // Validasi input dari pengguna
            $request->validate([
                'nama_beras' => 'required|string|max:255',
                'kualitas' => 'required|string|max:255',
            ]);

            // Mencari data beras yang ingin diupdate
            $beras = Beras::findOrFail($id);

            // Melakukan pembaruan data beras
            $beras->update([
                'nama_beras' => $request->nama_beras,
                'kualitas' => $request->kualitas,
            ]);

            // Redirect kembali dengan pesan sukses
            return redirect()->back()->with('success', 'Data Beras berhasil diubah.');
        } catch (\Exception $e) {
            // Menangani error dan mengirim pesan error ke view
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Mencari data beras berdasarkan ID
            $beras = Beras::findOrFail($id);

            // Menghapus data beras
            $beras->delete();

            // Redirect kembali dengan pesan sukses
            return redirect()->route('data-beras.index')->with('success', 'Data Beras berhasil dihapus');
        } catch (\Exception $e) {
            // Menangani error dan mengirim pesan error ke view
            return redirect()->route('data-beras.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
