<?php

namespace App\Http\Controllers;

use App\Models\AdjustmentRound;
use App\Models\Customer;
use App\Models\Date;
use App\Models\Decoration;
use App\Models\Dress;
use App\Models\Dressimage;
use App\Models\Dressmea;
use App\Models\Dressmeaadjustment;
use App\Models\Dressmeasurement;
use App\Models\Dressmeasurementcutedit;
use App\Models\Fitting;
use App\Models\Imagerent;
use App\Models\Measurementorderdetail;
use App\Models\Order;
use App\Models\Orderdetail;
use App\Models\Orderdetailstatus;
use App\Models\Paymentstatus;
use App\Models\Reservation;
use App\Models\Shirtitem;
use App\Models\Skirtitem;
use App\Models\Typedress;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManageRentcutController extends Controller
{
    //
    public function addrentcut()
    {
        $type_dress = Typedress::all();
        return view('employeerentcut.create-tailored-dress-rental', compact('type_dress'));
    }

    public function saveaddrentcut(Request $request)
    {

        $id_employee = Auth::user()->id;
        // 0คือยังไม่เสร็จ 1 คือ เสร็จแล้ว
        $check_order = Order::where('user_id', $id_employee)
            ->where('order_status', 0)->first();
        // ถ้ามันไม่มีให้สร้างใหม่
        if (!$check_order) {
            // ตารางorder
            $order = new Order();
            $order->user_id = Auth::user()->id;
            $order->total_quantity = 1;
            $order->total_price = $request->input('price') * $request->input('amount');
            $order->total_deposit = $request->input('deposit') * $request->input('amount');

            $order->order_status = 0;
            $order->save();
            $ID_ORDER = $order->id;
        }
        // ถ้ามีให้ดึงidมา
        else {
            $ID_ORDER = $check_order->id;
            // อัปเดตราคารวม + จำนวนรายการ
            $update_total_price = Order::find($check_order->id);
            $update_total_price->total_price = $check_order->total_price + ($request->input('price') * $request->input('amount'));
            $update_total_price->total_deposit = $check_order->total_deposit + ($request->input('deposit') * $request->input('amount'));
            $update_total_price->total_quantity = $check_order->total_quantity + 1;
            $update_total_price->save();
        }


        // ตารางorderdetail
        if ($request->input('type_dress') == 'other_type') {
            $checkdouble = Typedress::where('type_dress_name', $request->input('other_input'))->first();
            if ($checkdouble) {
                $TYPE_DRESS = $request->input('other_input');
            } else {
                //สร้างตัวอักษรมา1ตัว
                do {
                    $random = chr(65 + rand(0, 25));

                    $check = Typedress::where('specific_letter', $random)->first();
                } while ($check);
                $character = $random; //ได้ตัวอักษรมาแล้ว นำไปคำนวณcen
                $create_id_of_typedress = new Typedress();
                $create_id_of_typedress->type_dress_name = $request->input('other_input');
                $create_id_of_typedress->specific_letter = $character;
                $create_id_of_typedress->save();
                $TYPE_DRESS = $request->input('other_input');
            }
        } else {
            $TYPE_DRESS = $request->input('type_dress');
        }

        $orderdetail = new Orderdetail();
        $orderdetail->order_id = $ID_ORDER;
        $orderdetail->type_dress = $TYPE_DRESS;
        $orderdetail->type_order = 4; //1ตัดชุด 2เช่าชุด 3เช่าเครื่องประดับ 4เช่าตัด
        $orderdetail->amount = $request->input('amount');

        if ($request->input('deposit') > $request->input('price')) {
            DB::rollback();
            return redirect()->back()->with('fail', "ราคาตัดต้องมากกว่าราคามัดจำ");
        } else {
            $orderdetail->price = $request->input('price');
            $orderdetail->deposit = $request->input('deposit');
        }

        $orderdetail->note = $request->input('note');
        $orderdetail->damage_insurance = $request->input('price');
        $orderdetail->save();

        $date = new Date();
        $date->order_detail_id = $orderdetail->id;
        $date->pickup_date = $request->input('pickup_date');
        $date->return_date = $request->input('return_date');
        $date->save();

        // บันทึกข้อมูลในตาราง Dressmeaadjustment
        if ($request->input('name_')) {
            $mea_name = $request->input('name_');
            $mea_number = $request->input('number_');
            if ($mea_name) {
                foreach ($mea_name as $index => $mea) {
                    $data = new Dressmeaadjustment();
                    $data->order_detail_id = $orderdetail->id;
                    $data->name = $mea;
                    $data->new_size = $mea_number[$index];
                    $data->save();
                }
            }
        }
        // บันทึกช้อมูลลงในตาราง rentimage
        if ($request->hasFile('file_image_')) {
            $imf_loop = $request->file('file_image_');
            $note_image = $request->input('note_image_');
            foreach ($imf_loop as $index => $img) {
                $image_save = new Imagerent();
                $image_save->order_detail_id = $orderdetail->id;
                $image_save->image = $img->store('rent_images', 'public');
                $image_save->description = $note_image[$index] ?? null;
                $image_save->save();
            }
        }


        // บันทึกข้อมูลในตารางfitting
        if ($request->input('fitting')) {
            $add_fitting = new Fitting();
            $add_fitting->order_detail_id = $orderdetail->id;
            $add_fitting->fitting_date = $request->input('fitting');
            $add_fitting->fitting_status = 'ยังไม่มาลองชุด';
            $add_fitting->save();
        }


        return redirect()->back()->with('success', 'เพิ่มลงตะกร้าแล้ว !');
    }


    public function deleteitemfittingrentcut($id)
    {
        $fitting = Fitting::find($id);
        $fitting->delete();
        return redirect()->back()->with('success', 'ลบวันนัดลองชุดสำเร็จ');
    }


    public function rentcutmakingdress($id, $order_detail_id)
    {
        $orderdetail = Orderdetail::find($order_detail_id);
        $dress_adjusts = Dressmeaadjustment::where('order_detail_id', $order_detail_id)->get();
        $date = Date::where('order_detail_id', $order_detail_id)
            ->orderBy('created_at', 'desc')
            ->first();
        $fitting = Fitting::find($id);
        $customer_id = Order::where('id', $orderdetail->order_id)->value('customer_id');
        $customer = Customer::find($customer_id);
        return view('employeerentcut.making-rent-dress', compact('orderdetail', 'dress_adjusts', 'date', 'fitting', 'customer'));
    }
    public function rentcutmakingdresssave(Request $request, $id)
    {
        $fitting_id = $request->input('fitting_id');
        $fitting = Fitting::find($fitting_id);
        $fitting->fitting_note = $request->input('note_fitting');
        $fitting->fitting_status = 'มาลองชุดแล้ว';
        $fitting->save();

        // สิ่งที่เพิ่มเติมเข้ามานะ 
        if ($request->input('dec_des_')) {
            $dec_des = $request->input('dec_des_');
            $dec_price = $request->input('dec_price_');
            foreach ($dec_des as $index => $dec) {
                $extra_item = new Decoration();
                $extra_item->order_detail_id = $id;
                $extra_item->fitting_id = $fitting_id;
                $extra_item->decoration_description = $dec;
                $extra_item->decoration_price = $dec_price[$index];
                $extra_item->save();
            }
        }
        // ถ้ามันมีการเปลี่ยนแปลงค่าข้อมูลการวัด
        if ($request->input('adjust_name_')) {
            $adjust_name = $request->input('adjust_name_');
            $old = $request->input('old_');
            $new = $request->input('new_');
            $adjust_id = $request->input('adjust_id_');
            foreach ($adjust_name as $index => $name) {
                if ($old[$index] != $new[$index]) {
                    $create_adjust = new Dressmeasurementcutedit();
                    $create_adjust->adjustment_id = $adjust_id[$index];
                    $create_adjust->fitting_id  = $fitting_id;
                    $create_adjust->order_detail_id = $id;
                    $create_adjust->name = $name;
                    $create_adjust->old_size = $old[$index];
                    $create_adjust->edit_new_size = $new[$index];
                    $create_adjust->save();
                    $update_mea = Dressmeaadjustment::find($adjust_id[$index]);
                    $update_mea->new_size = $new[$index];
                    $update_mea->save();
                }
            }
        }
        return redirect()->route('detaildoingrentcut', ['id' => $id])->with('success', 'บันทึกการนัดลองชุดสำเร็จ');
    }


    //ยังไม่ตัดสินใจลบ
    public function actionupdatestatusrentcutpickup(Request $request, $id)
    {
        $orderdetail = Orderdetail::find($id);
        $show_date = Date::where('order_detail_id', $orderdetail->id)
            ->orderBy('created_at', 'desc')
            ->first();
        $status = $orderdetail->status_detail;



        $message_return =  Carbon::parse($show_date->return_date)->locale('th')->isoFormat('D MMM') . ' ' .
            (Carbon::parse($show_date->return_date)->year + 543);
        $message_session = 'ลูกค้ากำลังเช่า กำหนดคืนคือ ' . $message_return;

        //ตารางorderdetail
        $orderdetail->status_detail = "กำลังเช่า";
        $orderdetail->save();
        //ตารางorderdetailstatus
        $create_status = new Orderdetailstatus();
        $create_status->order_detail_id = $id;
        $create_status->status = "กำลังเช่า";
        $create_status->save();


        // อัปเดตตารางdate
        $date_id = Date::where('order_detail_id', $id)
            ->orderBy('created_at', 'desc')
            ->value('id');
        $update_real = Date::find($date_id);
        $update_real->actua_pickup_date = now(); //วันที่รับจริงๆ
        $update_real->save();
        if ($orderdetail->status_payment == 1) {
            //ตารางpaymentstatus
            $create_paymentstatus = new Paymentstatus();
            $create_paymentstatus->order_detail_id = $id;
            $create_paymentstatus->payment_status = 2;
            $create_paymentstatus->save();
            //ตารางorderdetail
            $orderdetail->status_payment = 2; //1จ่ายมัดจำ 2จ่ายเต็มจำนวน
            $orderdetail->save();
        }
        return redirect()->back()->with('success', $message_session);
    }















    public function detaildoingrentcut($id)
    {
        $orderdetail = Orderdetail::find($id);
        $customer_id = Order::where('id', $orderdetail->order_id)->value('customer_id');
        $customer = Customer::find($customer_id);
        $employee = User::find($orderdetail->employee_id);
        $fitting = Fitting::where('order_detail_id', $id)
            ->orderByRaw(" STR_TO_DATE(fitting_date,'%Y-%m-%d') asc ")
            ->get();
        $Date = Date::where('order_detail_id', $id)
            ->orderBy('created_at', 'desc')
            ->first();
        $decoration = Decoration::where('order_detail_id', $id)->get();
        $imagerent = Imagerent::where('order_detail_id', $id)->get();
        $mea_dress = Dressmeasurement::where('dress_id', $orderdetail->dress_id)->get();
        $mea_orderdetail = Measurementorderdetail::where('order_detail_id', $id)->get();
        $mea_orderdetailforedit = Measurementorderdetail::where('order_detail_id', $id)->get();
        $orderdetailstatus = Orderdetailstatus::where('order_detail_id', $id)->get();

        $valuestatus = $orderdetail->status_detail;
        $valuestatus = Orderdetailstatus::where('order_detail_id', $id)
            ->latest('created_at')
            ->value('status');
        $dress_edit_cut = Dressmeasurementcutedit::where('order_detail_id', $id)->get();
        $dress_adjusts = Dressmeaadjustment::where('order_detail_id', $id)->get();
        $round = AdjustmentRound::where('order_detail_id', $id)->get();
        $route_modal = AdjustmentRound::where('order_detail_id', $id)
            ->orderBy('created_at', 'desc')
            ->first();
        $is_admin = Auth::user()->is_admin;  //ตรวจสอบว่าเป็นแอดมินไหม
        $who_login = Auth::user()->id; //คนที่กำลังlogin
        $person_order = Order::where('id', $orderdetail->order_id)->value('user_id');  //คนที่รับ order
        return view('employeerentcut.managedetailrentcut-for-doing-cut', compact('is_admin', 'who_login', 'person_order', 'orderdetail', 'employee', 'fitting', 'Date', 'decoration', 'imagerent', 'mea_dress', 'mea_orderdetail', 'orderdetailstatus', 'valuestatus', 'customer', 'mea_orderdetailforedit', 'dress_adjusts', 'dress_edit_cut', 'round', 'route_modal'));
    }
    public function storeTailoredDress($id)
    {
        $orderdetail = Orderdetail::find($id);

        $typedress = Typedress::where('type_dress_name', $orderdetail->type_dress)->first();
        $dress = Dress::where('type_dress_id', $typedress->id)->max('dress_code');
        $next_code = $dress + 1;
        $measurements = Dressmeaadjustment::where('order_detail_id', $id)->get();
        return view('employeerentcut.add_tailored_dress', compact('orderdetail', 'measurements', 'typedress', 'next_code'));
    }



    public function storeTailoredDresssaved(Request $request, $id)
    {
        $orderdetail = Orderdetail::find($id);
        $typedress = Typedress::where('type_dress_name', $orderdetail->type_dress)->first();
        $dress = Dress::where('type_dress_id', $typedress->id)->max('dress_code');
        $next_code = $dress + 1;

        $dress = new Dress();
        $dress->type_dress_id = $typedress->id;
        $dress->dress_code = $next_code; //หมายเลขชุด
        $dress->dress_code_new = $typedress->specific_letter;
        $dress->dress_price = $orderdetail->price;
        $dress->dress_deposit = ($orderdetail->price) * 0.3;
        $dress->damage_insurance = $orderdetail->price;
        $dress->dress_count = 1;
        $dress->dress_status = "พร้อมให้เช่า";
        $dress->dress_description = $request->input('dress_details');
        $dress->dress_rental = 0;
        $dress->source_type = 2; //1 ชุดเปล่าๆ 2ชุดที่ได้มาจากการเช่าตัด
        $dress->separable = $request->input('rental_type'); //1แยกไม่ได้ 2 แยกได้
        $dress->save();

        if ($request->hasFile('dress_image')) {
            $add_image = new Dressimage();
            $add_image->dress_id = $dress->id;
            $add_image->dress_image = $request->file('dress_image')->store('dress_images', 'public');
            $add_image->save();
        }
        if ($request->input('rental_type') == 1) {
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
        } elseif ($request->input('rental_type') == 2) {
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



        $Date = Date::where('order_detail_id', $id)
            ->orderBy('created_at', 'desc')
            ->first();


        //ตารางreservation 
        $reservation = new Reservation();
        $reservation->dress_id = $dress->id;
        $reservation->start_date = $Date->pickup_date;
        $reservation->end_date = $Date->return_date;
        $reservation->status = 'ถูกจอง';
        $reservation->status_completed = 0; //0 คือ ยังไม่เสด 1 คือเสร็จแล้ว
        $reservation->save();


        $update_orderdetail = Orderdetail::find($id);
        $update_orderdetail->status_detail = "ตัดชุดเสร็จสิ้น";
        $update_orderdetail->reservation_id = $reservation->id;
        $update_orderdetail->dress_id = $dress->id;
        $update_orderdetail->save();
        $create_status = new Orderdetailstatus();
        $create_status->order_detail_id = $id;
        $create_status->status = "ตัดชุดเสร็จสิ้น";
        $create_status->save();

        $update_orderdetail = Orderdetail::find($id);
        $update_orderdetail->status_detail = "ถูกจอง";
        $update_orderdetail->save();
        $create_status = new Orderdetailstatus();
        $create_status->order_detail_id = $id;
        $create_status->status = "ถูกจอง";

        $create_status->save();
        return redirect()->route('detaildoingrentcut', ['id' => $orderdetail->id])->with('success', 'สำเร็จลุล่วงมะม่วงทอด');
    }
}
