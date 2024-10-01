<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InputLoanController extends Controller
{
    public function index()
    {
        return view('admin.inputpembiayaan');
    }
    public function indexKolektif()
    {
        return view('admin.inputpembiayaankolektif');
    }
}
