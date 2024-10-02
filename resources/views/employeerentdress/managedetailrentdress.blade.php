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
            background-color: #e2361b;
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

        .status-line.active {
            background-color: #f9e746;
        }

        .status-step p {
            margin-bottom: 0;
        }

        .status-step small {
            color: #6c757d;
        }
    </style>
    <div class="container mt-4">
        @php
            $dress = App\Models\Dress::find($orderdetail->dress_id);
            $typename = App\Models\Typedress::where('id', $dress->type_dress_id)->value('type_dress_name');
        @endphp

        <h4 class="mt-5"><strong>รายการเช่า :
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
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="card-title">สถานะการเช่า</h5>
                            </div>
                            <div class="col-md-6" style="text-align: right ;">
                                <button type="button" class="btn btn-primary">อัพเดตสถานะการเช่า</button>
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
                                                    ->addHours(7)
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
                                                    ->addHours(7)
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
                                                    ->addHours(7)
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
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-4">
                            <img src="{{ asset('storage/' . $dressimage->dress_image) }}" alt="" width="154px;"
                                    height="auto">
                            </div>
                            <div class="col-md-8">

                            
                                <p><strong>ข้อมุลชุด</strong></p>
                                <p>ประเภทชุด : {{ $typename }}</p>
                                <p>หมายเลขชุด : {{ $dress->dress_code_new }}{{ $dress->dress_code }}</p>
                                <p>รายละเอียด : {{$dress->dress_description}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
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
                                ->orderBy('created_at', 'asc')
                                ->first();
                        @endphp

                        <p><i class="bi bi-calendar"></i> วันที่นัดรับ - คืน :
                            {{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($Date->pickup_date)->year + 543 }}
                            -
                            {{ \Carbon\Carbon::parse($Date->return_date)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($Date->return_date)->year + 543 }}
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
                    </div>
                </div>
            </div>
        </div>



        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="card-title">รายการปรับแก้ไขชุด</h5>
                            </div>
                            <div class="col-md-6" style="text-align: right ; ">
                                <button class="btn btn-success">ปรับแก้ไขนาดสำเร็จ</button>
                            </div>
                        </div>

                        <table class="table mt-3">
                            <thead>
                                <tr>
                                    <th scope="col">รายการ</th>
                                    <th scope="col">ขนาดเดิม</th>
                                    <th scope="col">ขนาดที่ปรับแก้</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>รอบอก</td>
                                    <td>30 นิ้ว</td>
                                    <td>32 นิ้ว</td>
                                </tr>
                                <tr>
                                    <td>เอว</td>
                                    <td>28 นิ้ว</td>
                                    <td>30 นิ้ว</td>
                                </tr>
                                <tr>
                                    <td>สะโพก</td>
                                    <td>36 นิ้ว</td>
                                    <td>38 นิ้ว</td>
                                </tr>
                                <tr>
                                    <td>ความยาวไหล่</td>
                                    <td>34 นิ้ว</td>
                                    <td>35 นิ้ว</td>
                                </tr>
                                <tr>
                                    <td>ความยาวกระโปรง</td>
                                    <td>25 นิ้ว</td>
                                    <td>27 นิ้ว</td>
                                </tr>
                            </tbody>
                        </table>



                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
