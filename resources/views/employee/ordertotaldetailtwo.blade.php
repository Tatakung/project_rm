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
                                    <span style="font-size: 13px; color: red ; ">-รอปรับแก้ขนาด</span>
                                
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
                        <td>{{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($Date->pickup_date)->year + 543 }}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($Date->return_date)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($Date->return_date)->year + 543 }}
                        </td>
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

        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#show_modal_pickup_total"
            @if ($queue_pass == true && $check_text_detail_status == true && $check_text_detail_status_two == true) style="display: block ; "
        @else
        style="display: none ; " @endif>
            รับชุด/รับเครื่องประดับ (ทั้งหมด)
        </button>


    </div>
    <div class="container mt-4 mb-4">

        <p class="mt-5">ประวัติใบเสร็จ</p>


        @if ($receipt_one)
            <div class="list-group-item shadow-sm mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1">ใบเสร็จรับเงิน(จอง)</p>
                    <p class="mb-1">วันที่:
                        {{ Carbon\Carbon::parse($receipt_one->created_at)->locale('th')->isoFormat('D MMM') }}
                        {{ Carbon\Carbon::parse($receipt_one->created_at)->year + 543 }}

                    </p>
                </div>
                <a href="{{ route('receiptreservation', ['id' => $order_id]) }}" target="_blank"
                    class="btn btn-sm btn-primary" tabindex="-1">พิมพ์ใบเสร็จ</a>




            </div>
        @endif



        @if ($receipt_two)
            <div class="list-group-item shadow-sm mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1">ใบเสร็จรับชุด</p>
                    <p class="mb-1">วันที่:
                        {{ Carbon\Carbon::parse($receipt_two->created_at)->locale('th')->isoFormat('D MMM') }}
                        {{ Carbon\Carbon::parse($receipt_two->created_at)->year + 543 }}

                    </p>
                </div>
                <a href="{{route('receiptpickuprent' , ['id' => $order_id])}}" target="_blank" class="btn btn-sm btn-primary" tabindex="-1">พิมพ์ใบเสร็จ {{$receipt_two->id}}</a>
            </div>
        @endif

        @if ($receipt_three)
            <div class="list-group-item shadow-sm mb-3 d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1">ใบเสร็จคืนชุด</p>
                    <p class="mb-1">วันที่:
                        {{ Carbon\Carbon::parse($receipt_three->created_at)->locale('th')->isoFormat('D MMM') }}
                        {{ Carbon\Carbon::parse($receipt_three->created_at)->year + 543 }}

                    </p>
                </div>
                <a href="{{route('receiptreturnrent',['id' =>  $order_id])}}" target="_blank" class="btn btn-primary" tabindex="-1">ดูใบเสร็จ{{$receipt_three->id}}</a>
            </div>
        @endif
    </div>

    <div class="modal fade" id="show_modal_pickup_total" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-backdrop="static">
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
                            <tr>
                                <td><strong>รวมทั้งหมด</strong></td>
                                <td> {{ number_format($total_price, 2) }} </td>
                                <td> {{ number_format($total_damage_insurance, 2) }} </td>
                                @if ($is_fully_paid == 10)
                                    <td>{{ number_format($total_deposit, 2) }}</td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                    <p><strong>ยอดคงเหลือที่ต้องชำระ: {{ number_format($remaining_balance, 2) }} บาท</strong></p>

                    @if($only_payment == true)
                    <p style="font-size: 14px;">(ชำระค่ามัดจำไปแล้ว {{ number_format($total_deposit, 2) }} บาท)</p>
                    @endif



                </div>
                <form action="{{route('updatestatuspickuptotalrent',['id' => $order_id ])}}" method="POST">
                    @csrf
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn btn-primary">ยืนยัน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>







@endsection
