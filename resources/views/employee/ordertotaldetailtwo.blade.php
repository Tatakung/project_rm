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
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
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

    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">หน้าแรก</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employee.ordertotal') }}">รายการออเดอร์ทั้งหมด</a></li>
        <li class="breadcrumb-item active">รายละเอียดออเดอร์ที่ {{ $order_id }}</li>
    </ol>
    <div class="container mt-4">
        <h3>รายละเอียดออเดอร์เช่า {{ $order_id }}</h3>
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
            </div>
            <div class="col-md-6">
                <button type="button" class="btn btn-c " data-toggle="modal" data-target="#show_modal_pickup_total"
                    @if ($queue_pass == true) @if ($check_button_new == 1)
                    style="display: block ; background-color:#DADAE3; "
                @else
                    style="display: none ; background-color:#DADAE3; " @endif
                @else style="display: none ; background-color:#DADAE3; " @endif
                    >
                    รับชุด/รับเครื่องประดับ (ทั้งหมด)
                </button>
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


        {{-- <div class="alert alert-danger" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <div>

                    <p class="mb-0">กำไล C9 ได้รับรายงานว่าสูญหาย/เสียหาย ไม่สามารถให้เช่าต่อได้
                        กรุณาติดต่อลูกค้าเพื่อแจ้งยกเลิกและคืนเงินมัดจำ
                    </p>
                </div>
            </div>
        </div> --}}



        <table class="table table-striped shadow-sm">
            <thead>
                <tr>
                    <th>รูป</th>
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



                        <td class="image-container">
                            @if ($item->type_order == 2 && $item->orderdetailmanytoonedress->dressimages->first())
                                <img src="{{ asset('storage/' . $item->orderdetailmanytoonedress->dressimages->first()->dress_image) }}"
                                    alt="รูปสินค้า">
                            @elseif($item->type_order == 3 && $item->detail_many_one_re->jewelry_id)
                                <img src="{{ asset('storage/' . $item->detail_many_one_re->resermanytoonejew->jewelryimages->first()->jewelry_image) }}"
                                    alt="">
                            @else
                                <img src="{{ asset('images/setjewelry.jpg') }}" alt="">
                            @endif
                        </td>
                        <td>
                            @php
                                // เอาไว้ใช้ตอนที่ยกเลิกorder แล้วบอกว่า มันยกเลิหกมันไหน
                                $status_orderdetail = App\Models\Orderdetailstatus::where('order_detail_id', $item->id)
                                    ->orderBy('created_at', 'desc')
                                    ->first();
                            @endphp

                            @if ($item->type_order == 2)
                                @if ($item->shirtitems_id)
                                    เช่า{{ $item->orderdetailmanytoonedress->typedress->type_dress_name }}
                                    {{ $item->orderdetailmanytoonedress->typedress->specific_letter }}{{ $item->orderdetailmanytoonedress->dress_code }}
                                    (เสื้อ)
                                    <br>
                                @elseif($item->skirtitems_id)
                                    เช่า{{ $item->orderdetailmanytoonedress->typedress->type_dress_name }}
                                    {{ $item->orderdetailmanytoonedress->typedress->specific_letter }}{{ $item->orderdetailmanytoonedress->dress_code }}
                                    (ผ้าถุง)
                                    <br>
                                @else
                                    เช่า{{ $item->orderdetailmanytoonedress->typedress->type_dress_name }}
                                    {{ $item->orderdetailmanytoonedress->typedress->specific_letter }}{{ $item->orderdetailmanytoonedress->dress_code }}
                                    (ทั้งชุด)
                                    <br>
                                @endif
                                @php
                                    $dress_mea_adjust = App\Models\Dressmeaadjustment::where(
                                        'order_detail_id',
                                        $item->id,
                                    )->get();
                                    foreach ($dress_mea_adjust as $key => $adjust) {
                                        $is_adjust = false;
                                        if (
                                            $adjust->new_size !=
                                            $adjust->dressmeaadjust_many_to_one_dressmea->current_mea
                                        ) {
                                            $is_adjust = true;
                                            break;
                                        }
                                    }
                                @endphp
                                @if ($is_adjust)
                                    <span style="font-size: 13px; color: red ; ">-รอปรับแก้ขนาด</span> <br>
                                @endif
                                @if ($item->status_detail == 'ยกเลิกโดยทางร้าน' || $item->status_detail == 'ยกเลิกโดยลูกค้า')
                                    <span style="color: red ; font-size: 12px;">ยกเลิกมื่อ:
                                        {{ \Carbon\Carbon::parse($status_orderdetail->created_at)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($status_orderdetail->created_at)->year + 543 }}
                                    </span>
                                @endif
                            @elseif($item->type_order == 3)
                                @if ($item->detail_many_one_re->jewelry_id)
                                    เช่า{{ $item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->type_jewelry_name }}
                                    {{ $item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->specific_letter }}{{ $item->detail_many_one_re->resermanytoonejew->jewelry_code }}
                                    <br>
                                    @if ($item->status_detail == 'ยกเลิกโดยทางร้าน' || $item->status_detail == 'ยกเลิกโดยลูกค้า')
                                        <span style="color: red ; font-size: 12px;">ยกเลิกมื่อ:
                                            {{ \Carbon\Carbon::parse($status_orderdetail->created_at)->locale('th')->isoFormat('D MMM') }}
                                            {{ \Carbon\Carbon::parse($status_orderdetail->created_at)->year + 543 }}
                                        </span>
                                    @endif
                                @elseif($item->detail_many_one_re->jewelry_set_id)
                                    เช่าเซตเครื่องประดับ{{ $item->detail_many_one_re->resermanytoonejewset->set_name }}
                                    <br>
                                    @if ($item->status_detail == 'ยกเลิกโดยทางร้าน' || $item->status_detail == 'ยกเลิกโดยลูกค้า')
                                        <span style="color: red ; font-size: 12px;">ยกเลิกมื่อ:
                                            {{ \Carbon\Carbon::parse($status_orderdetail->created_at)->locale('th')->isoFormat('D MMM') }}
                                            {{ \Carbon\Carbon::parse($status_orderdetail->created_at)->year + 543 }}
                                        </span>
                                    @endif
                                @endif
                            @endif
                        </td>



                        @if ($item->status_detail == 'ยกเลิกโดยทางร้าน' || $item->status_detail == 'ยกเลิกโดยลูกค้า')
                            <td style="text-decoration: line-through;color: red;">{{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                {{ \Carbon\Carbon::parse($Date->pickup_date)->year + 543 }}
                            </td>
                            <td style="text-decoration: line-through;color: red;">{{ \Carbon\Carbon::parse($Date->return_date)->locale('th')->isoFormat('D MMM') }}
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
                            {{-- {{ $item->status_detail }} --}}

                            @if ($item->type_order == 2)
                                {{ $item->status_detail }}
                            @elseif($item->type_order == 3)
                                @if ($item->detail_many_one_re->jewelry_id)
                                    @if ($item->status_detail == 'ถูกจอง')
                                        @if (
                                            $item->detail_many_one_re->resermanytoonejew->jewelry_status == 'สูญหาย' ||
                                                $item->detail_many_one_re->resermanytoonejew->jewelry_status == 'ยุติการให้เช่า')
                                            <button class="btn btn-sm btn-danger"><i
                                                    class="fas fa-exclamation-triangle me-2"></i>{{ $item->detail_many_one_re->resermanytoonejew->jewelry_status }}</button>
                                        @else
                                            {{ $item->status_detail }}
                                        @endif
                                    @elseif($item->status_detail == 'ยกเลิกโดยทางร้าน' || $item->status_detail == 'ยกเลิกโดยลูกค้า')
                                        <span style="color: red ; ">{{ $item->status_detail }}</span>
                                    @else
                                        {{ $item->status_detail }}
                                    @endif
                                @elseif($item->detail_many_one_re->jewelry_set_id)
                                    {{ $item->status_detail }}
                                @endif
                            @endif





                        </td>
                        <td>
                            <a href="{{ route('employee.ordertotaldetailshow', ['id' => $item->id]) }}"
                                class="btn btn-c btn-sm"style="background-color:#ffffff;">ดูรายละเอียด</a>

                            @if ($item->status_detail == 'ถูกจอง')
                                @if ($item->type_order == 2)
                                    <a href="{{ route('employee.ordertotaldetailpostpone', ['id' => $item->reservation_id]) }}"
                                        class="btn btn-postpone btn-sm">เลื่อนวัน</a>
                                @elseif($item->type_order == 3)
                                    @if ($item->detail_many_one_re->jewelry_id)
                                        @if (
                                            $item->detail_many_one_re->resermanytoonejew->jewelry_status == 'สูญหาย' ||
                                                $item->detail_many_one_re->resermanytoonejew->jewelry_status == 'ยุติการให้เช่า')
                                        @else
                                            <a href="{{ route('postponeroutejewelry', ['id' => $item->reservation_id]) }}"
                                                class="btn btn-postpone btn-sm" style="background-color:#BACEE6 ;">
                                                เลื่อนวัน
                                            </a>
                                        @endif
                                    @elseif($item->detail_many_one_re->jewelry_set_id)
                                        <a href="{{ route('postponeroutejewelry', ['id' => $item->reservation_id]) }}"
                                            class="btn btn-postpone btn-sm" style="background-color:#BACEE6 ;">
                                            เลื่อนวัน
                                        </a>
                                    @endif
                                @endif
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                    data-target="#cancelModal{{ $item->id }}">
                                    ยกเลิกการจอง
                                </button>
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
                                            ยกเลิกการจอง:
                                            @if ($item->type_order == 2)
                                                @if ($item->shirtitems_id)
                                                    เช่า{{ $item->orderdetailmanytoonedress->typedress->type_dress_name }}
                                                    {{ $item->orderdetailmanytoonedress->typedress->specific_letter }}{{ $item->orderdetailmanytoonedress->dress_code }}
                                                    (เสื้อ)
                                                    <br>
                                                @elseif($item->skirtitems_id)
                                                    เช่า{{ $item->orderdetailmanytoonedress->typedress->type_dress_name }}
                                                    {{ $item->orderdetailmanytoonedress->typedress->specific_letter }}{{ $item->orderdetailmanytoonedress->dress_code }}
                                                    (ผ้าถุง)
                                                    <br>
                                                @else
                                                    เช่า{{ $item->orderdetailmanytoonedress->typedress->type_dress_name }}
                                                    {{ $item->orderdetailmanytoonedress->typedress->specific_letter }}{{ $item->orderdetailmanytoonedress->dress_code }}
                                                    (ทั้งชุด)
                                                    <br>
                                                @endif
                                            @elseif($item->type_order == 3)
                                                @if ($item->detail_many_one_re->jewelry_id)
                                                    เช่า{{ $item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->type_jewelry_name }}
                                                    {{ $item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->specific_letter }}{{ $item->detail_many_one_re->resermanytoonejew->jewelry_code }}
                                                @elseif($item->detail_many_one_re->jewelry_set_id)
                                                    เช่าเซตเครื่องประดับ{{ $item->detail_many_one_re->resermanytoonejewset->set_name }}
                                                @endif
                                            @endif
                                        </h5>

                                    </div>

                                    <form action="{{ route('cancelorderrent', ['id' => $item->id]) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <p class="text-muted">กรุณาเลือกสาเหตุการยกเลิกการจอง</p>
                                            <!-- ยกเลิกโดยทางร้าน -->
                                            <div class="form-check border rounded-lg p-4 mb-3">
                                                <input class="form-check-input" type="radio" name="cancelType"
                                                    id="store" value="store">
                                                <label class="form-check-label d-flex align-items-center gap-2"
                                                    for="store">
                                                    <i class="fas fa-store"></i>
                                                    ยกเลิกโดยทางร้าน
                                                </label>
                                                <div class="mt-2 text-muted">
                                                    <ul class="mb-2">
                                                        <li>สินค้าเสียหาย/สูญหาย ไม่สามารถให้เช่าได้</li>
                                                        <li>ต้องแจ้งลูกค้าและคืนเงินมัดจำ 100% <span
                                                                style="font-size: 14px;">({{ number_format($item->price, 2) }}
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
                                                        <li>ริบเงินมัดจำตามเงื่อนไข <span
                                                                style="font-size: 14px;">({{ number_format($item->deposit, 2) }}
                                                                บาท)</span></li>
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
                    <p class="mb-1">ใบเสร็จรับเงิน(จอง)</p>
                    <p class="mb-1">วันที่:
                        {{ Carbon\Carbon::parse($receipt_one->created_at)->locale('th')->isoFormat('D MMM') }}
                        {{ Carbon\Carbon::parse($receipt_one->created_at)->year + 543 }}

                    </p>
                </div>
                <a href="{{ route('receiptreservation', ['id' => $order_id]) }}" target="_blank" class="btn btn-sm"
                    style="background-color:#DADAE3;" tabindex="-1">พิมพ์ใบเสร็จ</a>




            </div>
        @endif

        @if ($receipt_two)
            <div class="list-group-item shadow-sm mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1">ใบเสร็จรับเงิน(วันที่มารับชุด/เครื่องประดับ)</p>
                    <p class="mb-1">วันที่:
                        {{ Carbon\Carbon::parse($receipt_two->created_at)->locale('th')->isoFormat('D MMM') }}
                        {{ Carbon\Carbon::parse($receipt_two->created_at)->year + 543 }}

                    </p>
                </div>
                <a href="{{ route('receiptpickuprent', ['id' => $order_id]) }}" target="_blank" class="btn btn-sm"
                    style="background-color:#DADAE3;" tabindex="-1">พิมพ์ใบเสร็จ</a>
            </div>
        @endif

        @if ($receipt_three)
            <div class="list-group-item shadow-sm mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1">ใบเสร็จคืนเงินประกัน</p>
                    <p class="mb-1">วันที่:
                        {{ Carbon\Carbon::parse($receipt_three->created_at)->locale('th')->isoFormat('D MMM') }}
                        {{ Carbon\Carbon::parse($receipt_three->created_at)->year + 543 }}

                    </p>
                </div>
                <a href="{{ route('receiptreturnrent', ['id' => $order_id]) }}" target="_blank" class="btn btn-sm"
                    style="background-color:#DADAE3;" tabindex="-1">พิมพ์ใบเสร็จ</a>
            </div>
        @endif
    </div>

    <div class="modal fade" id="show_modal_pickup_total" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#EAD8C0;">
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
                                <th @if ($is_fully_paid == 10) style="display: block ; "
                                @elseif($is_fully_paid == 20){
                                    style="display: none;" @endif
                                    }>เงินมัดจำ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderdetail as $item)
                                <tr>
                                    <td>
                                        @if ($item->type_order == 2)
                                            @if ($item->shirtitems_id)
                                                เช่า{{ $item->orderdetailmanytoonedress->typedress->type_dress_name }}
                                                {{ $item->orderdetailmanytoonedress->typedress->specific_letter }}{{ $item->orderdetailmanytoonedress->dress_code }}
                                                (เสื้อ)
                                                <br>
                                            @elseif($item->skirtitems_id)
                                                เช่า{{ $item->orderdetailmanytoonedress->typedress->type_dress_name }}
                                                {{ $item->orderdetailmanytoonedress->typedress->specific_letter }}{{ $item->orderdetailmanytoonedress->dress_code }}
                                                (ผ้าถุง)
                                                <br>
                                            @else
                                                เช่า{{ $item->orderdetailmanytoonedress->typedress->type_dress_name }}
                                                {{ $item->orderdetailmanytoonedress->typedress->specific_letter }}{{ $item->orderdetailmanytoonedress->dress_code }}
                                                (ทั้งชุด)
                                                <br>
                                            @endif
                                        @elseif($item->type_order == 3)
                                            @if ($item->detail_many_one_re->jewelry_id)
                                                เช่า{{ $item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->type_jewelry_name }}
                                                {{ $item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->specific_letter }}{{ $item->detail_many_one_re->resermanytoonejew->jewelry_code }}
                                            @elseif($item->detail_many_one_re->jewelry_set_id)
                                                เช่าเซตเครื่องประดับ{{ $item->detail_many_one_re->resermanytoonejewset->set_name }}
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{ number_format($item->price, 2) }}</td>
                                    <td>{{ number_format($item->damage_insurance, 2) }}</td>


                                    @if ($is_fully_paid == 10)
                                        <td>{{ number_format($item->deposit, 2) }}</td>
                                    @endif


                                </tr>
                            @endforeach
                            <tr style="background-color:rgb(240, 240, 240);">
                                <td><strong>รวมทั้งหมด</strong></td>
                                <td> {{ number_format($total_price, 2) }} </td>
                                <td> {{ number_format($total_damage_insurance, 2) }} </td>
                                @if ($is_fully_paid == 10)
                                    <td>{{ number_format($total_deposit, 2) }}</td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                    <p style="margin-bottom: 0px;"><strong>ยอดคงเหลือที่ต้องชำระ:
                            {{ number_format($remaining_balance, 2) }} บาท</strong></p>
                    @if ($only_payment == true)
                        <p style="font-size: 14px;">(ชำระค่ามัดจำไปแล้ว {{ number_format($total_deposit, 2) }} บาท)</p>
                    @endif



                </div>
                <form action="{{ route('updatestatuspickuptotalrent', ['id' => $order_id]) }}" method="POST">
                    @csrf
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn" style="background-color:#ACE6B7;">ยืนยัน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>







@endsection
