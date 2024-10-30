<?php

namespace App\Http\Controllers;

use App\Models\Date;
use App\Models\Jewelry;
use App\Models\Jewelryset;
use App\Models\Jewelrysetitem;
use App\Models\Order;
use App\Models\Orderdetail;
use App\Models\Reservation;
use App\Models\Typejewelry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Orderjewelry extends Controller
{
    //
    public function addrentjewelrytocard()
    {
        $typejew = Typejewelry::all();
        $jewelry_type = '';
        $start_date = '';
        $end_date = '';
        $jewelry_pass = null;
        return view('employeerentjewelry.addjewelrytocard', compact('typejew', 'jewelry_type', 'start_date', 'end_date', 'jewelry_pass'));
    }

    public function addrentjewelrytocardfilter(Request $request)
    {
        $jewelry_type = $request->input('jewelry_type');
        if ($jewelry_type == 'set') {
            return $this->filtersetjew($request);
        } else {
            return $this->filternotsetjew($request);
        }
    }

    // เลือกเซต
    private function filtersetjew(Request $request)
    {
        $typejew = Typejewelry::all();
        $jewelry_type = $request->input('jewelry_type');

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $start_date_fil = Carbon::parse($start_date);
        $end_date_fil = Carbon::parse($end_date);

        $fil_start_7 = $start_date_fil->copy()->subDays(7);
        $fil_start_1 = $start_date_fil->copy()->subDays(1);
        $fil_be_start = $start_date_fil->copy();
        $fil_be_end = $end_date_fil->copy();
        $fil_end_1 = $end_date_fil->copy()->addDays(1);
        $fil_end_7 = $end_date_fil->copy()->addDays(7);


        $jew_set = Jewelryset::all(); //1,2,3,4,7,8

        $list_pass_set_id = [];

        // เช็คเฉพาะที่เช่าแค่เป็นเซต
        foreach ($jew_set as $set_id) {
            $reservation_set = Reservation::where('jewelry_set_id', $set_id->id)
                ->where('status_completed', 0)
                ->get();
            $validate_pass = true;
            foreach ($reservation_set as $item) {
                $start_re = Carbon::parse($item->start_date);
                $end_re = Carbon::parse($item->end_date);
                if ($start_re->between($fil_start_7, $fil_start_1) || $end_re->between($fil_start_7, $fil_start_1)) {
                    $validate_pass = false;
                    break;
                }

                if ($start_re->between($fil_be_start, $fil_be_end) || $end_re->between($fil_be_start, $fil_be_end)) {
                    $validate_pass = false;
                    break;
                }

                if ($start_re->between($fil_end_1, $fil_end_7) || $end_re->between($fil_end_1, $fil_end_7)) {
                    $validate_pass = false;
                    break;
                }
            }
            if ($validate_pass) {
                $list_pass_set_id[] = $set_id->id;
            }
        }

        // dd($list_pass_set_id) ; 


        // เช็คดูว่าในในตารางreservation ที่มันเช่าแค่ set อะ แล้วต้องไปดูรายละเอียดลึกๆอีกทีละ jewelry_id ว่า มีการเช่าเป็ฯชิ้น เพื่อไม่ให้มันซ้ำ
        //พอเราเจอใช่ไหม แปล่วา เราจะต้องลบ set_id นั้นออกไปจาก $list_pass_set_id ที่มันผ่านเงื่อนไขแรก 

        $all_jew_set = Jewelryset::all(); //1,2,3,4,7,8
        $test_item = [];
        foreach ($all_jew_set as $items) {
            $set_in_item = Jewelrysetitem::where('jewelry_set_id', $items->id)->get();
            foreach ($set_in_item as $item) {
                $check_double_in_reservation = Reservation::where('status_completed', 0)
                    ->where('jewelry_id', $item->jewelry_id)
                    ->get();
                $validate_pass_two = 'ไม่ซ้ำ';
                foreach ($check_double_in_reservation as $it) {
                    $check_start_re = Carbon::parse($it->start_date);
                    $check_end_re = Carbon::parse($it->end_date);
                    if ($check_start_re->between($fil_start_7, $fil_start_1) || $check_end_re->between($fil_start_7, $fil_start_1)) {
                        $validate_pass_two = 'ซ้ำ';
                    }

                    if ($check_start_re->between($fil_be_start, $fil_be_end) || $check_end_re->between($fil_be_start, $fil_be_end)) {
                        $validate_pass_two = 'ซ้ำ';
                    }

                    if ($check_start_re->between($fil_end_1, $fil_end_7) || $check_end_re->between($fil_end_1, $fil_end_7)) {
                        $validate_pass_two = 'ซ้ำ';
                    }

                    if ($validate_pass_two == "ซ้ำ") {
                        $list_pass_set_id = array_diff($list_pass_set_id, [$item->jewelry_set_id]);
                    }
                }
            }
        }

        $jewelry_pass = Jewelryset::whereIn('id', $list_pass_set_id)->get();
        return view('employeerentjewelry.addjewelrytocard', compact('typejew', 'jewelry_type', 'start_date', 'end_date', 'jewelry_pass'));
    }

    private function filternotsetjew(Request $request)
    {
        $typejew = Typejewelry::all();


        $jewelry_type = $request->input('jewelry_type');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $start_date_fil = Carbon::parse($start_date);
        $end_date_fil = Carbon::parse($end_date);

        $jewel = Jewelry::where('type_jewelry_id', $jewelry_type)->get();
        $list_pass_id = [];


        $fil_start_7 = $start_date_fil->copy()->subDays(7);
        $fil_start_1 = $start_date_fil->copy()->subDays(1);
        $fil_be_start = $start_date_fil->copy();
        $fil_be_end = $end_date_fil->copy();
        $fil_end_1 = $end_date_fil->copy()->addDays(1);
        $fil_end_7 = $end_date_fil->copy()->addDays(7);

        foreach ($jewel as $jew) {
            $reservation = Reservation::where('jewelry_id', $jew->id)
                ->where('status_completed', 0)
                ->get();
            $validate_pass = true;
            foreach ($reservation as $re) {
                $start_re = Carbon::parse($re->start_date);
                $end_re = Carbon::parse($re->end_date);
                if ($start_re->between($fil_start_7, $fil_start_1) || $end_re->between($fil_start_7, $fil_start_1)) {
                    $validate_pass = false;
                    break;
                }

                if ($start_re->between($fil_be_start, $fil_be_end) || $end_re->between($fil_be_start, $fil_be_end)) {
                    $validate_pass = false;
                    break;
                }

                if ($start_re->between($fil_end_1, $fil_end_7) || $end_re->between($fil_end_1, $fil_end_7)) {
                    $validate_pass = false;
                    break;
                }
            }
            if ($validate_pass) {
                $list_pass_id[] = $jew->id;
            }
        }


        // เช็คแค่setสิ
        $reservation_set_id = Reservation::where('status_completed', 0)
            ->whereNotNull('jewelry_set_id')
            ->get();
        $validate_pass_set = 'ยังไม่ซ้ำ';
        foreach ($reservation_set_id as $re_set_id) {
            $re_set_start = Carbon::parse($re_set_id->start_date);
            $re_set_end = Carbon::parse($re_set_id->end_date);
            if ($re_set_start->between($fil_start_7, $fil_start_1) || $re_set_end->between($fil_start_7, $fil_start_1)) {
                $validate_pass_set = "ซ้ำ";
            }
            if ($re_set_start->between($fil_be_start, $fil_be_end) || $re_set_end->between($fil_be_start, $fil_be_end)) {
                $validate_pass_set = "ซ้ำ";
            }
            if ($re_set_start->between($fil_end_1, $fil_end_7) || $re_set_end->between($fil_end_1, $fil_end_7)) {
                $validate_pass_set = "ซ้ำ";
            }
            // ถ้ามันเป็นจริง แสดงว่ามันซ้ำ  ต้องลบ id ทิ้ง โดยการใช้ array_diff
            if ($validate_pass_set == "ซ้ำ") {
                $delete_id = Jewelrysetitem::where('jewelry_set_id', $re_set_id->jewelry_set_id)->get();
                foreach ($delete_id as $item) {
                    $list_pass_id = array_diff($list_pass_id, [$item->jewelry_id]);
                }
            }
        }




        $jewelry_pass = Jewelry::whereIn('id', $list_pass_id)->get();

        return view('employeerentjewelry.addjewelrytocard', compact('typejew', 'jewelry_type', 'start_date', 'end_date', 'jewelry_pass'));
    }
    // เพิ่มเครื่องประดับลงในตะกร้าชิ้น
    public function addrentjewelrytocardaddtocard(Request $request)
    {
        $jewelry_id = $request->input('jew_id');
        $pickupdate = $request->input('pickupdate');
        $returndate = $request->input('returndate');
        $jew_price = $request->input('jew_price');
        $jew_deposit = $request->input('jew_deposit');
        $jew_damage_insurance = $request->input('jew_damage_insurance');
        $employee_id = Auth::user()->id;


        //ตารางreservation 
        $reservation = new Reservation();
        $reservation->jewelry_id = $jewelry_id;
        $reservation->start_date = $pickupdate;
        $reservation->end_date = $returndate;
        $reservation->status = 'อยู่ในตะกร้า';
        $reservation->status_completed = 0; //0 คือ ยังไม่เสด 1 คือเสร็จแล้ว
        $reservation->save();




        //ตารางorder
        $order = Order::where('user_id', $employee_id)->where('order_status', 0)->first();
        // มีorderอยู่แล้ว
        if ($order) {
            //ตารางorder
            $update_order = Order::find($order->id);
            $update_order->total_quantity = $update_order->total_quantity + 1;
            $update_order->save();

            //ตารางorderdetail
            $create_order_detail = new Orderdetail();
            $create_order_detail->order_id = $update_order->id;
            $create_order_detail->reservation_id = $reservation->id;
            $create_order_detail->type_order = 3;
            $create_order_detail->amount = 1;
            $create_order_detail->price = $jew_price;
            $create_order_detail->deposit = $jew_deposit;
            $create_order_detail->damage_insurance = $jew_damage_insurance;
            $create_order_detail->save();

            // ตาราdate
            $create_date = new Date();
            $create_date->order_detail_id = $create_order_detail->id;
            $create_date->pickup_date = $pickupdate;
            $create_date->return_date = $returndate;
            $create_date->save();
        }
        //ไม่มีorder
        else {

            //ตารางorder
            $create_order = new Order();
            $create_order->user_id = $employee_id;
            $create_order->total_quantity = 1;
            $create_order->order_status = 0;
            $create_order->save();

            //ตารางorderdetail
            $create_order_detail = new Orderdetail();
            $create_order_detail->order_id = $create_order->id;
            $create_order_detail->reservation_id = $reservation->id;
            $create_order_detail->type_order = 3;
            $create_order_detail->amount = 1;
            $create_order_detail->price = $jew_price;
            $create_order_detail->deposit = $jew_deposit;
            $create_order_detail->damage_insurance = $jew_damage_insurance;
            $create_order_detail->save();

            // ตาราdate
            $create_date = new Date();
            $create_date->order_detail_id = $create_order_detail->id;
            $create_date->pickup_date = $pickupdate;
            $create_date->return_date = $returndate;
            $create_date->save();
        }
        return redirect()->back()->with('success', 'เพิ่มลงตะกร้าสำเร็จ');
    }
    public function addrentjewelrytocardaddtocardset(Request $request){


        $jewset_id = $request->input('jewset_id');
        $pickupdate = $request->input('pickupdate');
        $returndate = $request->input('returndate');
        $jewset_price = $request->input('jewset_price');
        $jewset_deposit = $jewset_price * 0.3 ; 
        $jewset_damage_insurance = $request->input('jewset_price');
        $employee_id = Auth::user()->id;


        //ตารางreservation 
        $reservation = new Reservation();
        $reservation->jewelry_set_id = $jewset_id;
        $reservation->start_date = $pickupdate;
        $reservation->end_date = $returndate;
        $reservation->status = 'อยู่ในตะกร้า';
        $reservation->status_completed = 0; //0 คือ ยังไม่เสด 1 คือเสร็จแล้ว
        $reservation->save();




        //ตารางorder
        $order = Order::where('user_id', $employee_id)->where('order_status', 0)->first();
        // มีorderอยู่แล้ว
        if ($order) {
            //ตารางorder
            $update_order = Order::find($order->id);
            $update_order->total_quantity = $update_order->total_quantity + 1;
            $update_order->save();

            //ตารางorderdetail
            $create_order_detail = new Orderdetail();
            $create_order_detail->order_id = $update_order->id;
            $create_order_detail->reservation_id = $reservation->id;
            $create_order_detail->type_order = 3;
            $create_order_detail->amount = 1;
            $create_order_detail->price = $jewset_price;
            $create_order_detail->deposit = $jewset_deposit;
            $create_order_detail->damage_insurance = $jewset_damage_insurance;
            $create_order_detail->save();

            // ตาราdate
            $create_date = new Date();
            $create_date->order_detail_id = $create_order_detail->id;
            $create_date->pickup_date = $pickupdate;
            $create_date->return_date = $returndate;
            $create_date->save();
        }
        //ไม่มีorder
        else {

            //ตารางorder
            $create_order = new Order();
            $create_order->user_id = $employee_id;
            $create_order->total_quantity = 1;
            $create_order->order_status = 0;
            $create_order->save();

            //ตารางorderdetail
            $create_order_detail = new Orderdetail();
            $create_order_detail->order_id = $create_order->id;
            $create_order_detail->reservation_id = $reservation->id;
            $create_order_detail->type_order = 3;
            $create_order_detail->amount = 1;
            $create_order_detail->price = $jewset_price;
            $create_order_detail->deposit = $jewset_deposit;
            $create_order_detail->damage_insurance = $jewset_damage_insurance;
            $create_order_detail->save();

            // ตาราdate
            $create_date = new Date();
            $create_date->order_detail_id = $create_order_detail->id;
            $create_date->pickup_date = $pickupdate;
            $create_date->return_date = $returndate;
            $create_date->save();
        }
        return redirect()->back()->with('success', 'เพิ่มลงตะกร้าสำเร็จ');
    }












}
