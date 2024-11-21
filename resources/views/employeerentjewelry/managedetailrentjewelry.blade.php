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
                                // $now_today = now()->setTime(0, 0)->format('Y-m-d');
                                $now_today = now()->format('Y-m-d');
                            @endphp

                            <div class="col-md-6 text-right"
                                @if ($orderdetail->status_detail == 'ถูกจอง') style="display: block ;"
                            @else
                            style="display: none ;" @endif>
                                <button class="btn" style="background: #C28041; color: #ffffff;" data-toggle="modal"
                                    data-target="#updatestatus">อัปเดตสถานะการเช่า</button>
                            </div>

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
                            <i class="bi bi-currency-dollar"></i>
                            ราคาเช่า : {{ number_format($orderdetail->price, 2) }} บาท
                        </p>

                        <p class="mb-3">
                            <i class="bi bi-currency-dollar"></i>
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
                                ชำระเงินเต็มจำนวนแล้ว
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
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
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
            <form action="{{ route('employee.updatereturnjewelry', ['id' => $orderdetail->id]) }}"
                method="POST">
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
                    <input type="hidden" name="check_for_set_or_item" value="{{$reservation->jewelry_id ? 'item' : 'set'}}">


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
                                            <select name="actionreturnitem" id="actionreturnitem" class="form-control">
                                                <option value="cleanitem" selected>ส่งทำความสะอาด</option>
                                                <option value="repairitem">ต้องซ่อม</option>
                                            </select>

                                            <div id="showrepair_detail_item" class="mt-2"
                                                style="display: none;">
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
                                        
                                        <input type="hidden" name="refil_id_[]" value="{{$item->id}}">
                                        <input type="hidden" name="refil_jewelry_id_[]" value="{{$item->jewelry_id}}">
                                        
                                        <td class="px-4 py-2">  
                                             {{ $item->jewvationtorefil->jewelry_m_o_typejew->type_jewelry_name }}
                                             {{ $item->jewvationtorefil->jewelry_m_o_typejew->specific_letter }}{{ $item->jewvationtorefil->jewelry_code }}
                                        </td>
                                        <td class="px-4 py-2">
                                            <select name="action_set_[]"
                                                id="actionreturn{{ $item->id}}" class="form-control">
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
