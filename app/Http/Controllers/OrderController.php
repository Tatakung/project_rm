<?php

namespace App\Http\Controllers;

use App\Models\Cost;
use App\Models\Customer;
use App\Models\Date;
use App\Models\Decoration;
use App\Models\Dress;
use App\Models\Dressmeasurement;
use App\Models\Financial;
use App\Models\Fitting;
use App\Models\Imagerent;
use App\Models\Jewelry;
use App\Models\Measurementorderdetail;
use App\Models\Order;
use App\Models\Orderdetail;
use App\Models\Orderdetailstatus;
use App\Models\Paymentstatus;
use App\Models\User;
use App\Models\Repair;
use App\Models\Dressmeasurementnow;
use App\Models\Dressmeasurementcutedit;
use App\Models\Shirtitem;
use App\Models\Skirtitem;
use App\Models\Clean;
use App\Models\Reservation;
use App\Models\Typedress;
use App\Models\Dressimage;
use App\Models\Dressmeaadjustment;
use App\Models\Dressmea;
use App\Models\AdditionalChange;
use App\Models\AdjustmentRound;
use App\Models\Jewelryimage;
use App\Models\Typejewelry;
use App\Models\Jewelrysetitem;
use App\Models\Reservationfilters;
use App\Models\Jewelryset;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OrderController extends Controller
{
    //ออเดอร์ทั้งหมด
    public function ordertotal()
    {
        // $customers = Customer::with('orders')->get();
        $name_search = null;
        $customers = Customer::with('orders')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('employee.ordertotal', compact('customers', 'name_search'));
    }

    public function searchordertotal(Request $request)
    {

        $name_search = $request->input('name_search');

        $query = Customer::query();

        if ($name_search) {
            $query->where('customer_fname', 'LIKE', '%' . $name_search . '%')
                ->with('orders')
                ->orderBy('created_at', 'desc');
        } else {
            $query->with('orders')
                ->orderBy('created_at', 'desc');
        }
        $customers = $query->get();
        return view('employee.ordertotal', compact('customers', 'name_search'));
    }


    //ออเดอร์ดีเทล
    public function ordertotaldetail($id)
    {

        $order_id = $id;
        $orderdetail = Orderdetail::where('order_id', $id)->get();
        return view('employee.ordertotaldetail', compact('orderdetail', 'order_id'));
    }

    public function cutadjust($id)
    {
        $orderdetail = Orderdetail::find($id);
        $dress_adjusts = Dressmeaadjustment::where('order_detail_id', $id)->get();
        $date = Date::where('order_detail_id', $id)
            ->orderBy('created_at', 'desc')
            ->first();
        return view('employeecutdress.manageadjust', compact('orderdetail', 'dress_adjusts', 'date'));
    }

    // บันทึกการปรับแก้ชุดกรณีตัดชุด\
    public function savecutadjust(Request $request, $id)
    {
        $orderdetail = Orderdetail::find($id);
        //ตารางorderdetail
        $orderdetail->status_detail = "แก้ไขชุด";
        $orderdetail->save();

        //ตารางorderdetailstatus
        $create_status = new Orderdetailstatus();
        $create_status->order_detail_id = $id;
        $create_status->status = "แก้ไขชุด";
        $create_status->save();

        // ตารางdate
        $create_date = new Date();
        $create_date->order_detail_id = $id;
        $create_date->pickup_date = $request->input('new_date');
        $create_date->save();

        $round = AdjustmentRound::where('order_detail_id', $id)->max('round_number');
        $round = $round + 1; //แก้ไขครั้งที่

        // สร้างการแก้ไขครั้งที่
        $create_round = new AdjustmentRound();
        $create_round->order_detail_id = $id;
        $create_round->round_number = $round;
        $create_round->save();


        if ($request->input('adjust_name_')) {
            $adjust_name = $request->input('adjust_name_');
            $old = $request->input('old_');
            $new = $request->input('new_');
            $adjust_id = $request->input('adjust_id_');
            foreach ($adjust_name as $index => $name) {

                if ($old[$index] != $new[$index]) {
                    $create_adjust = new Dressmeasurementcutedit();
                    $create_adjust->adjustment_id = $adjust_id[$index];
                    $create_adjust->adjustment_round_id = $create_round->id;
                    $create_adjust->order_detail_id = $id;
                    $create_adjust->name = $name;
                    $create_adjust->old_size = $old[$index];
                    $create_adjust->edit_new_size = $new[$index];
                    $create_adjust->save();
                }
            }
        }



        if ($request->input('dec_des_')) {
            $dec_des = $request->input('dec_des_');
            $dec_price = $request->input('dec_price_');
            foreach ($dec_des as $index => $des) {
                $create_dec = new Decoration();
                $create_dec->order_detail_id = $id;
                $create_dec->adjustment_round_id = $create_round->id;
                $create_dec->decoration_description = $des;
                $create_dec->decoration_price = $dec_price[$index];
                $create_dec->save();
            }
        }
        return redirect()->route('employee.ordertotaldetailshow', ['id' => $id])->with('success', 'บันทึกการแก้ไขชุดสำเร็จ');
    }





    //ฟังชั่นแยกหน้า orderdetail
    public function ordertotaldetailshow($id)
    {
        $orderdetail = Orderdetail::find($id);
        // dd($orderdetail->type_order);
        if ($orderdetail->type_order == 1) {
            return $this->managedetailcutdress($id);
        } elseif ($orderdetail->type_order == 2) {
            return $this->managedetailrentdress($id);
        } elseif ($orderdetail->type_order == 3) {
            return $this->managedetailrentjewelry($id);
        } elseif ($orderdetail->type_order == 4) {
            return $this->managedetailrentcut($id);
        }
    }

    //จัดการเช่าชุด
    private function managedetailrentdress($id)
    {

        $orderdetail = Orderdetail::find($id);
        $dress = Dress::where('id', $orderdetail->dress_id)->select('dress_code_new', 'dress_code')->first();
        $customer_id = Order::where('id', $orderdetail->order_id)->value('customer_id');
        $customer = Customer::find($customer_id);
        $employee = User::find($orderdetail->employee_id);
        $fitting = Fitting::where('order_detail_id', $id)->get();
        $cost = Cost::where('order_detail_id', $id)->get();
        $date = Date::where('order_detail_id', $id)->get();
        $decoration = Decoration::where('order_detail_id', $id)->get();
        $imagerent = Imagerent::where('order_detail_id', $id)->get();
        $mea_dress = Dressmeasurement::where('dress_id', $orderdetail->dress_id)->get();
        $mea_orderdetail = Measurementorderdetail::where('order_detail_id', $id)->get();
        $mea_orderdetail_for_adjust = Measurementorderdetail::where('order_detail_id', $id)->get();
        $dressimage = Dressimage::where('dress_id', $orderdetail->dress_id)->first();

        $dress_mea_adjust = Dressmeaadjustment::where('order_detail_id', $id)->get();
        $dress_mea_adjust_button = Dressmeaadjustment::where('order_detail_id', $id)->get();

        $dress_mea_adjust_modal = Dressmeaadjustment::where('order_detail_id', $id)->get();
        $dress_mea_adjust_modal_show = Dressmeaadjustment::where('order_detail_id', $id)->get();


        $additional = AdditionalChange::where('order_detail_id', $id)->get();


        $orderdetailstatus = Orderdetailstatus::where('order_detail_id', $id)->get();
        $valuestatus = $orderdetail->status_detail;
        $valuestatus = Orderdetailstatus::where('order_detail_id', $id)
            ->latest('created_at')
            ->value('status');

        $status_if_dress = Reservation::where('dress_id', $orderdetail->dress_id)
            ->where('status_completed', 0)
            ->orderByRaw(" STR_TO_DATE(start_date, '%Y-%m-%d') asc")
            ->first();


        $his_dress_adjust = Dressmeasurementcutedit::where('order_detail_id', $id)->get();


        $dateeee = Date::where('order_detail_id', $id)
            ->orderBy('created_at', 'desc')
            ->first();

        return view('employeerentdress.managedetailrentdress', compact('additional', 'dress_mea_adjust_modal_show', 'status_if_dress', 'orderdetail', 'dress', 'employee', 'fitting', 'cost', 'date', 'decoration', 'imagerent', 'mea_dress', 'mea_orderdetail', 'orderdetailstatus', 'valuestatus', 'customer', 'mea_orderdetail_for_adjust', 'dressimage', 'dress_mea_adjust', 'dress_mea_adjust_modal', 'dress_mea_adjust_button', 'his_dress_adjust', 'dateeee'));
    }


    //จัดการเช่าเครื่องประดับ
    private function managedetailrentjewelry($id)
    {
        $orderdetail = Orderdetail::find($id);
        $order = Order::find($orderdetail->order_id) ; 
        $customer = Customer::find($order->customer_id) ; 
        $user = User::find($order->user_id) ; 
        $reservation = Reservation::find($orderdetail->reservation_id) ;
        $reservationfilter = Reservationfilters::where('reservation_id',$reservation->id)->get() ; 
        $Date = Date::where('order_detail_id',$orderdetail->id)
                        ->orderBy('created_at','desc')
                        ->first() ;  
        if($reservation->jewelry_id){
            $jewelry = Jewelry::find($reservation->jewelry_id) ; 
            $typejewelry = Typejewelry::where('id',$jewelry->type_jewelry_id)->first() ; 
            $imagejewelry = Jewelryimage::where('jewelry_id',$jewelry->id)->first() ; 
            $setjewelry = null ; 
            $setjewelryitem = null ; 
        }
        else{
            $setjewelry = Jewelryset::find($reservation->jewelry_set_id) ; 
            $setjewelryitem = Jewelrysetitem::where('jewelry_set_id',$setjewelry->id)->get() ; 
            $jewelry = null ; 
            $typejewelry = null ; 
            $imagejewelry = null ; 
        }
        $orderdetailstatus = Orderdetailstatus::where('order_detail_id', $id)->get();
        $additional = AdditionalChange::where('order_detail_id', $id)->get();
        return view('employeerentjewelry.managedetailrentjewelry',compact('orderdetail','reservation','jewelry','typejewelry','orderdetailstatus','setjewelry','imagejewelry','order','customer','user','setjewelryitem','Date','reservationfilter','additional'));
    }

    //จัดการเช่าตัด
    private function managedetailrentcut($id)
    {
        $orderdetail = Orderdetail::find($id);
        $dress = Dress::where('id', $orderdetail->dress_id)->select('dress_code_new', 'dress_code')->first();
        $employee = User::find($orderdetail->employee_id);
        $fitting = Fitting::where('order_detail_id', $id)->get();
        $cost = Cost::where('order_detail_id', $id)->get();
        $date = Date::where('order_detail_id', $id)->get();
        $decoration = Decoration::where('order_detail_id', $id)->get();
        $imagerent = Imagerent::where('order_detail_id', $id)->get();
        $mea_dress = Dressmeasurement::where('dress_id', $orderdetail->dress_id)->get();
        $mea_orderdetail = Measurementorderdetail::where('order_detail_id', $id)->get();
        $orderdetailstatus = Orderdetailstatus::where('order_detail_id', $id)->get();
        $orderdetailstatusedit = Orderdetailstatus::where('order_detail_id', $id)->get();

        $valuestatus = $orderdetail->status_detail;
        $valuestatus = Orderdetailstatus::where('order_detail_id', $id)
            ->latest('created_at')
            ->value('status');
        return view('employeerentcut.managedetailrentcut', compact('orderdetail', 'dress', 'employee', 'fitting', 'cost', 'date', 'decoration', 'imagerent', 'mea_dress', 'mea_orderdetail', 'orderdetailstatus', 'valuestatus', 'orderdetailstatusedit'));
    }
    //จัดการตัดชุด
    private function managedetailcutdress($id)
    {
        $orderdetail = Orderdetail::find($id);
        $customer_id = Order::where('id', $orderdetail->order_id)->value('customer_id');
        $customer = Customer::find($customer_id);
        $dress = Dress::where('id', $orderdetail->dress_id)->select('dress_code_new', 'dress_code')->first();
        $employee = User::find($orderdetail->employee_id);
        $fitting = Fitting::where('order_detail_id', $id)->get();
        $cost = Cost::where('order_detail_id', $id)->get();
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


        return view('employeecutdress.managedetailcutdress', compact('is_admin', 'who_login', 'person_order', 'orderdetail', 'dress', 'employee', 'fitting', 'cost', 'Date', 'decoration', 'imagerent', 'mea_dress', 'mea_orderdetail', 'orderdetailstatus', 'valuestatus', 'customer', 'mea_orderdetailforedit', 'dress_adjusts', 'dress_edit_cut', 'round', 'route_modal'));
    }


    public function ordertotaldetailpostpone($id)
    {

        //แยกก่อนว่าชุดแยกเช่าได้หรือแยกไม่ได้
        $dress_id = Orderdetail::where('id', $id)->value('dress_id');
        $dress_check = Dress::find($dress_id);
        if ($dress_check->separable == 1) {
            return $this->detailpostponeno($id);
        } elseif ($dress_check->separable == 2) {

            $orderdetail = Orderdetail::find($id);

            // เช่าแค่เสื้อ
            if ($orderdetail->shirtitems_id) {
                return $this->detailpostponeyesshirt($id);
            }
            // เสื้อแค่ผ้าถุง
            elseif ($orderdetail->skirtitems_id) {
                return $this->detailpostponeyesskirt($id);
            }
            // เช่าทั้งชุด
            else {
                return $this->detailpostponeyesdresstotal($id);
            }
        }
    }

    private function detailpostponeno($id)
    {
        $orderdetail = Orderdetail::find($id);
        $reservation_id = Reservation::where('id', $orderdetail->reservation_id)->value('id');
        $reser = Reservation::find($reservation_id);
        $dress = Dress::find($orderdetail->dress_id);
        $typedress = Typedress::find($dress->type_dress_id);
        $cus_id = Order::where('id', $orderdetail->order_id)->value('customer_id');
        $cus = Customer::find($cus_id);
        $reservation_dress_total = Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->get();
        // สถานะชุดปัจจันตอนนี้อยู่ไหน
        $status_current  =
            Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->value('status');



        if ($status_current == "ถูกจอง") {
            $text_status = "อยู่ในร้าน";
        } else {
            $text_status = $status_current;
        }
        $value_start_date = $reser->start_date;
        $value_end_date = $reser->end_date;
        $condition = 'no';
        $value_start_date = $reser->start_date;
        $value_end_date = $reser->end_date;
        return view('employeerentdress.postponeno', compact('reservation_dress_total', 'orderdetail', 'reser', 'dress', 'typedress', 'cus', 'text_status', 'value_start_date', 'value_end_date', 'condition'));
    }

    //checkตรวจสอบก่อนว่า วันที่นัดใหม่ - คืนใหม่ มันทับกับคนอื่นไหมๆ (ของชุดที่แยกเช่าไม่ได้)
    public function ordertotaldetailpostponechecked(Request $request, $id)
    {

        $orderdetail = Orderdetail::find($id);
        $reservation_id = Reservation::where('id', $orderdetail->reservation_id)->value('id');
        $reser = Reservation::find($reservation_id);
        $dress = Dress::find($orderdetail->dress_id);
        $typedress = Typedress::find($dress->type_dress_id);
        $cus_id = Order::where('id', $orderdetail->order_id)->value('customer_id');
        $cus = Customer::find($cus_id);
        $reservation_dress_total = Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->get();
        // สถานะชุดปัจจันตอนนี้อยู่ไหน
        $status_current  =
            Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->value('status');
        if ($status_current == "ถูกจอง") {
            $text_status = "อยู่ในร้าน";
        } else {
            $text_status = $status_current;
        }


        //เช็ควันที่
        $value_start_date = $request->input('new_pickup_date');
        $value_end_date = $request->input('new_return_date');


        $pickup = Carbon::parse($request->input('new_pickup_date'));
        $return = Carbon::parse($request->input('new_return_date'));

        $past_7 = $pickup->copy()->subDays(7);
        $past_1 = $pickup->copy()->subDays(1);
        $pickup_start = $pickup->copy();
        $return_end = $return->copy();
        $future_1 = $return->copy()->addDays(1);
        $future_7 = $return->copy()->addDays(7);


        $check_reservation = Reservation::where('status_completed', 0)
            ->where('dress_id', $dress->id)
            ->whereNot('id', $reser->id)
            ->get();

        $condition = true;
        foreach ($check_reservation as $item) {
            $reservation_start = Carbon::parse($item->start_date);
            $reservation_end = Carbon::parse($item->end_date);

            if ($reservation_start->between($past_7, $past_1) ||  $reservation_end->between($past_7, $past_1)) {
                $condition = false;
                break;
            }
            if ($reservation_start->between($pickup_start, $return_end) || $reservation_end->between($pickup_start, $return_end)) {
                $condition = false;
                break;
            }
            if ($reservation_start->between($future_1, $future_7) || $reservation_end->between($future_1, $future_7)) {
                $condition = false;
                break;
            }
        }
        if ($condition == true) {
            session()->flash('condition', 'passsuccesst');
        } elseif ($condition == false) {
            session()->flash('condition', 'failno');
        }
        return view('employeerentdress.postponeno', compact('reservation_dress_total', 'orderdetail', 'reser', 'dress', 'typedress', 'cus', 'text_status', 'value_start_date', 'condition', 'value_end_date'));
    }


    public function ordertotaldetailpostponecheckeddresstotal(Request $request, $id)
    {

        $orderdetail = Orderdetail::find($id);
        $reservation_id = Reservation::where('id', $orderdetail->reservation_id)->value('id');
        $reser = Reservation::find($reservation_id);
        $dress = Dress::find($orderdetail->dress_id);
        $typedress = Typedress::find($dress->type_dress_id);
        $cus_id = Order::where('id', $orderdetail->order_id)->value('customer_id');
        $cus = Customer::find($cus_id);

        $shirt_id = Shirtitem::where('dress_id', $dress->id)->value('id');
        $skirt_id = Skirtitem::where('dress_id', $dress->id)->value('id');


        //เช่าเฉพาะทั้งชุด
        $reservation_dress_total = Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->whereNull('shirtitems_id')
            ->whereNull('skirtitems_id')
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->get();

        // เช่าเฉพะเสื้อ
        $reservation_dress_shirt = Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->where('shirtitems_id', $shirt_id)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->get();
        // เช่าเฉพาะผ้าถุง
        $reservation_dress_skirt = Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->where('skirtitems_id', $skirt_id)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->get();



        // สำหรับแสดงลำดับคิวที่ 1 2 3 
        $reservation_dress_index = Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->get();



        // สถานะชุดปัจจันตอนนี้อยู่ไหน
        $status_current  =
            Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->value('status');
        if ($status_current == "ถูกจอง") {
            $text_status = "อยู่ในร้าน";
        } else {
            $text_status = $status_current;
        }

        //เช็ควันที่
        $value_start_date = $request->input('new_pickup_date');
        $value_end_date = $request->input('new_return_date');

        $input_start = \Carbon\Carbon::parse($value_start_date);
        $input_end = \Carbon\Carbon::parse($value_end_date);


        $input_start_7 = $input_start->copy()->subDays(7);
        $input_start_1 = $input_start->copy()->subDays(1);
        $input_start_betwwen = $input_start->copy();
        $input_end_between = $input_end->copy();
        $input_end_1 = $input_end->copy()->addDays(1);
        $input_end_7 = $input_end->copy()->addDays(7);

        $condition = true;
        // เช็คแค่ทั้งชุด
        $reservation_check_total_dress = Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->whereNull('shirtitems_id')
            ->whereNull('skirtitems_id')
            ->whereNot('id', $orderdetail->reservation_id)
            ->get();
        foreach ($reservation_check_total_dress as $item) {
            $reser_start = \Carbon\Carbon::parse($item->start_date);
            $reser_end = \Carbon\Carbon::parse($item->end_date);
            if ($reser_start->between($input_start_7, $input_start_1)   || $reser_end->between($input_start_7, $input_start_1)) {
                $condition = false;
                break;
            }
            if ($reser_start->between($input_start_betwwen, $input_end_between)   || $reser_end->between($input_start_betwwen, $input_end_between)) {
                $condition = false;
                break;
            }
            if ($reser_start->between($input_end_1, $input_end_7)   || $reser_end->between($input_end_1, $input_end_7)) {
                $condition = false;
                break;
            }
        }

        // เช็คแค่เฉพาะเสื้อ
        $reservation_check_total_shirt = Reservation::where('status_completed', 0)
            ->where('shirtitems_id', $shirt_id)
            ->get();
        foreach ($reservation_check_total_shirt as $item) {
            $reser_start = \Carbon\Carbon::parse($item->start_date);
            $reser_end = \Carbon\Carbon::parse($item->end_date);
            if ($reser_start->between($input_start_7, $input_start_1)   || $reser_end->between($input_start_7, $input_start_1)) {
                $condition = false;
                break;
            }
            if ($reser_start->between($input_start_betwwen, $input_end_between)   || $reser_end->between($input_start_betwwen, $input_end_between)) {
                $condition = false;
                break;
            }
            if ($reser_start->between($input_end_1, $input_end_7)   || $reser_end->between($input_end_1, $input_end_7)) {
                $condition = false;
                break;
            }
        }

        // เช็คแค่เฉพาะผ้าถุง
        $reservation_check_total_skirt = Reservation::where('status_completed', 0)
            ->where('skirtitems_id', $skirt_id)
            ->get();
        foreach ($reservation_check_total_skirt as $item) {
            $reser_start = \Carbon\Carbon::parse($item->start_date);
            $reser_end = \Carbon\Carbon::parse($item->end_date);
            if ($reser_start->between($input_start_7, $input_start_1)   || $reser_end->between($input_start_7, $input_start_1)) {
                $condition = false;
                break;
            }
            if ($reser_start->between($input_start_betwwen, $input_end_between)   || $reser_end->between($input_start_betwwen, $input_end_between)) {
                $condition = false;
                break;
            }
            if ($reser_start->between($input_end_1, $input_end_7)   || $reser_end->between($input_end_1, $input_end_7)) {
                $condition = false;
                break;
            }
        }


        if ($condition == true) {
            session()->flash('condition', 'passsuccesst');
        } elseif ($condition == false) {
            session()->flash('condition', 'failno');
        }
        return view('employeerentdress.postponeyestotaldress', compact('reservation_dress_index', 'reservation_dress_skirt', 'reservation_dress_shirt', 'reservation_dress_total', 'orderdetail', 'reser', 'dress', 'typedress', 'cus', 'text_status', 'value_start_date', 'value_end_date', 'condition'));
    }



    public function postponecheckedpass(Request $request, $id)
    {
        $reservation_id = $request->input('reservation_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');


        $reservation = Reservation::find($reservation_id);
        $reservation->start_date = $start_date;
        $reservation->end_date = $end_date;
        $reservation->save();


        $date = new Date();
        $date->order_detail_id = $id;
        $date->pickup_date = $start_date;
        $date->return_date = $end_date;
        $date->save();

        return redirect()->route('employee.ordertotaldetailpostpone', ['id' => $id])->with('success', 'เลื่อนวันนัดรับ - นัดคืนชุด สำเร็จ');
    }



    private function detailpostponeyesshirt($id)
    {
        $orderdetail = Orderdetail::find($id);
        $reservation_id = Reservation::where('id', $orderdetail->reservation_id)->value('id');
        $reser = Reservation::find($reservation_id);
        $dress = Dress::find($orderdetail->dress_id);
        $typedress = Typedress::find($dress->type_dress_id);
        $cus_id = Order::where('id', $orderdetail->order_id)->value('customer_id');
        $cus = Customer::find($cus_id);

        $shirt_id = Shirtitem::where('dress_id', $dress->id)->value('id');
        $skirt_id = Skirtitem::where('dress_id', $dress->id)->value('id');


        //เช่าเฉพาะทั้งชุด
        $reservation_dress_total = Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->whereNull('shirtitems_id')
            ->whereNull('skirtitems_id')
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->get();

        // เช่าเฉพะเสื้อ
        $reservation_dress_shirt = Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->where('shirtitems_id', $shirt_id)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->get();


        // สำหรับแสดงลำดับคิวที่ 1 2 3 
        $list_for_Queue = [];
        $reserv_dress_index = Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->whereNull('shirtitems_id')
            ->whereNull('skirtitems_id')
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->get();
        $reservation_dress_shirt = Reservation::where('status_completed', 0)
            ->where('shirtitems_id', $shirt_id)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->whereIn('status', ['ถูกจอง', 'กำลังเช่า'])
            ->get();
        foreach ($reserv_dress_index as $item) {
            $list_for_Queue[] = $item->id;
        }
        foreach ($reservation_dress_shirt as $item) {
            $list_for_Queue[] = $item->id;
        }

        $reservation_dress_index = Reservation::whereIn('id', $list_for_Queue)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->get();
        // สถานะชุดปัจจันตอนนี้อยู่ไหน
        $status_current  =
            Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->value('status');
        if ($status_current == "ถูกจอง") {
            $text_status = "อยู่ในร้าน";
        } else {
            $text_status = $status_current;
        }

        $value_start_date = $reser->start_date;
        $value_end_date = $reser->end_date;
        $condition = 'no';
        return view('employeerentdress.postponeyestotalshirt', compact('reservation_dress_index', 'reservation_dress_shirt', 'reservation_dress_total', 'orderdetail', 'reser', 'dress', 'typedress', 'cus', 'text_status', 'value_start_date', 'value_end_date', 'condition'));
    }

    public function ordertotaldetailpostponecheckeddressshirt(Request $request, $id)
    {
        $orderdetail = Orderdetail::find($id);
        $reservation_id = Reservation::where('id', $orderdetail->reservation_id)->value('id');
        $reser = Reservation::find($reservation_id);
        $dress = Dress::find($orderdetail->dress_id);
        $typedress = Typedress::find($dress->type_dress_id);
        $cus_id = Order::where('id', $orderdetail->order_id)->value('customer_id');
        $cus = Customer::find($cus_id);

        $shirt_id = Shirtitem::where('dress_id', $dress->id)->value('id');
        $skirt_id = Skirtitem::where('dress_id', $dress->id)->value('id');


        //เช่าเฉพาะทั้งชุด
        $reservation_dress_total = Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->whereNull('shirtitems_id')
            ->whereNull('skirtitems_id')
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->get();

        // เช่าเฉพะเสื้อ
        $reservation_dress_shirt = Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->where('shirtitems_id', $shirt_id)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->get();


        // สำหรับแสดงลำดับคิวที่ 1 2 3 
        $list_for_Queue = [];
        $reserv_dress_index = Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->whereNull('shirtitems_id')
            ->whereNull('skirtitems_id')
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->get();
        $reservation_dress_shirt = Reservation::where('status_completed', 0)
            ->where('shirtitems_id', $shirt_id)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->whereIn('status', ['ถูกจอง', 'กำลังเช่า'])
            ->get();
        foreach ($reserv_dress_index as $item) {
            $list_for_Queue[] = $item->id;
        }
        foreach ($reservation_dress_shirt as $item) {
            $list_for_Queue[] = $item->id;
        }

        $reservation_dress_index = Reservation::whereIn('id', $list_for_Queue)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->get();
        // สถานะชุดปัจจันตอนนี้อยู่ไหน
        $status_current  =
            Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->value('status');
        if ($status_current == "ถูกจอง") {
            $text_status = "อยู่ในร้าน";
        } else {
            $text_status = $status_current;
        }

        //เช็ควันที่
        $value_start_date = $request->input('new_pickup_date');
        $value_end_date = $request->input('new_return_date');

        $input_start = \Carbon\Carbon::parse($value_start_date);
        $input_end = \Carbon\Carbon::parse($value_end_date);


        $input_start_7 = $input_start->copy()->subDays(7);
        $input_start_1 = $input_start->copy()->subDays(1);
        $input_start_betwwen = $input_start->copy();
        $input_end_between = $input_end->copy();
        $input_end_1 = $input_end->copy()->addDays(1);
        $input_end_7 = $input_end->copy()->addDays(7);

        $condition = true;
        // เช็คแค่ทั้งชุด
        $reservation_check_total_dress = Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->whereNull('shirtitems_id')
            ->whereNull('skirtitems_id')
            ->get();
        foreach ($reservation_check_total_dress as $item) {
            $reser_start = \Carbon\Carbon::parse($item->start_date);
            $reser_end = \Carbon\Carbon::parse($item->end_date);
            if ($reser_start->between($input_start_7, $input_start_1)   || $reser_end->between($input_start_7, $input_start_1)) {
                $condition = false;
                break;
            }
            if ($reser_start->between($input_start_betwwen, $input_end_between)   || $reser_end->between($input_start_betwwen, $input_end_between)) {
                $condition = false;
                break;
            }
            if ($reser_start->between($input_end_1, $input_end_7)   || $reser_end->between($input_end_1, $input_end_7)) {
                $condition = false;
                break;
            }
        }

        // เช็คแค่เฉพาะเสื้อ
        $reservation_check_total_shirt = Reservation::where('status_completed', 0)
            ->where('shirtitems_id', $shirt_id)
            ->whereNot('id', $orderdetail->reservation_id)
            ->get();
        foreach ($reservation_check_total_shirt as $item) {
            $reser_start = \Carbon\Carbon::parse($item->start_date);
            $reser_end = \Carbon\Carbon::parse($item->end_date);
            if ($reser_start->between($input_start_7, $input_start_1)   || $reser_end->between($input_start_7, $input_start_1)) {
                $condition = false;
                break;
            }
            if ($reser_start->between($input_start_betwwen, $input_end_between)   || $reser_end->between($input_start_betwwen, $input_end_between)) {
                $condition = false;
                break;
            }
            if ($reser_start->between($input_end_1, $input_end_7)   || $reser_end->between($input_end_1, $input_end_7)) {
                $condition = false;
                break;
            }
        }
        if ($condition == true) {
            session()->flash('condition', 'passsuccesst');
        } elseif ($condition == false) {
            session()->flash('condition', 'failno');
        }
        return view('employeerentdress.postponeyestotalshirt', compact('reservation_dress_index', 'reservation_dress_shirt', 'reservation_dress_total', 'orderdetail', 'reser', 'dress', 'typedress', 'cus', 'text_status', 'value_start_date', 'value_end_date', 'condition'));
    }


    private function detailpostponeyesskirt($id)
    {
        $orderdetail = Orderdetail::find($id);
        $reservation_id = Reservation::where('id', $orderdetail->reservation_id)->value('id');
        $reser = Reservation::find($reservation_id);
        $dress = Dress::find($orderdetail->dress_id);
        $typedress = Typedress::find($dress->type_dress_id);
        $cus_id = Order::where('id', $orderdetail->order_id)->value('customer_id');
        $cus = Customer::find($cus_id);

        $shirt_id = Shirtitem::where('dress_id', $dress->id)->value('id');
        $skirt_id = Skirtitem::where('dress_id', $dress->id)->value('id');


        //เช่าเฉพาะทั้งชุด
        $reservation_dress_total = Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->whereNull('shirtitems_id')
            ->whereNull('skirtitems_id')
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->get();

        // เช่าเฉพะผ้าถุง
        $reservation_dress_skirt = Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->where('skirtitems_id', $skirt_id)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->get();


        // สำหรับแสดงลำดับคิวที่ 1 2 3 
        $list_for_Queue = [];
        $reserv_dress_index = Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->whereNull('shirtitems_id')
            ->whereNull('skirtitems_id')
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->get();
        $reservation_dress_skirt = Reservation::where('status_completed', 0)
            ->where('skirtitems_id', $skirt_id)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->whereIn('status', ['ถูกจอง', 'กำลังเช่า'])
            ->get();
        foreach ($reserv_dress_index as $item) {
            $list_for_Queue[] = $item->id;
        }
        foreach ($reservation_dress_skirt as $item) {
            $list_for_Queue[] = $item->id;
        }

        $reservation_dress_index = Reservation::whereIn('id', $list_for_Queue)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->get();
        // สถานะชุดปัจจันตอนนี้อยู่ไหน
        $status_current  =
            Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->value('status');
        if ($status_current == "ถูกจอง") {
            $text_status = "อยู่ในร้าน";
        } else {
            $text_status = $status_current;
        }

        $value_start_date = $reser->start_date;
        $value_end_date = $reser->end_date;
        $condition = 'no';
        return view('employeerentdress.postponeyestotalskirt', compact('reservation_dress_index', 'reservation_dress_skirt', 'reservation_dress_total', 'orderdetail', 'reser', 'dress', 'typedress', 'cus', 'text_status', 'value_start_date', 'value_end_date', 'condition'));
    }

    public function ordertotaldetailpostponecheckeddressskirt(Request $request, $id)
    {
        $orderdetail = Orderdetail::find($id);
        $reservation_id = Reservation::where('id', $orderdetail->reservation_id)->value('id');
        $reser = Reservation::find($reservation_id);
        $dress = Dress::find($orderdetail->dress_id);
        $typedress = Typedress::find($dress->type_dress_id);
        $cus_id = Order::where('id', $orderdetail->order_id)->value('customer_id');
        $cus = Customer::find($cus_id);

        $shirt_id = Shirtitem::where('dress_id', $dress->id)->value('id');
        $skirt_id = Skirtitem::where('dress_id', $dress->id)->value('id');


        //เช่าเฉพาะทั้งชุด
        $reservation_dress_total = Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->whereNull('shirtitems_id')
            ->whereNull('skirtitems_id')
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->get();

        // เช่าเฉพะผ้าถุง
        $reservation_dress_skirt = Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->where('skirtitems_id', $skirt_id)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->get();


        // สำหรับแสดงลำดับคิวที่ 1 2 3 
        $list_for_Queue = [];
        $reserv_dress_index = Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->whereNull('shirtitems_id')
            ->whereNull('skirtitems_id')
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->get();
        $reservation_dress_skirt = Reservation::where('status_completed', 0)
            ->where('skirtitems_id', $skirt_id)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->whereIn('status', ['ถูกจอง', 'กำลังเช่า'])
            ->get();
        foreach ($reserv_dress_index as $item) {
            $list_for_Queue[] = $item->id;
        }
        foreach ($reservation_dress_skirt as $item) {
            $list_for_Queue[] = $item->id;
        }

        $reservation_dress_index = Reservation::whereIn('id', $list_for_Queue)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->get();
        // สถานะชุดปัจจันตอนนี้อยู่ไหน
        $status_current  =
            Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->value('status');
        if ($status_current == "ถูกจอง") {
            $text_status = "อยู่ในร้าน";
        } else {
            $text_status = $status_current;
        }

        //เช็ควันที่
        $value_start_date = $request->input('new_pickup_date');
        $value_end_date = $request->input('new_return_date');

        $input_start = \Carbon\Carbon::parse($value_start_date);
        $input_end = \Carbon\Carbon::parse($value_end_date);


        $input_start_7 = $input_start->copy()->subDays(7);
        $input_start_1 = $input_start->copy()->subDays(1);
        $input_start_betwwen = $input_start->copy();
        $input_end_between = $input_end->copy();
        $input_end_1 = $input_end->copy()->addDays(1);
        $input_end_7 = $input_end->copy()->addDays(7);

        $condition = true;
        // เช็คแค่ทั้งชุด
        $reservation_check_total_dress = Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->whereNull('shirtitems_id')
            ->whereNull('skirtitems_id')
            ->get();
        foreach ($reservation_check_total_dress as $item) {
            $reser_start = \Carbon\Carbon::parse($item->start_date);
            $reser_end = \Carbon\Carbon::parse($item->end_date);
            if ($reser_start->between($input_start_7, $input_start_1)   || $reser_end->between($input_start_7, $input_start_1)) {
                $condition = false;
                break;
            }
            if ($reser_start->between($input_start_betwwen, $input_end_between)   || $reser_end->between($input_start_betwwen, $input_end_between)) {
                $condition = false;
                break;
            }
            if ($reser_start->between($input_end_1, $input_end_7)   || $reser_end->between($input_end_1, $input_end_7)) {
                $condition = false;
                break;
            }
        }

        // เช็คแค่เฉพาะผ้าถุง
        $reservation_check_total_skirt = Reservation::where('status_completed', 0)
            ->where('skirtitems_id', $skirt_id)
            ->whereNot('id', $orderdetail->reservation_id)
            ->get();
        foreach ($reservation_check_total_skirt as $item) {
            $reser_start = \Carbon\Carbon::parse($item->start_date);
            $reser_end = \Carbon\Carbon::parse($item->end_date);
            if ($reser_start->between($input_start_7, $input_start_1)   || $reser_end->between($input_start_7, $input_start_1)) {
                $condition = false;
                break;
            }
            if ($reser_start->between($input_start_betwwen, $input_end_between)   || $reser_end->between($input_start_betwwen, $input_end_between)) {
                $condition = false;
                break;
            }
            if ($reser_start->between($input_end_1, $input_end_7)   || $reser_end->between($input_end_1, $input_end_7)) {
                $condition = false;
                break;
            }
        }
        if ($condition == true) {
            session()->flash('condition', 'passsuccesst');
        } elseif ($condition == false) {
            session()->flash('condition', 'failno');
        }

        return view('employeerentdress.postponeyestotalskirt', compact('reservation_dress_index', 'reservation_dress_skirt', 'reservation_dress_total', 'orderdetail', 'reser', 'dress', 'typedress', 'cus', 'text_status', 'value_start_date', 'value_end_date', 'condition'));
    }



    private function detailpostponeyesdresstotal($id)
    {
        $orderdetail = Orderdetail::find($id);
        $reservation_id = Reservation::where('id', $orderdetail->reservation_id)->value('id');
        $reser = Reservation::find($reservation_id);
        $dress = Dress::find($orderdetail->dress_id);
        $typedress = Typedress::find($dress->type_dress_id);
        $cus_id = Order::where('id', $orderdetail->order_id)->value('customer_id');
        $cus = Customer::find($cus_id);

        $shirt_id = Shirtitem::where('dress_id', $dress->id)->value('id');
        $skirt_id = Skirtitem::where('dress_id', $dress->id)->value('id');


        //เช่าเฉพาะทั้งชุด
        $reservation_dress_total = Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->whereNull('shirtitems_id')
            ->whereNull('skirtitems_id')
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->get();

        // เช่าเฉพะเสื้อ
        $reservation_dress_shirt = Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->where('shirtitems_id', $shirt_id)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->get();
        // เช่าเฉพาะผ้าถุง
        $reservation_dress_skirt = Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->where('skirtitems_id', $skirt_id)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->get();



        // สำหรับแสดงลำดับคิวที่ 1 2 3 
        $reservation_dress_index = Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->get();



        // สถานะชุดปัจจันตอนนี้อยู่ไหน
        $status_current  =
            Reservation::where('status_completed', 0)
            ->where('dress_id', $orderdetail->dress_id)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->value('status');
        if ($status_current == "ถูกจอง") {
            $text_status = "อยู่ในร้าน";
        } else {
            $text_status = $status_current;
        }

        $value_start_date = $reser->start_date;
        $value_end_date = $reser->end_date;
        $condition = 'no';

        return view('employeerentdress.postponeyestotaldress', compact('reservation_dress_index', 'reservation_dress_skirt', 'reservation_dress_shirt', 'reservation_dress_total', 'orderdetail', 'reser', 'dress', 'typedress', 'cus', 'text_status', 'value_start_date', 'value_end_date', 'condition'));
    }

















    //เพิ่มข้อมูลการวัดfitting
    public function actionaddfitting(Request $request, $id)
    {
        $add_fitting = new Fitting();
        $add_fitting->order_detail_id = $id;
        $add_fitting->fitting_date = $request->input('add_fitting_date');
        $add_fitting->fitting_note = $request->input('add_fitting_note');
        $add_fitting->fitting_status = "ยังไม่มาลอง";
        $add_fitting->save();
        return redirect()->back()->with('success', 'เพิ่มข้อมูลการนัดสำเร็จ !');
    }


    public function actionupdatefitting(Request $request, $id)
    {
        $update_fitting = Fitting::find($id);
        $update_fitting->fitting_note = $request->input('update_fitting_note');
        $update_fitting->fitting_status = $request->input('update_fitting_status');
        $update_fitting->save();
        return redirect()->back()->with('success', 'อัพเดตข้อมูลสำเร็จ !');
    }

    public function actiondeletefitting($id)
    {
        // dd($id) ; 
        $delete_fitting = Fitting::find($id);
        $delete_fitting->delete();
        return redirect()->back()->with('success', 'ลบการนัดสำเร็จ !');
    }

    public function actionupdatecost(Request $request, $id)
    {
        $update_cost = Cost::find($id);
        $update_cost->cost_type = $request->input('update_cost_type');
        $update_cost->cost_value = $request->input('update_cost_value');
        $update_cost->save();

        $update_financial_table = Financial::where('cost_id', $id)->value('id');
        $update_financial = Financial::find($update_financial_table);
        $update_financial->item_name = $request->input('update_cost_type');
        $update_financial->financial_expenses = $request->input('update_cost_value');
        $update_financial->save();

        return redirect()->back()->with('success', 'อัพเดตค่าใช้จ่ายสำเร็จ !');
    }

    public function actiondeletecost($id)
    {
        $delete_cost = Cost::find($id);
        $delete_cost->delete();
        //ลบลูกๆมันด้วย
        Financial::where('cost_id', $id)->delete();
        return redirect()->back()->with('success', 'ลบค่าใช้จ่ายสำเร็จ !');
    }
    public function actionadddecoration(Request $request, $id)
    {
        $add_decoration = new Decoration();
        $add_decoration->order_detail_id = $id;
        $add_decoration->decoration_description = $request->input('add_decoration_description');
        $add_decoration->decoration_price = $request->input('add_decoration_price');
        $add_decoration->save();
        return redirect()->back()->with('success', 'เพิ่มข้อมูลสำเร็จ !');
    }

    public function actionupdatemeadress(Request $request, $id)
    {
        $update_mea_dress = Dressmeasurement::find($id);
        $update_mea_dress->measurement_dress_number = $request->input('update_measurement_dress_number');
        $update_mea_dress->measurement_dress_unit = $request->input('update_measurement_dress_unit');
        $update_mea_dress->save();
        return redirect()->back()->with('success', 'อัพเดตข้อมูลการวัดสำเร็จ !');
    }



    public function actionaddmeaorderdetail(Request $request,  $id)
    {
        $add_mea_orderdetail = new Measurementorderdetail();
        $add_mea_orderdetail->order_detail_id = $id;
        $add_mea_orderdetail->measurement_name = $request->input('add_measurement_name');
        $add_mea_orderdetail->measurement_number = $request->input('add_measurement_number');
        $add_mea_orderdetail->measurement_unit = $request->input('add_measurement_unit');
        $add_mea_orderdetail->save();
        return redirect()->back()->with('success', 'เพิ่มข้อมูลการวัดสำเร็จ !');
    }
    public function actionupdatemeaorderdetail(Request $request, $id)
    {
        $update_mea_orderdetail = Measurementorderdetail::find($id);
        $update_mea_orderdetail->measurement_name = $request->input('update_measurement_name');
        $update_mea_orderdetail->measurement_number = $request->input('update_measurement_number');
        $update_mea_orderdetail->measurement_unit = $request->input('update_measurement_unit');
        $update_mea_orderdetail->save();
        return redirect()->back()->with('success', 'อัพเดตข้อมูลการวัดสำเร็จ !');
    }

    public function actiondeletemeaorderdetail($id)
    {
        $delete_mea_orderdetail = Measurementorderdetail::find($id);
        $delete_mea_orderdetail->delete();
        return redirect()->back()->with('success', 'ลบข้อมูลการวัดสำเร็จ !');
    }

    public function actionupdatedecoration(Request $request, $id)
    {
        $update_decoration = Decoration::find($id);
        $update_decoration->decoration_description = $request->input('update_decoration_description');
        $update_decoration->decoration_price = $request->input('update_decoration_price');
        $update_decoration->save();
        return redirect()->back()->with('success', 'อัพเดตข้อมูลสำเร็จ !');
    }
    public function actiondeletedecoration($id)
    {
        $delete_decoration = Decoration::find($id);
        $delete_decoration->delete();
        return redirect()->back()->with('success', 'ลบข้อมูลสำเร็จ !');
    }


    public function actionupdatestatusrentdress(Request $request, $id)
    {
        $orderdetail = Orderdetail::find($id);
        $show_date = Date::where('order_detail_id', $orderdetail->id)
            ->orderBy('created_at', 'desc')
            ->first();
        $status = $orderdetail->status_detail;
        if ($status == 'ถูกจอง') {
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

            //ตารางreservation 
            $reservation = Reservation::find($orderdetail->reservation_id);
            $reservation->status = 'กำลังเช่า';
            $reservation->save();

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
        }
        elseif ($status == "กำลังเช่า") {

            $total_damage_insurance = $request->input('total_damage_insurance'); //1.ปรับเงินประกันจริงๆ 
            $late_return_fee = $request->input('late_return_fee'); //2.ค่าปรับส่งคืนชุดล่าช้า:
            $late_chart = $request->input('late_chart'); //3.ค่าธรรมเนียมขยายระยะเวลาเช่า:

            if ($total_damage_insurance > 0) {
                $create_additional = new AdditionalChange();
                $create_additional->order_detail_id = $id;
                $create_additional->charge_type = 1;
                $create_additional->amount = $total_damage_insurance;
                $create_additional->save();
            }
            if ($late_return_fee > 0) {
                $create_additional = new AdditionalChange();
                $create_additional->order_detail_id = $id;
                $create_additional->charge_type = 2;
                $create_additional->amount = $late_return_fee;
                $create_additional->save();
            }
            if ($late_chart) {
                $create_additional = new AdditionalChange();
                $create_additional->order_detail_id = $id;
                $create_additional->charge_type = 3;
                $create_additional->amount = $late_chart;
                $create_additional->save();
            }

            //ตารางorderdetail
            $orderdetail->status_detail = "คืนชุดแล้ว";
            $orderdetail->save();

            // อัปเดตตารางdate
            $date_id = Date::where('order_detail_id', $id)
                ->orderBy('created_at', 'desc')
                ->value('id');
            $update_real = Date::find($date_id);
            $update_real->actua_return_date = now(); //วันที่คืนจริงๆ
            $update_real->save();



            //ตารางorderdetailstatus
            $create_status = new Orderdetailstatus();
            $create_status->order_detail_id = $id;
            $create_status->status = "คืนชุดแล้ว";
            $create_status->save();


            //+1จำนวนคราั้งที่ถูกเช่า
            $dress = Dress::where('id', $orderdetail->dress_id)->first();
            if ($dress->separable == 1) {
                $update_dress = Dress::find($dress->id);
                $update_dress->dress_rental = $update_dress->dress_rental + 1; //จำนวนครั้งที่ถูกเช่า
                $update_dress->save();
            } elseif ($dress->separable == 2) {
                if ($orderdetail->shirtitems_id != null) {
                    //เช่าแค่เสื้อ
                    $update_shirt = Shirtitem::find($orderdetail->shirtitems_id);
                    $update_shirt->shirtitem_rental =  $update_shirt->shirtitem_rental  + 1;
                    $update_shirt->save();
                } elseif ($orderdetail->skirtitems_id != null) {
                    //เช่าแค่ผ้าถุง
                    $update_skirt = Skirtitem::find($orderdetail->skirtitems_id);
                    $update_skirt->skirtitem_rental =  $update_skirt->skirtitem_rental  + 1;
                    $update_skirt->save();
                } elseif ($orderdetail->skirtitems_id == null &&  $orderdetail->shirtitems_id == null) {
                    //เช่าทั้งชุด
                    // +1ชุด
                    $update_dress = Dress::find($dress->id);
                    $update_dress->dress_rental = $update_dress->dress_rental + 1; //จำนวนครั้งที่ถูกเช่า
                    $update_dress->save();

                    $shirt_id = Shirtitem::where('dress_id', $update_dress->id)->value('id');
                    $skirt_id = Skirtitem::where('dress_id', $update_dress->id)->value('id');
                    // +1เสื้อ
                    $update_shirt = Shirtitem::find($shirt_id);
                    $update_shirt->shirtitem_rental =  $update_shirt->shirtitem_rental  + 1;
                    $update_shirt->save();
                    // +1ผ้าถุง
                    $update_skirt = Skirtitem::find($skirt_id);
                    $update_skirt->skirtitem_rental =  $update_skirt->skirtitem_rental  + 1;
                    $update_skirt->save();
                }
            }

            if ($request->input('return_status') == "ส่งซัก") {
                $next_message_index = 'ส่งซักต่อไป';
                $text_for_reservation = "รอดำเนินการส่งซัก";
                // ตารางclean
                $create_clean = new Clean();
                $create_clean->reservation_id = $orderdetail->reservation_id;
                $create_clean->clean_status = "รอดำเนินการ";
                $create_clean->save();
                //ตารางstatus
                $create_status = new Orderdetailstatus();
                $create_status->status = "รอดำเนินการ";
                $create_status->clean_id = $create_clean->id;
                $create_status->save();
            } elseif ($request->input('return_status') == "ต้องซ่อมแซม") {
                $next_message_index = 'ส่งซ่อม';
                $text_for_reservation = "รอดำเนินการซ่อม";
                //ตารางreqpair 
                $create_repair = new Repair();
                $create_repair->reservation_id = $orderdetail->reservation_id;
                $create_repair->repair_description = $request->input('repair_details');
                $create_repair->repair_status = 'รอดำเนินการ';
                $create_repair->repair_type = $request->input('repair_type'); //10ทั้งชุด 20เสื้อ 30ผ้าถุง
                $create_repair->save();
                //ตารางstatus
                $create_status = new Orderdetailstatus();
                $create_status->status = "รอดำเนินการ";
                $create_status->repair_id = $create_repair->id;
                $create_status->save();
            }
            //ตารางreservation 
            $reservation = Reservation::find($orderdetail->reservation_id);
            $reservation->status = $text_for_reservation;
            $reservation->save();
            $message_session = 'ลูกค้าคืนชุดแล้ว และชุดจะถูก' . $request->input('return_status') . 'ต่อไป';
        }

        return redirect()->back()->with('success', $message_session);
    }

    




    //อัปเดตสถานะเช่าตัดชุด
    public function actionupdatestatusrentcut(Request $request, $id)
    {
        $orderdetail = Orderdetail::find($id);
        $status = $orderdetail->status_detail;
        if ($status == 'รอตัด') {
            //ตารางorderdetail
            $orderdetail->status_detail = "กำลังตัด";
            $orderdetail->save();
            //ตารางorderdetailstatus
            $create_status = new Orderdetailstatus();
            $create_status->order_detail_id = $id;
            $create_status->status = "กำลังตัด";
            $create_status->save();
        } elseif ($status == 'กำลังตัด') {
            //ตารางorderdetail
            $orderdetail->status_detail = "ตัดเสร็จแล้ว";
            $orderdetail->save();
            //ตารางorderdetailstatus
            $create_status = new Orderdetailstatus();
            $create_status->order_detail_id = $id;
            $create_status->status = "ตัดเสร็จแล้ว";
            $create_status->save();
        } elseif ($status == 'ตัดเสร็จแล้ว') {
            //ตารางorderdetail
            $orderdetail->status_detail = "กำลังเช่า";
            $orderdetail->save();
            //ตารางorderdetailstatus
            $create_status = new Orderdetailstatus();
            $create_status->order_detail_id = $id;
            $create_status->status = "กำลังเช่า";
            $create_status->save();

            if ($orderdetail->status_payment == 1) {
                //ตารางpaymentstatus
                $create_paymentstatus = new Paymentstatus();
                $create_paymentstatus->order_detail_id = $id;
                $create_paymentstatus->payment_status = 2;
                $create_paymentstatus->save();
                //ตารางorderdetail
                $orderdetail->status_payment = 2; //1จ่ายมัดจำ 2จ่ายเต็มจำนวน
                $orderdetail->save();
                //ตารางfinancial  ถ้ามันเป็น 1 แปลว่ามันจ่ายแค่มัดจำ   ถ้าคืนเครื่องประดับแล้วอะ มันจะต้องเอาเงินเข้าไปในบัญชีส่วนต่าง
                $create_price = new Financial();
                $create_price->order_detail_id = $id;
                $create_price->item_name = 'จ่ายส่วนที่เหลือ';
                $create_price->type_order = $orderdetail->type_order;
                $create_price->financial_income = ($orderdetail->price) - ($orderdetail->deposit);
                $create_price->financial_expenses = 0;
                $create_price->save();
            }
        } elseif ($status == "กำลังเช่า") {
            //ตารางfinancial
            if ($request->input('total_damage_insurance') > 0) {
                $create_total_damage_insurance = new Financial();
                $create_total_damage_insurance->order_detail_id = $id;
                $create_total_damage_insurance->item_name = "หักค่าปรับจากประกัน";
                $create_total_damage_insurance->type_order = $orderdetail->type_order;
                $create_total_damage_insurance->financial_income = $request->input('total_damage_insurance');
                $create_total_damage_insurance->financial_expenses = 0;
                $create_total_damage_insurance->save();
            }
            //ตารางorderdetail
            $orderdetail->status_detail = "คืนชุดแล้ว";
            $orderdetail->total_damage_insurance = $request->input('total_damage_insurance'); //ปรับจริง
            $orderdetail->cause_for_insurance = $request->input('cause_for_insurance'); //เหตุผลในการปรับ ; 
            $orderdetail->save();
            //ตารางorderdetailstatus
            $create_status = new Orderdetailstatus();
            $create_status->order_detail_id = $id;
            $create_status->status = "คืนชุดแล้ว";
            $create_status->save();
        }
        return redirect()->back()->with('success', 'อัพเดตสถานะสำเร็จ !');
    }
    //อัปเดตสถานะตัดชุด
    public function actionupdatestatuscutdress(Request $request, $id)
    {
        $orderdetail = Orderdetail::find($id);
        $status = $orderdetail->status_detail;

        if ($status == "รอดำเนินการตัด") {
            //ตารางorderdetail
            $orderdetail->status_detail = "เริ่มดำเนินการตัด";
            $orderdetail->save();
            //ตารางorderdetailstatus
            $create_status = new Orderdetailstatus();
            $create_status->order_detail_id = $id;
            $create_status->status = "เริ่มดำเนินการตัด";
            $create_status->save();
            $message_session = 'เริ่มดำเนินการตัด';
        } elseif ($status == 'เริ่มดำเนินการตัด') {
            //ตารางorderdetail
            $orderdetail->status_detail = "ตัดชุดเสร็จสิ้น";
            $orderdetail->save();
            //ตารางorderdetailstatus
            $create_status = new Orderdetailstatus();
            $create_status->order_detail_id = $id;
            $create_status->status = "ตัดชุดเสร็จสิ้น";
            $create_status->save();
            $message_session = 'ตัดชุดเสร็จสิ้น (รอส่งมอบ)';
        } elseif ($status == 'ตัดชุดเสร็จสิ้น') {
            //ตารางorderdetail
            $orderdetail->status_detail = "ส่งมอบชุดแล้ว";
            $orderdetail->save();
            //ตารางorderdetailstatus
            $order_detail_id_for_new = $id;
            $create_status = new Orderdetailstatus();
            $create_status->order_detail_id = $order_detail_id_for_new;
            $create_status->status = "ส่งมอบชุดแล้ว";
            $create_status->save();
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
            $message_session = 'ส่งมอบชุดสำเร็จ';
        } elseif ($status == 'แก้ไขชุด') {

            //ตารางorderdetail
            $orderdetail->status_detail = "แก้ไขชุดเสร็จสิ้น";
            $orderdetail->save();
            //ตารางorderdetailstatus
            $create_status = new Orderdetailstatus();
            $create_status->order_detail_id = $id;
            $create_status->status = "แก้ไขชุดเสร็จสิ้น";
            $create_status->save();


            // เพิ่มว่าใครเป็นคนแก้ไขปรับเพิ่มเติม
            $round_id = $request->input('round_id');
            $update_round = AdjustmentRound::find($round_id);
            $update_round->user_id = Auth::user()->id;
            $update_round->save();

            if ($request->input('adjust_id_')) {
                $adjust_id = $request->input('adjust_id_');
                $new_size = $request->input('new_size_');
                foreach ($adjust_id as $index => $id) {
                    $update_adjust_edit = Dressmeaadjustment::find($id);
                    $update_adjust_edit->new_size = $new_size[$index];
                    $update_adjust_edit->save();
                }
            }
            $message_session = 'แก้ไขชุดเสร็จสิ้น (รอส่งมอบ)';
        } elseif ($status == 'แก้ไขชุดเสร็จสิ้น') {
            //ตารางorderdetail
            $orderdetail->status_detail = "ส่งมอบชุดแล้ว";
            $orderdetail->save();
            //ตารางorderdetailstatus
            $order_detail_id_for_new = $id;
            $create_status = new Orderdetailstatus();
            $create_status->order_detail_id = $order_detail_id_for_new;
            $create_status->status = "ส่งมอบชุดแล้ว";
            $create_status->save();

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

            // ตารางdate อัปเดตวันที่รับชุดจริง
            $date_id = Date::where('order_detail_id', $id)
                ->orderBy('created_at', 'desc')
                ->value('id');
            $update_date = Date::find($date_id);
            $update_date->actua_pickup_date = now();
            $update_date->save();
            $message_session = 'ส่งมอบชุดสำเร็จ';
        }
        return redirect()->back()->with('success', $message_session);
    }

    public function actionupdatedatecutdress(Request $request, $id)
    {
        $update_date = Orderdetail::find($id);
        $update_date->pickup_date = $request->input('datepicker');
        $update_date->save();
        $create_date = new Date();
        $create_date->order_detail_id = $id;
        $create_date->pickup_date = $request->input('datepicker');
        $create_date->save();
        return redirect()->back()->with('success', 'อัพเดตโน๊ตสำเร็จ');
    }

    public function actionupdatenotecutdress(Request $request, $id)
    {
        $update_note = Orderdetail::find($id);
        $update_note->note = $request->input('note');
        $update_note->save();
        return redirect()->back()->with('อัพเดตสำเร็จ');
    }



    public function actionupdatestatusadjustdress(Request $request, $id)
    {

        $dress_id = $request->input('dress_id');
        $shirtitems_id = $request->input('shirtitems_id');
        $skirtitems_id = $request->input('skirtitems_id');
        $dress = Dress::find($dress_id);
        $shirt = Shirtitem::find($shirtitems_id);
        $skirt = Skirtitem::find($skirtitems_id);
        $order_detail_id = $request->input('order_detail_id');


        if ($shirtitems_id) {
            $count_adjust_shirt = Shirtitem::where('id', $shirtitems_id)->max('shirt_adjustment');
            $count_adjust_shirt = $count_adjust_shirt + 1;
            $dressmea_id = $request->input('dressmea_id_');
            $new_size = $request->input('new_size_');
            $dress_adjustment = $request->input('dress_adjustment_');
            foreach ($dressmea_id as $index => $dress_mea_id) {
                $dress_mea = Dressmea::find($dress_mea_id);
                if ($dress_mea->current_mea != $new_size[$index]) {
                    // สร้างตารางประวัติการแก้ dressmeasurementcutedits
                    $create_cut_edit = new Dressmeasurementcutedit();
                    $create_cut_edit->adjustment_id = $dress_adjustment[$index];
                    $create_cut_edit->order_detail_id = $order_detail_id;
                    $create_cut_edit->name = $dress_mea->mea_dress_name;
                    $create_cut_edit->dress_id = $dress_mea->dress_id;
                    $create_cut_edit->shirtitems_id = $dress_mea->shirtitems_id;
                    $create_cut_edit->skirtitems_id = $dress_mea->skirtitems_id;
                    $create_cut_edit->old_size = $dress_mea->current_mea;
                    $create_cut_edit->edit_new_size = $new_size[$index];
                    $create_cut_edit->adjustment_number = $count_adjust_shirt;
                    $create_cut_edit->save();
                }
            }
            // อย่าลืมอัพเดตค่าจำนวนครั้งที่ชุดนี้ถุกแก้ ตาราง shirt 
            $update_shirt_adjust_count = Shirtitem::find($shirtitems_id);
            $update_shirt_adjust_count->shirt_adjustment = $count_adjust_shirt;
            $update_shirt_adjust_count->save();
        } elseif ($skirtitems_id) {

            $count_adjust_skirt = Skirtitem::where('id', $skirtitems_id)->max('skirt_adjustment');
            $count_adjust_skirt = $count_adjust_skirt + 1;
            $dressmea_id = $request->input('dressmea_id_');
            $new_size = $request->input('new_size_');
            $dress_adjustment = $request->input('dress_adjustment_');
            foreach ($dressmea_id as $index => $dress_mea_id) {
                $dress_mea = Dressmea::find($dress_mea_id);
                if ($dress_mea->current_mea != $new_size[$index]) {
                    // สร้างตารางประวัติการแก้ dressmeasurementcutedits
                    $create_cut_edit = new Dressmeasurementcutedit();
                    $create_cut_edit->adjustment_id = $dress_adjustment[$index];
                    $create_cut_edit->order_detail_id = $order_detail_id;
                    $create_cut_edit->name = $dress_mea->mea_dress_name;
                    $create_cut_edit->dress_id = $dress_mea->dress_id;
                    $create_cut_edit->shirtitems_id = $dress_mea->shirtitems_id;
                    $create_cut_edit->skirtitems_id = $dress_mea->skirtitems_id;
                    $create_cut_edit->old_size = $dress_mea->current_mea;
                    $create_cut_edit->edit_new_size = $new_size[$index];
                    $create_cut_edit->adjustment_number = $count_adjust_skirt;
                    $create_cut_edit->save();
                }
            }
            // อย่าลืมอัพเดตค่าจำนวนครั้งที่ชุดนี้ถุกแก้ ตาราง skirt 
            $update_skirt_adjust_count = Skirtitem::find($skirtitems_id);
            $update_skirt_adjust_count->skirt_adjustment = $count_adjust_skirt;
            $update_skirt_adjust_count->save();
        } else {
            if ($dress->separable == 1) {
                $max = Dress::where('id', $dress->id)->max('dress_adjustment');
                $count_dress_adjustment = $max + 1;

                $dressmea_id = $request->input('dressmea_id_');
                $new_size = $request->input('new_size_');
                $dress_adjustment = $request->input('dress_adjustment_');
                foreach ($dressmea_id as $index => $dress_mea_id) {
                    $dress_mea = Dressmea::find($dress_mea_id);
                    if ($dress_mea->current_mea != $new_size[$index]) {
                        // สร้างตารางประวัติการแก้ dressmeasurementcutedits
                        $create_cut_edit = new Dressmeasurementcutedit();
                        $create_cut_edit->adjustment_id = $dress_adjustment[$index];
                        $create_cut_edit->order_detail_id = $order_detail_id;
                        $create_cut_edit->name = $dress_mea->mea_dress_name;
                        $create_cut_edit->dress_id = $dress_mea->dress_id;
                        $create_cut_edit->shirtitems_id = $dress_mea->shirtitems_id;
                        $create_cut_edit->skirtitems_id = $dress_mea->skirtitems_id;
                        $create_cut_edit->old_size = $dress_mea->current_mea;
                        $create_cut_edit->edit_new_size = $new_size[$index];
                        $create_cut_edit->adjustment_number = $count_dress_adjustment;
                        $create_cut_edit->save();
                    }
                }
                // อย่าลืมอัพเดตค่าจำนวนครั้งที่ชุดนี้ถุกแก้ ตาราง dress
                $dress->dress_adjustment = $count_dress_adjustment;
                $dress->save();
            } elseif ($dress->separable == 2) {
                // เช่าทั้งชุด แต่มันแยกได้
                $dressmea_id = $request->input('dressmea_id_');
                $new_size = $request->input('new_size_');
                $dress_adjustment = $request->input('dress_adjustment_');
                foreach ($dressmea_id as $index => $dress_mea_id) {
                    $dress_mea = Dressmea::find($dress_mea_id);
                    if ($dress_mea->current_mea != $new_size[$index]) {
                        $check_shirt = $dress_mea->shirtitems_id;
                        $check_skirt = $dress_mea->skirtitems_id;
                        if ($check_shirt != null) {
                            $max = Shirtitem::where('id', $check_shirt)->max('shirt_adjustment');
                            $max = $max + 1;
                            $max_for_shirt = $max;
                            // อย่าลืมอัพเดตค่าจำนวนครั้งที่ชุดนี้ถุกแก้ ตาราง shirt และ ตาราง skirt 
                            $update_shirt_id = Shirtitem::where('dress_id', $dress_id)->value('id');
                            $update_shirt = Shirtitem::find($update_shirt_id);
                            $update_shirt->shirt_adjustment = $max_for_shirt;
                            $update_shirt->save();
                        } elseif ($check_skirt != null) {
                            $max = Skirtitem::where('id', $check_skirt)->max('skirt_adjustment');
                            $max =  $max + 1;
                            $max_for_skirt = $max;
                            // อย่าลืมอัพเดตค่าจำนวนครั้งที่ชุดนี้ถุกแก้ ตาราง shirt และ ตาราง skirt 
                            $update_skirt_id = Skirtitem::where('dress_id', $dress_id)->value('id');
                            $update_skirt = Skirtitem::find($update_skirt_id);
                            $update_skirt->skirt_adjustment = $max_for_skirt;
                            $update_skirt->save();
                        }
                        // สร้างตารางประวัติการแก้ dressmeasurementcutedits
                        $create_cut_edit = new Dressmeasurementcutedit();
                        $create_cut_edit->adjustment_id = $dress_adjustment[$index];
                        $create_cut_edit->order_detail_id = $order_detail_id;
                        $create_cut_edit->name = $dress_mea->mea_dress_name;
                        $create_cut_edit->dress_id = $dress_mea->dress_id;
                        $create_cut_edit->shirtitems_id = $dress_mea->shirtitems_id;
                        $create_cut_edit->skirtitems_id = $dress_mea->skirtitems_id;
                        $create_cut_edit->old_size = $dress_mea->current_mea;
                        $create_cut_edit->edit_new_size = $new_size[$index];
                        $create_cut_edit->adjustment_number = $max;
                        $create_cut_edit->save();
                    }
                }
            }
        }

        //สุดท้ายแล้ว ต้องอัพเดตค่าในตาราง dressmea ให้เป็นปัจจุับน
        foreach ($dressmea_id as $index => $dress_mea_id) {
            $update_mea = Dressmea::find($dress_mea_id);
            $update_mea->current_mea = $new_size[$index];
            $update_mea->save();
        }


        return redirect()->back()->with('success', 'อัพเดตสถานะของแก้ไขการวัดสำเร็จ');
    }




    public function adddresstocart()
    {
        $dress = null;
        $dress_type = null;
        $typedress = Typedress::all();
        $avalable_rent_pass = null;
        $textcharacter = null;
        $text_startDate = null;
        $text_endDate = null;
        $text_totalDay = null;

        return view('employeerentdress.adddresstocart', compact('typedress', 'dress', 'dress_type', 'avalable_rent_pass', 'textcharacter', 'text_startDate', 'text_endDate', 'text_totalDay'));
    }





    public function addrentdresstocard()
    {
        $typedress = Typedress::all();
        $dress = Dress::where('type_dress_id', 111)->get();
        $start_date = '';
        $end_date = '';
        $character = '';
        $character = '';
        $dress_type = '';
        $dress_pass = null;
        $textcharacter = null;
        return view('employeerentdress.adddresstocart', compact('dress', 'start_date', 'end_date', 'character', 'typedress', 'dress_type', 'dress_pass', 'textcharacter'));
    }

    public function addrentdresstocardfilter(Request $request)
    {

        $character = $request->input('character');
        // 10 คือทั้งชุด 20เสื้อ 30 กระโปรงหรือผ้าถุง 
        if ($character == 10) {
            return $this->addrentdresstocardfilterdress($request);
        } elseif ($character == 20) {
            return $this->addrentdresstocardfiltershirt($request);
        } elseif ($character == 30) {
            return $this->addrentdresstocardfilterskirt($request);
        }
    }


    public function addrentdresstocardfilterdress(Request $request)
    {
        $typedress = Typedress::all();
        $dress_type = $request->input('dress_type');
        $character = $request->input('character');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $star_date_filter = Carbon::parse($request->input('start_date'));
        $end_date_filter = Carbon::parse($request->input('end_date'));

        $type_id = Typedress::where('type_dress_name', $dress_type)->value('id');
        $dress = Dress::where('type_dress_id', $type_id)->get();
        $fil_start_7 = $star_date_filter->copy()->subDays(7);
        $fil_start_1 = $star_date_filter->copy()->subDays(1);
        $fil_be_start = $star_date_filter->copy();
        $fil_be_end = $end_date_filter->copy();
        $fil_end_1 = $end_date_filter->copy()->addDays(1);
        $fil_end_7 = $end_date_filter->copy()->addDays(7);

        $list_pass_dress_id = [];
        foreach ($dress as $index) {
            // แยกเช่าไม่ได้
            if ($index->separable == 1) {
                $reservation = Reservation::where('dress_id', $index->id)
                    ->where('status_completed', 0)
                    ->get();
                $validate_pass = true;
                if ($reservation->isNotempty()) {
                    foreach ($reservation as $re) {
                        $re_start = Carbon::parse($re->start_date);
                        $re_end  = Carbon::parse($re->end_date);

                        if ($re_start->between($fil_start_7, $fil_start_1) || $re_end->between($fil_start_7, $fil_start_1)) {
                            $validate_pass = false;
                            break;
                        }
                        if ($re_start->between($fil_be_start, $fil_be_end) || $re_end->between($fil_be_start, $fil_be_end)) {
                            $validate_pass = false;
                        }
                        if ($re_start->between($fil_end_1, $fil_end_7) || $re_end->between($fil_end_1, $fil_end_7)) {
                            $validate_pass = false;
                        }
                    }
                }
                if ($validate_pass) {
                    $list_pass_dress_id[] = $index->id;
                }
            } elseif ($index->separable == 2) {
                // ชุดแยกได้

                $validate_pass = true;

                $fil_start_7 = $star_date_filter->copy()->subDays(7);
                $fil_start_1 = $star_date_filter->copy()->subDays(1);
                $fil_be_start = $star_date_filter->copy();
                $fil_be_end = $end_date_filter->copy();
                $fil_end_1 = $end_date_filter->copy()->addDays(1);
                $fil_end_7 = $end_date_filter->copy()->addDays(7);



                // เช็คแค่เฉพาะเช่าทั้งชุดก่อน
                $reservation_dress = Reservation::where('dress_id', $index->id)
                    ->whereNull('shirtitems_id')
                    ->whereNull('skirtitems_id')
                    ->where('status_completed', 0)
                    ->get();
                if ($reservation_dress->isNotempty()) {
                    foreach ($reservation_dress as $re) {
                        $re_start = Carbon::parse($re->start_date);
                        $re_end  = Carbon::parse($re->end_date);

                        if ($re_start->between($fil_start_7, $fil_start_1) || $re_end->between($fil_start_7, $fil_start_1)) {
                            $validate_pass = false;
                            break;
                        }
                        if ($re_start->between($fil_be_start, $fil_be_end) || $re_end->between($fil_be_start, $fil_be_end)) {
                            $validate_pass = false;
                        }
                        if ($re_start->between($fil_end_1, $fil_end_7) || $re_end->between($fil_end_1, $fil_end_7)) {
                            $validate_pass = false;
                        }
                    }
                }


                //เช็คเฉพาะเสื้อ
                $shirt_id = Shirtitem::where('dress_id', $index->id)->value('id');
                $reservation_shirt = Reservation::where('shirtitems_id', $shirt_id)
                    ->where('status_completed', 0)
                    ->get();
                if ($reservation_shirt->isNotempty()) {
                    foreach ($reservation_shirt as $re) {
                        $re_start = Carbon::parse($re->start_date);
                        $re_end  = Carbon::parse($re->end_date);

                        if ($re_start->between($fil_start_7, $fil_start_1) || $re_end->between($fil_start_7, $fil_start_1)) {
                            $validate_pass = false;
                            break;
                        }
                        if ($re_start->between($fil_be_start, $fil_be_end) || $re_end->between($fil_be_start, $fil_be_end)) {
                            $validate_pass = false;
                        }
                        if ($re_start->between($fil_end_1, $fil_end_7) || $re_end->between($fil_end_1, $fil_end_7)) {
                            $validate_pass = false;
                        }
                    }
                }

                //เช็คเฉพาะผ้าถุง
                $skirt_id = Skirtitem::where('dress_id', $index->id)->value('id');
                $reservation_skirt = Reservation::where('skirtitems_id', $skirt_id)
                    ->where('status_completed', 0)
                    ->get();
                if ($reservation_skirt->isNotempty()) {
                    foreach ($reservation_skirt as $re) {
                        $re_start = Carbon::parse($re->start_date);
                        $re_end  = Carbon::parse($re->end_date);

                        if ($re_start->between($fil_start_7, $fil_start_1) || $re_end->between($fil_start_7, $fil_start_1)) {
                            $validate_pass = false;
                            break;
                        }
                        if ($re_start->between($fil_be_start, $fil_be_end) || $re_end->between($fil_be_start, $fil_be_end)) {
                            $validate_pass = false;
                        }
                        if ($re_start->between($fil_end_1, $fil_end_7) || $re_end->between($fil_end_1, $fil_end_7)) {
                            $validate_pass = false;
                        }
                    }
                }


                if ($validate_pass) {
                    $list_pass_dress_id[] = $index->id;
                }
            }
        }

        $dress_pass = Dress::whereIn('id', $list_pass_dress_id)->get();
        if ($character === "10") {
            $textcharacter = "ทั้งชุด";
        } elseif ($character === "20") {
            $textcharacter = "เสื้อ";
        } elseif ($character === "30") {
            $textcharacter = 'กระโปรง/ผ้าถุง';
        }
        return view('employeerentdress.adddresstocart', compact('dress', 'start_date', 'end_date', 'character', 'typedress', 'dress_type', 'dress_pass', 'textcharacter', 'start_date', 'end_date'));
    }






    public function addrentdresstocardfiltershirt(Request $request)
    {
        $typedress = Typedress::all();
        $dress_type = $request->input('dress_type');
        $character = $request->input('character');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $star_date_filter = Carbon::parse($request->input('start_date'));
        $end_date_filter = Carbon::parse($request->input('end_date'));

        $type_id = Typedress::where('type_dress_name', $dress_type)->value('id');
        $dress = Dress::where('type_dress_id', $type_id)
            ->where('separable', 2)
            ->get();
        $fil_start_7 = $star_date_filter->copy()->subDays(7);
        $fil_start_1 = $star_date_filter->copy()->subDays(1);
        $fil_be_start = $star_date_filter->copy();
        $fil_be_end = $end_date_filter->copy();
        $fil_end_1 = $end_date_filter->copy()->addDays(1);
        $fil_end_7 = $end_date_filter->copy()->addDays(7);

        $list_pass_dress_id = [];

        foreach ($dress as $index) {

            $validate_pass = true;
            //เช็คเฉพาะเสื้อ
            $shirt_id = Shirtitem::where('dress_id', $index->id)->value('id');
            $reservation_shirt = Reservation::where('shirtitems_id', $shirt_id)
                ->where('status_completed', 0)
                ->get();
            if ($reservation_shirt->isNotempty()) {
                foreach ($reservation_shirt as $re) {
                    $re_start = Carbon::parse($re->start_date);
                    $re_end  = Carbon::parse($re->end_date);

                    if ($re_start->between($fil_start_7, $fil_start_1) || $re_end->between($fil_start_7, $fil_start_1)) {
                        $validate_pass = false;
                        break;
                    }
                    if ($re_start->between($fil_be_start, $fil_be_end) || $re_end->between($fil_be_start, $fil_be_end)) {
                        $validate_pass = false;
                    }
                    if ($re_start->between($fil_end_1, $fil_end_7) || $re_end->between($fil_end_1, $fil_end_7)) {
                        $validate_pass = false;
                    }
                }
            }
            // เช็คแค่เฉพาะเช่าทั้งชุด
            $reservation_dress = Reservation::where('dress_id', $index->id)
                ->whereNull('shirtitems_id')
                ->whereNull('skirtitems_id')
                ->where('status_completed', 0)
                ->get();
            if ($reservation_dress->isNotempty()) {
                foreach ($reservation_dress as $re) {
                    $re_start = Carbon::parse($re->start_date);
                    $re_end  = Carbon::parse($re->end_date);

                    if ($re_start->between($fil_start_7, $fil_start_1) || $re_end->between($fil_start_7, $fil_start_1)) {
                        $validate_pass = false;
                        break;
                    }
                    if ($re_start->between($fil_be_start, $fil_be_end) || $re_end->between($fil_be_start, $fil_be_end)) {
                        $validate_pass = false;
                    }
                    if ($re_start->between($fil_end_1, $fil_end_7) || $re_end->between($fil_end_1, $fil_end_7)) {
                        $validate_pass = false;
                    }
                }
            }

            if ($validate_pass) {
                $list_pass_dress_id[] = $index->id;
            }
        }
        $dress_pass = Dress::whereIn('id', $list_pass_dress_id)->get();
        if ($character === "10") {
            $textcharacter = "ทั้งชุด";
        } elseif ($character === "20") {
            $textcharacter = "เสื้อ";
        } elseif ($character === "30") {
            $textcharacter = 'กระโปรง/ผ้าถุง';
        }
        return view('employeerentdress.adddresstocart', compact('dress', 'start_date', 'end_date', 'character', 'typedress', 'dress_type', 'dress_pass', 'textcharacter', 'start_date', 'end_date'));
    }


    public function addrentdresstocardfilterskirt(Request $request)
    {
        $typedress = Typedress::all();
        $dress_type = $request->input('dress_type');
        $character = $request->input('character');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $star_date_filter = Carbon::parse($request->input('start_date'));
        $end_date_filter = Carbon::parse($request->input('end_date'));

        $type_id = Typedress::where('type_dress_name', $dress_type)->value('id');
        $dress = Dress::where('type_dress_id', $type_id)
            ->where('separable', 2)
            ->get();
        $fil_start_7 = $star_date_filter->copy()->subDays(7);
        $fil_start_1 = $star_date_filter->copy()->subDays(1);
        $fil_be_start = $star_date_filter->copy();
        $fil_be_end = $end_date_filter->copy();
        $fil_end_1 = $end_date_filter->copy()->addDays(1);
        $fil_end_7 = $end_date_filter->copy()->addDays(7);
        $list_pass_dress_id = [];

        foreach ($dress as $index) {
            $validate_pass = true;
            //เช็คเฉพาะผ้าถุง
            $skirt_id = Skirtitem::where('dress_id', $index->id)->value('id');
            $reservation_skirt = Reservation::where('skirtitems_id', $skirt_id)
                ->where('status_completed', 0)
                ->get();
            if ($reservation_skirt->isNotempty()) {
                foreach ($reservation_skirt as $re) {
                    $re_start = Carbon::parse($re->start_date);
                    $re_end  = Carbon::parse($re->end_date);

                    if ($re_start->between($fil_start_7, $fil_start_1) || $re_end->between($fil_start_7, $fil_start_1)) {
                        $validate_pass = false;
                        break;
                    }
                    if ($re_start->between($fil_be_start, $fil_be_end) || $re_end->between($fil_be_start, $fil_be_end)) {
                        $validate_pass = false;
                    }
                    if ($re_start->between($fil_end_1, $fil_end_7) || $re_end->between($fil_end_1, $fil_end_7)) {
                        $validate_pass = false;
                    }
                }
            }
            // เช็คแค่เฉพาะเช่าทั้งชุด
            $reservation_dress = Reservation::where('dress_id', $index->id)
                ->whereNull('shirtitems_id')
                ->whereNull('skirtitems_id')
                ->where('status_completed', 0)
                ->get();
            if ($reservation_dress->isNotempty()) {
                foreach ($reservation_dress as $re) {
                    $re_start = Carbon::parse($re->start_date);
                    $re_end  = Carbon::parse($re->end_date);

                    if ($re_start->between($fil_start_7, $fil_start_1) || $re_end->between($fil_start_7, $fil_start_1)) {
                        $validate_pass = false;
                        break;
                    }
                    if ($re_start->between($fil_be_start, $fil_be_end) || $re_end->between($fil_be_start, $fil_be_end)) {
                        $validate_pass = false;
                    }
                    if ($re_start->between($fil_end_1, $fil_end_7) || $re_end->between($fil_end_1, $fil_end_7)) {
                        $validate_pass = false;
                    }
                }
            }
            if ($validate_pass) {
                $list_pass_dress_id[] = $index->id;
            }
        }
        $dress_pass = Dress::whereIn('id', $list_pass_dress_id)->get();
        if ($character === "10") {
            $textcharacter = "ทั้งชุด";
        } elseif ($character === "20") {
            $textcharacter = "เสื้อ";
        } elseif ($character === "30") {
            $textcharacter = 'กระโปรง/ผ้าถุง';
        }
        return view('employeerentdress.adddresstocart', compact('dress', 'start_date', 'end_date', 'character', 'typedress', 'dress_type', 'dress_pass', 'textcharacter', 'start_date', 'end_date'));
    }













    //เพิ่มชุด/เสื้อ/ผ้าถุง ลงบนตะกร้า 
    public function addtocart(Request $request)
    {
        $textcharacter = $request->input('textcharacter');
        $dress_id = $request->input('dress_id');
        $pickupdate = $request->input('pickupdate');
        $returndate = $request->input('returndate');

        $shirt_id = $request->input('shirt_id');
        $skirt_id = $request->input('skirt_id');
        $employee_id = Auth::user()->id;

        // $dress_mea = Dressmea::where('dress_id', $dress_id)->get();

        if ($textcharacter === "ทั้งชุด") {
            $data_dress = Dress::find($dress_id);  //สำหรับใช้ดึงข้อมูลต่างๆของชุด
            $type_dress_name = Typedress::where('id', $data_dress->type_dress_id)->value('type_dress_name');

            //ตารางreservation 
            $reservation = new Reservation();
            $reservation->dress_id = $dress_id;
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
                $create_order_detail->dress_id = $dress_id;
                $create_order_detail->order_id = $update_order->id;

                $create_order_detail->reservation_id = $reservation->id;


                $create_order_detail->type_dress = $type_dress_name;
                $create_order_detail->type_order = 2;
                $create_order_detail->amount = 1;
                $create_order_detail->price = $data_dress->dress_price;
                $create_order_detail->deposit = $data_dress->dress_deposit;
                $create_order_detail->damage_insurance = $data_dress->damage_insurance;
                $create_order_detail->save();


                // ตาราdate
                $create_date = new Date();
                $create_date->order_detail_id = $create_order_detail->id;
                $create_date->pickup_date = $pickupdate;
                $create_date->return_date = $returndate;
                $create_date->save();


                $dress_mea = Dressmea::where('dress_id', $dress_id)->get();
                foreach ($dress_mea as $data) {
                    //ตารางdressmeaadjustments
                    $create_dress_mea_adjust = new Dressmeaadjustment();
                    $create_dress_mea_adjust->dressmea_id = $data->id;
                    $create_dress_mea_adjust->order_detail_id = $create_order_detail->id;
                    $create_dress_mea_adjust->new_size = $data->current_mea;
                    $create_dress_mea_adjust->status = 'ไม่มีการแก้ไข';
                    $create_dress_mea_adjust->save();
                }
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
                $create_order_detail->dress_id = $dress_id;
                $create_order_detail->order_id = $create_order->id;
                // $create_order_detail->employee_id = $employee_id;
                $create_order_detail->reservation_id = $reservation->id;

                $create_order_detail->type_dress = $type_dress_name;
                $create_order_detail->type_order = 2;
                $create_order_detail->amount = 1;
                $create_order_detail->price = $data_dress->dress_price;
                $create_order_detail->deposit = $data_dress->dress_deposit;
                $create_order_detail->damage_insurance = $data_dress->damage_insurance;
                $create_order_detail->save();


                // ตาราdate
                $create_date = new Date();
                $create_date->order_detail_id = $create_order_detail->id;
                $create_date->pickup_date = $pickupdate;
                $create_date->return_date = $returndate;
                $create_date->save();




                $dress_mea = Dressmea::where('dress_id', $dress_id)->get();
                foreach ($dress_mea as $data) {
                    //ตารางdressmeaadjustments
                    $create_dress_mea_adjust = new Dressmeaadjustment();
                    $create_dress_mea_adjust->dressmea_id = $data->id;
                    $create_dress_mea_adjust->order_detail_id = $create_order_detail->id;
                    $create_dress_mea_adjust->new_size = $data->current_mea;
                    $create_dress_mea_adjust->status = 'ไม่มีการแก้ไข';
                    $create_dress_mea_adjust->save();
                }
            }
        } elseif ($textcharacter === "เสื้อ") {
            $data_dress = Dress::find($dress_id);  //สำหรับใช้ดึงข้อมูลต่างๆของชุด
            $type_dress_name = Typedress::where('id', $data_dress->type_dress_id)->value('type_dress_name');
            $data_shirt = Shirtitem::where('dress_id', $dress_id)->first();

            //ตารางreservation 
            $reservation = new Reservation();
            $reservation->dress_id = $dress_id;
            $reservation->shirtitems_id = $shirt_id;
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
                $create_order_detail->dress_id = $dress_id;
                $create_order_detail->shirtitems_id  = $shirt_id;
                $create_order_detail->order_id = $update_order->id;
                // $create_order_detail->employee_id = $employee_id;
                $create_order_detail->reservation_id  = $reservation->id;


                $create_order_detail->type_dress = $type_dress_name;
                $create_order_detail->type_order = 2;
                $create_order_detail->amount = 1;
                $create_order_detail->price = $data_shirt->shirtitem_price;
                $create_order_detail->deposit = $data_shirt->shirtitem_deposit;
                $create_order_detail->damage_insurance = $data_shirt->shirt_damage_insurance;
                $create_order_detail->save();


                // ตาราdate
                $create_date = new Date();
                $create_date->order_detail_id = $create_order_detail->id;
                $create_date->pickup_date = $pickupdate;
                $create_date->return_date = $returndate;
                $create_date->save();



                $dress_mea = Dressmea::where('dress_id', $dress_id)
                    ->whereNotNull('shirtitems_id')
                    ->get();
                foreach ($dress_mea as $data) {
                    //ตารางdressmeaadjustments
                    $create_dress_mea_adjust = new Dressmeaadjustment();
                    $create_dress_mea_adjust->dressmea_id = $data->id;
                    $create_dress_mea_adjust->order_detail_id = $create_order_detail->id;
                    $create_dress_mea_adjust->new_size = $data->current_mea;
                    $create_dress_mea_adjust->status = 'ไม่มีการแก้ไข';
                    $create_dress_mea_adjust->save();
                }
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
                $create_order_detail->dress_id = $dress_id;
                $create_order_detail->shirtitems_id  = $shirt_id;
                $create_order_detail->order_id = $create_order->id;
                // $create_order_detail->employee_id = $employee_id;
                $create_order_detail->reservation_id  = $reservation->id;

                $create_order_detail->type_dress = $type_dress_name;
                $create_order_detail->type_order = 2;
                $create_order_detail->amount = 1;
                $create_order_detail->price = $data_shirt->shirtitem_price;
                $create_order_detail->deposit = $data_shirt->shirtitem_deposit;
                $create_order_detail->damage_insurance = $data_shirt->shirt_damage_insurance;
                $create_order_detail->save();
                // ตาราdate
                $create_date = new Date();
                $create_date->order_detail_id = $create_order_detail->id;
                $create_date->pickup_date = $pickupdate;
                $create_date->return_date = $returndate;
                $create_date->save();


                $dress_mea = Dressmea::where('dress_id', $dress_id)
                    ->whereNotNull('shirtitems_id')
                    ->get();
                foreach ($dress_mea as $data) {
                    //ตารางdressmeaadjustments
                    $create_dress_mea_adjust = new Dressmeaadjustment();
                    $create_dress_mea_adjust->dressmea_id = $data->id;
                    $create_dress_mea_adjust->order_detail_id = $create_order_detail->id;
                    $create_dress_mea_adjust->new_size = $data->current_mea;
                    $create_dress_mea_adjust->status = 'ไม่มีการแก้ไข';
                    $create_dress_mea_adjust->save();
                }
            }
        } elseif ($textcharacter === "กระโปรง/ผ้าถุง") {
            $data_dress = Dress::find($dress_id);  //สำหรับใช้ดึงข้อมูลต่างๆของชุด
            $type_dress_name = Typedress::where('id', $data_dress->type_dress_id)->value('type_dress_name');
            $data_skirt = Skirtitem::where('dress_id', $dress_id)->first();



            //ตารางreservation 
            $reservation = new Reservation();
            $reservation->dress_id = $dress_id;
            $reservation->skirtitems_id = $skirt_id;
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
                $create_order_detail->dress_id = $dress_id;
                $create_order_detail->skirtitems_id  = $skirt_id;
                $create_order_detail->order_id = $update_order->id;
                // $create_order_detail->employee_id = $employee_id;
                $create_order_detail->reservation_id = $reservation->id;

                $create_order_detail->type_dress = $type_dress_name;
                $create_order_detail->type_order = 2;
                $create_order_detail->amount = 1;
                $create_order_detail->price = $data_skirt->skirtitem_price;
                $create_order_detail->deposit = $data_skirt->skirtitem_deposit;
                $create_order_detail->damage_insurance = $data_skirt->skirt_damage_insurance;
                $create_order_detail->save();

                // ตาราdate
                $create_date = new Date();
                $create_date->order_detail_id = $create_order_detail->id;
                $create_date->pickup_date = $pickupdate;
                $create_date->return_date = $returndate;
                $create_date->save();

                $dress_mea = Dressmea::where('dress_id', $dress_id)
                    ->whereNotNull('skirtitems_id')
                    ->get();
                foreach ($dress_mea as $data) {
                    //ตารางdressmeaadjustments
                    $create_dress_mea_adjust = new Dressmeaadjustment();
                    $create_dress_mea_adjust->dressmea_id = $data->id;
                    $create_dress_mea_adjust->order_detail_id = $create_order_detail->id;
                    $create_dress_mea_adjust->new_size = $data->current_mea;
                    $create_dress_mea_adjust->status = 'ไม่มีการแก้ไข';
                    $create_dress_mea_adjust->save();
                }
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
                $create_order_detail->dress_id = $dress_id;
                $create_order_detail->skirtitems_id  = $skirt_id;
                $create_order_detail->order_id = $create_order->id;
                // $create_order_detail->employee_id = $employee_id;
                $create_order_detail->reservation_id  = $reservation->id;

                $create_order_detail->type_dress = $type_dress_name;
                $create_order_detail->type_order = 2;
                $create_order_detail->amount = 1;
                $create_order_detail->price = $data_skirt->skirtitem_price;
                $create_order_detail->deposit = $data_skirt->skirtitem_deposit;
                $create_order_detail->damage_insurance = $data_skirt->skirt_damage_insurance;
                $create_order_detail->save();


                // ตาราdate
                $create_date = new Date();
                $create_date->order_detail_id = $create_order_detail->id;
                $create_date->pickup_date = $pickupdate;
                $create_date->return_date = $returndate;
                $create_date->save();



                $dress_mea = Dressmea::where('dress_id', $dress_id)
                    ->whereNotNull('skirtitems_id')
                    ->get();
                foreach ($dress_mea as $data) {
                    //ตารางdressmeaadjustments
                    $create_dress_mea_adjust = new Dressmeaadjustment();
                    $create_dress_mea_adjust->dressmea_id = $data->id;
                    $create_dress_mea_adjust->order_detail_id = $create_order_detail->id;
                    $create_dress_mea_adjust->new_size = $data->current_mea;
                    $create_dress_mea_adjust->status = 'ไม่มีการแก้ไข';
                    $create_dress_mea_adjust->save();
                }
            }
        }
        return redirect()->back()->with('success', 'เพิ่มลงตะกร้าสำเร็จ');
    }
}
