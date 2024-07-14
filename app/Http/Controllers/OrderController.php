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
use App\Models\Measurementorderdetail;
use App\Models\Orderdetail;
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
        $customer = User::find($orderdetail->employee_id);
        $fitting = Fitting::where('order_detail_id', $id)->get();
        $cost = Cost::where('order_detail_id', $id)->get();
        $date = Date::where('order_detail_id', $id)->get();
        $decoration = Decoration::where('order_detail_id', $id)->get();
        $imagerent = Imagerent::where('order_detail_id', $id)->get();
        $mea_dress = Dressmeasurement::where('dress_id', $orderdetail->dress_id)->get();
        $mea_orderdetail = Measurementorderdetail::where('order_detail_id', $id)->get();
        return view('employeerentdress.managedetailrentdress', compact('orderdetail', 'dress', 'customer', 'fitting', 'cost', 'date', 'decoration', 'imagerent', 'mea_dress', 'mea_orderdetail'));
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
        $add_image_rent->save() ; 
        return redirect()->back()->with('success',"เพิ่มรูปภาพสำเร็จ !") ; 
    }
}
