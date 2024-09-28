<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InputLoanController extends Controller
{
    public function index()
    {
        return view('inputpembiayaan');
    }
    public function indexKolektif()
    {
        return view('inputpembiayaankolektif');
    }
}
