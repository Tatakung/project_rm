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
use App\Models\Receipt;
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
use App\Models\ReceiptReturn;
use App\Models\Reservationfilterdress;

use App\Models\Reservationfilters;
use App\Models\Jewelryset;
use App\Models\Afterreturndress;
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
        // $customers = Customer::with('orders')
        //     ->orderBy('created_at', 'desc')
        //     ->get();
        $order = Order::where('order_status', 1)
            ->orderBy('created_at', 'desc')
            ->whereNotNull('type_order')->paginate(20);
        return view('employee.ordertotal', compact('name_search', 'order'));
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
    public function ordertotaldetail(Request $request, $id)
    {


        $order = Order::find($id);


        if ($order->type_order == 1) {
            return $this->ordertotaldetailone($id);
        } elseif ($order->type_order == 2) {
            return $this->ordertotaldetailtwo($id);
        } elseif ($order->type_order == 3) {
            return $this->ordertotaldetailthree($id);
        }
    }

    private function ordertotaldetailone($id)
    {
        $order = Order::find($id);
        $customer = Customer::find($order->customer_id);
        $employee = User::find($order->user_id);
        $order_id = $id;
        $orderdetail = Orderdetail::where('order_id', $id)->get();
        $receipt_one = Receipt::where('order_id', $id)
            ->where('receipt_type', 1)
            ->first();
        $today = now()->toDateString();
        return view('employee.ordertotaldetailone', compact('order', 'order_id', 'orderdetail', 'customer', 'employee', 'receipt_one', 'today'));
    }
    private function ordertotaldetailtwo($id)
    {
        $order = Order::find($id);
        $customer = Customer::find($order->customer_id);
        $employee = User::find($order->user_id);
        $order_id = $id;
        $orderdetail = Orderdetail::where('order_id', $id)->get();

        $orderdetail_modal =  Orderdetail::where('order_id', $id)
            ->whereNotIn('status_detail', ['ยกเลิกโดยทางร้าน', 'ยกเลิกโดยลูกค้า'])
            ->get();

        $date_now = now()->toDateString();

        $date_only = Date::where('order_detail_id', $orderdetail->first()->id)
            ->orderBy('created_at', 'desc')
            ->first();



        $receipt_one = Receipt::where('order_id', $id)
            ->where('receipt_type', 1)
            ->first();
        $receipt_two = Receipt::where('order_id', $id)
            ->where('receipt_type', 2)
            ->first();

        $receipt_three  = ReceiptReturn::where('order_id', $id)
            ->where('receipt_type', 3)
            ->first();


        $is_fully_paid_number = 0; //เช็คว่าจ่ายเต็มหรือยัง
        $remaining_balance = 0;
        foreach ($orderdetail as $item) {
            if ($item->status_payment == 1) {
                $remaining_balance += $item->damage_insurance + ($item->price - $item->deposit);
                $is_fully_paid_number = $is_fully_paid_number +  1;
            }
        }


        if ($is_fully_paid_number == $orderdetail->count()) {
            $is_fully_paid = 10; //หมายความว่า มันจ่ายแค่มัดจำทุกรายการ
        } else {
            $is_fully_paid = 20;
        }

        $total_price = $orderdetail->sum('price');
        $total_deposit = $orderdetail->sum('deposit');
        $total_damage_insurance = $orderdetail->sum('damage_insurance');


        //เช็คปุ่มกดรับชุด-เครื่องประดับ พร้อมกัน
        $queue_pass = false;
        foreach ($orderdetail_modal as $value) {
            if ($value->type_order == 2) {
                $dress_mea_adjuust = Dressmeaadjustment::where('order_detail_id', $value->id)->get();


                // ตรวจสอบว่าถึงคิวมันหรือยัง
                if ($value->shirtitems_id) {
                    //  ตรวจสอบเฉพาะเสื้อก่อน
                    $status_shirt = Reservation::where('status_completed', 0)
                        ->where('dress_id', $value->dress_id)
                        ->where('shirtitems_id', $value->shirtitems_id)
                        ->whereNull('skirtitems_id')
                        ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                        ->get();
                    // ตรวจอสอบเช่าเฉพาะทั้งชุด แต่ห้ามเอาเช่าเฉพาะผ้าถุง/เสื้อมาเกี่ยวข้อง เพราะอย่าไปนับคิวด้วย
                    $status_total_dress = Reservation::where('status_completed', 0)
                        ->where('dress_id', $value->dress_id)
                        ->whereNull('shirtitems_id')
                        ->whereNull('skirtitems_id')
                        ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                        ->get();
                    $list__for__one = [];
                    foreach ($status_shirt as $item) {
                        $list__for__one[] = $item->id;
                    }
                    foreach ($status_total_dress as $item) {
                        $list__for__one[] = $item->id;
                    }
                    $reservation_now = reservation::whereIn('id', $list__for__one)
                        ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                        ->first();

                    // เงื่อนไขที่1 ต้องเช็คว่าถึงคิวมันยัง 
                    if ($reservation_now) {
                        if ($reservation_now->id == $value->reservation_id) {
                            $queue_pass = true;
                        } elseif ($reservation_now->id != $value->reservation_id) {
                            $queue_pass = false;
                            break;
                        }
                    } else {
                        $queue_pass = true;
                    }

                    // เงื่อนไขที่ 2 คือ ชุดต้องได้รับการปรับแก้ไขขนาดแล้ว 
                    foreach ($dress_mea_adjuust as $item) {
                        if ($item->new_size != $item->dressmeaadjust_many_to_one_dressmea->current_mea) {
                            $queue_pass = false;
                            break;
                        } else {
                            $queue_pass = true;
                        }
                    }
                } elseif ($value->skirtitems_id) {

                    //  ตรวจสอบเฉพาะผ้าถุงก่อน
                    $status_skirt = Reservation::where('status_completed', 0)
                        ->where('dress_id', $value->dress_id)
                        ->where('skirtitems_id', $value->skirtitems_id)
                        ->whereNull('shirtitems_id')
                        ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                        ->get();
                    // ตรวจอสอบเช่าเฉพาะทั้งชุด แต่ห้ามเอาเช่าเฉพาะเสื้อและผ้าถุงมาเกี่ยวข้อง เพราะอย่าไปนับคิวด้วย
                    $status_total_dress = Reservation::where('status_completed', 0)
                        ->where('dress_id', $value->dress_id)
                        ->whereNull('shirtitems_id')
                        ->whereNull('skirtitems_id')
                        ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                        ->get();
                    $list__for__one = [];
                    foreach ($status_skirt as $item) {
                        $list__for__one[] = $item->id;
                    }
                    foreach ($status_total_dress as $item) {
                        $list__for__one[] = $item->id;
                    }
                    // เงื่อนไขที่1 ต้องเช็คว่าถึงคิวมันยัง 
                    $reservation_now = reservation::whereIn('id', $list__for__one)
                        ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                        ->first();
                    if ($reservation_now) {
                        if ($reservation_now->id == $value->reservation_id) {
                            $queue_pass = true;
                        } elseif ($reservation_now->id != $value->reservation_id) {
                            $queue_pass = false;
                            break;
                        }
                    } else {
                        $queue_pass = true;
                    }


                    // เงื่อนไขที่ 2 คือ ชุดต้องได้รับการปรับแก้ไขขนาดแล้ว 
                    foreach ($dress_mea_adjuust as $item) {
                        if ($item->new_size != $item->dressmeaadjust_many_to_one_dressmea->current_mea) {
                            $queue_pass = false;
                            break;
                        } else {
                            $queue_pass = true;
                        }
                    }
                } else {
                    $reservation_now = Reservation::where('status_completed', 0)
                        ->where('dress_id', $value->dress_id)
                        ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                        ->first();

                    if ($reservation_now) {
                        if ($reservation_now->id == $value->reservation_id) {
                            $queue_pass = true;
                        } elseif ($reservation_now->id != $value->reservation_id) {
                            $queue_pass = false;
                            break;
                        }
                    } else {
                        $queue_pass = true;
                    }

                    // เงื่อนไขที่ 2 คือ ชุดต้องได้รับการปรับแก้ไขขนาดแล้ว 
                    foreach ($dress_mea_adjuust as $item) {
                        if ($item->new_size != $item->dressmeaadjust_many_to_one_dressmea->current_mea) {
                            $queue_pass = false;
                            break;
                        } else {
                            $queue_pass = true;
                        }
                    }
                }
            } elseif ($value->type_order == 3) {

                if ($value->detail_many_one_re->jewelry_id) {
                    $list_check = [];
                    $check_unique_jew_id = Reservation::where('status_completed', 0)
                        ->whereIn('status', ['ถูกจอง', 'กำลังเช่า'])
                        ->where('jewelry_id', $value->detail_many_one_re->jewelry_id)
                        ->get();
                    foreach ($check_unique_jew_id as $item) {
                        $list_check[] = $item->id;
                    }


                    $set_in_re = Reservation::where('status_completed', 0)
                        ->whereIn('status', ['ถูกจอง', 'กำลังเช่า'])
                        ->whereNotNull('jewelry_set_id')
                        ->get();

                    foreach ($set_in_re as $re_set) {
                        $item_for_jew_set = Jewelrysetitem::where('jewelry_set_id', $re_set->jewelry_set_id)->get();
                        foreach ($item_for_jew_set as $item) {
                            if ($value->detail_many_one_re->jewelry_id == $item->jewelry_id) {
                                $list_check[] = $re_set->id;
                            }
                        }
                    }
                    $sort_queue = Reservation::whereIn('id', $list_check)
                        ->orderByRaw("STR_TO_DATE(start_date,'%Y-%m-%d') asc")
                        ->first();

                    if ($sort_queue) {
                        if ($value->detail_many_one_re->id == $sort_queue->id) {
                            //ถึงคิวคุณแล้ว
                            if ($value->detail_many_one_re->status == 'ถูกจอง') {
                                if ($value->detail_many_one_re->resermanytoonejew->jewelry_status != 'พร้อมให้เช่า') {
                                    $queue_pass = false;
                                    break;
                                }
                            }
                            if ($value->detail_many_one_re->status == 'กำลังเช่า') {
                                $check_bunton_pass = true;
                            }
                        }

                        // ยังไม่ถึงคิว
                        else {
                            $queue_pass = false;
                            break;
                        }
                    } else {
                        $queue_pass = true;
                    }
                } elseif ($value->detail_many_one_re->jewelry_set_id) {
                    // dd($value->id) ; 
                    $list_set = [];
                    // แค่jewelry_set_idในตาราง reservation
                    $jewwelry_set_id_in_reservation = Reservation::where('status_completed', 0)
                        ->whereIn('status', ['ถูกจอง', 'กำลังเช่า'])
                        ->where('jewelry_set_id', $value->detail_many_one_re->jewelry_set_id)
                        ->get();
                    foreach ($jewwelry_set_id_in_reservation as $key => $valueee) {
                        $list_set[] = $valueee->id;
                    }
                    // ส่วนjew_id
                    $jew_set_item = Jewelrysetitem::where('jewelry_set_id', $value->detail_many_one_re->jewelry_set_id)->get();
                    foreach ($jew_set_item as $key => $item) {
                        $check_jew_id_in_re = Reservation::where('status_completed', 0)
                            ->whereIn('status', ['ถูกจอง', 'กำลังเช่า'])
                            ->where('jewelry_id', $item->jewelry_id)
                            ->get();

                        if ($check_jew_id_in_re->isNotEmpty()) {
                            foreach ($check_jew_id_in_re as $index) {
                                $list_set[] = $index->id;
                            }
                        }
                    }


                    $sort_queue = Reservation::whereIn('id', $list_set)
                        ->orderByRaw("STR_TO_DATE(start_date,'%Y-%m-%d') asc")
                        ->first();
                    if ($sort_queue) {
                        if ($value->detail_many_one_re->id == $sort_queue->id) {
                            // คุณคือคิวแรก

                            if ($value->detail_many_one_re->status == 'ถูกจอง') {
                                $jew_set_id_for = Jewelrysetitem::where('jewelry_set_id', $value->detail_many_one_re->jewelry_set_id,)->get();
                                foreach ($jew_set_id_for as $key => $index_for_item) {
                                    $check_jew_status = Jewelry::find($index_for_item->jewelry_id);
                                    if ($check_jew_status->jewelry_status != 'พร้อมให้เช่า') {
                                        $queue_pass = false;
                                        break;
                                    } else {
                                        $queue_pass = true;
                                    }
                                }
                            }

                            if ($value->detail_many_one_re->status == 'กำลังเช่า') {
                                $queue_pass = true;
                            }
                        } else {
                            $queue_pass = false;
                            break;
                        }
                    } else {
                        $queue_pass = true;
                    }
                }
            }
        }


        // เช็คสถานะทั้งหมดของ order ว่า ถูกจองทั้งหมดไหม ถ้าถูกจองทั้งหมด จะได้ไม่ต้องแสดงปุ่มรับ
        $check_number_detail_status = 0;
        $return_number = 0;
        $new_status_check_number = 0;
        foreach ($orderdetail as $detail) {
            if ($detail->status_detail == 'กำลังเช่า') {
                $check_number_detail_status = $check_number_detail_status + 1;
            }
            if ($detail->type_order == 2) {
                if ($detail->status_detail == 'คืนชุดแล้ว') {
                    $return_number += 1;
                }
            } elseif ($detail->type_order == 3) {
                if ($detail->status_detail == 'คืนเครื่องประดับแล้ว') {
                    $return_number += 1;
                }
            }


            $check_status_new = Orderdetailstatus::where('order_detail_id', $detail->id)
                ->where('status', 'กำลังเช่า')
                ->exists();
            if ($check_status_new) {
                $new_status_check_number += 1;
            }
        }
        if ($check_number_detail_status >= 1) {
            $check_text_detail_status = true;
        } elseif ($check_number_detail_status == $orderdetail->count()) {
            $check_text_detail_status = false;
        } else {
            $check_text_detail_status = true;
        }

        if ($return_number >= 1) {
            $check_text_detail_status_two = false;
        } else {
            $check_text_detail_status_two = true;
        }

        if ($date_now == $date_only->pickup_date) {
            $check_date_now = true;
        } else {
            $check_date_now = false;
        }

        // dd($check_date_now) ; 

        $only_payment = Paymentstatus::where('order_detail_id', $orderdetail->first()->id)
            ->where('payment_status', 1)
            ->exists();

        $check_button_new = true;
        if ($new_status_check_number == $orderdetail->count()) {
            $check_button_new = false;
        }





        return view('employee.ordertotaldetailtwo', compact('order', 'order_id', 'orderdetail', 'customer', 'employee', 'receipt_one', 'receipt_two', 'receipt_three', 'remaining_balance', 'total_price', 'total_deposit', 'total_damage_insurance', 'is_fully_paid', 'date_only', 'queue_pass', 'check_text_detail_status', 'check_text_detail_status_two', 'check_date_now', 'only_payment', 'check_button_new', 'orderdetail_modal'));
    }
    private function ordertotaldetailthree($id)
    {
        $order = Order::find($id);
        $customer = Customer::find($order->customer_id);
        $employee = User::find($order->user_id);
        $order_id = $id;
        $orderdetail = Orderdetail::where('order_id', $id)->get();

        $receipt_one = Receipt::where('order_id', $id)
            ->where('receipt_type', 1)
            ->first();
        $receipt_two = Receipt::where('order_id', $id)
            ->where('receipt_type', 2)
            ->first();

        $receipt_three  = Receipt::where('order_id', $id)
            ->where('receipt_type', 3)
            ->first();

        $date_now = now()->toDateString();

        $date_only = Date::where('order_detail_id', $orderdetail->first()->id)
            ->orderBy('created_at', 'desc')
            ->first();

        // พูดง่ายๆก็คือว่า ถ้ามันมีเลข 1 หมายคววามว่า มันจ่ายมัดจำ
        $only_payment = Paymentstatus::where('order_detail_id', $orderdetail->first()->id)
            ->where('payment_status', 1)
            ->exists();
        $total_price = $orderdetail->sum('price');
        $total_deposit = $orderdetail->sum('deposit');
        $total_damage_insurance = $orderdetail->sum('damage_insurance');

        $remaining_balance = 0;
        $decoration_sum = 0;
        foreach ($orderdetail as $item) {
            if ($item->status_payment == 1) {
                $remaining_balance += $item->damage_insurance + ($item->price - $item->deposit);
            }


            $decoration = Decoration::where('order_detail_id', $item->id)->get();
            foreach ($decoration as $value) {
                $decoration_sum += $value->decoration_price;
            }
        }
        $remaining_balance = $remaining_balance + $decoration_sum;


        // กำหนดปุ่มว่าจะให้มนักดได้ตอนไหน 
        // $condition_one = 
        // เงื่อนไขที่ 1 คือ สถานะทุกรายการ จะต้องเป็น มีคำว่าถูกจอง

        $condition_one_number = 0;
        $condition_two_number = 0;
        foreach ($orderdetail as $value) {

            $check_status_two = Orderdetailstatus::where('order_detail_id', $value->id)
                ->where('status', 'กำลังเช่า')
                ->exists();
            if ($check_status_two) {
                $condition_two_number += 1;
            }

            $check_status = Orderdetailstatus::where('order_detail_id', $value->id)
                ->where('status', 'ถูกจอง')
                ->exists();
            if ($check_status) {
                $condition_one_number += 1;
            }
        }
        $pass_one = false;
        if ($condition_one_number == $orderdetail->count()) {
            $pass_one = true;
        }


        $pass_two = true;
        if ($condition_two_number == $orderdetail->count()) {
            $pass_two = false;
        }

        return view('employee.ordertotaldetailthree', compact('total_price', 'total_deposit', 'total_damage_insurance', 'order', 'order_id', 'orderdetail', 'customer', 'employee', 'receipt_one', 'receipt_two', 'receipt_three', 'date_only', 'only_payment', 'remaining_balance', 'decoration_sum', 'pass_one', 'pass_two'));
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
        $datadress = Dress::find($orderdetail->dress_id);
        $shirtdata = Shirtitem::where('dress_id', $datadress->id)->first();
        $skirtdata = Skirtitem::where('dress_id', $datadress->id)->first();
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

        $sum_additional = AdditionalChange::where('order_detail_id', $id)->sum('amount');


        $orderdetailstatus = Orderdetailstatus::where('order_detail_id', $id)->get();
        $valuestatus = $orderdetail->status_detail;
        $valuestatus = Orderdetailstatus::where('order_detail_id', $id)
            ->latest('created_at')
            ->value('status');

        $status_if_dress = Reservation::where('dress_id', $orderdetail->dress_id)
            ->where('status_completed', 0)
            ->orderByRaw(" STR_TO_DATE(start_date, '%Y-%m-%d') asc")
            ->first();

        $filtershirt_id = Reservationfilterdress::where('reservation_id', $orderdetail->reservation_id)
            ->whereNotNull('shirtitems_id')
            ->value('id');
        $filterskirt_id = Reservationfilterdress::where('reservation_id', $orderdetail->reservation_id)
            ->whereNotNull('skirtitems_id')
            ->value('id');



        $his_dress_adjust = Dressmeasurementcutedit::where('order_detail_id', $id)->get();



        $dateeee = Date::where('order_detail_id', $id)
            ->orderBy('created_at', 'desc')
            ->first();
        $receipt_bill_pickup = Orderdetailstatus::where('order_detail_id', $id)
            ->where('status', 'กำลังเช่า')
            ->exists();


        $receipt_bill_return = Orderdetailstatus::where('order_detail_id', $id)
            ->where('status', 'คืนชุดแล้ว')
            ->exists();


        $reservationfilterdress = Reservationfilterdress::where('reservation_id', $orderdetail->reservation_id)->get();

        $currentdate = now()->toDateString() ; 
        // dd($current) ; 

        return view('employeerentdress.managedetailrentdress', compact('receipt_bill_pickup', 'receipt_bill_return',   'additional', 'dress_mea_adjust_modal_show', 'status_if_dress', 'orderdetail', 'dress', 'employee', 'fitting', 'cost', 'date', 'decoration', 'imagerent', 'mea_dress', 'mea_orderdetail', 'orderdetailstatus', 'valuestatus', 'customer', 'mea_orderdetail_for_adjust', 'dressimage', 'dress_mea_adjust', 'dress_mea_adjust_modal', 'dress_mea_adjust_button', 'his_dress_adjust', 'dateeee', 'sum_additional', 'datadress', 'shirtdata', 'skirtdata', 'filtershirt_id', 'filterskirt_id', 'reservationfilterdress','currentdate'));
    }


    //จัดการเช่าเครื่องประดับ
    private function managedetailrentjewelry($id)
    {
        $orderdetail = Orderdetail::find($id);
        $order = Order::find($orderdetail->order_id);
        $customer = Customer::find($order->customer_id);
        $user = User::find($order->user_id);
        $reservation = Reservation::find($orderdetail->reservation_id);
        $reservationfilter = Reservationfilters::where('reservation_id', $reservation->id)->get();
        $Date = Date::where('order_detail_id', $orderdetail->id)
            ->orderBy('created_at', 'desc')
            ->first();
        if ($reservation->jewelry_id) {
            $jewelry = Jewelry::find($reservation->jewelry_id);
            $typejewelry = Typejewelry::where('id', $jewelry->type_jewelry_id)->first();
            $imagejewelry = Jewelryimage::where('jewelry_id', $jewelry->id)->first();
            $setjewelry = null;
            $setjewelryitem = null;
            $check_not_ready = false;
        } else {
            $setjewelry = Jewelryset::find($reservation->jewelry_set_id);
            $setjewelryitem = Jewelrysetitem::where('jewelry_set_id', $setjewelry->id)->get();
            $jewelry = null;
            $typejewelry = null;
            $imagejewelry = null;
            $check_not_ready = false;


            // เช็คสถานะเฉพาะเซตก่อน

            if ($setjewelry->set_status == 'พร้อมให้เช่า') {
                foreach ($setjewelryitem as $itemm) {
                    $jewel = Jewelry::find($itemm->jewelry_id);
                    if ($jewel->jewelry_status == 'สูญหาย' || $jewel->jewelry_status == 'ยุติการให้เช่า') {
                        $check_not_ready = true;
                        break;
                    }
                }
            } elseif ($setjewelry->set_status == 'ยุติการให้เช่า') {
                $check_not_ready = true;
            }
        }
        $orderdetailstatus = Orderdetailstatus::where('order_detail_id', $id)->get();
        $additional = AdditionalChange::where('order_detail_id', $id)->get();
        $receipt_bill_pickup = Orderdetailstatus::where('order_detail_id', $id)
            ->where('status', 'กำลังเช่า')
            ->exists();


        $receipt_bill_return = Orderdetailstatus::where('order_detail_id', $id)
            ->where('status', 'คืนเครื่องประดับแล้ว')
            ->exists();
        $additional = AdditionalChange::where('order_detail_id', $id)->get();

        $sum_additional = AdditionalChange::where('order_detail_id', $id)->sum('amount');

        $currentdate = now()->toDateString() ; 
        return view('employeerentjewelry.managedetailrentjewelry', compact('additional', 'sum_additional', 'orderdetail', 'reservation', 'jewelry', 'receipt_bill_pickup', 'typejewelry', 'receipt_bill_return', 'orderdetailstatus', 'setjewelry', 'imagejewelry', 'order', 'customer', 'user', 'setjewelryitem', 'Date', 'reservationfilter', 'additional', 'check_not_ready','currentdate'));
    }

    //จัดการเช่าตัด
    private function managedetailrentcut($id)
    {
        $orderdetail = Orderdetail::find($id);
        $datadress = Dress::find($orderdetail->dress_id);
        $dress = Dress::where('id', $orderdetail->dress_id)->select('dress_code_new', 'dress_code')->first();
        $customer_id = Order::where('id', $orderdetail->order_id)->value('customer_id');
        $customer = Customer::find($customer_id);
        $employee = User::find($orderdetail->employee_id);
        $fitting = Fitting::where('order_detail_id', $id)->get();
        $cost = Cost::where('order_detail_id', $id)->get();
        $date = Date::where('order_detail_id', $id)->get();

        $decoration = Decoration::where('order_detail_id', $id)->get();

        $decoration_sum = $decoration->sum('decoration_price');

        $sum_dec = Decoration::where('order_detail_id', $orderdetail->id)->sum(
            'decoration_price',
        );
        $filtershirt_id = Reservationfilterdress::where('reservation_id', $orderdetail->reservation_id)
            ->whereNotNull('shirtitems_id')
            ->value('id');
        $filterskirt_id = Reservationfilterdress::where('reservation_id', $orderdetail->reservation_id)
            ->whereNotNull('skirtitems_id')
            ->value('id');




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
        $sum_additional = AdditionalChange::where('order_detail_id', $id)->sum('amount');

        $his_dress_adjust = Dressmeasurementcutedit::where('order_detail_id', $id)->get();


        $dateeee = Date::where('order_detail_id', $id)
            ->orderBy('created_at', 'desc')
            ->first();
        $receipt_bill_pickup = Orderdetailstatus::where('order_detail_id', $id)
            ->where('status', 'กำลังเช่า')
            ->exists();


        $receipt_bill_return = Orderdetailstatus::where('order_detail_id', $id)
            ->where('status', 'คืนชุดแล้ว')
            ->exists();
        $reservationfilterdress = Reservationfilterdress::where('reservation_id', $orderdetail->reservation_id)->get();
        return view('employeerentcut.managedetailrentcut', compact('datadress', 'additional', 'dress_mea_adjust_modal_show', 'receipt_bill_pickup', 'receipt_bill_return',  'status_if_dress', 'orderdetail', 'dress', 'employee', 'fitting', 'cost', 'date', 'decoration', 'imagerent', 'mea_dress', 'mea_orderdetail', 'orderdetailstatus', 'valuestatus', 'customer', 'mea_orderdetail_for_adjust', 'dressimage', 'dress_mea_adjust', 'sum_dec', 'dress_mea_adjust_modal', 'dress_mea_adjust_button', 'his_dress_adjust', 'dateeee', 'decoration_sum', 'sum_additional', 'filtershirt_id', 'filterskirt_id','reservationfilterdress'));
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
        $decco = Decoration::where('order_detail_id', $id)->get();
        $decoration_sum = $decoration->sum('decoration_price');
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
        $check_cancel = Orderdetailstatus::where('order_detail_id', $id)
            ->where('status', 'เริ่มดำเนินการตัด')
            ->exists();

        $route_modal = AdjustmentRound::where('order_detail_id', $id)
            ->orderBy('created_at', 'desc')
            ->first();
        $is_admin = Auth::user()->is_admin;  //ตรวจสอบว่าเป็นแอดมินไหม
        $who_login = Auth::user()->id; //คนที่กำลังlogin
        $person_order = Order::where('id', $orderdetail->order_id)->value('user_id');  //คนที่รับ order

        $receipt_two = Receipt::where('order_detail_id', $id)
            ->where('receipt_type', 2)
            ->first();


        return view('employeecutdress.managedetailcutdress', compact('is_admin', 'who_login', 'person_order', 'orderdetail', 'dress', 'employee', 'fitting', 'cost', 'Date', 'decoration', 'imagerent', 'mea_dress', 'mea_orderdetail', 'orderdetailstatus', 'valuestatus', 'customer', 'mea_orderdetailforedit', 'dress_adjusts', 'dress_edit_cut', 'round', 'route_modal', 'decoration_sum', 'check_cancel', 'decco', 'receipt_two'));
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

        $past_7 = $pickup->copy()->subDays(7); //ถอยกลับไป 7 วัน
        $future_7 = $return->copy()->addDays(7); // เพิ่มเข้าไป 7 วัน 

        $check_reservation = Reservationfilterdress::where('status_completed', 0)
            ->where('dress_id', $dress->id)
            ->whereNot('id', $reser->id)
            ->get();


        $condition = true;
        foreach ($check_reservation as $item) {
            $reservation_start = Carbon::parse($item->start_date);
            $reservation_end = Carbon::parse($item->end_date);
            if ($reservation_start->between($past_7, $future_7) ||  $reservation_end->between($past_7, $future_7)) {
                $condition = false;
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


        $input_start_7 = $input_start->copy()->subDays(7); //ถอยกลับไป 7 วัน
        $input_end_7 = $input_end->copy()->addDays(7); //เพิ่มไป 7 วัน

        $condition = true;


        // เช็คแค่เฉพาะเสื้อ
        $reservation_check_total_shirt = Reservationfilterdress::where('status_completed', 0)
            ->where('shirtitems_id', $shirt_id)
            ->whereNot('reservation_id', $orderdetail->reservation_id)
            ->get();
        foreach ($reservation_check_total_shirt as $item) {
            $reser_start = \Carbon\Carbon::parse($item->start_date);
            $reser_end = \Carbon\Carbon::parse($item->end_date);
            if ($reser_start->between($input_start_7, $input_end_7)   || $reser_end->between($input_start_7, $input_end_7)) {
                $condition = false;
            }
        }

        // เช็คแค่เฉพาะผ้าถุง
        $reservation_check_total_skirt = Reservationfilterdress::where('status_completed', 0)
            ->where('skirtitems_id', $skirt_id)
            ->whereNot('reservation_id', $orderdetail->reservation_id)
            ->get();
        foreach ($reservation_check_total_skirt as $item) {
            $reser_start = \Carbon\Carbon::parse($item->start_date);
            $reser_end = \Carbon\Carbon::parse($item->end_date);
            if ($reser_start->between($input_start_7, $input_end_7)   || $reser_end->between($input_start_7, $input_end_7)) {
                $condition = false;
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


        $input_start_7 = $input_start->copy()->subDays(7); //ถอยกลับไป 7 วัน
        $input_end_7 = $input_end->copy()->addDays(7); //บวกเพิ่มไป 7 วัน

        $condition = true;


        // เช็คแค่เฉพาะเสื้อ
        $reservation_check_total_shirt = Reservationfilterdress::where('status_completed', 0)
            ->where('shirtitems_id', $shirt_id)
            ->whereNot('reservation_id', $orderdetail->reservation_id)
            ->get();
        foreach ($reservation_check_total_shirt as $item) {
            $reser_start = \Carbon\Carbon::parse($item->start_date);
            $reser_end = \Carbon\Carbon::parse($item->end_date);
            if ($reser_start->between($input_start_7, $input_end_7)   || $reser_end->between($input_start_7, $input_end_7)) {
                $condition = false;
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


        $input_start_7 = $input_start->copy()->subDays(7); //ถอยกลับไป 7 วัน
        $input_end_7 = $input_end->copy()->addDays(7); // เพิ่ม 7 วัน 
        $condition = true;


        // เช็คแค่เฉพาะผ้าถุง
        $reservation_check_total_skirt = Reservationfilterdress::where('status_completed', 0)
            ->where('skirtitems_id', $skirt_id)
            ->whereNot('reservation_id', $orderdetail->reservation_id)
            ->get();
        foreach ($reservation_check_total_skirt as $item) {
            $reser_start = \Carbon\Carbon::parse($item->start_date);
            $reser_end = \Carbon\Carbon::parse($item->end_date);
            if ($reser_start->between($input_start_7, $input_end_7)   || $reser_end->between($input_start_7, $input_end_7)) {
                $condition = false;
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
        $add_fitting->fitting_status = "ยังไม่มาลองชุด";
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



    // public function actionaddmeaorderdetail(Request $request,  $id)
    // {
    //     $add_mea_orderdetail = new Measurementorderdetail();
    //     $add_mea_orderdetail->order_detail_id = $id;
    //     $add_mea_orderdetail->measurement_name = $request->input('add_measurement_name');
    //     $add_mea_orderdetail->measurement_number = $request->input('add_measurement_number');
    //     $add_mea_orderdetail->measurement_unit = $request->input('add_measurement_unit');
    //     $add_mea_orderdetail->save();
    //     return redirect()->back()->with('success', 'เพิ่มข้อมูลการวัดสำเร็จ !');
    // }
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
            $filterdress = Reservationfilterdress::where('reservation_id', $orderdetail->reservation_id)->get();

            foreach ($filterdress as $item) {
                $update_filterdress = Reservationfilterdress::find($item->id);
                $update_filterdress->status = 'กำลังเช่า';
                $update_filterdress->save();
            }

            $Dress_data = Dress::find($orderdetail->dress_id);
            if ($Dress_data->separable == 1) {
                $update_dress = Dress::find($orderdetail->dress_id);
                $update_dress->dress_status = 'กำลังถูกเช่า';
                $update_dress->save();
            } elseif ($Dress_data->separable == 2) {


                if ($orderdetail->shirtitems_id) {
                    $shirt_ID = Shirtitem::where('dress_id', $orderdetail->dress_id)->value('id');
                    $update_SHIRT = Shirtitem::find($shirt_ID);
                    $update_SHIRT->shirtitem_status = 'กำลังถูกเช่า';
                    $update_SHIRT->save();
                } elseif ($orderdetail->skirtitems_id) {
                    $skirt_ID = Skirtitem::where('dress_id', $orderdetail->dress_id)->value('id');
                    $update_SKIRT = Skirtitem::find($skirt_ID);
                    $update_SKIRT->skirtitem_status = 'กำลังถูกเช่า';
                    $update_SKIRT->save();
                } else {
                    $shirt_ID = Shirtitem::where('dress_id', $orderdetail->dress_id)->value('id');
                    $skirt_ID = Skirtitem::where('dress_id', $orderdetail->dress_id)->value('id');
                    $update_SHIRT = Shirtitem::find($shirt_ID);
                    $update_SHIRT->shirtitem_status = 'กำลังถูกเช่า';
                    $update_SHIRT->save();
                    $update_SKIRT = Skirtitem::find($skirt_ID);
                    $update_SKIRT->skirtitem_status = 'กำลังถูกเช่า';
                    $update_SKIRT->save();
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
                $total_price_receipt = 0;
                // $price_total_decoration = 0 ; 

                $price_total_decoration = 0;
                foreach ($data_orderdetail as $index) {
                    $decoration_receipt = Decoration::where('order_detail_id', $index->id)->get();
                    foreach ($decoration_receipt as $item) {
                        $price_total_decoration += $item->decoration_price;
                    }
                }

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
                $ceate_receipt->receipt_type = 2;
                $ceate_receipt->total_price = $total_price_receipt + $price_total_decoration;
                $ceate_receipt->employee_id = Auth::user()->id;
                $ceate_receipt->save();
            }
        } elseif ($status == "กำลังเช่า") {
            $total_damage_insurance = $request->input('total_damage_insurance'); //1.ปรับเงินประกันจริงๆ 
            $late_return_fee = $request->input('late_return_fee'); //2.ค่าปรับส่งคืนชุดล่าช้า:
            $late_chart = $request->input('late_chart'); //3.ค่าธรรมเนียมขยายระยะเวลาเช่า:

            // if ($total_damage_insurance > 0) {
            //     $create_additional = new AdditionalChange();
            //     $create_additional->order_detail_id = $id;
            //     $create_additional->charge_type = 1;
            //     $create_additional->amount = $total_damage_insurance;
            //     $create_additional->save();
            // }
            // if ($late_return_fee > 0) {
            //     $create_additional = new AdditionalChange();
            //     $create_additional->order_detail_id = $id;
            //     $create_additional->charge_type = 2;
            //     $create_additional->amount = $late_return_fee;
            //     $create_additional->save();
            // }
            // if ($late_chart) {
            //     $create_additional = new AdditionalChange();
            //     $create_additional->order_detail_id = $id;
            //     $create_additional->charge_type = 3;
            //     $create_additional->amount = $late_chart;
            //     $create_additional->save();
            // }

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
            $damage_insurance_separable_one = $request->input('damage_insurance_separable_one');
            $dress = Dress::where('id', $orderdetail->dress_id)->first();
            if ($dress->separable == 1) {

                if ($request->input('actionreturnitemtotaldress') == 'cleanitem') {
                    // สภาพปกติ
                    $filterdress = Reservationfilterdress::where('reservation_id', $orderdetail->reservation_id)->get();
                    foreach ($filterdress as $item) {
                        $updatefilterdress = Reservationfilterdress::find($item->id);
                        $updatefilterdress->status = 'รอทำความสะอาด';
                        $updatefilterdress->save();
                    }
                    $update_dress = Dress::find($dress->id);
                    $update_dress->dress_rental = $update_dress->dress_rental + 1; //จำนวนครั้งที่ถูกเช่า
                    $update_dress->dress_status = 'รอทำความสะอาด';
                    $update_dress->save();

                    $after_return_dress = new Afterreturndress();
                    $after_return_dress->reservationfilterdress_id = $filterdress->first()->id;
                    $after_return_dress->type = 1;
                    $after_return_dress->price = $damage_insurance_separable_one;
                    $after_return_dress->save();
                    if ($damage_insurance_separable_one > 0) {
                        $create_additional = new AdditionalChange();
                        $create_additional->order_detail_id = $id;
                        $create_additional->charge_type = 1;
                        $create_additional->amount = $damage_insurance_separable_one;
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
                } elseif ($request->input('actionreturnitemtotaldress') == 'repairitem') {
                    // ต้องซ่อม
                    $filterdress = Reservationfilterdress::where('reservation_id', $orderdetail->reservation_id)->get();
                    foreach ($filterdress as $item) {
                        $updatefilterdress = Reservationfilterdress::find($item->id);
                        $updatefilterdress->status = 'รอดำเนินการซ่อม';
                        $updatefilterdress->save();
                    }
                    $update_dress = Dress::find($dress->id);
                    $update_dress->dress_rental = $update_dress->dress_rental + 1; //จำนวนครั้งที่ถูกเช่า
                    $update_dress->dress_status = 'รอดำเนินการซ่อม';
                    $update_dress->save();


                    $FILTERDRESS_ID = Reservationfilterdress::where('reservation_id', $orderdetail->reservation_id)->value('id');
                    //ตารางreqpair 
                    $create_repair = new Repair();
                    $create_repair->reservationfilterdress_id = $FILTERDRESS_ID;
                    $create_repair->repair_description = $request->input('repair_detail_for_itemtotaldress');
                    $create_repair->repair_status = 'รอดำเนินการ';
                    $create_repair->repair_type = 1; //1.ยังไม่ได้ทำความสะอาด 2.ทำความสะอาดแล้ว
                    $create_repair->save();

                    $after_return_dress = new Afterreturndress();
                    $after_return_dress->reservationfilterdress_id = $filterdress->first()->id;
                    $after_return_dress->type = 2;
                    $after_return_dress->price = $damage_insurance_separable_one;
                    $after_return_dress->save();
                    if ($damage_insurance_separable_one > 0) {
                        $create_additional = new AdditionalChange();
                        $create_additional->order_detail_id = $id;
                        $create_additional->charge_type = 1;
                        $create_additional->amount = $damage_insurance_separable_one;
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
                } elseif ($request->input('actionreturnitemtotaldress') == 'lost') {
                    // สูญหาย (ลูกค้าแจ้ง)
                    $filterdress = Reservationfilterdress::where('reservation_id', $orderdetail->reservation_id)->get();
                    foreach ($filterdress as $item) {
                        $updatefilterdress = Reservationfilterdress::find($item->id);
                        $updatefilterdress->status = 'คืนชุดแล้ว';
                        $updatefilterdress->status_completed = 1;
                        $updatefilterdress->save();
                    }
                    $update_dress = Dress::find($dress->id);
                    $update_dress->dress_rental = $update_dress->dress_rental + 1; //จำนวนครั้งที่ถูกเช่า
                    $update_dress->dress_status = 'สูญหาย';
                    $update_dress->save();

                    $after_return_dress = new Afterreturndress();
                    $after_return_dress->reservationfilterdress_id = $filterdress->first()->id;
                    $after_return_dress->type = 3;
                    $after_return_dress->price = $damage_insurance_separable_one;
                    $after_return_dress->save();
                    if ($damage_insurance_separable_one > 0) {
                        $create_additional = new AdditionalChange();
                        $create_additional->order_detail_id = $id;
                        $create_additional->charge_type = 1;
                        $create_additional->amount = $damage_insurance_separable_one;
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
                } elseif ($request->input('actionreturnitemtotaldress') == 'lost_unreported') {
                    // สูญหาย (ลูกค้าไม่แจ้ง
                    $filterdress = Reservationfilterdress::where('reservation_id', $orderdetail->reservation_id)->get();
                    foreach ($filterdress as $item) {
                        $updatefilterdress = Reservationfilterdress::find($item->id);
                        $updatefilterdress->status = 'คืนชุดแล้ว';
                        $updatefilterdress->status_completed = 1;
                        $updatefilterdress->save();
                    }
                    $update_dress = Dress::find($dress->id);
                    $update_dress->dress_rental = $update_dress->dress_rental + 1; //จำนวนครั้งที่ถูกเช่า
                    $update_dress->dress_status = 'สูญหาย';
                    $update_dress->save();
                    $after_return_dress = new Afterreturndress();
                    $after_return_dress->reservationfilterdress_id = $filterdress->first()->id;
                    $after_return_dress->type = 4;
                    $after_return_dress->price = $damage_insurance_separable_one;
                    $after_return_dress->save();
                    if ($damage_insurance_separable_one > 0) {
                        $create_additional = new AdditionalChange();
                        $create_additional->order_detail_id = $id;
                        $create_additional->charge_type = 1;
                        $create_additional->amount = $damage_insurance_separable_one;
                        $create_additional->save();
                    }
                } elseif ($request->input('actionreturnitemtotaldress') == 'damaged_beyond_repair') {
                    // เสียหายหนัก
                    $filterdress = Reservationfilterdress::where('reservation_id', $orderdetail->reservation_id)->get();
                    foreach ($filterdress as $item) {
                        $updatefilterdress = Reservationfilterdress::find($item->id);
                        $updatefilterdress->status = 'คืนชุดแล้ว';
                        $updatefilterdress->status_completed = 1;
                        $updatefilterdress->save();
                    }
                    $update_dress = Dress::find($dress->id);
                    $update_dress->dress_rental = $update_dress->dress_rental + 1; //จำนวนครั้งที่ถูกเช่า
                    $update_dress->dress_status = 'ยุติการให้เช่า';
                    $update_dress->save();
                    $after_return_dress = new Afterreturndress();
                    $after_return_dress->reservationfilterdress_id = $filterdress->first()->id;
                    $after_return_dress->type = 5;
                    $after_return_dress->price = $damage_insurance_separable_one;
                    $after_return_dress->save();
                    if ($damage_insurance_separable_one > 0) {
                        $create_additional = new AdditionalChange();
                        $create_additional->order_detail_id = $id;
                        $create_additional->charge_type = 1;
                        $create_additional->amount = $damage_insurance_separable_one;
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
                }
            } elseif ($dress->separable == 2) {
                if ($orderdetail->shirtitems_id != null) {

                    $damage_insurance_shirt = $request->input('damage_insurance_shirt');

                    if ($request->input('actionreturnitemshirt') == 'cleanitem') {
                        // สภาพปกติ ส่งทำความสะอาด
                        $filterdress = Reservationfilterdress::where('reservation_id', $orderdetail->reservation_id)->get();
                        foreach ($filterdress as $item) {
                            $updatefilterdress = Reservationfilterdress::find($item->id);
                            $updatefilterdress->status = 'รอทำความสะอาด';
                            $updatefilterdress->save();
                        }
                        //เช่าแค่เสื้อ
                        $update_shirt = Shirtitem::find($orderdetail->orderdetailmanytoonedress->shirtitems->first()->id);
                        $update_shirt->shirtitem_rental =  $update_shirt->shirtitem_rental  + 1;
                        $update_shirt->shirtitem_status = 'รอทำความสะอาด';
                        $update_shirt->save();

                        $after_return_dress = new Afterreturndress();
                        $after_return_dress->reservationfilterdress_id = $filterdress->first()->id;
                        $after_return_dress->type = 1;
                        $after_return_dress->price = $damage_insurance_shirt;
                        $after_return_dress->save();
                        if ($damage_insurance_shirt > 0) {
                            $create_additional = new AdditionalChange();
                            $create_additional->order_detail_id = $id;
                            $create_additional->charge_type = 1;
                            $create_additional->amount = $damage_insurance_shirt;
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
                    } elseif ($request->input('actionreturnitemshirt') == 'repairitem') {
                        // ต้องซ่อม
                        $filterdress = Reservationfilterdress::where('reservation_id', $orderdetail->reservation_id)->get();
                        foreach ($filterdress as $item) {
                            $updatefilterdress = Reservationfilterdress::find($item->id);
                            $updatefilterdress->status = 'รอดำเนินการซ่อม';
                            $updatefilterdress->save();
                        }

                        //เช่าแค่เสื้อ
                        $update_shirt = Shirtitem::find($orderdetail->orderdetailmanytoonedress->shirtitems->first()->id);
                        $update_shirt->shirtitem_rental =  $update_shirt->shirtitem_rental  + 1;
                        $update_shirt->shirtitem_status = 'รอดำเนินการซ่อม';
                        $update_shirt->save();


                        $FILTERDRESS_ID = Reservationfilterdress::where('reservation_id', $orderdetail->reservation_id)->value('id');
                        //ตารางreqpair 
                        $create_repair = new Repair();
                        $create_repair->reservationfilterdress_id = $FILTERDRESS_ID;
                        $create_repair->repair_description = $request->input('repair_detail_for_itemshirt');
                        $create_repair->repair_status = 'รอดำเนินการ';
                        $create_repair->repair_type = 1; //1.ยังไม่ได้ทำความสะอาด 2.ทำความสะอาดแล้ว
                        $create_repair->save();


                        $after_return_dress = new Afterreturndress();
                        $after_return_dress->reservationfilterdress_id = $filterdress->first()->id;
                        $after_return_dress->type = 2;
                        $after_return_dress->price = $damage_insurance_shirt;
                        $after_return_dress->save();
                        if ($damage_insurance_shirt > 0) {
                            $create_additional = new AdditionalChange();
                            $create_additional->order_detail_id = $id;
                            $create_additional->charge_type = 1;
                            $create_additional->amount = $damage_insurance_shirt;
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
                    } elseif ($request->input('actionreturnitemshirt') == 'lost') {
                        // สูญหาย (ลูกค้าแจ้ง)
                        $filterdress = Reservationfilterdress::where('reservation_id', $orderdetail->reservation_id)->get();
                        foreach ($filterdress as $item) {
                            $updatefilterdress = Reservationfilterdress::find($item->id);
                            $updatefilterdress->status = 'คืนชุดแล้ว';
                            $updatefilterdress->status_completed = 1;
                            $updatefilterdress->save();
                        }
                        //เช่าแค่เสื้อ
                        $update_shirt = Shirtitem::find($orderdetail->orderdetailmanytoonedress->shirtitems->first()->id);
                        $update_shirt->shirtitem_rental =  $update_shirt->shirtitem_rental  + 1;
                        $update_shirt->shirtitem_status = 'สูญหาย';
                        $update_shirt->save();
                        $after_return_dress = new Afterreturndress();
                        $after_return_dress->reservationfilterdress_id = $filterdress->first()->id;
                        $after_return_dress->type = 3;
                        $after_return_dress->price = $damage_insurance_shirt;
                        $after_return_dress->save();
                        if ($damage_insurance_shirt > 0) {
                            $create_additional = new AdditionalChange();
                            $create_additional->order_detail_id = $id;
                            $create_additional->charge_type = 1;
                            $create_additional->amount = $damage_insurance_shirt;
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
                    } elseif ($request->input('actionreturnitemshirt') == 'lost_unreported') {
                        // สูญหาย (ลูกค้าไม่แจ้ง)
                        $filterdress = Reservationfilterdress::where('reservation_id', $orderdetail->reservation_id)->get();
                        foreach ($filterdress as $item) {
                            $updatefilterdress = Reservationfilterdress::find($item->id);
                            $updatefilterdress->status = 'คืนชุดแล้ว';
                            $updatefilterdress->status_completed = 1;
                            $updatefilterdress->save();
                        }
                        //เช่าแค่เสื้อ
                        $update_shirt = Shirtitem::find($orderdetail->orderdetailmanytoonedress->shirtitems->first()->id);
                        $update_shirt->shirtitem_rental =  $update_shirt->shirtitem_rental  + 1;
                        $update_shirt->shirtitem_status = 'สูญหาย';
                        $update_shirt->save();
                        $after_return_dress = new Afterreturndress();
                        $after_return_dress->reservationfilterdress_id = $filterdress->first()->id;
                        $after_return_dress->type = 4;
                        $after_return_dress->price = $damage_insurance_shirt;
                        $after_return_dress->save();
                        if ($damage_insurance_shirt > 0) {
                            $create_additional = new AdditionalChange();
                            $create_additional->order_detail_id = $id;
                            $create_additional->charge_type = 1;
                            $create_additional->amount = $damage_insurance_shirt;
                            $create_additional->save();
                        }
                    } elseif ($request->input('actionreturnitemshirt') == 'damaged_beyond_repair') {
                        // เสียหายหนัก
                        $filterdress = Reservationfilterdress::where('reservation_id', $orderdetail->reservation_id)->get();
                        foreach ($filterdress as $item) {
                            $updatefilterdress = Reservationfilterdress::find($item->id);
                            $updatefilterdress->status = 'คืนชุดแล้ว';
                            $updatefilterdress->status_completed = 1;
                            $updatefilterdress->save();
                        }
                        //เช่าแค่เสื้อ
                        $update_shirt = Shirtitem::find($orderdetail->orderdetailmanytoonedress->shirtitems->first()->id);
                        $update_shirt->shirtitem_rental =  $update_shirt->shirtitem_rental  + 1;
                        $update_shirt->shirtitem_status = 'ยุติการให้เช่า';
                        $update_shirt->save();

                        $after_return_dress = new Afterreturndress();
                        $after_return_dress->reservationfilterdress_id = $filterdress->first()->id;
                        $after_return_dress->type = 5;
                        $after_return_dress->price = $damage_insurance_shirt;
                        $after_return_dress->save();
                        if ($damage_insurance_shirt > 0) {
                            $create_additional = new AdditionalChange();
                            $create_additional->order_detail_id = $id;
                            $create_additional->charge_type = 1;
                            $create_additional->amount = $damage_insurance_shirt;
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
                    }
                } elseif ($orderdetail->skirtitems_id != null) {

                    $damage_insurance_skirt = $request->input('damage_insurance_skirt');

                    if ($request->input('actionreturnitemskirt') == 'cleanitem') {
                        // สภาพปกติ
                        $filterdress = Reservationfilterdress::where('reservation_id', $orderdetail->reservation_id)->get();
                        foreach ($filterdress as $item) {
                            $updatefilterdress = Reservationfilterdress::find($item->id);
                            $updatefilterdress->status = 'รอทำความสะอาด';
                            $updatefilterdress->save();
                        }
                        //เช่าแค่ผ้าถุง
                        $update_skirt = Skirtitem::find($orderdetail->orderdetailmanytoonedress->skirtitems->first()->id);
                        $update_skirt->skirtitem_rental =  $update_skirt->skirtitem_rental  + 1;
                        $update_skirt->skirtitem_status = 'รอทำความสะอาด';
                        $update_skirt->save();


                        $after_return_dress = new Afterreturndress();
                        $after_return_dress->reservationfilterdress_id = $filterdress->first()->id;
                        $after_return_dress->type = 1;
                        $after_return_dress->price = $damage_insurance_skirt;
                        $after_return_dress->save();
                        if ($damage_insurance_skirt > 0) {
                            $create_additional = new AdditionalChange();
                            $create_additional->order_detail_id = $id;
                            $create_additional->charge_type = 1;
                            $create_additional->amount = $damage_insurance_skirt;
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
                    } elseif ($request->input('actionreturnitemskirt') == 'repairitem') {
                        // ต้องซ่อม
                        $filterdress = Reservationfilterdress::where('reservation_id', $orderdetail->reservation_id)->get();
                        foreach ($filterdress as $item) {
                            $updatefilterdress = Reservationfilterdress::find($item->id);
                            $updatefilterdress->status = 'รอดำเนินการซ่อม';
                            $updatefilterdress->save();
                        }

                        //เช่าแค่ผ้าถุง
                        $update_skirt = Skirtitem::find($orderdetail->orderdetailmanytoonedress->skirtitems->first()->id);
                        $update_skirt->skirtitem_rental =  $update_skirt->skirtitem_rental  + 1;
                        $update_skirt->skirtitem_status = 'รอดำเนินการซ่อม';
                        $update_skirt->save();


                        $FILTERDRESS_ID = Reservationfilterdress::where('reservation_id', $orderdetail->reservation_id)->value('id');
                        //ตารางreqpair 
                        $create_repair = new Repair();
                        $create_repair->reservationfilterdress_id = $FILTERDRESS_ID;
                        $create_repair->repair_description = $request->input('repair_detail_for_itemskirt');
                        $create_repair->repair_status = 'รอดำเนินการ';
                        $create_repair->repair_type = 1; //1.ยังไม่ได้ทำความสะอาด 2.ทำความสะอาดแล้ว
                        $create_repair->save();
                        $after_return_dress = new Afterreturndress();
                        $after_return_dress->reservationfilterdress_id = $filterdress->first()->id;
                        $after_return_dress->type = 2;
                        $after_return_dress->price = $damage_insurance_skirt;
                        $after_return_dress->save();
                        if ($damage_insurance_skirt > 0) {
                            $create_additional = new AdditionalChange();
                            $create_additional->order_detail_id = $id;
                            $create_additional->charge_type = 1;
                            $create_additional->amount = $damage_insurance_skirt;
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
                    } elseif ($request->input('actionreturnitemskirt') == 'lost') {
                        // สูญหาย (ลูกค้าแจ้ง)
                        $filterdress = Reservationfilterdress::where('reservation_id', $orderdetail->reservation_id)->get();
                        foreach ($filterdress as $item) {
                            $updatefilterdress = Reservationfilterdress::find($item->id);
                            $updatefilterdress->status = 'คืนชุดแล้ว';
                            $updatefilterdress->status_completed = 1;
                            $updatefilterdress->save();
                        }
                        //เช่าแค่ผ้าถุง
                        $update_skirt = Skirtitem::find($orderdetail->orderdetailmanytoonedress->skirtitems->first()->id);
                        $update_skirt->skirtitem_rental =  $update_skirt->skirtitem_rental  + 1;
                        $update_skirt->skirtitem_status = 'สูญหาย';
                        $update_skirt->save();
                        $after_return_dress = new Afterreturndress();
                        $after_return_dress->reservationfilterdress_id = $filterdress->first()->id;
                        $after_return_dress->type = 3;
                        $after_return_dress->price = $damage_insurance_skirt;
                        $after_return_dress->save();
                        if ($damage_insurance_skirt > 0) {
                            $create_additional = new AdditionalChange();
                            $create_additional->order_detail_id = $id;
                            $create_additional->charge_type = 1;
                            $create_additional->amount = $damage_insurance_skirt;
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
                    } elseif ($request->input('actionreturnitemskirt') == 'lost_unreported') {
                        // สูญหาย (ลูกค้าไม่แจ้ง)
                        $filterdress = Reservationfilterdress::where('reservation_id', $orderdetail->reservation_id)->get();
                        foreach ($filterdress as $item) {
                            $updatefilterdress = Reservationfilterdress::find($item->id);
                            $updatefilterdress->status = 'คืนชุดแล้ว';
                            $updatefilterdress->status_completed = 1;
                            $updatefilterdress->save();
                        }
                        //เช่าแค่ผ้าถุง
                        $update_skirt = Skirtitem::find($orderdetail->orderdetailmanytoonedress->skirtitems->first()->id);
                        $update_skirt->skirtitem_rental =  $update_skirt->skirtitem_rental  + 1;
                        $update_skirt->skirtitem_status = 'สูญหาย';
                        $update_skirt->save();
                        $after_return_dress = new Afterreturndress();
                        $after_return_dress->reservationfilterdress_id = $filterdress->first()->id;
                        $after_return_dress->type = 4;
                        $after_return_dress->price = $damage_insurance_skirt;
                        $after_return_dress->save();
                        if ($damage_insurance_skirt > 0) {
                            $create_additional = new AdditionalChange();
                            $create_additional->order_detail_id = $id;
                            $create_additional->charge_type = 1;
                            $create_additional->amount = $damage_insurance_skirt;
                            $create_additional->save();
                        }
                    } elseif ($request->input('actionreturnitemskirt') == 'damaged_beyond_repair') {
                        // เสียหายหนัก
                        $filterdress = Reservationfilterdress::where('reservation_id', $orderdetail->reservation_id)->get();
                        foreach ($filterdress as $item) {
                            $updatefilterdress = Reservationfilterdress::find($item->id);
                            $updatefilterdress->status = 'คืนชุดแล้ว';
                            $updatefilterdress->status_completed = 1;
                            $updatefilterdress->save();
                        }
                        //เช่าแค่ผ้าถุง
                        $update_skirt = Skirtitem::find($orderdetail->orderdetailmanytoonedress->skirtitems->first()->id);
                        $update_skirt->skirtitem_rental =  $update_skirt->skirtitem_rental  + 1;
                        $update_skirt->skirtitem_status = 'ยุติการให้เช่า';
                        $update_skirt->save();
                        $after_return_dress = new Afterreturndress();
                        $after_return_dress->reservationfilterdress_id = $filterdress->first()->id;
                        $after_return_dress->type = 5;
                        $after_return_dress->price = $damage_insurance_skirt;
                        $after_return_dress->save();
                        if ($damage_insurance_skirt > 0) {
                            $create_additional = new AdditionalChange();
                            $create_additional->order_detail_id = $id;
                            $create_additional->charge_type = 1;
                            $create_additional->amount = $damage_insurance_skirt;
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
                    }
                } elseif ($orderdetail->skirtitems_id == null &&  $orderdetail->shirtitems_id == null) {
                    //เช่าทั้งชุด
                    // +1ชุด
                    $update_dress = Dress::find($dress->id);
                    $update_dress->dress_rental = $update_dress->dress_rental + 1; //จำนวนครั้งที่ถูกเช่า
                    $update_dress->save();



                    // เสื้อ
                    $filtershirt_id = $request->input('filtershirt_id');
                    $filterdress_id = Reservationfilterdress::find($filtershirt_id);
                    $damage_insurance_shirt_two = $request->input('damage_insurance_shirt_two');
                    if ($request->input('actionreturnitemtotal1') == 'cleanitem') {
                        // สภาพปกติ
                        $filtershirt = Reservationfilterdress::find($filtershirt_id);
                        $filtershirt->status = 'รอทำความสะอาด';
                        $filtershirt->save();

                        //เช่าแค่เสื้อ
                        $update_shirt = Shirtitem::find($orderdetail->orderdetailmanytoonedress->shirtitems->first()->id);
                        $update_shirt->shirtitem_rental =  $update_shirt->shirtitem_rental  + 1;
                        $update_shirt->shirtitem_status = 'รอทำความสะอาด';
                        $update_shirt->save();
                        $after_return_dress = new Afterreturndress();
                        $after_return_dress->reservationfilterdress_id = $filterdress_id->id;
                        $after_return_dress->type = 1;
                        $after_return_dress->price = $damage_insurance_shirt_two;
                        $after_return_dress->save();
                    } elseif ($request->input('actionreturnitemtotal1') == 'repairitem') {
                        // ต้องซ่อม
                        $filtershirt = Reservationfilterdress::find($filtershirt_id);
                        $filtershirt->status = 'รอดำเนินการซ่อม';
                        $filtershirt->save();
                        //เช่าแค่เสื้อ
                        $update_shirt = Shirtitem::find($orderdetail->orderdetailmanytoonedress->shirtitems->first()->id);
                        $update_shirt->shirtitem_rental =  $update_shirt->shirtitem_rental  + 1;
                        $update_shirt->shirtitem_status = 'รอดำเนินการซ่อม';
                        $update_shirt->save();

                        $FILTERDRESS_ID = Reservationfilterdress::where('reservation_id', $orderdetail->reservation_id)->value('id');
                        //ตารางreqpair 
                        $create_repair = new Repair();
                        $create_repair->reservationfilterdress_id = $filtershirt_id;
                        $create_repair->repair_description = $request->input('repair_detail_for_item1');
                        $create_repair->repair_status = 'รอดำเนินการ';
                        $create_repair->repair_type = 1; //1.ยังไม่ได้ทำความสะอาด 2.ทำความสะอาดแล้ว
                        $create_repair->save();
                        $after_return_dress = new Afterreturndress();
                        $after_return_dress->reservationfilterdress_id = $filterdress_id->id;
                        $after_return_dress->type = 2;
                        $after_return_dress->price = $damage_insurance_shirt_two;
                        $after_return_dress->save();
                    } elseif ($request->input('actionreturnitemtotal1') == 'lost') {
                        // สูญหาย (ลูกค้าแจ้ง)
                        $filtershirt = Reservationfilterdress::find($filtershirt_id);
                        $filtershirt->status = 'คืนชุดแล้ว';
                        $filtershirt->status_completed = 1;
                        $filtershirt->save();
                        //เช่าแค่เสื้อ
                        $update_shirt = Shirtitem::find($orderdetail->orderdetailmanytoonedress->shirtitems->first()->id);
                        $update_shirt->shirtitem_rental =  $update_shirt->shirtitem_rental  + 1;
                        $update_shirt->shirtitem_status = 'สูญหาย';
                        $update_shirt->save();
                        $after_return_dress = new Afterreturndress();
                        $after_return_dress->reservationfilterdress_id = $filterdress_id->id;
                        $after_return_dress->type = 3;
                        $after_return_dress->price = $damage_insurance_shirt_two;
                        $after_return_dress->save();
                    } elseif ($request->input('actionreturnitemtotal1') == 'lost_unreported') {
                        // สูญหาย (ลูกค้าไม่แจ้ง)
                        $filtershirt = Reservationfilterdress::find($filtershirt_id);
                        $filtershirt->status = 'คืนชุดแล้ว';
                        $filtershirt->status_completed = 1;
                        $filtershirt->save();
                        //เช่าแค่เสื้อ
                        $update_shirt = Shirtitem::find($orderdetail->orderdetailmanytoonedress->shirtitems->first()->id);
                        $update_shirt->shirtitem_rental =  $update_shirt->shirtitem_rental  + 1;
                        $update_shirt->shirtitem_status = 'สูญหาย';
                        $update_shirt->save();
                        $after_return_dress = new Afterreturndress();
                        $after_return_dress->reservationfilterdress_id = $filterdress_id->id;
                        $after_return_dress->type = 4;
                        $after_return_dress->price = $damage_insurance_shirt_two;
                        $after_return_dress->save();
                    } elseif ($request->input('actionreturnitemtotal1') == 'damaged_beyond_repair') {
                        // เสียหายหนัก
                        $filtershirt = Reservationfilterdress::find($filtershirt_id);
                        $filtershirt->status = 'คืนชุดแล้ว';
                        $filtershirt->status_completed = 1;
                        $filtershirt->save();
                        //เช่าแค่เสื้อ
                        $update_shirt = Shirtitem::find($orderdetail->orderdetailmanytoonedress->shirtitems->first()->id);
                        $update_shirt->shirtitem_rental =  $update_shirt->shirtitem_rental  + 1;
                        $update_shirt->shirtitem_status = 'ยุติการให้เช่า';
                        $update_shirt->save();
                        $after_return_dress = new Afterreturndress();
                        $after_return_dress->reservationfilterdress_id = $filterdress_id->id;
                        $after_return_dress->type = 5;
                        $after_return_dress->price = $damage_insurance_shirt_two;
                        $after_return_dress->save();
                    }





                    // ผ้าถุง
                    $filterskirt_id = $request->input('filterskirt_id');
                    $filterdress_id_skirt = Reservationfilterdress::find($filterskirt_id);
                    $damage_insurance_skirt_two = $request->input('damage_insurance_skirt_two');
                    if ($request->input('actionreturnitemtotal2') == 'cleanitem') {
                        // สภาพปกติ
                        $filtershirt = Reservationfilterdress::find($filterskirt_id);
                        $filtershirt->status = 'รอทำความสะอาด';
                        $filtershirt->save();
                        //เช่าแค่ผ้าถุง
                        $update_skirt = Skirtitem::find($orderdetail->orderdetailmanytoonedress->skirtitems->first()->id);
                        $update_skirt->skirtitem_rental =  $update_skirt->skirtitem_rental  + 1;
                        $update_skirt->skirtitem_status = 'รอทำความสะอาด';
                        $update_skirt->save();
                        $after_return_dress = new Afterreturndress();
                        $after_return_dress->reservationfilterdress_id = $filterdress_id_skirt->id;
                        $after_return_dress->type = 1;
                        $after_return_dress->price = $damage_insurance_skirt_two;
                        $after_return_dress->save();
                    } elseif ($request->input('actionreturnitemtotal2') == 'repairitem') {
                        // ต้องซ่อม
                        $filtershirt = Reservationfilterdress::find($filterskirt_id);
                        $filtershirt->status = 'รอดำเนินการซ่อม';
                        $filtershirt->save();
                        //เช่าแค่ผ้าถุง
                        $update_skirt = Skirtitem::find($orderdetail->orderdetailmanytoonedress->skirtitems->first()->id);
                        $update_skirt->skirtitem_rental =  $update_skirt->skirtitem_rental  + 1;
                        $update_skirt->skirtitem_status = 'รอดำเนินการซ่อม';
                        $update_skirt->save();

                        $FILTERDRESS_ID = Reservationfilterdress::where('reservation_id', $orderdetail->reservation_id)->value('id');
                        //ตารางreqpair 
                        $create_repair = new Repair();
                        $create_repair->reservationfilterdress_id = $filterskirt_id;
                        $create_repair->repair_description = $request->input('repair_detail_for_item2');
                        $create_repair->repair_status = 'รอดำเนินการ';
                        $create_repair->repair_type = 1; //1.ยังไม่ได้ทำความสะอาด 2.ทำความสะอาดแล้ว
                        $create_repair->save();
                        $after_return_dress = new Afterreturndress();
                        $after_return_dress->reservationfilterdress_id = $filterdress_id_skirt->id;
                        $after_return_dress->type = 2;
                        $after_return_dress->price = $damage_insurance_skirt_two;
                        $after_return_dress->save();
                    } elseif ($request->input('actionreturnitemtotal2') == 'lost') {
                        // สูญหาย (ลูกค้าแจ้ง)
                        $filtershirt = Reservationfilterdress::find($filterskirt_id);
                        $filtershirt->status = 'คืนชุดแล้ว';
                        $filtershirt->status_completed = 1;
                        $filtershirt->save();
                        //เช่าแค่ผ้าถุง
                        $update_skirt = Skirtitem::find($orderdetail->orderdetailmanytoonedress->skirtitems->first()->id);
                        $update_skirt->skirtitem_rental =  $update_skirt->skirtitem_rental  + 1;
                        $update_skirt->skirtitem_status = 'สูญหาย';
                        $update_skirt->save();
                        $after_return_dress = new Afterreturndress();
                        $after_return_dress->reservationfilterdress_id = $filterdress_id_skirt->id;
                        $after_return_dress->type = 3;
                        $after_return_dress->price = $damage_insurance_skirt_two;
                        $after_return_dress->save();
                    } elseif ($request->input('actionreturnitemtotal2') == 'lost_unreported') {
                        // สูญหาย (ลูกค้าไม่แจ้ง)
                        $filtershirt = Reservationfilterdress::find($filterskirt_id);
                        $filtershirt->status = 'คืนชุดแล้ว';
                        $filtershirt->status_completed = 1;
                        $filtershirt->save();
                        //เช่าแค่ผ้าถุง
                        $update_skirt = Skirtitem::find($orderdetail->orderdetailmanytoonedress->skirtitems->first()->id);
                        $update_skirt->skirtitem_rental =  $update_skirt->skirtitem_rental  + 1;
                        $update_skirt->skirtitem_status = 'สูญหาย';
                        $update_skirt->save();
                        $after_return_dress = new Afterreturndress();
                        $after_return_dress->reservationfilterdress_id = $filterdress_id_skirt->id;
                        $after_return_dress->type = 4;
                        $after_return_dress->price = $damage_insurance_skirt_two;
                        $after_return_dress->save();
                    } elseif ($request->input('actionreturnitemtotal2') == 'damaged_beyond_repair') {
                        // เสียหายหนัก
                        $filtershirt = Reservationfilterdress::find($filterskirt_id);
                        $filtershirt->status = 'คืนชุดแล้ว';
                        $filtershirt->status_completed = 1;
                        $filtershirt->save();
                        //เช่าแค่ผ้าถุง
                        $update_skirt = Skirtitem::find($orderdetail->orderdetailmanytoonedress->skirtitems->first()->id);
                        $update_skirt->skirtitem_rental =  $update_skirt->skirtitem_rental  + 1;
                        $update_skirt->skirtitem_status = 'ยุติการให้เช่า';
                        $update_skirt->save();
                        $after_return_dress = new Afterreturndress();
                        $after_return_dress->reservationfilterdress_id = $filterdress_id_skirt->id;
                        $after_return_dress->type = 5;
                        $after_return_dress->price = $damage_insurance_skirt_two;
                        $after_return_dress->save();
                    }

                    $damage_insurance_shirt_skirt = $damage_insurance_shirt_two + $damage_insurance_skirt_two;

                    if ($damage_insurance_shirt_skirt > 0) {
                        $create_additional = new AdditionalChange();
                        $create_additional->order_detail_id = $id;
                        $create_additional->charge_type = 1;
                        $create_additional->amount = $damage_insurance_shirt_skirt;
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
                }
            }


            //ตารางreservation 
            $reservation = Reservation::find($orderdetail->reservation_id);
            $reservation->status = "คืนชุดแล้ว";
            $reservation->status_completed = 1;
            $reservation->save();
            $message_session = 'ลูกค้าคืนชุดแล้ว';



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
                } elseif ($item->type_order == 4) {
                    if ($item->status_detail == 'คืนชุดแล้ว') {
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
                $ceate_receipt->employee_id = Auth::user()->id;
                $ceate_receipt->receipt_type = 3;
                $ceate_receipt->total_price = $data_orderdetail_sum_damage_insurance - $additional_total;
                $ceate_receipt->save();
            }
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
                // เช็คเงินใบเสร็จ
                $price_receipt_total = $orderdetail->price - $orderdetail->deposit;
                //ตารางpaymentstatus
                $create_paymentstatus = new Paymentstatus();
                $create_paymentstatus->order_detail_id = $id;
                $create_paymentstatus->payment_status = 2;
                $create_paymentstatus->save();
                //ตารางorderdetail
                $orderdetail->status_payment = 2; //1จ่ายมัดจำ 2จ่ายเต็มจำนวน
                $orderdetail->save();
            } else {
                // เช็คเงินใบเสร็จ
                $price_receipt_total = 0;
            }
            $message_session = 'ส่งมอบชุดสำเร็จ';


            // สร้างใบเสร็จ
            $ceate_receipt = new Receipt();
            $ceate_receipt->order_id = $orderdetail->order_id;
            $ceate_receipt->order_detail_id = $orderdetail->id;
            $ceate_receipt->receipt_type = 2;
            $ceate_receipt->total_price = $price_receipt_total;
            $ceate_receipt->employee_id = Auth::user()->id;
            $ceate_receipt->save();
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
                $sum_price_receipt_total = $orderdetail->price - $orderdetail->deposit;
                $create_paymentstatus = new Paymentstatus();
                $create_paymentstatus->order_detail_id = $id;
                $create_paymentstatus->payment_status = 2;
                $create_paymentstatus->save();
                //ตารางorderdetail
                $orderdetail->status_payment = 2; //1จ่ายมัดจำ 2จ่ายเต็มจำนวน
                $orderdetail->save();
            } else {
                $sum_price_receipt_total = 0;
            }

            // ตารางdate อัปเดตวันที่รับชุดจริง
            $date_id = Date::where('order_detail_id', $id)
                ->orderBy('created_at', 'desc')
                ->value('id');
            $update_date = Date::find($date_id);
            $update_date->actua_pickup_date = now();
            $update_date->save();
            $message_session = 'ส่งมอบชุดสำเร็จ';


            // เช็คสิว่ามันมีค่า dec ไหม 
            $decoration_receipt = Decoration::where('order_detail_id', $id)->sum('decoration_price');
            // สร้างใบเสร็จ
            $ceate_receipt = new Receipt();
            $ceate_receipt->order_id = $orderdetail->order_id;
            $ceate_receipt->order_detail_id = $orderdetail->id;
            $ceate_receipt->receipt_type = 2;
            $ceate_receipt->total_price = $sum_price_receipt_total + $decoration_receipt;
            $ceate_receipt->employee_id = Auth::user()->id;
            $ceate_receipt->save();
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


        $dress_stage = Dress::where('type_dress_id', $type_id)
            ->where('dress_price', '!=', 0)
            ->get();

        $list_first_stage = [];
        // 1 2 3 4 5 6 7 8 9 10
        foreach ($dress_stage as $itemer) {
            $is_passner = true;

            if ($itemer->separable == 1) {
                if ($itemer->dress_status == 'ยุติการให้เช่า' || $itemer->dress_status == 'สูญหาย') {
                    $is_passner = false;
                }
            } elseif ($itemer->separable == 2) {
                $shi_id = Shirtitem::where('dress_id', $itemer->id)->value('id');
                $ski_id = Skirtitem::where('dress_id', $itemer->id)->value('id');

                $shi = Shirtitem::find($shi_id);
                $ski = Skirtitem::find($ski_id);

                if ($shi->shirtitem_status == 'ยุติการให้เช่า' || $shi->shirtitem_status == 'สูญหาย') {
                    $is_passner = false;
                }
                if ($ski->skirtitem_status == 'ยุติการให้เช่า' || $ski->skirtitem_status == 'สูญหาย') {
                    $is_passner = false;
                }
            }

            if ($is_passner) {
                $list_first_stage[] = $itemer->id;
            }
        }





        $dress = Dress::whereIn('id', $list_first_stage)->get();


        $fil_start_7 = $star_date_filter->copy()->subDays(7); //ถอยไป 7 วัน
        $fil_end_7 = $end_date_filter->copy()->addDays(7); // เพิ่มไป 7 วัน 


        $list_pass_dress_id = [];
        foreach ($dress as $index) {
            // แยกเช่าไม่ได้
            if ($index->separable == 1) {
                $reservation = Reservationfilterdress::where('dress_id', $index->id)
                    ->where('status_completed', 0)
                    ->get();
                $validate_pass = true;
                if ($reservation->isNotempty()) {
                    foreach ($reservation as $re) {
                        $re_start = Carbon::parse($re->start_date);
                        $re_end  = Carbon::parse($re->end_date);

                        if ($re_start->between($fil_start_7, $fil_end_7) || $re_end->between($fil_start_7, $fil_end_7)) {
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

                $fil_start_7 = $star_date_filter->copy()->subDays(7); //ถอยไป 7 วัน
                $fil_end_7 = $end_date_filter->copy()->addDays(7); // เพิ่มไป 7 วัน

                // เช็คแค่เฉพาะเช่าทั้งชุดก่อน เสื้อและผ้าถุงไม่เกี่ยว 
                // $reservation_dress = Reservation::where('dress_id', $index->id)
                //     ->whereNull('shirtitems_id')
                //     ->whereNull('skirtitems_id')
                //     ->where('status_completed', 0)
                //     ->get();
                // if ($reservation_dress->isNotempty()) {
                //     foreach ($reservation_dress as $re) {
                //         $re_start = Carbon::parse($re->start_date);
                //         $re_end  = Carbon::parse($re->end_date);

                //         if ($re_start->between($fil_start_7, $fil_end_7) || $re_end->between($fil_start_7, $fil_end_7)) {
                //             $validate_pass = false;
                //             break;
                //         }
                //     }
                // }

                //เช็คเฉพาะเสื้อเท่านั้น
                $shirt_id = Shirtitem::where('dress_id', $index->id)->value('id');
                $reservation_shirt = Reservationfilterdress::where('shirtitems_id', $shirt_id)
                    ->where('status_completed', 0)
                    ->get();
                if ($reservation_shirt->isNotempty()) {
                    foreach ($reservation_shirt as $re) {
                        $re_start = Carbon::parse($re->start_date);
                        $re_end  = Carbon::parse($re->end_date);

                        if ($re_start->between($fil_start_7, $fil_end_7) || $re_end->between($fil_start_7, $fil_end_7)) {
                            $validate_pass = false;
                        }
                    }
                }


                //เช็คเฉพาะผ้าถุงเท่านั้น
                $skirt_id = Skirtitem::where('dress_id', $index->id)->value('id');
                $reservation_skirt = Reservationfilterdress::where('skirtitems_id', $skirt_id)
                    ->where('status_completed', 0)
                    ->get();
                if ($reservation_skirt->isNotempty()) {
                    foreach ($reservation_skirt as $re) {
                        $re_start = Carbon::parse($re->start_date);
                        $re_end  = Carbon::parse($re->end_date);

                        if ($re_start->between($fil_start_7, $fil_end_7) || $re_end->between($fil_start_7, $fil_end_7)) {
                            $validate_pass = false;
                        }
                    }
                }
                // สุดท้ายแล้วอะ   เฉพาะเสื้อ เฉพาะผ้าถุง ถ้า$validate_pass มันยังเป็น true นั้นแปลว่า มันผ่าน
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
        $dress_stage = Dress::where('type_dress_id', $type_id)
            ->where('dress_price', '!=', 0)
            ->where('separable', 2)
            ->get();

        $list_first_stage = [];
        // 1 2 3 4 5 6 7 8 9 10
        foreach ($dress_stage as $itemer) {
            $is_passner = true;
            $shi_id = Shirtitem::where('dress_id', $itemer->id)->value('id');
            $shi = Shirtitem::find($shi_id);
            if ($shi->shirtitem_status == 'ยุติการให้เช่า' || $shi->shirtitem_status == 'สูญหาย') {
                $is_passner = false;
            }
            if ($is_passner) {
                $list_first_stage[] = $itemer->id;
            }
        }
        $dress = Dress::whereIn('id', $list_first_stage)->get();
        $fil_start_7 = $star_date_filter->copy()->subDays(7); //ถอยหลังไป 7 วัน
        $fil_start_1 = $star_date_filter->copy()->subDays(1);
        $fil_be_start = $star_date_filter->copy();
        $fil_be_end = $end_date_filter->copy();
        $fil_end_1 = $end_date_filter->copy()->addDays(1);
        $fil_end_7 = $end_date_filter->copy()->addDays(7); // เพิ่มขึ้นไป 7 วัน

        $list_pass_dress_id = [];

        foreach ($dress as $index) {

            $validate_pass = true;
            //เช็คเฉพาะเสื้อเท่านั้น
            $shirt_id = Shirtitem::where('dress_id', $index->id)->value('id');
            $reservation_shirt = Reservationfilterdress::where('shirtitems_id', $shirt_id)
                ->where('status_completed', 0)
                ->get();
            if ($reservation_shirt->isNotempty()) {
                foreach ($reservation_shirt as $re) {
                    $re_start = Carbon::parse($re->start_date);
                    $re_end  = Carbon::parse($re->end_date);
                    if ($re_start->between($fil_start_7, $fil_end_7) || $re_end->between($fil_start_7, $fil_end_7)) {
                        $validate_pass = false;
                    }
                }
            }
            // เช็คแค่เฉพาะเช่าทั้งชุดเท่านั้น
            // $reservation_dress = Reservation::where('dress_id', $index->id)
            //     ->whereNull('shirtitems_id')
            //     ->whereNull('skirtitems_id')
            //     ->where('status_completed', 0)
            //     ->get();
            // if ($reservation_dress->isNotempty()) {
            //     foreach ($reservation_dress as $re) {
            //         $re_start = Carbon::parse($re->start_date);
            //         $re_end  = Carbon::parse($re->end_date);
            //         if ($re_start->between($fil_start_7, $fil_end_7) || $re_end->between($fil_start_7, $fil_end_7)) {
            //             $validate_pass = false;
            //             break;
            //         }
            //     }
            // }

            // สุดท้ายแล้ว ถ้าเช็คว่า เช่าเฉพาะเสื้อ  และ เช่าเฉพาะทั้งชุด มันผ่านเงื่อนไขก็ผ่าน
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
        $dress_stage = Dress::where('type_dress_id', $type_id)
            ->where('dress_price', '!=', 0)
            ->where('separable', 2)
            ->get();

        $list_first_stage = [];
        // 1 2 3 4 5 6 7 8 9 10
        foreach ($dress_stage as $itemer) {
            $is_passner = true;


            $ski_id = Skirtitem::where('dress_id', $itemer->id)->value('id');

            $ski = Skirtitem::find($ski_id);


            if ($ski->skirtitem_status == 'ยุติการให้เช่า' || $ski->skirtitem_status == 'สูญหาย') {
                $is_passner = false;
            }


            if ($is_passner) {
                $list_first_stage[] = $itemer->id;
            }
        }





        $dress = Dress::whereIn('id', $list_first_stage)->get();




        $fil_start_7 = $star_date_filter->copy()->subDays(7); //ถอยไป 7 วัน
        $fil_start_1 = $star_date_filter->copy()->subDays(1);
        $fil_be_start = $star_date_filter->copy();
        $fil_be_end = $end_date_filter->copy();
        $fil_end_1 = $end_date_filter->copy()->addDays(1);
        $fil_end_7 = $end_date_filter->copy()->addDays(7); // เพิ่มขึ้น 7 วัน
        $list_pass_dress_id = [];

        foreach ($dress as $index) {
            $validate_pass = true;
            //เช็คเฉพาะผ้าถุงเท่านั้น
            $skirt_id = Skirtitem::where('dress_id', $index->id)->value('id');
            $reservation_skirt = Reservationfilterdress::where('skirtitems_id', $skirt_id)
                ->where('status_completed', 0)
                ->get();
            if ($reservation_skirt->isNotempty()) {
                foreach ($reservation_skirt as $re) {
                    $re_start = Carbon::parse($re->start_date);
                    $re_end  = Carbon::parse($re->end_date);

                    if ($re_start->between($fil_start_7, $fil_end_7) || $re_end->between($fil_start_7, $fil_end_7)) {
                        $validate_pass = false;
                    }
                }
            }
            // เช็คแค่เฉพาะเช่าทั้งชุดเท่านั้น
            // $reservation_dress = Reservation::where('dress_id', $index->id)
            //     ->whereNull('shirtitems_id')
            //     ->whereNull('skirtitems_id')
            //     ->where('status_completed', 0)
            //     ->get();
            // if ($reservation_dress->isNotempty()) {
            //     foreach ($reservation_dress as $re) {
            //         $re_start = Carbon::parse($re->start_date);
            //         $re_end  = Carbon::parse($re->end_date);

            //         if ($re_start->between($fil_start_7, $fil_end_7) || $re_end->between($fil_start_7, $fil_end_7)) {
            //             $validate_pass = false;
            //             break;
            //         }
            //     }
            // }
            // สุดท้ายแล้ว พอเช็คเช่าเฉพาะทั้งชุด และ เช่าเฉพาะผ้าถุงอะ  แปลว่าถ้ามัยังเป็นจริงก็มันผ่านเงื่อนไขทั้งหมดแล้ว
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

            if ($data_dress->separable == 1) {
                $filter = new Reservationfilterdress();
                $filter->dress_id = $dress_id;
                $filter->start_date = $pickupdate;
                $filter->end_date = $returndate;
                $filter->status = 'อยู่ในตะกร้า';
                $filter->status_completed = 0; //0 คือ ยังไม่เสด 1 คือเสร็จแล้ว
                $filter->reservation_id = $reservation->id;
                $filter->save();
            } elseif ($data_dress->separable == 2) {

                $shirt_ID = Shirtitem::where('dress_id', $dress_id)->value('id');
                $skirt_ID = Skirtitem::where('dress_id', $dress_id)->value('id');
                $filter = new Reservationfilterdress();
                $filter->dress_id = $dress_id;
                $filter->shirtitems_id = $shirt_ID;
                $filter->start_date = $pickupdate;
                $filter->end_date = $returndate;
                $filter->status = 'อยู่ในตะกร้า';
                $filter->status_completed = 0; //0 คือ ยังไม่เสด 1 คือเสร็จแล้ว
                $filter->reservation_id = $reservation->id;
                $filter->save();

                $filter = new Reservationfilterdress();
                $filter->dress_id = $dress_id;
                $filter->skirtitems_id = $skirt_ID;
                $filter->start_date = $pickupdate;
                $filter->end_date = $returndate;
                $filter->status = 'อยู่ในตะกร้า';
                $filter->status_completed = 0; //0 คือ ยังไม่เสด 1 คือเสร็จแล้ว
                $filter->reservation_id = $reservation->id;
                $filter->save();
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
                $create_order->type_order = 2; //1.คือตัด 2.เช่า 3.เช่าตัด
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

            // ตาราง filterdress
            $filterdress = new Reservationfilterdress();
            $filterdress->dress_id = $dress_id;
            $filterdress->shirtitems_id = $shirt_id;
            $filterdress->start_date = $pickupdate;
            $filterdress->end_date = $returndate;
            $filterdress->status = 'อยู่ในตะกร้า';
            $filterdress->status_completed = 0; //0 คือ ยังไม่เสด 1 คือเสร็จแล้ว
            $filterdress->reservation_id = $reservation->id;
            $filterdress->save();


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
                $create_order->type_order = 2; //1.คือตัด 2.เช่า 3.เช่าตัด
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

            // ตาราง filterdress
            $filterdress = new Reservationfilterdress();
            $filterdress->dress_id = $dress_id;
            $filterdress->skirtitems_id = $skirt_id;
            $filterdress->start_date = $pickupdate;
            $filterdress->end_date = $returndate;
            $filterdress->status = 'อยู่ในตะกร้า';
            $filterdress->status_completed = 0; //0 คือ ยังไม่เสด 1 คือเสร็จแล้ว
            $filterdress->reservation_id = $reservation->id;
            $filterdress->save();







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
                $create_order->type_order = 2; //1.คือตัด 2.เช่า 3.เช่าตัด
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
