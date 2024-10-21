@extends('layouts.adminlayout')
@section('content')
    <style>
        .status-timeline {
            padding: 20px 0;
        }

        .status-step {
            z-index: 1;
            position: relative;
        }

        .status-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            color: #6c757d;
        }

        .status-icon.active {
            background-color: #0c7ab6;
            color: #000;
            /* เปลี่ยนสีตัวอักษรเป็นสีดำเพื่อให้เห็นชัดบนพื้นสีเหลือง */
        }

        .status-line {
            flex-grow: 1;
            height: 3px;
            background-color: #e9ecef;
            position: relative;
            top: 25px;
            z-index: 0;
        }

        .status-line::after {
            content: '';
            position: absolute;
            right: -10px;
            /* Adjust this value to align the arrow */
            top: 50%;
            transform: translateY(-50%);
            border-left: 10px solid #e9ecef;
            /* Arrow color */
            border-top: 5px solid transparent;
            border-bottom: 5px solid transparent;
        }


        .status-step p {
            margin-bottom: 0;
        }

        .status-step small {
            color: #6c757d;
        }
    </style>


    <div class="modal fade" id="showfail" role="dialog" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog" role="document">
            <div class="modal-content custom-modal-content"
                style="max-width: 300px; height: 50px; width: 100%; margin: auto; background-color: #EE4E4E; border: 2px solid #EE4E4E; ">
                <div class="modal-body" style="padding: 5px; display: flex; align-items: center; justify-content: center;">
                    <p style="margin: 0; color: #ffffff;">{{ session('fail') }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="showsuccess" role="dialog" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog" role="document">
            <div class="modal-content custom-modal-content"
                style="max-width: 400px; height: 50px; width: 100%; margin: auto; background-color: #EAD8C0; border: 2px solid #EAD8C0; ">
                <div class="modal-body shadow"
                    style="padding: 10px; display: flex; align-items: center; justify-content: center;">
                    <p style="margin: 0; color: #000000;">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    </div>





    <script>
        @if (session('fail'))
            setTimeout(function() {
                $('#showfail').modal('show');
            }, 500);
        @endif
    </script>










    <script>
        @if (session('success'))
            setTimeout(function() {
                $('#showsuccess').modal('show');
            }, 500);
        @endif
    </script>


















    <ol class="breadcrumb" style="background-color: transparent; ">
        <li class="breadcrumb-item"><a href="">หน้าแรก</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employee.ordertotal') }}">รายการออเดอร์ทั้งหมด</a></li>
        <li class="breadcrumb-item"><a
                href="{{ route('employee.ordertotaldetail', ['id' => $orderdetail->order_id]) }}">รายละเอียดออเดอร์ที่
                {{ $orderdetail->order_id }}</a></li>
        <li class="breadcrumb-item active">{{ $orderdetail->title_name }}</li>
    </ol>

    <div class="container mt-4">
        @php
            $dress = App\Models\Dress::find($orderdetail->dress_id);
            $typename = App\Models\Typedress::where('id', $dress->type_dress_id)->value('type_dress_name');
        @endphp


        {{-- เอาไว้นับเงื่อนไขของปุ่มว่ามีชุดที่ปรับแก้ไขไหมนะ  --}}
        @php
            $check_button_updatestatusadjust = false;
            foreach ($dress_mea_adjust_button as $dress_mea_adjust_button) {
                $dress_mea = App\Models\Dressmea::where('id', $dress_mea_adjust_button->dressmea_id)->first();
                if ($dress_mea_adjust_button->new_size != $dress_mea->current_mea) {
                    $check_button_updatestatusadjust = true; //แปลว่าต้องแก้
                }
            }
        @endphp


        {{-- เช็คว่ามันอยู่คิวไหน --}}
        @php

            $reservation_now = App\Models\Reservation::where('status_completed', 0)
                ->where('dress_id', $orderdetail->dress_id)
                ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                ->first();

            // เช็คว่าเช่าทั้งชุด หรือ เช่าแค่เสื้อ หรือเช่าแค่ผ้าถุง
            if ($orderdetail->skirtitems_id) {
                //  ตรวจสอบเฉพาะผ้าถุงก่อน
                $status_skirt = App\Models\Reservation::where('status_completed', 0)
                    ->where('dress_id', $orderdetail->dress_id)
                    ->where('skirtitems_id', $orderdetail->skirtitems_id)
                    ->whereNull('shirtitems_id')
                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                    ->get();

                // ตรวจอสอบเช่าเฉพาะทั้งชุด แต่ห้ามเอาเช่าเฉพาะเสื้อมาเกี่ยวข้อง เพราะอย่าไปนับคิวด้วย
                $status_total_dress = App\Models\Reservation::where('status_completed', 0)
                    ->where('dress_id', $orderdetail->dress_id)
                    ->whereNull('shirtitems_id')
                    ->whereNull('skirtitems_id')
                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                    ->get();
                $list__for__one = [];

                foreach ($status_skirt as $item) {
                    $list__for__one[] = $item->id;
                }
                foreach ($status_total_dress as $item) {
                    $list__for__one[] = $item->id;
                }

                $reservation_now = App\Models\reservation::whereIn('id', $list__for__one)
                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                    ->first();
                if ($reservation_now) {
                    if ($reservation_now->id == $orderdetail->reservation_id) {
                        $check_open_button = true;
                    } elseif ($reservation_now->id != $orderdetail->reservation_id) {
                        $check_open_button = false;
                    }
                }
                // เพื่อที่เข้ามาดูประวะัติการเช่าได้
                else {
                    $check_open_button = true;
                }
            } elseif ($orderdetail->shirtitems_id) {
                //  ตรวจสอบเฉพาะเสื้อก่อน
                $status_shirt = App\Models\Reservation::where('status_completed', 0)
                    ->where('dress_id', $orderdetail->dress_id)
                    ->where('shirtitems_id', $orderdetail->shirtitems_id)
                    ->whereNull('skirtitems_id')
                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                    ->get();
                // ตรวจอสอบเช่าเฉพาะทั้งชุด แต่ห้ามเอาเช่าเฉพาะผ้าถุงมาเกี่ยวข้อง เพราะอย่าไปนับคิวด้วย
                $status_total_dress = App\Models\Reservation::where('status_completed', 0)
                    ->where('dress_id', $orderdetail->dress_id)
                    ->whereNull('shirtitems_id')
                    ->whereNull('skirtitems_id')
                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                    ->get();
                $list__for__one = [];

                foreach ($status_shirt as $item) {
                    $list__for__one[] = $item->id;
                }
                foreach ($status_total_dress as $item) {
                    $list__for__one[] = $item->id;
                }
                $reservation_now = App\Models\reservation::whereIn('id', $list__for__one)
                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                    ->first();

                if ($reservation_now) {
                    if ($reservation_now->id == $orderdetail->reservation_id) {
                        $check_open_button = true;
                    } elseif ($reservation_now->id != $orderdetail->reservation_id) {
                        $check_open_button = false;
                    }
                }
                // เพื่อที่เข้ามาดูประวะัติการเช่าได้
                else {
                    $check_open_button = true;
                }
            } else {
                $reservation_now = App\Models\Reservation::where('status_completed', 0)
                    ->where('dress_id', $orderdetail->dress_id)
                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                    ->first();
                if ($reservation_now) {
                    if ($reservation_now->id == $orderdetail->reservation_id) {
                        $check_open_button = true;
                    } elseif ($reservation_now->id != $orderdetail->reservation_id) {
                        $check_open_button = false;
                    }
                }
                // เพื่อที่เข้ามาดูประวะัติการเช่าได้
                else {
                    $check_open_button = true;
                }
            }

        @endphp

















        {{-- @php
            $check_open_button = true ; 
        @endphp --}}

        {{-- <p>คิวแรก : {{$reservation_now->id}}</p>
        <p>reservation_id : {{$orderdetail->reservation_id}}</p> --}}


        {{-- เอาไว้เช็คว่่าถ้า reservation_id มีคอลัมน์ status_completed เป็น 1 อะ ไอ้พวกแจ้งเตือนต่างๆไม่ต้องให้มันแสดงผลขึ้นมา เพราะมันเป็นประวัติไปแล้ว ไม่จำเป็นต้องแจ้งเตือน --}}
        @php
            $check_reser_status_for_his = App\Models\Reservation::where('id', $orderdetail->reservation_id)->value(
                'status_completed',
            );
        @endphp

        @if ($check_reser_status_for_his != 1)
            @if ($check_open_button == false)
                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="alert alert-danger" role="alert">
                            @if ($reservation_now->status == 'ถูกจอง' || $reservation_now->status == 'กำลังเช่า')
                                <strong>แจ้งเตือน:</strong> ชุดนี้<span> {{ $reservation_now->status }} </span>
                                โดยลูกค้าท่านอื่น ไม่สามารถดำเนินการในรายการนี้ได้
                            @else
                                <strong>แจ้งเตือน:</strong> ชุดนี้<span> {{ $reservation_now->status }} </span>
                                กรุณารอจนกว่าจะพร้อมใช้งาน
                            @endif

                            @if ($reservation_now->status == 'ถูกจอง' || $reservation_now->status == 'กำลังเช่า')
                                <hr>
                                <p class="mb-0">
                                    @php
                                        $find_order_detail_now = App\Models\Orderdetail::where(
                                            'reservation_id',
                                            $reservation_now->id,
                                        )->first();
                                        $find_order_detail_id = App\Models\Orderdetail::find(
                                            $find_order_detail_now->id,
                                        );
                                        $customer_id_re = App\Models\order::where(
                                            'id',
                                            $find_order_detail_id->order_id,
                                        )->value('customer_id');
                                        $customer_fname_re = App\Models\Customer::where('id', $customer_id_re)->value(
                                            'customer_fname',
                                        );
                                        $customer_lname_re = App\Models\Customer::where('id', $customer_id_re)->value(
                                            'customer_lname',
                                        );

                                    @endphp
                                    <strong>รายละเอียดการเช่าปัจจุบัน:</strong> <a
                                        href="{{ route('employee.ordertotaldetailshow', ['id' => $find_order_detail_now->id]) }}">ดูรายละเอียด</a><br>
                                    &bull; ลูกค้า: คุณ{{ $customer_fname_re }} {{ $customer_lname_re }}<br>
                                    &bull; วันที่เช่า:
                                    {{ \Carbon\carbon::parse($reservation_now->start_date)->locale('th')->isoFormat('D MMM') }}
                                    {{ \Carbon\carbon::parse($reservation_now->start_date)->year + 543 }}
                                    <br>
                                    &bull; กำหนดคืน:
                                    {{ \Carbon\carbon::parse($reservation_now->end_date)->locale('th')->isoFormat('D MMM') }}
                                    {{ \Carbon\carbon::parse($reservation_now->end_date)->year + 543 }}
                                </p>
                            @endif

                        </div>
                    </div>
                </div>
            @endif
        @endif



        <h4 class="mt-2"><strong>รายการ :
                @if ($orderdetail->shirtitems_id)
                    เช่า{{ $typename }} {{ $dress->dress_code_new }}{{ $dress->dress_code }} (เสื้อ)
                @elseif($orderdetail->skirtitems_id)
                    เช่า{{ $typename }} {{ $dress->dress_code_new }}{{ $dress->dress_code }}
                    (ผ้าถุง)
                @else
                    เช่า{{ $typename }} {{ $dress->dress_code_new }}{{ $dress->dress_code }}
                    (ทั้งชุด)
                @endif
            </strong>
        </h4>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="card-title">สถานะการเช่า</h5>
                            </div>
                            {{-- <div class="col-md-6" style="text-align: right ;">
                                <button type="button" class="btn btn-primary">อัพเดตสถานะการเช่า</button>
                            </div> --}}



                            @php
                                // $now_today = now()->setTime(0, 0)->format('Y-m-d');
                                $now_today = now()->format('Y-m-d');



                                // dd($dateeee->pickup_date) ;
                                // dd($now_today) ; 

                                // dd($now_today) ; 
                            @endphp

                            {{-- <div
                                @if ($now_today == $dateeee->pickup_date) style="display: block ; "
                            @else
                            style="display: none ; " @endif>
                            </div> --}}


                            <div class="col-md-6 text-right"
                            @if ($orderdetail->status_detail == 'ถูกจอง' && $check_button_updatestatusadjust == false && $check_open_button == true ) 
                                style="display: block ; "
                            @else
                                style="display: none ; " 
                            @endif
                            
                            
                            >
                            <button class="btn" style="background: #3406dc; color: #ffffff;" data-toggle="modal"
                                data-target="#updatestatus">อัพเดตสถานะการเช่าddsssd</button>
                        </div>




                            <div class="col-md-6 text-right"
                                @if ($orderdetail->status_detail == 'กำลังเช่า') style="display: block ; "@else style="display: none ; " @endif>
                                <button class="btn" style="background: #3406dc; color: #ffffff;" data-toggle="modal"
                                    data-target="#updatestatus_return">อัพเดตการรับชุดคืน</button>
                            </div>





                        </div>
                        <div class="status-timeline d-flex justify-content-between position-relative">



                            @php
                                $list_status = [];
                                foreach ($orderdetailstatus as $index => $status) {
                                    $list_status[] = $status->status;
                                }
                            @endphp



                            <div class="status-step text-center">
                                <div class="status-icon @if (in_array('ถูกจอง', $list_status)) active @endif">
                                    <i class="fas fa-check"></i>
                                </div>
                                <p>ถูกจอง</p>
                                <small>
                                    <p>
                                        @php
                                            $created_at = App\Models\Orderdetailstatus::where(
                                                'order_detail_id',
                                                $orderdetail->id,
                                            )
                                                ->where('status', 'ถูกจอง')
                                                ->first();
                                            if ($created_at) {
                                                $text_date = Carbon\Carbon::parse($created_at->created_at)
                                                    // ->addHours(7)
                                                    ->format('d/m/Y H:i');
                                            } else {
                                                $text_date = 'รอดำเนินการ';
                                            }
                                        @endphp
                                        {{ $text_date }}
                                    </p>
                                </small>
                            </div>


                            <div class="status-line "></div>



                            <div class="status-step text-center">
                                <div class="status-icon @if (in_array('กำลังเช่า', $list_status)) active @endif">
                                    <i class="fas fa-check"></i>
                                </div>
                                <p>กำลังเช่า</p>
                                <small>
                                    <p>
                                        @php
                                            $created_at = App\Models\Orderdetailstatus::where(
                                                'order_detail_id',
                                                $orderdetail->id,
                                            )
                                                ->where('status', 'กำลังเช่า')
                                                ->first();
                                            if ($created_at) {
                                                $text_date = Carbon\Carbon::parse($created_at->created_at)
                                                    // ->addHours(7)
                                                    ->format('d/m/Y H:i');
                                            } else {
                                                $text_date = 'รอดำเนินการ';
                                            }
                                        @endphp
                                        {{ $text_date }}
                                    </p>
                                </small>
                            </div>


                            <div class="status-line "></div>



                            <div class="status-step text-center">
                                <div class="status-icon @if (in_array('คืนชุดแล้ว', $list_status)) active @endif">
                                    <i class="fas fa-check"></i>
                                </div>
                                <p>คืนชุดแล้ว</p>
                                <small>
                                    <p>
                                        @php
                                            $created_at = App\Models\Orderdetailstatus::where(
                                                'order_detail_id',
                                                $orderdetail->id,
                                            )
                                                ->where('status', 'คืนชุดแล้ว')
                                                ->first();
                                            if ($created_at) {
                                                $text_date = Carbon\Carbon::parse($created_at->created_at)
                                                    // ->addHours(7)
                                                    ->format('d/m/Y H:i');
                                            } else {
                                                $text_date = 'รอดำเนินการ';
                                            }
                                        @endphp
                                        {{ $text_date }}
                                    </p>
                                </small>
                            </div>




                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3 d-flex align-items-stretch">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-4">
                                <img src="{{ asset('storage/' . $dressimage->dress_image) }}" alt="" width="154px;"
                                    height="auto">
                            </div>
                            <div class="col-md-8">
                                <h5>ข้อมุลชุด</h5>
                                <p>ประเภทชุด : {{ $typename }}</p>
                                <p>หมายเลขชุด : {{ $dress->dress_code_new }}{{ $dress->dress_code }}</p>
                                {{-- <p>รายละเอียด : {{ $dress->dress_description }}</p> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title">ข้อมูลการเช่า</h5>
                        @php
                            $customer_id = App\Models\Order::where('id', $orderdetail->order_id)->value('customer_id');
                            $customer = App\Models\Customer::find($customer_id);
                        @endphp
                        <p><span class="bi bi-person"></span> ชื่อผู้เช่า : คุณ{{ $customer->customer_fname }}
                            {{ $customer->customer_lname }}</p>


                        @php
                            $Date = App\Models\Date::where('order_detail_id', $orderdetail->id)
                                ->orderBy('created_at', 'desc')
                                ->first();
                        @endphp

                        <p><i class="bi bi-calendar"></i> วันที่นัดรับ - นัดคืน :
                            {{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($Date->pickup_date)->year + 543 }}
                            -
                            {{ \Carbon\Carbon::parse($Date->return_date)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($Date->return_date)->year + 543 }}

                            {{-- <span
                                @if ($orderdetail->status_detail == 'ถูกจอง') style="display: block ; "
                            @else
                            style="display: none ; " @endif>
                            <a
                                    href="{{ route('employee.ordertotaldetailpostpone', ['id' => $orderdetail->id]) }}">เลื่อนวัน</a></span> --}}
                        </p>



                        <p><i class="bi bi-currency-dollar"></i> ราคาเช่า : {{ number_format($orderdetail->price, 2) }} บาท
                        </p>
                        <p><i class="bi bi-currency-dollar"></i> เงินมัดจำ : {{ number_format($orderdetail->deposit, 2) }}
                            บาท</p>
                        <p><i class="bi bi-shield-check"></i> ประกันค่าเสียหาย :
                            {{ number_format($orderdetail->damage_insurance, 2) }} บาท</p>
                        <p><i class="bi bi-check-circle"></i> สถานะ : @if ($orderdetail->status_payment == 1)
                                ชำระเงินมัดจำแล้ว
                            @elseif($orderdetail->status_payment == 2)
                                ชำระเงินเต็มจำนวนแล้ว
                            @endif
                        </p>
                        @php
                            $user_id = App\Models\Order::where('id', $orderdetail->order_id)->value('user_id');
                            $user = App\Models\User::find($user_id);
                        @endphp
                        <p><span class="bi bi-person"></span> พนักงานผู้รับออเดอร์ : คุณ{{ $user->name }}
                            {{ $user->lname }}</p>
                    </div>
                </div>
            </div>
        </div>



        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="card-title">รายการปรับแก้ไขชุด (หน่วยเป็นนิ้ว)</h5>
                            </div>
                        </div>

                        <table class="table mt-3">
                            <thead>
                                <tr>
                                    <th scope="col">รายการ</th>
                                    <th scope="col">
                                        @if ($check_button_updatestatusadjust == true)
                                            ขนาดเดิม
                                        @elseif($check_button_updatestatusadjust == false)
                                            ขนาด
                                        @endif
                                    </th>
                                    <th scope="col">
                                        @if ($check_button_updatestatusadjust == true)
                                            ขนาดที่ปรับแก้
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dress_mea_adjust as $item)
                                    <tr>
                                        @php
                                            $dress_mea = App\Models\Dressmea::where('id', $item->dressmea_id)->first();
                                        @endphp
                                        <td>{{ $dress_mea->mea_dress_name }}</td>
                                        <td>{{ $dress_mea->current_mea }}</td>

                                        @if ($check_button_updatestatusadjust == true)
                                            <td>
                                                @if ($dress_mea->current_mea != $item->new_size)
                                                    <span style="color: red;">ปรับแก้:จาก{{ $dress_mea->current_mea }}<i
                                                            class="bi bi-arrow-right"></i>{{ $item->new_size }}นิ้ว</span>
                                                @else
                                                    ไม่ต้องปรับแก้ขนาด
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="col-md-12"
                            @if ($check_reser_status_for_his != 1) @if ($check_button_updatestatusadjust == true && $check_open_button == true) 
                                style="display: block; text-align: right ;" 
                            @else
                                style="display: none ; text-align: right ; " @endif
                        @else style="display: none ; text-align: right ; " @endif
                            >
                            <button class="btn btn-success" data-toggle='modal' data-target="#updatestatusadjust"
                                type="button">ปรับแก้ไขนาดสำเร็จ</button>
                        </div>

                        @if ($his_dress_adjust->count() > 0)
                            <p><strong>ประวัติการปรับแก้ขนาด</strong></p>
                            <p style="margin-left: 10px; font-size: 14px;">พนักงานที่ทำการปรับแก้ : คุณผกาสินี ชัยเลิศ</p>
                            @foreach ($his_dress_adjust as $item)
                                <li>{{ $item->name }} ปรับจาก {{ $item->old_size }} เป็น {{ $item->edit_new_size }}
                                </li>
                            @endforeach
                        @endif
                    </div>

                </div>

            </div>




        </div>







        <div class="row mt-3 d-flex align-items-stretch" id="div_show_net">
            <div class="col-md-12"
                @if ($orderdetail->status_detail == 'คืนชุดแล้ว') style="display: block ; "
             @else
              style="display: none ; " @endif>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-file-earmark-text"></i> สรุปข้อมูลการเช่าชุด
                        </h5>

                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>วันที่รับชุดจริง:</strong>
                                    {{ \Carbon\Carbon::parse($Date->actua_pickup_date)->locale('th')->isoFormat('D MMM') }}
                                    {{ \Carbon\Carbon::parse($Date->actua_pickup_date)->year + 543 }}
                                </p>
                                <p><strong>วันที่คืนชุดจริง:</strong>
                                    {{ \Carbon\Carbon::parse($Date->actua_return_date)->locale('th')->isoFormat('D MMM') }}
                                    {{ \Carbon\Carbon::parse($Date->actua_return_date)->year + 543 }}
                                </p>
                                <p><strong>จำนวนวันที่เช่าทั้งหมด:</strong><span id="total_day_real"> </span></p>
                                <script>
                                    var total_day_real = document.getElementById('total_day_real');
                                    var day_actua_pickup_date = new Date('{{ $Date->actua_pickup_date }}');
                                    day_actua_pickup_date.setHours(0, 0, 0, 0);

                                    var day_actua_return_date = new Date('{{ $Date->actua_return_date }}');
                                    day_actua_return_date.setHours(0, 0, 0, 0);

                                    var total_actua_pickup_date_return_date = Math.ceil((day_actua_return_date - day_actua_pickup_date) / (1000 * 60 *
                                        60 * 24));
                                    total_day_real.innerHTML = ' ' + total_actua_pickup_date_return_date + ' วัน';
                                </script>









                            </div>
                            <div class="col-md-6">
                                <p><strong>รายได้ค่าเช่าชุด:</strong> {{ number_format($orderdetail->price, 2) }} บาท</p>
                                <p><strong>เงินประกัน:</strong> {{ number_format($orderdetail->deposit, 2) }} บาท</p>

                                @if ($additional->count() > 0)
                                    @foreach ($additional as $item)
                                        @if ($item->charge_type == 1)
                                            <p><strong>รายได้จากการหักเงินประกัน:</strong>
                                                {{ number_format($item->amount, 2) }} บาท</p>
                                        @elseif($item->charge_type == 2)
                                            <p><strong>รายได้จากการคืนชุดล่าช้า:</strong>
                                                {{ number_format($item->amount, 2) }}
                                                บาท</p>
                                        @elseif($item->charge_type == 3)
                                            <p><strong>รายได้จากค่าธรรมเนียมขยายระยะเวลาเช่า:</strong>
                                                {{ number_format($item->amount, 2) }} บาท</p>
                                        @endif
                                    @endforeach
                                @else
                                    <p><strong>รายได้จากการหักเงินประกัน:</strong> 0.00 บาท</p>
                                @endif
                            </div>
                        </div>

                        {{-- <p><strong>หมายเหตุ:</strong> ไม่มีความเสียหายหรือการปรับแต่งเพิ่มเติม เงินประกันคืนเต็มจำนวน</p> --}}
                    </div>
                </div>
            </div>
        </div>

















    </div>















    </div>


    {{-- modalปรับแก้ไขชุดสำเร็จ   --}}
    <div class="modal fade" id="updatestatusadjust" tabindex="-1" role="dialog"
        aria-labelledby="updatestatusadjustLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('employee.actionupdatestatusadjustdress', ['id' => $orderdetail->id]) }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="dress_id" value="{{ $orderdetail->dress_id }}">
                    <input type="hidden" name="shirtitems_id" value="{{ $orderdetail->shirtitems_id }}">
                    <input type="hidden" name="skirtitems_id" value="{{ $orderdetail->skirtitems_id }}">
                    <input type="hidden" name="order_detail_id" value="{{ $orderdetail->id }}">
                    @foreach ($dress_mea_adjust_modal as $index => $dress_mea_adjust_modal)
                        <input type="hidden" name="dress_adjustment_[]" value="{{ $dress_mea_adjust_modal->id }}">
                        <input type="hidden" name="dressmea_id_[]" value="{{ $dress_mea_adjust_modal->dressmea_id }}">
                        <input type="hidden" name="new_size_[]" value="{{ $dress_mea_adjust_modal->new_size }}">
                    @endforeach

                    <div class="modal-header">
                        <h5 class="modal-title" id="adjustmentModalLabel">การปรับแก้ไขขนาดชุด</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <p>คุณได้ทำการปรับแก้ไขขนาดชุดสำเร็จแล้ว!</p>
                        <p>รายละเอียดการปรับแก้:</p>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">รายการ</th>
                                    <th scope="col">ขนาดเดิม</th>
                                    <th scope="col">ขนาดที่ปรับแก้</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dress_mea_adjust_modal_show as $item)
                                    @php
                                        $dress_mea = App\Models\Dressmea::where('id', $item->dressmea_id)->first();
                                    @endphp
                                    @if ($dress_mea->current_mea != $item->new_size)
                                        <tr>
                                            <td>{{ $dress_mea->mea_dress_name }}</td>
                                            <td>{{ $dress_mea->current_mea }}</td>
                                            <td>{{ $item->new_size }}</td>
                                        </tr>
                                    @endif
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" type="button" class="btn btn-secondary"
                            data-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal สำหรับยืนยันการอัปเดตสถานะพร้อมรายละเอียดเพิ่มเติม -->
    <div class="modal fade" id="updatestatus" tabindex="-1" role="dialog" aria-labelledby="updatestatusLabel"
        aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('employee.actionupdatestatusrentdress', ['id' => $orderdetail->id]) }}"
                    method="POST">
                    @csrf

                    <div class="modal-header" style="background-color: #007bff; color: white;">
                        <h5 class="modal-title" id="updatestatusLabel" style="font-weight: bold; font-size: 1.5rem;">
                            อัปเดตสถานะการเช่า</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            style="color: white;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h6 class="mb-3">รายละเอียดการจอง:</h6>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 30%; text-align: left; padding: 10px;">ชื่อลูกค้า:</th>
                                    <td style="padding: 10px;">คุณ{{ $customer->customer_fname }}
                                        {{ $customer->customer_lname }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 30%; text-align: left; padding: 10px;">วันที่นัดรับ:</th>
                                    <td style="padding: 10px;">
                                        {{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($Date->pickup_date)->year + 543 }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 30%; text-align: left; padding: 10px;">ส่วนต่างที่ต้องจ่าย:</th>
                                    <td style="padding: 10px;">
                                        {{ number_format($orderdetail->price - $orderdetail->deposit) }} บาท</td>
                                </tr>
                                <tr>
                                    <th style="width: 30%; text-align: left; padding: 10px;">เงินประกัน:</th>
                                    <td style="padding: 10px;">{{ number_format($orderdetail->damage_insurance, 2) }} บาท
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- สรุปการชำระเงิน -->
                        <h6 class="mt-4 mb-3">สรุปการชำระเงิน:</h6>
                        <div class="alert alert-info"
                            style="background-color: #e9f7f9; border-color: #bee5eb; color: #0c5460; font-size: 1.2rem; padding: 10px;">
                            <p>ยอดคงเหลือที่ต้องชำระ: <strong
                                    id="totalDue">{{ number_format($orderdetail->price - $orderdetail->deposit + $orderdetail->damage_insurance) }}
                                    บาท</strong></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            style="background-color: #6c757d; border-color: #6c757d;">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary" id="confirmUpdateButton"
                            style="background-color: #28a745; border-color: #28a745;">ยืนยันการอัปเดตสถานะ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="updatestatus_return" tabindex="-1" role="dialog"
        aria-labelledby="updatestatus_returnLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('employee.actionupdatestatusrentdress', ['id' => $orderdetail->id]) }}"
                method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #28a745; color: white;">
                        <h5 class="modal-title" id="returnModalLabel" style="font-weight: bold; font-size: 1.5rem;">
                            ยืนยันการคืนชุด</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            style="color: white;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- แสดงรายละเอียดการเช่าและการคืน -->
                        <h6 class="mb-3">รายละเอียดการเช่าและการคืน:</h6>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 50%; text-align: left; padding: 10px;">ชื่อลูกค้า:</th>
                                    <td style="padding: 10px;">คุณ{{ $customer->customer_fname }}
                                        {{ $customer->customer_lname }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%; text-align: left; padding: 10px;">วันที่รับชุด:</th>
                                    <td style="padding: 10px;">
                                        {{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($Date->pickup_date)->year + 543 }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%; text-align: left; padding: 10px;">วันที่มารับชุดจริง:</th>
                                    <td style="padding: 10px;">
                                        {{ \Carbon\Carbon::parse($Date->actua_pickup_date)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($Date->actua_pickup_date)->year + 543 }}</td>
                                </tr>

                                <tr>
                                    <th style="width: 50%; text-align: left; padding: 10px;">วันที่นัดคืน:</th>
                                    <td style="padding: 10px;">
                                        {{ \Carbon\Carbon::parse($Date->return_date)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($Date->return_date)->year + 543 }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%; text-align: left; padding: 10px;">วันที่มาคืนจริง:</th>
                                    <td style="padding: 10px;">
                                        {{ \Carbon\Carbon::now()->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::now()->year + 543 }}</td>
                                </tr>

                                <tr>
                                    <th style="width: 50%; text-align: left; padding: 10px;">ค่าปรับส่งคืนชุดล่าช้า:</th>
                                    <td style="padding: 10px;"><span id="mulct"></span>
                                    </td>
                                </tr>
                                <input type="hidden" name="late_return_fee" id="late_return_fee">
                                <input type="hidden" name="late_chart" id="late_chart">
                                <script>
                                    var now_day = new Date();
                                    var return_date = new Date('{{ $Date->return_date }}');
                                    now_day.setHours(0, 0, 0, 0);
                                    return_date.setHours(0, 0, 0, 0);
                                    // console.log(return_date) ; 
                                    var t = now_day - return_date;
                                    var s = Math.ceil(t / (1000 * 60 * 60 * 24));
                                    var p = s * 200; //ถ้าคืนช้า คิดวันละ 200 บาท  
                                    document.getElementById('late_return_fee').value = p;
                                    if (s == 0) {
                                        document.getElementById('mulct').innerHTML = '0 บาท';
                                    } else if (s > 0) {
                                        document.getElementById('mulct').innerHTML = p + ' บาท ' + 'เนื่องจากคืนล่าช้า ' + s + ' วัน';
                                    } else {
                                        document.getElementById('mulct').innerHTML = '0 บาท'
                                    }
                                </script>
                                <tr>
                                    <th style="width: 50%; text-align: left; padding: 10px;">ค่าธรรมเนียมขยายระยะเวลาเช่า:
                                    </th>
                                    <td style="padding: 10px;"><span id="rental_exte"></span></td>
                                </tr>
                                <script>
                                    var rr = new Date('{{ $Date->return_date }}');
                                    var pp = new Date('{{ $Date->pickup_date }}');
                                    rr.setHours(0, 0, 0, 0);
                                    pp.setHours(0, 0, 0, 0);
                                    var rr_pp = rr - pp;
                                    var late_chart_day = Math.ceil(rr_pp / (1000 * 60 * 60 * 24));

                                    if (late_chart_day > 3) {
                                        console.log('ในสัญญาเกิน 3 วัน ');
                                        var n = new Date('{{ $Date->actua_pickup_date }}'); //วันที่รับจริง
                                        var nn = new Date(); //วันปัจจุบัน
                                        n.setHours(0, 0, 0, 0);
                                        nn.setHours(0, 0, 0, 0);

                                        var nn_n = Math.ceil((nn - n) / (1000 * 60 * 60 * 24));

                                        if (nn_n > 3) {
                                            document.getElementById('rental_exte').innerHTML = (nn_n - 3) * 100 + ' บาท' + '   (ขยายเวลาเช่า ' + (nn_n -
                                                3) + ' วัน)';
                                        } else if (nn_n <= 3) {
                                            document.getElementById('rental_exte').innerHTML = '0 บาท';
                                        }

                                    } else if (late_chart_day <= 3) {
                                        document.getElementById('rental_exte').innerHTML = '0 บาท';
                                    }
                                </script>


                            </tbody>
                        </table>

                        <!-- ฟิลด์สำหรับพนักงานกรอกค่าธรรมเนียมการเสียหาย -->
                        <h6 class="mb-3">กรอกข้อมูลค่าธรรมเนียม:</h6>
                        <div class="form-group">
                            <p>เก็บประกันจากลูกค้า : <span>{{ $orderdetail->damage_insurance }} บาท</span></p>
                            <label for="damageFee">ค่าธรรมเนียมความเสียหาย (หักจากประกัน):</label>
                            <input type="number" class="form-control" name="total_damage_insurance"
                                id="total_damage_insurance" placeholder="กรอกจำนวนเงิน" min="0" step="0.01"
                                required value="0">
                        </div>

                        <!-- สรุปการชำระเงิน -->
                        <h6 class="mt-4 mb-3">สรุปการชำระเงิน:</h6>
                        {{-- <div class="alert alert-warning" style="font-size: 1.2rem; padding: 10px;">
                        <p>ยอดประกันชุดต้องคืนให้กับลูกค้า: <strong id="total_return_to_customer"></strong></p>
                        <p>ยอดเงินที่ลูกค้าต้องจ่ายเพิ่มเติม: <strong id="total_customer_to_pay_shop"></strong></p>
                    </div> --}}


                        <hr>
                        <!-- ฟิลด์สำหรับเลือกสถานะการดำเนินการหลังคืนชุด -->
                        <h6 class="mb-3">การดำเนินการหลังจากคืนชุด:</h6>
                        <div class="form-group">
                            <label for="return_status">เลือกการดำเนินการ:</label>
                            <select class="form-control" id="return_status" name="return_status" required>
                                <option value="ส่งซัก" selected>ส่งซัก</option>
                                <option value="ต้องซ่อมแซม">ส่งซ่อม</option>
                            </select>
                        </div>
                        <div class="form-group" id="repair_details_group" style="display: none;">
                            <label for="repair_details">รายละเอียดการซ่อม:
                            </label>
                            <select id="repair_type" name="repair_type">
                                <option value="10" id="type_total_dress">ทั้งชุด</option>
                                <option value="20" id="type_shirt">เฉพาะเสื้อ</option>
                                <option value="30" id="type_skirt">เฉพาะผ้าถุง</option>
                            </select>
                            <textarea class="form-control" id="repair_details" name="repair_details" rows="3"
                                placeholder="รายละเอียดการซ่อม"></textarea>
                        </div>

                        @php
                            $dress_separable = App\Models\Dress::where('id', $orderdetail->dress_id)->value(
                                'separable',
                            ); //เช็คว่าชุดแยกได้ไหม
                        @endphp

                        <script>
                            var dress_separable = '{{ $dress_separable }}';
                            var return_status = document.getElementById('return_status');
                            var type_total_dress = document.getElementById('type_total_dress');
                            var type_shirt = document.getElementById('type_shirt');
                            var type_skirt = document.getElementById('type_skirt');
                            var repair_details_group = document.getElementById('repair_details_group');
                            var have_shirt = '{{ $orderdetail->shirtitems_id }}';
                            var have_skirt = '{{ $orderdetail->skirtitems_id }}';

                            return_status.addEventListener('change', function() {

                                if (return_status.value === "ต้องซ่อมแซม") {
                                    repair_details_group.style.display = 'block';
                                    document.getElementById('repair_details').setAttribute('required', 'required');

                                    if (dress_separable == '1') {
                                        type_shirt.style.display = 'none';
                                        type_skirt.style.display = 'none';
                                        type_total_dress.style.display = 'block';
                                        type_total_dress.selected = true;
                                    } else if (dress_separable == '2') {
                                        if (have_shirt) {
                                            type_shirt.style.display = 'block';
                                            type_skirt.style.display = 'none';
                                            type_total_dress.style.display = 'none';
                                            type_shirt.selected = true;
                                        } else if (have_skirt) {
                                            type_shirt.style.display = 'none';
                                            type_skirt.style.display = 'block';
                                            type_total_dress.style.display = 'none';
                                            type_skirt.selected = true;
                                        } else {
                                            type_shirt.style.display = 'block';
                                            type_skirt.style.display = 'block';
                                            type_total_dress.style.display = 'block';
                                            type_total_dress.selected = true;
                                        }
                                    }

                                } else {
                                    repair_details_group.style.display = 'none';
                                    document.getElementById('repair_details').value = '';
                                    document.getElementById('repair_details').removeAttribute('required');
                                }
                            });
                        </script>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            style="background-color: #6c757d; border-color: #6c757d;">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary" id="confirmReturnButton"
                            style="background-color: #007bff; border-color: #007bff;">ยืนยันการคืนชุด</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
