<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillController extends Controller
{
    public function index()
    {
        $Bills = Bill::all();
        return view('bills.index', compact('Bills'));
    }
    
}
