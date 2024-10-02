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
        @php
            $reservation_now = App\Models\Reservation::where('status_completed', 0)
                ->where('dress_id', $orderdetail->dress_id)
                ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                ->first();
            if ($reservation_now->id == $orderdetail->reservation_id) {
                $check_open_button = true;
            } elseif ($reservation_now->id != $orderdetail->reservation_id) {
                $check_open_button = false;
            }

        @endphp


        <h4 class="mt-5"><strong>รายการ :
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
                            {{-- <div class="col-md-6" style="text-align: right ;">
                                <button type="button" class="btn btn-primary">อัพเดตสถานะการเช่า</button>
                            </div> --}}
                            <div class="col-md-6 text-right"
                                @if ($orderdetail->status_detail == 'ถูกจอง' && $check_button_updatestatusadjust == false && $check_open_button == true) style="display: block ; "
                                @else
                                    style="display: none ; " @endif>
                                <button class="btn" style="background: #3406dc; color: #ffffff;" data-toggle="modal"
                                    data-target="#updatestatus">อัพเดตสถานะการเช่า</button>
                            </div>


                            <div class="col-md-6 text-right"
                                @if ($orderdetail->status_detail == 'กำลังเช่า') style="display: block ; "@else style="display: none ; " @endif>
                                <button class="btn" style="background: #3406dc; color: #ffffff;" data-toggle="modal"
                                    data-target="#updatestatus_return">อัพเดตสถานะการเช่า</button>
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
                                <p>รายละเอียด : {{ $dress->dress_description }}</p>
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

                        <p><i class="bi bi-calendar"></i> วันที่นัดรับ - นัดคืน :
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

                            <div class="col-md-6" style="text-align: right ; "
                                @if ($check_button_updatestatusadjust == true && $check_open_button == true) style="display: block;" 
                                @else
                                    style="display: none ; " @endif>
                                <button class="btn btn-success" data-toggle='modal' data-target="#updatestatusadjust"
                                    type="button">ปรับแก้ไขนาดสำเร็จ</button>
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
                                @foreach ($dress_mea_adjust as $item)
                                    <tr>
                                        @php
                                            $dress_mea = App\Models\Dressmea::where('id', $item->dressmea_id)->first();
                                        @endphp
                                        <td>{{ $dress_mea->mea_dress_name }}</td>
                                        <td>{{ $item->new_size }}</td>


                                        @if ($dress_mea->current_mea != $item->new_size)
                                            <td style="color: #eb3131 ; ">
                                                ปรับแก้:จาก{{ $dress_mea->current_mea }}<i
                                                    class="bi bi-arrow-right"></i>{{ $item->new_size }}นิ้ว
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>



                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- modalปรับแก้ไขชุดสำเร็จ   --}}
    <div class="modal fade" id="updatestatusadjust" tabindex="-1" role="dialog" aria-labelledby="updatestatusadjustLabel"
        aria-hidden="true">
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
        aria-hidden="true">
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
                                    <td style="padding: 10px;">คุณ {{ $customer->customer_fname }}
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


<!-- ปุ่มสำหรับเปิด Modal คืนชุด -->
<button class="btn btn-success" id="returnButton" data-toggle="modal" data-target="#returnModal">คืนชุด</button>

<!-- Modal สำหรับยืนยันการคืนชุดพร้อมรายละเอียดเพิ่มเติม -->
<div class="modal fade" id="returnModal" tabindex="-1" role="dialog" aria-labelledby="returnModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #28a745; color: white;">
                <h5 class="modal-title" id="returnModalLabel" style="font-weight: bold; font-size: 1.5rem;">ยืนยันการคืนชุด</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- แสดงรายละเอียดการเช่าและการคืน -->
                <h6 class="mb-3">รายละเอียดการเช่าและการคืน:</h6>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th style="width: 30%; text-align: left; padding: 10px;">ชื่อลูกค้า:</th>
                            <td style="padding: 10px;">คุณ {{ $customer->customer_fname }} {{ $customer->customer_lname }}</td>
                        </tr>
                        <tr>
                            <th style="width: 30%; text-align: left; padding: 10px;">วันที่มารับชุดจริง:</th>
                            <td style="padding: 10px;">{{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM') }} {{ \Carbon\Carbon::parse($Date->pickup_date)->year + 543 }}</td>
                        </tr>
                        
                        <tr>
                            <th style="width: 30%; text-align: left; padding: 10px;">วันที่นัดคืน:</th>
                            <td style="padding: 10px;">{{ \Carbon\Carbon::parse($Date->return_date)->locale('th')->isoFormat('D MMM') }} {{ \Carbon\Carbon::parse($Date->return_date)->year + 543 }}</td>
                        </tr>
                        <tr>
                            <th style="width: 30%; text-align: left; padding: 10px;">วันที่มาคืนจริง:</th>
                            <td style="padding: 10px;">{{ \Carbon\Carbon::now()->locale('th')->isoFormat('D MMM') }} {{ \Carbon\Carbon::now()->year + 543 }}</td>
                        </tr>
                       
                        <tr>
                            <th style="width: 30%; text-align: left; padding: 10px;">จำนวนวันที่เช่าจริง:</th>
                            <td style="padding: 10px;">2 วัน</td>
                        </tr>
                        <tr>
                            <th style="width: 30%; text-align: left; padding: 10px;">ค่าธรรมเนียมการขยายระยะเวลา:</th>
                            <td style="padding: 10px;">0 บาท</td>
                        </tr>
                        <tr>
                            <th style="width: 30%; text-align: left; padding: 10px;">ค่าปรับส่งคืนชุดล่าช้า:</th>
                            <td style="padding: 10px;">0 บาท</td>
                        </tr>
                    </tbody>
                </table>

                <!-- ฟิลด์สำหรับพนักงานกรอกค่าธรรมเนียมการเสียหาย -->
                <h6 class="mb-3">กรอกข้อมูลค่าธรรมเนียม:</h6>
                <div class="form-group">
                    <label for="damageFee">ค่าธรรมเนียมความเสียหาย (หักจากประกัน):</label>
                    <input type="number" class="form-control" id="damageFee" placeholder="กรอกจำนวนเงิน" min="0" step="0.01">
                </div>

                <!-- ฟิลด์สำหรับเลือกสถานะการดำเนินการหลังคืนชุด -->
                <h6 class="mb-3">การดำเนินการหลังจากคืนชุด:</h6>
                <div class="form-group">
                    <label for="operationAfterReturn">เลือกการดำเนินการ:</label>
                    <select class="form-control" id="operationAfterReturn">
                        <option value="clean">ส่งซัก</option>
                        <option value="repair">ส่งซ่อม</option>
                    </select>
                </div>

                <!-- สรุปการชำระเงิน -->
                <h6 class="mt-4 mb-3">สรุปการชำระเงิน:</h6>
                <div class="alert alert-warning" style="font-size: 1.2rem; padding: 10px;">
                    <p>ยอดคงเหลือที่ต้องชำระ: <strong id="totalDue">0 บาท</strong></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background-color: #6c757d; border-color: #6c757d;">ยกเลิก</button>
                <button type="button" class="btn btn-primary" id="confirmReturnButton" style="background-color: #007bff; border-color: #007bff;">ยืนยันการคืนชุด</button>
            </div>
        </div>
    </div>
</div>







   
@endsection
