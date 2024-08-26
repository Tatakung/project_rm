<?php

namespace App\Http\Controllers;

use App\Models\Date;
use App\Models\Dress;
use App\Models\Dressimage;
use App\Models\Dressmeasurement;
use App\Models\Financial;
use App\Models\Fitting;
use App\Models\Imagerent;
use App\Models\Jewelry;
use App\Models\Jewelryimage;
use App\Models\Measurementorderdetail;
use App\Models\Order;
use App\Models\Orderdetail;
use App\Models\Orderdetailstatus;
use App\Models\Paymentstatus;
use App\Models\Shirtitem;
use App\Models\Skirtitem;
use App\Models\Clean;

use App\Models\Typedress;

use App\Models\Customer;
use App\Models\Dressmeasurementnow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    //
    public function homepage()
    {

        return view('employee.employeehome');
    }


    public function addorder()
    {
        // $dress = 

        return view('Employee.addorder');
    }

    public function selectdate()
    {
        return view('employee.seletedate');
    }


    public function clean()
    {
        $clean = Clean::all();
        $countwait = Clean::where('clean_status', 'รอดำเนินการ')->count();
        $countdoing = Clean::where('clean_status', 'กำลังส่งซัก')->count();
        $countsuccess = Clean::where('clean_status', 'ซักเสร็จแล้ว')->count();
        return view('employee.clean', compact('clean', 'countwait', 'countdoing', 'countsuccess'));
    }




    public function cleanupdatestatus(Request $request)
    {
        $select_item = $request->input('select_item_');
        if ($select_item == null) {
            return redirect()->back()->with('fail', 'กรุณาเลือกรายการที่ต้องการอัพเดต');
        }
        foreach ($select_item as $index => $clean_id) {
            $clean = Clean::find($clean_id);
            if ($clean->clean_status == "รอดำเนินการ") {
                if ($clean->shirtitems_id) {
                    // ตารางshirt
                    $shirt = Shirtitem::find($clean->shirtitems_id);
                    $shirt->shirtitem_status = "กำลังส่งซัก";
                    $shirt->save();
                    // ตารางclean
                    $clean->clean_status = 'กำลังส่งซัก';
                    $clean->save();
                } elseif ($clean->skirtitems_id) {
                    // ตารางshirt
                    $skirt = Skirtitem::find($clean->skirtitems_id);
                    $skirt->skirtitem_status = "กำลังส่งซัก";
                    $skirt->save();
                    // ตารางclean
                    $clean->clean_status = 'กำลังส่งซัก';
                    $clean->save();
                } else {
                    // dd('ทั้งชุด');
                    $dress = Dress::find($clean->dress_id);

                    if ($dress->separable == 1) {
                        // ตารางdress
                        $dress->dress_status = 'กำลังส่งซัก';
                        $dress->save();
                        // ตารางclean
                        $clean->clean_status = 'กำลังส่งซัก';
                        $clean->save();
                    } elseif ($dress->separable == 2) {
                        //ตารางdress
                        $dress->dress_status = 'กำลังส่งซัก';
                        $dress->save();
                        // ตารางshirt
                        $shirt_id = Shirtitem::where('dress_id', $dress->id)->value('id');
                        $shirt_update = Shirtitem::find($shirt_id);
                        $shirt_update->shirtitem_status = 'กำลังส่งซัก';
                        $shirt_update->save();
                        // ตารางskirt
                        $skirt_id = Skirtitem::where('dress_id', $dress->id)->value('id');
                        $skirt_update = Skirtitem::find($skirt_id);
                        $skirt_update->skirtitem_status = 'กำลังส่งซัก';
                        $skirt_update->save();

                        // ตารางclean
                        $clean->clean_status = 'กำลังส่งซัก';
                        $clean->save();
                    }
                }
            } elseif ($clean->clean_status == "กำลังส่งซัก") {
                if ($clean->shirtitems_id) {
                    // ตารางclean
                    $clean->clean_status = 'ซักเสร็จแล้ว';
                    $clean->save();
                    //ตารางshirt
                    $shirt_update = Shirtitem::find($clean->shirtitems_id);
                    $shirt_update->shirtitem_status = 'พร้อมให้เช่า';
                    $shirt_update->save();

                    $data_skirtitem = Skirtitem::where('dress_id',$clean->dress_id)->first() ; 
                    if($data_skirtitem->skirtitem_status == "พร้อมให้เช่า"){
                        $dress_change_status = Dress::find($clean->dress_id) ; 
                        $dress_change_status->dress_status = 'พร้อมให้เช่า' ; 
                        $dress_change_status->save();  
                    }


                } elseif ($clean->skirtitems_id) {
                    // ตารางclean
                    $clean->clean_status = 'ซักเสร็จแล้ว';
                    $clean->save();
                    // ตารางskirtt
                    $skirt_update = Skirtitem::find($clean->skirtitems_id);
                    $skirt_update->skirtitem_status = 'พร้อมให้เช่า';
                    $skirt_update->save();
                    $data_shirtitem = Shirtitem::where('dress_id',$clean->dress_id)->first() ; 
                    if($data_shirtitem->shirtitem_status == "พร้อมให้เช่า"){
                        $dress_change_status = Dress::find($clean->dress_id) ; 
                        $dress_change_status->dress_status = 'พร้อมให้เช่า' ; 
                        $dress_change_status->save();  
                    }

                }
                else {
                    $dress = Dress::find($clean->dress_id);
                    if ($dress->separable == 1) {
                        // ตารางclean
                        $clean->clean_status = 'ซักเสร็จแล้ว';
                        $clean->save();
                        // ตารางdress
                        $dress->dress_status = 'พร้อมให้เช่า';
                        $dress->save();
                    }
                    elseif ($dress->separable == 2) {
                        // ตารางclean
                        $clean->clean_status = 'ซักเสร็จแล้ว';
                        $clean->save();


                        //ตารางshirt
                 
                        $find_shirt_id = Shirtitem::where('dress_id',$dress->id)->value('id') ; 
                   
                        $shirt_update = Shirtitem::find($find_shirt_id);
                        $shirt_update->shirtitem_status = 'พร้อมให้เช่า'; 
                        $shirt_update->save() ;
                        
                        // ตารางskirtt
                        $find_skirt_id = Skirtitem::where('dress_id',$dress->id)->value('id') ; 
                        $skirt_update = Skirtitem::find($find_skirt_id);
                        $skirt_update->skirtitem_status = 'พร้อมให้เช่า';
                        $skirt_update->save();

                        // ตารางdress
                        $dress->dress_status = 'พร้อมให้เช่า';
                        $dress->save();
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'สถานะถูกอัพเดตเรียบร้อยแล้ว');
    }









    public function repair()
    {
        return view('employee.repair');
    }
    public function reservedress()
    {
        return view('employee.reserve-dress');
    }







    //หน้าformเพิ่มตัดชุด
    public function addcutdress()
    {

        $type_dress = Typedress::all();
        return view('Employee.addcutdress', compact('type_dress'));
    }
    //หน้า form เพิ่มเช่าตัด
    public function addcutrent()
    {
        $type_dress = Typedress::all();
        return view('Employee.addcutrent', compact('type_dress'));
    }



    //เพิ่มการตัดชุดลงในตะกร้า บันทึก
    public function savecutdress(Request $request)
    {


        DB::beginTransaction();
        try {

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
            $orderdetail->employee_id = $id_employee;
            $orderdetail->type_dress = $TYPE_DRESS;
            $orderdetail->type_order = 1; //1ตัดชุด 2เช่าชุด 3เช่าเครื่องประดับ 4เช่าตัด
            $orderdetail->title_name = "ตัด" . $TYPE_DRESS;
            $orderdetail->pickup_date = $request->input('pickup_date');
            $orderdetail->amount = $request->input('amount');

            if ($request->input('deposit') > $request->input('price')) {
                DB::rollback();
                return redirect()->back()->with('fail', "ราคาตัดต้องมากกว่าราคามัดจำ");
            } else {
                $orderdetail->price = $request->input('price');
                $orderdetail->deposit = $request->input('deposit');
            }

            $orderdetail->cloth = $request->input('cloth');
            $orderdetail->note = $request->input('note');
            $orderdetail->save();


            // บันทึกข้อมูลในตาราง Measurementorderdetail
            if ($request->input('add_mea_name_')) {
                $mea_name = $request->input('add_mea_name_');
                $mea_number = $request->input('add_mea_number_');
                if ($mea_name) {
                    foreach ($mea_name as $index => $mea) {
                        $data = new Measurementorderdetail();
                        $data->order_detail_id = $orderdetail->id;
                        $data->measurement_name = $mea;
                        $data->measurement_number_old = $mea_number[$index];
                        $data->measurement_number = $mea_number[$index];
                        $data->measurement_unit = 'นิ้ว';
                        $data->save();
                    }
                }
            }


            // //ตาราง fitting 
            // $fit_date = $request->input('fitting_date_');
            // $fit_note = $request->input('fitting_note_');
            // if ($fit_date && $fit_note) {
            //     foreach ($fit_date as $index => $fiting) {
            //         $data = new Fitting();
            //         $data->order_detail_id = $orderdetail->id;
            //         $data->fitting_date = $fiting;
            //         $data->fitting_note = $fit_note[$index];
            //         $data->save();
            //     }
            // }


            DB::commit();
            return redirect()->back()->with('success', 'เพิ่มลงตะกร้าแล้ว !');
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    //เพิ่มการเช่าตัดชุดลงในตะกร้า บันทึก
    public function savecutrent(Request $request)
    {
        DB::beginTransaction();
        try {

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
            } else { // ถ้ามีให้ดึงidมา
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
            $orderdetail->employee_id = $id_employee;
            $orderdetail->type_dress = $TYPE_DRESS;
            $orderdetail->type_order = 4; //1ตัดชุด 2เช่าชุด 3เช่าเครื่องประดับ 4เช่าตัด
            $orderdetail->title_name = "เช่าตัด" . $TYPE_DRESS;
            $orderdetail->pickup_date = $request->input('pickup_date');
            $orderdetail->return_date = $request->input('return_date');
            $orderdetail->late_charge = $request->input('late_charge');
            $orderdetail->damage_insurance = $request->input('damage_insurance');

            $orderdetail->amount = $request->input('amount');

            if ($request->input('deposit') > $request->input('price')) {
                DB::rollback();
                return redirect()->back()->with('fail', "ราคาตัดต้องมากกว่าราคามัดจำ");
            } else {
                $orderdetail->price = $request->input('price');
                $orderdetail->deposit = $request->input('deposit');
            }
            $orderdetail->color = $request->input('color');
            $orderdetail->status_payment = $request->input('status_payment');
            $orderdetail->note = $request->input('note');
            $orderdetail->save();

            // ตารางstatus_payment   1จ่ายมัดจำแล้ว 2.จ่ายเต็ม
            $status_payment = new Paymentstatus();
            $status_payment->order_detail_id = $orderdetail->id;
            $status_payment->payment_status = $request->input('status_payment');
            $status_payment->save();

            // ตาราง date
            $datedate = new Date();
            $datedate->order_detail_id = $orderdetail->id;
            $datedate->pickup_date = $request->input('pickup_date');
            $datedate->return_date = $request->input('return_date');
            $datedate->save();

            // ตาราง financial
            // $financial = new Financial();
            // $financial->order_detail_id = $orderdetail->id;
            // $financial->type_order = 1;
            // if ($request->input('status_payment') == 1) {
            //     $amount_of_money = $request->input('deposit') * $request->input('amount');
            //     $text = "จ่ายมัดจำ";
            // } else {
            //     $amount_of_money = $request->input('price') * $request->input('amount');
            //     $text = "จ่ายเต็ม";
            // }
            // $financial->item_name = $text . "(เช่าตัดชุด)";
            // $financial->financial_income = $amount_of_money;
            // $financial->financial_expenses = 0;
            // $financial->save();

            // ตารางorderdetailstatuses
            // $orderdetailstatus = new Orderdetailstatus();
            // $orderdetailstatus->order_detail_id = $orderdetail->id;
            // $orderdetailstatus->status = "รอตัด";
            // $orderdetailstatus->save();

            // บันทึกข้อมูลในตาราง Measurementorderdetail
            $mea_name = $request->input('add_mea_name_');
            $mea_number = $request->input('add_mea_number_');
            $mea_unit = $request->input('add_mea_unit_');
            if ($mea_name && $mea_number && $mea_unit) {
                foreach ($mea_name as $index => $mea) {
                    $data = new Measurementorderdetail();
                    $data->order_detail_id = $orderdetail->id;
                    $data->measurement_name = $mea;
                    $data->measurement_number = $mea_number[$index];
                    $data->measurement_unit = $mea_unit[$index];
                    $data->save();
                }
            }

            //ตาราง fitting 
            $fit_date = $request->input('add_fitting_date_');
            $fit_note = $request->input('add_fitting_note_');
            if ($fit_date && $fit_note) {
                foreach ($fit_date as $index => $fiting) {
                    $data = new Fitting();
                    $data->order_detail_id = $orderdetail->id;
                    $data->fitting_date = $fiting;
                    $data->fitting_note = $fit_note[$index];
                    $data->save();
                }
            }

            //ตารางimage
            if ($request->hasFile('add_image_')) {
                $imf_loop = $request->file('add_image_');
                foreach ($imf_loop as $index => $img) {
                    $image_save = new Imagerent();
                    $image_save->order_detail_id = $orderdetail->id;
                    $image_save->image = $img->store('rent_images', 'public');
                    $image_save->save();
                }
            }
            DB::commit();
            return redirect()->back()->with('success', 'เพิ่มลงตะกร้าแล้ว !');
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    //ตะกร้าสินค้า
    public function cart()
    {
        $order = Order::where('user_id', Auth::user()->id)
            ->where('order_status', 0)
            ->with('order_one_many_orderdetails')
            ->first();

        return view('Employee.cart', compact('order'));
    }

    //ลบรายการต่างๆ
    public function deletelist($id)
    {
        $delete_orderdetail = Orderdetail::find($id);
        // $delete_orderdetail->delete();

        //กรณีเช่าชุด
        if ($delete_orderdetail->type_order == 2) { //เช่าชุด เปลี่ยนสถานะของชุดให้เป็นเหมือนเดิม เพราะลบออกจากรายการ
            $edit_dress = Dress::find($delete_orderdetail->dress_id);


            if ($edit_dress->separable == 1) {
                $edit_dress->dress_status = "พร้อมให้เช่า";
                $edit_dress->save();
            } elseif ($edit_dress->separable == 2) {
                //เช้คว่าลบเสื้อ



                if ($delete_orderdetail->shirtitems_id) {
                    //ถ้ามีข้อมูล
                    $edit_status_shirt = Shirtitem::find($delete_orderdetail->shirtitems_id);
                    $edit_status_shirt->shirtitem_status = "พร้อมให้เช่า";
                    $edit_status_shirt->save();
                    $check_status_skirt = Skirtitem::where('dress_id', $delete_orderdetail->dress_id)->first();
                    if ($check_status_skirt->skirtitem_status == "พร้อมให้เช่า") {
                        $edit_dress_status = Dress::find($delete_orderdetail->dress_id);
                        $edit_dress_status->dress_status = "พร้อมให้เช่า";
                        $edit_dress_status->save();
                    } else {
                        $edit_dress_status = Dress::find($delete_orderdetail->dress_id);
                        $edit_dress_status->dress_status = "ไม่พร้อมให้เช่า";
                        $edit_dress_status->save();
                    }
                }
                //เช็คว่าลบกระโปรง
                elseif ($delete_orderdetail->skirtitems_id) {
                    //ถ้ามีข้อมูล
                    $edit_status_skirt = Skirtitem::find($delete_orderdetail->skirtitems_id);
                    $edit_status_skirt->skirtitem_status = "พร้อมให้เช่า";
                    $edit_status_skirt->save();
                    $check_status_shirt = Shirtitem::where('dress_id', $delete_orderdetail->dress_id)->first();
                    if ($check_status_shirt->shirtitem_status == "พร้อมให้เช่า") {
                        $edit_dress_status = Dress::find($delete_orderdetail->dress_id);
                        $edit_dress_status->dress_status = "พร้อมให้เช่า";
                        $edit_dress_status->save();
                    } else {
                        $edit_dress_status = Dress::find($delete_orderdetail->dress_id);
                        $edit_dress_status->dress_status = "ไม่พร้อมให้เช่า";
                        $edit_dress_status->save();
                    }
                } else {
                    // dd('ลบทั้งชุด') ; 
                    $edit_dress = Dress::find($delete_orderdetail->dress_id);
                    $edit_dress->dress_status = "พร้อมให้เช่า";
                    $edit_dress->save();
                    //เปลี่ยนสถานะของตารางshirt
                    $id_shirt = Shirtitem::where('dress_id', $edit_dress->id)->first();
                    $edit_status_shirt = Shirtitem::find($id_shirt->id);
                    $edit_status_shirt->shirtitem_status = "พร้อมให้เช่า";
                    $edit_status_shirt->save();
                    //เปลีย่นสถานะของตารางskirt
                    $id_skirt = Skirtitem::where('dress_id', $edit_dress->id)->first();
                    $edit_status_skirt = Skirtitem::find($id_skirt->id);
                    $edit_status_skirt->skirtitem_status = "พร้อมให้เช่า";
                    $edit_status_skirt->save();
                }
            }
        }
        // กรณีเช่าเครื่องประดับ
        if ($delete_orderdetail->type_order == 3) { //เช่าเครื่องประดับ เปลี่ยนสถานะของเครื่องประดับให้เป็นเหมือนเดิม เพราะลบออกจากรายการ
            $edit_jewelry = Jewelry::find($delete_orderdetail->jewelry_id);
            $edit_jewelry->jewelry_status = "พร้อมให้เช่า";
            $edit_jewelry->save();
        }


        //ลบลูกๆมันด้วย
        Imagerent::where('order_detail_id', $id)->delete();
        Paymentstatus::where('order_detail_id', $id)->delete();
        Fitting::where('order_detail_id', $id)->delete();
        Financial::where('order_detail_id', $id)->delete();
        Date::where('order_detail_id', $id)->delete();
        Measurementorderdetail::where('order_detail_id', $id)->delete();
        Orderdetailstatus::where('order_detail_id', $id)->delete();

        //อัปเดตตาราง order ด้วย เพราะorderdetail มันลบไปแล้วไง
        $ORDER_ID = $delete_orderdetail->order_id;
        $update_order = Order::find($ORDER_ID);

        $update_order->total_quantity = $update_order->total_quantity - 1; //รายการทั้งหมดจะลดลงทีละ1 
        $update_order->total_price = $update_order->total_price - ($delete_orderdetail->price * $delete_orderdetail->amount);
        $update_order->total_deposit = $update_order->total_deposit - ($delete_orderdetail->deposit * $delete_orderdetail->amount);
        $update_order->save();

        if ($update_order->total_quantity == 0) {
            $update_order->delete();
        }

        //สุดท้านท้ายสุดต้องลบตัวมันเองด้วยคือลบorder_detail_id 
        //ลบตัวมันเองคือorder_detail_id
        $delete_orderdetail->delete();
        return redirect()->back();
    }



    //จุดแยก type_order
    public function manageitem(Request $request, $id)
    {
        // dd($id) ; 
        $type_order = $request->input('type_order');
        // dd($type_order) ; 
        if ($type_order == 1) {
            return $this->manageitemcutdress($request, $id);
        }
        if ($type_order == 2) {
            return $this->manageitemrentdress($request, $id);
        }
        if ($type_order == 3) {
            return $this->manageitemrentjewelry($request, $id);
        }
        if ($type_order == 4) {
            return $this->manageitemrentcut($request, $id);
        }
    }

    //ตัดชุด
    private function manageitemcutdress($id)
    {
        $id = $id->id;
        $type_dress = Typedress::all();
        $orderdetail = Orderdetail::find($id);
        $measurementorderdetail  = Measurementorderdetail::where('order_detail_id', $id)->get();
        $fitting = Fitting::where('order_detail_id', $id)->get();
        return view('employeecutdress.manageitemcutdress', compact('orderdetail', 'type_dress', 'measurementorderdetail', 'fitting'));
    }

    //เช่าชุด
    private function manageitemrentdress($id)
    {
        // dd($id->id) ; 
        $id = $id->id;
        $type_dress = Typedress::all();
        $orderdetail = Orderdetail::find($id);
        $dress = Dress::where('id', $orderdetail->dress_id)->select('dress_code_new', 'dress_code')->first();
        $imagedress = Dressimage::where('dress_id', $orderdetail->dress_id)->get();
        $measurementdress = Dressmeasurement::where('dress_id', $orderdetail->dress_id)->get();
        $maxnow = Dressmeasurementnow::where('dress_id', $orderdetail->dress_id)->max('count');
        $Dressmeasurementnow = Dressmeasurementnow::where('dress_id', $orderdetail->dress_id)->where('count', $maxnow)->get();
        $measurementorderdetail = Measurementorderdetail::where('order_detail_id', $id)->get();
        // $measurementorderdetails = Measurementorderdetail::where('order_detail_id', $id)->get();
        $fitting = Fitting::where('order_detail_id', $id)->get();
        $imagerent = Imagerent::where('order_detail_id', $id)->get();
        return view('employeerentdress.manageitemrentdress', compact('orderdetail', 'type_dress', 'fitting', 'imagerent', 'dress', 'imagedress', 'measurementdress', 'Dressmeasurementnow', 'measurementorderdetail'));
    }

    //เช่าเครื่องประดับ
    private function manageitemrentjewelry($id)
    {
        $id = $id->id;
        $type_dress = Typedress::all();
        $orderdetail = Orderdetail::find($id);
        // $dress = Dress::where('id', $orderdetail->dress_id)->select('dress_code_new', 'dress_code')->first();
        $jewelry = Jewelry::where('id', $orderdetail->jewelry_id)->select('jewelry_code_new', 'jewelry_code')->first();
        $imagejewelry = Jewelryimage::where('jewelry_id', $orderdetail->jewelry_id)->get();
        $fitting = Fitting::where('order_detail_id', $id)->get();
        $imagerent = Imagerent::where('order_detail_id', $id)->get();

        return view('employeerentjewelry.manageitemrentjewelry', compact('orderdetail', 'type_dress', 'fitting', 'imagerent', 'jewelry', 'imagejewelry'));
    }

    //เช่าตัด
    private function manageitemrentcut($id)
    {
        $id = $id->id;
        $type_dress = Typedress::all();
        $orderdetail = Orderdetail::find($id);
        $measurementorderdetails = Measurementorderdetail::where('order_detail_id', $id)->get();
        $fitting = Fitting::where('order_detail_id', $id)->get();
        $imagerent = Imagerent::where('order_detail_id', $id)->get();
        return view('employeerentcut.manageitemrentcut', compact('orderdetail', 'type_dress', 'measurementorderdetails', 'fitting', 'imagerent'));
    }
    //ลบdeletemeasurementitem ใน item
    public function deletemeasurementitem($id)
    {
        // dd($id) ; 
        $delete_measuremen = Measurementorderdetail::find($id);
        $delete_measuremen->delete();
        return redirect()->back();
    }

    //ลบdeletefittingitem ใน item
    public function deletefittingitem($id)
    {
        $delete_fitting = Fitting::find($id);
        $delete_fitting->delete();
        return redirect()->back();
    }




    public function confirmorder($id)
    {
        $order_id = $id;
        $orderdetail = Orderdetail::where('order_id', $id)->get();
        return view('employee.confirmorder', compact('orderdetail', 'order_id'));
    }



    // ยืนยันการเพิ่มออเดอร์ 
    public function confirmordersave(Request $request, $id)
    {
        $customer_fname = $request->input('customer_fname');
        $customer_lname = $request->input('customer_lname');
        $customer_phone = $request->input('customer_phone');
        $payment_status = $request->input('payment_status');
        $order_id = $id;

        $checkcustomer = Customer::where('customer_fname', $customer_fname)
            ->where('customer_lname', $customer_lname)
            ->first();

        if ($checkcustomer) {
            $customer_id = $checkcustomer->id;
            $customer = Customer::find($customer_id);
            $customer->customer_phone = $customer_phone;
            $customer->save();
            $customer_id = $customer->id;
        } else {
            $create_customer = new Customer();
            $create_customer->customer_fname = $customer_fname;
            $create_customer->customer_lname = $customer_lname;
            $create_customer->customer_phone = $customer_phone;
            $create_customer->save();
            $customer_id = $create_customer->id;
        }
        $order_detail_id = Orderdetail::where('order_id', $order_id)->get();


        foreach ($order_detail_id as $order_detail_id) {

            // $dataorderdetail = Orderdetail::find($order_detail_id);
            $orderdetail = Orderdetail::find($order_detail_id->id);


            if ($orderdetail->type_order == 1) {
                //ตารางorderdetailstatus 
                $create_status = new Orderdetailstatus();
                $create_status->order_detail_id = $orderdetail->id;
                $create_status->status = 'เริ่มดำเนินการตัด';
                $create_status->save();
                //ตารางorderdetail
                $orderdetail->status_detail = "เริ่มดำเนินการตัด";
                $orderdetail->status_payment = $payment_status;
                $orderdetail->save();

                //ตารางdate
                $create_date = new Date();
                $create_date->order_detail_id = $orderdetail->id;
                $create_date->pickup_date = $orderdetail->pickup_date;
                $create_date->save();
                //ตารางpayment_status
                $create_payment = new Paymentstatus();
                $create_payment->order_detail_id = $orderdetail->id;
                $create_payment->payment_status = $payment_status;
                $create_payment->save();
                // ตารางfinancial
                $create_financial = new Financial();
                $create_financial->order_detail_id = $orderdetail->id;
                if ($payment_status == 1) {
                    $text = "ชำระมัดจำ";
                    $money = $orderdetail->deposit *  $orderdetail->amount;
                } elseif ($payment_status == 2) {
                    $text = "ชำระเต็มจำนวน";
                    $money = $orderdetail->price *  $orderdetail->amount;
                }
                $create_financial->item_name = $text;
                $create_financial->type_order = 1;
                $create_financial->financial_income = $money;
                $create_financial->financial_expenses = 0;
                $create_financial->save();
            } elseif ($orderdetail->type_order == 2) {
                //ตารางorderdetailstatus 
                $create_status = new Orderdetailstatus();
                $create_status->order_detail_id = $orderdetail->id;
                $create_status->status = 'ถูกจอง';
                $create_status->save();
                //ตารางorderdetail
                $orderdetail->status_detail = "ถูกจอง";
                $orderdetail->status_payment = $payment_status;
                $orderdetail->save();
                //ตารางdate
                $create_date = new Date();
                $create_date->order_detail_id = $orderdetail->id;
                $create_date->pickup_date = $orderdetail->pickup_date;
                $create_date->return_date = $orderdetail->return_date;

                $create_date->save();
                //ตารางpayment_status
                $create_payment = new Paymentstatus();
                $create_payment->order_detail_id = $orderdetail->id;
                $create_payment->payment_status = $payment_status;
                $create_payment->save();
                // ตารางfinancial
                $create_financial = new Financial();
                $create_financial->order_detail_id = $orderdetail->id;
                if ($payment_status == 1) {
                    $text = "ชำระมัดจำ";
                    $money = $orderdetail->deposit *  $orderdetail->amount;
                } elseif ($payment_status == 2) {
                    $text = "ชำระเต็มจำนวน";
                    $money = $orderdetail->price *  $orderdetail->amount;
                }
                $create_financial->item_name = $text;
                $create_financial->type_order = 2;
                $create_financial->financial_income = $money;
                $create_financial->financial_expenses = 0;
                $create_financial->save();
                // if ($orderdetail->late_charge > 0) {
                //     $create_financial = new Financial();
                //     $create_financial->order_detail_id = $orderdetail->id;
                //     $create_financial->item_name = 'ค่าบริการขยายเวลาเช่าชุด';
                //     $create_financial->type_order = 2;
                //     $create_financial->financial_income = $orderdetail->late_charge;
                //     $create_financial->financial_expenses = 0;
                //     $create_financial->save();
                // }
                //ประกันค่าเสียหาย
                // if ($orderdetail->damage_insurance > 0) {
                //     $create_financial = new Financial();
                //     $create_financial->order_detail_id = $orderdetail->id;
                //     $create_financial->item_name = 'ประกันค่าเสียหาย';
                //     $create_financial->type_order = 2;
                //     $create_financial->financial_income = $orderdetail->damage_insurance;
                //     $create_financial->financial_expenses = 0;
                //     $create_financial->save();
                // }
                //ตารางdress/shirt/skirt
                if ($orderdetail->shirtitems_id) {
                    //ถ้ามันมีแสดงว่ามันเช่าเสื้อ
                    $update_status_shirt = Shirtitem::find($orderdetail->shirtitems_id);
                    $update_status_shirt->shirtitem_status = "ถูกจองแล้ว";
                    $update_status_shirt->save();
                }
                if ($orderdetail->skirtitems_id) {
                    //ถ้ามันมีแสดงว่ามันเช่ากระโปรง/ผ้าสิ่น
                    $update_status_skirt = Skirtitem::find($orderdetail->skirtitems_id);
                    $update_status_skirt->skirtitem_status = "ถูกจองแล้ว";
                    $update_status_skirt->save();
                }
                if (($orderdetail->shirtitems_id == null && $orderdetail->skirtitems_id == null) && $orderdetail->dress_id != null) {
                    $update_status_dress = Dress::find($orderdetail->dress_id);
                    $update_status_dress->dress_status = "ถูกจองแล้ว";
                    $update_status_dress->save();

                    $check_id_for_shirt = Shirtitem::where('dress_id', $orderdetail->dress_id)->first();
                    if ($check_id_for_shirt) {
                        $update_status_shirt = Shirtitem::find($check_id_for_shirt->id);
                        $update_status_shirt->shirtitem_status = 'ถูกจองแล้ว';
                        $update_status_shirt->save();
                    }
                    $check_id_for_skirt = Skirtitem::where('dress_id', $orderdetail->dress_id)->first();
                    if ($check_id_for_skirt) {
                        $update_status_skirt = Skirtitem::find($check_id_for_skirt->id);
                        $update_status_skirt->skirtitem_status = 'ถูกจองแล้ว';
                        $update_status_skirt->save();
                    }
                }
            } elseif ($orderdetail->type_order == 3) {
            } elseif ($orderdetail->type_order == 4) {
            }
        }
        $update_order_status = Order::find($order_id);
        $update_order_status->customer_id = $customer_id;
        $update_order_status->order_status = 1;
        $update_order_status->save();

        return redirect()->route('employee.ordertotaldetail', ['id' => $order_id]);
    }
}
