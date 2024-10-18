<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ShuAnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $no_user = Session::get('no_user');
        $no_user = str_replace('-', '', $no_user);
        $spreadsheetId = '1-uXnDBTvCJsHfhY4qu6bgQK3wdjWG4kCuQNuC0AjNaQ'; // Replace with your Spreadsheet ID
        $apiKey = 'AIzaSyAK2aDynWrjpA15dROqXccbTxaOUvrPFcw'; // Replace with your API Key

        // Fetch data from Google Sheets API
        $response = Http::get("https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheetId}/values/Sheet1?key={$apiKey}");

        if ($response->successful()) {
            $data = $response->json()['values'] ?? []; // Get the data values

            // Filter data based on no_user (the first column)
            $filteredData = array_filter($data, function ($row) use ($no_user) {
                // Check if the first element (index 0) matches no_user
                return isset($row[0]) && $row[0] === $no_user;
            });

            // Reset array keys for filtered data
            $filteredData = array_values($filteredData);
        } else {
            $filteredData = []; // Set data to empty if the request failed
        }

        // Pass the filtered data to the view
        return view('anggota.shu', compact('filteredData'));
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
