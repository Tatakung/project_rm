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
use App\Models\Orderdetail;
use App\Models\Orderdetailstatus;
use App\Models\Paymentstatus;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //ออเดอร์ทั้งหมด
    public function ordertotal()
    {
        $customers = Customer::with('orders')->get();
        return view('employee.ordertotal', compact('customers'));
    }

    //ออเดอร์ดีเทล
    public function ordertotaldetail($id)
    {
        $order_id = $id;
        $orderdetail = Orderdetail::where('order_id', $id)->get();
        return view('employee.ordertotaldetail', compact('orderdetail', 'order_id'));
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
        $employee = User::find($orderdetail->employee_id);
        $fitting = Fitting::where('order_detail_id', $id)->get();
        $cost = Cost::where('order_detail_id', $id)->get();
        $date = Date::where('order_detail_id', $id)->get();
        $decoration = Decoration::where('order_detail_id', $id)->get();
        $imagerent = Imagerent::where('order_detail_id', $id)->get();
        $mea_dress = Dressmeasurement::where('dress_id', $orderdetail->dress_id)->get();
        $mea_orderdetail = Measurementorderdetail::where('order_detail_id', $id)->get();
        $orderdetailstatus = Orderdetailstatus::where('order_detail_id', $id)->get();
        $valuestatus = $orderdetail->status_detail;
        $valuestatus = Orderdetailstatus::where('order_detail_id', $id)
            ->latest('created_at')
            ->value('status');
        return view('employeerentdress.managedetailrentdress', compact('orderdetail', 'dress', 'employee', 'fitting', 'cost', 'date', 'decoration', 'imagerent', 'mea_dress', 'mea_orderdetail', 'orderdetailstatus', 'valuestatus'));
    }

    //จัดการเช่าเครื่องประดับ
    private function managedetailrentjewelry($id)
    {
        $orderdetail = Orderdetail::find($id);
        $customer = User::find($orderdetail->employee_id);
        $cost = Cost::where('order_detail_id', $id)->get();
        $date = Date::where('order_detail_id', $id)->get();
        $imagerent = Imagerent::where('order_detail_id', $id)->get();
        $orderdetailstatus = Orderdetailstatus::where('order_detail_id', $id)->get();
        $valuestatus = $orderdetail->status_detail;
        $valuestatus = Orderdetailstatus::where('order_detail_id', $id)
            ->latest('created_at')
            ->value('status');
        return view('employeerentjewelry.managedetailrentjewelry', compact('orderdetail', 'customer', 'cost', 'date', 'imagerent', 'orderdetailstatus', 'valuestatus'));
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
        $valuestatus = $orderdetail->status_detail;
        $valuestatus = Orderdetailstatus::where('order_detail_id', $id)
            ->latest('created_at')
            ->value('status');
        return view('employeerentcut.managedetailrentcut', compact('orderdetail', 'dress', 'employee', 'fitting', 'cost', 'date', 'decoration', 'imagerent', 'mea_dress', 'mea_orderdetail', 'orderdetailstatus', 'valuestatus'));
    }
    //จัดการตัดชุด
    private function managedetailcutdress($id)
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
        $valuestatus = $orderdetail->status_detail;
        $valuestatus = Orderdetailstatus::where('order_detail_id', $id)
            ->latest('created_at')
            ->value('status');
        return view('employeecutdress.managedetailcutdress', compact('orderdetail', 'dress', 'employee', 'fitting', 'cost', 'date', 'decoration', 'imagerent', 'mea_dress', 'mea_orderdetail', 'orderdetailstatus', 'valuestatus'));
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
    //เพิ่มข้อมูลการเพิ่มค่าใช้จ่าย
    public function actionaddcost(Request $request, $id)
    {
        $add_cost = new Cost();
        $add_cost->order_detail_id = $id;
        $add_cost->cost_type = $request->input('add_cost_type');
        $add_cost->cost_value = $request->input('add_cost_value');
        $add_cost->save();
        $id_for_cost = $add_cost->id;

        $orderdetail = Orderdetail::find($id);
        $Type_Order = $orderdetail->type_order;
        //ตารางfinancial
        $add_financial = new Financial();
        $add_financial->order_detail_id = $id;
        $add_financial->cost_id = $id_for_cost;
        $add_financial->item_name = $request->input('add_cost_type');
        $add_financial->type_order = $Type_Order;
        $add_financial->financial_income = 0;
        $add_financial->financial_expenses = $request->input('add_cost_value');
        $add_financial->save();
        return redirect()->back()->with('success', "เพิ่มค่าใช้จ่ายสำเร็จ!");
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
    public function actionaddimagerent(Request $request, $id)
    {
        $add_image_rent = new Imagerent();
        $add_image_rent->order_detail_id = $id;
        $add_image_rent->image = $request->file('add_image')->store('rent_images', 'public');
        $add_image_rent->save();
        return redirect()->back()->with('success', "เพิ่มรูปภาพสำเร็จ !");
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
        $status = $orderdetail->status_detail;
        if ($status == 'จองชุด') {
            //ตารางorderdetail
            $orderdetail->status_detail = "กำลังเช่า";
            $orderdetail->save();
            //ตารางorderdetailstatus
            $create_status = new Orderdetailstatus();
            $create_status->order_detail_id = $id;
            $create_status->status = "กำลังเช่า";
            $create_status->save();

            //ตารางdress
            $update_status_dress = Dress::find($orderdetail->dress_id);
            $update_status_dress->dress_status = 'กำลังถูกเช่า';
            $update_status_dress->dress_rental = $update_status_dress->dress_rental + 1;
            $update_status_dress->save();

            if ($orderdetail->status_payment == 1) {
                //ตารางpaymentstatus
                $create_paymentstatus = new Paymentstatus();
                $create_paymentstatus->order_detail_id = $id;
                $create_paymentstatus->payment_status = 2;
                $create_paymentstatus->save();
                //ตารางorderdetail
                $orderdetail->status_payment = 2; //1จ่ายมัดจำ 2จ่ายเต็มจำนวน
                $orderdetail->save();
                //ตารางfinancial  ถ้ามันเป็น 1 แปลว่ามันจ่ายแค่มัดจำ   ถ้าคืนชุดแล้วอะ มันจะต้องเอาเงินเข้าไปในบัญชีส่วนต่าง
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
            //ตารางdress
            $update_status_dress = Dress::find($orderdetail->dress_id);
            $update_status_dress->dress_status = 'ส่งซักทำความสะอาด';
            $update_status_dress->save();
        }
        return redirect()->back()->with('success', 'อัพเดตสถานะสำเร็จ !');
    }

    //อัปเดตสถานะเช่าเครื่องประดับ
    public function actionupdatestatusrentjewelry(Request $request, $id)
    {
        $orderdetail = Orderdetail::find($id);
        $status = $orderdetail->status_detail;
        if ($status == 'จองเครื่องประดับ') {
            //ตารางorderdetail
            $orderdetail->status_detail = "กำลังเช่า";
            $orderdetail->save();
            //ตารางorderdetailstatus
            $create_status = new Orderdetailstatus();
            $create_status->order_detail_id = $id;
            $create_status->status = "กำลังเช่า";
            $create_status->save();

            //ตารางjewelry
            $update_status_jewelry = Jewelry::find($orderdetail->jewelry_id);
            $update_status_jewelry->jewelry_status = 'กำลังถูกเช่า';
            $update_status_jewelry->jewelry_rental = $update_status_jewelry->jewelry_rental + 1;
            $update_status_jewelry->save();

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
            $orderdetail->status_detail = "คืนเครื่องประดับแล้ว";
            $orderdetail->total_damage_insurance = $request->input('total_damage_insurance'); //ปรับจริง
            $orderdetail->cause_for_insurance = $request->input('cause_for_insurance'); //เหตุผลในการปรับ ; 
            $orderdetail->save();
            //ตารางorderdetailstatus
            $create_status = new Orderdetailstatus();
            $create_status->order_detail_id = $id;
            $create_status->status = "คืนเครื่องประดับแล้ว";
            $create_status->save();
            //ตารางjewelry
            $update_status_jewelry = Jewelry::find($orderdetail->jewelry_id);
            $update_status_jewelry->jewelry_status = 'ส่งทำความสะอาด';
            $update_status_jewelry->save();
        }
        return redirect()->back()->with('success', 'อัพเดตสถานะสำเร็จ !');
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
        } 
        elseif($status == 'กำลังตัด'){
               //ตารางorderdetail
               $orderdetail->status_detail = "ตัดเสร็จแล้ว";
               $orderdetail->save();
               //ตารางorderdetailstatus
               $create_status = new Orderdetailstatus();
               $create_status->order_detail_id = $id;
               $create_status->status = "ตัดเสร็จแล้ว";
               $create_status->save();    
        } 
        elseif ($status == 'ตัดเสร็จแล้ว') {
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
        } 
        elseif ($status == "กำลังเช่า") {
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
        if ($status == 'รอตัด') {
            //ตารางorderdetail
            $orderdetail->status_detail = "กำลังตัด";
            $orderdetail->save();
            //ตารางorderdetailstatus
            $create_status = new Orderdetailstatus();
            $create_status->order_detail_id = $id;
            $create_status->status = "กำลังตัด";
            $create_status->save();
        } 
        elseif($status == 'กำลังตัด'){
               //ตารางorderdetail
               $orderdetail->status_detail = "ตัดเสร็จแล้ว";
               $orderdetail->save();
               //ตารางorderdetailstatus
               $create_status = new Orderdetailstatus();
               $create_status->order_detail_id = $id;
               $create_status->status = "ตัดเสร็จแล้ว";
               $create_status->save();    
        } 
        elseif ($status == 'ตัดเสร็จแล้ว') {
            //ตารางorderdetail
            $orderdetail->status_detail = "มารับชุดแล้ว";
            $orderdetail->save();
            //ตารางorderdetailstatus
            $create_status = new Orderdetailstatus();
            $create_status->order_detail_id = $id;
            $create_status->status = "มารับชุดแล้ว";
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
        } 
        return redirect()->back()->with('success', 'อัพเดตสถานะสำเร็จ !');
    }
}
