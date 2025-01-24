<?php

namespace App\Http\Controllers;

use App\Models\AdditionalChange;
use App\Models\Date;
use App\Models\Jewelry;
use App\Models\Jewelryset;
use App\Models\Jewelrysetitem;
use App\Models\Order;
use App\Models\Orderdetail;
use App\Models\Orderdetailstatus;
use App\Models\Paymentstatus;
use App\Models\Repair;
use App\Models\Reservation;
use App\Models\ChargeJewelry;
use App\Models\Reservationfilters;
use App\Models\Typejewelry;
use App\Models\Receipt;
use App\Models\ReceiptReturn;
use App\Models\Decoration;
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

        $fil_start_7 = $start_date_fil->copy()->subDays(7); //ถอยหลังไป 7 วัน
        $fil_end_7 = $end_date_fil->copy()->addDays(7); // บวกเพิ่มไป 7 วัน 

        $jew_set = Jewelryset::where('set_status', '!=', 'ยุติการให้เช่า')->get();


        $list_pass_set_id = [];

        foreach ($jew_set as $value) {
            //  รอบแรก วน 9 ก่อน 


            $jew_id_in_set = Jewelrysetitem::where('jewelry_set_id', $value->id)->get(); // 2  แถว

            // 114 122 
            $validate_pass = true;
            foreach ($jew_id_in_set as $jew) {
                // วน 122
                $check_jew_in_fil = Reservationfilters::where('jewelry_id', $jew->jewelry_id)
                    ->where('status_completed', 0)
                    ->get();  // 
                // สมมุดว่า มี jewelry_id ที่ 114 ทั้งหมด 3 แถว                    
                foreach ($check_jew_in_fil as $item) {
                    $start_re = Carbon::parse($item->start_date);
                    $end_re = Carbon::parse($item->end_date);
                    if ($start_re->between($fil_start_7, $fil_end_7) || $end_re->between($fil_start_7, $fil_end_7)) {
                        $validate_pass = false;
                        break;
                    }
                }
            }
            // ถ้ามันผ่านทุกเงื่อนไขแล้วอะ แปลว่ามันผ่านทุกเงื่อนไข 
            if ($validate_pass) {
                $list_pass_set_id[] = $value->id;
            }
        }


        $list_pass_set_id = array_unique($list_pass_set_id);
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

        $jewel = Jewelry::where('type_jewelry_id', $jewelry_type)
            ->whereNotIn('jewelry_status', ['สูญหาย', 'ยุติการให้เช่า'])
            ->get();
        $list_pass_id = [];

        $fil_start_7 = $start_date_fil->copy()->subDays(7); // ถอยหลังกลับไป 7 วัน 
        $fil_end_7 = $end_date_fil->copy()->addDays(7); // บวกเพิ่มขึ้นไป 7 วัน 

        foreach ($jewel as $jew) {
            $reservation = Reservationfilters::where('jewelry_id', $jew->id)
                ->where('status_completed', 0)
                ->get();
            $validate_pass = true;
            foreach ($reservation as $re) {
                $start_re = Carbon::parse($re->start_date);
                $end_re = Carbon::parse($re->end_date);
                if ($start_re->between($fil_start_7, $fil_end_7) || $end_re->between($fil_start_7, $fil_end_7)) {
                    $validate_pass = false;
                    break;
                }
            }

            // หลังจากที่มันเช็คทั้งหมดเสร็จแล้ว ถ้า $validate_pass เป็น true หมายความว่า มันผ่านเงื่อนไข
            if ($validate_pass) {
                $list_pass_id[] = $jew->id;
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

        // ตารางreservationfilter
        $reservationfilter = new Reservationfilters();
        $reservationfilter->jewelry_id = $jewelry_id;
        $reservationfilter->start_date = $pickupdate;
        $reservationfilter->end_date = $returndate;
        $reservationfilter->status = 'อยู่ในตะกร้า';
        $reservationfilter->status_completed = 0; //0 คือ ยังไม่เสด 1 คือเสร็จแล้ว
        $reservationfilter->reservation_id = $reservation->id;
        $reservationfilter->save();


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
            $create_order->order_status = 0;
            $create_order->type_order = 2; //1.คือตัด 2.เช่า 3.เช่าตัด
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
    public function addrentjewelrytocardaddtocardset(Request $request)
    {


        $jewset_id = $request->input('jewset_id');
        $pickupdate = $request->input('pickupdate');
        $returndate = $request->input('returndate');
        $jewset_price = $request->input('jewset_price');
        $jewset_deposit = $jewset_price * 0.3;
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


        $list_for_jewelry_set = Jewelrysetitem::where('jewelry_set_id', $jewset_id)->get();
        foreach ($list_for_jewelry_set as $item) {
            // ตารางreservationfilter
            $reservationfilter = new Reservationfilters();
            $reservationfilter->jewelry_id = $item->jewelry_id;
            $reservationfilter->jewelry_set_id = $jewset_id;
            $reservationfilter->start_date = $pickupdate;
            $reservationfilter->end_date = $returndate;
            $reservationfilter->status = 'อยู่ในตะกร้า';
            $reservationfilter->status_completed = 0; //0 คือ ยังไม่เสด 1 คือเสร็จแล้ว
            $reservationfilter->reservation_id = $reservation->id;
            $reservationfilter->save();
        }


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
            $create_order->type_order = 2; //1.คือตัด 2.เช่า 3.เช่าตัด
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


    public function actionupdatereceivejewelry(Request $request, $id)
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

        //ตารางreservation 
        $reservation = Reservation::find($orderdetail->reservation_id);
        $reservation->status = 'กำลังเช่า';
        $reservation->save();


        if ($reservation->jewelry_id) {
            $find_re_filter = Reservationfilters::where('reservation_id', $reservation->id)->first();
            $update_re_filter = Reservationfilters::find($find_re_filter->id);
            $update_re_filter->status = 'กำลังเช่า';
            $update_re_filter->save();

            $update_status_jewelry = Jewelry::find($reservation->jewelry_id);
            $update_status_jewelry->jewelry_status = 'กำลังถูกเช่า';
            $update_status_jewelry->save();
        } elseif ($reservation->jewelry_set_id) {

            $find_re_filter = Reservationfilters::where('reservation_id', $reservation->id)->get();
            foreach ($find_re_filter as $item) {
                $update_re_filter = Reservationfilters::find($item->id);
                $update_re_filter->status = 'กำลังเช่า';
                $update_re_filter->save();
            }

            $jew_item_total = Jewelrysetitem::where('jewelry_set_id', $reservation->jewelry_set_id)->get();
            foreach ($jew_item_total as $item) {
                $update_status_jew = Jewelry::find($item->jewelry_id);
                $update_status_jew->jewelry_status = 'กำลังถูกเช่า';
                $update_status_jew->save();
            }
        }




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

        // ตรวจสอบว่าใน order นี้ มีทั้งหมดกี่รายการ
        $count_index = 0;
        $data_orderdetail = Orderdetail::where('order_id', $orderdetail->order_id)
            ->whereNotIn('status_detail', ['ยกเลิกโดยทางร้าน', 'ยกเลิกโดยลูกค้า'])
            ->get();
        foreach ($data_orderdetail as $item) {
            if ($item->status_detail == 'กำลังเช่า') {
                $count_index += 1;
            }
        }

        // ถ้ามันตรวจสอบแล้วพบว่าทุกรายการ มันเป็น กำลังเช่า ทั้งหมดแล้ว แปลว่า จะต้องทำการสร้างใบเสร็จอัตโนมัติเลย
        if ($count_index == $data_orderdetail->count()) {
            // สร้างใบเสร็จรวม

            $price_total_decoration = 0;
            foreach ($data_orderdetail as $index) {
                $decoration_receipt = Decoration::where('order_detail_id', $index->id)->get();
                foreach ($decoration_receipt as $item) {
                    $price_total_decoration += $item->decoration_price;
                }
            }

            $total_price_receipt = 0;
            foreach ($data_orderdetail as $item) {
                $check_payment = Paymentstatus::where('order_detail_id', $item->id)
                    ->where('payment_status', 1)
                    ->exists();
                if ($check_payment) {
                    $total_price_receipt +=  ($item->price - $item->deposit) + $item->damage_insurance;
                }
            }
            $ceate_receipt = new Receipt();
            $ceate_receipt->order_id = $orderdetail->order_id;
            $ceate_receipt->employee_id = Auth::user()->id;
            $ceate_receipt->receipt_type = 2;
            $ceate_receipt->total_price = $total_price_receipt + $price_total_decoration;
            $ceate_receipt->save();
        }



        // สร้างใบเสร็จ
        // $check_payment_code_two_receipt = Paymentstatus::where('order_detail_id', $orderdetail->id)
        //     ->where('payment_status', 1)
        //     ->exists();
        // if ($check_payment_code_two_receipt) {
        //     $total_price_receipt = ($orderdetail->price - $orderdetail->deposit) + $orderdetail->damage_insurance;
        // } else {
        //     $total_price_receipt = 0;
        // }
        // $ceate_receipt = new Receipt();
        // $ceate_receipt->order_id = $orderdetail->order_id;
        // $ceate_receipt->order_detail_id = $orderdetail->id;
        // $ceate_receipt->receipt_type = 2;
        // $ceate_receipt->total_price = $total_price_receipt;
        // $ceate_receipt->save();




        return redirect()->back()->with('success', $message_session);
    }







    public function updatereturnjewelry(Request $request, $id)
    {

        $orderdetail = Orderdetail::find($id);
        $datareservation = Reservation::find($orderdetail->reservation_id);
        $check_for_set_or_item = $request->input('check_for_set_or_item');

        $total_damage_insurance = $request->input('total_damage_insurance'); //1.ปรับเงินประกันจริงๆ 
        $late_return_fee = $request->input('late_return_fee'); //2.ค่าปรับส่งคืนชุดล่าช้า:
        $late_chart = $request->input('late_chart'); //3.ค่าธรรมเนียมขยายระยะเวลาเช่า:
        if ($total_damage_insurance > 0) {
            $create_additional = new AdditionalChange();
            $create_additional->order_detail_id = $id;
            $create_additional->charge_type = 1;
            $create_additional->amount = $total_damage_insurance;
            $create_additional->save();

            if ($check_for_set_or_item == 'item') {
                $add_char_jew = new ChargeJewelry();
                $add_char_jew->additional_charge_id = $create_additional->id;
                $add_char_jew->jewelrys_id = $datareservation->jewelry_id;
                $add_char_jew->save();
            } elseif ($check_for_set_or_item == 'set') {
                $refil_id = $request->input('refil_id_');
                $action_set = $request->input('action_set_');
                $refil_jewelry_id = $request->input('refil_jewelry_id_');
                foreach ($refil_id as $index => $re) {

                    if ($action_set[$index] == 'repair') {
                        $add_char_jew = new ChargeJewelry();
                        $add_char_jew->additional_charge_id = $create_additional->id;
                        $add_char_jew->jewelry_id = $refil_jewelry_id[$index];
                        $add_char_jew->save();
                    }
                }
            }
        }
        if ($late_return_fee > 0) {
            $create_additionals = new AdditionalChange();
            $create_additionals->order_detail_id = $id;
            $create_additionals->charge_type = 2;
            $create_additionals->amount = $late_return_fee;
            $create_additionals->save();
        }
        if ($late_chart) {
            $create_additionalw = new AdditionalChange();
            $create_additionalw->order_detail_id = $id;
            $create_additionalw->charge_type = 3;
            $create_additionalw->amount = $late_chart;
            $create_additionalw->save();
        }

        // เช่าเป็นชิ้น
        if ($check_for_set_or_item == 'item') {
            $actionreturnitem = $request->input('actionreturnitem');

            if ($actionreturnitem == 'cleanitem') {
                // dd('ส่งทำความสะอาด');

                //ตารางorderdetail
                $orderdetail->status_detail = "คืนเครื่องประดับแล้ว";
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
                $create_status->status = "คืนเครื่องประดับแล้ว";
                $create_status->save();

                //ตารางreservation
                $reservation = Reservation::find($orderdetail->reservation_id);
                $reservation->status = 'คืนเครื่องประดับแล้ว';
                $reservation->status_completed = 1; //เสร็จแล้ว
                $reservation->save();

                // ตารางหลอก
                $find_re_filter = Reservationfilters::where('reservation_id', $reservation->id)->first();
                $update_re_filter = Reservationfilters::find($find_re_filter->id);
                $update_re_filter->status = 'รอทำความสะอาด';
                $update_re_filter->save();

                // อัปเดตสถานะเครื่องประดับ
                $update_status_jewelry = Jewelry::find($reservation->jewelry_id);
                $update_status_jewelry->jewelry_status = 'รอทำความสะอาด';
                $update_status_jewelry->jewelry_rental = $update_status_jewelry->jewelry_rental + 1;
                $update_status_jewelry->save();
            } elseif ($actionreturnitem == 'repairitem') {
                // dd('ส่งซ่อม') ; 
                $repair_detail_for_item = $request->input('repair_detail_for_item');
                //ตารางorderdetail
                $orderdetail->status_detail = "คืนเครื่องประดับแล้ว";
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
                $create_status->status = "คืนเครื่องประดับแล้ว";
                $create_status->save();

                //ตารางreservation
                $reservation = Reservation::find($orderdetail->reservation_id);
                $reservation->status = 'คืนเครื่องประดับแล้ว';
                $reservation->status_completed = 1; //เสร็จแล้ว
                $reservation->save();

                // ตารางหลอก
                $find_re_filter = Reservationfilters::where('reservation_id', $reservation->id)->first();
                $update_re_filter = Reservationfilters::find($find_re_filter->id);
                $update_re_filter->status = 'รอซ่อม';
                $update_re_filter->save();

                // อัปเดตสถานะเครื่องประดับ
                $update_status_jewelry = Jewelry::find($reservation->jewelry_id);
                $update_status_jewelry->jewelry_status = 'รอซ่อม';
                $update_status_jewelry->jewelry_rental = $update_status_jewelry->jewelry_rental + 1;
                $update_status_jewelry->save();

                // สร้างข้อมูลในตาราง repair
                $create_repair = new Repair();
                $create_repair->reservationfilter_id = $update_re_filter->id;
                $create_repair->repair_description = $repair_detail_for_item;
                $create_repair->repair_status = 'รอดำเนินการ';
                $create_repair->repair_type = 1;  //1.ยังไม่ทำความสะอาด 2.ทำความสะอาดแล้ว 
                $create_repair->save();
            } elseif ($actionreturnitem == 'lost') {
                // สูญหาย
                //ตารางorderdetail
                $orderdetail->status_detail = "คืนเครื่องประดับแล้ว";
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
                $create_status->status = "คืนเครื่องประดับแล้ว";
                $create_status->save();
                //ตารางorderdetailstatus
                $create_status = new Orderdetailstatus();
                $create_status->order_detail_id = $id;
                $create_status->status = "สูญหาย";
                $create_status->save();


                //ตารางreservation
                $reservation = Reservation::find($orderdetail->reservation_id);
                $reservation->status = 'คืนเครื่องประดับแล้ว';
                $reservation->status_completed = 1; //เสร็จแล้ว
                $reservation->save();

                // ตารางหลอก
                $find_re_filter = Reservationfilters::where('reservation_id', $reservation->id)->first();
                $update_re_filter = Reservationfilters::find($find_re_filter->id);
                $update_re_filter->status = 'สูญหาย';
                $update_re_filter->status_completed = 1; //เสร็จแล้ว
                $update_re_filter->save();

                // อัปเดตสถานะเครื่องประดับ
                $update_status_jewelry = Jewelry::find($reservation->jewelry_id);
                $update_status_jewelry->jewelry_status = 'สูญหาย';
                $update_status_jewelry->jewelry_rental = $update_status_jewelry->jewelry_rental + 1;
                $update_status_jewelry->save();

                // ตรวจหาว่าเครื่องประดับชิ้นนี้อยู่ในเซตไหนไหม
                $jewelry_item = Jewelrysetitem::where('jewelry_id', $reservation->jewelry_id)->get();
                if ($jewelry_item->isNotEmpty()) {
                    foreach ($jewelry_item as $valuee) {
                        $set_jewelry = Jewelryset::find($valuee->jewelry_set_id);
                        $set_jewelry->set_status = 'ยุติการให้เช่า';
                        $set_jewelry->save();
                    }
                }
            }
        }
        // เช่าเป็นเซต
        elseif ($check_for_set_or_item == 'set') {


            $refil_id = $request->input('refil_id_');
            $action_set = $request->input('action_set_');
            $repair_details_set = $request->input('repair_details_set_');
            $refil_jewelry_id = $request->input('refil_jewelry_id_');
            foreach ($refil_id as $index =>  $item) {
                if ($action_set[$index] == 'clean') {

                    //ตารางorderdetail
                    $orderdetail->status_detail = "คืนเครื่องประดับแล้ว";
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
                    $create_status->status = "คืนเครื่องประดับแล้ว";
                    $create_status->save();

                    //ตารางreservation
                    $reservation = Reservation::find($orderdetail->reservation_id);
                    $reservation->status = 'คืนเครื่องประดับแล้ว';
                    $reservation->status_completed = 1; //เสร็จแล้ว
                    $reservation->save();



                    // ตารางหลอก
                    $update_re_filter = Reservationfilters::find($refil_id[$index]);
                    $update_re_filter->status = 'รอทำความสะอาด';
                    $update_re_filter->save();
                    // อัปเดตสถานะเครื่องประดับ
                    $update_status_jewelry = Jewelry::find($refil_jewelry_id[$index]);
                    $update_status_jewelry->jewelry_status = 'รอทำความสะอาด';
                    $update_status_jewelry->jewelry_rental = $update_status_jewelry->jewelry_rental + 1;
                    $update_status_jewelry->save();
                } elseif ($action_set[$index] == 'repair') {

                    //ตารางorderdetail
                    $orderdetail->status_detail = "คืนเครื่องประดับแล้ว";
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
                    $create_status->status = "คืนเครื่องประดับแล้ว";
                    $create_status->save();

                    //ตารางreservation
                    $reservation = Reservation::find($orderdetail->reservation_id);
                    $reservation->status = 'คืนเครื่องประดับแล้ว';
                    $reservation->status_completed = 1; //เสร็จแล้ว
                    $reservation->save();


                    // ตารางหลอก
                    $update_re_filter = Reservationfilters::find($refil_id[$index]);
                    $update_re_filter->status = 'รอซ่อม';
                    $update_re_filter->save();
                    // อัปเดตสถานะเครื่องประดับ
                    $update_status_jewelry = Jewelry::find($refil_jewelry_id[$index]);
                    $update_status_jewelry->jewelry_status = 'รอซ่อม';
                    $update_status_jewelry->jewelry_rental = $update_status_jewelry->jewelry_rental + 1;
                    $update_status_jewelry->save();

                    // สร้างข้อมูลในตาราง repair
                    $create_repair = new Repair();
                    $create_repair->reservationfilter_id = $update_re_filter->id;
                    $create_repair->repair_description = $repair_details_set[$index];
                    $create_repair->repair_status = 'รอดำเนินการ';
                    $create_repair->repair_type = 1;  //1.ยังไม่ทำความสะอาด 2.ทำความสะอาดแล้ว 
                    $create_repair->save();
                } elseif ($action_set[$index] == 'lost') {

                    //ตารางorderdetail
                    $orderdetail->status_detail = "คืนเครื่องประดับแล้ว";
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
                    $create_status->status = "คืนเครื่องประดับแล้ว";
                    $create_status->save();
                    $create_status = new Orderdetailstatus();
                    $create_status->order_detail_id = $id;
                    $create_status->status = "สูญหาย";
                    $create_status->save();

                    //ตารางreservation
                    $reservation = Reservation::find($orderdetail->reservation_id);
                    $reservation->status = 'คืนเครื่องประดับแล้ว';
                    $reservation->status_completed = 1; //เสร็จแล้ว
                    $reservation->save();


                    // ตารางหลอก
                    $update_re_filter = Reservationfilters::find($refil_id[$index]);
                    $update_re_filter->status = 'สูญหาย';
                    $update_re_filter->status_completed = 1 ; 
                    $update_re_filter->save();
                    // อัปเดตสถานะเครื่องประดับ
                    $update_status_jewelry = Jewelry::find($refil_jewelry_id[$index]);
                    $update_status_jewelry->jewelry_status = 'สูญหาย';
                    $update_status_jewelry->jewelry_rental = $update_status_jewelry->jewelry_rental + 1;
                    $update_status_jewelry->save();


                    // ตรวจหาว่าเครื่องประดับชิ้นนี้อยู่ในเซตไหนไหม
                    $jewelry_item = Jewelrysetitem::where('jewelry_id', $refil_jewelry_id[$index])->get();
                    if ($jewelry_item->isNotEmpty()) {
                        foreach ($jewelry_item as $valuee) {
                            $set_jewelry = Jewelryset::find($valuee->jewelry_set_id);
                            $set_jewelry->set_status = 'ยุติการให้เช่า';
                            $set_jewelry->save();
                        }
                    }


                }
            }
        }

        // ถ้ามันตรวจสอบแล้วพบว่าทุกรายการ มันเป็น คืนชุด/คืนเครื่องประดับครบยัง ทั้งหมดแล้ว แปลว่า จะต้องทำการสร้างใบเสร็จอัตโนมัติเลย
        $count_index = 0;
        $additional_total = 0;
        $data_orderdetail = Orderdetail::where('order_id', $orderdetail->order_id)
            ->whereNotIn('status_detail', ['ยกเลิกโดยทางร้าน', 'ยกเลิกโดยลูกค้า'])
            ->get();
        $data_orderdetail_sum_damage_insurance = $data_orderdetail->sum('damage_insurance');
        foreach ($data_orderdetail as $item) {
            if ($item->type_order == 2) {
                if ($item->status_detail == 'คืนชุดแล้ว') {
                    $count_index += 1;
                }
            } elseif ($item->type_order == 3) {
                if ($item->status_detail == 'คืนเครื่องประดับแล้ว') {
                    $count_index += 1;
                }
            }
            $additional_receipt = AdditionalChange::where('order_detail_id', $item->id)->get();
            foreach ($additional_receipt as $value) {
                $additional_total += $value->amount;
            }
        }
        if ($count_index == $data_orderdetail->count()) {
            $ceate_receipt = new ReceiptReturn();
            $ceate_receipt->order_id = $orderdetail->order_id;
            $ceate_receipt->receipt_type = 3;
            $ceate_receipt->total_price = $data_orderdetail_sum_damage_insurance - $additional_total;
            $ceate_receipt->employee_id = Auth::user()->id;
            $ceate_receipt->save();
        }
        return redirect()->back()->with('success', 'ลูกค้าคืนเครื่องประดับแล้ว');
    }

    public function showpickupqueuejewelry()
    {
        $filer = 'today';
        $reservations = Reservation::where('status_completed', 0)
            ->where('status', 'ถูกจอง')
            ->whereDate('start_date', now())
            ->orderByRaw("STR_TO_DATE(start_date , '%Y-%m-%d') asc")
            ->get();
        return view('employeerentjewelry.jewelry-pickup-queue', compact('reservations', 'filer'));
    }
    public function showreturnqueuejewelry()
    {
        $filer = 'today';
        $listdressreturns = Reservation::where('status_completed', 0)
            ->where('status', 'กำลังเช่า')
            ->whereDate('end_date', now())
            ->orderByRaw("STR_TO_DATE(end_date , '%Y-%m-%d') asc")
            ->get();
        return view('employeerentjewelry.jewelry-return-queue', compact('listdressreturns', 'filer'));
    }

    public function showreturnqueuejewelryfilter(Request $request)
    {
        $filter_click = $request->input('filter_click');
        if ($filter_click == 'total') {
            $listdressreturns = Reservation::where('status_completed', 0)
                ->where('status', 'กำลังเช่า')
                ->orderByRaw("STR_TO_DATE(end_date , '%Y-%m-%d') asc")
                ->get();
            $filer = 'total';
        } elseif ($filter_click == 'today') {
            return $this->showreturnqueuejewelry();
        }
        return view('employeerentjewelry.jewelry-return-queue', compact('listdressreturns', 'filer'));
    }





    public function showpickupqueuejewelryfilter(Request $request)
    {
        $filter_click = $request->input('filter_click');
        if ($filter_click == "total") {
            $reservations = Reservation::where('status_completed', 0)
                ->where('status', 'ถูกจอง')
                ->orderByRaw("STR_TO_DATE(start_date , '%Y-%m-%d') asc")
                ->get();
            $filer = 'total';
        } elseif ($filter_click == "today") {
            return $this->showpickupqueuejewelry();
        }
        return view('employeerentjewelry.jewelry-pickup-queue', compact('reservations', 'filer'));
    }
    public function showcleanjewelry()
    {
        $clean_pending = Reservationfilters::where('status_completed', 0)
            ->where('status', 'รอทำความสะอาด')
            ->get();

        $clean_doing_wash = Reservationfilters::where('status_completed', 0)
            ->where('status', 'กำลังทำความสะอาด')
            ->get();

        return view('employeerentjewelry.jewelry-clean', compact('clean_pending', 'clean_doing_wash'));
    }

    public function jewelryupdatetocleaning(Request $request, $id)
    {
        $jewelry_id = $request->input('jewelry_id');

        $reser_filter = Reservationfilters::find($id);
        $reser_filter->status = 'กำลังทำความสะอาด';
        $reser_filter->save();

        $jewelry = Jewelry::find($jewelry_id);
        $jewelry->jewelry_status = 'กำลังทำความสะอาด';
        $jewelry->save();
        return redirect()->back()->with('success', 'อัพเดตสถานะสำเร็จ');
    }


    public function jewelryupdatetocleaned(Request $request, $id)
    {
        $jewelry_id = $request->input('jew_id');

        $reser_filter = Reservationfilters::find($id);
        $reser_filter->status = 'ทำความสะอาดเสร็จแล้ว';
        $reser_filter->status_completed = 1;
        $reser_filter->save();
        $jewelry = Jewelry::find($jewelry_id);
        $jewelry->jewelry_status = 'พร้อมให้เช่า';
        $jewelry->save();
        return redirect()->back()->with('success', 'อัพเดตสถานะสำเร็จ');
    }

    public function jewelryupdatetocleanedbutrepair(Request $request, $id)
    {
        $jewelry_id = $request->input('jew_id');

        $reser_filter = Reservationfilters::find($id);
        $reser_filter->status = 'รอดำเนินการซ่อม';
        $reser_filter->save();
        $jewelry = Jewelry::find($jewelry_id);
        $jewelry->jewelry_status = 'รอดำเนินการซ่อม';
        $jewelry->save();

        $create_repair = new Repair();
        $create_repair->repair_description = $request->input('repair_detail');
        $create_repair->repair_status = 'รอดำเนินการ';
        $create_repair->repair_type = 2; //1.ยังไม่ได้ทำความสะอาด 2.ทำความสะอาดแล้ว
        $create_repair->reservationfilter_id = $id;
        $create_repair->save();
        return redirect()->back()->with('success', 'อัพเดตสถานะสำเร็จ');
    }

    public function showrepairjewelry()
    {

        $repair_pending = Repair::whereNotNull('reservationfilter_id')
            ->where('repair_status', 'รอดำเนินการ')
            ->get();

        $repairs = Repair::whereNotNull('reservationfilter_id')
            ->where('repair_status', 'กำลังซ่อม')
            ->get();

        return view('employeerentjewelry.jewelry-repair', compact('repair_pending', 'repairs'));
    }

    public function jewelryupdatetorepairing(Request $request, $id)
    {

        $repair = Repair::find($id);
        $repair->repair_status = 'กำลังซ่อม';
        $repair->save();

        $reservationfilter_id = $request->input('reservationfilter_id');
        $reser_fil = Reservationfilters::find($reservationfilter_id);
        $reser_fil->status = 'กำลังซ่อม';
        $reser_fil->save();

        $jewelry_id = $request->input('jewelry_id');
        $jew = Jewelry::find($jewelry_id);
        $jew->jewelry_status = 'กำลังซ่อม';
        $jew->save();
        return redirect()->back()->with('success', 'อัพเดตสถานะสำเร็จ');
    }

    public function jewelryupdatetorepaired(Request $request, $id)
    {
        $repair_type = $request->input('repair_type');
        $jewelry_id = $request->input('jewelry_id');
        $reservationfilter_id = $request->input('reser_fil');


        if ($repair_type == "1") {
            // dd('ยังไม่ได้ทำความสะอาด') ; 
            $status_next = $request->input('status_next');
            if ($status_next == '1') {
                $reser_fil = Reservationfilters::find($reservationfilter_id);
                $reser_fil->status = 'รอทำความสะอาด';
                $reser_fil->save();
    
                $repair = Repair::find($id);
                $repair->repair_status = 'ซ่อมเสร็จแล้ว';
                $repair->save();
    
                $jew = Jewelry::find($jewelry_id);
                $jew->jewelry_status = 'รอทำความสะอาด';
                $jew->repair_count = $jew->repair_count + 1;
                $jew->save();
            }
            elseif ($status_next == '2') {
                $reser_fil = Reservationfilters::find($reservationfilter_id);
                $reser_fil->status = 'ซ่อมไม่ได้';
                $reser_fil->status_completed = 1;
                $reser_fil->save();

                $repair = Repair::find($id);
                $repair->repair_status = 'ซ่อมไม่ได้';
                $repair->save();

                $jew = Jewelry::find($jewelry_id);
                $jew->jewelry_status = 'ยุติการให้เช่า';
                $jew->repair_count = $jew->repair_count + 1;
                $jew->save();


            }

            

        } elseif ($repair_type == "2") {
            $status_next = $request->input('status_next');
            if ($status_next == '1') {
                $reser_fil = Reservationfilters::find($reservationfilter_id);
                $reser_fil->status = 'ซ่อมเสร็จแล้ว';
                $reser_fil->status_completed = 1;
                $reser_fil->save();

                $repair = Repair::find($id);
                $repair->repair_status = 'ซ่อมเสร็จแล้ว';
                $repair->save();

                $jew = Jewelry::find($jewelry_id);
                $jew->jewelry_status = 'พร้อมให้เช่า';
                $jew->repair_count = $jew->repair_count + 1;
                $jew->save();
            } elseif ($status_next == '2') {
                $reser_fil = Reservationfilters::find($reservationfilter_id);
                $reser_fil->status = 'รอทำความสะอาด';
                $reser_fil->save();

                $repair = Repair::find($id);
                $repair->repair_status = 'ซ่อมเสร็จแล้ว';
                $repair->save();

                $jew = Jewelry::find($jewelry_id);
                $jew->jewelry_status = 'รอทำความสะอาด';
                $jew->repair_count = $jew->repair_count + 1;
                $jew->save();
            }
            elseif($status_next == '3'){
                $reser_fil = Reservationfilters::find($reservationfilter_id);
                $reser_fil->status = 'ซ่อมไม่ได้';
                $reser_fil->status_completed = 1;
                $reser_fil->save();

                $repair = Repair::find($id);
                $repair->repair_status = 'ซ่อมไม่ได้';
                $repair->save();

                $jew = Jewelry::find($jewelry_id);
                $jew->jewelry_status = 'ยุติการให้เช่า';
                $jew->repair_count = $jew->repair_count + 1;
                $jew->save();
  
            }
            






        }
        return redirect()->back()->with('success', 'อัพเดตสถานะสำเร็จ');
    }

    public function showrentedhistory(Request $request, $id)
    {
        $jewelry = Jewelry::find($id);
        $typejewelry = Typejewelry::find($jewelry->type_jewelry_id);

        $value_month = 0;
        $value_year = 0;


        $history = Reservation::where('jewelry_id', $id)
            ->where('status_completed', 1)
            ->where('status', 'คืนเครื่องประดับแล้ว')
            // ->whereMonth('updated_at', now()->month)
            // ->whereYear('updated_at', now()->year)
            ->get();

        return view('employeerentjewelry.jewelry-rented-history', compact('history', 'jewelry', 'typejewelry', 'value_month', 'value_year'));
    }

    public function showrentedhistoryfilter(Request $request, $id)
    {
        $jewelry = Jewelry::find($id);
        $typejewelry = Typejewelry::find($jewelry->type_jewelry_id);
        $value_month = $request->input('month');
        $value_year = $request->input('year');


        $history = Reservation::where('jewelry_id', $id)
            ->where('status_completed', 1)
            ->where('status', 'คืนเครื่องประดับแล้ว');

        if ($value_month != 0) {
            $history->whereMonth('updated_at', $value_month);
        }

        if ($value_year != 0) {
            $history->whereYear('updated_at', $value_year);
        }

        $history = $history->get();

        return view('employeerentjewelry.jewelry-rented-history', compact('history', 'jewelry', 'typejewelry', 'value_month', 'value_year'));
    }

    public function showjewsetrentedhistory(Request $request, $id)
    {

        $jewelryset = Jewelryset::find($id);

        $value_month = 0;
        $value_year = 0;
        $history = Reservation::where('jewelry_set_id', $id)
            ->where('status_completed', 1)
            ->where('status', 'คืนเครื่องประดับแล้ว')
            ->get();
        return view('employeerentjewelry.jewelry-set-rented-history', compact('history', 'jewelryset', 'value_month', 'value_year'));
    }

    public function showjewsetrentedhistoryfilter(Request $request, $id)
    {
        $jewelryset = Jewelryset::find($id);
        $value_month = $request->input('month');
        $value_year = $request->input('year');
        $history = Reservation::where('jewelry_set_id', $id)
            ->where('status_completed', 1)
            ->where('status', 'คืนเครื่องประดับแล้ว');
        if ($value_month != 0) {
            $history->whereMonth('updated_at', $value_month);
        }
        if ($value_year != 0) {
            $history->whereYear('updated_at', $value_year);
        }
        $history = $history->get();

        return view('employeerentjewelry.jewelry-set-rented-history', compact('history', 'jewelryset', 'value_month', 'value_year'));
    }





    public function showrepairjewelryhistory($id)
    {
        $jewelry = Jewelry::find($id);
        $typejewelry = Typejewelry::find($jewelry->type_jewelry_id);

        $re_fil_jew_id = Reservationfilters::where('jewelry_id', $id)->get();
        $list_one = [];
        $list_two = [];
        foreach ($re_fil_jew_id as $item) {
            $list_one[] = $item->id;
        }

        foreach ($list_one as $item) {
            $findreservationfil = Repair::where('reservationfilter_id', $item)
                ->whereIn('repair_status', ['ซ่อมเสร็จแล้ว', 'กำลังซ่อม'])->get();


            if ($findreservationfil->isNotEmpty()) {
                foreach ($findreservationfil as $item) {
                    $list_two[] = $item->id;
                }
            }
        }
        $history = Repair::whereIn('id', $list_two)->get();
        return view('employeerentjewelry.jewelry-repaired-history', compact('jewelry', 'typejewelry', 'history'));
    }

    public function jewelryproblemcancel()
    {
        $jewelry = Jewelry::whereIn('jewelry_status', ['สูญหาย', 'ยุติการให้เช่า'])->get();
        $list = [];
        foreach ($jewelry as $item) {
            $reseraton = Reservationfilters::where('status_completed', 0)->where('jewelry_id', $item->id)->get();
            foreach ($reseraton as $index) {
                $list[] = $index->reservationtorefil->re_one_many_details->first()->id;
            }
        }
        $orderdetail = Orderdetail::whereIn('id', $list)->get();
        return view('employeerentjewelry.jewelry-problem', compact('orderdetail'));
    }
}
