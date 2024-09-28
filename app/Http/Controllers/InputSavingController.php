<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InputSavingController extends Controller
{
    public function index()
    {
        return view('inputsimpanan');
    }
    public function indexKolektif()
    {
        return view('inputsimpanankolektif');
    }
    public function indexPenarikanKolektif()
    {
        return view('penarikansimpanankolektif');
    }
}
