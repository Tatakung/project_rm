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
            background-color: #6B5949;
            color: #000;
            /* เปลี่ยนสีตัวอักษรเป็นสีดำเพื่อให้เห็นชัดบนพื้นสีเหลือง */
        }

        .status-line {
            flex-grow: 1;
            height: 3px;
            background-color: #EBC591;
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
            border-left: 10px solid #EBC591;
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
    <ol class="breadcrumb" style="background-color: transparent; ">
        <li class="breadcrumb-item"><a href="">หน้าแรก</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employee.ordertotal') }}">รายการออเดอร์ทั้งหมด</a></li>
        <li class="breadcrumb-item"><a
                href="{{ route('employee.ordertotaldetail', ['id' => $orderdetail->order_id]) }}">รายละเอียดออเดอร์ที่
                {{ $orderdetail->order_id }}</a></li>
        <li class="breadcrumb-item active">{{ $orderdetail->title_name }}</li>
    </ol>






    <div class="container mt-4">





        {{-- เช็คคิว --}}
        @php
            if ($reservation->jewelry_id) {
                $list_check = [];
                $check_unique_jew_id = App\Models\Reservation::where('status_completed', 0)
                    ->whereIn('status', ['ถูกจอง', 'กำลังเช่า'])
                    ->where('jewelry_id', $reservation->jewelry_id)
                    ->get();
                foreach ($check_unique_jew_id as $item) {
                    $list_check[] = $item->id;
                }
                $set_in_re = App\Models\Reservation::where('status_completed', 0)
                    ->whereIn('status', ['ถูกจอง', 'กำลังเช่า'])
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
                $check_bunton_pass = true; //ตัวเช็คในการกดปุ่มอัพเดตสถานะ

                if ($sort_queue) {
                    if ($reservation->id == $sort_queue->id) {
                        if ($reservation->status == 'ถูกจอง') {
                            if ($reservation->resermanytoonejew->jewelry_status != 'พร้อมให้เช่า') {
                                $check_bunton_pass = false;
                            }
                        }
                        if ($reservation->status == 'กำลังเช่า') {
                            $check_bunton_pass = true;
                        }
                    } else {
                        $check_bunton_pass = false;
                    }
                }

                // dd($check_bunton_pass) ;
            } elseif ($reservation->jewelry_set_id) {
                $list_set = [];
                // แค่jewelry_set_idในตาราง reservation
                $jewwelry_set_id_in_reservation = App\Models\Reservation::where('status_completed', 0)
                    ->whereIn('status', ['ถูกจอง', 'กำลังเช่า'])
                    ->where('jewelry_set_id', $reservation->jewelry_set_id)
                    ->get();
                foreach ($jewwelry_set_id_in_reservation as $key => $value) {
                    $list_set[] = $value->id;
                }
                // ส่วนjew_id
                $jew_set_item = App\Models\Jewelrysetitem::where('jewelry_set_id', $reservation->jewelry_set_id)->get();
                foreach ($jew_set_item as $key => $item) {
                    $check_jew_id_in_re = App\Models\Reservation::where('status_completed', 0)
                        ->whereIn('status', ['ถูกจอง', 'กำลังเช่า'])
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
                $check_bunton_pass = true; //ตัวเช็คในการกดปุ่มอัพเดตสถานะ
                if ($sort_queue) {
                    if ($reservation->id == $sort_queue->id) {
                        if ($reservation->status == 'ถูกจอง') {
                            $jew_set_id_for = App\Models\Jewelrysetitem::where(
                                'jewelry_set_id',
                                $reservation->jewelry_set_id,
                            )->get();
                            foreach ($jew_set_id_for as $key => $value) {
                                $check_jew_status = App\Models\Jewelry::find($value->jewelry_id);
                                if ($check_jew_status->jewelry_status != 'พร้อมให้เช่า') {
                                    $check_bunton_pass = false;
                                }
                            }
                        }

                        if ($reservation->status == 'กำลังเช่า') {
                            $check_bunton_pass = true;
                        }
                    } else {
                        $check_bunton_pass = false;
                    }
                }
            }
        @endphp

        {{-- <p>reservation_id {{ $reservation->id }}</p>
        <p>คิวแรก : {{ $sort_queue->id }}</p> --}}

        @if ($check_bunton_pass == false)
            @if ($reservation->jewelry_id && $reservation->re_one_many_details->first()->status_detail != 'คืนเครื่องประดับแล้ว')
                @if ($reservation->id != $sort_queue->id)
                    {{-- ไม่ใช่คิวแรก --}}
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="alert alert-danger" role="alert">
                                <strong>แจ้งเตือน:</strong> เครื่องประดับนี้<span> {{ $sort_queue->status }} </span>
                                โดยลูกค้าท่านอื่น ไม่สามารถดำเนินการในรายการนี้ได้
                                <hr>
                                <p class="mb-0">
                                    @php
                                        $find_order_detail_now = App\Models\Orderdetail::where(
                                            'reservation_id',
                                            $sort_queue->id,
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
                                    {{ \Carbon\carbon::parse($sort_queue->start_date)->locale('th')->isoFormat('D MMM') }}
                                    {{ \Carbon\carbon::parse($sort_queue->start_date)->year + 543 }}
                                    <br>
                                    &bull; กำหนดคืน:
                                    {{ \Carbon\carbon::parse($sort_queue->end_date)->locale('th')->isoFormat('D MMM') }}
                                    {{ \Carbon\carbon::parse($sort_queue->end_date)->year + 543 }}
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif($reservation->id == $sort_queue->id)
                    @if ($reservation->resermanytoonejew->jewelry_status != 'พร้อมให้เช่า')
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="alert alert-danger" role="alert">

                                    <strong>แจ้งเตือน:</strong> เครื่องประดับชิ้นนี้<span>
                                        {{ $reservation->resermanytoonejew->jewelry_status }} </span>
                                    กรุณารอจนกว่าจะพร้อมใช้งาน


                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            @elseif($reservation->jewelry_set_id && $reservation->re_one_many_details->first()->status_detail != 'คืนเครื่องประดับแล้ว')
                @if ($reservation->id != $sort_queue->id)
                    {{-- ไม่ใช่คิวแรก --}}
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="alert alert-danger" role="alert">
                                <strong>แจ้งเตือน:</strong> กรุณารอคิวจนกว่าจะถึงคิว<span>


                            </div>
                        </div>
                    </div>
                @elseif($reservation->id == $sort_queue->id)
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="alert alert-danger" role="alert">
                                <strong>แจ้งเตือน:</strong> ถึงคิวแล้ว : สถานะรายการเครื่องประดับในเซตนี้<span>
                                    @foreach ($setjewelryitem as $item)
                                        <p> {{ $item->jewitem_m_to_o_jew->jewelry_m_o_typejew->type_jewelry_name }}
                                            {{ $item->jewitem_m_to_o_jew->jewelry_m_o_typejew->specific_letter }}{{ $item->jewitem_m_to_o_jew->jewelry_code }}
                                            : {{ $item->jewitem_m_to_o_jew->jewelry_status }}</p>
                                    @endforeach

                            </div>
                        </div>
                    </div>
                @endif
            @endif

        @endif

        <h4 class="mt-2"><strong>รายการ :
                @if ($reservation->jewelry_id)
                    เช่า{{ $typejewelry->type_jewelry_name }}
                    {{ $typejewelry->specific_letter }}{{ $jewelry->jewelry_code }}
                @elseif($reservation->jewelry_set_id)
                    เช่าเซต{{ $setjewelry->set_name }}
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

                            @php
                                $now_today = now()->format('Y-m-d');
                            @endphp

                            @if ($check_bunton_pass == true)
                                <div class="col-md-6 text-right"
                                    @if ($orderdetail->status_detail == 'ถูกจอง') style="display: block ;"
                                @else
                                    style="display: none ;" @endif>

                                    <button class="btn" style="background: #C28041; color: #ffffff;" data-toggle="modal"
                                        data-target="#updatestatus">อัปเดตสถานะการเช่า</button>
                                </div>
                            @endif


                            <div class="col-md-6 text-right"
                                @if ($orderdetail->status_detail == 'กำลังเช่า') style="display: block ;"
                            @else
                            style="display: none ;" @endif>
                                <button class="btn" style="background: #C28041; color: #ffffff;" data-toggle="modal"
                                    data-target="#updatestatus_return">อัปเดตการรับเครื่องประดับคืน</button>
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


                                    <a href="{{route('receiptpickup', ['id' => $orderdetail->id])}}" target="_blank"
                                        class="btn btn-sm btn-secondary"@if ($receipt_bill_pickup) style="display: block ; "
                                    @else
                                    style="display: none ; " @endif>ใบเสร็จรับเครื่องประดับ</a>
                    
                                </small>
                            </div>


                            <div class="status-line "></div>



                            <div class="status-step text-center">
                                <div class="status-icon @if (in_array('คืนเครื่องประดับแล้ว', $list_status)) active @endif">
                                    <i class="fas fa-check"></i>
                                </div>
                                <p>คืนเครื่องประดับแล้ว</p>
                                <small>
                                    <p>
                                        @php
                                            $created_at = App\Models\Orderdetailstatus::where(
                                                'order_detail_id',
                                                $orderdetail->id,
                                            )
                                                ->where('status', 'คืนเครื่องประดับแล้ว')
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



                                    <a href="{{route('receiptreturn' , ['id' => $orderdetail->id])}}" target="_blank" class="btn btn-sm btn-secondary"@if ($receipt_bill_return) style="display: block ; "
                                        @else
                                        style="display: none ; " @endif>ใบเสร็จคืนเครื่องประดับ</a>
                        
                        

                                    
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3 d-flex align-items-stretch">
            {{-- ข้อมูลเครื่องประดับ --}}
            <div class="col-md-6 mb-3">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h5>ข้อมูลเครื่องประดับ</h5>
                                @if ($reservation->jewelry_id)
                                    <p>ประเภทเครื่องประดับ : {{ $typejewelry->type_jewelry_name }}</p>
                                    <p>หมายเลขเครื่องประดับ :
                                        {{ $typejewelry->specific_letter }}{{ $jewelry->jewelry_code }}</p>
                                    <div class="col-md-4">
                                        <img src="{{ asset('storage/' . $imagejewelry->jewelry_image) }}"
                                            alt="เครื่องประดับ" class="img-fluid rounded"
                                            style="width: 154px; height: auto;">
                                    </div>
                                @elseif($reservation->jewelry_set_id)
                                    <p>ประเภทเครื่องประดับ : เซตเครื่องประดับปิ่นผมสุดคุ้ม</p>
                                    <p>หมายเลขเครื่องประดับ : SET00{{ $setjewelry->id }}</p>
                                    <p>ประกอบด้วย :</p>
                                    <div class="row">
                                        @foreach ($setjewelryitem as $item)
                                            <div class="col-md-4 mb-3">
                                                <img src="{{ asset('storage/' . $item->jewitem_m_to_o_jew->jewelryimages->first()->jewelry_image) }}"
                                                    alt="เครื่องประดับในเซต" class="img-fluid rounded mb-2"
                                                    style="height: 150px; width: 150px; object-fit: cover;">
                                                <small class="d-block">
                                                    {{ $item->jewitem_m_to_o_jew->jewelry_m_o_typejew->type_jewelry_name }}
                                                    {{ $item->jewitem_m_to_o_jew->jewelry_m_o_typejew->specific_letter }}{{ $item->jewitem_m_to_o_jew->jewelry_code }}
                                                </small>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ข้อมูลการเช่า --}}
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title">ข้อมูลการเช่า</h5>

                        <p class="mb-3">
                            <span class="bi bi-person"></span>
                            ชื่อผู้เช่า : คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }}
                        </p>

                        <p class="mb-3">
                            <span class="bi bi-calendar"></span>
                            วันที่นัดรับ-นัดคืน :
                            {{ \Carbon\Carbon::parse($reservation->start_date)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($reservation->start_date)->year + 543 }} -
                            {{ \Carbon\Carbon::parse($reservation->end_date)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($reservation->end_date)->year + 543 }}
                        </p>

                        <p class="mb-3">
                            <i></i>
                            ราคาเช่า : {{ number_format($orderdetail->price, 2) }} บาท
                        </p>

                        <p class="mb-3">
                            <i></i>
                            เงินมัดจำ : {{ number_format($orderdetail->deposit, 2) }} บาท
                        </p>

                        <p class="mb-3">
                            <i class="bi bi-shield-check"></i>
                            ประกันค่าเสียหาย : {{ number_format($orderdetail->damage_insurance, 2) }} บาท
                        </p>

                        <p class="mb-3">
                            <i class="bi bi-check-circle"></i>
                            สถานะ :
                            @if ($orderdetail->status_payment == 1)
                                ชำระเงินมัดจำแล้ว
                            @elseif($orderdetail->status_payment == 2)
                                ชำระเงินครบแล้ว
                            @endif
                        </p>

                        <p class="mb-3">
                            <span class="bi bi-person"></span>
                            พนักงานผู้รับออเดอร์ : คุณ{{ $user->name }} {{ $user->lname }}
                        </p>
                    </div>
                </div>
            </div>
        </div>


        





        <div class="row mt-3 d-flex align-items-stretch" id="div_show_net">
            <div class="col-md-12"
                @if ($orderdetail->status_detail == 'คืนเครื่องประดับแล้ว') style="display: block;" 
                @else 
                    style="display: none;" @endif>
                <div class="card shadow-sm">
                    <!-- หัวข้อการ์ด -->
                    <div class="card-header bg-light border-bottom d-flex align-items-center">
                        <div class="border-4 border-primary rounded me-2" style="width: 4px; height: 20px;"></div>
                        <h5 class="card-title mb-0">
                            <i class="bi bi-file-earmark-text"></i> สรุปข้อมูลการเช่าเครื่องประดับ
                        </h5>
                    </div>

                    <!-- เนื้อหาการ์ด -->
                    <div class="card-body p-4">
                        <div class="row">
                            <!-- ข้อมูลระยะเวลา -->
                            <div class="col-md-6 mb-4">
                                <div class="d-flex align-items-center text-secondary mb-3">
                                    <i class="bi bi-calendar3 me-2"></i>
                                    <span class="fw-medium">ข้อมูลระยะเวลา</span>
                                </div>
                                <div class="ms-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-secondary">วันที่รับเครื่องประดับจริง</span>
                                        <span
                                            class="fw-medium">{{ \Carbon\Carbon::parse($Date->actua_pickup_date)->locale('th')->isoFormat('D MMM') }}
                                            {{ \Carbon\Carbon::parse($Date->actua_pickup_date)->year + 543 }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-secondary">วันที่คืนเครื่องประดับจริง</span>
                                        <span
                                            class="fw-medium">{{ \Carbon\Carbon::parse($Date->actua_return_date)->locale('th')->isoFormat('D MMM') }}
                                            {{ \Carbon\Carbon::parse($Date->actua_return_date)->year + 543 }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <span class="text-secondary">จำนวนวันที่เช่าทั้งหมด</span>
                                        <span class="fw-medium" id="total_day_reall">10 วัน</span>
                                        <script>
                                            var total_day_real = document.getElementById('total_day_reall');
                                            var day_actua_pickup_date = new Date('{{ $Date->actua_pickup_date }}');
                                            day_actua_pickup_date.setHours(0, 0, 0, 0);

                                            var day_actua_return_date = new Date('{{ $Date->actua_return_date }}');
                                            day_actua_return_date.setHours(0, 0, 0, 0);

                                            var total_actua_pickup_date_return_date = Math.ceil((day_actua_return_date - day_actua_pickup_date) / (1000 * 60 *
                                                60 * 24));
                                            total_day_real.innerHTML = ' ' + total_actua_pickup_date_return_date + ' วัน';
                                        </script>

                                    </div>
                                </div>
                            </div>

                            <!-- ข้อมูลการเงิน -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-center text-secondary mb-3">
                                    <i class="bi bi-coin me-2"></i>
                                    <span class="fw-medium">ข้อมูลการเงิน</span>
                                </div>
                                <div class="ms-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-secondary">รายได้ค่าเช่า</span>
                                        <span class="fw-medium text-secondary">{{ number_format($orderdetail->price, 2) }}
                                            บาท</span>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-secondary">รายได้จากการหักเงินประกัน
                                            
                                        </span>

                                        @if ($additional->count() > 0)
                                            @foreach ($additional as $item)
                                                @if ($item->charge_type == 1)
                                                    <span class="fw-medium">{{ number_format($item->amount, 2) }}
                                                        บาท</span>
                                                @else
                                                    <span class="fw-medium">0.00 บาท</span>
                                                @endif
                                            @endforeach
                                        @else
                                            <span class="fw-medium">0.00 บาท</span>
                                        @endif


                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-secondary">รายได้จากการคืนล่าช้า</span>
                                        @if ($additional->count() > 0)
                                            @foreach ($additional as $item)
                                                @if ($item->charge_type == 2)
                                                    <span class="fw-medium">{{ number_format($item->amount, 2) }}
                                                        บาท</span>
                                                @else
                                                    <span class="fw-medium">0.00 บาท</span>
                                                @endif
                                            @endforeach
                                        @else
                                            <span class="fw-medium">0.00 บาท</span>
                                        @endif
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-secondary">รายได้จากค่าธรรมเนียมขยายระยะเวลาเช่า </span>
                                        @if ($additional->count() > 0)
                                            @foreach ($additional as $item)
                                                @if ($item->charge_type == 3)
                                                    <span class="fw-medium">{{ number_format($item->amount, 2) }}
                                                        บาท</span>
                                                @else
                                                    <span class="fw-medium">0.00 บาท</span>
                                                @endif
                                            @endforeach
                                        @else
                                            <span class="fw-medium">0.00 บาท</span>
                                        @endif
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <span class="text-secondary fw-medium"><strong>รายได้รวมทั้งหมด</strong></span>
                                        @if ($additional->count() <= 0)
                                            <span
                                                class="fw-medium fs-5 text-primary">{{ number_format($orderdetail->price) }}
                                                บาท</span>
                                        @elseif($additional->count() > 0)
                                            <span
                                                class="fw-medium fs-5 text-primary">{{ number_format($orderdetail->price + $sum_additional, 2) }}
                                                บาท</span>
                                        @endif




                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
















    </div>


    <div class="modal fade" id="updatestatus" tabindex="-1" role="dialog" aria-labelledby="updatestatusLabel"
        aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('employee.actionupdatereceivejewelry', ['id' => $orderdetail->id]) }}"
                    method="POST">
                    @csrf

                    <div class="modal-header" style="background-color:#EAD8C0 ;">
                        <h5 class="modal-title" id="updatestatusLabel">
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
                                        {{ number_format($orderdetail->price - $orderdetail->deposit , 2 ) }} บาท</td>
                                </tr>
                                <tr>
                                    <th style="width: 30%; text-align: left; padding: 10px;">เงินประกัน:</th>
                                    <td style="padding: 10px;">{{ number_format($orderdetail->damage_insurance, 2) }}
                                        บาท
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- สรุปการชำระเงิน -->
                        <h6 class="mt-4 mb-3">สรุปการชำระเงิน:</h6>
                        <div class="alert alert-info"
                            style="background-color: #e9f7f9; border-color: #bee5eb; color: #0c5460; font-size: 1.2rem; padding: 10px;">
                            <p>ยอดคงเหลือที่ต้องชำระ: <strong
                                    id="totalDue">{{ number_format($orderdetail->price - $orderdetail->deposit + $orderdetail->damage_insurance,2) }}
                                    บาท</strong></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn " data-dismiss="modal"
                            style="background-color:#DADAE3;">ยกเลิก</button>
                        <button type="submit" class="btn " id="confirmUpdateButton"
                            style="background-color:#ACE6B7;">ยืนยันการอัปเดตสถานะ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>





    <div class="modal fade" id="showfail" role="dialog" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog" role="document">
            <div class="modal-content custom-modal-content"
                style="max-width: 300px; height: 50px; width: 100%; margin: auto; background-color: #EE4E4E; border: 2px solid #EE4E4E; ">
                <div class="modal-body"
                    style="padding: 5px; display: flex; align-items: center; justify-content: center;">
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




    <div class="modal fade" id="updatestatus_return" tabindex="-1" role="dialog"
        aria-labelledby="updatestatus_returnLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('employee.updatereturnjewelry', ['id' => $orderdetail->id]) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header" style="background-color:#EAD8C0 ;">
                        <h5 class="modal-title" id="returnModalLabel">
                            ยืนยันการคืนเครื่องประดับ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            style="color: white;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- แสดงรายละเอียดการเช่าและการคืน -->
                        <strong class="mb-3">รายละเอียดการเช่าและการคืน:</strong>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 50%; text-align: left; padding: 10px;">ชื่อลูกค้า:</th>
                                    <td style="padding: 10px;">คุณ{{ $customer->customer_fname }}
                                        {{ $customer->customer_lname }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%; text-align: left; padding: 10px;">วันที่นัดรับ:</th>
                                    <td style="padding: 10px;">
                                        {{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($Date->pickup_date)->year + 543 }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%; text-align: left; padding: 10px;">วันที่มารับจริง:</th>
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
                                    <th style="width: 50%; text-align: left; padding: 10px;">ค่าปรับส่งคืนล่าช้า:</th>
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
                                    <th style="width: 50%; text-align: left; padding: 10px;">
                                        ค่าธรรมเนียมขยายระยะเวลาเช่า:
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
                        <strong class="mb-3">กรอกข้อมูลค่าธรรมเนียม:</strong>
                        <div class="form-group">
                            <p>เก็บประกันจากลูกค้า : <span>{{ $orderdetail->damage_insurance }} บาท</span></p>
                            <strong for="damageFee">ค่าธรรมเนียมความเสียหาย (หักจากประกัน):</strong>
                            <input type="number" class="form-control" name="total_damage_insurance"
                                id="total_damage_insurance" placeholder="กรอกจำนวนเงิน" min="0" step="0.01"
                                required value="0">
                        </div>

                        <!-- สรุปการชำระเงิน -->
                        <strong class="mt-4 mb-3">สรุปการชำระเงิน:</strong>
                        <input type="hidden" name="check_for_set_or_item"
                            value="{{ $reservation->jewelry_id ? 'item' : 'set' }}">


                        @if ($reservation->jewelry_id)
                            <div class="form-group">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="bg-gray-100">รายการ</th>
                                            <th class="bg-gray-100">การดำเนินการ</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <td class="px-4 py-2">
                                                {{ $typejewelry->type_jewelry_name }}
                                                {{ $typejewelry->specific_letter }}
                                                {{ $jewelry->jewelry_code }}
                                            </td>
                                            <td class="px-4 py-2">
                                                <select name="actionreturnitem" id="actionreturnitem"
                                                    class="form-control">
                                                    <option value="cleanitem" selected>ส่งทำความสะอาด</option>
                                                    <option value="repairitem">ต้องซ่อม</option>
                                                </select>

                                                <div id="showrepair_detail_item" class="mt-2" style="display: none;">
                                                    <textarea name="repair_detail_for_item" class="form-control" placeholder="กรุณาระบุรายละเอียดการซ่อม..."
                                                        rows="3"></textarea>
                                                </div>
                                            </td>
                                        </tr>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                var actionreturnitem = document.getElementById('actionreturnitem');
                                                var showrepair_detail_item = document.getElementById('showrepair_detail_item');
                                                actionreturnitem.addEventListener('change', function() {
                                                    if (actionreturnitem.value == "repairitem") {
                                                        showrepair_detail_item.style.display = 'block';
                                                    } else {
                                                        showrepair_detail_item.style.display = 'none';
                                                    }
                                                });
                                            });
                                        </script>
                                    </tbody>
                                </table>
                            </div>
                        @elseif($reservation->jewelry_set_id)
                            <div class="form-group">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="bg-gray-100">รายการ</th>
                                            <th class="bg-gray-100">การดำเนินการ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reservationfilter as $item)
                                            <tr>

                                                <input type="hidden" name="refil_id_[]" value="{{ $item->id }}">
                                                <input type="hidden" name="refil_jewelry_id_[]"
                                                    value="{{ $item->jewelry_id }}">

                                                <td class="px-4 py-2">
                                                    {{ $item->jewvationtorefil->jewelry_m_o_typejew->type_jewelry_name }}
                                                    {{ $item->jewvationtorefil->jewelry_m_o_typejew->specific_letter }}{{ $item->jewvationtorefil->jewelry_code }}
                                                </td>
                                                <td class="px-4 py-2">
                                                    <select name="action_set_[]" id="actionreturn{{ $item->id }}"
                                                        class="form-control">
                                                        <option value="clean" selected>ส่งทำความสะอาด</option>
                                                        <option value="repair">ต้องซ่อม</option>
                                                    </select>

                                                    <div id="repair_details{{ $item->id }}" class="mt-2"
                                                        style="display: none;">
                                                        <textarea name="repair_details_set_[]" class="form-control" placeholder="กรุณาระบุรายละเอียดการซ่อม..."
                                                            rows="3"></textarea>
                                                    </div>
                                                </td>
                                            </tr>
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    var actionreturn = document.getElementById('actionreturn{{ $item->id }}');
                                                    var showrepair_detail = document.getElementById('repair_details{{ $item->id }}');
                                                    actionreturn.addEventListener('change', function() {
                                                        if (actionreturn.value == "repair") {
                                                            showrepair_detail.style.display = 'block';
                                                        } else {
                                                            showrepair_detail.style.display = 'none';
                                                        }
                                                    });
                                                });
                                            </script>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn " data-dismiss="modal"
                            style="background-color:#DADAE3;">ยกเลิก</button>
                        <button type="submit" class="btn " id="confirmReturnButton"
                            style="background-color:#ACE6B7;">ยืนยันการคืนเครื่องประดับ</button>
                    </div>
                </div>
            </form>
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
@endsection
