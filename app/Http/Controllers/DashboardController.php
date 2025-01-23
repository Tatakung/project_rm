<?php

namespace App\Http\Controllers;

use App\Models\Orderdetail;
use App\Models\Orderdetailstatus;
use App\Models\Reservation;
use App\Models\Reservationfilters;
use App\Models\Typedress;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function dashboardcutdress()
    {
        // ดึงข้อมูลประเภทชุดจากฐานข้อมูล
        $labels = Orderdetail::where('type_order', 1)
            ->pluck('type_dress')
            ->toArray();

        $count = array_count_values($labels);
        $labels = array_unique($labels);
        $labels = array_values($labels);
        $data = array_values($count);

        return view('employeecutdress.dashboardcut', compact('labels', 'data'));
    }

    public function cancelorderrent(Request $request ,$id){
        $orderdetail = Orderdetail::find($id) ; 
        $type_cancel = $request->input('cancelType') ; 
        if($type_cancel == 'store'){


            if($orderdetail->type_order == 2 ){
            }

            elseif($orderdetail->type_order == 3 ){
                $update_orderdetail = Orderdetail::find($id) ; 
                $update_orderdetail->status_detail = 'ยกเลิกโดยทางร้าน' ; 
                $update_orderdetail->save() ; 
                $status = new Orderdetailstatus() ; 
                $status->order_detail_id = $id ; 
                $status->status = 'ยกเลิกโดยทางร้าน' ; 
                $status->save() ; 
                $reservation = Reservation::find($update_orderdetail->reservation_id) ; 
                $reservation->status = 'ยกเลิกโดยทางร้าน' ; 
                $reservation->status_completed = 1 ; 
                $reservation->save() ; 

                $reservation_filter = Reservationfilters::where('reservation_id',$reservation->id)->get() ; 
                foreach($reservation_filter as $item){
                    $update_filter = Reservationfilters::find($item->id) ; 
                    $update_filter->status = 'ยกเลิกโดยทางร้าน' ; 
                    $update_filter->status_completed = 1 ; 
                    $update_filter->save() ; 
                }
            }

        }
        elseif($type_cancel == 'customer'){

            if($orderdetail->type_order == 2){

            }

            elseif($orderdetail->type_order == 3 ){
                $update_orderdetail = Orderdetail::find($id) ; 
                $update_orderdetail->status_detail = 'ยกเลิกโดยลูกค้า' ; 
                $update_orderdetail->save() ; 
                $status = new Orderdetailstatus() ; 
                $status->order_detail_id = $id ; 
                $status->status = 'ยกเลิกโดยลูกค้า' ; 
                $status->save() ; 
                $reservation = Reservation::find($update_orderdetail->reservation_id) ; 
                $reservation->status = 'ยกเลิกโดยลูกค้า' ; 
                $reservation->status_completed = 1 ; 
                $reservation->save() ; 

                $reservation_filter = Reservationfilters::where('reservation_id',$reservation->id)->get() ; 
                foreach($reservation_filter as $item){
                    $update_filter = Reservationfilters::find($item->id) ; 
                    $update_filter->status = 'ยกเลิกโดยลูกค้า' ; 
                    $update_filter->status_completed = 1 ; 
                    $update_filter->save() ; 
                }
            }


            
            elseif($orderdetail->type_order == 4 ){
            }
        }
        return redirect()->back()->with('success','ยกเลิกรายการสำเร็จ') ; 
    }






}
