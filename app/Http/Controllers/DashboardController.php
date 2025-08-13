<?php

namespace App\Http\Controllers;

use App\Models\Beras;
use App\Models\DataBeras;
use App\Models\PrediksiDataBaru;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = 'Dashboard';
        $user = User::count();
        $prediksiDataBaru = PrediksiDataBaru::count();
        $beras = DataBeras::count();
        $semuaBeras = Beras::all();
        $tahun = now()->year;
        $bulan_sekarang = now()->month;

        $prediksiPerBeras = [];

        foreach ($semuaBeras as $berasItem) {
            $namaBeras = $berasItem->nama_beras; // Pastikan nama kolomnya sesuai
            $kualitas = $berasItem->kualitas; // Pastikan nama kolomnya sesuai
            $berasId = $berasItem->id;

            $prediksiBulan = [];

            $tahunTerakhir = DataBeras::where('beras_id', $berasId)
                ->max('tahun'); // Tahun paling akhir

            // Ambil data 12 bulan terakhir, bisa melewati tahun (ambil berdasarkan tahun & bulan)
            $data12BulanTerakhir = DataBeras::where('beras_id', $berasId)
                ->where(function ($query) use ($tahunTerakhir) {
                    // Ambil data dari tahun terakhir dan mungkin tahun sebelumnya,
                    // untuk memastikan dapat 12 data bulan
                    $query->where('tahun', $tahunTerakhir)
                        ->orWhere('tahun', $tahunTerakhir - 1);
                })
                ->orderByDesc('tahun')
                ->orderByDesc('bulan')
                ->limit(12)
                ->get()
                ->sortBy(function ($item) {
                    // Supaya data terurut dari bulan paling lama ke terbaru
                    return $item->tahun * 100 + $item->bulan;
                })
                ->values();

            foreach ($data12BulanTerakhir as $dataBulan) {
                $bulan = $dataBulan->bulan;
                $tahun = $dataBulan->tahun;

                $hari_besar = $dataBulan->hari_besar ?? 0;
                $curah_hujan = $dataBulan->curah_hujan ?? 150;
                $suhu = $dataBulan->suhu ?? 30;
                $kelembaban = $dataBulan->kelembaban ?? 80;

                $semuaPrediksiInput[] = [
                    'beras_id' => $berasId,
                    'nama_beras' => $namaBeras,
                    'kualitas' => $kualitas,
                    'tahun' => $tahun,
                    'bulan' => $this->namaBulan($bulan),
                    'hari_besar' => $hari_besar,
                    'curah_hujan' => $curah_hujan,
                    'suhu' => $suhu,
                    'kelembaban' => $kelembaban,
                    'harga_aktual' => $dataBulan->harga ?? null,
                ];
            }
        }
        $response = Http::post('http://localhost:5000/predict-batch', $semuaPrediksiInput);
        $prediksiPerBeras = [];
        if ($response->successful()) {
            $results = $response->json();

            foreach ($results as $index => $result) {
                $input = $semuaPrediksiInput[$index];
                $key = $result['nama_beras'] . '|' . $result['kualitas'];
                if (!isset($prediksiPerBeras[$key])) {
                    $prediksiPerBeras[$key] = [];
                }

                $prediksiPerBeras[$key][] = [
                    'bulan' => $result['bulan'],
                    'harga' => $result['prediksi_harga'],
                    'harga_aktual' => $result['harga_aktual'] ?? null,
                ];
            }
        }

        return view('dashboard.pages.index')->with(compact('page', 'user', 'beras', 'prediksiPerBeras', 'prediksiDataBaru'));
    }

    public function namaBulan($bulanAngka)
    {
        $nama = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
        return $nama[$bulanAngka];
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
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
