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
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="">หน้าแรก</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employee.ordertotal') }}">รายการออเดอร์ทั้งหมด</a></li>
        <li class="breadcrumb-item"><a
                href="{{ route('employee.ordertotaldetail', ['id' => $orderdetail->order_id]) }}">รายละเอียดออเดอร์ที่
                {{ $orderdetail->order_id }}</a></li>
        <li class="breadcrumb-item active">{{ $orderdetail->title_name }}</li>
    </ol>

    <div class="container mt-4">

        @php
            $Date = App\Models\Date::where('order_detail_id', $orderdetail->id)
                ->orderBy('created_at', 'asc')
                ->first();
        @endphp


        <h4 class="mt-2"><strong>รายการ : ตัด{{ $orderdetail->type_dress }}</strong>
        </h4>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="card-title">สถานะการตัดเย็บชุด</h5>
                            </div>

                            <div class="col-md-6 text-right"
                                @if ($orderdetail->status_detail != 'รอดำเนินการตัด') style="display: none;" @endif>
                                <button class="btn" style="background: #3406dc; color: #ffffff;" data-toggle="modal"
                                    data-target="#updatestatus">อัพเดตสถานะตัดเย็บ</button>
                            </div>

                            <div class="col-md-6 text-right"
                                @if ($orderdetail->status_detail != 'เริ่มดำเนินการตัด') style="display: none;" @endif>
                                <button class="btn" style="background: #3406dc; color: #ffffff;" data-toggle="modal"
                                    data-target="#updatestatus">อัพเดตสถานะตัดเย็บ</button>
                            </div>

                            <div class="col-md-6 text-right"
                                @if ($orderdetail->status_detail != 'ตัดชุดเสร็จสิ้น') style="display: none;" @endif>
                                <button class="btn" style="background: #24a30e; color: #ffffff;" data-toggle="modal"
                                    data-target="#updatestatus_to_deliver">ชุดเรียบร้อย</button>
                                <a href="{{ route('employee.cutadjust', ['id' => $orderdetail->id]) }}"class="btn"
                                    style="background: #a01919; color: #ffffff;">ชุดต้องมีการปรับแก้ไข</a>
                            </div>









                            <div id="updatestatus" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static"
                                data-keyboard="false">>
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form
                                            action="{{ route('employee.actionupdatestatuscutdress', ['id' => $orderdetail->id]) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">ยืนยันการอัพเดตสถานะ</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                @if ($orderdetail->status_detail == 'รอดำเนินการตัด')
                                                    <p>คุณกำลังจะเปลี่ยนสถานะจาก "รอดำเนินการตัด" เป็น "เริ่มดำเนินการตัด"
                                                    </p>
                                                    <p>คุณแน่ใจหรือไม่ที่จะดำเนินการต่อ?</p>
                                                @elseif($orderdetail->status_detail == 'เริ่มดำเนินการตัด')
                                                    <p>คุณกำลังจะเปลี่ยนสถานะจาก "เริ่มดำเนินการตัด" เป็น "ตัดชุดเสร็จสิ้น"
                                                    </p>
                                                    <p>คุณแน่ใจหรือไม่ที่จะดำเนินการต่อ?</p>
                                                @endif
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">ยกเลิก</button>
                                                <button type="submit" class="btn btn-primary"
                                                    id="confirmUpdateStatus">ยืนยัน</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>



                            <!-- Modal -->
                            <div class="modal fade" id="updatestatus_to_deliver" tabindex="-1"
                                aria-labelledby="updatestatus_to_deliverLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form
                                            action="{{ route('employee.actionupdatestatuscutdress', ['id' => $orderdetail->id]) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title" id="readyToDeliverModalLabel">ยืนยันการส่งมอบชุด
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <h6 class="mb-3">รายละเอียดการสั่งตัด</h6>
                                                <div class="row mb-2">
                                                    <div class="col-md-6">
                                                        <p><strong>ชื่อลูกค้า:</strong>
                                                            <span>คุณ{{ $customer->customer_fname }}
                                                                {{ $customer->customer_lname }}</span>
                                                        </p>
                                                        <p><strong>วันที่สั่งตัดชุด:</strong>
                                                            {{ \Carbon\Carbon::parse($orderdetail->created_at)->locale('th')->isoFormat('D MMM') }}
                                                            {{ \Carbon\Carbon::parse($orderdetail->created_at)->year + 543 }}

                                                            </span></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>กำหนดส่งมอบชุด:</strong> <span>
                                                                {{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                                                {{ \Carbon\Carbon::parse($Date->pickup_date)->year + 543 }}
                                                            </span></p>
                                                        <p><strong>ระยะเวลาในการตัด:</strong>-<span></span></p>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <p><strong>ราคาตัดชุด:</strong>
                                                            <span>{{ $orderdetail->price }}</span> บาท
                                                        </p>
                                                        <p><strong>จ่ายแล้ว:</strong>
                                                            <span>{{ $orderdetail->deposit }}</span> บาท
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>คงเหลือ:</strong>
                                                            <span></span>{{ number_format($orderdetail->price - $orderdetail->deposit) }}
                                                            บาท
                                                        </p>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">ยกเลิก</button>
                                                <button type="submit" class="btn btn-success"
                                                    id="confirmDelivery">ยืนยันการส่งมอบ</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>


















                        </div>
                        <div class="status-timeline d-flex justify-content-between position-relative">



                            @php
                                $list_status = [];
                                foreach ($orderdetailstatus as $index => $status) {
                                    $list_status[] = $status->status;
                                }
                            @endphp
                            {{-- 
                                @foreach ($orderdetailstatus as $item)
                                    {{$item->status}}
                                @endforeach --}}





                            <div class="status-step text-center">
                                <div class="status-icon @if (in_array('รอดำเนินการตัด', $list_status)) active @endif">
                                    <i class="fas fa-check"></i>
                                </div>
                                <p>รอดำเนินการตัด</p>
                                <small>
                                    <p>
                                        @php
                                            $created_at = App\Models\Orderdetailstatus::where(
                                                'order_detail_id',
                                                $orderdetail->id,
                                            )
                                                ->where('status', 'รอดำเนินการตัด')
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
                                <div class="status-icon @if (in_array('เริ่มดำเนินการตัด', $list_status)) active @endif">
                                    <i class="fas fa-check"></i>
                                </div>
                                <p>เริ่มดำเนินการตัด</p>
                                <small>
                                    <p>
                                        @php
                                            $created_at = App\Models\Orderdetailstatus::where(
                                                'order_detail_id',
                                                $orderdetail->id,
                                            )
                                                ->where('status', 'เริ่มดำเนินการตัด')
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
                                <div class="status-icon @if (in_array('ตัดชุดเสร็จสิ้น', $list_status)) active @endif">
                                    <i class="fas fa-check"></i>
                                </div>
                                <p>ตัดชุดเสร็จสิ้น (รอส่งมอบ)</p>
                                <small>
                                    <p>
                                        @php
                                            $created_at = App\Models\Orderdetailstatus::where(
                                                'order_detail_id',
                                                $orderdetail->id,
                                            )
                                                ->where('status', 'ตัดชุดเสร็จสิ้น')
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

                            {{-- <div class="status-step text-center">
                                <div class="status-icon @if (in_array('ตัดชุดเสร็จสิ้น', $list_status)) active @endif">
                                    <i class="fas fa-check"></i>
                                </div>
                                <p>แก้ไขชุด</p>
                                <small>
                                    <p>
                                        @php
                                            $created_at = App\Models\Orderdetailstatus::where(
                                                'order_detail_id',
                                                $orderdetail->id,
                                            )
                                                ->where('status', 'แก้ไขชุด')
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

                            <div class="status-line "></div> --}}
                            <div class="status-step text-center">
                                <div class="status-icon @if (in_array('ส่งมอบชุดแล้ว', $list_status)) active @endif">
                                    <i class="fas fa-check"></i>
                                </div>
                                <p>ส่งมอบชุดแล้ว</p>
                                <small>
                                    <p>
                                        @php
                                            $created_at = App\Models\Orderdetailstatus::where(
                                                'order_detail_id',
                                                $orderdetail->id,
                                            )
                                                ->where('status', 'ส่งมอบชุดแล้ว')
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

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">ข้อมูลการตัดเย็บชุด</h5>
                        @php
                            $customer_id = App\Models\Order::where('id', $orderdetail->order_id)->value('customer_id');
                            $customer = App\Models\Customer::find($customer_id);
                        @endphp
                        <p><span class="bi bi-person"></span> ชื่อลูกค้า : คุณ{{ $customer->customer_fname }}
                            {{ $customer->customer_lname }}</p>


                        @php
                            $Date = App\Models\Date::where('order_detail_id', $orderdetail->id)
                                ->orderBy('created_at', 'asc')
                                ->first();
                        @endphp

                        <p><i class="bi bi-calendar"></i> วันที่นัดส่งมอบชุด :
                            {{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($Date->pickup_date)->year + 543 }} <span id="span_still_one"></span>
                        </p>
                        <script>
                            var pickup_date = new Date('{{ $Date->pickup_date }}');
                            var date_now = new Date();
                            var still_one = Math.ceil((pickup_date - date_now) / (1000 * 60 * 60 * 24));
                            document.getElementById('span_still_one').innerHTML = ' (เหลือเวลาอีก ' + still_one + ' วัน)';
                        </script>

                        <p><i class="bi bi-currency-dollar"></i> ราคาตัดชุด (บาท) :
                            {{ number_format($orderdetail->price, 2) }} บาท
                        </p>

                        <p><i class="bi bi-currency-dollar"></i> เงินมัดจำ (บาท) :
                            {{ number_format($orderdetail->deposit, 2) }} บาท
                        </p>

                        <p><i class="bi bi-file-earmark-text"></i> จำนวนชุด : {{ $orderdetail->amount }}</p>
                        <!-- ใช้ไอคอน file-earmark-text แทนจำนวนชุด -->

                        <p><i class="bi bi-text-left"></i> ผ้า :
                            @if ($orderdetail->cloth == 1)
                                ลูกค้านำผ้ามาเอง
                            @elseif($orderdetail->cloth == 2)
                                ทางร้านหาผ้าให้
                            @endif
                        </p>




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
                                <h5 class="card-title">ข้อมูลการวัดตัวของลูกค้า (นิ้ว)</h5>
                            </div>
                        </div>

                        <div class="row mb-3">
                            @foreach ($dress_adjusts as $item)
                                <div class="col-md-3 d-flex mt-3 ">
                                    <div class="col-md-12"><strong>{{ $item->name }} {{ $item->new_size }} </strong>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if ($orderdetail->note != null)
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h5 class="card-title">โน๊ต</h5>
                                    -{{ $orderdetail->note }}
                                </div>
                            </div>
                        @endif


                    </div>
                </div>
            </div>
        </div>

        @if($imagerent->count() > 0 )
        <div class="row mt-3 mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="card-title">รูปภาพแสดงตัวแบบสำหรับตัดเย็บ</h5>
                            </div>
                        </div>

                        <div class="row mb-3">
                            @foreach ($imagerent as $item)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 shadow-sm">
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="Image description"
                                            style="width: 100%; height: 300px;">
                                        <div class="card-body">
                                            <p class="card-text">{{ $item->description }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @endif


        <div class="row mt-3 mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="card-title">การแก้ไขและเพิ่มเติม (ร่างไว้คร่าวๆ)</h5>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <p><strong>แก้ไขครั้งที่ 1 </strong></p>
                                <p>ปรับความยาวกระโปรงจาก 24 -> 26.5 นิ้ว</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>









    </div>
@endsection
