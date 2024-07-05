<?php

namespace App\Http\Controllers;

use App\Models\Typedress;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    //
    public function homepage(){
        return view('employee.employeehome') ; 
    }


    public function addorder(){
        return view('Employee.addorder') ; 
    }

    public function addcutdress(){
        $type_dress = Typedress::all() ; 
        return view('Employee.addcutdress',compact('type_dress')); 
    }

    public function savecutdress(Request $request){
        
    }



}
