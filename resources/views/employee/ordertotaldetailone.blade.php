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
        <h3>รายการออเดอร์ตัดชุด เลขออเดอร์ที่ {{ $order_id }}</h3>
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
        <table class="table table-striped shadow-sm">
            <thead>
                <tr>
                    <th>รายการ</th>
                    <th>วันนัดรับชุด</th>
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
                            // เอาไว้ใช้ตอนที่ยกเลิกorder แล้วบอกว่า มันยกเลิหกวันไหน
                            $status_orderdetail = App\Models\Orderdetailstatus::where('order_detail_id', $item->id)
                                ->orderBy('created_at', 'desc')
                                ->first();
                        @endphp

                        <td>
                            ตัด{{ $item->type_dress }}
                        </td>


                        @if ($item->status_detail == 'ยกเลิกโดยทางร้าน' || $item->status_detail == 'ยกเลิกโดยลูกค้า')
                            <td style="text-decoration: line-through;color: red;">
                                {{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                {{ \Carbon\Carbon::parse($Date->pickup_date)->year + 543 }}
                            </td>
                        @else
                            <td>{{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                {{ \Carbon\Carbon::parse($Date->pickup_date)->year + 543 }}
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
                            <a href="{{ route('employee.ordertotaldetailshow', ['id' => $item->id]) }}"
                                class="btn btn-c btn-sm" style="background-color:#ffffff;">ดูรายละเอียด</a>
                            <button class="btn btn-postpone btn-sm" type="button" data-toggle="modal"
                                data-target="#changedate{{ $item->id }}"
                                @if (
                                    $item->status_detail == 'ส่งมอบชุดแล้ว' ||
                                        $item->status_detail == 'ยกเลิกโดยทางร้าน' ||
                                        $item->status_detail == 'ยกเลิกโดยลูกค้า') style="display:none;"
                                @else
                                    style="display: inline-block;" @endif>
                                เลื่อนวันนัดรับชุด
                            </button>


                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                data-target="#cancelModal{{ $item->id }}"
                                @if ($item->status_detail == 'รอดำเนินการตัด' || $item->status_detail == 'เริ่มดำเนินการตัด') style="display: inline-block;"
                                @else
                                    style="display: none;" @endif>
                                ยกเลิกรายการ
                            </button>
                        </td>




                        <div class="modal fade" id="changedate{{ $item->id }}" tabindex="-1"
                            aria-labelledby="changedateLabel{{ $item->id }}" aria-hidden="true" data-backdrop="static">
                            <div class="modal-dialog modal-md">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            <i class="fas fa-calendar-alt"></i> เลื่อนวันรับชุด: ตัด{{ $item->type_dress }}
                                        </h5>
                                    </div>

                                    <form action="{{ route('changePickupDateCutdress', ['id' => $item->id]) }}"
                                        method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <label for="new_pickup_date{{ $item->id }}"
                                                class="form-label">เลือกวันรับชุดใหม่:</label>
                                            <input type="date" id="new_pickup_date{{ $item->id }}"
                                                name="new_pickup_date" class="form-control" required
                                                value="{{ $Date->pickup_date }}" min="{{ $today }}">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">ยกเลิก</button>
                                            <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>






                        <div class="modal fade" id="cancelModal{{ $item->id }}" tabindex="-1"
                            aria-labelledby="cancelModal{{ $item->id }}" aria-hidden="true" data-backdrop="static">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title d-flex align-items-center gap-2"
                                            id="cancelModal{{ $item->id }}">
                                            <i class="fas fa-exclamation-triangle text-red-500"></i>
                                            ยกเลิกรายการ:
                                            ตัด{{ $item->type_dress }}
                                        </h5>

                                    </div>

                                    <form action="{{ route('cancelordercut', ['id' => $item->id]) }}" method="POST">
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
                                                        <li>ไม่สามารถตัดชุดได้</li>
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
                    <p class="mb-1">ใบเสร็จรับเงิน</p>
                    <p class="mb-1" style="font-size: 14px; color: #6c757d ; ">วันที่ออกใบเสร็จ:
                        {{ Carbon\Carbon::parse($receipt_one->created_at)->locale('th')->isoFormat('D MMM') }}
                        {{ Carbon\Carbon::parse($receipt_one->created_at)->year + 543 }}

                    </p>
                </div>
                <a href="{{ route('receiptordercutdress', ['id' => $order_id]) }}" target="_blank" class="btn btn-sm"
                    style="background-color:#DADAE3;" tabindex="-1">พิมพ์ใบเสร็จ</a>
            </div>
        @endif




    </div>
@endsection
