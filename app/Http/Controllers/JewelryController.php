<?php

namespace App\Http\Controllers;

use App\Models\Jewelry;
use App\Models\Jewelryimage;
use App\Models\Jewelryset;
use App\Models\Jewelrysetitem;
use App\Models\Typejewelry;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $list_for_session =  [];
        $select_type = $request->input('select_type');
        if ($select_type === "select_other") {
            $input_other = $request->input('input_other');
            $check_type_name = Typejewelry::where('type_jewelry_name', $input_other)->value('id');

            if ($check_type_name) {
                $TYPE_JEW_ID = $check_type_name;
                $maxcode = Jewelry::where('type_jewelry_id', $check_type_name)->max('jewelry_code');

                $session_name_type = Typejewelry::where('id', $check_type_name)->value('type_jewelry_name');
                $string_name = Typejewelry::where('id', $check_type_name)->value('specific_letter');    
         
            } else {
                do {
                    $character = chr(65 + rand(0, 25));
                    $check_string = Typejewelry::where('specific_letter', $character)->first();
                } while ($check_string);
                $cre_type_jew = new Typejewelry();
                $cre_type_jew->type_jewelry_name = $input_other;
                $cre_type_jew->specific_letter = $character;
                $cre_type_jew->save();
                $TYPE_JEW_ID = $cre_type_jew->id;
                $maxcode = 0;
                $session_name_type = $input_other;
                $string_name = $character;
            }
        }
        else {
            $session_name_type = Typejewelry::where('id', $select_type)->value('type_jewelry_name');
            $TYPE_JEW_ID = $select_type;
            $maxcode = Jewelry::where('type_jewelry_id', $select_type)->max('jewelry_code');
            $string_name = Typejewelry::where('id', $select_type)->value('specific_letter');
        }
        $jewelry_count = $request->input('jewelry_count'); //ตัวหมุน


        for ($i = 0; $i < $jewelry_count; $i++) {
            $maxcode = $maxcode + 1;
            $create_jew = new Jewelry();
            $create_jew->type_jewelry_id = $TYPE_JEW_ID;
            $create_jew->jewelry_code = $maxcode;
            $create_jew->jewelry_price = $request->input('jewelry_price');
            $create_jew->jewelry_deposit = $request->input('jewelry_deposit');
            $create_jew->damage_insurance = $request->input('damage_insurance');
            $create_jew->jewelry_count = 1;
            $create_jew->jewelry_status = 'พร้อมให้เช่า';
            $create_jew->jewelry_description = $request->input('jewelry_description');
            $create_jew->jewelry_rental = 0;
            $create_jew->save();

            $list_for_session[] = $session_name_type . ' รหัสเครื่องประดับ ' . $string_name . '' . $maxcode;

            // ตารางรูปภาพ
            if ($request->hasFile('jewelry_image')) {
                $create_image = new Jewelryimage();
                $create_image->jewelry_id = $create_jew->id;
                $create_image->jewelry_image = $request->file('jewelry_image')->store('jewelry_images', 'public');
                $create_image->save();
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
        $typename = Typejewelry::where('id',$id)->value('type_jewelry_name') ; 
        $datajewelry = Jewelry::where('type_jewelry_id', $id)->with('jewelryimages')->get();
        return view('adminjewelry.typejewelry', compact('datajewelry','typename'));
    }

    //รายละเอียดย่อยที่สุดแล้ว
    public function jewelrydetail($id)
    {
        $datajewelry = Jewelry::find($id);
        $data_type = Typejewelry::where('id', $datajewelry->type_jewelry_id)->first();
        $dataimage = Jewelryimage::where('jewelry_id', $id)->first();
        $jew_in_set = Jewelrysetitem::where('jewelry_id',$id)->get() ; 
        $is_admin = Auth::user()->is_admin ; 
        return view('adminjewelry.jewelrydetail', compact('datajewelry', 'dataimage', 'data_type','jew_in_set','is_admin'));
    }
    

    //อัปเดตเครื่องประดับ
    public function updatejewelry(Request $request, $id)
    {
        $savedata = Jewelry::find($id);
        $savedata->jewelry_price = $request->input('update_price') ; 
        $savedata->jewelry_deposit = $request->input('update_deposit') ; 
        $savedata->damage_insurance = $request->input('update_damage_insurance') ; 
        $savedata->jewelry_description = $request->input('update_dress_description');
        $savedata->save();
        return redirect()->back()->with('success', 'อัพเดตข้อมูลสำเร็จ !');
    }
    

    // หน้าสำหรับแสดงจัดเซต
    public function managesetjewelry(){
        $typejewelry = Typejewelry::all() ; 
        $jewtotal = Typejewelry::with('jewelrys')->get() ; 
        $typeJewelry_id = '01' ; 
        return view('adminjewelry.managesetjewelry',compact('typejewelry','jewtotal','typeJewelry_id')) ; 
    }
    // หน้าหลังจากฟิลเตอร์ประเภทแล้ว
    public function managesetjewelryfilter(Request $request){
        $typeJewelry_id = $request->input('typeJewelry_id') ; 
        if($typeJewelry_id == '01'){
            return $this->managesetjewelry() ; 
        }
        else{
            $jewtotal = Typejewelry::with('jewelrys')
                            ->where('id',$typeJewelry_id)
                            ->get() ;
        }
        $typejewelry = Typejewelry::all() ; 
        return view('adminjewelry.managesetjewelry',compact('typejewelry','jewtotal','typeJewelry_id')) ; 
    }

    public function managesetjewelrysubmit(Request $request){
        $list_select_jew_id = $request->input('list_select_jew_id') ;
        $list_select_jew_id_replace = explode(',',$list_select_jew_id) ; 

        $create_set_jew = new Jewelryset() ;
        $create_set_jew->set_name = $request->input('set_name') ; 
        $create_set_jew->set_price = $request->input('set_price') ; 
        $create_set_jew->set_status = 'พร้อมให้เช่า' ; 
        $create_set_jew->save() ; 
        
        
        $set_id = $create_set_jew->id ; 
        foreach($list_select_jew_id_replace as $item){
            $create_set_item_jew = new Jewelrysetitem() ; 
            $create_set_item_jew->jewelry_set_id = $set_id ; 
            $create_set_item_jew->jewelry_id = $item ; 
            $create_set_item_jew->save() ; 
        }
        return redirect()->back()->with('success','เพิ่มเช็ตสำเร็จแล้ว') ; 
    }

    public function setjewelry(){
        $Jewelryset = Jewelryset::all() ; 
        return view('adminjewelry.setjewelry',compact('Jewelryset')) ; 
    }
    public function setjewelrydetail($id){
        $jewelryset = Jewelryset::find($id) ; 
        $Jewelrysetitem = Jewelrysetitem::where('jewelry_set_id',$id)->get() ;
        $is_admin = Auth::user()->is_admin ; 
        return view('adminjewelry.setjewelrydetail',compact('jewelryset','Jewelrysetitem','is_admin')) ; 
    }


    
}
