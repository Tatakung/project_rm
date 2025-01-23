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
    .list-group-item.active {
    z-index: 2;
    color: #000;
    background-color:rgb(226, 226, 226);
    border-color:rgb(234, 234, 234);
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
            style="max-width: 400px; height: 50px; width: 100%; margin: auto; background-color: #39d628; border: 2px solid #4fe227; ">
            <div class="modal-body" style="padding: 10px; display: flex; align-items: center; justify-content: center;">
                <p style="margin: 0; color: #ffffff;">{{ session('success') }}</p>
            </div>
        </div>
    </div>
</div>

<script>
    @if(session('fail'))
    setTimeout(function() {
        $('#showfail').modal('show');
    }, 500);
    @endif
</script>

<script>
    @if(session('success'))
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
    $Date = App\Models\Date::where('order_detail_id', $orderdetail->id)
    ->orderBy('created_at', 'desc')
    ->first();
    @endphp


    <h4 class="mt-2"><strong>รายการ : เช่าตัด{{ $orderdetail->type_dress }}</strong>
    </h4>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title">สถานะการเช่าตัดชุด</h5>
                        </div>

                        <div class="col-md-6 text-right"
                            @if ($orderdetail->status_detail != 'รอดำเนินการตัด') style="display: none;" @endif>
                            <button class="btn" style="background: #C28041; color: #ffffff;" data-toggle="modal"
                                data-target="#updatestatus">อัปเดตสถานะชุด</button>
                        </div>

                        <div class="col-md-6 text-right"
                            @if ($orderdetail->status_detail != 'เริ่มดำเนินการตัด') style="display: none;" @endif>
                            <a href="{{ route('storeTailoredDress', ['id' => $orderdetail->id]) }}" class="btn"
                                style="background: #C28041; color: #ffffff;">
                                บันทึกการตัดชุด
                            </a>
                        </div>

                        {{-- <div class="col-md-6 text-right"
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
                        <a href="{{ route('employee.cutadjust', ['id' => $orderdetail->id]) }}" class="btn"
                            style="background: #E3A499;">ชุดต้องมีการปรับแก้ไข</a>
                    </div> --}}









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
                                        <button type="button" class="btn " style="background-color:#DADAE3;"
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

                                    <div class="modal-header text-dark" style="background-color:#EAD8C0 ;">
                                        <h5 class="modal-title">ยืนยันการอัปเดตสถานะ</h5>
                                        <button type="button" class="close text-black" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        @if ($route_modal != null)
                                        {{-- เช็คเพื่อไม้ให้มันerror --}}
                                        <input type="hidden" name="round_id" value="{{ $route_modal->id }}">

                                        <h4 class="text-center font-weight-bold mb-4">
                                            ยืนยันการปรับแก้ชุดครั้งที่
                                            {{ $route_modal->round_number }}
                                        </h4>
                                        @php
                                        $show_edit_mea = App\Models\Dressmeasurementcutedit::where(
                                        'adjustment_round_id',
                                        $route_modal->id,
                                        )->get();
                                        @endphp
                                        @if ($show_edit_mea->count() > 0)
                                        <h5 class="mt-2">รายการที่ปรับแก้:</h5>
                                        <ul>
                                            @foreach ($show_edit_mea as $item)
                                            <li>{{ $item->name }} ปรับจาก {{ $item->old_size }} เป็น
                                                {{ $item->edit_new_size }}
                                            </li>
                                            <input type="hidden" name="adjust_id_[]"
                                                value="{{ $item->adjustment_id }}">
                                            <input type="hidden" name="new_size_[]"
                                                value="{{ $item->edit_new_size }}">
                                            @endforeach
                                        </ul>
                                        @endif
                                        @php
                                        $show_edit_decoration = App\Models\Decoration::where(
                                        'adjustment_round_id',
                                        $route_modal->id,
                                        )->get();
                                        @endphp
                                        @if ($show_edit_decoration->count() > 0)
                                        <h5 class="mt-4">รายการเพิ่มเติม:</h5>
                                        <ul>
                                            @foreach ($show_edit_decoration as $item)
                                            <li>{{ $item->decoration_description }}</li>
                                            @endforeach
                                        </ul>
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
                        aria-labelledby="updatestatus_to_deliverLabel" aria-hidden="true" data-backdrop="static">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form
                                    action="{{ route('employee.actionupdatestatuscutdress', ['id' => $orderdetail->id]) }}"
                                    method="POST">
                                    @csrf
                                    <div class="modal-header" style="background-color:#EAD8C0 ;">
                                        <h5 class="modal-title" id="readyToDeliverModalLabel">ยืนยันการส่งมอบชุด
                                        </h5>

                                    </div>
                                    <div class="modal-body">
                                        <h5 class="mb-3">รายละเอียดการสั่งตัด</h5>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <p><strong>ชื่อลูกค้า:</strong>
                                                    <span>คุณ{{ $customer->customer_fname }}
                                                        {{ $customer->customer_lname }}</span>
                                                </p>
                                                <p><strong>วันที่สั่งตัดชุด:</strong>
                                                    {{ \Carbon\Carbon::parse($orderdetail->created_at)->locale('th')->isoFormat('D MMM') }}
                                                    {{ \Carbon\Carbon::parse($orderdetail->created_at)->year + 543 }}

                                                    </span>
                                                </p>
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

                                        <h6 class="mt-4 mb-3">สรุปการชำระเงิน:</h6>
                                        <div class="alert alert-info"
                                            style="background-color: #e9f7f9; border-color: #bee5eb; color: #0c5460; font-size: 1.2rem; padding: 10px;">
                                            <p>ยอดคงเหลือที่ต้องชำระ: <strong
                                                    id="totalDue">{{ number_format($orderdetail->price - $orderdetail->deposit) }}
                                                    บาท</strong></p>
                                        </div>

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
                        aria-labelledby="confirmDeliveryModalLabel" aria-hidden="true" data-backdrop="static">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form
                                    action="{{ route('employee.actionupdatestatuscutdress', ['id' => $orderdetail->id]) }}"
                                    method="POST">
                                    @csrf

                                    <div class="modal-header " style="background-color:#EAD8C0 ;">
                                        <h5 class="modal-title" id="confirmDeliveryModalLabel">ยืนยันการส่งมอบชุด
                                        </h5>

                                    </div>
                                    <div class="modal-body">
                                        <h6 class="mb-3">รายละเอียดการสั่งตัด</h6>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <p><strong>ชื่อลูกค้า:</strong> คุณ{{ $customer->customer_fname }}
                                                    {{ $customer->customer_lname }}
                                                </p>
                                                <p><strong>วันที่สั่งตัดชุด:</strong>
                                                    {{ \Carbon\Carbon::parse($orderdetail->created_at)->locale('th')->isoFormat('D MMM') }}
                                                    {{ \Carbon\Carbon::parse($orderdetail->created_at)->year + 543 }}
                                                </p>
                                                <p><strong>ราคาตัดชุด:</strong>
                                                    {{ number_format($orderdetail->price, 2) }} บาท
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>กำหนดส่งมอบชุด:</strong>
                                                    {{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                                    {{ \Carbon\Carbon::parse($Date->pickup_date)->year + 543 }}
                                                </p>
                                                <p><strong>เงินมัดจำ:</strong>
                                                    {{ number_format($orderdetail->deposit, 2) }} บาท
                                                </p>
                                                {{-- <p><strong>ยอดคงเหลือ:</strong>
                                                            {{ number_format($orderdetail->price - $orderdetail->deposit, 2) }}
                                                บาท</p> --}}
                                            </div>
                                        </div>

                                        @if ($decoration->count() > 0)
                                        <h6 class="mt-3 mb-2">ค่าใช้จ่ายเพิ่มเติม</h6>
                                        <ul>
                                            @foreach ($decoration as $item)
                                            <li>{{ $item->decoration_description }}:
                                                {{ number_format($item->decoration_price, 2) }} บาท
                                            </li>
                                            @endforeach
                                        </ul>
                                        <p><strong>รวมค่าใช้จ่ายเพิ่มเติม:</strong>
                                            {{ number_format($decoration->sum('decoration_price')), 2 }} บาท
                                        </p>
                                        @endif

                                        {{-- <h6 class="mt-3 mb-2">สรุปยอดชำระ</h6>
                                                <p><strong>ยอดรวมทั้งหมด:</strong>
                                                    {{ number_format($orderdetail->price + $decoration->sum('decoration_price'), 2) }}
                                        บาท</p> --}}


                                        <h6 class="mt-4 mb-3">สรุปการชำระเงิน:</h6>
                                        <div class="alert alert-info"
                                            style="background-color: #e9f7f9; border-color: #bee5eb; color: #0c5460; font-size: 1.2rem; padding: 10px;">
                                            <p>ยอดคงเหลือที่ต้องชำระ: <strong
                                                    id="totalDue">{{ number_format($decoration->sum('decoration_price')), 2 }}
                                                    บาท</strong></p>
                                        </div>




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
                        <p>ตัดชุดเสร็จสิ้น</p>
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

                    @if (in_array('ตัดชุดเสร็จสิ้น', $list_status))
                    <div class="status-line "></div>
                    <div class="status-step text-center">
                        <div class="status-icon @if (in_array('ตัดชุดเสร็จสิ้น', $list_status)) active @endif">
                            <i class="fas fa-check"></i>
                        </div>
                        <p>
                            <a href="{{route('employee.ordertotaldetailshow',['id' => $orderdetail->id])}}">ดูรายการจอง</a>
                        </p>
                        <small>
                            <p>

                            </p>
                        </small>
                    </div>
                    @endif













                    {{-- พักไว้ก่อน ยังไม่แน่ใจว่าจะทำไหม ส่วนนี้อะ  --}}

                    @if (in_array('แก้ไขชุด', $list_status))
                    <div class="status-step text-center">
                        <div class="status-icon @if (in_array('แก้ไขชุด', $list_status)) active @endif">
                            <i class="fas fa-check"></i>
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
                            <i class="fas fa-check"></i>
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



















                    {{-- <div class="status-step text-center">
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
                </div> --}}







            </div>
        </div>
    </div>
</div>
</div>
<h3 class="mt-5 ">ข้อมูลการเช่าตัดชุด</h3>
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



                <p><i class="bi bi-calendar"></i> วันที่นัดรับ :
                    {{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                    {{ \Carbon\Carbon::parse($Date->pickup_date)->year + 543 }} <span id="span_still_one"></span>
                </p>

                <p><i class="bi bi-calendar"></i> วันที่นัดคืน :
                    {{ \Carbon\Carbon::parse($Date->return_date)->locale('th')->isoFormat('D MMM') }}
                    {{ \Carbon\Carbon::parse($Date->return_date)->year + 543 }} <span id="span_still_one"></span>
                </p>
                
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

            <p> ราคาเช่าตัดชุด (บาท) :
                {{ number_format($orderdetail->price, 2) }} บาท
            </p>

            <p> เงินมัดจำ (บาท) :
                {{ number_format($orderdetail->deposit, 2) }} บาท
            </p>

            <p> ประกันชุด (บาท) :
                {{ number_format($orderdetail->damage_insurance, 2) }} บาท
            </p>
            <p> สถานะ : @if ($orderdetail->status_payment == 1)
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
        <div class="card shadow">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="card-title">ข้อมูลการวัดตัวสำหรับเช่าตัดชุด (นิ้ว)</h5>
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
                {{-- <div class="row mb-3">
                            <div class="col-md-12">
                                <p><strong>แก้ไขและปรับขนาดชุดครั้งที่ 1 </strong></p>
                                <li>ปรับความยาวกระโปรงจาก 24 -> 26.5 นิ้ว</li>
                                <li>ปรับไหล่กว้างจาก 26.00 -> 25.45 นิ้ว</li>
                                <li>รอบคอ 12.00 -> 13.50 นิ้ว</li>
                            </div>
                            <div class="col-md-12">
                                <p>รายละเอียดที่ต้องแก้ไข</p>
                                <span>จะต้องมีการขยับส่วนของส่วนหัวไหล่เข้ามาอีกสัก 5 เซน เพื่อให้มันประชิดกับรอบหอ</span>
                            </div>
                            <div class="col-md-12">
                                <p>รายการเพิ่มเติม(ถ้ามี)</p>
                                <li>เพิ่มลูกไม้ตรงบริเวณหัวไหล่ 50 บาท</li>
                                <li>เพิ่ม</li>
                            </div>
                        </div> --}}








            </div>
        </div>
    </div>
</div>
@endif


<div class="row mt-3">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">วันนัดลองชุด</h5>
                            <button type="button" class="btn btn-secondary" data-toggle="modal"
                                data-target="#modaladdfitting"
                                @if($check_button_add_fitting_image)
                                style="display: none ; "
                                @else
                                style="display: block ; "
                                @endif>เพิ่มวันนัดลองชุด</button>
                        </div>
                    </div>
                </div>

                @if($fitting->count() > 0 )
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>วันที่</th>
                            <th>สถานะ</th>
                            <th>ดูรายละเอียด</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fitting as $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->fitting_date)->locale('th')->isoFormat('D MMM') }}
                                {{ \Carbon\Carbon::parse($item->fitting_date)->year + 543 }}
                                <span style="color: red ;font-size: 14px; "
                                    id="showfittingdate{{ $item->id }}"></span>
                                <script>
                                    var now = new Date();
                                    var datefitting = new Date('{{ $item->fitting_date }}');
                                    var fitting_total_day = Math.ceil((datefitting - now) / (1000 * 60 * 60 * 24));

                                    if (fitting_total_day == 0) {
                                        document.getElementById('showfittingdate{{ $item->id }}').innerHTML = 'วันนี้';
                                    } else if (fitting_total_day > 0) {
                                        document.getElementById('showfittingdate{{ $item->id }}').innerHTML = 'อีก ' + fitting_total_day + ' วัน';
                                    }
                                </script>

                            </td>
                            <td>{{ $item->fitting_status }}</td>
                            <td>

                            <div @if ($item->fitting_status == 'มาลองชุดแล้ว') 
                                style="display: block ; "
                            @else
                                style="display: none ; " 
                            @endif
>
                                <button type="button" class="btn btn-sm"style="background-color:#DADAE3;" data-toggle="modal"
                                    data-target="#modalfitting_show{{ $item->id }}"
                                    
                                    >
                                     ดูรายละเอียด
                                </button>
                            </div>

                            

                            <div @if ($item->fitting_status == 'ยังไม่มาลองชุด') 
                                style="display: block ; "
                            @else
                                style="display: none ; " 
                            @endif>
                                <a href="{{ route('rentcutmakingdress', ['id' => $item->id, 'order_detail_id' => $orderdetail->id]) }}"
                                    class="btn btn-sm"style="background-color:#ACE6B7"
                                    @if ($item->fitting_status == 'ยังไม่มาลองชุด') style="display: block ; "
                                    @else
                                    style="display: none ; " @endif>
                                     ทำรายการ
                                </a>
                            </div>


                            </td>


                            <div class="modal fade" id="modalfitting_show{{ $item->id }}" tabindex="-1"
                                aria-hidden="true" data-backdrop="static">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content shadow-lg">
                                        {{-- Modal Header --}}
                                        <div class="modal-header py-3">
                                            <h5 class="modal-title font-weight-bold">
                                    
                                                รายละเอียดการลองชุด
                                                {{ \Carbon\Carbon::parse($item->fitting_date)->locale('th')->isoFormat('D MMM') }}
                                                {{ \Carbon\Carbon::parse($item->fitting_date)->year + 543 }}
                                            </h5>
                                            <button type="button" class="close text-black"
                                                data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        {{-- Measurement Adjustments --}}
                                        @php
                                        $mea_adjust_to_fitting = App\Models\Dressmeasurementcutedit::where(
                                        'fitting_id',
                                        $item->id,
                                        )->get();
                                        @endphp
                                        @if ($mea_adjust_to_fitting->isNotEmpty())
                                        <div class="px-4 pt-3">
                                            <h6 class=" mb-2">รายการข้อมูลการวัดที่ปรับ</h6>
                                            <ul class="list-group list-group-flush ">
                                                @foreach ($mea_adjust_to_fitting as $mea)
                                                <li
                                                    class="d-flex justify-content-between">
                                                    <span>ปรับ {{ $mea->name }}</span>
                                                    <span class=" ">
                                                        {{ $mea->old_size }} →
                                                        {{ $mea->edit_new_size }} นิ้ว
                                                    </span>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endif

                                        {{-- Modal Body --}}
                                        <div class="modal-body">
                                            {{-- Fitting Details --}}
                                            <div class="form-group mb-4">
                                                <label
                                                    class="font-weight-bold mb-2">รายละเอียดสำหรับลองชุด</label>
                                                <div class="card bg-light p-3">
                                                    {{ $item->fitting_note }}
                                                </div>
                                            </div>

                                            {{-- Extra Decorations --}}
                                            @php
                                            $Have_Extra = App\Models\Decoration::where(
                                            'fitting_id',
                                            $item->id,
                                            )->get();
                                            @endphp
                                            @if ($Have_Extra->isNotEmpty())
                                            <div class="mt-4">
                                                <h6 class=" mb-3">รายการเพิ่มเติมพิเศษ</h6>
                                                <div class="list-group">
                                                    @foreach ($Have_Extra as $extra)
                                                    <div
                                                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                        <span>{{ $extra->decoration_description }}</span>
                                                        <span class=" ">
                                                            {{ number_format($extra->decoration_price, 2) }}
                                                            บาท
                                                        </span>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif
                                        </div>

                                        {{-- Modal Footer --}}
                                        <!-- <div class="modal-footer">
                                            <button type="button" class="btn btn-danger"
                                                data-dismiss="modal">
                                                <i class="bi bi-x-circle mr-2"></i>ปิด
                                            </button>
                                        </div> -->
                                    </div>
                                </div>
                            </div>


                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p style="text-align: center ; ">ไม่ได้นัดลองชุดกับลูกค้า</p>
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
                            <h5 class="card-title">รูปภาพแสดงตัวแบบสำหรับเช่าตัด</h5>
                            <button type="button" class="btn btn-secondary" data-toggle="modal"
                                data-target="#add_image"
                                @if($check_button_add_fitting_image)
                                style="display: none ; "
                                @else
                                style="display: block ; "
                                @endif>
                                +เพิ่มรูปภาพ
                            </button>
                        </div>
                    </div>
                </div>

                @if($imagerent->count() > 0 )
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
                @else
                <div style="text-align: center ;">ไม่มีรูปภาพแสดง</div>
                @endif
            </div>
        </div>
    </div>
</div>

















<div class="row mt-3 d-flex align-items-stretch" id="div_show_net">
    <div class="col-md-12"
        @if ($orderdetail->status_detail == 'ส่งมอบชุดแล้ว') style="display: block;" @else style="display: none;" @endif>
        <div class="card shadow">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-file-earmark-text"></i> สรุปข้อมูลตัดชุด
                </h5>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>วันที่สั่งตัด:</strong>
                            {{ \Carbon\Carbon::parse($orderdetail->created_at)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($orderdetail->created_at)->year + 543 }}
                        </p>
                        <p><strong>วันที่รับชุดจริง:</strong>
                            {{ \Carbon\Carbon::parse($Date->actua_pickup_date)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($Date->actua_pickup_date)->year + 543 }}
                        </p>
                        {{-- <p><strong>จำนวนวันที่ทำการตัด:</strong> 
                                    15 วัน
                                </p> --}}
                    </div>
                    <div class="col-md-6">
                        <p><strong>ราคาตัดชุด:</strong> {{ number_format($orderdetail->price, 2) }} บาท</p>
                        <p><strong>ค่าใช้จ่ายเพิ่มเติม:</strong>
                            {{ number_format($decoration->sum('decoration_price'), 2) }} บาท
                        </p>
                        <p><strong>รายได้รวมทั้งหมด:</strong>
                            {{ number_format($orderdetail->price + $decoration->sum('decoration_price'), 2) }} บาท
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>






<div class="modal fade" role="dialog" aria-hidden="true" data-backdrop="static" id="add_image">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('employee.savecutdressaddimage', ['id' => $orderdetail->id]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">รูปภาพตัวแบบสำหรับการเช่าตัดชุด</h5>
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
                    <button class="btn" style="background-color:#DADAE3;"  type="button" data-dismiss="modal">ยกเลิก</button>
                    <button class="btn" style="background-color:#ACE6B7"  type="submit">ยืนยัน</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>





@php
$today = Carbon\Carbon::now()->toDateString();
@endphp

<div class="modal fade" id="modaladdfitting" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5>เพิ่มข้อมูลการนัด</h5>
            </div>
            <form action="{{ route('employee.actionaddfitting', ['id' => $orderdetail->id]) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>วันที่นัด:</strong></div>
                        <div class="col-md-8">
                            <input type="date" class="form-control" name="add_fitting_date"
                                max="{{ $Date->pickup_date }}" min="{{ $today }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn " style="background-color:#DADAE3;" type="button" data-dismiss="modal">ยกเลิก</button>
                    <button class="btn "style="background-color:#ACE6B7" type="submit">ยืนยัน</button>
                </div>
            </form>
        </div>
    </div>
</div>















@endsection