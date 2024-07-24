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
use App\Models\Shirtitem;
use App\Models\Skirtitem;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManageorderController extends Controller
{
    //หน้าเพิ่มออเดอร์เช่าชุด เลือกประเภทชุด
    public function typerentdress()
    {
        $typedress = Typedress::all();
        return view('Employee.typerentdress', compact('typedress'));
    }

    //หน้าเพิ่มออเดอร์เช่าชุด หลังจากที่เลือกประเภทชุดแล้ว
    public function typerentdressshow($id)
    {
        $type_dress_id = $id;
        $type_dress_name = Typedress::where('id', $id)->select('type_dress_name', 'specific_letter')->first();
        $search_separable = null;
        // $dress = Dress::where('type_dress_id', $id)->with('dressimages')->get();
        $dress = Dress::where('type_dress_id', $id)->with(['dressimages', 'dressmeasurements', 'dressmeasurementnows', 'shirtitems', 'skirtitems'])->get();
        // dd($dress) ; 
        return view('Employee.typerentdressshow', compact('dress', 'type_dress_name', 'type_dress_id', 'search_separable'));


        // $shirtitems_id  = App\Models\Shirtitem::where('dress_id',$item->id)->value('id') ;  //3 

        // $mea = App\Models\Dressmeasurement::where('shirtitems_id',App\Models\Shirtitem::where('dress_id',$item->id)->value('id'))->get() ; 

        // $mea = App\Models\Dressmeasurementnow::where('dress_id',$item->id)->get()

        // $find = App\Models\Shirtitem::where('dress_id',$item->id)->value('shirtitem_status')




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
















    //หน้าเพิ่มออเดอร์เช่าเครื่องประดับ เลือกประเภทเครื่องประดับ
    public function typerentjewelry()
    {
        $typejewelry = Typejewelry::all();
        return view('employee.typerentjewelry', compact('typejewelry'));
    }
    public function typerentjewelryshow($id)
    {

        $type_jewelry_name = Typejewelry::where('id', $id)->select('type_jewelry_name', 'specific_letter')->first();


        $jewelry = Jewelry::where('type_jewelry_id', $id)->with('jewelryimages')->get();
        return view('Employee.typerentjewelryshow', compact('type_jewelry_name', 'jewelry'));
    }





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
                $create_order->save();
                //อัปเดตตาราง dress
                $update_dress = Dress::find($dress_id);
                $update_dress->dress_status = "อยู่ในตะกร้า";
                $update_dress->save();
            } elseif ($separable == 2) {
                //แยกได้

                //ทั้งชุด
                if ($dress_id) {
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
                    $create_order->save();
                    //อัปเดตตาราง dress
                    $update_dress = Dress::find($dress_id);
                    $update_dress->dress_status = "อยู่ในตะกร้าทั้งหมด";
                    $update_dress->save();
                    // ตาราง shirt
                    $update_id_shirt = Shirtitem::where('dress_id',$dress_id)->value('id') ; 
                    $update_shirt = Shirtitem::find($update_id_shirt) ; 
                    $update_shirt->shirtitem_status = "อยู่ในตะกร้า" ; 
                    $update_shirt->save() ; 
                    // ตารางskirt
                    $update_id_skirt = Skirtitem::where('dress_id',$dress_id)->value('id') ; 
                    $update_skirt = Skirtitem::find($update_id_skirt) ; 
                    $update_skirt->skirtitem_status = "อยู่ในตะกร้า" ;
                    $update_skirt->save() ; 
                }

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
                    $create_order->save();
                    // อัปเดตตารางshirt
                    $update_shirt = Shirtitem::find($shirtitem_id);
                    $update_shirt->shirtitem_status = "อยู่ในตะกร้า";
                    $update_shirt->save();
                    //อัปเดตตาราง dress
                    $check = Skirtitem::where('dress_id', $dress_id)->value('skirtitem_status');
                    if ($check == "พร้อมให้เช่า") {
                        $update_dress = Dress::find($dress_id);
                        $update_dress->dress_status = "อยู่ในตะกร้าบางส่วน";
                        $update_dress->save();
                    } else {
                        $update_dress = Dress::find($dress_id);
                        $update_dress->dress_status = "อยู่ในตะกร้าทั้งหมด";
                        $update_dress->save();
                    }
                }

                //กระโปรง
                if ($skirtitem_id) {
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
                    $create_order->save();

                    // อัปเดตตารางskirt
                    $update_skirt = Skirtitem::find($skirtitem_id);
                    $update_skirt->skirtitem_status = "อยู่ในตะกร้า";
                    $update_skirt->save();
                    //อัปเดตตาราง dress
                    $check = Shirtitem::where('dress_id', $dress_id)->value('shirtitem_status');
                    if ($check == "พร้อมให้เช่า") {
                        $update_dress = Dress::find($dress_id);
                        $update_dress->dress_status = "อยู่ในตะกร้าบางส่วน";
                        $update_dress->save();
                    } else {
                        $update_dress = Dress::find($dress_id);
                        $update_dress->dress_status = "อยู่ในตะกร้าทั้งหมด";
                        $update_dress->save();
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
                $create_order->save();
                //อัปเดตตาราง dress
                $update_dress = Dress::find($dress_id);
                $update_dress->dress_status = "อยู่ในตะกร้า";
                $update_dress->save();
            } elseif ($separable == 2) {
                //แยกได้
                //ทั้งชุด
                if ($dress_id) {
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
                    $create_order->save();
                    //อัปเดตตาราง dress
                    $update_dress = Dress::find($dress_id);
                    $update_dress->dress_status = "อยู่ในตะกร้าทั้งหมด";
                    $update_dress->save();
                    // ตาราง shirt
                    $update_id_shirt = Shirtitem::where('dress_id',$dress_id)->value('id') ; 
                    $update_shirt = Shirtitem::find($update_id_shirt) ; 
                    $update_shirt->shirtitem_status = "อยู่ในตะกร้า" ; 
                    $update_shirt->save() ; 
                    // ตารางskirt
                    $update_id_skirt = Skirtitem::where('dress_id',$dress_id)->value('id') ; 
                    $update_skirt = Skirtitem::find($update_id_skirt) ; 
                    $update_skirt->skirtitem_status = "อยู่ในตะกร้า" ;
                    $update_skirt->save() ; 
                }
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
                    $create_order->save();
                    // อัปเดตตารางshirt
                    $update_shirt = Shirtitem::find($shirtitem_id);
                    $update_shirt->shirtitem_status = "อยู่ในตะกร้า";
                    $update_shirt->save();
                    //อัปเดตตาราง dress
                    $check = Skirtitem::where('dress_id', $dress_id)->value('skirtitem_status');
                    if ($check == "พร้อมให้เช่า") {
                        $update_dress = Dress::find($dress_id);
                        $update_dress->dress_status = "อยู่ในตะกร้าบางส่วน";
                        $update_dress->save();
                    } else {
                        $update_dress = Dress::find($dress_id);
                        $update_dress->dress_status = "อยู่ในตะกร้าทั้งหมด";
                        $update_dress->save();
                    }
                }
                if ($skirtitem_id) {
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
                    $create_order->save();

                    // อัปเดตตารางskirt
                    $update_skirt = Skirtitem::find($skirtitem_id);
                    $update_skirt->skirtitem_status = "อยู่ในตะกร้า";
                    $update_skirt->save();
                    //อัปเดตตาราง dress
                    $check = Shirtitem::where('dress_id', $dress_id)->value('shirtitem_status');
                    if ($check == "พร้อมให้เช่า") {
                        $update_dress = Dress::find($dress_id);
                        $update_dress->dress_status = "อยู่ในตะกร้าบางส่วน";
                        $update_dress->save();
                    } else {
                        $update_dress = Dress::find($dress_id);
                        $update_dress->dress_status = "อยู่ในตะกร้าทั้งหมด";
                        $update_dress->save();
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

            //เลือกอื่นๆ ต้องกรอก   ประเภทชุดที่เลือกตัด
            if ($request->input('type_dress') == 'other_type') {
                $checkdouble = Typedress::where('type_dress_name', $request->input('other_type'))->first();
                if ($checkdouble) {
                    $TYPE_DRESS_NAME = $request->input('other_type');
                } else {
                    //สร้างอักษรมา 1 ตัว 
                    do {
                        $random = chr(65 + rand(0, 25));
                        $check = Typedress::where('specific_letter', $random)->first();
                    } while ($check);
                    $character = $random; //ได้ตัวอักษรมาแล้ว 
                    $create_id_of_typedress = new Typedress();
                    $create_id_of_typedress->type_dress_name = $request->input('other_input');
                    $create_id_of_typedress->specific_letter = $character;
                    $create_id_of_typedress->save();
                    $TYPE_DRESS_NAME = $request->input('other_input');

                    //ลบประเภทชุดเดิมที่เคยสร้าง
                    $ID_FOR_TYPE_NAME = Typedress::where('type_dress_name', $orderdetail->type_dress)->value('id');  //ได้ id มาแล้ว
                    $find_for_delete = Dress::where('type_dress_id', $ID_FOR_TYPE_NAME)->first(); //ค้นหาว่าid ของประเภทชุดมันมีไหม F
                    if (!$find_for_delete) {
                        $delete_type_name_now = Typedress::find($ID_FOR_TYPE_NAME);
                        $delete_type_name_now->delete(); //ลบประเภทชุดที่เคยเลือกแล้ว
                    }
                }
            }
            // เลือกในดรอปดาว  ประเภทชุดที่เลือกตัด
            else {
                $TYPE_DRESS_NAME = $request->input('type_dress');
                //เช็คว่าเลือกอันเดิมไหม ถ้าไม่เลือกอันเดิม จะลบ ประเภทชุดทิ้ง
                if ($request->input('type_dress') != $orderdetail->type_dress) {
                    $ID_FOR_TYPE_NAME = Typedress::where('type_dress_name', $orderdetail->type_dress)->value('id');  //ได้ id มาแล้ว
                    $find_for_delete = Dress::where('type_dress_id', $ID_FOR_TYPE_NAME)->first(); //ค้นหาว่าid ของประเภทชุดมันมีไหม 
                    if (!$find_for_delete) {
                        $delete_type_name_now = Typedress::find($ID_FOR_TYPE_NAME);
                        $delete_type_name_now->delete(); //ลบประเภทชุดที่เคยเลือกแล้ว
                    }
                }
            }

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
            $update_order_detail->title_name = 'ตัด' . $TYPE_DRESS_NAME;
            $update_order_detail->type_dress = $TYPE_DRESS_NAME;
            $update_order_detail->pickup_date = $request->input('update_pickup_date');
            $update_order_detail->amount = $request->input('update_amount');

            if ($request->input('update_deposit') > $request->input('update_price')) {
                DB::rollback();
                return redirect()->back()->with('fail', "ราคาตัดต้องมากกว่าราคามัดจำ");
            } elseif ($request->input('update_deposit') <= $request->input('update_price')) {
                $update_order_detail->price = $request->input('update_price');
                $update_order_detail->deposit = $request->input('update_deposit');
            }
            $update_order_detail->note = $request->input('update_note');
            $update_order_detail->color = $request->input('update_color');
            $update_order_detail->cloth = $request->input('update_cloth');
            $update_order_detail->status_payment = $request->input('update_status_payment');
            $update_order_detail->save();

            //อัปเดตตาราง date
            $find_id_in_date = Date::where('order_detail_id', $id)->first();
            $find_id_in_date->pickup_date = $request->input('update_pickup_date');
            $find_id_in_date->save();


            //อัปเดตตารางstatus_payment
            $find_id_in_payment = Paymentstatus::where('order_detail_id', $id)->first();
            $find_id_in_payment->payment_status = $request->input('update_status_payment');
            $find_id_in_payment->save();

            //อัปเดตตาราง financial
            // $find_id_in_financial = Financial::where('order_detail_id', $id)->first();
            // if ($request->input('update_status_payment') == 1) {
            //     $text_update_financial = "จ่ายมัดจำ";
            //     $income = $request->input('update_deposit') * $request->input('update_amount');
            // } else {
            //     $text_update_financial = "จ่ายเต็ม";
            //     $income = $request->input('update_price') * $request->input('update_amount');
            // }
            // $find_id_in_financial->item_name = $text_update_financial . '(ตัดชุด)';
            // $find_id_in_financial->type_order = $orderdetail->type_order;
            // $find_id_in_financial->financial_income = $income;
            // $find_id_in_financial->save();

            //อัปเดตข้อมูลการวัดmeasurement

            if ($request->input('mea_id_')) {
                $id_for_mea = $request->input('mea_id_'); //ตัวหมุน
                $update_name_mea = $request->input('mea_name_');
                $update_number_mea = $request->input('mea_number_');
                $update_unit_mea = $request->input('mea_unit_');
                foreach ($id_for_mea as $index => $id_for_mea_table) {
                    $update_data = Measurementorderdetail::find($id_for_mea_table);
                    $update_data->measurement_name = $update_name_mea[$index];
                    $update_data->measurement_number = $update_number_mea[$index];
                    $update_data->measurement_unit = $update_unit_mea[$index];
                    $update_data->save();
                }
            }
            //อัปเดตตาราการนัดลองชุด
            if ($request->input('fitting_id_')) {
                $id_for_fitting = $request->input('fitting_id_'); //ตัวหมุน
                $update_fitting_date = $request->input('fitting_date_');
                $update_fitting_note = $request->input('fitting_note_');
                foreach ($id_for_fitting as $index => $id_for_fitting) {
                    if ($update_fitting_date[$index] != null) {
                        $update_data = Fitting::find($id_for_fitting);
                        $update_data->fitting_date = $update_fitting_date[$index];
                        $update_data->fitting_note = $update_fitting_note[$index];
                        $update_data->save();
                    }
                }
            }

            //บันทึกข้อมูลการวัด
            if ($request->input('add_mea_name_')) {
                $add_mea_name = $request->input('add_mea_name_'); //ตัวหมุน
                $add_mea_number = $request->input('add_mea_number_');
                $add_mea_unit = $request->input('add_mea_unit_');
                foreach ($add_mea_name as $index => $add) {
                    $add_measurement = new Measurementorderdetail();
                    $add_measurement->order_detail_id = $id;
                    $add_measurement->measurement_name = $add;
                    $add_measurement->measurement_number = $add_mea_number[$index];
                    $add_measurement->measurement_unit = $add_mea_unit[$index];
                    $add_measurement->save();
                }
            }

            //เพิ่มข้อมูลการนัดลองชุด
            if ($request->input('add_fitting_date_')) {
                $add_fitting_date = $request->input('add_fitting_date_'); //ตัวหมุน
                $add_fitting_note = $request->input('add_fitting_note_');
                foreach ($add_fitting_date as $index => $add) {
                    $add_fitting = new Fitting();
                    $add_fitting->order_detail_id = $id;
                    $add_fitting->fitting_date = $add;
                    $add_fitting->fitting_note = $add_fitting_note[$index];
                    $add_fitting->save();
                }
            }

            //เพิ่มรูปภาพ 
            if ($request->hasFile('add_image_')) {
                $add_image = $request->file('add_image_');
                $img = $request->file('add_image_');
                foreach ($add_image as $index => $img) {
                    $add = new Imagerent();
                    $add->order_detail_id = $id;
                    $add->image = $img->store('rent_images', 'public');
                    $add->save();
                }
            }

            DB::commit();
            return redirect()->back()->with('success', $TYPE_DRESS_NAME);
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    //บันทึกของเช่าชุดsavemanageitemrentdress
    public function savemanageitemrentdress(Request $request, $id)
    {
        //ตาราง orderdetail
        $orderdetail = Orderdetail::find($id);
        $orderdetail->late_charge = $request->input('update_late_charge');
        $orderdetail->pickup_date = $request->input('update_pickup_date');
        $orderdetail->return_date = $request->input('update_return_date');
        $orderdetail->note = $request->input('note');
        $orderdetail->damage_insurance = $request->input('update_damage_insurance'); //ประกันค่าเสียหาย 
        $orderdetail->status_payment = $request->input('update_status_payment');
        $orderdetail->save();

        // ตารางdate
        $date_check = Date::where('order_detail_id', $id)->first();
        if ($date_check) {
            $update_date = Date::find($date_check->id);
            $update_date->pickup_date = $request->input('update_pickup_date');
            $update_date->pickup_date = $request->input('update_pickup_date');
            $update_date->return_date = $request->input('update_return_date');
            $update_date->save();
        } else {
            $create_date = new Date();
            $create_date->order_detail_id = $id;
            $create_date->pickup_date = $request->input('update_pickup_date');
            $create_date->return_date = $request->input('update_return_date');
            $create_date->save();
        }

        // ตารางpaymentstatus
        $payment_status = Paymentstatus::where('order_detail_id', $id)->first();
        if ($payment_status) {
            $update_payment_status = Paymentstatus::find($payment_status->id);
            $update_payment_status->payment_status = $request->input('update_status_payment');
            $update_payment_status->save();
        } else {
            $create_payment_status = new Paymentstatus();
            $create_payment_status->order_detail_id = $id;
            $create_payment_status->payment_status = $request->input('update_status_payment');
            $create_payment_status->save();
        }

        //อัปเดตตาราง financial
        // $check_financial = Financial::where('order_detail_id', $id)->first();
        // if ($check_financial) { //มีข้อมูล
        //     $financial = Financial::find($check_financial->id);
        //     if ($request->input('update_status_payment') == 1) {
        //         $financial->item_name = 'จ่ายมัดจำ(เช่าชุด)';
        //         $financial->type_order = $orderdetail->type_order;
        //         $financial->financial_income = $request->input('update_deposit') * $request->input('update_amount');
        //         $financial->financial_expenses = 0;
        //         $financial->save();
        //     } elseif ($request->input('update_status_payment') == 2) {
        //         $financial->item_name = 'จ่ายเต็ม(เช่าชุด)';
        //         $financial->type_order = $orderdetail->type_order;
        //         $financial->financial_income = $request->input('update_price') * $request->input('update_amount');
        //         $financial->financial_expenses = 0;
        //         $financial->save();
        //     }
        // } else { //ไม่มีข้อมูล
        //     if ($request->input('update_status_payment') == 1) {
        //         $text = "จ่ายมัดจำ";
        //         $income = $request->input('update_deposit') * $request->input('update_amount');
        //     } else {
        //         $text = "จ่ายเต็ม";
        //         $income = $request->input('update_price') * $request->input('update_amount');
        //     }
        //     $create_financial = new Financial();
        //     $create_financial->order_detail_id = $id;
        //     $create_financial->item_name = $text . 'เช่าชุด';
        //     $create_financial->type_order = $orderdetail->type_order;
        //     $create_financial->financial_income = $income;
        //     $create_financial->financial_expenses = 0;
        //     $create_financial->save();
        // }


        //อัปเดตข้อมูลการวัดในตาราง meaของ dress
        if ($request->input('mea_dress_id_')) {
            $update_mea_dress_id = $request->input('mea_dress_id_'); //ตัวหมุน
            $update_mea_dress_name = $request->input('mea_dress_name_');
            $update_mea_dress_number = $request->input('mea_dress_number_');
            $update_mea_dress_unit = $request->input('mea_dress_unit_');
            foreach ($update_mea_dress_id as $index => $id_for_mea_dress) {
                $update_mea_dress = Dressmeasurement::find($id_for_mea_dress);
                $update_mea_dress->measurement_dress_name = $update_mea_dress_name[$index];
                $update_mea_dress->measurement_dress_number = $update_mea_dress_number[$index];
                $update_mea_dress->measurement_dress_unit = $update_mea_dress_unit[$index];
                $update_mea_dress->save();
            }
        }

        //อัปเดตข้อมูลการวัดในตาราง meaของ orderdetail
        // dd($request->input('mea_orderdetail_id_')) ; 
        if ($request->input('mea_orderdetail_id_')) {
            $update_mea_orderdetail_id = $request->input('mea_orderdetail_id_'); //ตัวหมุน
            $update_mea_orderdetail_name = $request->input('mea_orderdetail_name_');
            $update_mea_orderdetail_number = $request->input('mea_orderdetail_number_');
            $update_mea_orderdetail_unit = $request->input('mea_orderdetail_unit_');
            foreach ($update_mea_orderdetail_id as $index => $id_for_orderdetail_mea) {
                $update_data = Measurementorderdetail::find($id_for_orderdetail_mea);
                $update_data->measurement_name = $update_mea_orderdetail_name[$index];
                $update_data->measurement_number = $update_mea_orderdetail_number[$index];
                $update_data->measurement_unit = $update_mea_orderdetail_unit[$index];
                $update_data->save();
            }
        }

        //เพิ่มข้อมูลการวัด
        if ($request->input('add_mea_name_')) {
            $add_mea_name = $request->input('add_mea_name_'); //ตัวหมุน
            $add_mea_number = $request->input('add_mea_number_');
            $add_mea_unit = $request->input('add_mea_unit_');
            foreach ($add_mea_name as $index => $mea_name) {
                $create_mea = new Measurementorderdetail();
                $create_mea->order_detail_id = $id;
                $create_mea->measurement_name = $mea_name;
                $create_mea->measurement_number = $add_mea_number[$index];
                $create_mea->measurement_unit = $add_mea_unit[$index];
                $create_mea->save();
            }
        }

        //อัปเดตตาราการนัดลองชุด
        if ($request->input('fitting_id_')) {
            $id_for_fitting = $request->input('fitting_id_'); //ตัวหมุน
            $update_fitting_date = $request->input('fitting_date_');
            $update_fitting_note = $request->input('fitting_note_');
            foreach ($id_for_fitting as $index => $id_for_fitting) {
                if ($update_fitting_date[$index] != null) {
                    $update_data = Fitting::find($id_for_fitting);
                    $update_data->fitting_date = $update_fitting_date[$index];
                    $update_data->fitting_note = $update_fitting_note[$index];
                    $update_data->save();
                }
            }
        }
        //เพิ่มข้อมูลการนัดลองชุด
        if ($request->input('add_fitting_date_')) {
            $add_fitting_date = $request->input('add_fitting_date_'); //ตัวหมุน
            $add_fitting_note = $request->input('add_fitting_note_');
            foreach ($add_fitting_date as $index => $add) {
                $add_fitting = new Fitting();
                $add_fitting->order_detail_id = $id;
                $add_fitting->fitting_date = $add;
                $add_fitting->fitting_note = $add_fitting_note[$index];
                $add_fitting->save();
            }
        }
        //เพิ่มรูปภาพ 
        if ($request->hasFile('add_image_')) {
            $add_image = $request->file('add_image_');
            $img = $request->file('add_image_');
            foreach ($add_image as $index => $img) {
                $add = new Imagerent();
                $add->order_detail_id = $id;
                $add->image = $img->store('rent_images', 'public');
                $add->save();
            }
        }
        return redirect()->back()->with('success', "บันทึกข้อมูล");
    }

    //บันทึกของเช่าเครื่องประดับsavemanageitemrentjewelry
    public function savemanageitemrentjewelry(Request $request, $id)
    {
        //ตาราง orderdetail
        $orderdetail = Orderdetail::find($id);
        $orderdetail->late_charge = $request->input('update_late_charge');
        $orderdetail->pickup_date = $request->input('update_pickup_date');
        $orderdetail->return_date = $request->input('update_return_date');
        $orderdetail->note = $request->input('note');
        $orderdetail->damage_insurance = $request->input('update_damage_insurance'); //ประกันค่าเสียหาย 
        $orderdetail->status_payment = $request->input('update_status_payment');
        $orderdetail->save();
        // ตารางdate
        $date_check = Date::where('order_detail_id', $id)->first();
        if ($date_check) {
            $update_date = Date::find($date_check->id);
            $update_date->pickup_date = $request->input('update_pickup_date');
            $update_date->pickup_date = $request->input('update_pickup_date');
            $update_date->return_date = $request->input('update_return_date');
            $update_date->save();
        } else {
            $create_date = new Date();
            $create_date->order_detail_id = $id;
            $create_date->pickup_date = $request->input('update_pickup_date');
            $create_date->return_date = $request->input('update_return_date');
            $create_date->save();
        }

        // ตารางpaymentstatus
        $payment_status = Paymentstatus::where('order_detail_id', $id)->first();
        if ($payment_status) {
            $update_payment_status = Paymentstatus::find($payment_status->id);
            $update_payment_status->payment_status = $request->input('update_status_payment');
            $update_payment_status->save();
        } else {
            $create_payment_status = new Paymentstatus();
            $create_payment_status->order_detail_id = $id;
            $create_payment_status->payment_status = $request->input('update_status_payment');
            $create_payment_status->save();
        }

        //อัปเดตตาราง financial
        // $check_financial = Financial::where('order_detail_id', $id)->first();

        // if ($check_financial) { //มีข้อมูล
        //     $financial = Financial::find($check_financial->id);
        //     if ($request->input('update_status_payment') == 1) {
        //         $financial->item_name = 'จ่ายมัดจำ(เช่าเครื่องประดับ)';
        //         $financial->type_order = $orderdetail->type_order;
        //         $financial->financial_income = $request->input('update_deposit') * $request->input('update_amount');
        //         $financial->financial_expenses = 0;
        //         $financial->save();
        //     } elseif ($request->input('update_status_payment') == 2) {
        //         $financial->item_name = 'จ่ายเต็ม(เช่าเครื่องประดับ)';
        //         $financial->type_order = $orderdetail->type_order;
        //         $financial->financial_income = $request->input('update_price') * $request->input('update_amount');
        //         $financial->financial_expenses = 0;
        //         $financial->save();
        //     }
        // } else { //ไม่มีข้อมูล
        //     if ($request->input('update_status_payment') == 1) {
        //         $text = "จ่ายมัดจำ";
        //         $income = $request->input('update_deposit') * $request->input('update_amount');
        //     } else {
        //         $text = "จ่ายเต็ม";
        //         $income = $request->input('update_price') * $request->input('update_amount');
        //     }
        //     $create_financial = new Financial();
        //     $create_financial->order_detail_id = $id;
        //     $create_financial->item_name = $text . 'เช่าเครื่องประดับ';
        //     $create_financial->type_order = $orderdetail->type_order;
        //     $create_financial->financial_income = $income;
        //     $create_financial->financial_expenses = 0;
        //     $create_financial->save();
        // }



        //เพิ่มรูปภาพ 
        if ($request->hasFile('add_image_')) {
            $add_image = $request->file('add_image_');
            $img = $request->file('add_image_');
            foreach ($add_image as $index => $img) {
                $add = new Imagerent();
                $add->order_detail_id = $id;
                $add->image = $img->store('rent_images', 'public');
                $add->save();
            }
        }
        return redirect()->back()->with('success', 'สำเร็จ !');
    }


    //บันทึกของเช่าตัดชุดsavemanageitemcutrent
    public function savemanageitemcutrent(Request $request, $id)
    {

        DB::beginTransaction();
        try {

            $orderdetail = Orderdetail::find($id);  //ค้นหา order_detail_id ในตาราง orderdetail 

            //เลือกอื่นๆ ต้องกรอก   ประเภทชุดที่เลือกตัด
            if ($request->input('type_dress') == 'other_type') {
                $checkdouble = Typedress::where('type_dress_name', $request->input('other_type'))->first();
                if ($checkdouble) {
                    $TYPE_DRESS_NAME = $request->input('other_type');
                } else {
                    //สร้างอักษรมา 1 ตัว 
                    do {
                        $random = chr(65 + rand(0, 25));
                        $check = Typedress::where('specific_letter', $random)->first();
                    } while ($check);
                    $character = $random; //ได้ตัวอักษรมาแล้ว 
                    $create_id_of_typedress = new Typedress();
                    $create_id_of_typedress->type_dress_name = $request->input('other_input');
                    $create_id_of_typedress->specific_letter = $character;
                    $create_id_of_typedress->save();
                    $TYPE_DRESS_NAME = $request->input('other_input');

                    //ลบประเภทชุดเดิมที่เคยสร้าง
                    $ID_FOR_TYPE_NAME = Typedress::where('type_dress_name', $orderdetail->type_dress)->value('id');  //ได้ id มาแล้ว
                    $find_for_delete = Dress::where('type_dress_id', $ID_FOR_TYPE_NAME)->first(); //ค้นหาว่าid ของประเภทชุดมันมีไหม F
                    if (!$find_for_delete) {
                        $delete_type_name_now = Typedress::find($ID_FOR_TYPE_NAME);
                        $delete_type_name_now->delete(); //ลบประเภทชุดที่เคยเลือกแล้ว
                    }
                }
            }
            // เลือกในดรอปดาว  ประเภทชุดที่เลือกตัด
            else {
                $TYPE_DRESS_NAME = $request->input('type_dress');
                //เช็คว่าเลือกอันเดิมไหม ถ้าไม่เลือกอันเดิม จะลบ ประเภทชุดทิ้ง
                if ($request->input('type_dress') != $orderdetail->type_dress) {
                    $ID_FOR_TYPE_NAME = Typedress::where('type_dress_name', $orderdetail->type_dress)->value('id');  //ได้ id มาแล้ว
                    $find_for_delete = Dress::where('type_dress_id', $ID_FOR_TYPE_NAME)->first(); //ค้นหาว่าid ของประเภทชุดมันมีไหม 
                    if (!$find_for_delete) {
                        $delete_type_name_now = Typedress::find($ID_FOR_TYPE_NAME);
                        $delete_type_name_now->delete(); //ลบประเภทชุดที่เคยเลือกแล้ว
                    }
                }
            }

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
            $update_order_detail->title_name = 'เช่าตัด' . $TYPE_DRESS_NAME;
            $update_order_detail->type_dress = $TYPE_DRESS_NAME;
            $update_order_detail->pickup_date = $request->input('update_pickup_date');
            $update_order_detail->return_date = $request->input('update_return_date');
            $update_order_detail->return_date = $request->input('update_return_date');
            $update_order_detail->amount = $request->input('update_amount');

            $update_order_detail->late_charge = $request->input('update_late_charge');
            $update_order_detail->damage_insurance = $request->input('update_damage_insurance');

            if ($request->input('update_deposit') > $request->input('update_price')) {
                DB::rollback();
                return redirect()->back()->with('fail', "ราคาตัดต้องมากกว่าราคามัดจำ");
            } elseif ($request->input('update_deposit') <= $request->input('update_price')) {
                $update_order_detail->price = $request->input('update_price');
                $update_order_detail->deposit = $request->input('update_deposit');
            }
            $update_order_detail->note = $request->input('update_note');
            $update_order_detail->color = $request->input('update_color');
            $update_order_detail->status_payment = $request->input('update_status_payment');
            $update_order_detail->save();

            //อัปเดตตาราง date
            $find_id_in_date = Date::where('order_detail_id', $id)->first();
            $find_id_in_date->pickup_date = $request->input('update_pickup_date');
            $find_id_in_date->save();


            //อัปเดตตารางstatus_payment
            $find_id_in_payment = Paymentstatus::where('order_detail_id', $id)->first();
            $find_id_in_payment->payment_status = $request->input('update_status_payment');
            $find_id_in_payment->save();

            //อัปเดตตาราง financial
            // $find_id_in_financial = Financial::where('order_detail_id', $id)->first();
            // if ($request->input('update_status_payment') == 1) {
            //     $text_update_financial = "จ่ายมัดจำ";
            //     $income = $request->input('update_deposit') * $request->input('update_amount');
            // } else {
            //     $text_update_financial = "จ่ายเต็ม";
            //     $income = $request->input('update_price') * $request->input('update_amount');
            // }
            // $find_id_in_financial->item_name = $text_update_financial . '(เช่าตัดชุด)';
            // $find_id_in_financial->type_order = $orderdetail->type_order;
            // $find_id_in_financial->financial_income = $income;
            // $find_id_in_financial->save();

            //อัปเดตข้อมูลการวัดmeasurement
            if ($request->input('mea_orderdetail_id_')) {
                $id_for_mea = $request->input('mea_orderdetail_id_'); //ตัวหมุน
                $update_name_mea = $request->input('mea_orderdetail_name_');
                $update_number_mea = $request->input('mea_orderdetail_number_');
                $update_unit_mea = $request->input('mea_orderdetail_unit_');
                foreach ($id_for_mea as $index => $id_for_mea_table) {
                    $update_data = Measurementorderdetail::find($id_for_mea_table);
                    $update_data->measurement_name = $update_name_mea[$index];
                    $update_data->measurement_number = $update_number_mea[$index];
                    $update_data->measurement_unit = $update_unit_mea[$index];
                    $update_data->save();
                }
            }
            //อัปเดตตาราการนัดลองชุด

            if ($request->input('fitting_id_')) {
                $id_for_fitting = $request->input('fitting_id_'); //ตัวหมุน
                $update_fitting_date = $request->input('fitting_date_');
                $update_fitting_note = $request->input('fitting_note_');
                foreach ($id_for_fitting as $index => $id_for_fitting) {
                    if ($update_fitting_date[$index] != null) {
                        $update_data = Fitting::find($id_for_fitting);
                        $update_data->fitting_date = $update_fitting_date[$index];
                        $update_data->fitting_note = $update_fitting_note[$index];
                        $update_data->save();
                    }
                }
            }

            //บันทึกข้อมูลการวัด
            if ($request->input('add_mea_name_')) {
                $add_mea_name = $request->input('add_mea_name_'); //ตัวหมุน
                $add_mea_number = $request->input('add_mea_number_');
                $add_mea_unit = $request->input('add_mea_unit_');
                foreach ($add_mea_name as $index => $add) {
                    $add_measurement = new Measurementorderdetail();
                    $add_measurement->order_detail_id = $id;
                    $add_measurement->measurement_name = $add;
                    $add_measurement->measurement_number = $add_mea_number[$index];
                    $add_measurement->measurement_unit = $add_mea_unit[$index];
                    $add_measurement->save();
                }
            }

            //เพิ่มข้อมูลการนัดลองชุด
            if ($request->input('add_fitting_date_')) {
                $add_fitting_date = $request->input('add_fitting_date_'); //ตัวหมุน
                $add_fitting_note = $request->input('add_fitting_note_');
                foreach ($add_fitting_date as $index => $add) {
                    $add_fitting = new Fitting();
                    $add_fitting->order_detail_id = $id;
                    $add_fitting->fitting_date = $add;
                    $add_fitting->fitting_note = $add_fitting_note[$index];
                    $add_fitting->save();
                }
            }

            //เพิ่มรูปภาพ 
            if ($request->hasFile('add_image_')) {
                $add_image = $request->file('add_image_');
                $img = $request->file('add_image_');
                foreach ($add_image as $index => $img) {
                    $add = new Imagerent();
                    $add->order_detail_id = $id;
                    $add->image = $img->store('rent_images', 'public');
                    $add->save();
                }
            }

            DB::commit();
            return redirect()->back()->with('success', $TYPE_DRESS_NAME);
        } catch (\Exception $e) {
            DB::rollback();
        }
    }
}
