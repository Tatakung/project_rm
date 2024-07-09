<?php

namespace App\Http\Controllers;

use App\Models\Date;
use App\Models\Dress;
use App\Models\Dressimage;
use App\Models\Financial;
use App\Models\Fitting;
use App\Models\Imagerent;
use App\Models\Jewelry;
use App\Models\Measurementorderdetail;
use App\Models\Order;
use App\Models\Orderdetail;
use App\Models\Orderdetailstatus;
use App\Models\Paymentstatus;
use App\Models\Typedress;
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
        return view('Employee.addorder');
    }

    public function addcutdress()
    {
        $type_dress = Typedress::all();
        return view('Employee.addcutdress', compact('type_dress'));
    }

    //เพิ่มการตัดชุดลงในตะกร้า
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
            $datedate->save();

            // ตาราง financial
            $financial = new Financial();
            $financial->order_detail_id = $orderdetail->id;
            $financial->type_order = 1;
            if ($request->input('status_payment') == 1) {
                $amount_of_money = $request->input('deposit') * $request->input('amount');
                $text = "จ่ายมัดจำ";
            } else {
                $amount_of_money = $request->input('price') * $request->input('amount');
                $text = "จ่ายเต็ม";
            }
            $financial->item_name = $text . "(ตัดชุด)";
            $financial->financial_income = $amount_of_money;
            $financial->financial_expenses = 0;
            $financial->save();

            // ตารางorderdetailstatuses
            $orderdetailstatus = new Orderdetailstatus();
            $orderdetailstatus->order_detail_id = $orderdetail->id;
            $orderdetailstatus->status = "รอตัด";
            $orderdetailstatus->save();

            // บันทึกข้อมูลในตาราง Measurementorderdetail
            $mea_name = $request->input('mea_name_');
            $mea_number = $request->input('mea_number_');
            $mea_unit = $request->input('mea_unit_');
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
            $fit_date = $request->input('fitting_date_');
            $fit_note = $request->input('fitting_note_');
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
            if ($request->hasFile('image_')) {
                $imf_loop = $request->file('image_');
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
            ->first();

        return view('Employee.cart', compact('order'));
    }

    //ลบรายการต่างๆ
    public function deletelist($id)
    {
        $delete_orderdetail = Orderdetail::find($id);
        $delete_orderdetail->delete();

        if($delete_orderdetail->type_order == 2){ //เช่าชุด เปลี่ยนสถานะของชุดให้เป็นเหมือนเดิม เพราะลบออกจากรายการ
            // $dress = Dress::where('id',$delete_orderdetail->dress_id); 
            $edit_dress = Dress::find($delete_orderdetail->dress_id) ; 
            $edit_dress->dress_status = "พร้อมให้เช่า" ; 
            $edit_dress->save() ; 
        }
        if($delete_orderdetail->type_order == 3){ //เช่าเครื่องประดับ เปลี่ยนสถานะของเครื่องประดับให้เป็นเหมือนเดิม เพราะลบออกจากรายการ
            $edit_jewelry = Jewelry::find($delete_orderdetail->jewelry_id) ; 
            $edit_jewelry->jewelry_status = "พร้อมให้เช่า" ; 
            $edit_jewelry->save() ; 
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
        return redirect()->back();
    }




    //จัดการตาม item
    public function manageitem($id)
    {
        $type_dress = Typedress::all();
        $orderdetail = Orderdetail::find($id);
        $dress = Dress::where('id',$orderdetail->dress_id)->select('dress_code_new','dress_code')->first() ; 
        $imagedress = Dressimage::where('dress_id',$orderdetail->dress_id)->first() ; 

        $measurementorderdetail = Measurementorderdetail::where('order_detail_id', $id)->get();
        $fitting = Fitting::where('order_detail_id', $id)->get();
        $imagerent = Imagerent::where('order_detail_id', $id)->get();
        return view('Employee.manageitem', compact('orderdetail', 'type_dress', 'measurementorderdetail', 'fitting', 'imagerent','dress','imagedress'));
    }

    //ลบdeletemeasurement ใน item
    public function deletemeasurement($id)
    {
        $delete_mea = Measurementorderdetail::find($id);
        $delete_mea->delete();
        return redirect()->back();
    }

    //ลบdeletefittingitem ใน item
    public function deletefittingitem($id)
    {
        $delete_fitting = Fitting::find($id);
        $delete_fitting->delete();
        return redirect()->back();
    }

    
}
