@extends('layouts.adminlayout')

@section('content')
    <style>
        .btn-c {
            background-color: #EBDE88;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            color: black;
            border: none;
            font-weight: bold;
        }

        .btn-c:hover {
            background-color: #D4C66E;
        }

        .btn-postpone {
            background-color: #BACEE6;
            color: black;
            border: none;
            font-weight: bold;
        }

        .btn-postpone:hover {
            background-color: #A3B7D4;
        }

        .image-container img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s ease;
        }

        .image-container img:hover {
            transform: scale(1.1);
        }



        .breadcrumb {
            background-color: transparent;
            font-size: 1rem;
        }

        .breadcrumb a {
            text-decoration: none;
            color: #333;
        }

        .breadcrumb-item.active {
            color: #6c757d;
        }

        .container h3 {
            font-weight: bold;
            color: #343a40;
            margin-bottom: 1.5rem;
        }

        .table {
            border-collapse: collapse;
            /* ทำให้เส้นคั่นหายไป */
            margin-top: 1.5rem;
            border-radius: 8px;
            overflow: hidden;
        }

        .table thead {
            background-color: #f8f9fa;
            text-align: center;
            font-weight: bold;
        }

        .table thead th {
            border-bottom: none;
            /* ลบเส้นคั่นใต้หัวข้อ */
        }

        .table tbody tr {
            border: none;
            /* ลบเส้นคั่นแถว */
        }

        .table tbody td {
            border: none;
            /* ลบเส้นคั่นคอลัมน์ */
            vertical-align: middle;
            text-align: center;
        }

        .list-group-item.active {
            background-color: #F7F9FA !important;
            color: black !important;
            border-color: #DADAE3;
        }
    </style>

    <ol class="breadcrumb" style="background-color: transparent;">
        <li class="breadcrumb-item">
            <a href="{{ route('employee.ordertotal') }}" style="color: black ; ">รายการออเดอร์ทั้งหมด</a>
        </li>

        <li class="breadcrumb-item active">
            รายการออเดอร์ที่ {{ $order_id }}
        </li>
    </ol>



    <div class="container mt-4">
        <h3>รายละเอียดออเดอร์เช่าตัดชุด {{ $order_id }}</h3>
        <div class="row mb-4">
            <div class="col-md-6">
                <p><strong>ชื่อลูกค้า:</strong> คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }}</p>
                <p><strong>ชื่อพนักงานรับออเดอร์:</strong> คุณ{{ $employee->name }} {{ $employee->lname }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>วันที่ทำรายการ:</strong>
                    {{ \Carbon\Carbon::parse($order->created_at)->locale('th')->isoFormat('D MMM') }}
                    {{ \Carbon\Carbon::parse($order->created_at)->year + 543 }}
                    <span id="show_history_day" style="font-size: 14px; color: rgb(158, 143, 143) ; "></span>
                </p>
                {{-- <a href="{{ route('receiptdeposittotal', ['id' => $order_id]) }}" class="btn btn-primary btn-sm mt-2"
                    target="_blank">ใบเสร็จรับเงิน</a> --}}
            </div>
        </div>
        <script>
            var create_date_now = new Date();
            var create_order_date = new Date('{{ $order->created_at }}');
            var history_day = Math.ceil((create_date_now - create_order_date) / (1000 * 60 * 60 * 24) - 1);
            if (history_day == 0) {
                document.getElementById('show_history_day').innerHTML = '(วันนี้)';
            } else {
                document.getElementById('show_history_day').innerHTML = '(เมื่อ ' + history_day + ' วันที่แล้ว)';
            }
        </script>

        <button type="button"class="btn btn-c " data-toggle="modal" data-target="#show_modal_pickup_total"
            @if ($pass_one == true) @if ($pass_two == true) 
                    style="display: block ; background-color:#DADAE3;"
                @else
                    style="display: none ; background-color:#DADAE3;" @endif
        @else style="display: none ; background-color:#DADAE3;" @endif
            >
            รับชุด (ทั้งหมด)
        </button>

        <table class="table table-striped shadow-sm">
            <thead>
                <tr>
                    <th>รายการ</th>
                    <th>วันนัดรับ</th>
                    <th>วันนัดคืน</th>
                    <th>สถานะออเดอร์</th>
                    <th>การดำเนินการ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orderdetail as $index => $item)
                    <tr>
                        @php
                            $Date = App\Models\Date::where('order_detail_id', $item->id)
                                ->orderBy('created_at', 'desc')
                                ->first();
                        @endphp




                        <td>
                            @php
                                // เอาไว้ใช้ตอนที่ยกเลิกorder แล้วบอกว่า มันยกเลิหกวันไหน
                                $status_orderdetail = App\Models\Orderdetailstatus::where('order_detail_id', $item->id)
                                    ->orderBy('created_at', 'desc')
                                    ->first();
                            @endphp
                            เช่าตัด{{ $item->type_dress }}
                            <br>

                        </td>


                        @if ($item->status_detail == 'ยกเลิกโดยทางร้าน' || $item->status_detail == 'ยกเลิกโดยลูกค้า')
                            <td style="text-decoration: line-through;color: red;">
                                {{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                {{ \Carbon\Carbon::parse($Date->pickup_date)->year + 543 }}
                            </td>
                            <td style="text-decoration: line-through;color: red;">
                                {{ \Carbon\Carbon::parse($Date->return_date)->locale('th')->isoFormat('D MMM') }}
                                {{ \Carbon\Carbon::parse($Date->return_date)->year + 543 }}
                            </td>
                        @else
                            <td>{{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                {{ \Carbon\Carbon::parse($Date->pickup_date)->year + 543 }}
                            </td>
                            <td>{{ \Carbon\Carbon::parse($Date->return_date)->locale('th')->isoFormat('D MMM') }}
                                {{ \Carbon\Carbon::parse($Date->return_date)->year + 543 }}
                            </td>
                        @endif






                        <td>
                            @if ($item->status_detail == 'ยกเลิกโดยทางร้าน' || $item->status_detail == 'ยกเลิกโดยลูกค้า')
                                <span style="color: red ; ">{{ $item->status_detail }}</span>
                                <br>
                                <span style="color: red ; font-size: 12px;">ยกเลิกเมื่อ:
                                    {{ \Carbon\Carbon::parse($status_orderdetail->created_at)->locale('th')->isoFormat('D MMM') }}
                                    {{ \Carbon\Carbon::parse($status_orderdetail->created_at)->year + 543 }}
                                </span>
                            @else
                                {{ $item->status_detail }}
                            @endif
                        </td>


                        <td>


                            @php
                                $check_route_pass = App\Models\Orderdetailstatus::where('order_detail_id', $item->id)
                                    ->where('status', 'ตัดชุดเสร็จสิ้น')
                                    ->exists();
                                $check_reservation = App\Models\Orderdetailstatus::where('order_detail_id', $item->id)
                                    ->where('status', 'ถูกจอง')
                                    ->exists();
                            @endphp

                            @if (!$check_route_pass)
                                <a href="{{ route('detaildoingrentcut', ['id' => $item->id]) }}"
                                    class="btn btn-c btn-sm">จัดการ</a>
                            @else
                                @if ($check_reservation == true)
                                    <a href="{{ route('employee.ordertotaldetailshow', ['id' => $item->id]) }}"
                                        class="btn btn-c btn-sm">จัดการ</a>
                                @elseif($check_reservation == false)
                                    <a href="{{ route('detaildoingrentcut', ['id' => $item->id]) }}"
                                        class="btn btn-c btn-sm">จัดการ</a>
                                @endif
                            @endif




                            @if ($item->status_detail == 'ยกเลิกโดยลูกค้า' || $item->status_detail == 'ยกเลิกโดยทางร้าน')
                                {{-- style="display: none;" --}}
                            @else
                                @if ($item->status_detail == 'กำลังเช่า' || $item->status_detail == 'คืนชุดแล้ว')
                                    {{-- style="display: none;" --}}
                                @else
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                        data-target="#cancelModal{{ $item->id }}">ยกเลิก
                                    </button>
                                @endif
                            @endif









                        </td>




                        <div class="modal fade" id="cancelModal{{ $item->id }}" tabindex="-1"
                            aria-labelledby="cancelModal{{ $item->id }}" aria-hidden="true" data-backdrop="static">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title d-flex align-items-center gap-2"
                                            id="cancelModal{{ $item->id }}">
                                            <i class="fas fa-exclamation-triangle text-red-500"></i>
                                            ยกเลิกรายการ:
                                            เช่าตัด{{ $item->type_dress }}
                                        </h5>

                                    </div>

                                    <form action="{{ route('cancelorderrent', ['id' => $item->id]) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <p class="text-muted">กรุณาเลือกสาเหตุการยกเลิกรายการ</p>
                                            <!-- ยกเลิกโดยทางร้าน -->
                                            <div class="form-check border rounded-lg p-4 mb-3">
                                                <input class="form-check-input" type="radio" name="cancelType"
                                                    id="store" value="store" checked>
                                                <label class="form-check-label d-flex align-items-center gap-2"
                                                    for="store">
                                                    <i class="fas fa-store"></i>
                                                    ยกเลิกโดยทางร้าน
                                                </label>
                                                <div class="mt-2 text-muted">
                                                    <ul class="mb-2">
                                                        <li>ไม่สามารถเช่าตัดได้</li>
                                                        <li>ต้องแจ้งลูกค้าและคืนเงินมัดจำ 100% แก่ลูกค้า <span
                                                                style="font-size: 14px;">({{ number_format($item->deposit, 2) }}
                                                                บาท)</span></li>
                                                    </ul>
                                                    {{-- <textarea class="form-control" placeholder="ระบุรายละเอียดการยกเลิก..." rows="3"></textarea> --}}
                                                </div>

                                                <div class="mt-3 bg-yellow-50 border border-yellow-200 rounded p-3 d-none"
                                                    id="storeActions">
                                                    <div class="fw-bold text-yellow-800">รายการที่ต้องดำเนินการ:</div>
                                                    <ul class="mt-1 text-yellow-700">
                                                        <li>แจ้งลูกค้าเรื่องการยกเลิก</li>
                                                        <li>คืนเงินมัดจำให้ลูกค้า</li>
                                                        <li>ทำรายการยกเลิกในระบบ</li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <!-- ยกเลิกโดยลูกค้า -->
                                            <div class="form-check border rounded-lg p-4">
                                                <input class="form-check-input" type="radio" name="cancelType"
                                                    id="customer" value="customer">
                                                <label class="form-check-label d-flex align-items-center gap-2"
                                                    for="customer">
                                                    <i class="fas fa-user"></i>
                                                    ยกเลิกโดยลูกค้า
                                                </label>
                                                <div class="mt-2 text-muted">
                                                    <ul class="mb-2">
                                                        <li>ลูกค้าต้องการยกเลิกการจอง</li>
                                                        @if ($item->status_detail == 'รอดำเนินการตัด')
                                                            <li>ขณะนี้สถานะของรายการคือ {{ $item->status_detail }}
                                                                หากยกเลิกรายการจะไม่ริบเงินมัดจำลูกค้า <br>
                                                                เนื่องจากชุดยังไม่ได้เริ่มดำเนินการตัด</li>
                                                        @elseif($item->status_detail == 'เริ่มดำเนินการตัด')
                                                            <li>ขณะนี้สถานะของรายการคือ {{ $item->status_detail }} <br>
                                                                หากยกเลิกรายการจำเป็นต้องริบเงินมัดจำตามเงื่อนไข <span
                                                                    style="font-size: 14px;">({{ number_format($item->deposit, 2) }}
                                                                    บาท)</span></li>
                                                        @elseif($item->status_detail == 'ถูกจอง')
                                                            ขณะนี้สถานะของรายการคือ {{ $item->status_detail }}
                                                            ซึ่งรอลูกค้ามารับชุดเช่า <br>
                                                            หากยกเลิกรายการจำเป็นต้องริบเงินมัดจำตามเงื่อนไข <span
                                                                style="font-size: 14px;">({{ number_format($item->deposit, 2) }}
                                                                บาท)</span></li>
                                                        @endif



                                                    </ul>
                                                    {{-- <textarea class="form-control" placeholder="ระบุรายละเอียดการยกเลิก..." rows="3"></textarea> --}}
                                                </div>

                                                <div class="mt-3 bg-yellow-50 border border-yellow-200 rounded p-3 d-none"
                                                    id="customerActions">
                                                    <div class="fw-bold text-yellow-800">การจัดการเงินมัดจำ:</div>
                                                    <ul class="mt-1 text-yellow-700">
                                                        <li>ริบเงินมัดจำตามเงื่อนไขการจอง</li>
                                                        <li>บันทึกการยกเลิกในระบบ</li>
                                                    </ul>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">ยกเลิก</button>
                                            <button type="submit" class="btn btn-danger">ยืนยันการยกเลิก</button>
                                        </div>
                                    </form>


                                </div>
                            </div>
                        </div>




























                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>


    <div class="container mt-4 mb-4">

        <h3>ประวัติใบเสร็จ</h3>


        @if ($receipt_one)
            <div class="list-group-item shadow-sm mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1">ใบเสร็จ</p>
                    <p class="mb-1" style="font-size: 14px; color: #6c757d ; ">วันที่ออกใบเสร็จ:
                        {{ Carbon\Carbon::parse($receipt_one->created_at)->locale('th')->isoFormat('D MMM') }}
                        {{ Carbon\Carbon::parse($receipt_one->created_at)->year + 543 }}

                    </p>
                </div>
                <a href="{{ route('receiptreservation', ['id' => $order_id]) }}" target="_blank"
                    class="btn btn-sm "style="background-color:#DADAE3;" tabindex="-1">พิมพ์ใบเสร็จ</a>

            </div>
        @endif



        @if ($receipt_two)
            <div class="list-group-item shadow-sm mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1">ใบเสร็จ</p>
                    <p class="mb-1" style="font-size: 14px; color: #6c757d ; ">วันที่ออกใบเสร็จ:
                        {{ Carbon\Carbon::parse($receipt_two->created_at)->locale('th')->isoFormat('D MMM') }}
                        {{ Carbon\Carbon::parse($receipt_two->created_at)->year + 543 }}

                    </p>
                </div>
                <a href="{{ route('receiptpickuprent', ['id' => $order_id]) }}" target="_blank" class="btn btn-sm "
                    style="background-color:#DADAE3;" tabindex="-1">พิมพ์ใบเสร็จ</a>
            </div>
        @endif

        @if ($receipt_three)
            <div class="list-group-item shadow-sm mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1">ใบเสร็จ</p>
                    <p class="mb-1" style="font-size: 14px; color: #6c757d ; ">วันที่ออกใบเสร็จ:
                        {{ Carbon\Carbon::parse($receipt_three->created_at)->locale('th')->isoFormat('D MMM') }}
                        {{ Carbon\Carbon::parse($receipt_three->created_at)->year + 543 }}

                    </p>
                </div>
                <a href="{{ route('receiptreturnrent', ['id' => $order_id]) }}" target="_blank" class="btn btn-sm "
                    style="background-color:#DADAE3;" tabindex="-1">พิมพ์ใบเสร็จ</a>
            </div>
        @endif
    </div>





    <div class="modal fade" id="show_modal_pickup_total" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">รายละเอียดค่าใช้จ่าย</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <p><strong>ชื่อลูกค้า:</strong> คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }}</p>
                    <p><strong>วันที่นัดรับ:</strong>
                        {{ Carbon\Carbon::parse($date_only->pickup_date)->locale('th')->isoFormat('D MMM') }}
                        {{ Carbon\Carbon::parse($date_only->pickup_date)->year + 543 }}
                    </p>
                    <p><strong>วันที่นัดคืน:</strong>
                        {{ Carbon\Carbon::parse($date_only->return_date)->locale('th')->isoFormat('D MMM') }}
                        {{ Carbon\Carbon::parse($date_only->return_date)->year + 543 }}

                    </p>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>รายการ</th>
                                <th>ค่าเช่า</th>
                                <th>เงินประกัน</th>
                                <th @if ($only_payment == true) style="display: block ; "
                                    @elseif($only_payment == false){
                                        style="display: none;" @endif
                                    }>เงินมัดจำ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderdetail as $item)
                                <tr>
                                    <td>
                                        เช่าตัด{{ $item->type_dress }}
                                    </td>
                                    <td>{{ number_format($item->price, 2) }}</td>
                                    <td>{{ number_format($item->damage_insurance, 2) }}</td>


                                    @if ($only_payment == true)
                                        <td>{{ number_format($item->deposit, 2) }}</td>
                                    @endif


                                </tr>
                            @endforeach
                            <tr style="background-color:rgb(240, 240, 240);">
                                <td><strong>รวมทั้งหมด</strong></td>
                                <td> {{ number_format($total_price, 2) }} </td>
                                <td> {{ number_format($total_damage_insurance, 2) }} </td>
                                @if ($only_payment == true)
                                    <td>{{ number_format($total_deposit, 2) }}</td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                    <p><strong>ยอดคงเหลือที่ต้องชำระ: {{ number_format($remaining_balance, 2) }} บาท</strong></p>

                    {{-- ถ้ามันมีสิ่งที่เพิ่มเติมเข้ามาให้มันแสดงผลเลย --}}
                    @if ($decoration_sum > 0)
                        <ul>
                            @foreach ($orderdetail as $item)
                                @php
                                    $decoration = App\Models\Decoration::where('order_detail_id', $item->id)->get();
                                @endphp
                                @foreach ($decoration as $value)
                                    <li>{{ $value->decoration_description }} ราคา{{ $value->decoration_price }} บาท</li>
                                @endforeach
                            @endforeach

                        </ul>


                    @endif

                    @if ($only_payment == true)
                        <p style="font-size: 14px;">(ชำระค่ามัดจำไปแล้ว {{ number_format($total_deposit, 2) }} บาท)</p>
                    @endif



                </div>
                <form action="{{ route('updatestatuspickuptotalrent', ['id' => $order_id]) }}" method="POST">
                    @csrf
                    <div class="modal-footer">
                        <button type="button" class="btn " style="background-color:#DADAE3;"
                            data-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn " style="background-color:#ACE6B7">ยืนยัน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
