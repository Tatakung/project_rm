<?php

namespace App\Http\Controllers;

use App\Models\Orderdetail;
use App\Models\Typedress;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function dashboardcutdress()
    {
        // ดึงข้อมูลประเภทชุดจากฐานข้อมูล
        $labels = Orderdetail::where('type_order', 1)
            ->pluck('type_dress')
            ->toArray();

        $count = array_count_values($labels);
        $labels = array_unique($labels);
        $labels = array_values($labels);
        $data = array_values($count);

        return view('employeecutdress.dashboardcut', compact('labels', 'data'));
    }
}
