<?php

namespace App\Http\Controllers;

use App\Models\DataBeras;
use App\Models\EvaluasiModel;
use App\Models\PrediksiBulanan;
use App\Models\PrediksiDataBaru;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SendDataBerasController extends Controller
{
    public function sendDataToFlask(Request $request)
    {
        // Ambil data yang ingin dikirim
        $data = [
            'Tahun' => 2023,  // Ganti dengan data yang sesuai
            'Bulan' => 'Januari',  // Ganti dengan data yang sesuai
            'Nama_Beras' => 'Cap Walet',  // Ganti dengan data yang sesuai
            'Hari_Besar' => 1,  // Ganti dengan data yang sesuai
            'Curah_Hujan' => 6.2,  // Ganti dengan data yang sesuai
            'Suhu' => 27.7,  // Ganti dengan data yang sesuai
            'Kelembaban' => 86,  // Ganti dengan data yang sesuai
        ];

        $client = new Client();
        $response = $client->post('http://flask-server-url/predict', [
            'json' => $data
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        return response()->json([
            'prediksi_harga' => $result['prediksi_harga']
        ]);
    }

    public function exportCsv(Request $request)
    {
        $data = DataBeras::all();
        $response = new StreamedResponse(function () use ($data) {
            $handle = fopen('php://output', 'w');
            // Add header row
            fputcsv($handle, ['Tahun', 'Bulan', 'Nama_Beras', 'Harga', 'Hari_Besar', 'Curah_Hujan', 'Suhu', 'Kelembaban']);

            // Loop through the data and write each row
            foreach ($data as $row) {
                fputcsv($handle, [
                    $row->tahun,
                    $row->bulan,
                    $row->beras->nama,
                    $row->harga,
                    $row->hari_besar,
                    $row->curah_hujan,
                    $row->suhu,
                    $row->kelembaban,
                ]);
            }
            fclose($handle);
        });
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="data_beras.csv"');
        return $response;
    }

    public function storeEvaluasi(Request $request)
    {
        $validated = $request->validate([
            'mae' => 'required|numeric',
            'mse' => 'required|numeric',
            'rmse' => 'required|numeric',
            'r2_score' => 'required|numeric',
            'persentase_error' => 'required|numeric',
            'akurasi' => 'required|numeric',
        ]);

        $evaluasi = EvaluasiModel::create($validated);

        return response()->json($evaluasi, 201);
    }

    public function storePrediksiBulanan(Request $request)
    {
        $validated = $request->validate([
            '*.beras_id' => 'required|exists:beras,id',
            '*.bulan' => 'required|integer',
            '*.tahun' => 'required|integer',
            '*.harga_prediksi' => 'required|numeric',
            '*.harga_aktual' => 'nullable|numeric',
        ]);

        $prediksi = PrediksiBulanan::insert($validated);

        return response()->json([
            'message' => 'Batch prediksi berhasil disimpan.',
            'data' => $validated
        ], 201);
    }

    public function storeData(Request $request)
    {
        $validated = $request->validate([
            'beras_id' => 'required|exists:beras,id',
            'tahun' => 'required|integer',
            'bulan' => 'required|integer',
            'hari_besar' => 'required|boolean',
            'curah_hujan' => 'required|numeric',
            'suhu' => 'required|numeric',
            'kelembaban' => 'required|numeric',
            'harga_prediksi' => 'required|numeric',
        ]);

        // Simpan data prediksi ke database
        $prediksi = PrediksiDataBaru::create([
            'beras_id' => $validated['beras_id'],
            'tahun' => $validated['tahun'],
            'bulan' => $validated['bulan'],
            'hari_besar' => $validated['hari_besar'],
            'curah_hujan' => $validated['curah_hujan'],
            'suhu' => $validated['suhu'],
            'kelembaban' => $validated['kelembaban'],
            'harga_prediksi' => $validated['harga_prediksi'],
        ]);

        return response()->json([
            'message' => 'Data prediksi berhasil disimpan',
            'data' => $prediksi
        ], 201);
    }
}
