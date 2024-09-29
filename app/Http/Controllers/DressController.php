<?php

namespace App\Http\Controllers;

use App\Models\Dress;
use App\Models\Dressimage;
use App\Models\Dressmea;
use App\Models\Dressmeasurement;
use App\Models\Dressmeasurementnow;
use App\Models\Expense;
use App\Models\Shirtitem;
use App\Models\Skirtitem;
use App\Models\Typedress;
use App\Models\Orderdetail;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Dressmeasurementcutedit;
use App\Models\Repair;
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
        // dd($request->file('imagerent_')) ; 
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

            if ($maxDressCode) {
                //ถ้ามี
                $newDressCode = $maxDressCode + 1; //กำหนดให้หมายเลขชุดที่มี + 1 เพิ่มขึ้นไปเรื่อยๆ 
            } else {
                //ถ้าไม่มีก็ให้เริ่มที่ 1 
                $newDressCode = 1;
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
            // $dress->dress_title_name = $request->input('dress_title_name');
            // $dress->dress_color = $request->input('dress_color');
            if ($request->input('dress_deposit') > $request->input('dress_price')) {
                return redirect()->back()->with('fail', "ราคาชุดจะต้องมีการค่ามากกว่าราคามัดจำ !");
            } else {
                $dress->dress_price = $request->input('dress_price');
                $dress->dress_deposit = $request->input('dress_deposit');
            }
            $dress->damage_insurance = $request->input('damage_insurance');
            $dress->dress_count = 1;
            $dress->dress_status = "พร้อมให้เช่า";
            $dress->dress_description = $request->input('dress_description');
            $dress->dress_rental = 0;
            $dress->separable = $request->input('separable'); //1แยกไม่ได้ 2 แยกได้
            $dress->save();

            // เก็บ dress_code ที่ถูกสร้างไว้ใน list
            $dressCodes[] = $name_for_session . ' รหัสชุด ' . $unique_character . '' . $newDressCode;
            $newDressCode =  $newDressCode + 1; // บวก dress_code ขึ้นทีละ 1 

            $mea_dress_name = $request->input('measurement_dress_name_');
            $mea_dress_number = $request->input('measurement_dress_number_');
            $mea_dress_unit = $request->input('measurement_dress_unit_');

            // foreach ($mea_dress_name as $index => $mea) {
            //     $addmea = new Dressmeasurement;
            //     $addmea->dress_id  = $dress->id;
            //     $addmea->measurement_dress_name = $mea;
            //     $addmea->measurement_dress_number = $mea_dress_number[$index];
            //     $addmea->measurement_dress_unit = $mea_dress_unit[$index];
            //     $addmea->save();
            // }


            //รูปภาพ
            // if ($request->hasFile('imagerent_')) {
            //     $images = $request->file('imagerent_');
            //     // $images->image = $request->file('imagerent_')->store('dress_image','public') ; 
            //     foreach ($images as $index => $image) {
            //         $additionalImage = new Dressimage;
            //         $additionalImage->dress_id  = $dress->id;
            //         $additionalImage->dress_image = $image->store('dress_images', 'public');
            //         $additionalImage->save();
            //     }
            // }

            // ปรับเป็น 2 รูป
            if ($request->hasFile('add_image')) {
                $add_image = new Dressimage();
                $add_image->dress_id = $dress->id;
                $add_image->dress_image = $request->file('add_image')->store('dress_images', 'public');
                $add_image->save();
            }






            //ส่วนการแยกได้กับแยกไม่ได้
            if ($request->input('separable') == 1) {
                //แยกไม่ได้

                //ตารางเริ่มต้นdressmeasurement
                if ($request->input('no_shirt_measurement_dress_name_') != null) {
                    $no_shirt_measurement_dress_name = $request->input('no_shirt_measurement_dress_name_');
                    $no_shirt_measurement_dress_number = $request->input('no_shirt_measurement_dress_number_');
                    // $no_shirt_measurement_dress_unit = $request->input('no_shirt_measurement_dress_unit_');
                    foreach ($no_shirt_measurement_dress_name as $index => $no_shirt_meas_dress_name) {
                        $addmea = new Dressmea();
                        $addmea->dress_id  = $dress->id;
                        $addmea->mea_dress_name = $no_shirt_meas_dress_name;
                        $addmea->initial_mea = $no_shirt_measurement_dress_number[$index];
                        $addmea->current_mea = $no_shirt_measurement_dress_number[$index];
                        $addmea->save();
                    }
                }
            } elseif ($request->input('separable') == 2) {
                //แยกได้

                //ตารางshirtitem
                $add_shirtitem = new Shirtitem();
                $add_shirtitem->dress_id = $dress->id;
                $add_shirtitem->shirtitem_price = $request->input('shirt_price');
                $add_shirtitem->shirtitem_deposit = $request->input('shirt_deposit');
                $add_shirtitem->shirt_damage_insurance = $request->input('shirt_damage_insurance');
                $add_shirtitem->shirtitem_status = "พร้อมให้เช่า";
                $add_shirtitem->shirtitem_rental = 0;
                $add_shirtitem->save();

                //ตารางskirtitem
                $add_skirtitem = new Skirtitem();
                $add_skirtitem->dress_id = $dress->id;
                $add_skirtitem->skirtitem_price = $request->input('skirt_price');
                $add_skirtitem->skirtitem_deposit = $request->input('skirt_deposit');
                $add_skirtitem->skirt_damage_insurance = $request->input('skirt_damage_insurance');
                $add_skirtitem->skirtitem_status = "พร้อมให้เช่า";
                $add_skirtitem->skirtitem_rental = 0;
                $add_skirtitem->save();


                //เสื้อตารางdressmeasurement
                $yes_shirt_measurement_dress_name = $request->input('yes_shirt_measurement_dress_name_');
                $yes_shirt_measurement_dress_number = $request->input('yes_shirt_measurement_dress_number_');
                // $yes_shirt_measurement_dress_unit = $request->input('yes_shirt_measurement_dress_unit_');
                foreach ($yes_shirt_measurement_dress_name as $index => $yes_shirt_mea_dress_name) {
                    $add_item_shiry = new Dressmea();
                    $add_item_shiry->dress_id = $dress->id;
                    $add_item_shiry->shirtitems_id  = $add_shirtitem->id;
                    $add_item_shiry->mea_dress_name = $yes_shirt_mea_dress_name;
                    $add_item_shiry->initial_mea = $yes_shirt_measurement_dress_number[$index];
                    $add_item_shiry->current_mea = $yes_shirt_measurement_dress_number[$index];
                    $add_item_shiry->save();
                }

                //กระโปรงตารางdressmeasurement
                $yes_skirt_measurement_dress_name = $request->input('yes_skirt_measurement_dress_name_');
                $yes_skirt_measurement_dress_number = $request->input('yes_skirt_measurement_dress_number_');
                // $yes_skirt_measurement_dress_unit = $request->input('yes_skirt_measurement_dress_unit_');
                foreach ($yes_skirt_measurement_dress_name as $index => $yes_skirt_mea_dress_name) {
                    $add_item_skiry = new Dressmea();
                    $add_item_skiry->dress_id = $dress->id;
                    $add_item_skiry->skirtitems_id  = $add_skirtitem->id;
                    $add_item_skiry->mea_dress_name = $yes_skirt_mea_dress_name;
                    $add_item_skiry->initial_mea = $yes_skirt_measurement_dress_number[$index];
                    $add_item_skiry->current_mea = $yes_skirt_measurement_dress_number[$index];
                    $add_item_skiry->save();
                }
            }
        }
        return redirect()->back()->with('dressCodes', $dressCodes);
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
        // $showtype = Typedress::all();
        $showtype = TypeDress::all();
        // foreach ($showtype as $item) {
        //     $dress = Dress::where('type_dress_id', $item->id)->inRandomOrder()->first();
        //     if ($dress) {
        //         $item->image = Dressimage::where('dress_id', $dress->id)->inRandomOrder()->value('dress_image');
        //     } else {
        //         $item->image = null;
        //     }
        // }
        return view('admin.dresstotal', compact('showtype'));
    }


    public function typedress($id)
    {
        $type_dress_id = $id;
        $typedress = Typedress::find($id);
        $status = null; //กำหนดให้ตอนแรกมันว่างก่อนสิ
        $data = Dress::where('type_dress_id', $id)->with('dressimages')->get();
        return view('admin.typedress', compact('data', 'type_dress_id', 'status', 'typedress'));
    }

    //รายละเอียดชุด ละเอียดที่สุดแล้ว
    public function dressdetail(Request $request, $id)
    {
        $separable = $request->input('separable');

        if ($separable == 1) {
            return $this->dressdetailno($id);
        } elseif ($separable == 2) {
            return $this->dressdetailyes($id);
        }
    }
    //แยกไม่ได้
    private function dressdetailno($id)
    {
        $datadress = Dress::find($id);
        $name_type = Typedress::where('id', $datadress->type_dress_id)->value('type_dress_name');
        $imagedata = Dressimage::where('dress_id', $id)->get();
        $maxcount = Dressmeasurementnow::where('dress_id', $id)->max('count');
        $measument_no_separate = Dressmeasurement::where('dress_id', $id)->get();
        $measument_no_separate_now = Dressmeasurementnow::where('dress_id', $id)
            ->where('count', $maxcount)->get();
        $measument_no_separate_now_modal = Dressmeasurementnow::where('dress_id', $id)
            ->where('count', $maxcount)->get();

        $reservations = Reservation::where('dress_id', $id)
            ->where('status_completed', 0)
            ->orderByRaw("STR_TO_DATE(start_date , '%Y-%m-%d') asc")
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->get();


        $dress_status_now = Reservation::where('status_completed', 0)
            ->where('dress_id', $id)
            ->orderByRaw("STR_TO_DATE(start_date , '%Y-%m-%d') asc")
            ->first();

        $history_reservation = Orderdetail::where('dress_id', $id)
            ->where('status_detail', 'คืนชุดแล้ว')
            ->get();


        $date_reservations = Reservation::where('dress_id', $id)
            ->where('dress_id', $id)
            ->where('status_completed', 0)
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->get();


        $mea_dress = Dressmea::where('dress_id', $id)->get();
        return view('admin.dressdetail', compact('date_reservations', 'datadress', 'imagedata', 'name_type', 'measument_no_separate', 'measument_no_separate_now', 'measument_no_separate_now_modal', 'reservations', 'mea_dress', 'dress_status_now', 'history_reservation'));
    }

    // แยกได้
    private function dressdetailyes($id)
    {
        // dd($id);
        $datadress = Dress::find($id);
        $name_type = Typedress::where('id', $datadress->type_dress_id)->value('type_dress_name');
        $imagedata = Dressimage::where('dress_id', $id)->get();
        $shirtitem = Shirtitem::where('dress_id', $id)->first();
        $skirtitem = Skirtitem::where('dress_id', $id)->first();

        $reservation_dress = Reservation::where('dress_id', $id)
            ->where('shirtitems_id', null)
            ->where('skirtitems_id', null)
            ->where('status_completed', 0)
            ->orderByRaw("STR_TO_DATE(start_date , '%Y-%m-%d') asc")
            ->get();
        $dress_mea_totaldress = Dressmea::where('dress_id', $id)->get();
        //เสื้อ
        $maxcountshirt = Dressmeasurementnow::where('shirtitems_id', $shirtitem->id)->max('count');
        $measument_yes_separate_shirt = Dressmeasurement::where('shirtitems_id', $shirtitem->id)->get();
        $measument_yes_separate_now_shirt = Dressmeasurementnow::where('shirtitems_id', $shirtitem->id)
            ->where('count', $maxcountshirt)->get();
        $reservation_shirt = Reservation::where('shirtitems_id', $shirtitem->id)

            ->where('status_completed', 0)
            ->orderByRaw("STR_TO_DATE(start_date , '%Y-%m-%d') asc")
            ->get();
        $dress_mea_shirt = Dressmea::where('shirtitems_id', $shirtitem->id)->get();

        // กระโปรง
        $maxcountskirt = Dressmeasurementnow::where('skirtitems_id', $skirtitem->id)->max('count');
        $measument_yes_separate_skirt = Dressmeasurement::where('skirtitems_id', $skirtitem->id)->get();
        $measument_yes_separate_now_skirt = Dressmeasurementnow::where('skirtitems_id', $skirtitem->id)
            ->where('count', $maxcountskirt)->get();
        $reservation_skirt = Reservation::where('skirtitems_id', $skirtitem->id)
            ->where('status_completed', 0)
            ->orderByRaw("STR_TO_DATE(start_date , '%Y-%m-%d') asc")
            ->get();


        $measument_yes_separate_now_shirt_modal = Dressmeasurementnow::where('shirtitems_id', $shirtitem->id)
            ->where('count', $maxcountshirt)->get();
        $measument_yes_separate_now_skirt_modal = Dressmeasurementnow::where('skirtitems_id', $skirtitem->id)
            ->where('count', $maxcountskirt)->get();

        $dress_mea_skirt = Dressmea::where('skirtitems_id', $skirtitem->id)->get();


        $check_status_dress = Reservation::where('dress_id', $id)
            ->where('status_completed', 0)
            ->orderByRaw("STR_TO_DATE(start_date , '%Y-%m-%d') asc ")
            ->first();
        $check_status_shirt = Reservation::where('shirtitems_id', $shirtitem->id)
            ->where('status_completed', 0)
            ->orderByRaw("STR_TO_DATE(start_date , '%Y-%m-%d') asc ")
            ->first();

        $check_status_skirt = Reservation::where('skirtitems_id', $skirtitem->id)
            ->where('status_completed', 0)
            ->orderByRaw("STR_TO_DATE(start_date , '%Y-%m-%d') asc ")
            ->first();


        //สถานะล่าสุดของเสื้อ
        if ($check_status_dress != null && $check_status_shirt != null) {
            $convert_dress = strtotime($check_status_dress->start_date);
            $convert_shirt = strtotime($check_status_shirt->start_date);

            if ($convert_dress < $convert_shirt) {
                $text_check_status_shirt = $check_status_dress->status;
            } elseif ($convert_dress > $convert_shirt) {
                $text_check_status_shirt = $check_status_shirt->status;
            } elseif ($convert_dress == $convert_shirt) {
                $text_check_status_shirt = $check_status_dress->status;
            }
        } elseif ($check_status_dress != null && $check_status_shirt == null) {
            $text_check_status_shirt = $check_status_dress->status;
        } elseif ($check_status_dress == null && $check_status_shirt != null) {
            $text_check_status_shirt =  $check_status_shirt->status;
        } elseif ($check_status_dress == null && $check_status_shirt == null) {
            $text_check_status_shirt = "เสื้ออยู่ในร้าน ไม่มีคิวจอง";
        }


        //สถานะล่าสุดของผ้าถุง
        if ($check_status_dress != null && $check_status_skirt != null) {
            $convert_dress = strtotime($check_status_dress->start_date);
            $convert_skirt = strtotime($check_status_skirt->start_date);

            if ($convert_dress < $convert_skirt) {
                $text_check_status_skirt = $check_status_dress->status;
            } elseif ($convert_dress > $convert_skirt) {
                $text_check_status_skirt = $check_status_skirt->status;
            } elseif ($convert_dress == $convert_skirt) {
                $text_check_status_skirt = $check_status_dress->status;
            }
        } elseif ($check_status_dress != null && $check_status_skirt == null) {
            $text_check_status_skirt = $check_status_dress->status;
        } elseif ($check_status_dress == null && $check_status_skirt != null) {
            $text_check_status_skirt =  $check_status_skirt->status;
        } elseif ($check_status_dress == null && $check_status_skirt == null) {
            $text_check_status_skirt = "ผ้าถุงอยู่ในร้าน ไม่มีคิวจอง";
        }






        return view('admin.dressdetailyes', compact('text_check_status_shirt', 'text_check_status_skirt',  'datadress', 'imagedata', 'name_type', 'shirtitem', 'skirtitem', 'measument_yes_separate_shirt', 'measument_yes_separate_now_shirt', 'measument_yes_separate_skirt', 'measument_yes_separate_now_skirt', 'measument_yes_separate_now_shirt_modal', 'measument_yes_separate_now_skirt_modal', 'reservation_shirt', 'reservation_skirt', 'reservation_dress', 'dress_mea_shirt', 'dress_mea_skirt', 'dress_mea_totaldress'));
    }











    //อัปเดตชุดnoyes
    public function updatedressnoyes(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            //ตารางdress
            $update_dress = Dress::find($id);
            if ($request->input('update_dress_deposit') > $request->input('update_dress_price')) {
                DB::rollback();
                return redirect()->back()->with('fail', 'ราคาชุดต้องมากกว่าราคามัดจำ');
            }
            $update_dress->dress_price = $request->input('update_dress_price');
            $update_dress->dress_deposit = $request->input('update_dress_deposit');
            $update_dress->damage_insurance = $request->input('update_damage_insurance');
            $update_dress->dress_status = $request->input('update_dress_status');
            $update_dress->dress_description = $request->input('update_dress_description');
            $update_dress->save();

            if ($request->input('mea_now_id_') != null) {
                $mea_now_id = $request->input('mea_now_id_');
                $mea_now_name = $request->input('mea_now_name_');
                $mea_now_number = $request->input('mea_now_number_');
                // $mea_now_unit = $request->input('mea_now_unit_');
                foreach ($mea_now_id as $index => $mea_now_id) {
                    $update_mea_now = Dressmeasurementnow::find($mea_now_id);
                    $update_mea_now->measurementnow_dress_number = $mea_now_number[$index];
                    $update_mea_now->measurementnow_dress_unit = "นิ้ว";
                    $update_mea_now->save();
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'อัพเดตข้อมูลสำเร็จ !');
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    //อัปเดตชุดyesshirt
    public function updatedressyesshirt(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            //ตารางshirt
            $update_shirt = Shirtitem::find($id);
            if ($request->input('update_shirt_deposit') > $request->input('update_shirt_price')) {
                DB::rollback();
                return redirect()->back()->with('fail', 'ราคาชุดต้องมากกว่าราคามัดจำ');
            }
            $update_shirt->shirtitem_price = $request->input('update_shirt_price');
            $update_shirt->shirtitem_deposit = $request->input('update_shirt_deposit');
            $update_shirt->shirt_damage_insurance = $request->input('update_shirt_damage_insurance');
            $update_shirt->shirtitem_status = $request->input('update_shirt_status');
            $update_shirt->save();


            if ($request->input('mea_now_id_') != null) {
                $mea_now_id = $request->input('mea_now_id_');
                $mea_now_name = $request->input('mea_now_name_');
                $mea_now_number = $request->input('mea_now_number_');
                // $mea_now_unit = $request->input('mea_now_unit_');
                foreach ($mea_now_id as $index => $mea_now_id) {
                    $update_mea_now = Dressmeasurementnow::find($mea_now_id);
                    $update_mea_now->measurementnow_dress_number = $mea_now_number[$index];
                    $update_mea_now->measurementnow_dress_unit = "นิ้ว";
                    $update_mea_now->save();
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'อัพเดตข้อมูลสำเร็จ !');
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    //อัปเดตชุดyesshirt
    public function updatedressyesskirt(Request $request, $id)
    {

        DB::beginTransaction();
        try {
            //ตารางskirt
            $update_skirt = Skirtitem::find($id);
            if ($request->input('update_skirt_deposit') > $request->input('update_skirt_price')) {
                DB::rollback();
                return redirect()->back()->with('fail', 'ราคาชุดต้องมากกว่าราคามัดจำ');
            }
            $update_skirt->skirtitem_price = $request->input('update_skirt_price');
            $update_skirt->skirtitem_deposit = $request->input('update_skirt_deposit');
            $update_skirt->skirt_damage_insurance = $request->input('update_skirt_damage_insurance');
            $update_skirt->skirtitem_status = $request->input('update_skirt_status');
            $update_skirt->save();

            if ($request->input('mea_now_id_') != null) {
                $mea_now_id = $request->input('mea_now_id_');
                $mea_now_name = $request->input('mea_now_name_');
                $mea_now_number = $request->input('mea_now_number_');
                // $mea_now_unit = $request->input('mea_now_unit_');
                foreach ($mea_now_id as $index => $mea_now_id) {
                    $update_mea_now = Dressmeasurementnow::find($mea_now_id);
                    $update_mea_now->measurementnow_dress_number = $mea_now_number[$index];
                    $update_mea_now->measurementnow_dress_unit = "นิ้ว";
                    $update_mea_now->save();
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'อัพเดตข้อมูลสำเร็จ !');
        } catch (\Exception $e) {
            DB::rollback();
        }
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

    //เพิ่มข้อมูลการวัดno
    public function addmeasumentno(Request $request, $id)
    {
        // add_mea_now_unit_
        $max = Dressmeasurementnow::where('dress_id', $id)->max('count');
        //ตาราง dressmeasurement
        $add_mea_now_name = $request->input('add_mea_now_name_');
        $add_mea_now_number = $request->input('add_mea_now_number_');
        // $add_mea_now_unit = $request->input('add_mea_now_unit_');
        foreach ($add_mea_now_name as $index => $add_mea_now_name) {
            $add_save_measument = new Dressmeasurement;
            $add_save_measument->dress_id = $id;
            $add_save_measument->measurement_dress_name = $add_mea_now_name;
            $add_save_measument->measurement_dress_number = $add_mea_now_number[$index];
            $add_save_measument->measurement_dress_unit = "นิ้ว";
            $add_save_measument->save();
        }
        //ตาราง dressmeasurementnow
        $add_mea_now_name = $request->input('add_mea_now_name_');
        $add_mea_now_number = $request->input('add_mea_now_number_');
        // $add_mea_now_unit = $request->input('add_mea_now_unit_');
        foreach ($add_mea_now_name as $index => $add_mea_now_name) {
            $add_mea_now = new Dressmeasurementnow;
            $add_mea_now->dress_id  = $id;
            $add_mea_now->measurementnow_dress_name = $add_mea_now_name;
            $add_mea_now->measurementnow_dress_number = $add_mea_now_number[$index];
            $add_mea_now->measurementnow_dress_number_start = $add_mea_now_number[$index];
            $add_mea_now->measurementnow_dress_unit = "นิ้ว";
            $add_mea_now->count = $max;
            $add_mea_now->save();
        }
        return redirect()->back()->with('success', 'เพิ่มข้อมูลการวัดสำเร็จ !');
    }








    //เพิ่มข้อมูลการวัดyesyshirt
    public function addmeasumentyesshirt(Request $request, $id)
    {
        $max = Dressmeasurementnow::where('shirtitems_id', $id)->max('count');
        //ตาราง dressmeasurement
        $add_mea_now_name = $request->input('add_mea_now_name_');
        $add_mea_now_number = $request->input('add_mea_now_number_');
        // $add_mea_now_unit = $request->input('add_mea_now_unit_');
        foreach ($add_mea_now_name as $index => $add_mea_now_name) {
            $add_save_measument = new Dressmeasurement;
            $add_save_measument->dress_id = $request->input('dress_id');
            $add_save_measument->shirtitems_id = $id;
            $add_save_measument->measurement_dress_name = $add_mea_now_name;
            $add_save_measument->measurement_dress_number = $add_mea_now_number[$index];
            $add_save_measument->measurement_dress_unit = "นิ้ว";
            $add_save_measument->save();
        }
        //ตาราง dressmeasurementnow
        $add_mea_now_name = $request->input('add_mea_now_name_');
        $add_mea_now_number = $request->input('add_mea_now_number_');
        // $add_mea_now_unit = $request->input('add_mea_now_unit_');
        foreach ($add_mea_now_name as $index => $add_mea_now_name) {
            $add_mea_now = new Dressmeasurementnow;
            $add_mea_now->dress_id = $request->input('dress_id');
            $add_mea_now->shirtitems_id  = $id;
            $add_mea_now->measurementnow_dress_name = $add_mea_now_name;
            $add_mea_now->measurementnow_dress_number = $add_mea_now_number[$index];
            $add_mea_now->measurementnow_dress_number_start = $add_mea_now_number[$index];
            $add_mea_now->measurementnow_dress_unit = "นิ้ว";
            $add_mea_now->count = $max;
            $add_mea_now->save();
        }
        return redirect()->back()->with('success', 'เพิ่มข้อมูลการวัดสำเร็จ !');
    }

    //เพิ่มข้อมูลการวัดyesyskirt
    public function addmeasumentyesskirt(Request $request, $id)
    {
        $max = Dressmeasurementnow::where('skirtitems_id', $id)->max('count');
        //ตาราง dressmeasurement
        $add_mea_now_name = $request->input('add_mea_now_name_');
        $add_mea_now_number = $request->input('add_mea_now_number_');
        // $add_mea_now_unit = $request->input('add_mea_now_unit_');
        foreach ($add_mea_now_name as $index => $add_mea_now_name) {
            $add_save_measument = new Dressmeasurement;
            $add_save_measument->dress_id = $request->input('dress_id');
            $add_save_measument->skirtitems_id = $id;
            $add_save_measument->measurement_dress_name = $add_mea_now_name;
            $add_save_measument->measurement_dress_number = $add_mea_now_number[$index];
            $add_save_measument->measurement_dress_unit = "นิ้ว";
            $add_save_measument->save();
        }
        //ตาราง dressmeasurementnow
        $add_mea_now_name = $request->input('add_mea_now_name_');
        $add_mea_now_number = $request->input('add_mea_now_number_');
        // $add_mea_now_unit = $request->input('add_mea_now_unit_');
        foreach ($add_mea_now_name as $index => $add_mea_now_name) {
            $add_mea_now = new Dressmeasurementnow;
            $add_mea_now->dress_id = $request->input('dress_id');
            $add_mea_now->skirtitems_id = $id;
            $add_mea_now->measurementnow_dress_name = $add_mea_now_name;
            $add_mea_now->measurementnow_dress_number = $add_mea_now_number[$index];
            $add_mea_now->measurementnow_dress_number_start = $add_mea_now_number[$index];
            $add_mea_now->measurementnow_dress_unit = "นิ้ว";
            $add_mea_now->count = $max;
            $add_mea_now->save();
        }
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
    public function expense()
    {
        $dataexpense = Expense::orderBy('date', 'desc')->get();
        return view('admin.expense', compact('dataexpense'));
    }



    public function saveexpense(Request $request)
    {

        if ($request->input('expense_type') == 'other_expense') {
            $expense_type = $request->input('other_expense_type');
        } else {
            $expense_type = $request->input('expense_type');
        }

        $save = new Expense();
        $save->date = $request->input('expense_date');
        $save->expense_type = $expense_type;
        $save->expense_value = $request->input('expense_value');
        $save->employee_id = Auth::user()->id;
        $save->save();
        return redirect()->back()->with('success', "เพิ่มค่าใช้จ่ายสำเร็จ !");
    }


    public function testtest()
    {
        $users = User::all();
        return view('admin.test', compact('users'));
    }

    public function search(Request $request)
    {

        $nameSearch = $request->input('name');
        $lnameSearch = $request->input('lname');

        $query = User::query();

        if ($nameSearch) {
            $query->where('name', 'LIKE', '%' . $nameSearch . '%');
        }
        if ($lnameSearch) {
            $query->where('lname', 'LIKE', '%' . $lnameSearch . '%');
        }

        $users = $query->get();
        return view('admin.test', compact('users'));
    }

    public function searchstatusdress(Request $request)
    {
        $status = $request->input('search_status_of_dress');
        $type_dress_id = $request->input('type_dress_id');


        if ($status == "พร้อมให้เช่า") {
            $data = Dress::where('type_dress_id', $type_dress_id)
                ->where('dress_status', $status)->get();
        }
        if ($status == "ถูกจองแล้ว") {
            $data = Dress::where('type_dress_id', $type_dress_id)
                ->where('dress_status', $status)->get();
        }
        if ($status == "ทั้งหมด") {
            $data = Dress::where('type_dress_id', $type_dress_id)->get();
            return $this->typedress($type_dress_id);
        }
        return view('admin.typedress', compact('data', 'type_dress_id', 'status'));
    }


    public function testtab()
    {


        return view('testtab');
    }


    protected function getAllRooms()
    {
        return Dress::all();
    }
    public function dresslist()
    {
        $typedresss = Typedress::with('dresses')->get();
        return view('admin.dresslist', compact('typedresss'));
    }

    public function historydressadjust($id)
    {
        $dress = Dress::find($id);
        if ($dress->separable == 1) {
            return $this->dresslistno($id);
        } elseif ($dress->separable == 2) {
            return $this->dresslistyes($id);
        }
    }

    private function dresslistno($id)
    {
        $dress = Dress::find($id);
        $typedress = Typedress::where('id', $dress->type_dress_id)->first();
        $history = Dressmeasurementcutedit::where('dress_id', $id)->get();
        return view('admin.his-dress-adjust-no', compact('dress', 'typedress', 'history'));
    }

    private function dresslistyes($id)
    {
        $dress = Dress::find($id);
        $typedress = Typedress::where('id', $dress->type_dress_id)->first();

        $shirt_id = Shirtitem::where('dress_id', $id)->value('id');
        $skirt_id = Skirtitem::where('dress_id', $id)->value('id');
        $history_shirt = Dressmeasurementcutedit::where('shirtitems_id', $shirt_id)->get();
        $history_skirt = Dressmeasurementcutedit::where('skirtitems_id', $skirt_id)->get();
        return view('admin.his-dress-adjust-yes', compact('dress', 'typedress', 'history_shirt', 'history_skirt'));
    }

    // ตัวแยกประวัติการซ่อม
    public function historydressrepair($id)
    {
        $dress = Dress::find($id);
        if ($dress->separable == 1) {
            return $this->hisrepairno($id);
        } elseif ($dress->separable == 2) {
            return $this->hisrepairyes($id);
        }
    }


    private function hisrepairno($id)
    {
        $dress = Dress::find($id);
        $typedressname = Typedress::find($dress->type_dress_id);


        $reservation = Reservation::where('dress_id', $id)->get();
        $list_one = [];
        $list_two = [];
        foreach ($reservation as $item) {
            $list_one[] = $item->id;
        }

        foreach ($list_one as $item) 
            {
            $findreservation = Repair::where('reservation_id', $item)
                ->whereIn('repair_status', ['ซ่อมเสร็จแล้ว', 'กำลังซ่อม'])->get();
            if($findreservation->isNotEmpty()){
                foreach($findreservation as $item){
                    $list_two[] = $item->id ; 
                }
            }
        }

        $history = Repair::whereIn('id',$list_two)->get() ; 
        return view('admin.hist-dress-repair-no', compact('dress', 'typedressname','history'));
    }


    private function hisrepairyes($id)
    {
        return view('admin.hist-dress-repair-yes');
    }
}
