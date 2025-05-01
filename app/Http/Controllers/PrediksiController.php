<?php

namespace App\Http\Controllers;

use App\Models\Beras;
use App\Models\EvaluasiModel;
use App\Models\PrediksiBulanan;
use Illuminate\Http\Request;

class PrediksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = 'Prediksi';
        $berasList = Beras::all();
        $dataPrediksi = PrediksiBulanan::latest()->get(); // atau sesuai kebutuhan
        $evaluasi = EvaluasiModel::latest()->first(); // jika hanya butuh 1 evaluasi terakhir
        return view('dashboard.pages.prediksi')->with(compact('page', 'dataPrediksi', 'evaluasi', 'berasList'));
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
