<?php

namespace App\Http\Controllers;

use App\Models\Date;
use App\Models\Dress;
use App\Models\Financial;
use App\Models\Fitting;
use App\Models\Imagerent;
use App\Models\Measurementorderdetail;
use App\Models\Order;
use App\Models\Orderdetail;
use App\Models\Orderdetailstatus;
use App\Models\Paymentstatus;
use App\Models\Typejewelry;
use App\Models\Dressmeasurement;
use App\Models\Typedress;
use App\Models\Jewelry;
use App\Models\Dressmeaadjustment;
use App\Models\Dressmea;
use App\Models\Shirtitem;
use App\Models\Skirtitem;
use App\Models\Decoration;
use App\Models\Reservation;
use App\Models\Reservationfilters;
use App\Models\Jewelrysetitem;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManageorderController extends Controller
{

    public function selectdatesuccess(Request $request)
    {
        $startDate = $request->input('startDate'); //วันที่รับชุด
        $endDate = $request->input('endDate'); //วันที่คืนชุด
        $totalDay = $request->input('totalDay');
        $typedress = Typedress::all();
        return view('employee.typerentdress', compact('typedress', 'startDate', 'endDate', 'totalDay'));
    }








    //ค้นหา อก เอว สะโพก
    public function filtermea(Request $request)
    {

        $chest = $request->input('chest'); //อก
        $waist = $request->input('waist');
        $hip = $request->input('hip');
        $type_dress_id = $request->input('type_dress_id');
        $search_separable = $request->input('search_separable');

        $id = $request->input('type_dress_id');
        $type_dress_name = Typedress::where('id', $id)->select('type_dress_name', 'specific_letter')->first();


        $condition_mea = Dressmeasurement::query();
        if ($chest) {
            $condition_mea->where('measurement_dress_name', 'รอบอก')
                ->whereBetween('measurement_dress_number', [$chest - 4, $chest + 4])
                ->where('measurement_dress_unit', 'นิ้ว');
        }
        if ($waist) {
            $condition_mea->Where('measurement_dress_name', 'รอบเอว')
                ->whereBetween('measurement_dress_number', [$waist - 4, $waist + 4])
                ->where('measurement_dress_unit', 'นิ้ว');
        }
        if ($hip) {
            $condition_mea->Where('measurement_dress_name', 'รอบสะโพก')
                ->whereBetween('measurement_dress_number', [$hip - 4, $hip + 4])
                ->where('measurement_dress_unit', 'นิ้ว');
        }

        //ดึง dress_id จากตาราง mea
        $dress_id = $condition_mea->pluck('dress_id');
        dd($dress_id);

        $dress = Dress::where('type_dress_id', $id)
            ->whereIn('id', $dress_id)
            ->with(['dressimages', 'dressmeasurements'])
            ->get();
        return view('Employee.typerentdressshow', compact('dress', 'type_dress_name', 'type_dress_id', 'search_separable'));
    }















    // if ($search_separable == null) {
    //     return $this->typerentdressshow($id);
    // } elseif ($search_separable == 1) {
    //     $type_dress_name = Typedress::where('id', $id)->select('type_dress_name', 'specific_letter')->first();
    //     $dress = Dress::where('type_dress_id', $id)
    //         ->where('separable', 1)
    //         ->with(['dressimages','dressmeasurements'])->get();
    // } elseif ($search_separable == 2) {
    //     $type_dress_name = Typedress::where('id', $id)->select('type_dress_name', 'specific_letter')->first();
    //     $dress = Dress::where('type_dress_id', $id)
    //         ->where('separable', 2)
    //         ->with(['dressimages','dressmeasurements'])->get();
    // }






















    //เพิ่มชุดลงในตะกร้า
    public function addrentdresscart(Request $request)
    {

        $dress_id = $request->input('dress_id');
        $price_dress = $request->input('price_dress'); //ราคาชุด
        $deposit_dress = $request->input('deposit_dress'); //ราคามัดจำ
        $damage_insurance_dress = $request->input('damage_insurance_dress'); //ราคาประกันค่าเสียหาย
        $type_dress_name = $request->input('type_dress_name');
        $dress_code = $request->input('dress_code');
        $separable = $request->input('separable');  // 1 แยกไม่ได้ 2 แยกได


        //วันที่นัดรับ-คืน-จำนวนวัน
        $selectstartDate = $request->input('selectstartDate');
        $selectendDate = $request->input('selectendDate');
        $selecttotalDay = $request->input('selecttotalDay');

        //เสื้อ
        $shirtitem_id = $request->input('shirtitem_id');
        $shirtitem_price = $request->input('shirtitem_price');
        $shirtitem_deposit = $request->input('shirtitem_deposit');
        $shirt_damage_insurance = $request->input('shirt_damage_insurance');

        //กระโปรง/กางเกง
        $skirtitem_id = $request->input('skirtitem_id');
        $skirtitem_price = $request->input('skirtitem_price');
        $skirtitem_deposit = $request->input('skirtitem_deposit');
        $skirt_damage_insurance = $request->input('skirt_damage_insurance');

        $id_employee = Auth::user()->id;
        $addcheckorder = Order::where('user_id', $id_employee)
            ->where('order_status', 0)->first();
        //ถ้ามันมีตะกร้าอยู่แล้ว
        if ($addcheckorder) {
            if ($separable == 1) {
                //แยกไม้ได้
                //ตารางorder
                $update_cart = Order::find($addcheckorder->id);
                $update_cart->total_quantity = $addcheckorder->total_quantity + 1;
                $update_cart->total_price = $addcheckorder->total_price + $price_dress;
                $update_cart->total_deposit = $addcheckorder->total_deposit + $deposit_dress;
                $update_cart->save();
                //สร้างตารางorderdetail
                $create_order = new Orderdetail();
                $create_order->order_id = $addcheckorder->id;
                $create_order->employee_id = $id_employee;
                $create_order->dress_id = $dress_id;
                $create_order->title_name = 'เช่า' . $type_dress_name . ' ' . $dress_code;
                $create_order->type_dress = $type_dress_name;
                $create_order->type_order = 2; //1ตัดชุด 2เช่าชุด 3เช่าเครื่องประดับ 4.เช่าตัด
                $create_order->amount = 1;
                $create_order->price = $price_dress;
                $create_order->deposit = $deposit_dress;
                $create_order->damage_insurance = $damage_insurance_dress;
                $create_order->pickup_date = $selectstartDate;
                $create_order->return_date = $selectendDate;
                if ($selecttotalDay > 3) {
                    $over = $selecttotalDay - 3;
                    $price_service_fee = ($price_dress * 0.2) * $over;  //จำนวนเงินค่าบริการขยายเวลาเช่าชุด
                    $create_order->late_charge = $price_service_fee;
                } else {
                    $create_order->late_charge = 0;
                }
                $create_order->status_fix_measurement = "ไม่มีการแก้ไข";
                $create_order->save();

                //ตารางการวัด
                $mea_now_dress_no_name = $request->input('mea_now_dress_name_');
                $mea_now_dress_no_number = $request->input('mea_now_dress_number_');
                $mea_now_dress_no_unit = $request->input('mea_now_dress_unit_');
                $mea_now_dress_no_number_start = $request->input('mea_now_dress_number_start_');
                foreach ($mea_now_dress_no_name as $index => $mea_now_name) {
                    $add_mea = new Measurementorderdetail();
                    $add_mea->order_detail_id = $create_order->id;
                    $add_mea->dress_id = $dress_id;
                    $add_mea->measurement_name = $mea_now_name;
                    $add_mea->measurement_number_start = $mea_now_dress_no_number_start[$index];
                    $add_mea->measurement_number_old = $mea_now_dress_no_number[$index];
                    $add_mea->measurement_number = $mea_now_dress_no_number[$index];
                    $add_mea->measurement_unit = $mea_now_dress_no_unit[$index];
                    $add_mea->status_measurement = 'ไม่มีการแก้ไข';
                    $add_mea->save();
                }


                //อัปเดตตาราง dress
                $update_dress = Dress::find($dress_id);
                $update_dress->dress_status = "อยู่ในตะกร้า";
                $update_dress->save();
            } elseif ($separable == 2) {
                //แยกได้

                //เสื้อ
                if ($shirtitem_id) {
                    //ตารางorder
                    $update_cart = Order::find($addcheckorder->id);
                    $update_cart->total_quantity = $addcheckorder->total_quantity + 1;
                    $update_cart->total_price = $addcheckorder->total_price + $shirtitem_price;
                    $update_cart->total_deposit = $addcheckorder->total_deposit + $shirtitem_deposit;
                    $update_cart->save();
                    //สร้างตารางorderdetail
                    $create_order = new Orderdetail();
                    $create_order->order_id = $addcheckorder->id;
                    $create_order->employee_id = $id_employee;
                    $create_order->dress_id = $dress_id;
                    $create_order->shirtitems_id  = $shirtitem_id;
                    $create_order->title_name = 'เช่า' . $type_dress_name . ' ' . $dress_code . '(เสื้อ)';
                    $create_order->type_dress = $type_dress_name;
                    $create_order->type_order = 2; //1ตัดชุด 2เช่าชุด 3เช่าเครื่องประดับ 4.เช่าตัด
                    $create_order->amount = 1;
                    $create_order->price = $shirtitem_price;
                    $create_order->deposit = $shirtitem_deposit;
                    $create_order->damage_insurance = $shirt_damage_insurance;

                    $create_order->pickup_date = $selectstartDate;
                    $create_order->return_date = $selectendDate;
                    if ($selecttotalDay > 3) {
                        $over = $selecttotalDay - 3;
                        $price_service_fee = ($shirtitem_price * 0.2) * $over;  //จำนวนเงินค่าบริการขยายเวลาเช่าชุด
                        $create_order->late_charge = $price_service_fee;
                    } else {
                        $create_order->late_charge = 0;
                    }

                    $create_order->status_fix_measurement = "ไม่มีการแก้ไข";
                    $create_order->save();
                    // อัปเดตตารางshirt
                    $update_shirt = Shirtitem::find($shirtitem_id);
                    $update_shirt->shirtitem_status = "อยู่ในตะกร้า";
                    $update_shirt->save();
                    //อัปเดตตาราง dress
                    $check = Skirtitem::where('dress_id', $dress_id)->value('skirtitem_status');
                    if ($check == "พร้อมให้เช่า") {
                        $update_dress = Dress::find($dress_id);
                        $update_dress->dress_status = "ไม่พร้อมให้เช่า";
                        $update_dress->save();
                    } else {
                        $update_dress = Dress::find($dress_id);
                        $update_dress->dress_status = "ไม่พร้อมให้เช่า";
                        $update_dress->save();
                    }
                    //ตารางการวัด
                    $mea_now_dress_yes_name = $request->input('mea_now_dress_name_');
                    $mea_now_dress_yes_number = $request->input('mea_now_dress_number_');
                    $mea_now_dress_yes_unit = $request->input('mea_now_dress_unit_');
                    $mea_now_dress_yes_number_start = $request->input('mea_now_dress_number_start_');
                    foreach ($mea_now_dress_yes_name as $index => $mea_now_name) {
                        $add_mea = new Measurementorderdetail();
                        $add_mea->order_detail_id = $create_order->id;
                        $add_mea->dress_id = $dress_id;
                        $add_mea->item_shirt_id = $shirtitem_id;
                        $add_mea->measurement_name = $mea_now_name;
                        $add_mea->measurement_number_start = $mea_now_dress_yes_number_start[$index];
                        $add_mea->measurement_number_old = $mea_now_dress_yes_number[$index];
                        $add_mea->measurement_number = $mea_now_dress_yes_number[$index];
                        $add_mea->measurement_unit = $mea_now_dress_yes_unit[$index];
                        $add_mea->status_measurement = 'ไม่มีการแก้ไข';
                        $add_mea->save();
                    }
                }
                //กระโปรง
                elseif ($skirtitem_id) {
                    //ตารางorder
                    $update_cart = Order::find($addcheckorder->id);
                    $update_cart->total_quantity = $addcheckorder->total_quantity + 1;
                    $update_cart->total_price = $addcheckorder->total_price + $skirtitem_price;
                    $update_cart->total_deposit = $addcheckorder->total_deposit + $skirtitem_deposit;
                    $update_cart->save();
                    //สร้างตารางorderdetail
                    $create_order = new Orderdetail();
                    $create_order->order_id = $addcheckorder->id;
                    $create_order->employee_id = $id_employee;
                    $create_order->dress_id = $dress_id;
                    $create_order->skirtitems_id  = $skirtitem_id;
                    $create_order->title_name = 'เช่า' . $type_dress_name . ' ' . $dress_code . '(กระโปรง)';
                    $create_order->type_dress = $type_dress_name;
                    $create_order->type_order = 2; //1ตัดชุด 2เช่าชุด 3เช่าเครื่องประดับ 4.เช่าตัด
                    $create_order->amount = 1;
                    $create_order->price = $skirtitem_price;
                    $create_order->deposit = $skirtitem_deposit;
                    $create_order->damage_insurance = $skirt_damage_insurance;

                    $create_order->pickup_date = $selectstartDate;
                    $create_order->return_date = $selectendDate;
                    if ($selecttotalDay > 3) {
                        $over = $selecttotalDay - 3;
                        $price_service_fee = ($skirtitem_price * 0.2) * $over;  //จำนวนเงินค่าบริการขยายเวลาเช่าชุด
                        $create_order->late_charge = $price_service_fee;
                    } else {
                        $create_order->late_charge = 0;
                    }
                    $create_order->status_fix_measurement = "ไม่มีการแก้ไข";
                    $create_order->save();

                    // อัปเดตตารางskirt
                    $update_skirt = Skirtitem::find($skirtitem_id);
                    $update_skirt->skirtitem_status = "อยู่ในตะกร้า";
                    $update_skirt->save();
                    //อัปเดตตาราง dress
                    $check = Shirtitem::where('dress_id', $dress_id)->value('shirtitem_status');
                    if ($check == "พร้อมให้เช่า") {
                        $update_dress = Dress::find($dress_id);
                        $update_dress->dress_status = "ไม่พร้อมให้เช่า";
                        $update_dress->save();
                    } else {
                        $update_dress = Dress::find($dress_id);
                        $update_dress->dress_status = "ไม่พร้อมให้เช่า";
                        $update_dress->save();
                    }

                    //ตารางการวัด
                    $mea_now_dress_yes_name = $request->input('mea_now_dress_name_');
                    $mea_now_dress_yes_number = $request->input('mea_now_dress_number_');
                    $mea_now_dress_yes_unit = $request->input('mea_now_dress_unit_');
                    $mea_now_dress_yes_number_start = $request->input('mea_now_dress_number_start_');
                    foreach ($mea_now_dress_yes_name as $index => $mea_now_name) {
                        $add_mea = new Measurementorderdetail();
                        $add_mea->order_detail_id = $create_order->id;
                        $add_mea->dress_id = $dress_id;
                        $add_mea->item_skirt_id = $skirtitem_id;
                        $add_mea->measurement_name = $mea_now_name;
                        $add_mea->measurement_number_start = $mea_now_dress_yes_number_start[$index];
                        $add_mea->measurement_number_old = $mea_now_dress_yes_number[$index];
                        $add_mea->measurement_number = $mea_now_dress_yes_number[$index];
                        $add_mea->measurement_unit = $mea_now_dress_yes_unit[$index];
                        $add_mea->status_measurement = 'ไม่มีการแก้ไข';
                        $add_mea->save();
                    }
                }
                //ทั้งชุด
                else {
                    $update_cart = Order::find($addcheckorder->id);
                    $update_cart->total_quantity = $addcheckorder->total_quantity + 1;
                    $update_cart->total_price = $addcheckorder->total_price + $price_dress;
                    $update_cart->total_deposit = $addcheckorder->total_deposit + $deposit_dress;
                    $update_cart->save();
                    //สร้างตารางorderdetail
                    $create_order = new Orderdetail();
                    $create_order->order_id = $addcheckorder->id;
                    $create_order->employee_id = $id_employee;
                    $create_order->dress_id = $dress_id;
                    $create_order->title_name = 'เช่า' . $type_dress_name . ' ' . $dress_code;
                    $create_order->type_dress = $type_dress_name;
                    $create_order->type_order = 2; //1ตัดชุด 2เช่าชุด 3เช่าเครื่องประดับ 4.เช่าตัด
                    $create_order->amount = 1;
                    $create_order->price = $price_dress;
                    $create_order->deposit = $deposit_dress;
                    $create_order->damage_insurance = $damage_insurance_dress;
                    $create_order->pickup_date = $selectstartDate;
                    $create_order->return_date = $selectendDate;
                    if ($selecttotalDay > 3) {
                        $over = $selecttotalDay - 3;
                        $price_service_fee = ($price_dress * 0.2) * $over;  //จำนวนเงินค่าบริการขยายเวลาเช่าชุด
                        $create_order->late_charge = $price_service_fee;
                    } else {
                        $create_order->late_charge = 0;
                    }
                    $create_order->status_fix_measurement = "ไม่มีการแก้ไข";
                    $create_order->save();
                    //อัปเดตตาราง dress
                    $update_dress = Dress::find($dress_id);
                    $update_dress->dress_status = "อยู่ในตะกร้า";
                    $update_dress->save();
                    // ตาราง shirt
                    $update_id_shirt = Shirtitem::where('dress_id', $dress_id)->value('id');
                    $update_shirt = Shirtitem::find($update_id_shirt);
                    $update_shirt->shirtitem_status = "อยู่ในตะกร้า";
                    $update_shirt->save();
                    // ตารางskirt
                    $update_id_skirt = Skirtitem::where('dress_id', $dress_id)->value('id');
                    $update_skirt = Skirtitem::find($update_id_skirt);
                    $update_skirt->skirtitem_status = "อยู่ในตะกร้า";
                    $update_skirt->save();

                    //ตารางการวัด
                    $mea_now_dress_no_yes_name = $request->input('mea_now_dress_name_');
                    $mea_now_dress_no_yes_number = $request->input('mea_now_dress_number_');
                    $mea_now_dress_no_yes_unit = $request->input('mea_now_dress_unit_');
                    $mea_shirt_now = $request->input('mea_shirt_now_');
                    $mea_skirt_now = $request->input('mea_skirt_now_');
                    $mea_now_dress_no_yes_number_start = $request->input('mea_now_dress_number_start_');
                    foreach ($mea_now_dress_no_yes_name as $index => $mea_now_name) {
                        $add_mea = new Measurementorderdetail();
                        $add_mea->order_detail_id = $create_order->id;
                        $add_mea->dress_id = $dress_id;
                        $add_mea->item_shirt_id = $mea_shirt_now[$index];
                        $add_mea->item_skirt_id = $mea_skirt_now[$index];
                        $add_mea->measurement_name = $mea_now_name;
                        $add_mea->measurement_number_start = $mea_now_dress_no_yes_number_start[$index];
                        $add_mea->measurement_number_old = $mea_now_dress_no_yes_number[$index];
                        $add_mea->measurement_number = $mea_now_dress_no_yes_number[$index];
                        $add_mea->measurement_unit = $mea_now_dress_no_yes_unit[$index];
                        $add_mea->status_measurement = 'ไม่มีการแก้ไข';
                        $add_mea->save();
                    }
                }
            }
        }
        //ถ้ามันไม่มีตะกร้า 
        else {
            if ($separable == 1) {
                //แยกไม่ได้
                //ตารางorder
                $create_cart = new Order();
                $create_cart->user_id = $id_employee;
                $create_cart->total_quantity = 1;
                $create_cart->total_price = $price_dress;
                $create_cart->total_deposit = $deposit_dress;
                $create_cart->order_status = 0;
                $create_cart->save();
                //สร้างตารางorderdetail
                $create_order = new Orderdetail();
                $create_order->order_id = $create_cart->id;
                $create_order->employee_id = $id_employee;
                $create_order->dress_id = $dress_id;
                $create_order->title_name = 'เช่า' . $type_dress_name . ' ' . $dress_code;
                $create_order->type_dress = $type_dress_name;
                $create_order->type_order = 2; //1ตัดชุด 2เช่าชุด 3เช่าเครื่องประดับ 4.เช่าตัด
                $create_order->amount = 1;
                $create_order->price = $price_dress;
                $create_order->deposit = $deposit_dress;
                $create_order->damage_insurance = $damage_insurance_dress;
                $create_order->pickup_date = $selectstartDate;
                $create_order->return_date = $selectendDate;
                if ($selecttotalDay > 3) {
                    $over = $selecttotalDay - 3;
                    $price_service_fee = ($price_dress * 0.2) * $over;  //จำนวนเงินค่าบริการขยายเวลาเช่าชุด
                    $create_order->late_charge = $price_service_fee;
                } else {
                    $create_order->late_charge = 0;
                }
                $create_order->status_fix_measurement = "ไม่มีการแก้ไข";
                $create_order->save();

                //ตารางการวัด
                $mea_now_dress_no_name = $request->input('mea_now_dress_name_');
                $mea_now_dress_no_number = $request->input('mea_now_dress_number_');
                $mea_now_dress_no_unit = $request->input('mea_now_dress_unit_');
                $mea_now_dress_no_number_start = $request->input('mea_now_dress_number_start_');
                foreach ($mea_now_dress_no_name as $index => $mea_now_name) {
                    $add_mea = new Measurementorderdetail();
                    $add_mea->order_detail_id = $create_order->id;
                    $add_mea->dress_id = $dress_id;
                    $add_mea->measurement_name = $mea_now_name;
                    $add_mea->measurement_number_start = $mea_now_dress_no_number_start[$index];
                    $add_mea->measurement_number_old = $mea_now_dress_no_number[$index];
                    $add_mea->measurement_number = $mea_now_dress_no_number[$index];
                    $add_mea->measurement_unit = $mea_now_dress_no_unit[$index];
                    $add_mea->status_measurement = 'ไม่มีการแก้ไข';
                    $add_mea->save();
                }
                //อัปเดตตาราง dress
                $update_dress = Dress::find($dress_id);
                $update_dress->dress_status = "อยู่ในตะกร้า";
                $update_dress->save();
            } elseif ($separable == 2) {
                //แยกได้                
                //เสื้อ
                if ($shirtitem_id) {
                    //ตารางorder
                    $create_cart = new Order();
                    $create_cart->user_id = $id_employee;
                    $create_cart->total_quantity = 1;
                    $create_cart->total_price = $shirtitem_price;
                    $create_cart->total_deposit = $shirtitem_deposit;
                    $create_cart->order_status = 0;
                    $create_cart->save();
                    //สร้างตารางorderdetail
                    $create_order = new Orderdetail();
                    $create_order->order_id = $create_cart->id;
                    $create_order->employee_id = $id_employee;
                    $create_order->dress_id = $dress_id;
                    $create_order->shirtitems_id  = $shirtitem_id;
                    $create_order->title_name = 'เช่า' . $type_dress_name . ' ' . $dress_code . '(เสื้อ)';
                    $create_order->type_dress = $type_dress_name;
                    $create_order->type_order = 2; //1ตัดชุด 2เช่าชุด 3เช่าเครื่องประดับ 4.เช่าตัด
                    $create_order->amount = 1;
                    $create_order->price = $shirtitem_price;
                    $create_order->deposit = $shirtitem_deposit;
                    $create_order->damage_insurance = $shirt_damage_insurance;
                    $create_order->pickup_date = $selectstartDate;
                    $create_order->return_date = $selectendDate;
                    if ($selecttotalDay > 3) {
                        $over = $selecttotalDay - 3;
                        $price_service_fee = ($shirtitem_price * 0.2) * $over;  //จำนวนเงินค่าบริการขยายเวลาเช่าชุด
                        $create_order->late_charge = $price_service_fee;
                    } else {
                        $create_order->late_charge = 0;
                    }
                    $create_order->status_fix_measurement = "ไม่มีการแก้ไข";
                    $create_order->save();
                    // อัปเดตตารางshirt
                    $update_shirt = Shirtitem::find($shirtitem_id);
                    $update_shirt->shirtitem_status = "อยู่ในตะกร้า";
                    $update_shirt->save();
                    //อัปเดตตาราง dress
                    $check = Skirtitem::where('dress_id', $dress_id)->value('skirtitem_status');
                    if ($check == "พร้อมให้เช่า") {
                        $update_dress = Dress::find($dress_id);
                        $update_dress->dress_status = "ไม่พร้อมให้เช่า";
                        $update_dress->save();
                    } else {
                        $update_dress = Dress::find($dress_id);
                        $update_dress->dress_status = "ไม่พร้อมให้เช่า";
                        $update_dress->save();
                    }
                    //ตารางการวัด
                    $mea_now_dress_yes_name = $request->input('mea_now_dress_name_');
                    $mea_now_dress_yes_number = $request->input('mea_now_dress_number_');
                    $mea_now_dress_yes_unit = $request->input('mea_now_dress_unit_');
                    $mea_now_dress_yes_number_start = $request->input('mea_now_dress_number_start_');
                    foreach ($mea_now_dress_yes_name as $index => $mea_now_name) {
                        $add_mea = new Measurementorderdetail();
                        $add_mea->order_detail_id = $create_order->id;
                        $add_mea->dress_id = $dress_id;
                        $add_mea->item_shirt_id = $shirtitem_id;
                        $add_mea->measurement_name = $mea_now_name;
                        $add_mea->measurement_number_start = $mea_now_dress_yes_number_start[$index];
                        $add_mea->measurement_number_old = $mea_now_dress_yes_number[$index];
                        $add_mea->measurement_number = $mea_now_dress_yes_number[$index];
                        $add_mea->measurement_unit = $mea_now_dress_yes_unit[$index];
                        $add_mea->status_measurement = 'ไม่มีการแก้ไข';
                        $add_mea->save();
                    }
                }
                //กางเกง
                elseif ($skirtitem_id) {
                    //ตารางorder
                    $create_cart = new Order();
                    $create_cart->user_id = $id_employee;
                    $create_cart->total_quantity = 1;
                    $create_cart->total_price = $skirtitem_price;
                    $create_cart->total_deposit = $skirtitem_deposit;
                    $create_cart->order_status = 0;
                    $create_cart->save();
                    //สร้างตารางorderdetail
                    $create_order = new Orderdetail();
                    $create_order->order_id = $create_cart->id;
                    $create_order->employee_id = $id_employee;
                    $create_order->dress_id = $dress_id;
                    $create_order->skirtitems_id  = $skirtitem_id;
                    $create_order->title_name = 'เช่า' . $type_dress_name . ' ' . $dress_code . '(กระโปรง)';
                    $create_order->type_dress = $type_dress_name;
                    $create_order->type_order = 2; //1ตัดชุด 2เช่าชุด 3เช่าเครื่องประดับ 4.เช่าตัด
                    $create_order->amount = 1;
                    $create_order->price = $skirtitem_price;
                    $create_order->deposit = $skirtitem_deposit;
                    $create_order->damage_insurance = $skirt_damage_insurance;
                    $create_order->pickup_date = $selectstartDate;
                    $create_order->return_date = $selectendDate;
                    if ($selecttotalDay > 3) {
                        $over = $selecttotalDay - 3;
                        $price_service_fee = ($skirtitem_price * 0.2) * $over;  //จำนวนเงินค่าบริการขยายเวลาเช่าชุด
                        $create_order->late_charge = $price_service_fee;
                    } else {
                        $create_order->late_charge = 0;
                    }
                    $create_order->status_fix_measurement = "ไม่มีการแก้ไข";
                    $create_order->save();
                    // อัปเดตตารางskirt
                    $update_skirt = Skirtitem::find($skirtitem_id);
                    $update_skirt->skirtitem_status = "อยู่ในตะกร้า";
                    $update_skirt->save();
                    //อัปเดตตาราง dress
                    $check = Shirtitem::where('dress_id', $dress_id)->value('shirtitem_status');
                    if ($check == "พร้อมให้เช่า") {
                        $update_dress = Dress::find($dress_id);
                        $update_dress->dress_status = "ไม่พร้อมให้เช่า";
                        $update_dress->save();
                    } else {
                        $update_dress = Dress::find($dress_id);
                        $update_dress->dress_status = "ไม่พร้อมให้เช่า";
                        $update_dress->save();
                    }

                    //ตารางการวัด
                    $mea_now_dress_yes_name = $request->input('mea_now_dress_name_');
                    $mea_now_dress_yes_number = $request->input('mea_now_dress_number_');
                    $mea_now_dress_yes_unit = $request->input('mea_now_dress_unit_');
                    $mea_now_dress_yes_number_start = $request->input('mea_now_dress_number_start_');
                    foreach ($mea_now_dress_yes_name as $index => $mea_now_name) {
                        $add_mea = new Measurementorderdetail();
                        $add_mea->order_detail_id = $create_order->id;
                        $add_mea->dress_id = $dress_id;
                        $add_mea->item_skirt_id = $skirtitem_id;
                        $add_mea->measurement_name = $mea_now_name;
                        $add_mea->measurement_number_start = $mea_now_dress_yes_number_start[$index];
                        $add_mea->measurement_number_old = $mea_now_dress_yes_number[$index];
                        $add_mea->measurement_number = $mea_now_dress_yes_number[$index];
                        $add_mea->measurement_unit = $mea_now_dress_yes_unit[$index];
                        $add_mea->status_measurement = 'ไม่มีการแก้ไข';
                        $add_mea->save();
                    }
                }
                //ทั้งชุด 
                else {
                    $create_cart = new Order();
                    $create_cart->user_id = $id_employee;
                    $create_cart->total_quantity = 1;
                    $create_cart->total_price = $price_dress;
                    $create_cart->total_deposit = $deposit_dress;
                    $create_cart->order_status = 0;
                    $create_cart->save();
                    //สร้างตารางorderdetail
                    $create_order = new Orderdetail();
                    $create_order->order_id = $create_cart->id;
                    $create_order->employee_id = $id_employee;
                    $create_order->dress_id = $dress_id;
                    $create_order->title_name = 'เช่า' . $type_dress_name . ' ' . $dress_code;
                    $create_order->type_dress = $type_dress_name;
                    $create_order->type_order = 2; //1ตัดชุด 2เช่าชุด 3เช่าเครื่องประดับ 4.เช่าตัด
                    $create_order->amount = 1;
                    $create_order->price = $price_dress;
                    $create_order->deposit = $deposit_dress;
                    $create_order->damage_insurance = $damage_insurance_dress;
                    $create_order->pickup_date = $selectstartDate;
                    $create_order->return_date = $selectendDate;
                    if ($selecttotalDay > 3) {
                        $over = $selecttotalDay - 3;
                        $price_service_fee = ($price_dress * 0.2) * $over;  //จำนวนเงินค่าบริการขยายเวลาเช่าชุด
                        $create_order->late_charge = $price_service_fee;
                    } else {
                        $create_order->late_charge = 0;
                    }
                    $create_order->status_fix_measurement = "ไม่มีการแก้ไข";
                    $create_order->save();
                    //อัปเดตตาราง dress
                    $update_dress = Dress::find($dress_id);
                    $update_dress->dress_status = "อยู่ในตะกร้า";
                    $update_dress->save();
                    // ตาราง shirt
                    $update_id_shirt = Shirtitem::where('dress_id', $dress_id)->value('id');
                    $update_shirt = Shirtitem::find($update_id_shirt);
                    $update_shirt->shirtitem_status = "อยู่ในตะกร้า";
                    $update_shirt->save();
                    // ตารางskirt
                    $update_id_skirt = Skirtitem::where('dress_id', $dress_id)->value('id');
                    $update_skirt = Skirtitem::find($update_id_skirt);
                    $update_skirt->skirtitem_status = "อยู่ในตะกร้า";
                    $update_skirt->save();

                    //ตารางการวัด
                    $mea_now_dress_no_yes_name = $request->input('mea_now_dress_name_');
                    $mea_now_dress_no_yes_number = $request->input('mea_now_dress_number_');
                    $mea_now_dress_no_yes_unit = $request->input('mea_now_dress_unit_');
                    $mea_shirt_now = $request->input('mea_shirt_now_');
                    $mea_skirt_now = $request->input('mea_skirt_now_');
                    $mea_now_dress_no_yes_number_start = $request->input('mea_now_dress_number_start_');
                    foreach ($mea_now_dress_no_yes_name as $index => $mea_now_name) {
                        $add_mea = new Measurementorderdetail();
                        $add_mea->order_detail_id = $create_order->id;
                        $add_mea->dress_id = $dress_id;
                        $add_mea->item_shirt_id = $mea_shirt_now[$index];
                        $add_mea->item_skirt_id = $mea_skirt_now[$index];
                        $add_mea->measurement_name = $mea_now_name;
                        $add_mea->measurement_number_start = $mea_now_dress_no_yes_number_start[$index];
                        $add_mea->measurement_number_old = $mea_now_dress_no_yes_number[$index];
                        $add_mea->measurement_number = $mea_now_dress_no_yes_number[$index];
                        $add_mea->measurement_unit = $mea_now_dress_no_yes_unit[$index];
                        $add_mea->status_measurement = 'ไม่มีการแก้ไข';
                        $add_mea->save();
                    }
                }
            }
        }
        return redirect()->back()->with('success', "เพิ่มลงตะกร้าสำเร็จ !");
    }
















    //เพิ่มเครื่องประดับลงในตะกร้า
    public function addrentjewelrycart(Request $request)
    {
        $jewelry_id = $request->input('jewelry_id');
        $jewelry_price = $request->input('jewelry_price');
        $jewelry_deposit = $request->input('jewelry_deposit');
        $type_jewelry_name = $request->input('type_jewelry_name');
        $jewelry_code = $request->input('jewelry_code');
        $user_id = Auth::user()->id;
        $check_cart_order = Order::where('user_id', $user_id)
            ->where('order_status', 0)->first();

        if ($check_cart_order) { //มีตะกร้า
            $update_cart = Order::find($check_cart_order->id);
            $update_cart->total_quantity = $update_cart->total_quantity + 1;
            $update_cart->total_price += $jewelry_price;
            $update_cart->total_deposit += $jewelry_deposit;
            $update_cart->save();
            $ORDER_ID = $update_cart->id;

            //สร้างตารางorderdetail
            $create_order = new Orderdetail();
            $create_order->order_id = $ORDER_ID;
            $create_order->employee_id = $user_id;
            $create_order->jewelry_id  = $jewelry_id;
            $create_order->title_name = 'เช่า' . $type_jewelry_name . ' ' . $jewelry_code;
            $create_order->type_dress = $type_jewelry_name;
            $create_order->type_order = 3; //1ตัดชุด 2เช่าชุด 3เช่าเครื่องประดับ 4.เช่าตัด
            $create_order->amount = 1;
            $create_order->price = $jewelry_price;
            $create_order->deposit = $jewelry_deposit;
            $create_order->save();
            //อัปเดตตาราง jewelry
            $update_jewelry = Jewelry::find($jewelry_id);
            $update_jewelry->jewelry_status = "อยู่ในตะกร้า";
            $update_jewelry->save();
        } else {
            //ไม่มีตะกร้า
            $create_order = new Order();
            $create_order->user_id = $user_id;
            $create_order->total_quantity = 1;
            $create_order->total_price = $jewelry_price;
            $create_order->total_deposit = $jewelry_deposit;
            $create_order->order_status = 0;
            $create_order->save();
            $ORDER_ID = $create_order->id;

            //สร้างตารางorderdetail
            $create_order = new Orderdetail();
            $create_order->order_id = $ORDER_ID;
            $create_order->employee_id = $user_id;
            $create_order->jewelry_id  = $jewelry_id;
            $create_order->title_name = 'เช่า' . $type_jewelry_name . ' ' . $jewelry_code;
            $create_order->type_dress = $type_jewelry_name;
            $create_order->type_order = 3; //1ตัดชุด 2เช่าชุด 3เช่าเครื่องประดับ 4.เช่าตัด
            $create_order->amount = 1;
            $create_order->price = $jewelry_price;
            $create_order->deposit = $jewelry_deposit;
            $create_order->save();
            //อัปเดตตาราง jewelry
            $update_jewelry = Jewelry::find($jewelry_id);
            $update_jewelry->jewelry_status = "อยู่ในตะกร้า";
            $update_jewelry->save();
        }
        return redirect()->back()->with('success', "เพิ่มลงตะกร้าสำเร็จ");
    }


















    //อัปเดตข้อมูลในรายการ item 
    public function savemanageitem(Request $request, $id)
    {
        $orderdetail = Orderdetail::find($id);
        //บันทึกกรณีตัดชุด
        if ($orderdetail->type_order == 1) {
            return $this->savemanageitemone($request, $id);
        }
        // บันทึกกรณีเช่าชุด
        elseif ($orderdetail->type_order == 2) {
            return $this->savemanageitemtwo($request, $id);
        }
    }


    // //อัปเดพกรณีตัดชุด
    // private function savemanageitemone(Request $request, $id)
    // {
    //     DB::beginTransaction();
    //     try {

    //         $orderdetail = Orderdetail::find($id);  //ค้นหา order_detail_id ในตาราง orderdetail 
    //         //เลือกอื่นๆ ต้องกรอก   ประเภทชุดที่เลือกตัด
    //         if ($request->input('type_dress') == 'other_type') {
    //             $checkdouble = Typedress::where('type_dress_name', $request->input('other_type'))->first();
    //             if ($checkdouble) {
    //                 $TYPE_DRESS_NAME = $request->input('other_type');
    //             } else {
    //                 //สร้างอักษรมา 1 ตัว 
    //                 do {
    //                     $random = chr(65 + rand(0, 25));
    //                     $check = Typedress::where('specific_letter', $random)->first();
    //                 } while ($check);
    //                 $character = $random; //ได้ตัวอักษรมาแล้ว 
    //                 $create_id_of_typedress = new Typedress();
    //                 $create_id_of_typedress->type_dress_name = $request->input('other_input');
    //                 $create_id_of_typedress->specific_letter = $character;
    //                 $create_id_of_typedress->save();
    //                 $TYPE_DRESS_NAME = $request->input('other_input');

    //                 //ลบประเภทชุดเดิมที่เคยสร้าง
    //                 $ID_FOR_TYPE_NAME = Typedress::where('type_dress_name', $orderdetail->type_dress)->value('id');  //ได้ id มาแล้ว
    //                 $find_for_delete = Dress::where('type_dress_id', $ID_FOR_TYPE_NAME)->first(); //ค้นหาว่าid ของประเภทชุดมันมีไหม F
    //                 if (!$find_for_delete) {
    //                     $delete_type_name_now = Typedress::find($ID_FOR_TYPE_NAME);
    //                     $delete_type_name_now->delete(); //ลบประเภทชุดที่เคยเลือกแล้ว
    //                 }
    //             }
    //         }
    //         // เลือกในดรอปดาว  ประเภทชุดที่เลือกตัด
    //         else {
    //             $TYPE_DRESS_NAME = $request->input('type_dress');
    //             //เช็คว่าเลือกอันเดิมไหม ถ้าไม่เลือกอันเดิม จะลบ ประเภทชุดทิ้ง
    //             if ($request->input('type_dress') != $orderdetail->type_dress) {
    //                 $ID_FOR_TYPE_NAME = Typedress::where('type_dress_name', $orderdetail->type_dress)->value('id');  //ได้ id มาแล้ว
    //                 $find_for_delete = Dress::where('type_dress_id', $ID_FOR_TYPE_NAME)->first(); //ค้นหาว่าid ของประเภทชุดมันมีไหม 
    //                 if (!$find_for_delete) {
    //                     $delete_type_name_now = Typedress::find($ID_FOR_TYPE_NAME);
    //                     $delete_type_name_now->delete(); //ลบประเภทชุดที่เคยเลือกแล้ว
    //                 }
    //             }
    //         }



    //         //อัปเดตตาราง order
    //         $order = Order::where('id', $orderdetail->order_id)->first();
    //         if ($request->input('update_price') > $orderdetail->price) {
    //             $order->total_price = ($order->total_price + $request->input('update_price')) * $request->input('update_amount');
    //         } else {
    //             $order->total_price = ($order->total_price - $request->input('update_price')) * $request->input('update_amount');
    //         }

    //         if ($request->input('update_deposit') > $orderdetail->deposit) {
    //             $order->total_deposit = ($order->total_deposit + $request->input('update_deposit')) * $request->input('update_amount');
    //         } elseif ($request->input('update_deposit') < $orderdetail->deposit) {
    //             $order->total_deposit = ($order->total_deposit - $request->input('update_deposit')) * $request->input('update_amount');
    //         }

    //         $order->save();

    //         //อัปเดตตารางorderdetail
    //         $update_order_detail = Orderdetail::find($id);
    //         $update_order_detail->title_name = 'ตัด' . $TYPE_DRESS_NAME;
    //         $update_order_detail->type_dress = $TYPE_DRESS_NAME;
    //         $update_order_detail->pickup_date = $request->input('update_pickup_date');
    //         $update_order_detail->amount = $request->input('update_amount');

    //         if ($request->input('update_deposit') > $request->input('update_price')) {
    //             DB::rollback();
    //             return redirect()->back()->with('fail', "ราคาตัดต้องมากกว่าราคามัดจำ");
    //         } elseif ($request->input('update_deposit') <= $request->input('update_price')) {
    //             $update_order_detail->price = $request->input('update_price');
    //             $update_order_detail->deposit = $request->input('update_deposit');
    //         }
    //         $update_order_detail->note = $request->input('update_note');
    //         $update_order_detail->color = $request->input('update_color');
    //         $update_order_detail->cloth = $request->input('update_cloth');
    //         $update_order_detail->status_payment = $request->input('update_status_payment');
    //         $update_order_detail->save();
    //         //อัปเดตตาราง date
    //         $find_id_in_date = Date::where('order_detail_id', $id)->first();
    //         $find_id_in_date->pickup_date = $request->input('update_pickup_date');
    //         $find_id_in_date->save();


    //         //อัปเดตตารางstatus_payment
    //         $find_id_in_payment = Paymentstatus::where('order_detail_id', $id)->first();
    //         $find_id_in_payment->payment_status = $request->input('update_status_payment');
    //         $find_id_in_payment->save();

    //         //อัปเดตตาราง financial
    //         $find_id_in_financial = Financial::where('order_detail_id', $id)->first();
    //         if ($request->input('update_status_payment') == 1) {
    //             $text_update_financial = "จ่ายมัดจำ";
    //             $income = $request->input('update_deposit') * $request->input('update_amount');
    //         } else {
    //             $text_update_financial = "จ่ายเต็ม";
    //             $income = $request->input('update_price') * $request->input('update_amount');
    //         }
    //         $find_id_in_financial->item_name = $text_update_financial . '(ตัดชุด)';
    //         $find_id_in_financial->type_order = $orderdetail->type_order;
    //         $find_id_in_financial->financial_income = $income;
    //         $find_id_in_financial->save();

    //         //อัปเดตข้อมูลการวัดmeasurement
    //         $id_for_mea = $request->input('mea_id_'); //ตัวหมุน
    //         $update_name_mea = $request->input('mea_name_');
    //         $update_number_mea = $request->input('mea_number_');
    //         $update_unit_mea = $request->input('mea_unit_');
    //         foreach ($id_for_mea as $index => $id_for_mea_table) {
    //             $update_data = Measurementorderdetail::find($id_for_mea_table);
    //             if ($update_name_mea[$index] != null) {
    //                 $update_data->measurement_name = $update_name_mea[$index];
    //                 $update_data->measurement_number = $update_number_mea[$index];
    //                 $update_data->measurement_unit = $update_unit_mea[$index];
    //                 $update_data->save();
    //             }
    //         }


    //         //บันทึกข้อมูลการวัด
    //         if ($request->input('add_mea_name_') != null) {
    //             $add_mea_name = $request->input('add_mea_name_'); //ตัวหมุน
    //             $add_mea_number = $request->input('add_mea_number_');
    //             $add_mea_unit = $request->input('add_mea_unit_');
    //             foreach ($add_mea_name as $index => $add) {
    //                 $add_measurement = new Measurementorderdetail();
    //                 $add_measurement->order_detail_id = $id;
    //                 $add_measurement->measurement_name = $add;
    //                 $add_measurement->measurement_number = $add_mea_number[$index];
    //                 $add_measurement->measurement_unit = $add_mea_unit[$index];
    //                 $add_measurement->save();
    //             }
    //         }

    //         //อัปเดตตาราการนัดลองชุด
    //         $id_for_fitting = $request->input('fitting_id_'); //ตัวหมุน
    //         $update_fitting_date = $request->input('fitting_date_');
    //         $update_fitting_note = $request->input('fitting_note_');
    //         foreach ($id_for_fitting as $index => $id_for_fitting) {
    //             if ($update_fitting_date[$index] != null) {
    //                 $update_data = Fitting::find($id_for_fitting);
    //                 $update_data->fitting_date = $update_fitting_date[$index];
    //                 $update_data->fitting_note = $update_fitting_note[$index];
    //                 $update_data->save();
    //             }
    //         }
    //         //เพิ่มข้อมูลการนัดลองชุด
    //         if ($request->input('add_fitting_date_') != null) {
    //             $add_fitting_date = $request->input('add_fitting_date_'); //ตัวหมุน
    //             $add_fitting_note = $request->input('add_fitting_note_');
    //             foreach ($add_fitting_date as $index => $add) {
    //                 $add_fitting = new Fitting();
    //                 $add_fitting->order_detail_id = $id;
    //                 $add_fitting->fitting_date = $add;
    //                 $add_fitting->fitting_note = $add_fitting_note[$index];
    //                 $add_fitting->save();
    //             }
    //         }

    //         //เพิ่มรูปภาพ 
    //         if ($request->hasFile('add_image_')) {
    //             $add_image = $request->file('add_image_');
    //             $img = $request->file('add_image_');
    //             foreach ($add_image as $index => $img) {
    //                 $add = new Imagerent();
    //                 $add->order_detail_id = $id;
    //                 $add->image = $img->store('rent_images', 'public');
    //                 $add->save();
    //             }
    //         }
    //         DB::commit();
    //         return redirect()->route('employee.manageitem', ['id' => $id])->with('success', "สำเร็จ");
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //     }
    // }




    //บันทึกของตัดชุดsavemanageitemcutdress
    public function savemanageitemcutdress(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $orderdetail = Orderdetail::find($id);  //ค้นหา order_detail_id ในตาราง orderdetail 
            //ตาราง order
            $update_order = Order::find($orderdetail->order_id);

            //total_price 
            $price_local = $orderdetail->price * $orderdetail->amount; //ราคาเดิม
            $price_new = $request->input('update_price') * $request->input('update_amount'); //ราคาใหม่
            if ($price_new > $price_local) {
                $update_order->total_price = ($price_new - $price_local)  + $update_order->total_price;
                $update_order->save();
            } elseif ($price_new < $price_local) {
                $update_order->total_price = $update_order->total_price - ($price_local - $price_new);
                $update_order->save();
            }

            //total_deposit
            $deposit_local = $orderdetail->deposit * $orderdetail->amount; //ราคาเดิม
            $deposit_new = $request->input('update_deposit') * $request->input('update_amount'); //ราคาใหม่
            if ($deposit_new > $deposit_local) {
                $update_order->total_deposit = ($deposit_new - $deposit_local)  + $update_order->total_deposit;
                $update_order->save();
            } elseif ($deposit_new < $deposit_local) {
                $update_order->total_deposit = $update_order->total_deposit - ($deposit_local - $deposit_new);
                $update_order->save();
            }

            //ตาราง orderdetail
            $update_order_detail = Orderdetail::find($id);
            $update_order_detail->amount = $request->input('update_amount');

            if ($request->input('update_deposit') > $request->input('update_price')) {
                DB::rollback();
                return redirect()->back()->with('fail', "ราคาตัดต้องมากกว่าราคามัดจำ");
            } elseif ($request->input('update_deposit') <= $request->input('update_price')) {
                $update_order_detail->price = $request->input('update_price');
                $update_order_detail->deposit = $request->input('update_deposit');
            }
            $update_order_detail->note = $request->input('update_note');
            $update_order_detail->cloth = $request->input('update_cloth');
            $update_order_detail->save();

            // ตารางdate
            $update_date_id = Date::where('order_detail_id', $id)
                ->orderBy('created_at', 'desc')
                ->value('id');
            $update_date = Date::find($update_date_id);
            $update_date->pickup_date = $request->input('update_pickup_date');
            $update_date->save();





            //อัปเดตข้อมูลการวัดmeasurement

            if ($request->input('mea_id_')) {
                $id_for_mea = $request->input('mea_id_'); //ตัวหมุน
                $update_name_mea = $request->input('update_mea_name_');
                $update_number_mea = $request->input('update_mea_number_');
                foreach ($id_for_mea as $index => $id_for_mea_table) {
                    $update_data = Dressmeaadjustment::find($id_for_mea_table);
                    $update_data->name = $update_name_mea[$index];
                    $update_data->new_size = $update_number_mea[$index];
                    $update_data->save();
                }
            }




            //บันทึกข้อมูลการวัด
            if ($request->input('add_mea_name_')) {
                $add_mea_name = $request->input('add_mea_name_'); //ตัวหมุน
                $add_mea_number = $request->input('add_mea_number_');
                foreach ($add_mea_name as $index => $add) {
                    $add_measurement = new Dressmeaadjustment();
                    $add_measurement->order_detail_id = $id;
                    $add_measurement->name = $add;
                    $add_measurement->new_size = $add_mea_number[$index];
                    $add_measurement->save();
                }
            }

            // if ($request->hasFile('file_image_')) {
            //     $file_image = $request->file('file_image_');
            //     $note_image = $request->input('note_image_');

            //     foreach ($file_image as $index => $image) {
            //         $create_image = new Imagerent();
            //         $create_image->order_detail_id = $id;
            //         $create_image->image = $image->store('rent_images', 'public');
            //         $create_image->description = $note_image[$index];
            //         $create_image->save();
            //     }
            // }


            if ($request->hasFile('file_image_')) {
                $file_images = $request->file('file_image_');
                $note_images = $request->input('note_image_');

                foreach ($file_images as $index => $image) {
                    if ($image->isValid()) {
                        // สร้างชื่อไฟล์แบบไม่ซ้ำ
                        $imageName = time() . '_' . $index . '.' . $image->extension();
                        // ย้ายไฟล์ไปที่ public/rent_images
                        $image->move(public_path('rent_images'), $imageName);

                        // บันทึกข้อมูลลงฐานข้อมูล
                        $create_image = new Imagerent();
                        $create_image->order_detail_id = $id;
                        $create_image->image = 'rent_images/' . $imageName; // เก็บเฉพาะ path โดยไม่ต้องมี public
                        $create_image->description = $note_images[$index] ?? null;
                        $create_image->save();
                    }
                }
            }










            DB::commit();
            return redirect()->back()->with('success', 'จัดการสำเร็จ');
        } catch (\Exception $e) {
            DB::rollback();
        }
    }



    //บันทึกของเช่าชุดsavemanageitemrentdress
    public function savemanageitemrentdress(Request $request, $id)
    {
        //ตาราง orderdetail
        $orderdetail = Orderdetail::find($id);


        $dress_mea_adjust = $request->input('dress_mea_adjust_');
        $dress_mea_adjust_number = $request->input('dress_mea_adjust_number_');

        foreach ($dress_mea_adjust as $index => $mea_adjust_id) {
            $update_mea_adjust = Dressmeaadjustment::find($mea_adjust_id);
            $update_mea_adjust->new_size = $dress_mea_adjust_number[$index];
            $update_mea_adjust->status = 'รอ';

            $mea_current = Dressmea::where('id', $update_mea_adjust->dressmea_id)->value('current_mea');
            if ($update_mea_adjust->new_size !=  $mea_current) {
                $update_mea_adjust->status = "แก้ไข";
            } else {
                $update_mea_adjust->status = "ไม่มีการแก้ไข";
            }
            $update_mea_adjust->save();
        }

        $orderdetail->note = $request->input('note');
        $orderdetail->save();
        return redirect()->back()->with('success', "บันทึกข้อมูล");
    }





    //บันทึกของเช่าเครื่องประดับsavemanageitemrentjewelry
    public function savemanageitemrentjewelry(Request $request, $id)
    {
        //ตาราง orderdetail
        $orderdetail = Orderdetail::find($id);
        $orderdetail->note = $request->input('note');
        $orderdetail->save();
        return redirect()->back()->with('success', 'สำเร็จ !');
    }


    //บันทึกของเช่าตัดชุดsavemanageitemcutrent
    public function savemanageitemcutrent(Request $request, $id)
    {

        $orderdetail = Orderdetail::find($id);  //ค้นหา order_detail_id ในตาราง orderdetail 
        //ตาราง order
        $update_order = Order::find($orderdetail->order_id);

        //total_price 
        $price_local = $orderdetail->price * $orderdetail->amount; //ราคาเดิม
        $price_new = $request->input('update_price') * $request->input('update_amount'); //ราคาใหม่
        if ($price_new > $price_local) {
            $update_order->total_price = ($price_new - $price_local)  + $update_order->total_price;
            $update_order->save();
        } elseif ($price_new < $price_local) {
            $update_order->total_price = $update_order->total_price - ($price_local - $price_new);
            $update_order->save();
        }

        //total_deposit
        $deposit_local = $orderdetail->deposit * $orderdetail->amount; //ราคาเดิม
        $deposit_new = $request->input('update_deposit') * $request->input('update_amount'); //ราคาใหม่
        if ($deposit_new > $deposit_local) {
            $update_order->total_deposit = ($deposit_new - $deposit_local)  + $update_order->total_deposit;
            $update_order->save();
        } elseif ($deposit_new < $deposit_local) {
            $update_order->total_deposit = $update_order->total_deposit - ($deposit_local - $deposit_new);
            $update_order->save();
        }

        //ตาราง orderdetail
        $update_order_detail = Orderdetail::find($id);
        $update_order_detail->amount = $request->input('update_amount');

        $update_order_detail->price = $request->input('update_price');
        $update_order_detail->deposit = $request->input('update_deposit');
        $update_order_detail->damage_insurance = $request->input('update_price');

        $update_order_detail->note = $request->input('update_note');
        $update_order_detail->cloth = $request->input('update_cloth');
        $update_order_detail->save();

        // ตารางdate
        $update_date_id = Date::where('order_detail_id', $id)
            ->orderBy('created_at', 'desc')
            ->value('id');
        $update_date = Date::find($update_date_id);
        $update_date->pickup_date = $request->input('update_pickup_date');
        $update_date->return_date = $request->input('update_return_date');
        $update_date->save();

        //อัปเดตข้อมูลการวัดmeasurement

        if ($request->input('mea_id_')) {
            $id_for_mea = $request->input('mea_id_'); //ตัวหมุน
            $update_name_mea = $request->input('update_mea_name_');
            $update_number_mea = $request->input('update_mea_number_');
            foreach ($id_for_mea as $index => $id_for_mea_table) {
                $update_data = Dressmeaadjustment::find($id_for_mea_table);
                $update_data->name = $update_name_mea[$index];
                $update_data->new_size = $update_number_mea[$index];
                $update_data->save();
            }
        }

        //บันทึกข้อมูลการวัด
        if ($request->input('add_mea_name_')) {
            $add_mea_name = $request->input('add_mea_name_'); //ตัวหมุน
            $add_mea_number = $request->input('add_mea_number_');
            foreach ($add_mea_name as $index => $add) {
                $add_measurement = new Dressmeaadjustment();
                $add_measurement->order_detail_id = $id;
                $add_measurement->name = $add;
                $add_measurement->new_size = $add_mea_number[$index];
                $add_measurement->save();
            }
        }

        // บันทึกข้อมูลลงในตาราง rentimage โดยตรง
        if ($request->hasFile('file_image_')) {
            foreach ($request->file('file_image_') as $index => $file) {
                if ($file->isValid()) {
                    // สร้างชื่อไฟล์แบบไม่ซ้ำ
                    $imageName = time() . '_' . $index . '.' . $file->extension();
                    // ย้ายไฟล์ไปที่ public/rent_images
                    $file->move(public_path('rent_images'), $imageName);

                    // บันทึกข้อมูลลงฐานข้อมูล
                    $image_save = new Imagerent();
                    $image_save->order_detail_id = $id;
                    $image_save->image = 'rent_images/' . $imageName; // เก็บ path ไฟล์
                    $image_save->description = $request->input('note_image_')[$index] ?? null;
                    $image_save->save();
                }
            }
        }









        // อัปเดตข้อมูลการนัดลองชุด
        if ($request->input('fitting_id_')) {
            $fitting_id = $request->input('fitting_id_');
            $update_fitting = $request->input('update_fitting_');
            foreach ($fitting_id as $index => $id) {
                $update = Fitting::find($id);
                $update->fitting_date = $update_fitting[$index];
                $update->save();
            }
        }

        // บันทึกข้อมูลการนัดลองชุด
        if ($request->input('add_fitting_')) {
            $add_fitting = $request->input('add_fitting_');
            foreach ($add_fitting as $index => $date) {
                $add_fitting = new Fitting();
                $add_fitting->order_detail_id = $orderdetail->id;
                $add_fitting->fitting_date = $date;
                $add_fitting->fitting_status = 'ยังไม่มาลองชุด';
                $add_fitting->save();
            }
        }
        return redirect()->back()->with('success', 'จัดการสำเร็จ');
    }

    public function updatestatuspickuptotalrent(Request $request, $id)
    {
        $order = Order::find($id);
        $dataorderdetail = Orderdetail::where('order_id', $order->id)->get();
        foreach ($dataorderdetail as $detail) {
            if ($detail->type_order == 2 || $detail->type_order == 4) {
                if ($detail->status_detail == 'ถูกจอง') {
                    //ตารางorderdetail
                    $orderdetail = Orderdetail::find($detail->id);
                    $orderdetail->status_detail = "กำลังเช่า";
                    $orderdetail->save();
                    //ตารางorderdetailstatus
                    $create_status = new Orderdetailstatus();
                    $create_status->order_detail_id = $detail->id;
                    $create_status->status = "กำลังเช่า";
                    $create_status->save();

                    //ตารางreservation 
                    $reservation = Reservation::find($detail->reservation_id);
                    $reservation->status = 'กำลังเช่า';
                    $reservation->save();

                    // อัปเดตตารางdate
                    $date_id = Date::where('order_detail_id', $detail->id)
                        ->orderBy('created_at', 'desc')
                        ->value('id');
                    $update_real = Date::find($date_id);
                    $update_real->actua_pickup_date = now(); //วันที่รับจริงๆ
                    $update_real->save();


                    if ($detail->status_payment == 1) {
                        //ตารางpaymentstatus
                        $create_paymentstatus = new Paymentstatus();
                        $create_paymentstatus->order_detail_id = $detail->id;
                        $create_paymentstatus->payment_status = 2;
                        $create_paymentstatus->save();
                        //ตารางorderdetail
                        $orderdetail->status_payment = 2; //1จ่ายมัดจำ 2จ่ายเต็มจำนวน
                        $orderdetail->save();
                    }
                }
            } elseif ($detail->type_order == 3) {
                //ตารางorderdetail
                $orderdetail = Orderdetail::find($detail->id);
                $orderdetail->status_detail = "กำลังเช่า";
                $orderdetail->save();
                //ตารางorderdetailstatus
                $create_status = new Orderdetailstatus();
                $create_status->order_detail_id = $detail->id;
                $create_status->status = "กำลังเช่า";
                $create_status->save();

                //ตารางreservation 
                $reservation = Reservation::find($detail->reservation_id);
                $reservation->status = 'กำลังเช่า';
                $reservation->save();

                if ($detail->detail_many_one_re->jewelry_id) {
                    $find_re_filter = Reservationfilters::where('reservation_id', $detail->detail_many_one_re->id)->first();
                    $update_re_filter = Reservationfilters::find($find_re_filter->id);
                    $update_re_filter->status = 'กำลังเช่า';
                    $update_re_filter->save();

                    $update_status_jewelry = Jewelry::find($detail->detail_many_one_re->jewelry_id);
                    $update_status_jewelry->jewelry_status = 'กำลังถูกเช่า';
                    $update_status_jewelry->save();
                } elseif ($detail->detail_many_one_re->jewelry_set_id) {
                    $find_re_filter = Reservationfilters::where('reservation_id', $detail->detail_many_one_re->id)->get();
                    foreach ($find_re_filter as $item) {
                        $update_re_filter = Reservationfilters::find($item->id);
                        $update_re_filter->status = 'กำลังเช่า';
                        $update_re_filter->save();
                    }
                    $jew_item_total = Jewelrysetitem::where('jewelry_set_id', $detail->detail_many_one_re->jewelry_set_id)->get();
                    foreach ($jew_item_total as $item) {
                        $update_status_jew = Jewelry::find($item->jewelry_id);
                        $update_status_jew->jewelry_status = 'กำลังถูกเช่า';
                        $update_status_jew->save();
                    }
                }

                // อัปเดตตารางdate
                $date_id = Date::where('order_detail_id', $detail->id)
                    ->orderBy('created_at', 'desc')
                    ->value('id');
                $update_real = Date::find($date_id);
                $update_real->actua_pickup_date = now(); //วันที่รับจริงๆ
                $update_real->save();
                if ($detail->status_payment == 1) {
                    //ตารางpaymentstatus
                    $create_paymentstatus = new Paymentstatus();
                    $create_paymentstatus->order_detail_id = $detail->id;
                    $create_paymentstatus->payment_status = 2;
                    $create_paymentstatus->save();
                    //ตารางorderdetail
                    $orderdetail->status_payment = 2; //1จ่ายมัดจำ 2จ่ายเต็มจำนวน
                    $orderdetail->save();
                }
            }
        }



        // สร้างใบเสร็จรวม
        $total_price_receipt = 0;
        $sum_decoration = 0;
        foreach ($dataorderdetail as $item) {
            $decoration = Decoration::where('order_detail_id', $item->id)->get();
            foreach ($decoration as $value) {
                $sum_decoration += $value->decoration_price;
            }
        }


        foreach ($dataorderdetail as $item) {
            $check_payment = Paymentstatus::where('order_detail_id', $item->id)
                ->where('payment_status', 1)
                ->exists();
            if ($check_payment) {
                $total_price_receipt +=  ($item->price - $item->deposit) + $item->damage_insurance;
            }
        }


        $ceate_receipt = new Receipt();
        $ceate_receipt->order_id = $order->id;
        $ceate_receipt->receipt_type = 2;
        $ceate_receipt->employee_id = Auth::user()->id;
        $ceate_receipt->total_price = $total_price_receipt + $sum_decoration;
        $ceate_receipt->save();


        return redirect()->back()->with('success', 'สำเร็จแล้ว');
    }
}
