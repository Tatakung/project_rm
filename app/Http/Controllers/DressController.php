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

use App\Models\PriceHistory_Shirt;
use App\Models\PriceHistory_Skirt;


use App\Models\Typedress;
use App\Models\Orderdetail;
use App\Models\PriceHistory_Dress;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Dressmeasurementcutedit;
use App\Models\Date;
use App\Models\Reservationfilterdress;

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
            $dress->source_type = 1; //1 ชุดเปล่าๆ 2ชุดที่ได้มาจากการเช่าตัด
            $dress->separable = $request->input('rental_option'); //1แยกไม่ได้ 2 แยกได้
            $dress->save();

            // เก็บ dress_code ที่ถูกสร้างไว้ใน list
            $dressCodes[] = $name_for_session . ' รหัสชุด ' . $unique_character . '' . $newDressCode;
            $newDressCode =  $newDressCode + 1; // บวก dress_code ขึ้นทีละ 1 

            $mea_dress_name = $request->input('measurement_dress_name_');
            $mea_dress_number = $request->input('measurement_dress_number_');
            $mea_dress_unit = $request->input('measurement_dress_unit_');


            // ปรับเป็น 2 รูป
            if ($request->hasFile('add_image')) {
                $add_image = new Dressimage();
                $add_image->dress_id = $dress->id;
                $add_image->dress_image = $request->file('add_image')->store('dress_images', 'public');
                $add_image->save();
            }




            //ส่วนการแยกได้กับแยกไม่ได้
            if ($request->input('rental_option') == 1) {
                //แยกไม่ได้

                //ตารางเริ่มต้นdressmeasurement
                if ($request->input('name_total_') != null) {
                    $name_total = $request->input('name_total_');
                    $number_total = $request->input('number_total_');
                    $number_total_min = $request->input('number_total_min_');
                    $number_total_max = $request->input('number_total_max_');
                    foreach ($name_total as $index => $name) {
                        $addmea = new Dressmea();
                        $addmea->dress_id  = $dress->id;
                        $addmea->mea_dress_name = $name;
                        $addmea->initial_mea = $number_total[$index];
                        $addmea->initial_min = $number_total_min[$index];
                        $addmea->initial_max = $number_total_max[$index];
                        $addmea->current_mea = $number_total[$index];
                        $addmea->save();
                    }
                }
            } elseif ($request->input('rental_option') == 2) {
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
                $name_shirt = $request->input('name_shirt_');
                $number_shirt = $request->input('number_shirt_');
                $number_shirt_min = $request->input('number_shirt_min_');
                $number_shirt_max = $request->input('number_shirt_max_');
                foreach ($name_shirt as $index => $name_sh) {
                    $add_item_shiry = new Dressmea();
                    $add_item_shiry->dress_id = $dress->id;
                    $add_item_shiry->shirtitems_id  = $add_shirtitem->id;
                    $add_item_shiry->mea_dress_name = $name_sh;
                    $add_item_shiry->initial_mea = $number_shirt[$index];
                    $add_item_shiry->initial_min = $number_shirt_min[$index];
                    $add_item_shiry->initial_max = $number_shirt_max[$index];
                    $add_item_shiry->current_mea = $number_shirt[$index];
                    $add_item_shiry->save();
                }

                //กระโปรงตารางdressmeasurement
                $name_skirt = $request->input('name_skirt_');
                $number_skirt = $request->input('number_skirt_');
                $number_skirt_min = $request->input('number_skirt_min_');
                $number_skirt_max = $request->input('number_skirt_max_');
                foreach ($name_skirt as $index => $name_sk) {
                    $add_item_skiry = new Dressmea();
                    $add_item_skiry->dress_id = $dress->id;
                    $add_item_skiry->skirtitems_id  = $add_skirtitem->id;
                    $add_item_skiry->mea_dress_name = $name_sk;
                    $add_item_skiry->initial_mea = $number_skirt[$index];
                    $add_item_skiry->initial_min = $number_skirt_min[$index];
                    $add_item_skiry->initial_max = $number_skirt_max[$index];
                    $add_item_skiry->current_mea = $number_skirt[$index];
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
            ->where('status_completed', 0)
            // ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->where('status', '!=', 'อยู่ในตะกร้า')
            ->get();

        $reser_dress_stopRent = Reservation::where('dress_id', $id)
            ->where('dress_id', $id)
            ->where('status_completed', 0)
            ->where('status', 'ถูกจอง')
            ->get();



        $mea_dress = Dressmea::where('dress_id', $id)->get();

        $historydress = PriceHistory_Dress::where('dress_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        $check_admin = Auth::user()->is_admin;
        return view('admin.dressdetail', compact('date_reservations', 'datadress', 'imagedata', 'name_type', 'measument_no_separate', 'measument_no_separate_now', 'measument_no_separate_now_modal', 'reservations', 'mea_dress', 'dress_status_now', 'history_reservation', 'check_admin', 'historydress', 'reser_dress_stopRent'));
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




        //คิวเช่าเฉพาะทั้งชุด
        $date_reservations_dress = Reservation::where('dress_id', $id)
            ->whereNull('shirtitems_id')
            ->whereNull('skirtitems_id')
            ->where('status_completed', 0)
            ->where('status', '!=', 'อยู่ในตะกร้า')
            ->get();

        // คิวเช่าเฉพาะเสื้อ
        $date_reservations_shirt = Reservation::where('shirtitems_id', $shirtitem->id)
            ->where('status_completed', 0)
            ->where('status', '!=', 'อยู่ในตะกร้า')
            ->get();

        //คิวเช่าเฉพาะผ้าถุง
        $date_reservations_skirt = Reservation::where('skirtitems_id', $skirtitem->id)
            ->where('status_completed', 0)
            ->where('status', '!=', 'อยู่ในตะกร้า')
            ->get();

        $historydress = PriceHistory_Dress::where('dress_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        $historypriceshirt = PriceHistory_Shirt::where('shirtitems_id', $shirtitem->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $historypriceskirt = PriceHistory_Skirt::where('skirtitems_id', $skirtitem->id)
            ->orderBy('created_at', 'desc')
            ->get();



        $check_admin = Auth::user()->is_admin;


        //stop เสื้อ/ผ้าถุง
        // reser_dress_stopRent_shirt
        // reser_dress_stopRent_skirt
        $list_stop_sh = [];
        $list_stop_sk = [];
        $list_id_stop_dress = Reservation::where('dress_id', $id)
            ->whereNull('shirtitems_id')
            ->whereNull('skirtitems_id')
            ->where('status_completed', 0)
            ->where('status', 'ถูกจอง')
            ->get();
        if ($list_id_stop_dress->isNotEmpty()) {
            foreach ($list_id_stop_dress as $list_id_dress) {
                $list_stop_sh[] = $list_id_dress->id;
                $list_stop_sk[] = $list_id_dress->id;
            }
        }
        $list_id_stop_shirt = Reservation::where('shirtitems_id', $shirtitem->id)
            ->where('status_completed', 0)
            ->where('status', 'ถูกจอง')
            ->get();
        if ($list_id_stop_shirt->isNotEmpty()) {
            foreach ($list_id_stop_shirt as $list_id_sh) {
                $list_stop_sh[] = $list_id_sh->id;
            }
        }
        $reser_dress_stopRent_shirt = Reservation::whereIn('id', $list_stop_sh)->get();


        $list_id_stop_skirt = Reservation::where('skirtitems_id', $skirtitem->id)
            ->where('status_completed', 0)
            ->where('status', 'ถูกจอง')
            ->get();
        if ($list_id_stop_skirt->isNotEmpty()) {
            foreach ($list_id_stop_skirt as $list_id_sk) {
                $list_stop_sk[] = $list_id_sk->id;
            }
        }


        $reser_dress_stopRent_skirt = Reservation::whereIn('id', $list_stop_sk)->get();















        return view('admin.dressdetailyes', compact('text_check_status_shirt', 'text_check_status_skirt',  'datadress', 'imagedata', 'name_type', 'shirtitem', 'skirtitem', 'measument_yes_separate_shirt', 'measument_yes_separate_now_shirt', 'measument_yes_separate_skirt', 'measument_yes_separate_now_skirt', 'measument_yes_separate_now_shirt_modal', 'measument_yes_separate_now_skirt_modal', 'reservation_shirt', 'reservation_skirt', 'reservation_dress', 'dress_mea_shirt', 'dress_mea_skirt', 'dress_mea_totaldress', 'date_reservations_dress', 'date_reservations_shirt', 'date_reservations_skirt', 'check_admin', 'historydress', 'historypriceshirt', 'historypriceskirt', 'reser_dress_stopRent_shirt', 'reser_dress_stopRent_skirt'));
    }


    //อัปเดตชุดnoyes
    public function updatedressnoyes(Request $request, $id)
    {
        //ตารางdress
        $update_dress = Dress::find($id);
        if ($update_dress->dress_price != $request->input('update_dress_price')) {

            $historydress = new PriceHistory_Dress();
            $historydress->dress_id = $id;
            $historydress->old_price = $update_dress->dress_price;
            $historydress->new_price = $request->input('update_dress_price');
            $historydress->save();
        }
        $update_dress->dress_price = $request->input('update_dress_price');
        $update_dress->dress_deposit = $request->input('update_dress_deposit');
        $update_dress->damage_insurance = $request->input('update_damage_insurance');
        $update_dress->save();
        return redirect()->back()->with('success', 'อัพเดตข้อมูลสำเร็จ !');
    }

    public function updatedressnoyesdes(Request $request, $id)
    {
        //ตารางdress
        $update_dress = Dress::find($id);
        $update_dress->dress_description = $request->input('update_dress_description');
        $update_dress->save();
        return redirect()->back()->with('success', 'อัพเดตข้อมูลสำเร็จ !');
    }









    //อัปเดตชุดyesshirt
    public function updatedressyesshirt(Request $request, $id)
    {

        //ตารางshirt
        $update_shirt = Shirtitem::find($id);
        if ($update_shirt->shirtitem_price != $request->input('update_shirt_price')) {
            $historydress = new PriceHistory_Shirt();
            $historydress->shirtitems_id = $id;
            $historydress->old_price = $update_shirt->shirtitem_price;
            $historydress->new_price = $request->input('update_shirt_price');
            $historydress->save();
        }
        $update_shirt->shirtitem_price = $request->input('update_shirt_price');
        $update_shirt->shirtitem_deposit = $request->input('update_shirt_deposit');
        $update_shirt->shirt_damage_insurance = $request->input('update_shirt_damage_insurance');
        $update_shirt->save();


        return redirect()->back()->with('success', 'อัพเดตข้อมูลสำเร็จ !');
    }

    //อัปเดตชุดyesskirt
    public function updatedressyesskirt(Request $request, $id)
    {
        //ตารางskirt
        $update_skirt = Skirtitem::find($id);
        if ($update_skirt->skirtitem_price != $request->input('update_skirt_price')) {
            $historydress = new PriceHistory_Skirt();
            $historydress->skirtitems_id = $id;
            $historydress->old_price = $update_skirt->skirtitem_price;
            $historydress->new_price = $request->input('update_skirt_price');
            $historydress->save();
        }
        $update_skirt->skirtitem_price = $request->input('update_skirt_price');
        $update_skirt->skirtitem_deposit = $request->input('update_skirt_deposit');
        $update_skirt->skirt_damage_insurance = $request->input('update_skirt_damage_insurance');
        $update_skirt->save();
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




    //บันทึกค่าใช้จ่าย
    public function expense()
    {
        $dataexpense = Expense::orderBy('date', 'desc')->paginate(8);
        $expense = Expense::orderBy('date', 'desc')->get();
        $today = now()->toDateString();
        $month = 0;
        $year = 0;
        return view('admin.expense', compact('dataexpense', 'today', 'month', 'year', 'expense'));
    }
    public function expenseeditupdate(Request $request)
    {
        dd('ไม่ทำ');
    }

    public function expensedelete(Request $request, $id)
    {
        $expense = Expense::find($id);
        $expense->delete();
        return redirect()->back()->with('success', 'ลบสำเร็จ');
    }






    public function expensefilter(Request $request)
    {

        $month = $request->input('month');
        $year = $request->input('year');
        $today = now()->toDateString();
        $dataexpense = Expense::orderBy('date', 'desc');
        $expense = Expense::orderBy('date', 'desc');

        if ($month != 0) {
            $dataexpense->whereMonth('date', $month);
            $expense->whereMonth('date', $month);
        }
        if ($year != 0) {
            $dataexpense->whereYear('date', $year);
            $expense->whereYear('date', $year);
        }
        $dataexpense = $dataexpense->paginate(8);
        $expense = $expense->get();

        return view('admin.expense', compact('dataexpense', 'today', 'month', 'year', 'expense'));
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
        return redirect()->route('admin.expense')->with('success', "เพิ่มค่าใช้จ่ายสำเร็จ !");
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
        $categories = ['กำไล', 'สร้อย', 'แหวน', 'เพชร']; // ข้อมูลจากฐานข้อมูลแทนได้
        return view('testtab',compact('categories'));
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

        foreach ($list_one as $item) {
            $findreservation = Repair::where('reservation_id', $item)
                ->whereIn('repair_status', ['ซ่อมเสร็จแล้ว', 'กำลังซ่อม'])->get();
            if ($findreservation->isNotEmpty()) {
                foreach ($findreservation as $item) {
                    $list_two[] = $item->id;
                }
            }
        }
        $history = Repair::whereIn('id', $list_two)->get();
        return view('admin.hist-dress-repair-no', compact('dress', 'typedressname', 'history'));
    }
    private function hisrepairyes($id)
    {
        $dress = Dress::find($id);
        $typedressname = Typedress::find($dress->type_dress_id);

        $shirt_id = Shirtitem::where('dress_id', $id)->value('id');
        $skirt_id = Skirtitem::where('dress_id', $id)->value('id');
        $list_one_shirt = [];
        $list_two_shirt = [];

        // สาเหตุที่เราต้องเช็คทั้ง dress_idและshirt_id เพราะว่ามันจะมี 2 กรณีททีคือ กรณีที่เขาเช่าแคาเสื้อ มันก็จะมีแค่ shirt_idไง พอตอนซ่อม มันก็จะซ่อมแค่เสื้อ
        // และกรณีที่สองคือ เช่าทั้งชุด dress_id  แล้วซ่อมทั้งชุดอะไรยังงี้ หรือซ่อมแค่เสื้ออะไรยังงี้อะ มันก็ต้องนับเสื้อด้วยถ้าซ่อมทั้งชุดอะ  
        $shirt_id_in_re = reservation::where('shirtitems_id', $shirt_id)->get();
        $dress_id_in_re = Reservation::where('dress_id', $id)->get();
        foreach ($shirt_id_in_re as $item) {
            $list_one_shirt[] = $item->id;
        }
        foreach ($dress_id_in_re as $item) {
            $list_one_shirt[] = $item->id;
        }
        $list_one_shirt = array_unique($list_one_shirt);
        foreach ($list_one_shirt as $reservation_id) {
            $find_repair = Repair::where('reservation_id', $reservation_id)
                ->whereIn('repair_type', ['10', '20'])
                ->whereIn('repair_status', ['ซ่อมเสร็จแล้ว', 'กำลังซ่อม'])
                ->get();

            if ($find_repair->isNotEmpty()) {
                foreach ($find_repair as $item) {
                    $list_two_shirt[] = $item->id;
                }
            }
        }
        $history_shirt = Repair::whereIn('id', $list_two_shirt)->get();




        $list_one_skirt = [];
        $list_two_skirt = [];

        // สาเหตุที่เราต้องเช็คทั้ง dress_idและshirt_id เพราะว่ามันจะมี 2 กรณีททีคือ กรณีที่เขาเช่าแคาเสื้อ มันก็จะมีแค่ shirt_idไง พอตอนซ่อม มันก็จะซ่อมแค่เสื้อ
        // และกรณีที่สองคือ เช่าทั้งชุด dress_id  แล้วซ่อมทั้งชุดอะไรยังงี้ หรือซ่อมแค่เสื้ออะไรยังงี้อะ มันก็ต้องนับเสื้อด้วยถ้าซ่อมทั้งชุดอะ  
        $skirt_id_in_re = reservation::where('skirtitems_id', $skirt_id)->get();
        $dress_id_in_re = Reservation::where('dress_id', $id)->get();
        foreach ($skirt_id_in_re as $item) {
            $list_one_skirt[] = $item->id;
        }
        foreach ($dress_id_in_re as $item) {
            $list_one_skirt[] = $item->id;
        }
        $list_one_skirt = array_unique($list_one_skirt);
        foreach ($list_one_skirt as $reservation_id) {
            $find_repair = Repair::where('reservation_id', $reservation_id)
                ->whereIn('repair_type', ['10', '30'])
                ->whereIn('repair_status', ['ซ่อมเสร็จแล้ว', 'กำลังซ่อม'])
                ->get();

            if ($find_repair->isNotEmpty()) {
                foreach ($find_repair as $item) {
                    $list_two_skirt[] = $item->id;
                }
            }
        }
        $history_skirt = Repair::whereIn('id', $list_two_skirt)->get();
        return view('admin.hist-dress-repair-yes', compact('dress', 'typedressname', 'history_shirt', 'history_skirt'));
    }


    public function historydressrent($id)
    {
        $dress = Dress::find($id);
        if ($dress->separable == 1) {
            return $this->historydressrentNo($id);
        } elseif ($dress->separable == 2) {
            return $this->historydressrentYes($id);
        }
    }


    private function historydressrentNo($id)
    {
        $dress = Dress::find($id);
        $typedress = Typedress::find($dress->type_dress_id);

        $value_month = 0;
        $value_year = 0;

        $history_renrdress = Orderdetail::where('dress_id', $id)
            ->where('status_detail', 'คืนชุดแล้ว')
            // ->whereMonth('updated_at', now()->month)
            // ->whereYear('updated_at', now()->year)
            ->get();
        return view('admin.his-dress-rent-history-no', compact('history_renrdress', 'dress', 'typedress', 'value_month', 'value_year'));
    }



    public function historydressrentnofilter(Request $request,  $id)
    {
        $dress = Dress::find($id);
        $typedress = Typedress::find($dress->type_dress_id);


        $value_month = $request->input('month');
        $value_year = $request->input('year');

        $history_renrdress = Orderdetail::where('dress_id', $id)
            ->where('status_detail', 'คืนชุดแล้ว');

        if ($value_month != 0) {
            $history_renrdress->whereMonth('updated_at', $value_month);
        }

        if ($value_year != 0) {
            $history_renrdress->whereYear('updated_at', $value_year);
        }

        $history_renrdress = $history_renrdress->get();


        return view('admin.his-dress-rent-history-no', compact('history_renrdress', 'dress', 'typedress', 'value_month', 'value_year'));
    }


    private function historydressrentYes($id)
    {
        $dress = Dress::find($id);
        $shirt_id = Shirtitem::where('dress_id', $id)->value('id');
        $skirt_id = Skirtitem::where('dress_id', $id)->value('id');

        $typedress = Typedress::find($dress->type_dress_id);
        $history_renrdress = Orderdetail::where('dress_id', $id)
            ->whereNull('shirtitems_id')
            ->whereNull('skirtitems_id')
            // ->whereMonth('updated_at', now()->month)
            // ->whereYear('updated_at', now()->year)
            ->where('status_detail', 'คืนชุดแล้ว')
            ->get();

        $history_rentshirt = Orderdetail::where('shirtitems_id', $shirt_id)
            ->whereNull('skirtitems_id')
            ->where('dress_id', $id)
            // ->whereMonth('updated_at', now()->month)
            // ->whereYear('updated_at', now()->year)
            ->where('status_detail', 'คืนชุดแล้ว')
            ->get();

        $history_rentskirt = Orderdetail::where('skirtitems_id', $skirt_id)
            ->whereNull('shirtitems_id')
            ->where('dress_id', $id)
            // ->whereMonth('updated_at', now()->month)
            // ->whereYear('updated_at', now()->year)
            ->where('status_detail', 'คืนชุดแล้ว')
            ->get();

        $value_month_dress = 0;
        $value_year_dress = 0;

        $value_month_shirt = 0;
        $value_year_shirt = 0;

        $value_month_skirt = 0;
        $value_year_skirt = 0;
        $activetab = '1';
        return view('admin.his-dress-rent-history-yes', compact('history_renrdress', 'dress', 'typedress', 'history_rentshirt', 'history_rentskirt', 'value_month_dress', 'value_year_dress', 'value_month_shirt', 'value_year_shirt', 'value_month_skirt', 'value_year_skirt', 'activetab'));
    }


    public function historydressrentyesfilter(Request $request, $id)
    {

        $dress = Dress::find($id);
        $shirt_id = Shirtitem::where('dress_id', $id)->value('id');
        $skirt_id = Skirtitem::where('dress_id', $id)->value('id');

        $typedress = Typedress::find($dress->type_dress_id);
        $value_month_dress = $request->input('month_dress');
        $value_year_dress = $request->input('year_dress');

        $history_renrdress = Orderdetail::where('dress_id', $id)
            ->whereNull('shirtitems_id')
            ->whereNull('skirtitems_id')
            // ->whereMonth('updated_at', $value_month_dress)
            // ->whereYear('updated_at', $value_year_dress)
            ->where('status_detail', 'คืนชุดแล้ว');
        if ($value_month_dress != 0) {
            $history_renrdress->whereMonth('updated_at', $value_month_dress);
        }
        if ($value_year_dress != 0) {
            $history_renrdress->whereYear('updated_at', $value_year_dress);
        }
        $history_renrdress = $history_renrdress->get();



        $value_month_shirt = 0;
        $value_year_shirt = 0;

        $value_month_skirt = 0;
        $value_year_skirt = 0;



        $history_rentshirt = Orderdetail::where('shirtitems_id', $shirt_id)
            ->whereNull('skirtitems_id')
            ->where('dress_id', $id)
            ->where('status_detail', 'คืนชุดแล้ว')
            ->get();

        $history_rentskirt = Orderdetail::where('skirtitems_id', $skirt_id)
            ->whereNull('shirtitems_id')
            ->where('dress_id', $id)
            ->where('status_detail', 'คืนชุดแล้ว')
            ->get();

        $activetab = '1';
        return view('admin.his-dress-rent-history-yes', compact('history_renrdress', 'dress', 'typedress', 'history_rentshirt', 'history_rentskirt', 'value_month_dress', 'value_year_dress', 'value_month_shirt', 'value_year_shirt', 'value_month_skirt', 'value_year_skirt', 'activetab'));
    }



    public function historydressrentyesshirtfilter(Request $request, $id)
    {

        $dress = Dress::find($id);
        $shirt_id = Shirtitem::where('dress_id', $id)->value('id');
        $skirt_id = Skirtitem::where('dress_id', $id)->value('id');

        $value_month_dress = 0;
        $value_year_dress = 0;


        $value_month_skirt = 0;
        $value_year_skirt = 0;

        $typedress = Typedress::find($dress->type_dress_id);
        $history_renrdress = Orderdetail::where('dress_id', $id)
            ->whereNull('shirtitems_id')
            ->whereNull('skirtitems_id')
            ->where('status_detail', 'คืนชุดแล้ว')
            ->get();



        $value_month_shirt = $request->input('month_shirt');
        $value_year_shirt = $request->input('year_shirt');


        $history_rentshirt = Orderdetail::where('shirtitems_id', $shirt_id)
            ->whereNull('skirtitems_id')
            ->where('dress_id', $id)
            ->where('status_detail', 'คืนชุดแล้ว');
        if ($value_month_shirt != 0) {
            $history_rentshirt->whereMonth('updated_at', $value_month_shirt);
        }
        if ($value_year_shirt != 0) {
            $history_rentshirt->whereYear('updated_at', $value_year_shirt);
        }

        $history_rentshirt = $history_rentshirt->get();

        $history_rentskirt = Orderdetail::where('skirtitems_id', $skirt_id)
            ->whereNull('shirtitems_id')
            ->where('dress_id', $id)
            ->where('status_detail', 'คืนชุดแล้ว')
            ->get();
        $activetab = '2';
        return view('admin.his-dress-rent-history-yes', compact('history_renrdress', 'dress', 'typedress', 'history_rentshirt', 'history_rentskirt', 'value_month_dress', 'value_year_dress', 'value_month_shirt', 'value_year_shirt', 'value_month_skirt', 'value_year_skirt', 'activetab'));
    }


    public function historydressrentyesskirtfilter(Request $request, $id)
    {

        $dress = Dress::find($id);
        $shirt_id = Shirtitem::where('dress_id', $id)->value('id');
        $skirt_id = Skirtitem::where('dress_id', $id)->value('id');

        $value_month_dress = 0;
        $value_year_dress = 0;

        $value_month_shirt = 0;
        $value_year_shirt = 0;


        $typedress = Typedress::find($dress->type_dress_id);
        $history_renrdress = Orderdetail::where('dress_id', $id)
            ->whereNull('shirtitems_id')
            ->whereNull('skirtitems_id')
            ->where('status_detail', 'คืนชุดแล้ว')
            ->get();

        $history_rentshirt = Orderdetail::where('shirtitems_id', $shirt_id)
            ->whereNull('skirtitems_id')
            ->where('dress_id', $id)
            ->where('status_detail', 'คืนชุดแล้ว')
            ->get();


        $value_month_skirt = $request->input('month_skirt');
        $value_year_skirt = $request->input('year_skirt');

        $history_rentskirt = Orderdetail::where('skirtitems_id', $skirt_id)
            ->whereNull('shirtitems_id')
            ->where('dress_id', $id)
            ->where('status_detail', 'คืนชุดแล้ว');

        if ($value_month_skirt != 0) {
            $history_rentskirt->whereMonth('updated_at', $value_month_skirt);
        }
        if ($value_year_skirt != 0) {
            $history_rentskirt->whereYear('updated_at', $value_year_skirt);
        }
        $history_rentskirt = $history_rentskirt->get();


        $activetab = '3';
        return view('admin.his-dress-rent-history-yes', compact('history_renrdress', 'dress', 'typedress', 'history_rentshirt', 'history_rentskirt', 'value_month_dress', 'value_year_dress', 'value_month_shirt', 'value_year_shirt', 'value_month_skirt', 'value_year_skirt', 'activetab'));
    }


    public function dresswaitprice()
    {
        $dress_wait = Dress::where('dress_price', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(5);
        $count_dress = Dress::where('dress_price', 0)->count();
        return view('admin.dress-wait-price', compact('dress_wait', 'count_dress'));
    }
    public function dresswaitpricesaved(Request $request, $id)
    {
        $price_dress = $request->input('price_dress');
        $deposit_dress = $request->input('deposit_dress');
        $insurance_dress = $request->input('insurance_dress');



        $price_shirt = $request->input('price_shirt');
        $deposit_shirt = $request->input('deposit_shirt');
        $insurance_shirt = $request->input('insurance_shirt');

        $skirt_price = $request->input('skirt_price');
        $skirt_deposit = $request->input('skirt_deposit');
        $skirt_insurance = $request->input('skirt_insurance');

        $dress = Dress::find($id);

        if ($dress->separable == 1) {
            $dress->dress_price = $price_dress;
            $dress->dress_deposit = $deposit_dress;
            $dress->damage_insurance = $insurance_dress;
            $dress->save();
        } elseif ($dress->separable == 2) {
            $dress->dress_price = $price_dress;
            $dress->dress_deposit = $deposit_dress;
            $dress->damage_insurance = $insurance_dress;
            $dress->save();

            $shirt_id = Shirtitem::where('dress_id', $dress->id)->value('id');
            $shirt = Shirtitem::find($shirt_id);
            $shirt->shirtitem_price = $price_shirt;
            $shirt->shirtitem_deposit = $deposit_shirt;
            $shirt->shirt_damage_insurance = $insurance_shirt;
            $shirt->save();


            $skirt_id = Skirtitem::where('dress_id', $dress->id)->value('id');
            $skirt = Skirtitem::find($skirt_id);
            $skirt->skirtitem_price = $skirt_price;
            $skirt->skirtitem_deposit = $skirt_deposit;
            $skirt->skirt_damage_insurance = $skirt_insurance;
            $skirt->save();
        }
        return redirect()->back()->with('success', 'สำเร็จ');
    }
    public function stopRentalnodress($id)
    {
        $dress = Dress::find($id);
        if ($dress->dress_status == 'พร้อมให้เช่า') {
            $dress->dress_status = 'ยุติการให้เช่า';
            $dress->save();
        } elseif ($dress->dress_status == 'กำลังถูกเช่า') {
            return redirect()->back()->with('fail', 'ขณะนี้ชุดกำลังถูกเช่าโดยลูกค้าอยู่ ไม่สามารถยุติการให้เช่าสำหรับชุดนี้ได้');
        } else {
            // dd('อื่นๆ');
            // รอทำความสะอาด กำลังส่งซัก 
            //รอดำเนินการซ่อม กำลังซ่อม

            $dress->dress_status = 'ยุติการให้เช่า';
            $dress->save();

            $reservation_filterdress = Reservationfilterdress::where('dress_id', $dress->id)
                ->where('status_completed', 0)
                ->whereIn('status', ['รอทำความสะอาด', 'กำลังส่งซัก', 'รอดำเนินการซ่อม', 'กำลังซ่อม'])
                ->first();
            //กำลังถูกเช่า กำลังส่งซัก
            //รอดำเนินการซ่อม กำลังซ่อม

            $reservation_update = Reservationfilterdress::find($reservation_filterdress->id);
            $reservation_update->status = 'ยุติการให้เช่า';
            $reservation_update->status_completed = 1;
            $reservation_update->save();
        }
        return redirect()->back()->with('success', 'ชุดนี้ได้ยุติการให้เช่าแล้ว');
    }

    public function stopRentalyesdressshirt($id)
    {
        $shirtitem = Shirtitem::find($id);
        if ($shirtitem->shirtitem_status == 'พร้อมให้เช่า') {
            $shirtitem->shirtitem_status = 'ยุติการให้เช่า';
            $shirtitem->save();
        } elseif ($shirtitem->shirtitem_status == 'กำลังถูกเช่า') {
            return redirect()->back()->with('fail', 'ขณะนี้เสื้อกำลังถูกเช่าโดยลูกค้าอยู่ ไม่สามารถยุติการให้เช่าสำหรับเสื้อนี้ได้');
        } else {
            // รอทำความสะอาด กำลังส่งซัก 
            //รอดำเนินการซ่อม กำลังซ่อม

            $shirtitem->shirtitem_status = 'ยุติการให้เช่า';
            $shirtitem->save();

            $reservation_filter_shirt = Reservationfilterdress::where('shirtitems_id', $shirtitem->id)
                ->where('status_completed', 0)
                ->whereIn('status', ['รอทำความสะอาด', 'กำลังส่งซัก', 'รอดำเนินการซ่อม', 'กำลังซ่อม'])
                ->first();
            $reservation_update = Reservationfilterdress::find($reservation_filter_shirt->id);
            $reservation_update->status = 'ยุติการให้เช่า';
            $reservation_update->status_completed = 1;
            $reservation_update->save();
        }
        return redirect()->back()->with('success', 'ยุติการให้เช่าเสื้อสำเร็จแล้ว');
    }


    public function stopRentalyesdressskirt($id)
    {
        $skirtitem = Skirtitem::find($id);
        if ($skirtitem->skirtitem_status == 'พร้อมให้เช่า') {
            $skirtitem->skirtitem_status = 'ยุติการให้เช่า';
            $skirtitem->save();
        }
        elseif ($skirtitem->skirtitem_status == 'กำลังถูกเช่า') {
            return redirect()->back()->with('fail', 'ขณะนี้ผ้าถุงกำลังถูกเช่าโดยลูกค้าอยู่ ไม่สามารถยุติการให้เช่าสำหรับผ้าถุงนี้ได้');
        }
        else {
            // รอทำความสะอาด กำลังส่งซัก 
            //รอดำเนินการซ่อม กำลังซ่อม

            $skirtitem->skirtitem_status = 'ยุติการให้เช่า';
            $skirtitem->save();

            $reservation_filter_skirt = Reservationfilterdress::where('skirtitems_id', $skirtitem->id)
                ->where('status_completed', 0)
                ->whereIn('status', ['รอทำความสะอาด', 'กำลังส่งซัก', 'รอดำเนินการซ่อม', 'กำลังซ่อม'])
                ->first();
            $reservation_update = Reservationfilterdress::find($reservation_filter_skirt->id);
            $reservation_update->status = 'ยุติการให้เช่า';
            $reservation_update->status_completed = 1;
            $reservation_update->save();



            
        }
        return redirect()->back()->with('success', 'ยุติการให้เช่าผ้าถุงสำเร็จแล้ว');
    }

    public function reopenRentalnodress($id)
    {
        $dress = Dress::find($id);
        $dress->dress_status = 'พร้อมให้เช่า';
        $dress->save();
        return redirect()->back()->with('success', 'ชุดนี้ได้เปิดให้เช่าอีกครั้งแล้ว');
    }
    public function reopenRentalyesdressshirt($id)
    {
        $shirtitem = Shirtitem::find($id);
        $shirtitem->shirtitem_status = 'พร้อมให้เช่า';
        $shirtitem->save();
        return redirect()->back()->with('success', 'เสื้อนี้ได้เปิดให้เช่าอีกครั้งแล้ว');
    }
    public function reopenRentalyesdressskirt($id)
    {
        $skirtitem = Skirtitem::find($id);
        $skirtitem->skirtitem_status = 'พร้อมให้เช่า';
        $skirtitem->save();
        return redirect()->back()->with('success', 'ผ้าถุงนี้ได้เปิดให้เช่าอีกครั้งแล้ว');
    }
}
