<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\JenisCuti;

class JenisCutiController extends Controller
{
    public function index()
    {
        $jenisCuti = JenisCuti::all();
        return view('manajemen_cuti', compact('jenisCuti'));
    }
}