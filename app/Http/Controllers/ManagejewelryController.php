<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\Date;
use App\Models\Jewelry;
use App\Models\Jewelryset;
use App\Models\Jewelrysetitem;
use App\Models\Order;
use App\Models\Orderdetail;
use App\Models\Reservation;
use App\Models\Reservationfilters;
use App\Models\Typejewelry;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ManagejewelryController extends Controller
{
    //
    public function postponeroutejewelry($id)
    {
        $reservation = Reservation::find($id);

        if ($reservation->jewelry_id) {
            return $this->postponeroutejewelrynoset($id);
        } elseif ($reservation->jewelry_set_id) {
            return $this->postponeroutejewelryset($id);
        }
    }



    private function postponeroutejewelrynoset($id)
    {
        $order_detail_id = Orderdetail::where('reservation_id', $id)->value('id');
        $orderdetail = Orderdetail::find($order_detail_id);
        $reser = Reservation::find($id);
        $jewelry = Jewelry::find($reser->jewelry_id);
        $typejewelry = Typejewelry::find($jewelry->type_jewelry_id);
        $cus_id = Order::where('id', $orderdetail->order_id)->value('customer_id');
        $cus = Customer::find($cus_id);
        $list = [];
        $jew_id_in_re = Reservation::where('status_completed', 0)
            ->where('jewelry_id', $reser->jewelry_id)
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->get();
        foreach ($jew_id_in_re as $item) {
            $list[] = $item->id;
        }
        // เอาแค่ jew_set_id
        $set_in_re = Reservation::where('status_completed', 0)
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->whereNotNull('jewelry_set_id')
            ->get();

        foreach ($set_in_re as $value) {
            $item_for_jew_set = Jewelrysetitem::where('jewelry_set_id', $value->jewelry_set_id,)->get();
            foreach ($item_for_jew_set as $item) {
                if ($reser->jewelry_id == $item->jewelry_id) {
                    $list[] = $value->id;
                }
            }
        }
        $reservation_jewelry_total = Reservation::whereIn('id', $list)
            ->where('status_completed', 0)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->get();
        $value_start_date = $reser->start_date;
        $value_end_date = $reser->end_date;
        $condition = 'no';
        return view('employeerentjewelry.jewelry-postpone-no', compact('reservation_jewelry_total', 'orderdetail', 'reser', 'jewelry', 'typejewelry', 'cus', 'value_start_date', 'value_end_date', 'condition'));
    }


    public function postponeroutejewelrycheckednoset(Request $request, $id)
    {
        $order_detail_id = Orderdetail::where('reservation_id', $id)->value('id');
        $orderdetail = Orderdetail::find($order_detail_id);
        $reser = Reservation::find($id);
        $jewelry = Jewelry::find($reser->jewelry_id);
        $typejewelry = Typejewelry::find($jewelry->type_jewelry_id);
        $cus_id = Order::where('id', $orderdetail->order_id)->value('customer_id');
        $cus = Customer::find($cus_id);
        $list = [];
        $jew_id_in_re = Reservation::where('status_completed', 0)
            ->where('jewelry_id', $reser->jewelry_id)
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->get();
        foreach ($jew_id_in_re as $item) {
            $list[] = $item->id;
        }
        // เอาแค่ jew_set_id
        $set_in_re = Reservation::where('status_completed', 0)
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->whereNotNull('jewelry_set_id')
            ->get();

        foreach ($set_in_re as $value) {
            $item_for_jew_set = Jewelrysetitem::where('jewelry_set_id', $value->jewelry_set_id,)->get();
            foreach ($item_for_jew_set as $item) {
                if ($reser->jewelry_id == $item->jewelry_id) {
                    $list[] = $value->id;
                }
            }
        }
        $reservation_jewelry_total = Reservation::whereIn('id', $list)
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->get();






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

        $check_reservation = Reservationfilters::where('status_completed', 0)
            ->where('jewelry_id', $reser->jewelry_id)
            ->whereNot('reservation_id', $reser->id)
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
        return view('employeerentjewelry.jewelry-postpone-no', compact('reservation_jewelry_total', 'orderdetail', 'reser', 'jewelry', 'typejewelry', 'cus', 'value_start_date', 'value_end_date', 'condition'));
    }




    public function postponecheckedpassjewelry(Request $request, $id)
    {


        $reservation_id = $request->input('reservation_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $reservation = Reservation::find($reservation_id);
        $reservation->start_date = $start_date;
        $reservation->end_date = $end_date;
        $reservation->save();


        $reservationfilter = Reservationfilters::where('reservation_id', $reservation_id)->get();
        foreach ($reservationfilter as $item) {
            $update_re_fil = Reservationfilters::find($item->id);
            $update_re_fil->start_date = $start_date;
            $update_re_fil->end_date = $end_date;
            $update_re_fil->save();
        }

        $date = new Date();
        $date->order_detail_id = $id;
        $date->pickup_date = $start_date;
        $date->return_date = $end_date;
        $date->save();
        return redirect()->route('postponeroutejewelry', ['id' => $reservation_id])->with('success', 'เลื่อนวันนัดรับ - นัดคืน สำเร็จ');
    }

    private function postponeroutejewelryset($id)
    {
        $order_detail_id = Orderdetail::where('reservation_id', $id)->value('id');
        $orderdetail = Orderdetail::find($order_detail_id);
        $reser = Reservation::find($id);
        $jewelryset = Jewelryset::find($reser->jewelry_set_id);
        $jewelrysetitem = Jewelrysetitem::where('jewelry_set_id', $reser->jewelry_set_id)->get();
        $cus_id = Order::where('id', $orderdetail->order_id)->value('customer_id');
        $cus = Customer::find($cus_id);
        $list = [];
        $jewwelry_set_id_in_reservation = Reservation::where('status_completed', 0)
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->where('jewelry_set_id', $reser->jewelry_set_id)
            ->get();
        foreach ($jewwelry_set_id_in_reservation as $key => $value) {
            $list[] = $value->id;
        }
        // ส่วนjew_id
        $jew_set_item = Jewelrysetitem::where('jewelry_set_id', $reser->jewelry_set_id)->get();
        foreach ($jew_set_item as $key => $item) {
            $check_jew_id_in_re = Reservation::where('status_completed', 0)
                ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
                ->where('jewelry_id', $item->jewelry_id)
                ->get();
            if ($check_jew_id_in_re->isNotEmpty()) {
                foreach ($check_jew_id_in_re as $value) {
                    $list[] = $value->id;
                }
            }
        }
        $reservation_jewelry_total = Reservation::whereIn('id', $list)
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->get();
        $value_start_date = $reser->start_date;
        $value_end_date = $reser->end_date;
        $condition = 'no';
        return view('employeerentjewelry.jewelry-postpone-yes', compact('reservation_jewelry_total', 'orderdetail', 'reser', 'jewelryset', 'jewelrysetitem', 'cus', 'value_start_date', 'value_end_date', 'condition'));
    }



    public function postponeroutejewelrycheckedyesset(Request $request, $id)
    {
        $order_detail_id = Orderdetail::where('reservation_id', $id)->value('id');
        $orderdetail = Orderdetail::find($order_detail_id);
        $reser = Reservation::find($id);
        $jewelryset = Jewelryset::find($reser->jewelry_set_id);
        $jewelrysetitem = Jewelrysetitem::where('jewelry_set_id', $reser->jewelry_set_id)->get();
        $cus_id = Order::where('id', $orderdetail->order_id)->value('customer_id');
        $cus = Customer::find($cus_id);
        $list = [];
        $jewwelry_set_id_in_reservation = Reservation::where('status_completed', 0)
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->where('jewelry_set_id', $reser->jewelry_set_id)
            ->get();
        foreach ($jewwelry_set_id_in_reservation as $key => $value) {
            $list[] = $value->id;
        }
        // ส่วนjew_id
        $jew_set_item = Jewelrysetitem::where('jewelry_set_id', $reser->jewelry_set_id)->get();
        foreach ($jew_set_item as $key => $item) {
            $check_jew_id_in_re = Reservation::where('status_completed', 0)
                ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
                ->where('jewelry_id', $item->jewelry_id)
                ->get();
            if ($check_jew_id_in_re->isNotEmpty()) {
                foreach ($check_jew_id_in_re as $value) {
                    $list[] = $value->id;
                }
            }
        }
        $reservation_jewelry_total = Reservation::whereIn('id', $list)
            ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
            ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
            ->get();


        //เช็ควันที่
        $value_start_date = $request->input('new_pickup_date');
        $value_end_date = $request->input('new_return_date');


        $pickup = Carbon::parse($request->input('new_pickup_date'));
        $return = Carbon::parse($request->input('new_return_date'));




        $start_date = $request->input('new_pickup_date');
        $end_date = $request->input('new_return_date');

        $start_date_fil = Carbon::parse($start_date);
        $end_date_fil = Carbon::parse($end_date);





        $fil_start_7 = $start_date_fil->copy()->subDays(7);
        $fil_start_1 = $start_date_fil->copy()->subDays(1);
        $fil_be_start = $start_date_fil->copy();
        $fil_be_end = $end_date_fil->copy();
        $fil_end_1 = $end_date_fil->copy()->addDays(1);
        $fil_end_7 = $end_date_fil->copy()->addDays(7);




        $jew_id_in_set = Jewelrysetitem::where('jewelry_set_id', $reser->jewelry_set_id)->get();
        $condition = true;
        foreach ($jew_id_in_set as $jew) {
            $check_jew_in_fil = Reservationfilters::where('jewelry_id', $jew->jewelry_id)
                ->where('status_completed', 0)
                ->whereNot('reservation_id', $reser->id)
                ->get();  // 3 แถว 
            foreach ($check_jew_in_fil as $item) {
                $start_re = Carbon::parse($item->start_date);
                $end_re = Carbon::parse($item->end_date);
                if ($start_re->between($fil_start_7, $fil_start_1) || $end_re->between($fil_start_7, $fil_start_1)) {
                    $condition = false;
                    break;
                }

                if ($start_re->between($fil_be_start, $fil_be_end) || $end_re->between($fil_be_start, $fil_be_end)) {
                    $condition = false;
                    break;
                }

                if ($start_re->between($fil_end_1, $fil_end_7) || $end_re->between($fil_end_1, $fil_end_7)) {
                    $condition = false;
                    break;
                }
            }
        }


        if ($condition == true) {
            session()->flash('condition', 'passsuccesst');
        } elseif ($condition == false) {
            session()->flash('condition', 'failno');
        }

        return view('employeerentjewelry.jewelry-postpone-yes', compact('reservation_jewelry_total', 'orderdetail', 'reser', 'jewelryset', 'jewelrysetitem', 'cus', 'value_start_date', 'value_end_date', 'condition'));
    }
    
}
