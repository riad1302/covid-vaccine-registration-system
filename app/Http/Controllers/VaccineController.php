<?php

namespace App\Http\Controllers;

use App\Models\VaccineCenter;

class VaccineController extends Controller
{
    public function list()
    {
        return VaccineCenter::select('id', 'name')->get();
    }
}
