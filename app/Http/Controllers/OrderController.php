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
use App\Models\Shirtitem;
use App\Models\Skirtitem;
use App\Models\Clean;
use App\Models\Dressimage;
use Illuminate\Http\Request;
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
















    public function room(Request $request)
    {
        $search = $request->input('search');
        if ($search) {
            $rooms = Dress::where('room_name', 'LIKE', "%{$search}%")
                ->orWhere('room_description', 'LIKE', "%{$search}%")
                ->get();
        } else {
            $rooms = $this->getAllRooms();
        }
        return view('owner.room', compact('rooms', 'search'));
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
        // dd($orderdetail->dress_id) ; 
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

        $orderdetailstatus = Orderdetailstatus::where('order_detail_id', $id)->get();
        $valuestatus = $orderdetail->status_detail;
        $valuestatus = Orderdetailstatus::where('order_detail_id', $id)
            ->latest('created_at')
            ->value('status');
        return view('employeerentdress.managedetailrentdress', compact('orderdetail', 'dress', 'employee', 'fitting', 'cost', 'date', 'decoration', 'imagerent', 'mea_dress', 'mea_orderdetail', 'orderdetailstatus', 'valuestatus', 'customer', 'mea_orderdetail_for_adjust', 'dressimage'));
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
        $date = Date::where('order_detail_id', $id)->get();
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
        return view('employeecutdress.managedetailcutdress', compact('orderdetail', 'dress', 'employee', 'fitting', 'cost', 'date', 'decoration', 'imagerent', 'mea_dress', 'mea_orderdetail', 'orderdetailstatus', 'valuestatus', 'customer', 'mea_orderdetailforedit'));
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
        if ($status == 'ถูกจอง') {
            //ตารางorderdetail
            $orderdetail->status_detail = "กำลังเช่า";
            $orderdetail->save();
            //ตารางorderdetailstatus
            $create_status = new Orderdetailstatus();
            $create_status->order_detail_id = $id;
            $create_status->status = "กำลังเช่า";
            $create_status->save();

            if ($orderdetail->shirtitems_id) {
                $update_shirt = Shirtitem::find($orderdetail->shirtitems_id);
                $update_shirt->shirtitem_status = "กำลังถูกเช่า";
                $update_shirt->save();
            } elseif ($orderdetail->skirtitems_id) {
                $update_skirt = Skirtitem::find($orderdetail->skirtitems_id);
                $update_skirt->skirtitem_status = "กำลังถูกเช่า";
                $update_skirt->save();
            } elseif (($orderdetail->shirtitems_id == null && $orderdetail->skirtitems_id == null) && $orderdetail->dress_id) {
                $dress = Dress::find($orderdetail->dress_id);
                if ($dress->separable == 1) {
                    $dress->dress_status = 'กำลังถูกเช่า';
                    $dress->save();
                } elseif ($dress->separable == 2) {
                    $datashirt = Shirtitem::where('dress_id', $dress->id)->first();
                    $shirt = Shirtitem::find($datashirt->id);
                    $shirt->shirtitem_status = 'กำลังถูกเช่า';
                    $shirt->save();

                    $dataskirt = Skirtitem::where('dress_id', $dress->id)->first();
                    $skirt = Skirtitem::find($dataskirt->id);
                    $skirt->skirtitem_status = 'กำลังถูกเช่า';
                    $skirt->save();

                    $update_dress_status = Dress::find($dress->id);
                    $update_dress_status->dress_status = 'กำลังถูกเช่า';
                    $update_dress_status->save();
                }
            }

            //ตารางfinancila
            $create_total_damage_insurance = new Financial();
            $create_total_damage_insurance->order_detail_id = $id;
            $create_total_damage_insurance->item_name = "ประกันค่าเสียหาย";
            $create_total_damage_insurance->type_order = $orderdetail->type_order;
            $create_total_damage_insurance->financial_income = $orderdetail->damage_insurance;
            $create_total_damage_insurance->financial_expenses =  0;
            $create_total_damage_insurance->save();



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
                $create_total_damage_insurance->item_name = "คินเงินประกันลูกค้า";
                $create_total_damage_insurance->type_order = $orderdetail->type_order;
                $create_total_damage_insurance->financial_income = 0;
                $create_total_damage_insurance->financial_expenses = $orderdetail->damage_insurance -  $request->input('total_damage_insurance');
                $create_total_damage_insurance->save();
            } elseif ($request->input('total_damage_insurance') == 0) {
                $create_total_damage_insurance = new Financial();
                $create_total_damage_insurance->order_detail_id = $id;
                $create_total_damage_insurance->item_name = "คินเงินประกันลูกค้า";
                $create_total_damage_insurance->type_order = $orderdetail->type_order;
                $create_total_damage_insurance->financial_income = 0;
                $create_total_damage_insurance->financial_expenses = $orderdetail->damage_insurance;
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


            if ($request->input('return_status') == "ส่งซัก") {
                if ($orderdetail->shirtitems_id) {
                    $update_shirt = Shirtitem::find($orderdetail->shirtitems_id);
                    $update_shirt->shirtitem_status = "ส่งซัก";
                    $update_shirt->shirtitem_rental = $update_shirt->shirtitem_rental + 1;
                    $update_shirt->save();
                } elseif ($orderdetail->skirtitems_id) {
                    $update_skirt = Skirtitem::find($orderdetail->skirtitems_id);
                    $update_skirt->skirtitem_status = "ส่งซัก";
                    $update_skirt->skirtitem_rental = $update_skirt->skirtitem_rental + 1;
                    $update_skirt->save();
                } elseif (($orderdetail->shirtitems_id == null && $orderdetail->skirtitems_id == null) && $orderdetail->dress_id) {
                    $dress = Dress::find($orderdetail->dress_id);
                    if ($dress->separable == 1) {
                        $dress->dress_status = 'ส่งซัก';
                        $dress->dress_rental = $dress->dress_rental + 1;
                        $dress->save();
                    } elseif ($dress->separable == 2) {
                        $datashirt = Shirtitem::where('dress_id', $dress->id)->first();
                        $shirt = Shirtitem::find($datashirt->id);
                        $shirt->shirtitem_status = 'ส่งซัก';
                        $shirt->shirtitem_rental = $shirt->shirtitem_rental + 1;
                        $shirt->save();

                        $dataskirt = Skirtitem::where('dress_id', $dress->id)->first();
                        $skirt = Skirtitem::find($dataskirt->id);
                        $skirt->skirtitem_status = 'ส่งซัก';
                        $skirt->skirtitem_rental = $skirt->skirtitem_rental + 1;
                        $skirt->save();

                        $update_dress_status = Dress::find($dress->id);
                        $update_dress_status->dress_status = 'ส่งซัก';
                        $update_dress_status->dress_rental = $update_dress_status->dress_rental + 1;
                        $update_dress_status->save();
                    }
                }
                //ตารางClean
                $create_clean = new Clean();
                $create_clean->dress_id = $orderdetail->dress_id;
                $create_clean->shirtitems_id  = $orderdetail->shirtitems_id;
                $create_clean->skirtitems_id   = $orderdetail->skirtitems_id;
                $create_clean->clean_status = 'รอดำเนินการ';
                $create_clean->save();
            } elseif ($request->input('return_status') == "ต้องซ่อมแซม") {
                if ($orderdetail->shirtitems_id) {
                    $update_shirt = Shirtitem::find($orderdetail->shirtitems_id);
                    $update_shirt->shirtitem_status = "ต้องซ่อมแซม";
                    $update_shirt->shirtitem_rental = $update_shirt->shirtitem_rental + 1;
                    $update_shirt->save();
                } elseif ($orderdetail->skirtitems_id) {
                    $update_skirt = Skirtitem::find($orderdetail->skirtitems_id);
                    $update_skirt->skirtitem_status = "ต้องซ่อมแซม";
                    $update_skirt->skirtitem_rental = $update_skirt->skirtitem_rental + 1;
                    $update_skirt->save();
                } elseif (($orderdetail->shirtitems_id == null && $orderdetail->skirtitems_id == null) && $orderdetail->dress_id) {
                    $dress = Dress::find($orderdetail->dress_id);
                    if ($dress->separable == 1) {
                        $dress->dress_status = 'ต้องซ่อมแซม';
                        $dress->dress_rental = $dress->dress_rental + 1;
                        $dress->save();
                    } elseif ($dress->separable == 2) {
                        $datashirt = Shirtitem::where('dress_id', $dress->id)->first();
                        $shirt = Shirtitem::find($datashirt->id);
                        $shirt->shirtitem_status = 'ต้องซ่อมแซม';
                        $shirt->shirtitem_rental = $shirt->shirtitem_rental + 1;
                        $shirt->save();

                        $dataskirt = Skirtitem::where('dress_id', $dress->id)->first();
                        $skirt = Skirtitem::find($dataskirt->id);
                        $skirt->skirtitem_status = 'ต้องซ่อมแซม';
                        $skirt->skirtitem_rental = $skirt->skirtitem_rental + 1;
                        $skirt->save();

                        $update_dress_status = Dress::find($dress->id);
                        $update_dress_status->dress_status = 'ต้องซ่อมแซม';
                        $update_dress_status->dress_rental = $update_dress_status->dress_rental + 1;
                        $update_dress_status->save();
                    }
                }
                //ตารางreqpari
                $create_repair = new Repair();
                $create_repair->dress_id = $orderdetail->dress_id;
                $create_repair->shirtitems_id  = $orderdetail->shirtitems_id;
                $create_repair->skirtitems_id   = $orderdetail->skirtitems_id;
                $create_repair->repair_description = $request->input('repair_details');
                $create_repair->clean_status = 'รอดำเนินการ';
                $create_repair->save();
            } elseif ($request->input('return_status') == "ไม่สามารถให้เช่าต่อได้") {

                if ($orderdetail->shirtitems_id) {
                    $update_shirt = Shirtitem::find($orderdetail->shirtitems_id);
                    $update_shirt->shirtitem_status = "ไม่สามารถให้เช่าต่อได้";
                    $update_shirt->shirtitem_rental = $update_shirt->shirtitem_rental + 1;
                    $update_shirt->save();
                } elseif ($orderdetail->skirtitems_id) {
                    $update_skirt = Skirtitem::find($orderdetail->skirtitems_id);
                    $update_skirt->skirtitem_status = "ไม่สามารถให้เช่าต่อได้";
                    $update_skirt->skirtitem_rental = $update_skirt->skirtitem_rental + 1;
                    $update_skirt->save();
                } elseif (($orderdetail->shirtitems_id == null && $orderdetail->skirtitems_id == null) && $orderdetail->dress_id) {
                    $dress = Dress::find($orderdetail->dress_id);
                    if ($dress->separable == 1) {
                        $dress->dress_status = 'ไม่สามารถให้เช่าต่อได้';
                        $dress->dress_rental = $dress->dress_rental + 1;
                        $dress->save();
                    } elseif ($dress->separable == 2) {
                        $datashirt = Shirtitem::where('dress_id', $dress->id)->first();
                        $shirt = Shirtitem::find($datashirt->id);
                        $shirt->shirtitem_status = 'ไม่สามารถให้เช่าต่อได้';
                        $shirt->shirtitem_rental = $shirt->shirtitem_rental + 1;
                        $shirt->save();

                        $dataskirt = Skirtitem::where('dress_id', $dress->id)->first();
                        $skirt = Skirtitem::find($dataskirt->id);
                        $skirt->skirtitem_status = 'ไม่สามารถให้เช่าต่อได้';
                        $skirt->skirtitem_rental = $skirt->skirtitem_rental + 1;
                        $skirt->save();

                        $update_dress_status = Dress::find($dress->id);
                        $update_dress_status->dress_status = 'ไม่สามารถให้เช่าต่อได้';
                        $update_dress_status->dress_rental = $update_dress_status->dress_rental + 1;
                        $update_dress_status->save();
                    }
                }
            }
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
        if ($status == 'เริ่มดำเนินการตัด') {
            //ตารางorderdetail
            $orderdetail->status_detail = "ตัดชุดเสร็จสิ้น";
            $orderdetail->save();
            //ตารางorderdetailstatus
            $create_status = new Orderdetailstatus();
            $create_status->order_detail_id = $id;
            $create_status->status = "ตัดชุดเสร็จสิ้น";
            $create_status->save();
        } elseif ($status == 'ตัดชุดเสร็จสิ้น') {
            if ($request->input('dressStatus') == "yes") {
                //ตารางorderdetail
                $orderdetail->status_detail = "รับชุดแล้ว";
                $orderdetail->real_pickup_date = now();
                $orderdetail->save();
                //ตารางorderdetailstatus
                $order_detail_id_for_new = $id;
                $create_status = new Orderdetailstatus();
                $create_status->order_detail_id = $order_detail_id_for_new;
                $create_status->status = "รับชุดแล้ว";
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
            } elseif ($request->input('dressStatus') == "no") {
                //ตารางorderdetail
                $orderdetail->status_detail = "แก้ไขชุด";
                $orderdetail->status_fix_measurement = 'รอการแก้ไข';
                $orderdetail->save();
                //ตารางorderdetailstatus
                $order_detail_id_for_new = $id;
                $create_status = new Orderdetailstatus();
                $create_status->order_detail_id = $order_detail_id_for_new;
                $create_status->status = "แก้ไขชุด";
                $create_status->save();

                //ตารางmea_orderdetail
                $id_for_edit_mea_cut = $request->input('id_for_edit_mea_cut_');
                $edit_mea_cut = $request->input('edit_mea_cut_');
                foreach ($id_for_edit_mea_cut as $index => $id) {
                    $update = Measurementorderdetail::find($id);
                    if ($update->measurement_number_old != $edit_mea_cut[$index]) {
                        $update->measurement_number = $edit_mea_cut[$index];
                        $update->status_measurement = 'รอการแก้ไข';
                        $update->save();
                    } else {
                        $update->measurement_number = $edit_mea_cut[$index];
                        $update->save();
                    }
                }
            }
        } elseif ($status == 'แก้ไขชุด') {
            //ตารางorderdetail
            $orderdetail->status_detail = "แก้ไขชุดเสร็จสิ้น";
            $orderdetail->status_fix_measurement = "แก้ไขแล้ว";
            $orderdetail->save();
            //ตารางorderdetailstatus
            $create_status = new Orderdetailstatus();
            $create_status->order_detail_id = $id;
            $create_status->status = "แก้ไขชุดเสร็จสิ้น";
            $create_status->save();
        } elseif ($status == 'แก้ไขชุดเสร็จสิ้น') {
            //ตารางorderdetail
            $orderdetail->status_detail = "รับชุดแล้ว";
            $orderdetail->real_pickup_date = now();
            $orderdetail->save();
            //ตารางorderdetailstatus
            $order_detail_id_for_new = $id;
            $create_status = new Orderdetailstatus();
            $create_status->order_detail_id = $order_detail_id_for_new;
            $create_status->status = "รับชุดแล้ว";
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

        $orderdetail = Orderdetail::find($id);
        $orderdetail->status_fix_measurement = 'แก้ไขแล้ว';
        $orderdetail->save();
        $id_for_mea = $request->input('id_for_mea_');
        foreach ($id_for_mea as $index => $id_for_mea) {
            $update_statsu_adjust = Measurementorderdetail::find($id_for_mea);
            $update_statsu_adjust->status_measurement = "แก้ไขแล้ว";
            $update_statsu_adjust->save();
        }
        // //บันทึกการวัดค่าใหม่ลงในตาราง meanow
        $mea_name = $request->input('mea_name_');
        $mea_number = $request->input('mea_number_');
        $mea_number_start = $request->input('mea_number_start_');
        $dress_id = $request->input('dress_id_');
        $item_shirt_id = $request->input('item_shirt_id_');
        $item_skirt_id = $request->input('item_skirt_id_');
        $max = Dressmeasurementnow::max('count');
        $max_count = $max + 1;
        foreach ($mea_name as $index => $mea_name) {
            $create_now = new Dressmeasurementnow();
            $create_now->dress_id = $dress_id[$index];
            $create_now->shirtitems_id = $item_shirt_id[$index];
            $create_now->skirtitems_id = $item_skirt_id[$index];
            $create_now->measurementnow_dress_name = $mea_name;
            $create_now->measurementnow_dress_number = $mea_number[$index];
            $create_now->measurementnow_dress_number_start = $mea_number_start[$index];
            $create_now->measurementnow_dress_unit = 'นิ้ว';
            $create_now->count = $max_count;
            $create_now->save();
        }

        return redirect()->back()->with('อัพเดตสถานะของแก้ไขการวัดสำเร็จ');
    }
}
