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
                <a href="{{ route('receiptdeposittotal', ['id' => $order_id]) }}" class="btn btn-primary btn-sm mt-2"
                    target="_blank">ใบเสร็จรับเงิน</a>
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
                            @if ($item->type_order == 2)
                                เช่า{{ $item->orderdetailmanytoonedress->typedress->type_dress_name }}
                                {{ $item->orderdetailmanytoonedress->typedress->specific_letter }}{{ $item->orderdetailmanytoonedress->dress_code }}
                            @elseif($item->type_order == 3)
                                @if ($item->detail_many_one_re->jewelry_id)
                                    เช่า{{ $item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->type_jewelry_name }}
                                    {{ $item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->specific_letter }}{{ $item->detail_many_one_re->resermanytoonejew->jewelry_code }}
                                @elseif($item->detail_many_one_re->jewelry_set_id)
                                    เช่าเซตเครื่องประดับ{{ $item->detail_many_one_re->resermanytoonejewset->set_name }}
                                @endif
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM YYYY') }}</td>
                        <td>{{ \Carbon\Carbon::parse($Date->return_date)->locale('th')->isoFormat('D MMM YYYY') }}</td>
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
@endsection
