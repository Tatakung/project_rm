@extends('layouts.adminlayout')

@section('content')


    <style>
        .btn-s {
            border-radius: 10px;
        }

        .btn-s:hover {
            background-color: #0056b3;
        }

        .btn-s i {
            margin-right: 2px;
            font-size: 14px;
        }

        .btn-m {
            border-radius: 10px;
        }

        .btn-m:hover {
            background-color: #0056b3;
        }

        .btn-m i {
            margin-right: 2px;
            font-size: 14px;
        }
    </style>



    <div class="container">
        <h1 class="text-start my-4" style="color: #3d3d3d;">
            คิวการจัดเตรียมเครื่องประดับสำหรับลูกค้า
        </h1>
        <p>รายการนี้แสดงลำดับคิวการจัดเตรียมเครื่องประดับสำหรับลูกค้า โดยเรียงตามวันที่ลูกค้าจะมารับ</p>
        <div class="row ">
            <div class="col-md-12" style="text-align: right ; ">
               

                <form action="{{route('showpickupqueuejewelryfilter')}}" method="GET">
                    @csrf

                    
                    <div class="filter-buttons mb-3">
                        <button class="btn" type="submit" name="filter_click" value="today"
                            @if ($filer == 'today') style="border: 1px solid #ccc;background-color: rgb(238, 77, 45) ; color: #ffffff ;"
                        @else
                        style="border: 1px solid #ccc;" @endif>เฉพาะวันนี้</button>
                        <button class="btn" type="submit" name="filter_click" value="total"
                            @if ($filer == 'total') style="border: 1px solid #ccc;background-color: rgb(238, 77, 45) ; color: #ffffff ;"
                        @else
                        style="border: 1px solid #ccc;" @endif>ทั้งหมด</button>

                    </div>
                </form>


            </div>
        </div>




        @if ($reservations->count() > 0)
            <table class="table shadow-sm" style="width: 100%; background-color: #ffffff; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f2f2f2;">
                        <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">วันที่นัดรับ</th>
                        <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">เครื่องประดับ</th>
                        <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">ชื่อลูกค้า</th>
                        <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">สถานะเครื่องประดับ</th>
                        <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservations as $index => $reservation)
                        @if ($reservation->re_one_many_details->first() && $reservation->re_one_many_details->first()->type_order == 3)
                            @php
                                $orderdetail = App\Models\Orderdetail::where(
                                    'reservation_id',
                                    $reservation->id,
                                )->first();
                                $customer_id = App\Models\Order::where('id', $orderdetail->order_id)->value(
                                    'customer_id',
                                );
                                $customer = App\Models\Customer::find($customer_id);

                            @endphp

                            <tr style="border-bottom: 1px solid #e6e6e6;">
                               
                                <td style="padding: 16px;">
                                    {{ \Carbon\Carbon::parse($reservation->start_date)->locale('th')->isoFormat('D MMM') }}
                                    {{ \Carbon\Carbon::parse($reservation->start_date)->year + 543 }}
                                    <p id="showday{{ $reservation->id }}" class="mt-2" style="color: #8d1e1e;"></p>

                                    <script>
                                        var now = new Date();
                                        now.setHours(0, 0, 0, 0); // ตั้งเวลาให้เป็น 00:00:00
                                        var start_date = new Date("{{ $reservation->start_date }}");
                                        start_date.setHours(0, 0, 0, 0);

                                        console.log(start_date);
                                        var day = start_date - now;
                                        var totalday = Math.ceil(day / (1000 * 60 * 60 * 24));

                                        if (totalday > 0) {
                                            document.getElementById('showday{{ $reservation->id }}').innerHTML = "เหลืออีก " + totalday + ' วัน ';
                                        } else if (totalday == 0) {
                                            document.getElementById('showday{{ $reservation->id }}').innerHTML = "มารับวันนี้ ";
                                        } else {
                                            document.getElementById('showday{{ $reservation->id }}').innerHTML = "เลยวันนัดรับ " + Math.abs(totalday) +
                                                ' วัน ';
                                        }
                                    </script>
                                </td>


                                <td style="padding: 16px;">
                                    @if ($reservation->jewelry_id)
                                        {{ $reservation->resermanytoonejew->jewelry_m_o_typejew->type_jewelry_name }}
                                        {{ $reservation->resermanytoonejew->jewelry_m_o_typejew->specific_letter }}{{ $reservation->resermanytoonejew->jewelry_code }}
                                    @elseif($reservation->jewelry_set_id)
                                        เซต{{ $reservation->resermanytoonejewset->set_name }}
                                    @endif
                                </td>

                                <td style="padding: 16px;">
                                    คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }}
                                </td>


                                <td style="width: 200px;">
                                    @if ($reservation->jewelry_id)
                                        @php
                                            $list_check = [];
                                            // เอาแค่ jew_id ก่อน
                                            $check_unique_jew_id = App\Models\Reservation::where('status_completed', 0)
                                                ->where('status', 'ถูกจอง')
                                                ->where('jewelry_id', $reservation->jewelry_id)
                                                ->get();
                                            foreach ($check_unique_jew_id as $item) {
                                                $list_check[] = $item->id;
                                            }
                                            // เอาแค่ jew_set_id
                                            $set_in_re = App\Models\Reservation::where('status_completed', 0)
                                                ->where('status', 'ถูกจอง')
                                                ->whereNotNull('jewelry_set_id')
                                                ->get();
                                            foreach ($set_in_re as $value) {
                                                $item_for_jew_set = App\Models\Jewelrysetitem::where(
                                                    'jewelry_set_id',
                                                    $value->jewelry_set_id,
                                                )->get();
                                                foreach ($item_for_jew_set as $item) {
                                                    if ($reservation->jewelry_id == $item->jewelry_id) {
                                                        $list_check[] = $value->id;
                                                    }
                                                }
                                            }
                                            $sort_queue = App\Models\Reservation::whereIn('id', $list_check)
                                                ->orderByRaw("STR_TO_DATE(start_date,'%Y-%m-%d') asc")
                                                ->first();
                                        @endphp
                                        @if ($sort_queue->id == $reservation->id)
                                            
                                            @if($reservation->resermanytoonejew->jewelry_status == 'กำลังถูกเช่า')
                                            กำลังถูกเช่าโดยลูกค้าท่านอื่น
                                            @else
                                            {{ $reservation->resermanytoonejew->jewelry_status }}
                                            @endif
                                        @else
                                            รอคิว
                                        @endif
                                    @elseif($reservation->jewelry_set_id)
                                        @php
                                            $list_set = [];
                                            // แค่jewelry_set_idในตาราง reservation
                                            $jewwelry_set_id_in_reservation = App\Models\Reservation::where('status_completed',0,
                                            )
                                                ->where('status', 'ถูกจอง')
                                                ->where('jewelry_set_id', $reservation->jewelry_set_id)
                                                ->get();
                                            foreach ($jewwelry_set_id_in_reservation as $key => $value) {
                                                $list_set[] = $value->id;
                                            }

                                            // ส่วนjew_id
                                            $jew_set_item = App\Models\Jewelrysetitem::where(
                                                'jewelry_set_id',
                                                $reservation->jewelry_set_id,
                                            )->get();
                                            foreach ($jew_set_item as $key => $item) {
                                                $check_jew_id_in_re = App\Models\Reservation::where(
                                                    'status_completed',
                                                    0,
                                                )
                                                    ->where('status', 'ถูกจอง')
                                                    ->where('jewelry_id', $item->jewelry_id)
                                                    ->get();
                                                if ($check_jew_id_in_re->isNotEmpty()) {
                                                    foreach ($check_jew_id_in_re as $value) {
                                                        $list_set[] = $value->id;
                                                    }
                                                }
                                            }
                                            $sort_queue = App\Models\Reservation::whereIn('id', $list_set)
                                                ->orderByRaw("STR_TO_DATE(start_date,'%Y-%m-%d') asc")
                                                ->first();
                                        @endphp
                                        
                                        @if ($reservation->id == $sort_queue->id)
                                            @php
                                                $item_for_set = App\Models\Jewelrysetitem::where(
                                                    'jewelry_set_id',
                                                    $reservation->jewelry_set_id,
                                                )->get();
                                                $is_ready = true;
                                                foreach ($item_for_set as $key => $value) {
                                                    if ($value->jewitem_m_to_o_jew->jewelry_status != 'พร้อมให้เช่า') {
                                                        $is_ready = false;
                                                        break;
                                                    }
                                                }
                                                // dd($is_ready) ; 
                                            @endphp

                                            @if($is_ready)
                                            พร้อมให้เช่า
                                            @else
                                            ยังไม่พร้อมให้เช่า
                                            @endif
                                        @else
                                            รอคิว
                                        @endif
                                    @endif




                                </td>

                                <td style="padding: 16px;">
                                    {{-- <a href="{{ route('employee.ordertotaldetailshow', ['id' => $orderdetail->id]) }}"
                                        class="btn btn-s" style="background-color:#DADAE3;">
                                        ดูรายละเอียด
                                    </a> --}}

                                    <a href="{{ route('employee.ordertotaldetail', ['id' => $orderdetail->order_id]) }}"
                                        class="btn btn-sm" style="background-color:#DADAE3;">
                                        ดูรายละเอียด
                                    </a> 




                                    <a href="{{route('postponeroutejewelry',['id' => $reservation->id])}}" class="btn btn-m" style="background-color:#BACEE6 ;">
                                        เลื่อนวัน
                                    </a>

                                </td>
                            </tr>
                        @endif
                    @endforeach



                </tbody>
            </table>
        @else
            <p style="text-align: center ; ">ไม่มีรายการแสดงผล</p>
        @endif
    </div>
@endsection
