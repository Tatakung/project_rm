<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ใบเสร็จรับเงิน</title>
    <style>
        @font-face {
            font-family: 'THSarabunNew';
            src: url({{ storage_path('fonts/THSarabunNew.ttf') }}) format('truetype');
        }

        body {
            font-family: 'THSarabunNew';
            font-size: 19px;
            margin: -15;
            line-height: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            padding: 5px;
            vertical-align: top;
        }

        .info-box {
            border: 1px solid black;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .info-box td {
            padding: 10px;
        }

        .items-table {
            border: 1px solid black;
            margin-top: 20px;
        }

        /* สไตล์ใหม่สำหรับตารางสินค้า */
        .items-table th {
            border-bottom: 1px solid black;
            border-right: 1px solid black;
            padding: 5px;
            background-color: #f5f5f5;
        }

        .items-table td {
            border-right: 1px solid black;
            padding: 5px;
        }

        .items-table th:last-child,
        .items-table td:last-child {
            border-right: none;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .company-logo {
            width: 80px;
        }
    </style>
</head>

<body>
    <table class="header-table">
        <tr>
            <td width="15%">
                <img src="{{ public_path('images/logo5.png') }}" class="company-logo">
            </td>
            <td width="50%">
                <strong style="font-size: 25px;">ร้านเปลือกไหม</strong><br>
                81/1 ถ.โพนพิสัย ต.หมากแข้ง อ.เมือง จ.อุดรธานี 41000<br>
                โทรศัพท์ 081-8717-791<br>
                เลขประจำตัวผู้เสียภาษี 1623651970
            </td>
            <td width="35%" style="text-align: right;">
                <strong style="font-size: 25px;">ใบเสร็จรับเงิน</strong><br>
                {{-- สำเนา --}}
            </td>
        </tr>
    </table>
    <!-- ส่วนข้อมูลลูกค้าและเลขที่เอกสาร -->
    <table style="width: 100%;" style="margin-top: -25px;">
        <tr>
            <td style="width: 60%; padding-right: 10px;">
                <table class="info-box" style="width: 100%;">
                    <tr>
                        <td>
                            ชื่อลูกค้า: คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }}<br>
                            เบอร์โทร: {{ $customer->customer_phone }} <br>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 40%;">
                <table class="info-box" style="width: 100%;">
                    <tr>
                        <td>
                            เลขที่ใบเสร็จ: {{ $receipt->id }}<br>
                            วันที่: {{ \Carbon\Carbon::parse($receipt->created_at)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($receipt->created_at)->year + 543 }}
                            <br>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    @php
        $count_index = 1;
    @endphp
    <!-- ส่วนรายการสินค้า -->
    <table class="items-table" style="margin-top: -13px;">
        <thead>
            <tr>
                
                <th width="10%" style="text-align: center;">ลำดับที่</th>
                <th width="40%" style="text-align: center;">รายการ</th>
                <th width="10%" style="text-align: center;">จำนวน</th>
                <th width="15%" style="text-align: center;">ราคาต่อหน่วย</th>
                <th width="15%" style="text-align: center;">จำนวนเงิน</th>
            </tr>
        </thead>
        <tbody>


            @foreach ($orderdetail as $index => $item)
                <tr>
                    
                    <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                    <td>

                        @php
                            // ถ้าจ่ายเงินมัดจำ ตัวแปร check_payment จะเป็น true
                            $check_payment = App\Models\Paymentstatus::where('order_detail_id', $item->id)
                                ->where('payment_status', 1)
                                ->exists();
                        @endphp
                        @if ($item->type_order == 1)
                            @if ($check_payment)
                                มัดจำ{{ $item->type_dress }}<br>
                            @else
                                ค่าตัด{{ $item->type_dress }}<br>
                            @endif
                        @elseif($item->type_order == 2)
                            @if ($check_payment)
                                @if ($item->shirtitems_id)
                                    มัดจำค่าเช่า{{ $item->orderdetailmanytoonedress->typedress->type_dress_name }}
                                    {{ $item->orderdetailmanytoonedress->typedress->specific_letter }}{{ $item->orderdetailmanytoonedress->dress_code }}
                                    (เสื้อ)
                                @elseif($item->skirtitems_id)
                                    มัดจำค่าเช่า{{ $item->orderdetailmanytoonedress->typedress->type_dress_name }}
                                    {{ $item->orderdetailmanytoonedress->typedress->specific_letter }}{{ $item->orderdetailmanytoonedress->dress_code }}
                                    (ผ้าถุง)
                                @else
                                    มัดจำค่าเช่า{{ $item->orderdetailmanytoonedress->typedress->type_dress_name }}
                                    {{ $item->orderdetailmanytoonedress->typedress->specific_letter }}{{ $item->orderdetailmanytoonedress->dress_code }}
                                    (ทั้งชุด)
                                @endif
                            @else
                                @if ($item->shirtitems_id)
                                    ค่าเช่า{{ $item->orderdetailmanytoonedress->typedress->type_dress_name }}
                                    {{ $item->orderdetailmanytoonedress->typedress->specific_letter }}{{ $item->orderdetailmanytoonedress->dress_code }}
                                    (เสื้อ)
                                @elseif($item->skirtitems_id)
                                    ค่าเช่า{{ $item->orderdetailmanytoonedress->typedress->type_dress_name }}
                                    {{ $item->orderdetailmanytoonedress->typedress->specific_letter }}{{ $item->orderdetailmanytoonedress->dress_code }}
                                    (ผ้าถุง)
                                @else
                                    ค่าเช่า{{ $item->orderdetailmanytoonedress->typedress->type_dress_name }}
                                    {{ $item->orderdetailmanytoonedress->typedress->specific_letter }}{{ $item->orderdetailmanytoonedress->dress_code }}
                                    (ทั้งชุด)
                                @endif
                            @endif
                        @elseif($item->type_order == 3)
                            @if ($check_payment)
                                @if ($item->detail_many_one_re->jewelry_id)
                                    มัดจำค่าเช่า{{ $item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->type_jewelry_name }}
                                    {{ $item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->specific_letter }}{{ $item->detail_many_one_re->resermanytoonejew->jewelry_code }}
                                    <br>
                                @elseif($item->detail_many_one_re->jewelry_set_id)
                                    มัดจำค่าเช่าเซตเครื่องประดับ{{ $item->detail_many_one_re->resermanytoonejewset->set_name }}
                                    <br>
                                @endif
                            @else
                                @if ($item->detail_many_one_re->jewelry_id)
                                    ค่าเช่า{{ $item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->type_jewelry_name }}
                                    {{ $item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->specific_letter }}{{ $item->detail_many_one_re->resermanytoonejew->jewelry_code }}
                                @elseif($item->detail_many_one_re->jewelry_set_id)
                                    ค่าเช่าเซตเครื่องประดับ{{ $item->detail_many_one_re->resermanytoonejewset->set_name }}
                                @endif
                            @endif
                        @elseif($item->type_order == 4)
                            @if ($check_payment)
                                มัดจำค่าเช่าตัด{{ $item->type_dress }}
                            @else
                                ค่าเช่าตัด{{ $item->type_dress }}
                            @endif
                        @endif
                    </td>


                    <td class="text-center" style="vertical-align: top;">{{ number_format($item->amount, 2) }}</td>


                    <td class="text-center" style="vertical-align: top;">
                        @if ($check_payment)
                            {{ number_format($item->deposit, 2) }}
                        @else
                            {{ number_format($item->price, 2) }}
                        @endif
                    </td>


                    <td class="text-center" style="vertical-align: top;">
                        @if ($check_payment)
                            {{ number_format($item->deposit, 2) }}
                        @else
                            {{ number_format($item->price, 2) }}
                        @endif
                    </td>

                </tr>
            @endforeach



            @foreach ($orderdetail as $index => $item)
                @php
                    // ถ้าจ่ายเงินมัดจำ ตัวแปร check_payment จะเป็น true
                    $check_payment = App\Models\Paymentstatus::where('order_detail_id', $item->id)
                        ->where('payment_status', 1)
                        ->exists();
                @endphp

                @if (!$check_payment)
                    <tr>
                        
                        <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                        <td>
                            @if ($item->type_order == 1)
                                รอ
                            @elseif($item->type_order == 2)
                                @if ($item->shirtitems_id)
                                    ค่าประกัน{{ $item->orderdetailmanytoonedress->typedress->type_dress_name }}
                                    {{ $item->orderdetailmanytoonedress->typedress->specific_letter }}{{ $item->orderdetailmanytoonedress->dress_code }}
                                    (เสื้อ)
                                @elseif($item->skirtitems_id)
                                    ค่าประกัน{{ $item->orderdetailmanytoonedress->typedress->type_dress_name }}
                                    {{ $item->orderdetailmanytoonedress->typedress->specific_letter }}{{ $item->orderdetailmanytoonedress->dress_code }}
                                    (ผ้าถุง)
                                @else
                                    ค่าประกัน{{ $item->orderdetailmanytoonedress->typedress->type_dress_name }}
                                    {{ $item->orderdetailmanytoonedress->typedress->specific_letter }}{{ $item->orderdetailmanytoonedress->dress_code }}
                                    (ทั้งชุด) 
                                @endif
                            @elseif($item->type_order == 3)
                                @if ($item->detail_many_one_re->jewelry_id)
                                    ค่าประกัน{{ $item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->type_jewelry_name }}
                                    {{ $item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->specific_letter }}{{ $item->detail_many_one_re->resermanytoonejew->jewelry_code }}
                                @elseif($item->detail_many_one_re->jewelry_set_id)
                                    ค่าประกันเซตเครื่องประดับ{{ $item->detail_many_one_re->resermanytoonejewset->set_name }}
                                @endif
                            @elseif($item->type_order == 4)
                                ค่าประกันเช่าตัด{{ $item->type_dress }}
                            @endif
                        </td>
                        <td class="text-center" style="vertical-align: top;">{{ number_format($item->amount , 2 ) }}</td>
                        <td class="text-center" style="vertical-align: top;">{{ number_format($item->damage_insurance , 2 ) }}</td>
                        <td class="text-center" style="vertical-align: top;"> {{ number_format($item->damage_insurance , 2 ) }} </td>
                    </tr>
                @endif
            @endforeach




            @for ($i = $count_index; $i <= 14; $i++)
                <tr>
                    
                    <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                    <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                    <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                </tr>
            @endfor

            <tr>
                <td class="text-center" style="vertical-align: top ;   border-top: 1px solid black;" colspan="4">
                    รวมเงิน</td>
                <td class="text-center" style="vertical-align: top; border-top: 1px solid black;">
                    {{ $receipt->total_price }}</td>
            </tr>



        </tbody>
    </table>
    <!-- ส่วนลายเซ็น -->
    <table style="margin-top: 10px;">
        <tr>
            <td width="50%" class="text-center">
                (..........................................................)<br>
                ชื่อผู้รับเงิน<br>
                พนักงาน<br>
                วันที่ {{ \Carbon\Carbon::parse($receipt->created_at)->locale('th')->isoFormat('D MMM') }}
                {{ \Carbon\Carbon::parse($receipt->created_at)->year + 543 }}
            </td>
            <td width="50%" class="text-center">
                (..........................................................)<br>
                ชื่อลูกค้า<br>
                คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }} <br>
                วันที่ {{ \Carbon\Carbon::parse($receipt->created_at)->locale('th')->isoFormat('D MMM') }}
                {{ \Carbon\Carbon::parse($receipt->created_at)->year + 543 }}
            </td>
        </tr>
    </table>


    <div style="margin-top: 10px;">
        <p>หมายเหตุ :</p>


        @if ($order->type_order == 1)
        @elseif($order->type_order == 2)
            <span class="sub-item" style="margin-left: 20px;">-
                นัดรับวันที่
                {{ \Carbon\Carbon::parse($pickup_return_only->pickup_date)->locale('th')->isoFormat('D MMM') }}
                {{ \Carbon\Carbon::parse($pickup_return_only->pickup_date)->year + 543 }}
                นัดคืนวันที่
                {{ \Carbon\Carbon::parse($pickup_return_only->return_date)->locale('th')->isoFormat('D MMM') }}
                {{ \Carbon\Carbon::parse($pickup_return_only->return_date)->year + 543 }}
            </span>
        @elseif($order->type_order == 3)
            <span class="sub-item" style="margin-left: 20px;">-
                นัดรับวันที่
                {{ \Carbon\Carbon::parse($pickup_return_only->pickup_date)->locale('th')->isoFormat('D MMM') }}
                {{ \Carbon\Carbon::parse($pickup_return_only->pickup_date)->year + 543 }}
                นัดคืนวันที่
                {{ \Carbon\Carbon::parse($pickup_return_only->return_date)->locale('th')->isoFormat('D MMM') }}
                {{ \Carbon\Carbon::parse($pickup_return_only->return_date)->year + 543 }}
            </span>
        @endif













        {{-- <span class="sub-item" style="margin-left: 20px;">- เช่าชุดไทย E03 นัดรับวันที่ 25/12/2567 (คงค้างชำระได้แก่ ค่าประกันชุด: 1,100.00 บาท,ค่าส่วนที่เหลือจากมัดจำ: 660.00 บาท )</span><br> --}}

    </div>





</body>

</html>
