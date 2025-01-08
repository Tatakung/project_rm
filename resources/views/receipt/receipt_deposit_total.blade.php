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
            {{-- <tr>
                <td class="text-center" style="vertical-align: top;">1</td>
                <td>ชำระมัดจำตัดชุดราตรี<br>
                    <span class="sub-item" style="margin-left: 20px;">- ราคาเช่า 1300 บาท</span><br>
                </td>
                <td class="text-center" style="vertical-align: top;">1.00
                </td>
                <td class="text-center" style="vertical-align: top;">500.00</td>
                <td class="text-center" style="vertical-align: top;">250.00</td>
            </tr>
            <tr>
                <td class="text-center" style="vertical-align: top;">2</td>
                <td>ชำระค่าเช่าชุดไทย A01 + เงินประกันชุด<br>
                    <span class="sub-item" style="margin-left: 20px;">- ราคาเช่า 1300 บาท</span><br>
                    <span class="sub-item" style="margin-left: 20px;">- ค่าประกันชุด 1300 บาท</span>
                </td>
                <td class="text-center" style="vertical-align: top;">1.00</td>
                <td class="text-center" style="vertical-align: top;">200.00</td>
                <td class="text-center" style="vertical-align: top;">100.00</td>
            </tr> --}}

            @foreach ($orderdetail as $index => $item)
                <tr>
                    <td class="text-center" style="vertical-align: top;">{{ $index + 1 }}</td>

                    <td>


                        @php
                            // ถ้าจ่ายเงินมัดจำ ตัวแปร check_payment จะเป็น true
                            $check_payment = App\Models\Paymentstatus::where('order_detail_id', $item->id)
                                ->where('payment_status', 1)
                                ->exists();
                        @endphp
                        @if ($item->type_order == 1)
                            @if ($check_payment)
                                ชำระมัดจำ{{ $item->type_dress }}<br>
                                {{-- <span class="sub-item" style="margin-left: 20px;">- ราคาเช่า
                                    {{ number_format($item->price, 2) }} บาท</span><br> --}}
                            @else
                                ชำระค่าตัด{{ $item->type_dress }}<br>
                            @endif
                        @elseif($item->type_order == 2)
                            @if ($check_payment)
                                ชำระมัดจำค่าเช่า{{ $item->orderdetailmanytoonedress->typedress->type_dress_name }}
                                {{ $item->orderdetailmanytoonedress->typedress->specific_letter }}{{ $item->orderdetailmanytoonedress->dress_code }}
                                <br>
                                {{-- <span class="sub-item" style="margin-left: 20px;">- ราคาเช่า
                                    {{ number_format($item->price, 2) }} บาท</span><br> --}}
                            @else
                                ชำระค่าเช่า{{ $item->orderdetailmanytoonedress->typedress->type_dress_name }}
                                {{ $item->orderdetailmanytoonedress->typedress->specific_letter }}{{ $item->orderdetailmanytoonedress->dress_code }}
                                + เงินประกันชุด <br>
                                {{-- <span class="sub-item" style="margin-left: 20px;">- ราคาเช่า
                                    {{ number_format($item->price, 2) }} บาท</span><br>
                                <span class="sub-item" style="margin-left: 20px;">- ค่าประกันชุด
                                    {{ number_format($item->damage_insurance, 2) }} บาท</span> --}}
                            @endif
                        @elseif($item->type_order == 3)
                            @if ($check_payment)
                                @if ($item->detail_many_one_re->jewelry_id)
                                    ชำระมัดจำค่าเช่า{{ $item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->type_jewelry_name }}
                                    {{ $item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->specific_letter }}{{ $item->detail_many_one_re->resermanytoonejew->jewelry_code }}
                                    <br>
                                    {{-- <span class="sub-item" style="margin-left: 20px;">- ราคาเช่า
                                        {{ number_format($item->price, 2) }} บาท</span><br> --}}
                                @elseif($item->detail_many_one_re->jewelry_set_id)
                                    ชำระมัดจำค่าเช่าเซตเครื่องประดับ{{ $item->detail_many_one_re->resermanytoonejewset->set_name }}
                                    <br>
                                    {{-- <span class="sub-item" style="margin-left: 20px;">- ราคาเช่า
                                        {{ number_format($item->price, 2) }} บาท</span><br> --}}
                                @endif
                            @else
                                @if ($item->detail_many_one_re->jewelry_id)
                                    ชำระค่าเช่า{{ $item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->type_jewelry_name }}
                                    {{ $item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->specific_letter }}{{ $item->detail_many_one_re->resermanytoonejew->jewelry_code }}
                                    + เงินประกันเครื่องประดับ <br>
                                    {{-- <span class="sub-item" style="margin-left: 20px;">- ราคาเช่า
                                        {{ number_format($item->price, 2) }} บาท</span><br>
                                    <span class="sub-item" style="margin-left: 20px;">- ค่าประกันเครื่องประดับ --}}
                                    {{ number_format($item->damage_insurance, 2) }} บาท</span>
                                @elseif($item->detail_many_one_re->jewelry_set_id)
                                    ชำระค่าเช่าเซตเครื่องประดับ{{ $item->detail_many_one_re->resermanytoonejewset->set_name }}
                                    + เงินประกันเครื่องประดับ <br>
                                    {{-- <span class="sub-item" style="margin-left: 20px;">- ราคาเช่า
                                        {{ number_format($item->price, 2) }} บาท</span><br>
                                    <span class="sub-item" style="margin-left: 20px;">- ค่าประกันเครื่องประดับ
                                        {{ number_format($item->damage_insurance, 2) }} บาท</span> --}}
                                @endif
                            @endif
                        @elseif($item->type_order == 4)
                            @if ($check_payment)
                                ชำระมัดจำค่าเช่าตัด{{ $item->type_dress }}
                            @else
                                ชำระค่าเช่าตัด{{ $item->type_dress }} + เงินประกันชุด <br>
                            @endif
                        @endif




                    </td>

                    <td class="text-center" style="vertical-align: top;">{{ number_format($item->amount, 2) }}</td>

                    <td class="text-center" style="vertical-align: top;">
                        @if ($check_payment)
                            {{ number_format($item->deposit, 2) }}
                        @else
                            {{ number_format($item->price + $item->damage_insurance, 2) }}
                        @endif

                    </td>

                    <td class="text-center" style="vertical-align: top;">
                        @if ($check_payment)
                            {{ number_format($item->deposit, 2) }}
                        @else
                            {{ number_format($item->price + $item->damage_insurance, 2) }}
                        @endif
                    </td>

                </tr>
            @endforeach
            @for ($i = count($orderdetail); $i <= 11; $i++)
                <tr>
                    <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                    <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                    <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                </tr>
            @endfor

            <tr>
                <td class="text-center" style="vertical-align: top ;   border-top: 1px solid black;" colspan="4">รวมเงิน</td>
                <td class="text-center" style="vertical-align: top; border-top: 1px solid black;">{{ $receipt->total_price }}</td>
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
                คุณ{{$customer->customer_fname}} {{ $customer->customer_lname }} <br>
                วันที่ {{ \Carbon\Carbon::parse($receipt->created_at)->locale('th')->isoFormat('D MMM') }}
                {{ \Carbon\Carbon::parse($receipt->created_at)->year + 543 }}
            </td>
        </tr>
    </table>


    <div style="margin-top: 10px;">
        <p>หมายเหตุ :</p>



        @foreach ($orderdetail as $item)
            @php
                // ถ้าจ่ายเงินมัดจำ ตัวแปร check_payment จะเป็น true
                $check_payment_note = App\Models\Paymentstatus::where('order_detail_id', $item->id)
                    ->where('payment_status', 1)
                    ->exists();

                $date_note = App\Models\Date::where('order_detail_id', $item->id)
                    ->orderBy('created_at', 'desc')
                    ->first();

            @endphp
            @if ($item->type_order == 1)
                @if ($check_payment_note)
                    <span class="sub-item" style="margin-left: 20px;">- ตัด{{ $item->type_dress }} นัดรับ
                        {{ \Carbon\Carbon::parse($date_note->pickup_date)->locale('th')->isoFormat('D MMM') }}
                        {{ \Carbon\Carbon::parse($date_note->pickup_date)->year + 543 }}
                        (คงค้างชำระ: ค่าส่วนที่เหลือจากมัดจำ: {{ number_format($item->price - $item->deposit , 2 ) }} บาท )
                    </span><br>
                @else
                    <span class="sub-item" style="margin-left: 20px;">- ตัด{{ $item->type_dress }} นัดรับ
                        {{ \Carbon\Carbon::parse($date_note->pickup_date)->locale('th')->isoFormat('D MMM') }}
                        {{ \Carbon\Carbon::parse($date_note->pickup_date)->year + 543 }}
                        (คงค้างชำระ :0.00 บาท)</span><br>
                @endif
            @elseif($item->type_order == 2)
                @if ($check_payment_note)
                    <span class="sub-item" style="margin-left: 20px;">-
                        เช่า{{ $item->orderdetailmanytoonedress->typedress->type_dress_name }}
                        {{ $item->orderdetailmanytoonedress->typedress->specific_letter }}{{ $item->orderdetailmanytoonedress->dress_code }}
                        นัดรับ
                        {{ \Carbon\Carbon::parse($date_note->pickup_date)->locale('th')->isoFormat('D MMM') }}
                        {{ \Carbon\Carbon::parse($date_note->pickup_date)->year + 543 }}

                        นัดคืน
                        {{ \Carbon\Carbon::parse($date_note->return_date)->locale('th')->isoFormat('D MMM') }}
                        {{ \Carbon\Carbon::parse($date_note->return_date)->year + 543 }}
                        (คงค้างชำระ: ค่าประกันชุด: {{  number_format($item->damage_insurance , 2 ) }} บาท,ค่าส่วนที่เหลือ: {{ number_format($item->price - $item->deposit , 2 ) }} บาท )
                @else
                    <span class="sub-item" style="margin-left: 20px;">-
                        เช่า{{ $item->orderdetailmanytoonedress->typedress->type_dress_name }}
                        {{ $item->orderdetailmanytoonedress->typedress->specific_letter }}{{ $item->orderdetailmanytoonedress->dress_code }}
                        นัดรับ
                        {{ \Carbon\Carbon::parse($date_note->pickup_date)->locale('th')->isoFormat('D MMM') }}
                        {{ \Carbon\Carbon::parse($date_note->pickup_date)->year + 543 }}

                        นัดคืน
                        {{ \Carbon\Carbon::parse($date_note->return_date)->locale('th')->isoFormat('D MMM') }}
                        {{ \Carbon\Carbon::parse($date_note->return_date)->year + 543 }}
                        (คงค้างชำระ :0.00 บาท)</span><br>
                @endif
            @elseif($item->type_order == 3)
                @if ($check_payment_note)
                    @if ($item->detail_many_one_re->jewelry_id)
                        <span class="sub-item" style="margin-left: 20px;">-
                            เช่า{{ $item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->type_jewelry_name }}
                            {{ $item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->specific_letter }}{{ $item->detail_many_one_re->resermanytoonejew->jewelry_code }}
                            นัดรับ
                            {{ \Carbon\Carbon::parse($date_note->pickup_date)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($date_note->pickup_date)->year + 543 }}

                            นัดคืน
                            {{ \Carbon\Carbon::parse($date_note->return_date)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($date_note->return_date)->year + 543 }}
                            (คงค้างชำระ: ค่าประกัน: {{  number_format($item->damage_insurance , 2 ) }} บาท,ค่าส่วนที่เหลือ: {{ number_format($item->price - $item->deposit , 2 ) }} บาท )
                        </span><br>
                    @elseif($item->detail_many_one_re->jewelry_set_id)
                        <span class="sub-item" style="margin-left: 20px;">-
                            เช่าเซตเครื่องประดับ{{ $item->detail_many_one_re->resermanytoonejewset->set_name }}
                            นัดรับ
                            {{ \Carbon\Carbon::parse($date_note->pickup_date)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($date_note->pickup_date)->year + 543 }}

                            นัดคืน
                            {{ \Carbon\Carbon::parse($date_note->return_date)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($date_note->return_date)->year + 543 }}
                            (คงค้างชำระ: ค่าประกัน: {{  number_format($item->damage_insurance , 2 ) }} บาท,ค่าส่วนที่เหลือ: {{ number_format($item->price - $item->deposit , 2 ) }} บาท )
                        </span><br>
                    @endif
                @else
                    @if ($item->detail_many_one_re->jewelry_id)
                        <span class="sub-item" style="margin-left: 20px;">-
                            เช่า{{ $item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->type_jewelry_name }}
                            {{ $item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->specific_letter }}{{ $item->detail_many_one_re->resermanytoonejew->jewelry_code }}
                            นัดรับ
                            {{ \Carbon\Carbon::parse($date_note->pickup_date)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($date_note->pickup_date)->year + 543 }}
                            นัดคืน
                            {{ \Carbon\Carbon::parse($date_note->return_date)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($date_note->return_date)->year + 543 }}
                            (คงค้างชำระ
                            :0.00 บาท)
                        </span><br>
                    @elseif($item->detail_many_one_re->jewelry_set_id)
                        <span class="sub-item" style="margin-left: 20px;">-
                            เช่าเซตเครื่องประดับ{{ $item->detail_many_one_re->resermanytoonejewset->set_name }}
                            นัดรับ
                            {{ \Carbon\Carbon::parse($date_note->pickup_date)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($date_note->pickup_date)->year + 543 }}
                            นัดคืน
                            {{ \Carbon\Carbon::parse($date_note->return_date)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($date_note->return_date)->year + 543 }}
                            (คงค้างชำระ
                            :0.00 บาท)
                        </span><br>
                    @endif
                @endif
            @elseif($item->type_order == 4)
                @if ($check_payment_note)
                    <span class="sub-item" style="margin-left: 20px;">-
                        เช่าตัด{{ $item->type_dress }}
                        นัดรับ
                        {{ \Carbon\Carbon::parse($date_note->pickup_date)->locale('th')->isoFormat('D MMM') }}
                        {{ \Carbon\Carbon::parse($date_note->pickup_date)->year + 543 }}

                        นัดคืน
                        {{ \Carbon\Carbon::parse($date_note->return_date)->locale('th')->isoFormat('D MMM') }}
                        {{ \Carbon\Carbon::parse($date_note->return_date)->year + 543 }}
                        (คงค้างชำระ: ค่าประกันชุด: {{  number_format($item->damage_insurance , 2 ) }} บาท,ค่าส่วนที่เหลือ: {{ number_format($item->price - $item->deposit , 2 ) }} บาท )
                    </span><br>
                @else
                    <span class="sub-item" style="margin-left: 20px;">-
                        เช่าตัด{{ $item->type_dress }}
                        นัดรับ
                        {{ \Carbon\Carbon::parse($date_note->pickup_date)->locale('th')->isoFormat('D MMM') }}
                        {{ \Carbon\Carbon::parse($date_note->pickup_date)->year + 543 }}

                        นัดคืน
                        {{ \Carbon\Carbon::parse($date_note->return_date)->locale('th')->isoFormat('D MMM') }}
                        {{ \Carbon\Carbon::parse($date_note->return_date)->year + 543 }}
                        (คงค้างชำระ
                        :0.00 บาท)
                    </span><br>
                @endif
            @endif
        @endforeach









        {{-- <span class="sub-item" style="margin-left: 20px;">- เช่าชุดไทย E03 นัดรับวันที่ 25/12/2567 (คงค้างชำระได้แก่ ค่าประกันชุด: 1,100.00 บาท,ค่าส่วนที่เหลือจากมัดจำ: 660.00 บาท )</span><br> --}}

    </div>





</body>

</html>
