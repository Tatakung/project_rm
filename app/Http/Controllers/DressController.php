<?php

namespace App\Http\Controllers;

use App\Models\Dress;
use App\Models\Dressimage;
use App\Models\Dressmeasurement;
use App\Models\Expense;
use App\Models\Typedress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DressController extends Controller
{


    public function formadddress()
    {
        $typeDresses = Typedress::all();
        return view('admin.adddress', compact('typeDresses'));
    }



    public function savedress(Request $request)
    {
        $dressCount = $request->input('dress_count');
        $dressCodes = [];
        //เพิ่มประเภทชุดใหม่
        if ($request->input('type_dress_id') == "select_other") {

            $checkdata = Typedress::where('type_dress_name', $request->input('inputother'))->first();   //เช็คว่ามันซ้ำไหม

            if (!$checkdata) {

                // แรนด้อมตัวอักษรมาก่อนนะ
                do {
                    $random = chr(65 + rand(0, 25));
                    $check = Typedress::where('specific_letter', $random)->first();
                } while ($check);
                $unique_character = $random;   //ได้ตัวอักษรมาแล้ว

                //บันทึกข้อมูลลงในตาราง typedress
                $type = new Typedress;
                $type->type_dress_name = $request->input('inputother');
                $type->specific_letter = $unique_character;
                $type->save();
                $TYPE_ID = $type->id;
                $newDressCode = 1; //กำหนดให้หมายเลขชุดเริ่มที่ 1 
                $name_for_session = $request->input('inputother');
            } else {
                return redirect()->back()->with('fail', "ประเภทชุดนี้มีอยู่แล้ว !");
            }
        }
        //เพิ่มชุดประเภทเดิม
        else {
            $TYPE_ID = $request->input('type_dress_id'); //เก็บid  typedress เพื่อนำไปใส่ในตาราง dress
            $maxDressCode = Dress::where('type_dress_id', $request->input('type_dress_id'))->max('dress_code');
            
            if($maxDressCode){
                //ถ้ามี
                $newDressCode = $maxDressCode + 1; //กำหนดให้หมายเลขชุดที่มี + 1 เพิ่มขึ้นไปเรื่อยๆ 
            }
            else{
                //ถ้าไม่มีก็ให้เริ่มที่ 1 
                $newDressCode = 1 ; 
            }

            $unique_character = Typedress::where('id', $request->input('type_dress_id'))->value('specific_letter');
            $name_for_session = Typedress::where('id', $request->input('type_dress_id'))->value('type_dress_name');
            
        }


        for ($i = 0; $i < $dressCount; $i++) {
            // สร้าง dress ใหม่
            $dress = new Dress();
            $dress->type_dress_id = $TYPE_ID;
            $dress->dress_code = $newDressCode; //หมายเลขชุด
            $dress->dress_code_new = $unique_character;
            $dress->dress_title_name = $request->input('dress_title_name');
            $dress->dress_color = $request->input('dress_color');


            if($request->input('dress_deposit') > $request->input('dress_price') ){
                return redirect()->back()->with('fail',"ราคาชุดจะต้องมีการค่ามากกว่าราคามัดจำ !") ; 
            }
            else{
                $dress->dress_price = $request->input('dress_price');
                $dress->dress_deposit = $request->input('dress_deposit');    
            }


            $dress->dress_count = 1 ; 
            $dress->dress_status = "พร้อมให้เช่า";
            $dress->dress_description = $request->input('dress_description');
            $dress->dress_rental = 0;
            $dress->save();

            // เก็บ dress_code ที่ถูกสร้างไว้ใน list
            $dressCodes[] = $name_for_session . ' รหัสชุด ' . $unique_character . '' . $newDressCode;
            $newDressCode =  $newDressCode + 1; // บวก dress_code ขึ้นทีละ 1 

            $mea_dress_name = $request->input('measurement_dress_name_');
            $mea_dress_number = $request->input('measurement_dress_number_');
            $mea_dress_unit = $request->input('measurement_dress_unit_');

            foreach ($mea_dress_name as $index => $mea) {
                $addmea = new Dressmeasurement;
                $addmea->dress_id  = $dress->id;
                $addmea->measurement_dress_name = $mea;
                $addmea->measurement_dress_number = $mea_dress_number[$index];
                $addmea->measurement_dress_unit = $mea_dress_unit[$index];
                $addmea->save();
            }

        
            //รูปภาพ
            if ($request->hasFile('imagerent_')) {
                $images = $request->file('imagerent_');
                // $images->image = $request->file('imagerent_')->store('dress_image','public') ; 
                foreach ($images as $index => $image) {
                    $additionalImage = new Dressimage;
                    $additionalImage->dress_id  = $dress->id;
                    $additionalImage->dress_image = $image->store('dress_images', 'public');
                    $additionalImage->save();
                }
            }
        }
        return redirect()->back()->with('dressCodes',$dressCodes);
    }





    public function autodresscode($typename)
    {
        // หา type_dress โดยใช้ชื่อประเภทชุดที่รับมา
        $dataname = Typedress::where('type_dress_name', $typename)->first();

        if ($dataname) {
            $ID = $dataname->id;
            // หา dress_code สูงสุดของประเภทชุดที่ตรงกับ ID นี้
            $find = Dress::where('type_dress_id', $ID)->max('dress_code');
            return response()->json(['max' => $find]);
        } else {
            // ถ้าไม่พบ type_dress คืนค่า 'max' เป็น null
            return response()->json(['max' => null]);
        }
    }


    public function dresstotal()
    {
        $showtype = Typedress::all();
        return view('admin.dresstotal', compact('showtype'));
    }


    public function typedress($id)
    {

        $data = Dress::where('type_dress_id', $id)->with('dressimages')->get();
        return view('admin.typedress', compact('data'));
    }

    //รายละเอียดชุด ละเอียดที่สุดแล้ว
    public function dressdetail($id)
    {
        $datadress = Dress::find($id);
        $name_type = Typedress::where('id', $datadress->type_dress_id)->value('type_dress_name');
        $measument = Dressmeasurement::where('dress_id', $id)->get();

        $imagedata = Dressimage::where('dress_id', $id)->get();
        return view('admin.dressdetail', compact('datadress', 'imagedata', 'name_type', 'measument'));
    }

    //อัปเดตชุด
    public function updatedress(Request $request, $id)
    {
        $savedata = Dress::find($id);
        $savedata->dress_title_name = $request->input('update_dress_title_name');
        $savedata->dress_color = $request->input('update_dress_color');
        $savedata->dress_description = $request->input('update_dress_description');
        $savedata->save();
        return redirect()->back()->with('success', 'อัพเดตข้อมูลสำเร็จ !');
    }

    //อีปเดตราคา
    public function updateprice(Request $request,  $id)
    {
        $updatedata = Dress::find($id);


        if ($request->input('update_dress_deposit') <= $request->input('update_dress_price')) {
            $updatedata->dress_price = $request->input('update_dress_price');
            $updatedata->dress_deposit = $request->input('update_dress_deposit');
            $updatedata->save();
            return redirect()->back()->with('success', 'อัพเดตข้อมูลสำเร็จ !');
        } else {
            return redirect()->back()->with('fail', 'ราคาชุดต้องมากว่าราคามัดจำชุด !');
        }
    }

    //เพิ่มข้อมูลการวัด
    public function addmeasument(Request $request, $id)
    {
        $savemeasument = new Dressmeasurement;
        $savemeasument->dress_id = $id;
        $savemeasument->measurement_dress_name = $request->input('measurement_dress_name');
        $savemeasument->measurement_dress_number = $request->input('measurement_dress_number');
        $savemeasument->measurement_dress_unit = $request->input('measurement_dress_unit');
        $savemeasument->save();
        return redirect()->back()->with('success', 'เพิ่มข้อมูลการวัดสำเร็จ !');
    }

    //อัปเดตข้อมูลการวัด
    public function updatemeasument(Request $request, $id)
    {
        $updatemeasument = Dressmeasurement::find($id);
        $updatemeasument->measurement_dress_number = $request->input('update_measurement_dress_number');
        $updatemeasument->measurement_dress_unit = $request->input('update_measurement_dress_unit');
        $updatemeasument->save();
        return redirect()->back()->with('success', "อัพเดตข้อมูลการวัดสำเร็จ !");
    }
    //ลบข้อมูลการวัด
    public function deletemeasument($id)
    {
        $delete = Dressmeasurement::find($id);
        $delete->delete();
        return redirect()->back()->with('success', 'ลบข้อมูลการวัดสำเร็จ !');
    }

    //เพิ่มรูปภาพชุด
    public function addimage(Request $request, $id)
    {
        $adddata = new Dressimage();
        $adddata->dress_id = $id;
        if ($request->hasFile('addimage')) {
            $adddata->dress_image = $request->file('addimage')->store('dress_images', 'public');
            $adddata->save();
        }
        return redirect()->back()->with('success', 'เพิ่มรูปภาพสำเร็จ !');
    }


    //บันทึกค่าใช้จ่าย
    public function expense(){
        // $dataexpense = Expense::all() ; 
        $dataexpense = Expense::orderBy('date','desc')->get() ; 
        return view('admin.expense',compact('dataexpense')) ; 
    }
    public function saveexpense(Request $request){
        // $test = Auth::user()->id ; 
        // dd($test) ; 
        $save = new Expense() ; 
        $save->date = $request->input('expense_date') ;
        $save->expense_type = $request->input('expense_type') ; 
        $save->expense_value = $request->input('expense_value') ; 
        $save->employee_id = Auth::user()->id ; 
        $save->save() ; 
        return redirect()->back()->with('success',"เพิ่มค่าใช้จ่ายสำเร็จ !") ; 
    }






}
