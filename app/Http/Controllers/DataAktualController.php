<?php

namespace App\Http\Controllers;

use App\Models\Beras;
use App\Models\DataBeras;
use Illuminate\Http\Request;

class DataAktualController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $aktuals = DataBeras::with('beras')->get();
        $beras = Beras::all();
        $page = 'Data Aktual';
        return view('dashboard.pages.data-aktual')->with(compact('page', 'aktuals', 'beras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'beras_id' => 'required|exists:beras,id',
                'bulan' => 'required|integer',
                'tahun' => 'required|integer',
                'harga' => 'required|numeric',
                'hari_besar' => 'required|boolean',
                'curah_hujan' => 'required|numeric',
                'suhu' => 'required|numeric',
                'kelembaban' => 'required|numeric',
            ]);

            // Menyimpan data ke dalam database
            DataBeras::create($validatedData);

            // Redirect atau response sukses
            return redirect()->back()->with('success', 'Data Aktual berhasil ditambahkan!');
        } catch (\Exception $e) {

            // Mengembalikan error message
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.' . $e->getMessage());
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
            // Validasi input
            $validatedData = $request->validate([
                'beras_id' => 'required|exists:beras,id',
                'bulan' => 'required|integer',
                'tahun' => 'required|integer',
                'harga' => 'required|numeric',
                'hari_besar' => 'required|boolean',
                'curah_hujan' => 'required|numeric',
                'suhu' => 'required|numeric',
                'kelembaban' => 'required|numeric',
            ]);

            // Mencari data yang ingin diupdate
            $dataAktual = DataBeras::findOrFail($id);

            // Mengupdate data
            $dataAktual->update($validatedData);

            // Redirect atau response sukses
            return redirect()->back()->with('success', 'Data Aktual berhasil diperbarui!');
        } catch (\Exception $e) {

            // Mengembalikan error message
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data.' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Mencari data yang ingin dihapus
            $dataAktual = DataBeras::findOrFail($id);

            // Menghapus data
            $dataAktual->delete();

            // Redirect atau response sukses
            return redirect()->back()->with('success', 'Data Aktual berhasil dihapus!');
        } catch (\Exception $e) {

            // Mengembalikan error message
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data.' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        // Validasi file CSV
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        // Mendapatkan file CSV
        $file = $request->file('file');

        // Membaca file CSV
        $data = array_map('str_getcsv', file($file));

        // Ambil header kolom
        $header = array_shift($data);

        // Loop melalui data CSV dan simpan ke database
        foreach ($data as $row) {
            // Mencocokkan Nama Beras
            $beras = Beras::where('nama_beras', $row[1])->first(); // Indeks 1 untuk Nama Beras

            if ($beras) {
                DataBeras::create([
                    'beras_id' => $beras->id,
                    'bulan' => $this->getBulan($row[0]), // Indeks 0 untuk Bulan
                    'harga' => $row[2], // Indeks 2 untuk Harga
                    'hari_besar' => $row[3], // Indeks 3 untuk Hari Besar
                    'curah_hujan' => $row[4], // Indeks 4 untuk Curah Hujan
                    'suhu' => $row[5], // Indeks 5 untuk Suhu
                    'kelembaban' => $row[6], // Indeks 6 untuk Kelembaban
                    'tahun' => $row[7], // Indeks 7 untuk Tahun
                ]);
            }
        }

        return back()->with('success', 'Data berhasil diimpor');
    }

    private function getBulan($bulan)
    {
        $months = [
            'Januari' => 1,
            'Februari' => 2,
            'Maret' => 3,
            'April' => 4,
            'Mei' => 5,
            'Juni' => 6,
            'Juli' => 7,
            'Agustus' => 8,
            'September' => 9,
            'Oktober' => 10,
            'November' => 11,
            'Desember' => 12,
        ];

        return $months[$bulan] ?? null; // Jika bulan tidak ditemukan, kembalikan null
    }
}
