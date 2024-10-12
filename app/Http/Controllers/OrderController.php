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


    //ออเดอร์ดีเทล
    public function ordertotaldetail($id)
    {

        $order_id = $id;
        $orderdetail = Orderdetail::where('order_id', $id)->get();
        return view('employee.ordertotaldetail', compact('orderdetail', 'order_id'));
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
        return redirect()->route('employee.ordertotaldetailshow', ['id' => $id]);
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


        $orderdetailstatus = Orderdetailstatus::where('order_detail_id', $id)->get();
        $valuestatus = $orderdetail->status_detail;
        $valuestatus = Orderdetailstatus::where('order_detail_id', $id)
            ->latest('created_at')
            ->value('status');

        $status_if_dress = Reservation::where('dress_id', $orderdetail->dress_id)
            ->where('status_completed', 0)
            ->orderByRaw(" STR_TO_DATE(start_date, '%Y-%m-%d') asc")
            ->first();


        $his_dress_adjust = Dressmeasurementcutedit::where('order_detail_id', $id)->get();
        return view('employeerentdress.managedetailrentdress', compact('additional', 'dress_mea_adjust_modal_show', 'status_if_dress', 'orderdetail', 'dress', 'employee', 'fitting', 'cost', 'date', 'decoration', 'imagerent', 'mea_dress', 'mea_orderdetail', 'orderdetailstatus', 'valuestatus', 'customer', 'mea_orderdetail_for_adjust', 'dressimage', 'dress_mea_adjust', 'dress_mea_adjust_modal', 'dress_mea_adjust_button', 'his_dress_adjust'));
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
        $Date = Date::where('order_detail_id', $id)
            ->orderBy('created_at', 'desc')
            ->first();
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
        $dress_edit_cut = Dressmeasurementcutedit::where('order_detail_id', $id)->get();
        $dress_adjusts = Dressmeaadjustment::where('order_detail_id', $id)->get();
        $round = AdjustmentRound::where('order_detail_id', $id)->get();


        $route_modal = AdjustmentRound::where('order_detail_id', $id)
            ->orderBy('created_at', 'desc')
            ->first();



        return view('employeecutdress.managedetailcutdress', compact('orderdetail', 'dress', 'employee', 'fitting', 'cost', 'Date', 'decoration', 'imagerent', 'mea_dress', 'mea_orderdetail', 'orderdetailstatus', 'valuestatus', 'customer', 'mea_orderdetailforedit', 'dress_adjusts', 'dress_edit_cut', 'round', 'route_modal'));
    }


    public function ordertotaldetailpostpone($id)
    {

        //แยกก่อนว่าชุดแยกเช่าได้หรือแยกไม่ได้
        $dress_id = Orderdetail::where('id', $id)->value('dress_id');
        $dress_check = Dress::find($dress_id);
        if ($dress_check->separable == 1) {
            return $this->detailpostponeno($id);
        } elseif ($dress_check->separable == 2) {
            return $this->detailpostponeyes($id);
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
            // ->whereIn('status', ['ถูกจอง', "กำลังเช่า"])
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
        $condition = false;
        return view('employeerentdress.postponeno', compact('reservation_dress_total', 'orderdetail', 'reser', 'dress', 'typedress', 'cus', 'text_status', 'value_start_date', 'value_end_date', 'condition'));
    }

    //checkการแก้ไข
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

        $past_7 = $pickup->copy()->subDays(7);
        $past_1 = $pickup->copy()->subDays(1);
        $pickup_start = $pickup->copy();
        $return_end = $return->copy();
        $future_1 = $return->copy()->addDays(1);
        $future_7 = $return->copy()->addDays(7);


        $check_reservation = Reservation::where('status_completed', 0)
            ->where('dress_id', $dress->id)
            ->whereNot('id', $reser->id)
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
        return view('employeerentdress.postponeno', compact('reservation_dress_total', 'orderdetail', 'reser', 'dress', 'typedress', 'cus', 'text_status', 'value_start_date', 'value_end_date', 'condition'));
    }
















    private function detailpostponeyes($id)
    {
        dd($id);
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

            //ตารางreservation 
            $reservation = Reservation::find($orderdetail->reservation_id);
            $reservation->status = 'กำลังเช่า';
            $reservation->save();

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
        } elseif ($status == "กำลังเช่า") {


            $total_damage_insurance = $request->input('total_damage_insurance'); //1.ปรับเงินประกันจริงๆ 
            $late_return_fee = $request->input('late_return_fee'); //2.ค่าปรับส่งคืนชุดล่าช้า:
            $late_chart = $request->input('late_chart'); //3.ค่าธรรมเนียมขยายระยะเวลาเช่า:

            if ($total_damage_insurance > 0) {
                $create_additional = new AdditionalChange();
                $create_additional->order_detail_id = $id;
                $create_additional->charge_type = 1;
                $create_additional->amount = $total_damage_insurance;
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


            //+1จำนวนคราั้งที่ถูกเช่า
            $dress = Dress::where('id', $orderdetail->dress_id)->first();
            if ($dress->separable == 1) {
                $update_dress = Dress::find($dress->id);
                $update_dress->dress_rental = $update_dress->dress_rental + 1; //จำนวนครั้งที่ถูกเช่า
                $update_dress->save();
            } elseif ($dress->separable == 2) {
                if ($orderdetail->shirtitems_id != null) {
                    //เช่าแค่เสื้อ
                    $update_shirt = Shirtitem::find($orderdetail->shirtitems_id);
                    $update_shirt->shirtitem_rental =  $update_shirt->shirtitem_rental  + 1;
                    $update_shirt->save();
                } elseif ($orderdetail->skirtitems_id != null) {
                    //เช่าแค่ผ้าถุง
                    $update_skirt = Skirtitem::find($orderdetail->skirtitems_id);
                    $update_skirt->skirtitem_rental =  $update_skirt->skirtitem_rental  + 1;
                    $update_skirt->save();
                } elseif ($orderdetail->skirtitems_id == null &&  $orderdetail->shirtitems_id == null) {
                    //เช่าทั้งชุด
                    // +1ชุด
                    $update_dress = Dress::find($dress->id);
                    $update_dress->dress_rental = $update_dress->dress_rental + 1; //จำนวนครั้งที่ถูกเช่า
                    $update_dress->save();

                    $shirt_id = Shirtitem::where('dress_id', $update_dress->id)->value('id');
                    $skirt_id = Skirtitem::where('dress_id', $update_dress->id)->value('id');
                    // +1เสื้อ
                    $update_shirt = Shirtitem::find($shirt_id);
                    $update_shirt->shirtitem_rental =  $update_shirt->shirtitem_rental  + 1;
                    $update_shirt->save();
                    // +1ผ้าถุง
                    $update_skirt = Skirtitem::find($skirt_id);
                    $update_skirt->skirtitem_rental =  $update_skirt->skirtitem_rental  + 1;
                    $update_skirt->save();
                }
            }




            if ($request->input('return_status') == "ส่งซัก") {
                $text_for_reservation = "รอดำเนินการส่งซัก";
                // ตารางclean
                $create_clean = new Clean();
                $create_clean->reservation_id = $orderdetail->reservation_id;
                $create_clean->clean_status = "รอดำเนินการ";
                $create_clean->save();
                //ตารางstatus
                $create_status = new Orderdetailstatus();
                $create_status->status = "รอดำเนินการ";
                $create_status->clean_id = $create_clean->id;
                $create_status->save();
            } elseif ($request->input('return_status') == "ต้องซ่อมแซม") {
                $text_for_reservation = "รอดำเนินการซ่อม";
                //ตารางreqpair 
                $create_repair = new Repair();
                $create_repair->reservation_id = $orderdetail->reservation_id;
                $create_repair->repair_description = $request->input('repair_details');
                $create_repair->repair_status = 'รอดำเนินการ';
                $create_repair->repair_type = $request->input('repair_type'); //10ทั้งชุด 20เสื้อ 30ผ้าถุง
                $create_repair->save();
                //ตารางstatus
                $create_status = new Orderdetailstatus();
                $create_status->status = "รอดำเนินการ";
                $create_status->repair_id = $create_repair->id;
                $create_status->save();
            }
            //ตารางreservation 
            $reservation = Reservation::find($orderdetail->reservation_id);
            $reservation->status = $text_for_reservation;
            $reservation->save();
            // elseif ($request->input('return_status') == "ไม่สามารถให้เช่าต่อได้") {

            //     if ($orderdetail->shirtitems_id) {
            //         $update_shirt = Shirtitem::find($orderdetail->shirtitems_id);
            //         $update_shirt->shirtitem_status = "ไม่สามารถให้เช่าต่อได้";
            //         $update_shirt->shirtitem_rental = $update_shirt->shirtitem_rental + 1;
            //         $update_shirt->save();
            //     } elseif ($orderdetail->skirtitems_id) {
            //         $update_skirt = Skirtitem::find($orderdetail->skirtitems_id);
            //         $update_skirt->skirtitem_status = "ไม่สามารถให้เช่าต่อได้";
            //         $update_skirt->skirtitem_rental = $update_skirt->skirtitem_rental + 1;
            //         $update_skirt->save();
            //     } elseif (($orderdetail->shirtitems_id == null && $orderdetail->skirtitems_id == null) && $orderdetail->dress_id) {
            //         $dress = Dress::find($orderdetail->dress_id);
            //         if ($dress->separable == 1) {
            //             $dress->dress_status = 'ไม่สามารถให้เช่าต่อได้';
            //             $dress->dress_rental = $dress->dress_rental + 1;
            //             $dress->save();
            //         } elseif ($dress->separable == 2) {
            //             $datashirt = Shirtitem::where('dress_id', $dress->id)->first();
            //             $shirt = Shirtitem::find($datashirt->id);
            //             $shirt->shirtitem_status = 'ไม่สามารถให้เช่าต่อได้';
            //             $shirt->shirtitem_rental = $shirt->shirtitem_rental + 1;
            //             $shirt->save();

            //             $dataskirt = Skirtitem::where('dress_id', $dress->id)->first();
            //             $skirt = Skirtitem::find($dataskirt->id);
            //             $skirt->skirtitem_status = 'ไม่สามารถให้เช่าต่อได้';
            //             $skirt->skirtitem_rental = $skirt->skirtitem_rental + 1;
            //             $skirt->save();

            //             $update_dress_status = Dress::find($dress->id);
            //             $update_dress_status->dress_status = 'ไม่สามารถให้เช่าต่อได้';
            //             $update_dress_status->dress_rental = $update_dress_status->dress_rental + 1;
            //             $update_dress_status->save();
            //         }
            //     }
            // }
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

        if ($status == "รอดำเนินการตัด") {
            //ตารางorderdetail
            $orderdetail->status_detail = "เริ่มดำเนินการตัด";
            $orderdetail->save();
            //ตารางorderdetailstatus
            $create_status = new Orderdetailstatus();
            $create_status->order_detail_id = $id;
            $create_status->status = "เริ่มดำเนินการตัด";
            $create_status->save();
        } elseif ($status == 'เริ่มดำเนินการตัด') {
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
                $orderdetail->status_detail = "ส่งมอบชุดแล้ว";
                $orderdetail->real_pickup_date = now();
                $orderdetail->save();
                //ตารางorderdetailstatus
                $order_detail_id_for_new = $id;
                $create_status = new Orderdetailstatus();
                $create_status->order_detail_id = $order_detail_id_for_new;
                $create_status->status = "ส่งมอบชุดแล้ว";
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
                $orderdetail->pickup_date = $request->input('pickup_date_new');
                // $orderdetail->status_fix_measurement = 'รอการแก้ไข';
                $orderdetail->save();
                //ตารางorderdetailstatus
                $order_detail_id_for_new = $id;
                $create_status = new Orderdetailstatus();
                $create_status->order_detail_id = $order_detail_id_for_new;
                $create_status->status = "แก้ไขชุด";
                $create_status->save();

                // ตารางdate
                $create_date = new Date();
                $create_date->order_detail_id = $order_detail_id_for_new;
                $create_date->pickup_date = $request->input('pickup_date_new');
                $create_date->save();


                //ตารางmea_orderdetail
                $id_for_edit_mea_cut = $request->input('id_for_edit_mea_cut_');
                $edit_mea_cut = $request->input('edit_mea_cut_');

                $max_count = Dressmeasurementcutedit::where('order_detail_id', $id)->max('adjustment_number');

                foreach ($id_for_edit_mea_cut as $index => $id) {
                    $update = Dressmeaadjustment::find($id);
                    if ($update->new_size != $edit_mea_cut[$index]) {
                        $create_mea_cut_edit = new Dressmeasurementcutedit();
                        $create_mea_cut_edit->adjustment_id = $update->id;
                        $create_mea_cut_edit->order_detail_id = $update->order_detail_id;
                        $create_mea_cut_edit->old_size = $update->new_size;
                        $create_mea_cut_edit->edit_new_size = $edit_mea_cut[$index];
                        $create_mea_cut_edit->adjustment_number = $max_count + 1;
                        $create_mea_cut_edit->status = 'รอการแก้ไข';
                        $create_mea_cut_edit->save();
                    }
                }
            }
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

            // ตารางdate
            $date_id = Date::where('order_detail_id', $id)
                ->orderBy('created_at', 'desc')
                ->value('id');
            $update_date = Date::find($date_id);
            $update_date->actua_pickup_date = now();
            $update_date->save();
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








        return redirect()->back()->with('อัพเดตสถานะของแก้ไขการวัดสำเร็จ');
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


    public function searchadddresstocart(Request $request)
    {
        $character = $request->input('character');
        // 10คือทั้งชุด 20เสื้อ 30 กระโปรงหรือผ้าถุง 
        if ($character == 10) {
            return $this->searchadddresstocarttotaldress($request);
        } elseif ($character == 20) {
            return $this->searchadddresstocartshirt($request);
        } elseif ($character == 30) {
            return $this->searchadddresstocartskirt($request);
        }
    }

    //ทั้งชุด
    public function searchadddresstocarttotaldress(Request $request)
    {
        $text_startDate = $request->input('startDate');
        $text_endDate = $request->input('endDate');
        $text_totalDay = $request->input('totalDay');
        $typedress = Typedress::all();
        $dress_type = $request->input('dress_type');
        $dress_type_id = Typedress::where('type_dress_name', $dress_type)->value('id');

        $dress = Dress::where('type_dress_id', $dress_type_id)->get();
        $pickupdate = Carbon::parse($request->input('startDate'));
        $returndate = Carbon::parse($request->input('endDate'));
        $list_dress_id_pass = [];

        foreach ($dress as $index) {
            //แยกไม่ได้
            if ($index->separable == 1) {
                $reservation = Reservation::where('dress_id', $index->id)
                    ->where('status_completed', 0)->get();
                $past_7_day_start = $pickupdate->copy()->subDays(7);
                $past_7_day_end = $pickupdate->copy()->subDays(1);
                $pickup_day_start = $pickupdate;
                $return_day_end = $returndate;
                $future_7_day_start = $returndate->copy()->addDays(1);
                $future_7_day_end = $returndate->copy()->addDays(7);
                $is_avalable = true;
                if ($reservation->isNotEmpty()) {
                    foreach ($reservation as $re) {

                        $reservation_start = Carbon::parse($re->start_date);
                        $reservation_end = Carbon::parse($re->end_date);

                        //เงื่อนไขหน้า
                        if ($reservation_start->between($past_7_day_start, $past_7_day_end) || $reservation_end->between($past_7_day_start, $past_7_day_end)) {
                            $is_avalable = false;
                            break;
                        }
                        if ($reservation_start->between($pickup_day_start, $return_day_end) || $reservation_end->between($pickup_day_start, $return_day_end)) {
                            $is_avalable = false;
                            break;
                        }
                        if ($reservation_start->between($future_7_day_start, $future_7_day_end) || $reservation_end->between($future_7_day_start, $future_7_day_end)) {
                            $is_avalable = false;
                            break;
                        }
                    }
                }
                if ($is_avalable) {
                    $list_dress_id_pass[] = $index->id;
                }
            }


            //แยกได้
            elseif ($index->separable == 2) {

                $is_avalable = true;
                //เช็คเสื้อ
                $shirt = Shirtitem::where('dress_id', $index->id)->first();
                $reservation_shirt = Reservation::where('shirtitems_id', $shirt->id)
                    ->where('status_completed', 0)
                    ->get();
                $past_7_day_start = $pickupdate->copy()->subDays(7);
                $past_7_day_end = $pickupdate->copy()->subDays(1);
                $pickup_day_start = $pickupdate;
                $return_day_end = $returndate;
                $future_7_day_start = $returndate->copy()->addDays(1);
                $future_7_day_end = $returndate->copy()->addDays(7);

                foreach ($reservation_shirt as $check) {
                    $reservation_start = Carbon::parse($check->start_date);
                    $reservation_end = Carbon::parse($check->end_date);
                    if ($reservation_start->between($past_7_day_start, $past_7_day_end) || $reservation_end->between($past_7_day_start, $past_7_day_end)) {
                        $is_avalable = false;
                        break;
                    }
                    if ($reservation_start->between($pickup_day_start, $return_day_end)  || $reservation_end->between($pickup_day_start, $return_day_end)) {
                        $is_avalable = false;
                        break;
                    }
                    if ($reservation_start->between($future_7_day_start, $future_7_day_end) || $reservation_end->between($future_7_day_start, $future_7_day_end)) {
                        $is_avalable = false;
                        break;
                    }
                }
                //เช็คกระโปรง/ผ้าถุง
                $skirt = Skirtitem::where('dress_id', $index->id)->first();
                $reservation_skirt = Reservation::where('skirtitems_id', $skirt->id)
                    ->where('status_completed', 0)
                    ->get();
                foreach ($reservation_skirt as $check) {
                    $reservation_start = Carbon::parse($check->start_date);
                    $reservation_end = Carbon::parse($check->end_date);
                    if ($reservation_start->between($past_7_day_start, $past_7_day_end) || $reservation_end->between($past_7_day_start, $past_7_day_end)) {
                        $is_avalable = false;
                        break;
                    }
                    if ($reservation_start->between($pickup_day_start, $return_day_end)  || $reservation_end->between($pickup_day_start, $return_day_end)) {
                        $is_avalable = false;
                        break;
                    }
                    if ($reservation_start->between($future_7_day_start, $future_7_day_end) || $reservation_end->between($future_7_day_start, $future_7_day_end)) {
                        $is_avalable = false;
                        break;
                    }
                }

                //เช็คชุดเดี่ยว เช้คแค่คอลัมน์ dress_id อย่างเดียว เพราะบางทีเช่าทั้งชุดก็จริงแต่ว่า  ตอนเช่าทั้งชุดถึงแม้ว่าจะชุดจะแยกได้ มันระบุแค่ dress_id ฉะนั้น เราต้องเช้คด้วย
                $reservation_dress = Reservation::where('dress_id', $index->id)
                    ->whereNull('shirtitems_id')
                    ->whereNull('skirtitems_id')
                    ->where('status_completed', 0)
                    ->get();
                foreach ($reservation_dress as $check) {
                    $reservation_start = Carbon::parse($check->start_date);
                    $reservation_end = Carbon::parse($check->end_date);
                    if ($reservation_start->between($past_7_day_start, $past_7_day_end) || $reservation_end->between($past_7_day_start, $past_7_day_end)) {
                        $is_avalable = false;
                        break;
                    }
                    if ($reservation_start->between($pickup_day_start, $return_day_end)  || $reservation_end->between($pickup_day_start, $return_day_end)) {
                        $is_avalable = false;
                        break;
                    }
                    if ($reservation_start->between($future_7_day_start, $future_7_day_end) || $reservation_end->between($future_7_day_start, $future_7_day_end)) {
                        $is_avalable = false;
                        break;
                    }
                }

                if ($is_avalable) {
                    $list_dress_id_pass[] = $index->id;
                }
            }
        }
        $character = $request->input('character');

        if ($character === "10") {
            $textcharacter = "ทั้งชุด";
        } elseif ($character === "20") {
            $textcharacter = "เสื้อ";
        } elseif ($character === "30") {
            $textcharacter = 'กระโปรง/ผ้าถุง';
        }

        $avalable_rent_pass = Dress::whereIn('id', $list_dress_id_pass)->get();
        return view('employeerentdress.adddresstocart', compact('typedress', 'dress', 'dress_type', 'avalable_rent_pass', 'textcharacter', 'text_startDate', 'text_endDate', 'text_totalDay'));
    }





    //เสื้อ
    public function searchadddresstocartshirt(Request $request)
    {
        $text_startDate = $request->input('startDate');
        $text_endDate = $request->input('endDate');
        $text_totalDay = $request->input('totalDay');

        $typedress = Typedress::all();
        $dress_type = $request->input('dress_type');
        $dress_type_id = Typedress::where('type_dress_name', $dress_type)->value('id');
        $dress = Dress::where('type_dress_id', $dress_type_id)
            ->where('separable', 2)
            ->get();
        $pickupdate = Carbon::parse($request->input('startDate'));
        $returndate = Carbon::parse($request->input('endDate'));
        $list_dress_id_pass = [];
        foreach ($dress as $index) {
            //เช็คเสื้อก่อนติดไหม 
            $shirt = Shirtitem::where('dress_id', $index->id)->first();
            $reservation = Reservation::where('shirtitems_id', $shirt->id)
                ->whereNull('skirtitems_id')
                ->where('status_completed', 0)
                ->get();
            $past_7_day_start = $pickupdate->copy()->subDays(7);
            $past_7_day_end = $pickupdate->copy()->subDays(1);
            $pickup_day_start = $pickupdate;
            $return_day_end = $returndate;
            $future_7_day_start = $returndate->copy()->addDays(1);
            $future_7_day_end = $returndate->copy()->addDays(7);
            $is_avalable = true;
            if ($reservation->isNotEmpty()) {
                foreach ($reservation as $check) {
                    $reservation_start = Carbon::parse($check->start_date);
                    $reservation_end = Carbon::parse($check->end_date);

                    if ($reservation_start->between($past_7_day_start, $past_7_day_end) || $reservation_end->between($past_7_day_start, $past_7_day_end)) {
                        $is_avalable = false;
                        break;
                    }

                    if ($reservation_start->between($pickup_day_start, $return_day_end)  || $reservation_end->between($pickup_day_start, $return_day_end)) {
                        $is_avalable = false;
                        break;
                    }

                    if ($reservation_start->between($future_7_day_start, $future_7_day_end) || $reservation_end->between($future_7_day_start, $future_7_day_end)) {
                        $is_avalable = false;
                        break;
                    }
                }
            }
            //เช็คทั้งชุดด้วย เพราะจะเช่าแค่เสื้อได้ ต้องเช็คชุดก่อนว่ามันติดจองทั้งชุดไหม นึกออกปะ 
            $reservation_dress = Reservation::where('dress_id', $index->id)
                ->whereNull('shirtitems_id')
                ->whereNull('skirtitems_id')
                ->where('status_completed', 0)
                ->get();
            if ($reservation_dress->isNotEmpty()) {
                foreach ($reservation_dress as $check) {
                    $reservation_start = Carbon::parse($check->start_date);
                    $reservation_end = Carbon::parse($check->end_date);

                    if ($reservation_start->between($past_7_day_start, $past_7_day_end) || $reservation_end->between($past_7_day_start, $past_7_day_end)) {
                        $is_avalable = false;
                        break;
                    }

                    if ($reservation_start->between($pickup_day_start, $return_day_end)  || $reservation_end->between($pickup_day_start, $return_day_end)) {
                        $is_avalable = false;
                        break;
                    }

                    if ($reservation_start->between($future_7_day_start, $future_7_day_end) || $reservation_end->between($future_7_day_start, $future_7_day_end)) {
                        $is_avalable = false;
                        break;
                    }
                }
            }
            if ($is_avalable) {
                $list_dress_id_pass[] = $index->id;
            }
        }


        $avalable_rent_pass = Dress::whereIn('id', $list_dress_id_pass)->get();

        $character = $request->input('character');

        if ($character === "10") {
            $textcharacter = "ทั้งชุด";
        } elseif ($character === "20") {
            $textcharacter = "เสื้อ";
        } elseif ($character === "30") {
            $textcharacter = 'กระโปรง/ผ้าถุง';
        }

        return view('employeerentdress.adddresstocart', compact('typedress', 'dress', 'dress_type', 'avalable_rent_pass', 'textcharacter', 'text_startDate', 'text_endDate', 'text_totalDay'));
    }

    //กระโปรง/ผ้าถุง
    public function searchadddresstocartskirt(Request $request)
    {
        $text_startDate = $request->input('startDate');
        $text_endDate = $request->input('endDate');
        $text_totalDay = $request->input('totalDay');

        $typedress = Typedress::all();
        $dress_type = $request->input('dress_type');
        $dress_type_id = Typedress::where('type_dress_name', $dress_type)->value('id');
        $dress = Dress::where('type_dress_id', $dress_type_id)
            ->where('separable', 2)
            ->get();
        $pickupdate = Carbon::parse($request->input('startDate'));
        $returndate = Carbon::parse($request->input('endDate'));
        $list_dress_id_pass = [];
        foreach ($dress as $index) {
            //เช็คแค่กระโปรงก่อน
            $skirt = Shirtitem::where('dress_id', $index->id)->first();
            $reservation = Reservation::where('skirtitems_id', $skirt->id)
                ->whereNull('shirtitems_id')
                ->where('status_completed', 0)
                ->get();
            $past_7_day_start = $pickupdate->copy()->subDays(7);
            $past_7_day_end = $pickupdate->copy()->subDays(1);
            $pickup_day_start = $pickupdate;
            $return_day_end = $returndate;
            $future_7_day_start = $returndate->copy()->addDays(1);
            $future_7_day_end = $returndate->copy()->addDays(7);
            $is_avalable = true;
            if ($reservation->isNotEmpty()) {

                foreach ($reservation as $check) {
                    $reservation_start = Carbon::parse($check->start_date);
                    $reservation_end = Carbon::parse($check->end_date);

                    if ($reservation_start->between($past_7_day_start, $past_7_day_end) || $reservation_end->between($past_7_day_start, $past_7_day_end)) {
                        $is_avalable = false;
                        break;
                    }

                    if ($reservation_start->between($pickup_day_start, $return_day_end)  || $reservation_end->between($pickup_day_start, $return_day_end)) {
                        $is_avalable = false;
                        break;
                    }

                    if ($reservation_start->between($future_7_day_start, $future_7_day_end) || $reservation_end->between($future_7_day_start, $future_7_day_end)) {
                        $is_avalable = false;
                        break;
                    }
                }
            }
            // เช็คทั้งชุดด้วย  เพราะว่า ถ้าจะเช่ากระโปรงอะ จะไปเช็คแค่กระโปรงมันไม่ได้ มันต้องเช็คทั้งชุดก่อนว่าติดจองไหม 
            $reservation_dress = Reservation::where('dress_id', $index->id)
                ->whereNull('shirtitems_id')
                ->whereNull('skirtitems_id')
                ->where('status_completed', 0)
                ->get();
            if ($reservation_dress->isNotEmpty()) {
                foreach ($reservation_dress as $check) {
                    $reservation_start = Carbon::parse($check->start_date);
                    $reservation_end = Carbon::parse($check->end_date);
                    if ($reservation_start->between($past_7_day_start, $past_7_day_end) || $reservation_end->between($past_7_day_start, $past_7_day_end)) {
                        $is_avalable = false;
                        break;
                    }
                    if ($reservation_start->between($pickup_day_start, $return_day_end)  || $reservation_end->between($pickup_day_start, $return_day_end)) {
                        $is_avalable = false;
                        break;
                    }
                    if ($reservation_start->between($future_7_day_start, $future_7_day_end) || $reservation_end->between($future_7_day_start, $future_7_day_end)) {
                        $is_avalable = false;
                        break;
                    }
                }
            }
            if ($is_avalable) {
                $list_dress_id_pass[] = $index->id;
            }
        }

        $avalable_rent_pass = Dress::whereIn('id', $list_dress_id_pass)->get();

        $character = $request->input('character');

        if ($character === "10") {
            $textcharacter = "ทั้งชุด";
        } elseif ($character === "20") {
            $textcharacter = "เสื้อ";
        } elseif ($character === "30") {
            $textcharacter = 'กระโปรง/ผ้าถุง';
        }
        $text_startDate = $request->input('startDate');
        $text_endDate = $request->input('endDate');
        $text_totalDay = $request->input('totalDay');

        return view('employeerentdress.adddresstocart', compact('typedress', 'dress', 'dress_type', 'avalable_rent_pass', 'textcharacter', 'text_startDate', 'text_endDate', 'text_totalDay'));
    }

    //เพิ่มชุด/เสื้อ/ผผ้าถุง ลงบนตะกร้า 
    public function addtocart(Request $request)
    {
        $textcharacter = $request->input('textcharacter');
        $dress_id = $request->input('dress_id');
        $pickupdate = $request->input('pickupdate');
        $returndate = $request->input('returndate');
        $totalday = $request->input('totalday');
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
                $create_order_detail->employee_id = $employee_id;
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
                $create_order->save();

                //ตารางorderdetail
                $create_order_detail = new Orderdetail();
                $create_order_detail->dress_id = $dress_id;
                $create_order_detail->order_id = $create_order->id;
                $create_order_detail->employee_id = $employee_id;
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
                $create_order_detail->employee_id = $employee_id;
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
                $create_order->save();

                //ตารางorderdetail
                $create_order_detail = new Orderdetail();
                $create_order_detail->dress_id = $dress_id;
                $create_order_detail->shirtitems_id  = $shirt_id;
                $create_order_detail->order_id = $create_order->id;
                $create_order_detail->employee_id = $employee_id;
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
                $create_order_detail->employee_id = $employee_id;
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
                $create_order->save();

                //ตารางorderdetail
                $create_order_detail = new Orderdetail();
                $create_order_detail->dress_id = $dress_id;
                $create_order_detail->skirtitems_id  = $skirt_id;
                $create_order_detail->order_id = $create_order->id;
                $create_order_detail->employee_id = $employee_id;
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
