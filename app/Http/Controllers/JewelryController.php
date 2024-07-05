<?php

namespace App\Http\Controllers;

use App\Models\Jewelry;
use App\Models\Jewelryimage;
use App\Models\Typejewelry;
use Illuminate\Http\Request;

class JewelryController extends Controller
{
    //หน้าเพิ่มเพิ่มประดับ
    public function formaddjewelry()
    {
        $typejewelry = Typejewelry::all();
        return view('adminjewelry.addjewelry', compact('typejewelry'));
    }

    public function savejewelry(Request $request)
    {

        $count_direction = $request->input('jewelry_count');  //ตัวกำหนดทิศทางซึ่งก็คือ จำนวนเครื่องประดบัที่ทำการเพิ่ม
        $list_for_session =  [];

        // dd($request->input('type_jewelry_id')) ; 
        //เลือกอื่นๆ
        if ($request->input('type_jewelry_id') == "select_other") {
            $checkdata = Typejewelry::where('type_jewelry_name', $request->input('inputother'))->first();
            if (!$checkdata) {

                //แรนด้อมอักษร1ตัว
                do {
                    $character = chr(65 + rand(0, 25));

                    $check = Typejewelry::where('specific_letter', $character)->first();
                } while ($check);

                $CHR = $character; //เก็บตัวอักษร

                $savetypejewelry = new Typejewelry();
                $savetypejewelry->type_jewelry_name = $request->input('inputother');
                $savetypejewelry->specific_letter = $CHR;
                $savetypejewelry->save();
                $TYPE_ID = $savetypejewelry->id;
                $maxcode = 1;
                $name_for_session = $request->input('inputother');
            } else {
                return redirect()->back()->with('fail', "เครื่องประดับซ้ำกับที่มีอยู่แล้ว โปรดออกเครื่องประดับที่ไม่ซ้ำ");
            }
        }
        //เลือกในดรอปดาว
        else {
            $TYPE_ID = $request->input('type_jewelry_id');
            $CHR = Typejewelry::where('id', $request->input('type_jewelry_id'))->value('specific_letter');
            $name_for_session = Typejewelry::where('id', $request->input('type_jewelry_id'))->value('type_jewelry_name');
            $maxcode = Jewelry::where('type_jewelry_id', $request->input('type_jewelry_id'))->max('jewelry_code');
            $maxcode = $maxcode + 1;
        }

        for ($i = 0; $i < $count_direction; $i++) {
            //ตารางjewelry
            $jewelry = new Jewelry();
            $jewelry->type_jewelry_id  = $TYPE_ID;
            $jewelry->jewelry_code =  $maxcode;
            $jewelry->jewelry_title_name = $request->input('jewelry_title_name');
            $jewelry->jewelry_code_new = $CHR;
            // $jewelry->jewelry_price = $request->input('jewelry_price');
            // $jewelry->jewelry_deposit = $request->input('jewelry_deposit');
            if ($request->input('jewelry_deposit') > $request->input('jewelry_price')) {
                return redirect()->back()->with('fail', "ราคาเครื่องประดับจะต้องมีการค่ามากกว่าราคามัดจำเครื่องประดับ !");
            } else {
                $jewelry->jewelry_price = $request->input('jewelry_price');
                $jewelry->jewelry_deposit = $request->input('jewelry_deposit');
            }

            $jewelry->jewelry_count = 1;
            $jewelry->jewelry_status = "พร้อมให้เช่า";
            $jewelry->jewelry_description = $request->input('jewelry_description');
            $jewelry->jewelry_rental = 0;
            $jewelry->save();
            $list_for_session[] = $name_for_session . ' รหัสเครื่องประดับ ' . $CHR . '' . $maxcode; //ส่งข้อมูลไปแจ้งให้แอดมินทราบ
            $maxcode++;
            //ตารางjewelryimage
            if ($request->hasFile('jewelry_image_')) {
                $jewelry_image_turn = $request->file('jewelry_image_');
                foreach ($jewelry_image_turn as $index => $image) {
                    $saveimage = new Jewelryimage();
                    $saveimage->jewelry_id = $jewelry->id;
                    $saveimage->jewelry_image = $image->store('jewelry_images', 'public');
                    $saveimage->save();
                }
            }
        }
        return redirect()->back()->with('warn', $list_for_session);
    }

    //แสดงประเภทชุดทั้งหมด
    public function jewelrytotal()
    {
        $showtype = Typejewelry::all();
        return view('adminjewelry.jewelrytotal', compact('showtype'));
    }

    //แสดงเฉพาะประเภทชุดทั้งหมดที่เลือกนะ
    public function typejewelry($id)
    {
        $datajewelry = Jewelry::where('type_jewelry_id', $id)->with('jewelryimages')->get();
        return view('adminjewelry.typejewelry', compact('datajewelry'));
    }

    //รายละเอียดย่อยที่สุดแล้ว
    public function jewelrydetail($id)
    {
        $datajewelry = Jewelry::find($id);
        $data_type_name = Typejewelry::where('id', $datajewelry->type_jewelry_id)->value('type_jewelry_name');
        $dataimage = Jewelryimage::where('jewelry_id', $id)->get();
        return view('adminjewelry.jewelrydetail', compact('datajewelry', 'dataimage', 'data_type_name'));
    }
    //เพิ่มรูปภาพเครื่องประดับ
    public function addjewelryimage(Request $request, $id)
    {
        $save = new Jewelryimage();
        $save->jewelry_id = $id;
        if ($request->file('jewelry_image')) {
            $save->jewelry_image = $request->file('jewelry_image')->store('jewelry_images', 'public');
            $save->save();
        }
        return redirect()->back()->with('success', 'เพิ่มรูปภาพสำเร็จ !');
    }

    //อัปเดตเครื่องประดับ
    public function updatejewelry(Request $request, $id)
    {
        $savedata = Jewelry::find($id);
        $savedata->jewelry_title_name = $request->input('update_jewelry_title_name');
        $savedata->jewelry_description = $request->input('update_jewelry_description');
        $savedata->save();
        return redirect()->back()->with('success', 'อัพเดตข้อมูลสำเร็จ !');
    }
    //อัปเดตราคาเครื่องประดับ
    public function updatepricejewelry(Request  $request,  $id)
    {
        $update = Jewelry::find($id);
        if ($request->input('update_jewelry_deposit') > $request->input('update_jewelry_price')) {
            return redirect()->back()->with('fail', "ราคามัดจำต้องมากกว่าราคาเต็มของเครื่องประดับ");
        } else {
            $update->jewelry_price = $request->input('update_jewelry_price') ; 
            $update->jewelry_deposit = $request->input('update_jewelry_deposit') ; 
            $update->save();
        }
        return redirect()->back()->with('success', "อัปเดตราคาสำเร็จ !");
    }
}
