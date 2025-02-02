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
        .list-group-item.active{
            background-color: #F7F9FA !important;
            color: black !important;
            border-color:#DADAE3;
        }
    </style>

    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">หน้าแรก</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employee.ordertotal') }}">รายการออเดอร์ทั้งหมด</a></li>
        <li class="breadcrumb-item active">รายละเอียดออเดอร์ที่ {{ $order_id }}</li>
    </ol>

    <div class="container mt-4">
        <h3>รายละเอียดออเดอร์ตัดชุด เลขออเดอร์ที่{{ $order_id }}</h3>
        <div class="row mb-4">
            <div class="col-md-6">
                <p><strong>ชื่อลูกค้า:</strong> คุณ{{$customer->customer_fname}} {{$customer->customer_lname}}</p>
                <p><strong>ชื่อพนักงานรับออเดอร์:</strong> คุณ{{$employee->name}} {{$employee->lname}}</p>
            </div>
            <div class="col-md-6">
                <p><strong>วันที่ทำรายการ:</strong>
                    {{ \Carbon\Carbon::parse($order->created_at)->locale('th')->isoFormat('D MMM') }}
                    {{ \Carbon\Carbon::parse($order->created_at)->year +543 }}
                    <span id="show_history_day" style="font-size: 14px; color: rgb(158, 143, 143) ; "></span>
                </p>
            </div>
        </div>
        <script>
            var create_date_now = new Date();
            var create_order_date = new Date('{{ $order->created_at }}');
            var history_day = Math.ceil((create_date_now - create_order_date) / (1000 * 60 * 60 * 24) - 1);
            if(history_day == 0){
                document.getElementById('show_history_day').innerHTML = '(วันนี้)';
            }
            else{
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
                        @endphp



                
                        <td>
                            ตัด{{$item->type_dress}}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM YYYY') }}</td>
                        <td>{{ $item->status_detail }}</td>
                        <td>
                            <a href="{{ route('employee.ordertotaldetailshow', ['id' => $item->id]) }}"
                                class="btn btn-c btn-sm">ดูรายละเอียด</a>
                            @if ($item->status_detail == 'ถูกจอง')
                                <a href="{{ route('employee.ordertotaldetailpostpone', ['id' => $item->id]) }}"
                                    class="btn btn-postpone btn-sm">เลื่อนวัน</a>
                            @endif
                        </td>
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
                <a href="{{ route('receiptreservation', ['id' => $order_id]) }}" target="_blank" class="btn btn-sm"
                    style="background-color:#DADAE3;" tabindex="-1">พิมพ์ใบเสร็จ</a>




            </div>
        @endif

        

        
    </div>














@endsection