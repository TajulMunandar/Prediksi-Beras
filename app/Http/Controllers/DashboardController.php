<?php

namespace App\Http\Controllers;

use App\Models\DataBeras;
use App\Models\PrediksiDataBaru;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = 'Dashboard';
        $user = User::count();
        $beras = DataBeras::count();
        $prediksi = PrediksiDataBaru::count();
        $oneWeekAgo = Carbon::now()->subWeek(); // Get the date for one week ago
        $dates = []; // Array to store date counts
        $dateCounts = []; // Array to store the counts for each day

        // Loop through each day of the last 7 days and count records
        for ($i = 0; $i < 7; $i++) {
            $date = $oneWeekAgo->copy()->addDays($i)->format('Y-m-d'); // Get the date for each day
            $dates[] = $date; // Store the date

            // Count the number of predictions for this day
            $count = PrediksiDataBaru::whereDate('tanggal_input', '=', $date)->count();
            $dateCounts[$date] = $count; // Store the count for this date
        }
        return view('dashboard.pages.index')->with(compact('page', 'user', 'beras', 'prediksi',  'dates', 'dateCounts'));
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
