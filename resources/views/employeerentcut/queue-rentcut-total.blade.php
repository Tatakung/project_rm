@extends('layouts.adminlayout')

@section('content')
    <style>
        .custom-badge {
            background-color: #EB7E52;
            /* สีพื้นหลัง */
            color: white;
            /* สีข้อความ */
            padding: 0.2em 0.45em;
            /* ระยะห่างด้านใน */
            border-radius: 1rem;
            /* ทำมุมให้โค้ง */
            font-size: 0.7em;
            /* ขนาดตัวอักษร */
        }

        .btn-s {
            border-radius: 10px;
        }

        .btn-s:hover {
            background-color: #0056b3;
        }

        .btn-s i {
            margin-right: 2px;
            font-size: 14px;
        }
    </style>



    <div class="container">

        <h1 class="text-start my-4" style="color: #3d3d3d;">
            คิวการเช่าตัดชุด
        </h1>
        <p>รายการนี้แสดงลำดับการเช่าตัดชุดสำหรับลูกค้า โดยเรียงตามวันที่ลูกค้าจะมารับเร็วที่สุด</p>



        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a href="#pagea" class="nav-link active" data-toggle="tab">รอดำเนินการตัด
                    @if ($cutdresss_page_one->count() > 0)
                        <span class="badge custom-badge ml-1" style="font-size: 0.8rem;">
                            {{ $cutdresss_page_one->count() }}
                        </span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a href="#pageb" class="nav-link" data-toggle="tab">เริ่มดำเนินการตัด
                    @if ($cutdresss_page_two->count() > 0)
                        <span class="badge custom-badge ml-1" style="font-size: 0.8rem;">
                            {{ $cutdresss_page_two->count() }}
                        </span>
                    @endif
                </a>
            </li>

            {{-- <li class="nav-item">
                <a href="#paged" class="nav-link" data-toggle="tab">ตัดเสร็จแล้ว
                    @if ($cutdresss_page_three->count() > 0)
                        <span class="badge custom-badge ml-1" style="font-size: 0.8rem;">
                            {{ $cutdresss_page_three->count() }}
                        </span>
                    @endif
                </a>
            </li> --}}

        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="pagea">
                @if ($cutdresss_page_one->count() > 0)
                    <table class="table shadow-sm"
                        style="width: 100%; background-color: #ffffff; border-collapse: collapse;">
                        <thead>
                            <tr style="background-color: #f2f2f2;">
                                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">ลำดับ</th>
                                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">ชื่อลูกค้า</th>

                                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">วันที่รับออเดอร์</th>
                                <th style="width: 150px;">วันที่นัดส่งมอบชุด</th>
                                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">สถานะงาน</th>
                                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">รายละเอียดงาน</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cutdresss_page_one as $index => $cutdress)
                                <tr style="border-bottom: 1px solid #e6e6e6;">
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @php
                                            $customer_id = App\Models\Order::where('id', $cutdress->order_id)->value(
                                                'customer_id',
                                            );
                                            $customer = App\Models\Customer::find($customer_id);
                                        @endphp
                                        คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }}
                                    </td>

                                    <td>
                                        @php
                                            $date_one = App\Models\Date::where('order_detail_id', $cutdress->id)
                                                ->orderBy('created_at', 'desc')
                                                ->first();
                                        @endphp
                                        {{ \Carbon\Carbon::parse($date_one->created_at)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($date_one->created_at)->locale('th')->year + 543 }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($date_one->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($date_one->pickup_date)->year + 543 }}
                                        <span style="color: red;" id="showdate{{ $cutdress->id }}"></span>

                                        <script>
                                            var now = new Date();
                                            var pickup_date = new Date("{{ $date_one->pickup_date }}");
                                            var total = pickup_date - now;

                                            var totalday = Math.ceil(total / (1000 * 60 * 60 * 24));
                                            document.getElementById('showdate{{ $cutdress->id }}').innerHTML = "เหลืออีก " + totalday + " วัน";
                                        </script>

                                    </td>
                                    <td>
                                        @if ($cutdress->status_detail == 'ตัดชุดเสร็จสิ้น')
                                            {{ $cutdress->status_detail }}(รอส่งมอบ)
                                        @else
                                            {{ $cutdress->status_detail }}
                                        @endif
                                    </td>
                                    <td style="padding: 16px;">
                                        <a href="{{ route('detaildoingrentcut', ['id' => $cutdress->id]) }}"
                                            class="btn btn-s" style="padding: 6px 12px; background-color:#DADAE3; ">
                                            ดูรายละเอียด
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="container mt-5">
                        <div class="card" style="margin-left: 150px; margin-right: 150px;">
                            <div class="card-body text-center">
                                <p><strong>ไม่มีรายการแสดงผล</strong></p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="tab-pane" id="pageb">
                @if ($cutdresss_page_two->count() > 0)
                    <table class="table shadow-sm"
                        style="width: 100%; background-color: #ffffff; border-collapse: collapse;">
                        <thead>
                            <tr style="background-color: #f2f2f2;">
                                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">ลำดับ</th>
                                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">ชื่อลูกค้า</th>

                                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">วันที่รับออเดอร์</th>
                                <th style="width: 150px;">วันที่นัดส่งมอบชุด</th>
                                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">สถานะงาน</th>
                                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">รายละเอียดงาน</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cutdresss_page_two as $index => $cutdress)
                                <tr style="border-bottom: 1px solid #e6e6e6;">
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @php
                                            $customer_id = App\Models\Order::where('id', $cutdress->order_id)->value(
                                                'customer_id',
                                            );
                                            $customer = App\Models\Customer::find($customer_id);
                                        @endphp
                                        คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }}
                                    </td>

                                    <td>
                                        @php
                                            $date_two = App\Models\Date::where('order_detail_id', $cutdress->id)
                                                ->orderBy('created_at', 'desc')
                                                ->first();
                                        @endphp
                                        {{ \Carbon\Carbon::parse($date_two->created_at)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($date_two->created_at)->locale('th')->year + 543 }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($date_two->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($date_two->pickup_date)->year + 543 }}
                                        <span style="color: red;" id="showdate{{ $cutdress->id }}"></span>

                                        <script>
                                            var now = new Date();
                                            var pickup_date = new Date("{{ $date_two->pickup_date }}");
                                            var total = pickup_date - now;

                                            var totalday = Math.ceil(total / (1000 * 60 * 60 * 24));
                                            document.getElementById('showdate{{ $cutdress->id }}').innerHTML = "เหลืออีก " + totalday + " วัน";
                                        </script>

                                    </td>
                                    <td>
                                        @if ($cutdress->status_detail == 'ตัดชุดเสร็จสิ้น')
                                            {{ $cutdress->status_detail }}(รอส่งมอบ)
                                        @else
                                            {{ $cutdress->status_detail }}
                                        @endif
                                    </td>
                                    <td style="padding: 16px;">
                                        <a href="{{ route('detaildoingrentcut', ['id' => $cutdress->id]) }}"
                                            class="btn btn-s" style="padding: 6px 12px; background-color:#DADAE3; ">
                                            ดูรายละเอียด
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="container mt-5">
                        <div class="card" style="margin-left: 150px; margin-right: 150px;">
                            <div class="card-body text-center">
                                <p><strong>ไม่มีรายการแสดงผล</strong></p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>


            <div class="tab-pane" id="paged">
                @if ($cutdresss_page_three->count() > 0)
                    <table class="table shadow-sm"
                        style="width: 100%; background-color: #ffffff; border-collapse: collapse;">
                        <thead>
                            <tr style="background-color: #f2f2f2;">
                                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">ลำดับ</th>
                                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">ชื่อลูกค้า</th>

                                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">วันที่รับออเดอร์</th>
                                <th style="width: 150px;">วันที่นัดส่งมอบชุด</th>
                                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">สถานะงาน</th>
                                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">รายละเอียดงาน</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cutdresss_page_three as $index => $cutdress)
                                <tr style="border-bottom: 1px solid #e6e6e6;">
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @php
                                            $customer_id = App\Models\Order::where('id', $cutdress->order_id)->value(
                                                'customer_id',
                                            );
                                            $customer = App\Models\Customer::find($customer_id);
                                        @endphp
                                        คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }}
                                    </td>

                                    <td>
                                        @php
                                            $date_four = App\Models\Date::where('order_detail_id', $cutdress->id)
                                                ->orderBy('created_at', 'desc')
                                                ->first();
                                        @endphp
                                        {{ \Carbon\Carbon::parse($date_four->created_at)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($date_four->created_at)->locale('th')->year + 543 }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($date_four->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($date_four->pickup_date)->year + 543 }}
                                        <span style="color: red;" id="showdate{{ $cutdress->id }}"></span>

                                        <script>
                                            var now = new Date();
                                            var pickup_date = new Date("{{ $date_four->pickup_date }}");
                                            var total = pickup_date - now;

                                            var totalday = Math.ceil(total / (1000 * 60 * 60 * 24));
                                            document.getElementById('showdate{{ $cutdress->id }}').innerHTML = "เหลืออีก " + totalday + " วัน";
                                        </script>

                                    </td>
                                    <td>
                                        @if ($cutdress->status_detail == 'ตัดชุดเสร็จสิ้น')
                                            {{ $cutdress->status_detail }}(รอส่งมอบ)
                                        @else
                                            {{ $cutdress->status_detail }}
                                        @endif
                                    </td>
                                    <td style="padding: 16px;">
                                        <a href="{{ route('employee.ordertotaldetailshow', ['id' => $cutdress->id]) }}"
                                            class="btn btn-s" style="padding: 6px 12px; background-color:#DADAE3; ">
                                            ดูรายละเอียด
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="container mt-5">
                        <div class="card" style="margin-left: 150px; margin-right: 150px;">
                            <div class="card-body text-center">
                                <p><strong>ไม่มีรายการแสดงผล</strong></p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>



































        </div>

    </div>












@endsection
