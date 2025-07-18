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
    




    <ol class="breadcrumb" style="background-color: transparent;">
        <li class="breadcrumb-item">
            <a href="{{ route('employee.ordertotal') }}" style="color: black ; ">รายการออเดอร์ทั้งหมด</a>
        </li>

        <li class="breadcrumb-item">
            <a href="{{ route('employee.ordertotaldetail', ['id' => $orderdetail->order_id]) }}" style="color: black ; ">รายการออเดอร์ที่ {{ $orderdetail->order_id }} </a>
        </li>
        
        <li class="breadcrumb-item active">
            รายละเอียดที่ {{ $orderdetail->id }}
        </li>
    </ol>



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
                style="max-width: 400px; height: 50px; width: 100%; margin: auto; background-color: #39d628; border: 2px solid #4fe227; ">
                <div class="modal-body" style="padding: 10px; display: flex; align-items: center; justify-content: center;">
                    <p style="margin: 0; color: #ffffff;">{{ session('success') }}</p>
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



    <div class="container mt-4">

        @php
            $Date = App\Models\Date::where('order_detail_id', $orderdetail->id)->orderBy('created_at', 'desc')->first();
        @endphp


        <h4 class="mt-2"><strong>รายการ : ตัด{{ $orderdetail->type_dress }}</strong>
        </h4>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="card-title">สถานะการตัดชุด</h5>
                            </div>

                            <div class="col-md-6 text-right"
                                @if ($orderdetail->status_detail != 'รอดำเนินการตัด') style="display: none;" @endif>
                                <button class="btn" style="background: #C28041; color: #ffffff;" data-toggle="modal"
                                    data-target="#updatestatus">อัปเดตสถานะชุด</button>
                            </div>

                            <div class="col-md-6 text-right"
                                @if ($orderdetail->status_detail != 'เริ่มดำเนินการตัด') style="display: none;" @endif>
                                <button class="btn" style="background: #C28041; color: #ffffff;" data-toggle="modal"
                                    data-target="#updatestatus">อัปเดตสถานะตัดชุด</button>
                            </div>

                            <div class="col-md-6 text-right"
                                @if ($orderdetail->status_detail != 'ตัดชุดเสร็จสิ้น') style="display: none;" @endif>
                                <button class="btn" style="background: #ACE6B7;" data-toggle="modal"
                                    data-target="#updatestatus_to_deliver">ส่งมอบชุด</button>
                                <a href="{{ route('employee.cutadjust', ['id' => $orderdetail->id]) }}"class="btn"
                                    style="background: #E3A499;">ชุดต้องมีการปรับแก้ไข</a>
                            </div>



                            <div class="col-md-6 text-right"
                                @if ($orderdetail->status_detail != 'แก้ไขชุดเสร็จสิ้น') style="display: none;" @endif>
                                <button class="btn" style="background-color:#ACE6B7;" data-toggle="modal"
                                    data-target="#updatestatus_to_deliver_after_edit">ส่งมอบชุด</button>
                                <a href="{{ route('employee.cutadjust', ['id' => $orderdetail->id]) }}"class="btn"
                                    style="background: #E3A499;">ชุดต้องมีการปรับแก้ไข</a>
                            </div>



                            <div class="col-md-6 text-right"
                                @if ($orderdetail->status_detail != 'แก้ไขชุด') style="display: none;" @endif>
                                <button class="btn" style="background: #3406dc; color: #ffffff;" data-toggle="modal"
                                    data-target="#modal_for_edit_dress">แก้ไขชุดเสร็จสิ้น</button>
                            </div>





                            <div id="updatestatus" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static"
                                data-keyboard="false">>
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form
                                            action="{{ route('employee.actionupdatestatuscutdress', ['id' => $orderdetail->id]) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-header text-dark" style="background-color:#EAD8C0 ;">
                                                <h5 class="modal-title">ยืนยันการอัปเดตสถานะ</h5>
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
                                                <button type="button" class="btn "style="background-color:#DADAE3;"
                                                    data-dismiss="modal">ยกเลิก</button>
                                                <button type="submit" class="btn" style="background-color:#ACE6B7;"
                                                    id="confirmUpdateStatus">ยืนยัน</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>



                            {{-- modalสำหรับแก้ไขชุดเสร็จสิ้น --}}
                            <div id="modal_for_edit_dress" class="modal fade" tabindex="-1" role="dialog"
                                data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content border-0">

                                        <form
                                            action="{{ route('employee.actionupdatestatuscutdress', ['id' => $orderdetail->id]) }}"
                                            method="POST">
                                            @csrf

                                            <div class="modal-header text-dark">
                                                <h5 class="modal-title">ยืนยันการปรับแก้ไข</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                @if ($route_modal != null)
                                                    {{-- เช็คเพื่อไม้ให้มันerror --}}
                                                    <input type="hidden" name="round_id"
                                                        value="{{ $route_modal->id }}">

                                                    <h4 class="text-center font-weight-bold mb-4">
                                                        ยืนยันการปรับแก้ชุดครั้งที่
                                                        {{ $route_modal->round_number }}</h4>
                                                    @php
                                                        $show_edit_mea = App\Models\Dressmeasurementcutedit::where(
                                                            'adjustment_round_id',
                                                            $route_modal->id,
                                                        )->get();
                                                    @endphp
                                                    @if ($show_edit_mea->count() > 0)
                                                        <h5 class="mt-2">รายการที่ปรับแก้:</h5>
                                                        {{-- <ul>
                                                            @foreach ($show_edit_mea as $item)
                                                                <li>{{ $item->name }} ปรับจาก {{ $item->old_size }} เป็น
                                                                    {{ $item->edit_new_size }}</li>
                                                                <input type="hidden" name="adjust_id_[]"
                                                                    value="{{ $item->adjustment_id }}">
                                                                <input type="hidden" name="new_size_[]"
                                                                    value="{{ $item->edit_new_size }}">
                                                            @endforeach
                                                        </ul> --}}

                                                        @foreach ($show_edit_mea as $item)
                                                            <div class="p-2 bg-light rounded">
                                                                <div class="d-flex justify-content-between">
                                                                    <span>{{ $item->name }} ปรับจาก
                                                                        {{ $item->old_size }} เป็น
                                                                        {{ $item->edit_new_size }}</span>

                                                                </div>
                                                            </div>
                                                        @endforeach




                                                    @endif
                                                    @php
                                                        $show_edit_decoration = App\Models\Decoration::where(
                                                            'adjustment_round_id',
                                                            $route_modal->id,
                                                        )->get();
                                                    @endphp
                                                    @if ($show_edit_decoration->count() > 0)
                                                        <h5 class="mt-4">รายการเพิ่มเติม:</h5>
                                                        {{-- <ul>
                                                            @foreach ($show_edit_decoration as $item)
                                                                <li>{{ $item->decoration_description }}</li>
                                                            @endforeach
                                                        </ul> --}}

                                                        @foreach ($show_edit_decoration as $item)
                                                            <div class="p-2 bg-light rounded">
                                                                <div class="d-flex justify-content-between">
                                                                    <span>{{ $item->decoration_description }}</span>

                                                                </div>
                                                            </div>
                                                        @endforeach


                                                    @endif
                                                @endif
                                            </div>

                                            <div class="modal-footer justify-content-end">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">ยกเลิก</button>
                                                <button type="submit" class="btn"
                                                    style="background-color:#ACE6B7;">ยืนยัน</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>










                            <!-- Modal -->
                            <div class="modal fade" id="updatestatus_to_deliver" tabindex="-1"
                                aria-labelledby="updatestatusLabel" aria-hidden="true" data-backdrop="static">
                                <div class="modal-dialog" style="max-width: 40% ; ">
                                    <div class="modal-content">
                                        <form
                                            action="{{ route('employee.actionupdatestatuscutdress', ['id' => $orderdetail->id]) }}"
                                            method="POST">
                                            @csrf

                                            <div class="modal-header">
                                                <h5 class="modal-title w-100 text-center" id="updatestatusLabel">
                                                    ยืนยันการส่งมอบชุด</h5>
                                            </div>


                                            <div class="modal-body">
                                                <div class="row mb-3">
                                                    <div class="col-6">
                                                        <strong>ชื่อลูกค้า:</strong> คุณ{{ $customer->customer_fname }}
                                                        {{ $customer->customer_lname }}
                                                    </div>

                                                </div>

                                                <div class="row mb-3">

                                                    <div class="col-6">
                                                        <strong>กำหนดวันส่งมอบชุด:</strong>
                                                        {{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                                        {{ \Carbon\Carbon::parse($Date->pickup_date)->year + 543 }}
                                                    </div>
                                                </div>


                                                <h6 class="fw-bold mb-3">รายละเอียดการชำระเงิน</h6>
                                                @if ($orderdetail->status_payment == 1)
                                                    <div class="p-3 bg-light rounded">
                                                        <div class="d-flex justify-content-between">
                                                            <span>ค่าตัดชุด:</span>
                                                            <span>
                                                                {{ number_format($orderdetail->price, 2) }} บาท
                                                            </span>
                                                        </div>
                                                    </div>




                                                    <div class="p-3 bg-light rounded mb-3">
                                                        <div class="d-flex justify-content-between">
                                                            <span>เงินมัดจำ: <span
                                                                    style="font-size: 14px; color: rgb(133, 126, 126) ;">(ชำระเมื่อ
                                                                    {{ \Carbon\Carbon::parse($orderdetail->created_at)->locale('th')->isoFormat('D MMM') }}
                                                                    {{ \Carbon\Carbon::parse($orderdetail->created_at)->year + 543 }}
                                                                    )</span></span>
                                                            <span>
                                                                {{ number_format($orderdetail->deposit, 2) }} บาท
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="p-4 bg-opacity-10 rounded mt-4"
                                                        style="background-color: #F0FFFF	 ; ">
                                                        <div class="d-flex justify-content-between fw-bold text-info">
                                                            <span style="color:#0000CD ; ">ยอดคงเหลือที่ต้องชำระ:</span>
                                                            <span class="fs-5"
                                                                style="color:#0000CD ; ">{{ number_format($orderdetail->price - $orderdetail->deposit + $orderdetail->damage_insurance, 2) }}
                                                                บาท</span>
                                                        </div>

                                                    </div>
                                                @elseif($orderdetail->status_payment == 2)
                                                    <div class="p-3 bg-light rounded">
                                                        <div class="d-flex justify-content-between">
                                                            <span>ค่าตัดชุด: <span
                                                                    style="font-size: 14px; color: rgb(133, 126, 126) ;">(ชำระเมื่อ
                                                                    {{ \Carbon\Carbon::parse($orderdetail->created_at)->locale('th')->isoFormat('D MMM') }}
                                                                    {{ \Carbon\Carbon::parse($orderdetail->created_at)->year + 543 }}
                                                                    )</span></span>
                                                            <span>
                                                                {{ number_format($orderdetail->price, 2) }} บาท
                                                            </span>
                                                        </div>
                                                    </div>





                                                    <div class="p-4 bg-opacity-10 rounded mt-4"
                                                        style="background-color: #F0FFFF	 ; ">
                                                        <div class="d-flex justify-content-between fw-bold text-info">
                                                            <span style="color:#0000CD ; ">ยอดคงเหลือที่ต้องชำระ:</span>
                                                            <span class="fs-5" style="color:#0000CD ; ">0.00
                                                                บาท</span>
                                                        </div>
                                                        <small class="text-muted" style="color:#0000CD ; ">
                                                            ชำระเงินครบเรียบร้อยแล้ว
                                                            <i class="text-success bi bi-check-circle ms-2"></i>
                                                        </small>
                                                    </div>
                                                @endif
                                            </div>



                                            <div class="modal-footer">
                                                <button type="button" class="btn" style="background-color:#DADAE3;"
                                                    data-dismiss="modal">ยกเลิก</button>
                                                <button type="submit" class="btn" style="background-color:#ACE6B7;"
                                                    id="confirmDelivery">ยืนยันการส่งมอบ</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>


                            <div class="modal fade" id="updatestatus_to_deliver_after_edit" tabindex="-1"
                                aria-labelledby="updatestatusLabel" aria-hidden="true" data-backdrop="static">
                                <div class="modal-dialog" style="max-width: 40% ; ">
                                    <div class="modal-content">

                                        <form
                                            action="{{ route('employee.actionupdatestatuscutdress', ['id' => $orderdetail->id]) }}"
                                            method="POST">
                                            @csrf


                                            <div class="modal-header">
                                                <h5 class="modal-title w-100 text-center" id="confirmDeliveryModalLabel">
                                                    ยืนยันการส่งมอบชุด</h5>
                                            </div>


                                            <div class="modal-body">
                                                <div class="row mb-3">
                                                    <div class="col-6">
                                                        <strong>ชื่อลูกค้า:</strong> คุณ{{ $customer->customer_fname }}
                                                        {{ $customer->customer_lname }}
                                                    </div>

                                                </div>

                                                <div class="row mb-3">

                                                    <div class="col-6">
                                                        <strong>วันที่นัดส่งมอบชุด:</strong>
                                                        {{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                                        {{ \Carbon\Carbon::parse($Date->pickup_date)->year + 543 }}
                                                    </div>
                                                </div>


                                                <h6 class="fw-bold mb-3">รายการเพิ่มเติม</h6>
                                                @if ($orderdetail->status_payment == 1)
                                                    <div class="p-3 bg-light rounded">
                                                        <div class="d-flex justify-content-between">
                                                            <span>ค่าตัด{{ $orderdetail->type_dress }}:</span>
                                                            <span>
                                                                {{ number_format($orderdetail->price, 2) }} บาท
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="p-3 bg-light rounded">
                                                        <div class="d-flex justify-content-between">
                                                            <span>เงินมัดจำ: <span
                                                                    style="font-size: 14px; color: rgb(133, 126, 126) ;">(ชำระเมื่อ
                                                                    {{ \Carbon\Carbon::parse($orderdetail->created_at)->locale('th')->isoFormat('D MMM') }}
                                                                    {{ \Carbon\Carbon::parse($orderdetail->created_at)->year + 543 }}
                                                                    )</span></span>
                                                            <span>
                                                                {{ number_format($orderdetail->deposit, 2) }} บาท
                                                            </span>
                                                        </div>
                                                    </div>
                                                @elseif($orderdetail->status_payment == 2)
                                                    <div class="p-3 bg-light rounded">
                                                        <div class="d-flex justify-content-between">
                                                            <span>ค่าตัด{{ $orderdetail->type_dress }}: <span
                                                                    style="font-size: 14px; color: rgb(133, 126, 126) ;">(ชำระเมื่อ
                                                                    {{ \Carbon\Carbon::parse($orderdetail->created_at)->locale('th')->isoFormat('D MMM') }}
                                                                    {{ \Carbon\Carbon::parse($orderdetail->created_at)->year + 543 }}
                                                                    )</span></span>
                                                            <span>
                                                                {{ number_format($orderdetail->price, 2) }} บาท
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endif


                                                @if ($decoration_sum > 0)
                                                    @if ($decoration->count() > 0)
                                                        @foreach ($decoration as $item)
                                                            <div class="p-3 bg-light rounded">
                                                                <div class="d-flex justify-content-between">
                                                                    <span>{{ $item->decoration_description }}:</span>
                                                                    <span>
                                                                        {{ number_format($item->decoration_price, 2) }} บาท
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif

                                                    @if ($orderdetail->status_payment == 1)
                                                        <div class="p-4 bg-opacity-10 rounded mt-4"
                                                            style="background-color: #F0FFFF	 ; ">
                                                            <div class="d-flex justify-content-between fw-bold text-info">
                                                                <span
                                                                    style="color:#0000CD ; ">ยอดคงเหลือที่ต้องชำระ:</span>
                                                                <span class="fs-5"
                                                                    style="color:#0000CD ; ">{{ number_format($decoration_sum + ($orderdetail->price - $orderdetail->deposit), 2) }}
                                                                    บาท</span>
                                                            </div>
                                                        </div>
                                                    @elseif($orderdetail->status_payment == 2)
                                                        <div class="p-4 bg-opacity-10 rounded mt-4"
                                                            style="background-color: #F0FFFF	 ; ">
                                                            <div class="d-flex justify-content-between fw-bold text-info">
                                                                <span
                                                                    style="color:#0000CD ; ">ยอดคงเหลือที่ต้องชำระ:</span>
                                                                <span class="fs-5"
                                                                    style="color:#0000CD ; ">{{ number_format($decoration_sum, 2) }}
                                                                    บาท</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @elseif($decoration_sum == 0)
                                                    @if ($orderdetail->status_payment == 1)
                                                        <div class="p-4 bg-opacity-10 rounded mt-4"
                                                            style="background-color: #F0FFFF	 ; ">
                                                            <div class="d-flex justify-content-between fw-bold text-info">
                                                                <span
                                                                    style="color:#0000CD ; ">ยอดคงเหลือที่ต้องชำระ:</span>
                                                                <span class="fs-5"
                                                                    style="color:#0000CD ; ">{{ number_format($orderdetail->price - $orderdetail->deposit, 2) }}
                                                                    บาท</span>
                                                            </div>
                                                        </div>
                                                    @elseif($orderdetail->status_payment == 2)
                                                        <div class="p-4 bg-opacity-10 rounded mt-4"
                                                            style="background-color: #F0FFFF	 ; ">
                                                            <div class="d-flex justify-content-between fw-bold text-info">
                                                                <span
                                                                    style="color:#0000CD ; ">ยอดคงเหลือที่ต้องชำระ:</span>
                                                                <span class="fs-5" style="color:#0000CD ; ">0.00
                                                                    บาท</span>
                                                            </div>
                                                            <small class="text-muted" style="color:#0000CD ; ">
                                                                ชำระเงินครบเรียบร้อยแล้ว
                                                                <i class="text-success bi bi-check-circle ms-2"></i>
                                                            </small>
                                                        </div>
                                                    @endif



                                                @endif
                                            </div>




                                            <div class="modal-footer">
                                                <button type="button" class="btn" style="background-color:#DADAE3;"
                                                    data-dismiss="modal">ยกเลิก</button>
                                                <button type="submit" class="btn " style="background-color:#ACE6B7;"
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




                            <div class="status-step text-center">
                                <div class="status-icon @if (in_array('รอดำเนินการตัด', $list_status)) active @endif">
                                    {{-- <i class="fas fa-check"></i> --}}
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


                            @if ($orderdetail->status_detail == 'ยกเลิกโดยลูกค้า' || $orderdetail->status_detail == 'ยกเลิกโดยทางร้าน')
                                @if ($check_cancel == false)
                                    <div class="status-step text-center">
                                        <div class="status-icon active" style="background: rgb(166, 32, 32) ; ">
                                            {{-- <i class="fas fa-check"></i> --}}
                                        </div>
                                        <p class="text-danger">ยกเลิกรายการ</p>
                                        <small>
                                            <p>
                                                @php
                                                    $created_at = App\Models\Orderdetailstatus::where(
                                                        'order_detail_id',
                                                        $orderdetail->id,
                                                    )
                                                        ->whereIn('status', ['ยกเลิกโดยทางร้าน', 'ยกเลิกโดยลูกค้า'])
                                                        ->first();
                                                    if ($created_at) {
                                                        $text_date = Carbon\Carbon::parse($created_at->created_at)
                                                            ->addHours(7)
                                                            ->format('d/m/Y H:i');
                                                    } else {
                                                        $text_date = 'รอดำเนินการ';
                                                    }
                                                @endphp
                                            <p class="text-danger">{{ $text_date }}</p>
                                            </p>
                                        </small>
                                    </div>
                                    <div class="status-line "></div>
                                @endif
                            @endif

                            <div class="status-step text-center">
                                <div class="status-icon @if (in_array('เริ่มดำเนินการตัด', $list_status)) active @endif">
                                    {{-- <i class="fas fa-check">ยกเลิก</i> --}}
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

                            @if ($orderdetail->status_detail == 'ยกเลิกโดยลูกค้า' || $orderdetail->status_detail == 'ยกเลิกโดยทางร้าน')
                                @if ($check_cancel == true)
                                    <div class="status-step text-center">
                                        <div class="status-icon active" style="background: rgb(166, 32, 32) ; ">
                                            {{-- <i class="fas fa-check"></i> --}}
                                        </div>
                                        <p class="text-danger">ยกเลิกรายการ</p>
                                        <small>
                                            <p>
                                                @php
                                                    $created_at = App\Models\Orderdetailstatus::where(
                                                        'order_detail_id',
                                                        $orderdetail->id,
                                                    )
                                                        ->whereIn('status', ['ยกเลิกโดยทางร้าน', 'ยกเลิกโดยลูกค้า'])
                                                        ->first();
                                                    if ($created_at) {
                                                        $text_date = Carbon\Carbon::parse($created_at->created_at)
                                                            ->addHours(7)
                                                            ->format('d/m/Y H:i');
                                                    } else {
                                                        $text_date = 'รอดำเนินการ';
                                                    }
                                                @endphp
                                            <p class="text-danger">{{ $text_date }}</p>
                                            </p>
                                        </small>
                                    </div>
                                    <div class="status-line "></div>
                                @endif

                            @endif


                            <div class="status-step text-center">
                                <div class="status-icon @if (in_array('ตัดชุดเสร็จสิ้น', $list_status)) active @endif">
                                    {{-- <i class="fas fa-check"></i> --}}
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







                            @if (in_array('แก้ไขชุด', $list_status))
                                <div class="status-step text-center">
                                    <div class="status-icon @if (in_array('แก้ไขชุด', $list_status)) active @endif">
                                        {{-- <i class="fas fa-check"></i> --}}
                                    </div>
                                    <p>แก้ไขชุด</p>
                                    <small>
                                        <p>
                                            {{-- @php
                                                $created_at = App\Models\Orderdetailstatus::where(
                                                    'order_detail_id',
                                                    $orderdetail->id,
                                                )
                                                    ->where('status', 'แก้ไขชุด')
                                                    ->orderBy('created_at', 'desc')
                                                    ->first();
                                                if ($created_at) {
                                                    $text_date = Carbon\Carbon::parse($created_at->created_at)
                                                        ->addHours(7)
                                                        ->format('d/m/Y H:i');
                                                } else {
                                                    $text_date = 'รอดำเนินการ';
                                                }
                                            @endphp
                                            {{ $text_date }} --}}


                                            @foreach ($round as $item)
                                                <p><span style="color: #a01919">ครั้งที่ {{ $item->round_number }}</span>
                                                    :
                                                    {{ Carbon\Carbon::parse($item->created_at)->addHours(7)->format('d/m/Y H:i') }}
                                                </p>
                                            @endforeach

                                        </p>
                                    </small>
                                </div>
                                <div class="status-line "></div>
                            @endif

                            @if (in_array('แก้ไขชุด', $list_status))
                                <div class="status-step text-center">
                                    <div class="status-icon @if (in_array('แก้ไขชุดเสร็จสิ้น', $list_status)) active @endif">
                                        {{-- <i class="fas fa-check"></i> --}}
                                    </div>
                                    <p>แก้ไขชุดเสร็จสิ้น (รอส่งมอบ)</p>
                                    <small>
                                        <p>
                                            {{-- @php
                                                $created_at = App\Models\Orderdetailstatus::where('order_detail_id',$orderdetail->id
                                                )
                                                    ->where('status', 'แก้ไขชุดเสร็จสิ้น')
                                                    ->orderBy('created_at', 'desc')
                                                    ->first();
                                                if ($created_at) {
                                                    $text_date = Carbon\Carbon::parse($created_at->created_at)
                                                        ->addHours(7)
                                                        ->format('d/m/Y H:i');
                                                } else {
                                                    $text_date = 'รอดำเนินการ';
                                                }
                                            @endphp
                                            {{ $text_date }} --}}
                                            @php
                                                $status_finish_edit = App\Models\Orderdetailstatus::where(
                                                    'order_detail_id',
                                                    $orderdetail->id,
                                                )
                                                    ->whereIn('status', ['แก้ไขชุดเสร็จสิ้น'])
                                                    ->orderBy('created_at', 'asc')
                                                    ->get();
                                            @endphp
                                            @if ($status_finish_edit->count() > 0)
                                                @foreach ($status_finish_edit as $index => $item)
                                                    <p><span style="color: #a01919">ครั้งที่ {{ $index + 1 }}</span> :
                                                        {{ Carbon\Carbon::parse($item->created_at)->addHours(7)->format('d/m/Y H:i') }}
                                                    </p>
                                                @endforeach
                                            @else
                                                รอดำเนินการ
                                            @endif
                                        </p>
                                    </small>
                                </div>
                                <div class="status-line "></div>
                            @endif



















                            <div class="status-step text-center">
                                <div class="status-icon @if (in_array('ส่งมอบชุดแล้ว', $list_status)) active @endif">
                                    {{-- <i class="fas fa-check"></i> --}}
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

        @if ($receipt_two)
            <div class="list-group-item shadow-sm mb-3 d-flex justify-content-between align-items-center mt-3">
                <div>
                    <p class="mb-1">ใบเสร็จรับชุด</p>
                    <p class="mb-1" style="font-size: 14px; color: #6c757d ; ">วันที่ออกใบเสร็จ:
                        {{ Carbon\Carbon::parse($receipt_two->created_at)->locale('th')->isoFormat('D MMM') }}
                        {{ Carbon\Carbon::parse($receipt_two->created_at)->year + 543 }}
                        

                    </p>
                </div>
                <a href="{{route('receiptpickupcutdress',['id' => $orderdetail->id])}}" target="_blank" class="btn btn-sm" style="background-color:#DADAE3;"
                    tabindex="-1">พิมพ์ใบเสร็จ</a>
            </div>
        @endif
        <div class="row mt-3 d-flex align-items-stretch" id="div_show_net">
            <div class="col-md-12"
                @if ($orderdetail->status_detail == 'ส่งมอบชุดแล้ว') style="display: block;" 
                @else 
                    style="display: none;" @endif>
                <div class="card shadow-sm">
                    <!-- หัวข้อการ์ด -->
                    <div class="card-header bg-light border-bottom d-flex align-items-center">
                        <div class="border-4 border-primary rounded me-2" style="width: 4px; height: 20px;"></div>
                        <h5 class="card-title mb-0">
                            <i class="bi bi-file-earmark-text"></i> สรุปข้อมูลการตัดชุด
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
                                        <span class="text-secondary">วันที่รับชุดจริง</span>
                                        <span
                                            class="fw-medium">{{ \Carbon\Carbon::parse($Date->actua_pickup_date)->locale('th')->isoFormat('D MMM') }}
                                            {{ \Carbon\Carbon::parse($Date->actua_pickup_date)->year + 543 }}</span>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">


                                    </div>
                                </div>
                            </div>

                            <!-- ข้อมูลการเงิน -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-center text-dark mb-3">

                                    <span class="fw-medium">ข้อมูลการเงิน</span>
                                </div>
                                <div class="ms-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-secondary">รายได้ค่าเช่าชุด</span>
                                        <span class="fw-medium text-secondary">{{ number_format($orderdetail->price, 2) }}
                                            บาท</span>
                                    </div>



                                    @if ($decco->count() > 0)
                                        @foreach ($decco as $item)
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="text-secondary"> {{ $item->decoration_description }} </span>
                                                <span
                                                    class="fw-medium text-secondary">{{ number_format($item->decoration_price, 2) }}
                                                    บาท</span>
                                            </div>
                                        @endforeach
                                    @endif



                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <span class="text-secondary fw-medium"><strong>รายได้รวมทั้งหมด</strong></span>

                                        <span
                                            class="fw-medium fs-5">{{ number_format($orderdetail->price + $decoration_sum, 2) }}
                                            บาท</span>

                                    </div>
                                </div>




                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <h3 class="mt-5 ">ข้อมูลการตัดชุด</h3>
        <div class="row mt-3 d-flex align-items-stretch">

            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        @php
                            $customer_id = App\Models\Order::where('id', $orderdetail->order_id)->value('customer_id');
                            $customer = App\Models\Customer::find($customer_id);
                        @endphp
                        <p><span class="bi bi-person"></span> ชื่อลูกค้า : คุณ{{ $customer->customer_fname }}
                            {{ $customer->customer_lname }}
                        </p>



                        <p><i class="bi bi-calendar"></i> วันที่นัดส่งมอบชุด :
                            {{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($Date->pickup_date)->year + 543 }} <span id="span_still_one"></span>
                        </p>

                        <p><i class="bi bi-file-earmark-text"></i> จำนวนชุด : {{ $orderdetail->amount }} ชุด</p>
                        <!-- ใช้ไอคอน file-earmark-text แทนจำนวนชุด -->

                        @php
                            $user_id = App\Models\Order::where('id', $orderdetail->order_id)->value('user_id');
                            $user = App\Models\User::find($user_id);
                        @endphp
                        <p><span class="bi bi-person"></span> พนักงานผู้รับออเดอร์ : คุณ{{ $user->name }}
                            {{ $user->lname }}
                        </p>



                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">

                        @php
                            $customer_id = App\Models\Order::where('id', $orderdetail->order_id)->value('customer_id');
                            $customer = App\Models\Customer::find($customer_id);
                        @endphp


                        <p>ราคาตัดชุด (บาท) :
                            {{ number_format($orderdetail->price, 2) }} บาท
                        </p>

                        <p>เงินมัดจำ (บาท) :
                            {{ number_format($orderdetail->deposit, 2) }} บาท
                        </p>

                        <p>ผ้า :
                            @if ($orderdetail->cloth == 1)
                                ลูกค้านำผ้ามาเอง
                            @elseif($orderdetail->cloth == 2)
                                ทางร้านหาผ้าให้
                            @endif
                        </p>


                        <p>สถานะ : @if ($orderdetail->status_payment == 1)
                                ชำระเงินมัดจำแล้ว
                            @elseif($orderdetail->status_payment == 2)
                                ชำระเงินเต็มจำนวนแล้ว
                            @endif
                        </p>

                        @php
                            $user_id = App\Models\Order::where('id', $orderdetail->order_id)->value('user_id');
                            $user = App\Models\User::find($user_id);
                        @endphp





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
                                <h5 class="card-title">ข้อมูลการวัดตัวสำหรับตัดชุด (นิ้ว)</h5>
                            </div>
                        </div>

                        <div class="row mb-3">
                            @foreach ($dress_adjusts as $item)
                                <div class="col-md-3 d-flex mt-3 ">
                                    <div class="col-md-12"><strong>{{ $item->name }} </strong> :{{ $item->new_size }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if ($orderdetail->note != null)
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h5 class="card-title">หมายเหตุ</h5>
                                    -{{ $orderdetail->note }}
                                </div>
                            </div>
                        @endif


                    </div>
                </div>
            </div>
        </div>


        <div class="row mt-3 mb-3">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <h5 class="card-title">รูปภาพแสดงตัวแบบสำหรับ</h5>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#add_image"
                                        @if ($is_admin == 1 && $orderdetail->status_detail != 'ส่งมอบชุดแล้ว') @if ($orderdetail->status_detail == 'ยกเลิกโดยลูกค้า' || $orderdetail->status_detail == 'ยกเลิกโดยทางร้าน')
                                            style="display: none ; "
                                            @else
                                            style="display: block ; " @endif
                                    @elseif($who_login == $person_order && $orderdetail->status_detail != 'ส่งมอบชุดแล้ว')
                                        @if ($orderdetail->status_detail == 'ยกเลิกโดยลูกค้า' || $orderdetail->status_detail == 'ยกเลิกโดยทางร้าน') style="display: none ; "
                                        @else
                                        style="display: block ; " @endif
                                    @else style="display: none ; " @endif>
                                        +เพิ่มรูปภาพ
                                    </button>
                                </div>
                            </div>
                        </div>


                        @if ($imagerent->count() > 0)
                            <div class="row mb-3">
                                @foreach ($imagerent as $item)
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card h-100 shadow-sm">
                                            <img src="{{ asset($item->image) }}" alt="Image description"
                                                style="width: 100%; height: 300px;">

                                        </div>
                                        <div class="card-body">
                                            <p class="card-text">หมายเหตุ :{{ $item->description }}</p>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        @else
                            <div class=" d-flex align-items-center mt-2">
                                <p>ไม่มีรูปภาพสำหรับตัวแบบในการเช่าตัดชุด</p>
                            </div>
                        @endif


                    </div>
                </div>
            </div>
        </div>





        @if ($round->count() > 0)
            <div class="row mt-3 mb-3">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="card-title">การแก้ไขและเพิ่มเติม (หากมีการแก้ไข)</h5>
                                </div>
                            </div>


                            @foreach ($round as $item)
                                @php
                                    $user_to_edit = App\Models\User::find($item->user_id);
                                @endphp


                                <p><strong>แก้ไขชุดครั้งที่ {{ $item->round_number }}</strong></p>
                                @if ($user_to_edit != null)
                                    <p>-พนักงานที่ปรับแก้ : คุณ{{ $user_to_edit->name }} {{ $user_to_edit->lname }}</p>
                                @endif
                                @php
                                    $mea_adjust = App\Models\Dressmeasurementcutedit::where(
                                        'adjustment_round_id',
                                        $item->id,
                                    )->get();
                                @endphp


                                @if ($mea_adjust->count() > 0)
                                    <div class="col-md-12">
                                        @foreach ($mea_adjust as $ad)
                                            <li>ปรับ {{ $ad->name }} จาก {{ $ad->old_size }} เป็น
                                                {{ $ad->edit_new_size }} นิ้ว
                                            </li>
                                        @endforeach
                                    </div>
                                @endif

                                @php
                                    $decoration = App\Models\Decoration::where('adjustment_round_id', $item->id)->get();

                                @endphp

                                @if ($decoration->count() > 0)
                                    <div class="col-md-12 mt-4">
                                        <p>รายการเพิ่มเติม</p>
                                        @foreach ($decoration as $dec)
                                            <li>{{ $dec->decoration_description }} {{ $dec->decoration_price }}บาท</li>
                                        @endforeach
                                    </div>
                                @endif
                            @endforeach



                        </div>
                    </div>
                </div>
            </div>
        @endif








        <div class="modal fade" role="dialog" aria-hidden="true" data-backdrop="static" id="add_image">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form action="{{ route('employee.savecutdressaddimage', ['id' => $orderdetail->id]) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header" style="background-color:#EAD8C0 ;">
                            <h5 class="modal-title">รูปภาพตัวแบบสำหรับการตัดชุด</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="col-md-12" id="div_image1">
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <div class="card shadow">
                                            <div class="card-body ">
                                                <input type="file" id="file_image" name="file_image"
                                                    class="form-control mb-3" accept="image/*" required>
                                                <textarea class="form-control" name="note_image" required placeholder="ใส่รายละเอียดเกี่ยวกับรูปภาพ..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">ยกเลิก</button>
                            <button class="btn " style="background-color:#ACE6B7;" type="submit">ยืนยัน</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
