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
use App\Models\Jewelryset;
use App\Models\Jewelryimage;
use App\Models\Measurementorderdetail;
use App\Models\Order;
use App\Models\Orderdetail;
use App\Models\Orderdetailstatus;
use App\Models\Paymentstatus;
use App\Models\Shirtitem;
use App\Models\Skirtitem;
use App\Models\Clean;
use App\Models\Receipt;

use App\Models\Typedress;
use App\Models\Typejewelry;

use App\Models\Dressmeaadjustment;
use App\Models\Repair;

use App\Models\Customer;
use App\Models\Dressmeasurementnow;
use App\Models\Jewelrysetitem;
use App\Models\User;
use App\Models\Reservationfilters;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    //
    public function homepage()
    {

        // เครื่องประดับที่ต้องรับคืนวันนี้
        $return_jewelry_today_after_jew = Reservation::where('status_completed', 0)
            ->whereNotNull('jewelry_id')
            ->where('status', 'กำลังเช่า')
            ->whereDate('end_date', now())
            ->get();
        $list_return_jew = [];
        foreach ($return_jewelry_today_after_jew as $item) {
            $list_return_jew[] = $item->id;
        }
        $return_jewelry_today_after_set_jew = Reservation::where('status_completed', 0)
            ->whereNotNull('jewelry_set_id')
            ->where('status', 'กำลังเช่า')
            ->whereDate('end_date', now())
            ->get();
        foreach ($return_jewelry_today_after_set_jew as $item) {
            $list_return_jew[] = $item->id;
        }
        $return_jewelry_today = Reservation::whereIn('id', $list_return_jew)->get();


        // ชุดที่ต้องรับคืนวันนี้
        $return_dress_today = Reservation::where('status_completed', 0)
            ->orderByRaw("STR_TO_DATE(end_date,'%Y-%m-%d') asc")
            ->where('status', 'กำลังเช่า')
            ->whereDate('end_date', now())
            ->get();

        // ชุดที่รอการตัด
        $work_waiting_to_cut = Orderdetail::whereIn('type_order', [1, 4])
            ->where('status_detail', 'รอดำเนินการตัด')
            ->get();
        $clean_pending = Clean::where('clean_status', "รอดำเนินการ")->get();


        $repair = Repair::where('repair_status', 'รอดำเนินการ')
            ->whereNotNull('reservation_id')
            ->get();




        $employee = Auth::user();
        $time = now();
        return view('employee.employeehome', compact('employee', 'time', 'return_jewelry_today', 'return_dress_today', 'work_waiting_to_cut', 'clean_pending', 'repair'));
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




    public function dressadjust()
    {
        $reservations = Reservation::where('status_completed', 0)
            ->where('status', 'ถูกจอง')
            ->orderByRaw("STR_TO_DATE(start_date, '%Y-%m-%d') asc")
            ->whereDate('start_date', now())
            ->get();



        $filer = 'today';
        return view('employee.dressadjust', compact('reservations', 'filer'));
    }


    public function dressadjustfilter(Request $request)
    {
        $filter_click = $request->input('filter_click');

        if ($filter_click == "total") {
            $reservations = Reservation::where('status_completed', 0)
                ->where('status', 'ถูกจอง')
                ->orderByRaw("STR_TO_DATE(start_date, '%Y-%m-%d') asc")
                ->get();
            $filer = 'total';
        } elseif ($filter_click == "today") {
            return $this->dressadjust();
        }
        return view('employee.dressadjust', compact('reservations', 'filer'));
    }






    public function listdressreturn()
    {
        $listdressreturns = Reservation::where('status_completed', 0)
            ->orderByRaw("STR_TO_DATE(end_date,'%Y-%m-%d') asc")
            ->where('status', 'กำลังเช่า')
            ->whereDate('end_date', now())
            ->get();
        $filer = 'today';
        return view('employee.listdressreturn', compact('listdressreturns', 'filer'));
    }

    public function listdressreturnfilter(Request $request)
    {
        $filter_click = $request->input('filter_click');
        if ($filter_click == 'total') {
            $listdressreturns = Reservation::where('status_completed', 0)
                ->orderByRaw("STR_TO_DATE(end_date,'%Y-%m-%d') asc")
                ->where('status', 'กำลังเช่า')
                ->get();
            $filer = 'total';
        } elseif ($filter_click == 'today') {
            return $this->listdressreturn();
        }
        return view('employee.listdressreturn', compact('listdressreturns', 'filer'));
    }








    public function cutdressadjust()
    {
        $cutdresss_page_one = Orderdetail::where('type_order', 1)
            ->where('status_detail', 'รอดำเนินการตัด')
            ->orderByRaw(" STR_TO_DATE(pickup_date,'%Y-%m-%d') asc ")
            ->get();
        $cutdresss_page_two = Orderdetail::where('type_order', 1)
            ->where('status_detail', 'เริ่มดำเนินการตัด')
            ->orderByRaw(" STR_TO_DATE(pickup_date,'%Y-%m-%d') asc ")
            ->get();
        $cutdresss_page_three = Orderdetail::where('type_order', 1)
            ->where('status_detail', 'ตัดชุดเสร็จสิ้น')
            ->orderByRaw(" STR_TO_DATE(pickup_date,'%Y-%m-%d') asc ")
            ->get();

        $cutdresss_page_four = Orderdetail::where('type_order', 1)
            ->where('status_detail', 'แก้ไขชุด')
            ->orderByRaw(" STR_TO_DATE(pickup_date,'%Y-%m-%d') asc ")
            ->get();

        $cutdresss_page_five = Orderdetail::where('type_order', 1)
            ->where('status_detail', 'แก้ไขชุดเสร็จสิ้น')
            ->orderByRaw(" STR_TO_DATE(pickup_date,'%Y-%m-%d') asc ")
            ->get();

        return view('employee.cutdressadjust', compact('cutdresss_page_one', 'cutdresss_page_two', 'cutdresss_page_three', 'cutdresss_page_four', 'cutdresss_page_five'));
    }


    public function queuerentcuttotal()
    {
        $cutdresss_page_one = Orderdetail::where('type_order', 4)
            ->where('status_detail', 'รอดำเนินการตัด')
            ->orderByRaw(" STR_TO_DATE(pickup_date,'%Y-%m-%d') asc ")
            ->get();
        $cutdresss_page_two = Orderdetail::where('type_order', 4)
            ->where('status_detail', 'เริ่มดำเนินการตัด')
            ->orderByRaw(" STR_TO_DATE(pickup_date,'%Y-%m-%d') asc ")
            ->get();
        $cutdresss_page_three = Orderdetail::where('type_order', 4)
            ->where('status_detail', 'ตัดชุดเสร็จสิ้น')
            ->orderByRaw(" STR_TO_DATE(pickup_date,'%Y-%m-%d') asc ")
            ->get();

        return view('employeerentcut.queue-rentcut-total', compact('cutdresss_page_one', 'cutdresss_page_two', 'cutdresss_page_three'));
    }










    public function calendar()
    {
        $reservation = Reservation::all();
        return view('employee.calendar', compact('reservation'));
    }


    public function buttoncleanrowpageone($id)
    {
        $clean = clean::find($id);
        //ตารางclean
        $clean->clean_status = "กำลังส่งซัก";
        $clean->save();
        //ตารางstatus
        // $create_status = new Orderdetailstatus();
        // $create_status->status = "กำลังส่งซัก";
        // $create_status->clean_id = $clean->id;
        // $create_status->save();



        $reservation = Reservation::find($clean->reservation_id);
        $reservation->status = "กำลังส่งซัก";
        $reservation->save();
        return redirect()->back()->with('success', "อัพเดตสำเร็จ");
    }
    public function buttoncleanrowpagetwo($id)
    {
        $clean = Clean::find($id);
        //ตาราclean
        $clean->clean_status = "ซักเสร็จแล้ว";
        $clean->save();
        //ตารางstatus
        // $create_status = new Orderdetailstatus();
        // $create_status->status = "ซักเสร็จแล้ว";
        // $create_status->clean_id = $clean->id;
        // $create_status->save();
        //ตารางreservation 
        $reservation = Reservation::find($clean->reservation_id);
        $reservation->status = "ซักเสร็จแล้ว";
        $reservation->status_completed = 1; //เสร็จสมบูรณ์
        $reservation->save();
        return redirect()->back()->with('success', "อัพเดตสำเร็จ");
    }



    public function cleanupdatestatus(Request $request)
    {


        $next_status = $request->input('next_status'); //ซักเสร็จแล้ว  /  ส่งซ่อม 
        // $separate_clean_id = $request->input('select_item_');
        $separate_clean_id = explode(',', $request->input('select_item'));
        foreach ($separate_clean_id as $index => $clean_id) {
            $clean = Clean::find($clean_id);
            if ($clean->clean_status == "รอดำเนินการ") {
                //ตารางclean
                $clean->clean_status = "กำลังส่งซัก";
                $clean->save();
                //ตารางstatus
                $create_status = new Orderdetailstatus();
                $create_status->status = "กำลังส่งซัก";
                $create_status->clean_id = $clean->id;
                $create_status->save();

                $reservation = Reservation::find($clean->reservation_id);
                $reservation->status = "กำลังส่งซัก";
                $reservation->save();
            } elseif ($clean->clean_status == "กำลังส่งซัก") {
                if ($next_status == "ซักเสร็จแล้ว") {
                    //ตาราclean
                    $clean->clean_status = "ซักเสร็จแล้ว";
                    $clean->save();
                    //ตารางstatus
                    $create_status = new Orderdetailstatus();
                    $create_status->status = "ซักเสร็จแล้ว";
                    $create_status->clean_id = $clean->id;
                    $create_status->save();
                    //ตารางreservation 
                    $reservation = Reservation::find($clean->reservation_id);
                    $reservation->status = "ซักเสร็จแล้ว";
                    $reservation->status_completed = 1; //เสร็จสมบูรณ์
                    $reservation->save();
                } elseif ($next_status == "ต้องซ่อม") {
                    //ตาราclean
                    $clean->clean_status = "ซักเสร็จแล้ว";
                    $clean->save();
                    //ตารางstatus
                    $create_status = new Orderdetailstatus();
                    $create_status->status = "ซักเสร็จแล้ว";
                    $create_status->clean_id = $clean->id;
                    $create_status->save();

                    //เพิ่มข้อมูลในตาราง repair 
                    $create_repair = new Repair();
                    $create_repair->clean_id = $clean->id;
                    $create_repair->repair_status = 'รอดำเนินการ';
                    $create_repair->reservation_id =  $clean->reservation_id;
                    $create_repair->save();
                }
            }
        }
        return redirect()->back()->with('success', 'สถานะถูกอัพเดตเรียบร้อยแล้ว');
    }

    public function cleanupdatestatuspagetwo(Request $request)
    {
        $ID_for_clean = $request->input('ID_for_clean');
        $separate_clean_id = explode(',', $ID_for_clean);
        foreach ($separate_clean_id as $index => $clean_id) {
            $clean = Clean::find($clean_id);
            if ($clean->clean_status == "กำลังส่งซัก") {
                //ตาราclean
                $clean->clean_status = "ซักเสร็จแล้ว";
                $clean->save();
                //ตารางstatus
                $create_status = new Orderdetailstatus();
                $create_status->status = "ซักเสร็จแล้ว";
                $create_status->clean_id = $clean->id;
                $create_status->save();
                //ตารางreservation 
                $reservation = Reservation::find($clean->reservation_id);
                $reservation->status = "ซักเสร็จแล้ว";
                $reservation->status_completed = 1; //เสร็จสมบูรณ์
                $reservation->save();
            }
        }
        return redirect()->back()->with('success', 'สถานะถูกอัพเดตเรียบร้อยแล้ว');
    }

    //ซักเสร็จแล้ว แต่มันต้องซ่อมอะ 
    public function afterwashtorepair(Request $request)
    {
        $repair_detail = $request->input('repair_detail');
        $clean_id = $request->input('clean_id');

        //ตาราclean
        $clean = Clean::find($clean_id);
        $clean->clean_status = "ซักเสร็จแล้ว";
        $clean->save();
        //ตารางstatus
        // $order_status = new Orderdetailstatus();
        // $order_status->clean_id = $clean->id;
        // $order_status->status = "ซักเสร็จแล้ว";
        // $order_status->save();

        //เพิ่มข้อมูลในตาราง repair 
        $create_repair = new Repair();
        $create_repair->clean_id = $clean->id;
        $create_repair->repair_status = 'รอดำเนินการ';
        $create_repair->repair_type = $request->input('typerepair');   //10ทั้งชุด 20เสื้อ 30ผ้าถุง
        $create_repair->repair_description = $request->input('repair_detail');
        $create_repair->reservation_id =  $clean->reservation_id;
        $create_repair->save();
        //ตารางstatus
        // $order_status = new Orderdetailstatus();
        // $order_status->repair_id = $create_repair->id;
        // $order_status->status = "รอดำเนินการ";
        // $order_status->save();
        //ตารางreservation 
        $reservation = Reservation::find($clean->reservation_id);
        $reservation->status = "รอดำเนินการซ่อม";
        $reservation->save();
        return redirect()->back()->with('success', 'สถานะถูกอัพเดตเรียบร้อยแล้ว');
    }


    public function buttonrepairrowpageone($id)
    {
        $repair = Repair::find($id);
        if ($repair->repair_status == "รอดำเนินการ") {
            //ตารางrepair
            $repair->repair_status = "กำลังซ่อม";
            $repair->save();
            // ตารางstatus
            // $create_status = new Orderdetailstatus();
            // $create_status->repair_id = $repair->id;
            // $create_status->status = 'กำลังซ่อม';
            // $create_status->save();
            // ตารางreservation
            $reservation = Reservation::find($repair->reservation_id);
            $reservation->status = "กำลังซ่อม";
            $reservation->save();
        }
        return redirect()->back()->with('success', 'สถานะถูกอัพเดตเรียบร้อยแล้ว');
    }






    public function repairupdatestatus(Request $request)
    {

        $item = $request->input('item_check');
        $item_check = explode(',', $item);
        // dd($item_check) ; 
        // $item_check = $request->input('item_check_');
        foreach ($item_check as $index => $repair_id) {
            $repair = Repair::find($repair_id);
            if ($repair->repair_status == "รอดำเนินการ") {
                //ตารางrepair
                $repair->repair_status = "กำลังซ่อม";
                $repair->save();
                // ตารางstatus
                $create_status = new Orderdetailstatus();
                $create_status->repair_id = $repair->id;
                $create_status->status = 'กำลังซ่อม';
                $create_status->save();
                // ตารางreservation
                $reservation = Reservation::find($repair->reservation_id);
                $reservation->status = "กำลังซ่อม";
                $reservation->save();
            }
        }
        return redirect()->back()->with('success', 'สถานะถูกอัพเดตเรียบร้อยแล้ว');
    }
    public function repairupdatestatustoclean(Request $request)
    {
        dd('หน้านี้นะ');
        $item_check = $request->input('item_check_');
        foreach ($item_check as $index => $repair_id) {
            // ตารางrepair
            $repair = Repair::find($repair_id);
            $repair->repair_status = "ซ่อมเสร็จแล้ว";
            $repair->save();
            // ตารางstatus
            $create_status = new Orderdetailstatus();
            $create_status->repair_id = $repair->id;
            $create_status->status = "ซ่อมเสร็จแล้ว";
            $create_status->save();
            // ตารางreservation
            $reservation = Reservation::find($repair->reservation_id);
            $reservation->status = "รอดำเนินการส่งซัก";
            $reservation->save();
            // ตารางclean
            $create_clean = new Clean();
            $create_clean->clean_status = "รอดำเนินการ";
            $create_clean->reservation_id = $repair->reservation_id;
            $create_clean->save();
        }
        return redirect()->back()->with('success', 'สถานะถูกอัพเดตเรียบร้อยแล้ว');
    }

    public function repairupdatestatustocleanbutton($id)
    {
        // ตารางrepair
        $repair = Repair::find($id);
        $repair->repair_status = "ซ่อมเสร็จแล้ว";
        $repair->save();
        // ตารางstatus
        // $create_status = new Orderdetailstatus();
        // $create_status->repair_id = $repair->id;
        // $create_status->status = "ซ่อมเสร็จแล้ว";
        // $create_status->save();
        // ตารางreservation
        $reservation = Reservation::find($repair->reservation_id);
        $reservation->status = "รอดำเนินการส่งซัก";
        $reservation->save();
        // ตารางclean
        $create_clean = new Clean();
        $create_clean->clean_status = "รอดำเนินการ";
        $create_clean->reservation_id = $repair->reservation_id;
        $create_clean->save();
        return redirect()->back()->with('success', 'อัพเดตสถานะสำเร็จ');
    }









    public function repairupdatestatustocleanorready(Request $request)
    {
        dd('เอฟ');
        $item_check = $request->input('item_check_');
        $status_next = $request->input('status_next');
        // พร้อมให้เช่าต่อ
        if ($status_next == 1) {
            foreach ($item_check as $index => $repair_id) {
                // ตารางrepair
                $repair = Repair::find($repair_id);
                $repair->repair_status = "ซ่อมเสร็จแล้ว";
                $repair->save();
                // ตารางstatus
                $create_status = new Orderdetailstatus();
                $create_status->repair_id = $repair->id;
                $create_status->status = "ซ่อมเสร็จแล้ว";
                $create_status->save();
                // ตารางreservation
                $reservation = Reservation::find($repair->reservation_id);
                $reservation->status = "ซ่อมเสร็จแล้ว";
                $reservation->status_completed = 1;
                $reservation->save();
                // เพิ่มจำนวนครั้งในการแก้ไข  ทั้งชุด / เสื้อ /ผ้าถุง      
                if ($reservation->shirtitems_id != null) {
                    $shirt = Shirtitem::find($reservation->shirtitems_id);
                    $shirt->repair_count = $shirt->repair_count + 1;
                    $shirt->save();
                } elseif ($reservation->skirtitems_id != null) {
                    $skirt = Skirtitem::find($reservation->skirtitems_id);
                    $skirt->repair_count = $skirt->repair_count + 1;
                    $skirt->save();
                } else {
                    $dress = Dress::find($reservation->dress_id);
                    if ($dress->separable == 1) {
                        $dress->repair_count = $dress->repair_count + 1;
                        $dress->save();
                    } elseif ($dress->separable == 2) {
                        // ซ่อมทั้งชุด
                        if ($repair->repair_type == 10) {
                            // อัปเดตตาราง dress
                            $dress->repair_count = $dress->repair_count + 1;
                            $dress->save();
                            // อัปเดตตารางshirt
                            $shirt_id = Shirtitem::where('dress_id', $dress->id)->value('id');
                            $shirt = Shirtitem::find($shirt_id);
                            $shirt->repair_count = $shirt->repair_count + 1;
                            $shirt->save();
                            // อัปเดตตาราง skirt 
                            $skirt_id = Skirtitem::where('dress_id', $dress->id)->value('id');
                            $skirt = Skirtitem::find($skirt_id);
                            $skirt->repair_count = $skirt->repair_count + 1;
                            $skirt->save();
                        }
                        // ซ่อมแค่เสื้อ
                        elseif ($repair->repair_type == 20) {
                            // อัปเดตตารางshirt
                            $shirt_id = Shirtitem::where('dress_id', $dress->id)->value('id');
                            $shirt = Shirtitem::find($shirt_id);
                            $shirt->repair_count = $shirt->repair_count + 1;
                            $shirt->save();
                        }
                        // ซ่อมแค่ผ้าถุง
                        elseif ($repair->repair_type == 30) {
                            // อัปเดตตาราง skirt 
                            $skirt_id = Skirtitem::where('dress_id', $dress->id)->value('id');
                            $skirt = Skirtitem::find($skirt_id);
                            $skirt->repair_count = $skirt->repair_count + 1;
                            $skirt->save();
                        }
                    }
                }
            }
        }
        // ส่งไปซักอีกครั้ง
        elseif ($status_next == 2) {
            foreach ($item_check as $index => $repair_id) {
                // ตารางrepair
                $repair = Repair::find($repair_id);
                $repair->repair_status = "ซ่อมเสร็จแล้ว";
                $repair->save();
                // ตารางstatus
                $create_status = new Orderdetailstatus();
                $create_status->repair_id = $repair->id;
                $create_status->status = "ซ่อมเสร็จแล้ว";
                $create_status->save();
                // ตารางreservation
                $reservation = Reservation::find($repair->reservation_id);
                $reservation->status = 'รอดำเนินการส่งซัก';
                $reservation->save();
                // ตารางclean
                $create_clean = new Clean();
                $create_clean->reservation_id = $repair->reservation_id;
                $create_clean->clean_status = "รอดำเนินการ";
                $create_clean->save();
                // ตารางstatus
                $create_status = new Orderdetailstatus();
                $create_status->clean_id = $create_clean->id;
                $create_status->status = "รอดำเนินการ";
                $create_status->save();
            }
        }





        return redirect()->back()->with('success', 'สถานะถูกอัพเดตเรียบร้อยแล้ว');
    }


    public function repairupdatestatustocleanorreadybutton(Request $request, $id)
    {

        $status_next = $request->input('status_next');
        // พร้อมให้เช่าต่อ
        if ($status_next == 1) {
            // ตารางrepair
            $repair = Repair::find($id);
            $repair->repair_status = "ซ่อมเสร็จแล้ว";
            $repair->save();
            // ตารางstatus
            // $create_status = new Orderdetailstatus();
            // $create_status->repair_id = $repair->id;
            // $create_status->status = "ซ่อมเสร็จแล้ว";
            // $create_status->save();
            // ตารางreservation
            $reservation = Reservation::find($repair->reservation_id);
            $reservation->status = "ซ่อมเสร็จแล้ว";
            $reservation->status_completed = 1;
            $reservation->save();
            // เพิ่มจำนวนครั้งในการแก้ไข  ทั้งชุด / เสื้อ /ผ้าถุง      
            if ($reservation->shirtitems_id != null) {
                $shirt = Shirtitem::find($reservation->shirtitems_id);
                $shirt->repair_count = $shirt->repair_count + 1;
                $shirt->save();
            } elseif ($reservation->skirtitems_id != null) {
                $skirt = Skirtitem::find($reservation->skirtitems_id);
                $skirt->repair_count = $skirt->repair_count + 1;
                $skirt->save();
            } else {
                $dress = Dress::find($reservation->dress_id);
                if ($dress->separable == 1) {
                    $dress->repair_count = $dress->repair_count + 1;
                    $dress->save();
                } elseif ($dress->separable == 2) {
                    // ซ่อมทั้งชุด
                    if ($repair->repair_type == 10) {
                        // อัปเดตตาราง dress
                        $dress->repair_count = $dress->repair_count + 1;
                        $dress->save();
                        // อัปเดตตารางshirt
                        $shirt_id = Shirtitem::where('dress_id', $dress->id)->value('id');
                        $shirt = Shirtitem::find($shirt_id);
                        $shirt->repair_count = $shirt->repair_count + 1;
                        $shirt->save();
                        // อัปเดตตาราง skirt 
                        $skirt_id = Skirtitem::where('dress_id', $dress->id)->value('id');
                        $skirt = Skirtitem::find($skirt_id);
                        $skirt->repair_count = $skirt->repair_count + 1;
                        $skirt->save();
                    }
                    // ซ่อมแค่เสื้อ
                    elseif ($repair->repair_type == 20) {
                        // อัปเดตตารางshirt
                        $shirt_id = Shirtitem::where('dress_id', $dress->id)->value('id');
                        $shirt = Shirtitem::find($shirt_id);
                        $shirt->repair_count = $shirt->repair_count + 1;
                        $shirt->save();
                    }
                    // ซ่อมแค่ผ้าถุง
                    elseif ($repair->repair_type == 30) {
                        // อัปเดตตาราง skirt 
                        $skirt_id = Skirtitem::where('dress_id', $dress->id)->value('id');
                        $skirt = Skirtitem::find($skirt_id);
                        $skirt->repair_count = $skirt->repair_count + 1;
                        $skirt->save();
                    }
                }
            }
        }
        // ส่งไปซักอีกครั้ง
        elseif ($status_next == 2) {
            // ตารางrepair
            $repair = Repair::find($id);
            $repair->repair_status = "ซ่อมเสร็จแล้ว";
            $repair->save();
            // ตารางstatus
            // $create_status = new Orderdetailstatus();
            // $create_status->repair_id = $repair->id;
            // $create_status->status = "ซ่อมเสร็จแล้ว";
            // $create_status->save();
            // ตารางreservation
            $reservation = Reservation::find($repair->reservation_id);
            $reservation->status = 'รอดำเนินการส่งซัก';
            $reservation->save();
            // ตารางclean
            $create_clean = new Clean();
            $create_clean->reservation_id = $repair->reservation_id;
            $create_clean->clean_status = "รอดำเนินการ";
            $create_clean->save();
            // ตารางstatus
            // $create_status = new Orderdetailstatus();
            // $create_status->clean_id = $create_clean->id;
            // $create_status->status = "รอดำเนินการ";
            // $create_status->save();
        }
        return redirect()->back()->with('success', 'สถานะถูกอัพเดตเรียบร้อยแล้ว');
    }


    public function clean()
    {
        $clean = Clean::all();
        $clean_pending = Clean::where('clean_status', "รอดำเนินการ")->get();
        $cleans = Clean::all();
        $clean_doing_wash = Clean::where('clean_status', 'กำลังส่งซัก')->get();
        $countwait = Clean::where('clean_status', 'รอดำเนินการ')->count();
        $countdoing = Clean::where('clean_status', 'กำลังส่งซัก')->count();
        $countsuccess = Clean::where('clean_status', 'ซักเสร็จแล้ว')->count();
        return view('employee.clean', compact('clean', 'countwait', 'countdoing', 'countsuccess', 'cleans', 'clean_pending', 'clean_doing_wash'));
    }

    public function repair()
    {
        $repair = Repair::all();
        $repair_pending = Repair::where('repair_status', "รอดำเนินการ")
            ->whereNull('reservationfilter_id')
            ->get();
        $repairs_null = Repair::where('repair_status', "กำลังซ่อม")
            ->whereNull('reservationfilter_id')
            ->where('clean_id', null)->get();
        $repairs_not_null = Repair::where('repair_status', "กำลังซ่อม")
            ->whereNull('reservationfilter_id')
            ->whereNotNull('clean_id')->get();
            
        return view('employee.repair', compact('repair', 'repairs_null', 'repairs_not_null', 'repair_pending'));
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
                $order->type_order = 1; //1ตัด 2.เช่า 3.เช่าตัด
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

            $date = new Date();
            $date->order_detail_id = $orderdetail->id;
            $date->pickup_date = $request->input('pickup_date');
            $date->save();

            // บันทึกข้อมูลในตาราง Dressmeaadjustment
            if ($request->input('name_')) {
                $mea_name = $request->input('name_');
                $mea_number = $request->input('number_');
                if ($mea_name) {
                    foreach ($mea_name as $index => $mea) {
                        $data = new Dressmeaadjustment();
                        $data->order_detail_id = $orderdetail->id;
                        $data->name = $mea;
                        $data->new_size = $mea_number[$index];
                        $data->save();
                    }
                }
            }

            // บันทึกช้อม๔ุลงในตาราง rentimage
            if ($request->hasFile('file_image_')) {
                $imf_loop = $request->file('file_image_');
                $note_image = $request->input('note_image_');
                foreach ($imf_loop as $index => $img) {
                    $image_save = new Imagerent();
                    $image_save->order_detail_id = $orderdetail->id;
                    $image_save->image = $img->store('rent_images', 'public');
                    $image_save->description = $note_image[$index] ?? null;
                    $image_save->save();
                }
            }


            DB::commit();
            return redirect()->back()->with('success', 'เพิ่มลงตะกร้าแล้ว !');
        } catch (\Exception $e) {
            DB::rollback();
        }
    }



    public function savecutdressaddimage(Request $request, $id)
    {
        if ($request->hasFile('file_image')) {
            $add_image = new Imagerent();
            $add_image->order_detail_id = $id;
            $add_image->image = $request->file('file_image')->store('rent_images', 'public');
            $add_image->description = $request->input('note_image');
            $add_image->save();
        }
        return redirect()->back()->with('success', 'เพิ่มรูปภาพสำเร็จ');
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

        //ตัดชุด
        if ($delete_orderdetail->type_order == 1) {
            //ลบตารางdress_mea_adjust
            Dressmeaadjustment::where('order_detail_id', $id)->delete();
            //ลบลูกๆมันด้วย
            Imagerent::where('order_detail_id', $id)->delete();
            Paymentstatus::where('order_detail_id', $id)->delete();
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
        }
        // เช่าชุด
        elseif ($delete_orderdetail->type_order == 2) {
            //ลบตาราง reservation
            $delete_reservation = Reservation::find($delete_orderdetail->reservation_id);
            $delete_reservation->delete();

            //ลบตารางdress_mea_adjust
            Dressmeaadjustment::where('order_detail_id', $id)->delete();

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
        } elseif ($delete_orderdetail->type_order == 3) {
            //ลบตาราง reservation
            $delete_reservation = Reservation::find($delete_orderdetail->reservation_id);
            $delete_reservation->delete();

            //ลบลูกๆมันด้วย
            Imagerent::where('order_detail_id', $id)->delete();
            Paymentstatus::where('order_detail_id', $id)->delete();
            Fitting::where('order_detail_id', $id)->delete();
            Financial::where('order_detail_id', $id)->delete();
            Date::where('order_detail_id', $id)->delete();
            Orderdetailstatus::where('order_detail_id', $id)->delete();
            Reservationfilters::where('reservation_id', $delete_orderdetail->reservation_id)->delete();
            //อัปเดตตาราง order ด้วย เพราะorderdetail มันลบไปแล้วไง
            $ORDER_ID = $delete_orderdetail->order_id;
            $update_order = Order::find($ORDER_ID);
            $update_order->total_quantity = $update_order->total_quantity - 1; //รายการทั้งหมดจะลดลงทีละ1 
            $update_order->total_price = $update_order->total_price - ($delete_orderdetail->price * $delete_orderdetail->amount);
            $update_order->total_deposit = $update_order->total_deposit - ($delete_orderdetail->deposit * $delete_orderdetail->amount);
            $update_order->save();
        } elseif ($delete_orderdetail->type_order == 4) {


            //ลบตารางdress_mea_adjust
            Dressmeaadjustment::where('order_detail_id', $id)->delete();
            //ลบลูกๆมันด้วย
            Imagerent::where('order_detail_id', $id)->delete();
            Paymentstatus::where('order_detail_id', $id)->delete();
            Date::where('order_detail_id', $id)->delete();
            Measurementorderdetail::where('order_detail_id', $id)->delete();
            Orderdetailstatus::where('order_detail_id', $id)->delete();
            Fitting::where('order_detail_id', $id)->delete();

            //อัปเดตตาราง order ด้วย เพราะorderdetail มันลบไปแล้วไง
            $ORDER_ID = $delete_orderdetail->order_id;
            $update_order = Order::find($ORDER_ID);

            $update_order->total_quantity = $update_order->total_quantity - 1; //รายการทั้งหมดจะลดลงทีละ1 

            $update_order->total_price = $update_order->total_price - ($delete_orderdetail->price * $delete_orderdetail->amount);
            $update_order->total_deposit = $update_order->total_deposit - ($delete_orderdetail->deposit * $delete_orderdetail->amount);
            $update_order->save();
        }



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
        $measurementadjusts = Dressmeaadjustment::where('order_detail_id', $id)->get();
        $Date = Date::where('order_detail_id', $orderdetail->id)
            ->orderBy('created_at', 'desc')
            ->first();
        $image_rent = Imagerent::where('order_detail_id', $orderdetail->id)->get();
        return view('employeecutdress.manageitemcutdress', compact('orderdetail', 'type_dress', 'measurementorderdetail', 'fitting', 'measurementadjusts', 'Date', 'image_rent'));
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
        $dress_mea_adjust = Dressmeaadjustment::where('order_detail_id', $orderdetail->id)->get();
        $imagerent = Imagerent::where('order_detail_id', $id)->get();
        $Date = Date::where('order_detail_id', $orderdetail->id)
            ->orderBy('created_at', 'desc')
            ->first();
        return view('employeerentdress.manageitemrentdress', compact('orderdetail', 'type_dress', 'imagerent', 'dress', 'imagedress', 'dress_mea_adjust', 'Date'));
    }

    //เช่าเครื่องประดับ
    private function manageitemrentjewelry($id)
    {
        $id = $id->id;
        $orderdetail = Orderdetail::find($id);
        $reservation = Reservation::find($orderdetail->reservation_id);
        $jewelry = Jewelry::find($reservation->jewelry_id);
        if ($jewelry) {
            $typejewelry = Typejewelry::where('id', $jewelry->type_jewelry_id)->first();
            $imagejewelry = Jewelryimage::where('jewelry_id', $jewelry->id)->first();
            $setjewelryitem = null;
        } else {
            $setjewelry = Jewelryset::find($reservation->jewelry_set_id);
            $setjewelryitem = Jewelrysetitem::where('jewelry_set_id', $setjewelry->id)->get();
            $typejewelry = null;
            $imagejewelry = null;
        }
        $jewelryset = Jewelryset::find($reservation->jewelry_set_id);
        return view('employeerentjewelry.manageitemrentjewelry', compact('orderdetail', 'reservation', 'jewelry', 'typejewelry', 'imagejewelry', 'jewelryset', 'setjewelryitem'));
    }

    //เช่าตัด
    private function manageitemrentcut($id)
    {

        $id = $id->id;
        $type_dress = Typedress::all();
        $orderdetail = Orderdetail::find($id);
        $measurementorderdetail  = Measurementorderdetail::where('order_detail_id', $id)->get();
        $fittings = Fitting::where('order_detail_id', $id)->get();
        $measurementadjusts = Dressmeaadjustment::where('order_detail_id', $id)->get();
        $Date = Date::where('order_detail_id', $orderdetail->id)
            ->orderBy('created_at', 'desc')
            ->first();
        $image_rent = Imagerent::where('order_detail_id', $orderdetail->id)->get();
        return view('employeerentcut.manageitemrentcut', compact('orderdetail', 'type_dress', 'measurementorderdetail', 'fittings', 'measurementadjusts', 'Date', 'image_rent'));
    }
    //ลบdeletemeasurementitem ใน item
    public function deletemeasurementitem($id)
    {
        dd($id);
        $delete_measuremen = Dressmeaadjustment::find($id);
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
        $orderdetail = Orderdetail::where('order_id', $id)->paginate(2);
        $order = Order::find($id);
        // dd($date_now) ; 
        $total_deposit = Orderdetail::where('order_id', $id)->sum('deposit');
        $total_price = Orderdetail::where('order_id', $id)->sum('price');
        $total_damage_insurance = Orderdetail::where('order_id', $id)->sum('damage_insurance');
        $total_price_and_damage_insurance = $total_price + $total_damage_insurance;


        $orderdetail_date = Orderdetail::where('order_id', $id)->get();

        $date_now = now()->toDateString();
        $count_inex = 0;
        foreach ($orderdetail_date as $item) {
            $pickup_detail = Date::where('order_detail_id', $item->id)
                ->orderBy('created_at', 'desc')
                ->first();
            if ($pickup_detail->pickup_date == $date_now) {
                $count_inex += 1;
            }
        }
        $check_pickip_today = 0;
        if ($orderdetail_date->count() == $count_inex) {
            $check_pickip_today = 1; //หมายความว่า ทั้งหมดทุกรายการ  วันนัดรับ เป็นวันที่ปัจจุนบะนคือวันนี้
        }
        //0คือ ไม่ได้รับชุดวันนี้ 1 คือ รับชุดวันนี้
        return view('employee.confirmorder', compact('orderdetail', 'order_id', 'order', 'date_now', 'total_deposit', 'total_price_and_damage_insurance', 'check_pickip_today'));
    }



    // ยืนยันการเพิ่มออเดอร์ 
    public function confirmordersave(Request $request, $id)
    {
        $customer_fname = $request->input('customer_fname');
        $customer_lname = $request->input('customer_lname');
        $customer_phone = $request->input('customer_phone');
        $payment_status = $request->input('payment_status');
        $order_id = $id;
        $date_now = now()->toDateString();

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
                $create_status->status = 'รอดำเนินการตัด';
                $create_status->save();
                //ตารางorderdetail
                $orderdetail->status_detail = "รอดำเนินการตัด";
                $orderdetail->status_payment = $payment_status;
                $orderdetail->save();

                //ตารางpayment_status
                $create_payment = new Paymentstatus();
                $create_payment->order_detail_id = $orderdetail->id;
                $create_payment->payment_status = $payment_status;
                $create_payment->save();
            } elseif ($orderdetail->type_order == 2) {

                $date = Date::where('order_detail_id', $orderdetail->id)
                    ->orderBy('created_at', 'desc')
                    ->first();
                if ($date->pickup_date == $date_now) {
                    //ตารางorderdetailstatus 
                    $create_status = new Orderdetailstatus();
                    $create_status->order_detail_id = $orderdetail->id;
                    $create_status->status = 'ถูกจอง';
                    $create_status->save();
                    //ตารางorderdetailstatus 
                    $create_status = new Orderdetailstatus();
                    $create_status->order_detail_id = $orderdetail->id;
                    $create_status->status = 'กำลังเช่า';
                    $create_status->save();


                    // อัปเดตตารางdate
                    $date_id = Date::where('order_detail_id', $orderdetail->id)
                        ->orderBy('created_at', 'desc')
                        ->value('id');
                    $update_real = Date::find($date_id);
                    $update_real->actua_pickup_date = now(); //วันที่รับจริงๆ
                    $update_real->save();




                    //ตารางorderdetail
                    $orderdetail->status_detail = "กำลังเช่า";
                    $orderdetail->status_payment = $payment_status;
                    $orderdetail->save();

                    //ตารางpayment_status
                    $create_payment = new Paymentstatus();
                    $create_payment->order_detail_id = $orderdetail->id;
                    $create_payment->payment_status = $payment_status;
                    $create_payment->save();

                    //ตารางreservation 
                    $update_reservation = Reservation::find($orderdetail->reservation_id);
                    $update_reservation->status = 'กำลังเช่า';
                    $update_reservation->save();
                } else {
                    //ตารางorderdetailstatus 
                    $create_status = new Orderdetailstatus();
                    $create_status->order_detail_id = $orderdetail->id;
                    $create_status->status = 'ถูกจอง';
                    $create_status->save();
                    //ตารางorderdetail
                    $orderdetail->status_detail = "ถูกจอง";
                    $orderdetail->status_payment = $payment_status;
                    $orderdetail->save();
                    //ตารางpayment_status
                    $create_payment = new Paymentstatus();
                    $create_payment->order_detail_id = $orderdetail->id;
                    $create_payment->payment_status = $payment_status;
                    $create_payment->save();
                    //ตารางreservation 
                    $update_reservation = Reservation::find($orderdetail->reservation_id);
                    $update_reservation->status = 'ถูกจอง';
                    $update_reservation->save();
                }
            } elseif ($orderdetail->type_order == 3) {
                //เช่าเครื่องประดับ
                $date = Date::where('order_detail_id', $orderdetail->id)
                    ->orderBy('created_at', 'desc')
                    ->first();
                if ($date->pickup_date == $date_now) {
                    //ตารางorderdetailstatus 
                    $create_status = new Orderdetailstatus();
                    $create_status->order_detail_id = $orderdetail->id;
                    $create_status->status = 'ถูกจอง';
                    $create_status->save();
                    //ตารางorderdetailstatus 
                    $create_status = new Orderdetailstatus();
                    $create_status->order_detail_id = $orderdetail->id;
                    $create_status->status = 'กำลังเช่า';
                    $create_status->save();

                    // อัปเดตตารางdate
                    $date_id = Date::where('order_detail_id', $orderdetail->id)
                        ->orderBy('created_at', 'desc')
                        ->value('id');
                    $update_real = Date::find($date_id);
                    $update_real->actua_pickup_date = now(); //วันที่รับจริงๆ
                    $update_real->save();


                    //ตารางorderdetail
                    $orderdetail->status_detail = "กำลังเช่า";
                    $orderdetail->status_payment = $payment_status;
                    $orderdetail->save();
                    //ตารางpayment_status
                    $create_payment = new Paymentstatus();
                    $create_payment->order_detail_id = $orderdetail->id;
                    $create_payment->payment_status = $payment_status;
                    $create_payment->save();
                    //ตารางreservation 
                    $update_reservation = Reservation::find($orderdetail->reservation_id);
                    $update_reservation->status = 'กำลังเช่า';
                    $update_reservation->save();

                    if ($update_reservation->jewelry_id) {
                        $find_re_filter = Reservationfilters::where('reservation_id', $orderdetail->reservation_id)->first();
                        $update_re_filter = Reservationfilters::find($find_re_filter->id);
                        $update_re_filter->status = 'กำลังเช่า';
                        $update_re_filter->save();

                        $update_status_jewelry = Jewelry::find($update_reservation->jewelry_id);
                        $update_status_jewelry->jewelry_status = 'กำลังถูกเช่า';
                        $update_status_jewelry->save();
                    } elseif ($update_reservation->jewelry_set_id) {
                        $find_re_filter = Reservationfilters::where('reservation_id', $orderdetail->reservation_id)->get();
                        foreach ($find_re_filter as $item) {
                            $update_re_filter = Reservationfilters::find($item->id);
                            $update_re_filter->status = 'กำลังเช่า';
                            $update_re_filter->save();
                        }


                        $jew_item_total = Jewelrysetitem::where('jewelry_set_id', $update_reservation->jewelry_set_id)->get();
                        foreach ($jew_item_total as $item) {
                            $update_status_jew = Jewelry::find($item->jewelry_id);
                            $update_status_jew->jewelry_status = 'กำลังถูกเช่า';
                            $update_status_jew->save();
                        }
                    }
                } else {
                    //ตารางorderdetailstatus 
                    $create_status = new Orderdetailstatus();
                    $create_status->order_detail_id = $orderdetail->id;
                    $create_status->status = 'ถูกจอง';
                    $create_status->save();
                    //ตารางorderdetail
                    $orderdetail->status_detail = "ถูกจอง";
                    $orderdetail->status_payment = $payment_status;
                    $orderdetail->save();
                    //ตารางpayment_status
                    $create_payment = new Paymentstatus();
                    $create_payment->order_detail_id = $orderdetail->id;
                    $create_payment->payment_status = $payment_status;
                    $create_payment->save();
                    //ตารางreservation 
                    $update_reservation = Reservation::find($orderdetail->reservation_id);
                    $update_reservation->status = 'ถูกจอง';
                    $update_reservation->save();

                    if ($update_reservation->jewelry_id) {
                        $find_re_filter = Reservationfilters::where('reservation_id', $orderdetail->reservation_id)->first();
                        $update_re_filter = Reservationfilters::find($find_re_filter->id);
                        $update_re_filter->status = 'ถูกจอง';
                        $update_re_filter->save();
                    } elseif ($update_reservation->jewelry_set_id) {
                        $find_re_filter = Reservationfilters::where('reservation_id', $orderdetail->reservation_id)->get();
                        foreach ($find_re_filter as $item) {
                            $update_re_filter = Reservationfilters::find($item->id);
                            $update_re_filter->status = 'ถูกจอง';
                            $update_re_filter->save();
                        }
                    }
                }
            } elseif ($orderdetail->type_order == 4) {
                //เช่าตัด
                //ตารางorderdetailstatus 
                $create_status = new Orderdetailstatus();
                $create_status->order_detail_id = $orderdetail->id;
                $create_status->status = 'รอดำเนินการตัด';
                $create_status->save();
                //ตารางorderdetail
                $orderdetail->status_detail = "รอดำเนินการตัด";
                $orderdetail->status_payment = $payment_status;
                $orderdetail->save();

                //ตารางpayment_status
                $create_payment = new Paymentstatus();
                $create_payment->order_detail_id = $orderdetail->id;
                $create_payment->payment_status = $payment_status;
                $create_payment->save();
            }
        }

        //อัปเดตตารางorder ว่า ออเดอร์นี้ได้รับการยืนยันแล้ว
        $update_order_status = Order::find($order_id);
        $update_order_status->customer_id = $customer_id;
        $update_order_status->order_status = 1;
        $update_order_status->save();





        $total_price_receipt = 0;
        $orderdetail_for_receipt = Orderdetail::where('order_id', $order_id)->get();
        foreach ($orderdetail_for_receipt as $item) {
            $detail = Orderdetail::find($item->id);

            if ($detail->status_payment == 1) {
                $total_price_receipt += $detail->deposit;
            } elseif ($detail->status_payment == 2) {
                $total_price_receipt = $total_price_receipt + $detail->price + $detail->damage_insurance;
            }
        }



        // date_now
        $this_order_detail_only = Orderdetail::where('order_id', $order_id)->first();
        $this_pickup_only = Date::where('order_detail_id', $this_order_detail_only->id)->value('pickup_date');

        if ($this_pickup_only == $date_now) {
            // สร้างใบเสร็จรับเงิน
            $create_receipt = new Receipt();
            $create_receipt->order_id = $order_id;
            $create_receipt->total_price = $total_price_receipt;
            $create_receipt->receipt_type = 2; //1ตอนที่จอง 2ตอนที่มารับชุด 3ตอนที่มาคืน
            $create_receipt->save();
        } else {
            // สร้างใบเสร็จรับเงิน
            $create_receipt = new Receipt();
            $create_receipt->order_id = $order_id;
            $create_receipt->total_price = $total_price_receipt;
            $create_receipt->receipt_type = 1; //1ตอนที่จอง 2ตอนที่มารับชุด 3ตอนที่มาคืน
            $create_receipt->save();
        }







        return redirect()->route('employee.ordertotaldetail', ['id' => $order_id]);
    }
}
