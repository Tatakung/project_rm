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
            @php
                // ถ้าจ่ายเงินมัดจำ ตัวแปร check_payment จะเป็น true
                $check_payment = App\Models\Paymentstatus::where('order_detail_id', $orderdetail->id)
                    ->where('payment_status', 1)
                    ->exists();
            @endphp

            @php
                $count_index = 1;
            @endphp


            @if ($orderdetail->type_order == 1)
                รอก่อน
            @elseif($orderdetail->type_order == 2)
                @if ($check_payment)
                    <tr>
                        <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                        <td>ค่าเช่า{{ $orderdetail->detail_many_one_re->reservation_many_to_one_dress->typedress->type_dress_name }}
                            {{ $orderdetail->detail_many_one_re->reservation_many_to_one_dress->typedress->specific_letter }}{{ $orderdetail->detail_many_one_re->reservation_many_to_one_dress->dress_code }}
                            <br>
                            <span class="sub-item" style="color: rgb(133, 126, 126) ; ">หักเงินมัดจำ (จ่ายเมื่อวันที่
                                {{ \Carbon\Carbon::parse($date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                {{ \Carbon\Carbon::parse($date->pickup_date)->year + 543 }})</span><br>
                            <span class="sub-item">คงเหลือชำระ</span>
                        </td>
                        <td class="text-center" style="vertical-align: top;">1</td>
                        <td class="text-center" style="vertical-align: top;">
                            {{ number_format($orderdetail->price, 2) }}</td>
                        <td class="text-center" style="vertical-align: top;">
                            {{ number_format($orderdetail->price, 2) }} <br>
                            <span class="sub-item"
                                style="color: rgb(133, 126, 126) ; ">{{ number_format($orderdetail->deposit, 2) }}</span><br>
                            <span
                                class="sub-item">{{ number_format($orderdetail->price - $orderdetail->deposit, 2) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                        <td>ค่าประกัน{{ $orderdetail->detail_many_one_re->reservation_many_to_one_dress->typedress->type_dress_name }}
                            {{ $orderdetail->detail_many_one_re->reservation_many_to_one_dress->typedress->specific_letter }}{{ $orderdetail->detail_many_one_re->reservation_many_to_one_dress->dress_code }}
                        </td>
                        <td class="text-center" style="vertical-align: top;">1</td>
                        <td class="text-center" style="vertical-align: top;">
                            {{ number_format($orderdetail->damage_insurance, 2) }}</td>
                        <td class="text-center" style="vertical-align: top;">
                            {{ number_format($orderdetail->damage_insurance, 2) }}</td>
                    </tr>
                    @for ($i = $count_index; $i <= 11; $i++)
                        <tr>
                            <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                            <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                            <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                        </tr>
                    @endfor
                @else
                    <tr>
                        <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                        <td>ค่าเช่า{{ $orderdetail->detail_many_one_re->reservation_many_to_one_dress->typedress->type_dress_name }}
                            {{ $orderdetail->detail_many_one_re->reservation_many_to_one_dress->typedress->specific_letter }}{{ $orderdetail->detail_many_one_re->reservation_many_to_one_dress->dress_code }}
                            <br>
                            <span class="sub-item" style="color: rgb(133, 126, 126) ; ">ชำระแล้ว (จ่ายเมื่อวันที่
                                {{ \Carbon\Carbon::parse($date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                {{ \Carbon\Carbon::parse($date->pickup_date)->year + 543 }}
                                )</span><br>
                            <span class="sub-item">คงเหลือชำระ</span>
                        </td>
                        <td class="text-center" style="vertical-align: top;">1</td>
                        <td class="text-center" style="vertical-align: top;">
                            {{ number_format($orderdetail->price, 2) }}</td>
                        <td class="text-center" style="vertical-align: top;">
                            {{ number_format($orderdetail->price, 2) }} <br>
                            <span class="sub-item"
                                style="color: rgb(133, 126, 126) ; ">{{ number_format($orderdetail->price, 2) }}</span><br>
                            <span class="sub-item">0.00 </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                        <td>ค่าประกัน{{ $orderdetail->detail_many_one_re->reservation_many_to_one_dress->typedress->type_dress_name }}
                            {{ $orderdetail->detail_many_one_re->reservation_many_to_one_dress->typedress->specific_letter }}{{ $orderdetail->detail_many_one_re->reservation_many_to_one_dress->dress_code }}
                            <br>
                            <span class="sub-item" style="color: rgb(133, 126, 126) ; ">ชำระแล้ว (จ่ายเมื่อวันที่
                                {{ \Carbon\Carbon::parse($date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                {{ \Carbon\Carbon::parse($date->pickup_date)->year + 543 }}
                                )</span><br>
                            <span class="sub-item">คงเหลือชำระ</span>
                        </td>
                        <td class="text-center" style="vertical-align: top;">1</td>
                        <td class="text-center" style="vertical-align: top;">
                            {{ number_format($orderdetail->damage_insurance, 2) }}</td>
                        <td class="text-center" style="vertical-align: top;">
                            {{ number_format($orderdetail->damage_insurance, 2) }} <br>
                            <span class="sub-item"
                                style="color: rgb(133, 126, 126) ; ">{{ number_format($orderdetail->damage_insurance, 2) }}
                            </span> <br>
                            <span class="sub-item">0.00 </span>

                        </td>
                    </tr>
                    @for ($i = $count_index; $i <= 10; $i++)
                        <tr>
                            <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                            <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                            <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                        </tr>
                    @endfor
                @endif
            @elseif($orderdetail->type_order == 3)
                @if ($check_payment)
                    @if ($orderdetail->detail_many_one_re->jewelry_id)
                        <tr>
                            <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                            <td>ค่าเช่า{{ $orderdetail->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->type_jewelry_name }}
                                {{ $orderdetail->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->specific_letter }}{{ $orderdetail->detail_many_one_re->resermanytoonejew->jewelry_code }}
                                <br>
                                <span class="sub-item" style="color: rgb(133, 126, 126) ; ">หักเงินมัดจำ
                                    (จ่ายเมื่อวันที่
                                    {{ \Carbon\Carbon::parse($date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                    {{ \Carbon\Carbon::parse($date->pickup_date)->year + 543 }})</span><br>
                                <span class="sub-item">คงเหลือชำระ</span>
                            </td>
                            <td class="text-center" style="vertical-align: top;">1</td>
                            <td class="text-center" style="vertical-align: top;">
                                {{ number_format($orderdetail->price, 2) }}</td>
                            <td class="text-center" style="vertical-align: top;">
                                {{ number_format($orderdetail->price, 2) }} <br>
                                <span class="sub-item"
                                    style="color: rgb(133, 126, 126) ; ">{{ number_format($orderdetail->deposit, 2) }}</span><br>
                                <span
                                    class="sub-item">{{ number_format($orderdetail->price - $orderdetail->deposit, 2) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                            <td>ค่าประกัน{{ $orderdetail->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->type_jewelry_name }}
                                {{ $orderdetail->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->specific_letter }}{{ $orderdetail->detail_many_one_re->resermanytoonejew->jewelry_code }}
                            </td>
                            <td class="text-center" style="vertical-align: top;">1</td>
                            <td class="text-center" style="vertical-align: top;">
                                {{ number_format($orderdetail->damage_insurance, 2) }}</td>
                            <td class="text-center" style="vertical-align: top;">
                                {{ number_format($orderdetail->damage_insurance, 2) }}</td>
                        </tr>
                        @for ($i = $count_index; $i <= 11; $i++)
                            <tr>
                                <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                                <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                                <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                            </tr>
                        @endfor
                    @elseif($orderdetail->detail_many_one_re->jewelry_set_id)
                        <tr>
                            <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                            <td>ค่าเช่าเซตเครื่องประดับ{{ $orderdetail->detail_many_one_re->resermanytoonejewset->set_name }}
                                <br>
                                <span class="sub-item" style="color: rgb(133, 126, 126) ; ">หักเงินมัดจำ
                                    (จ่ายเมื่อวันที่
                                    {{ \Carbon\Carbon::parse($date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                    {{ \Carbon\Carbon::parse($date->pickup_date)->year + 543 }})</span><br>
                                <span class="sub-item">คงเหลือชำระ</span>
                            </td>
                            <td class="text-center" style="vertical-align: top;">1</td>
                            <td class="text-center" style="vertical-align: top;">
                                {{ number_format($orderdetail->price, 2) }}</td>
                            <td class="text-center" style="vertical-align: top;">
                                {{ number_format($orderdetail->price, 2) }} <br>
                                <span class="sub-item"
                                    style="color: rgb(133, 126, 126) ; ">{{ number_format($orderdetail->deposit, 2) }}</span><br>
                                <span
                                    class="sub-item">{{ number_format($orderdetail->price - $orderdetail->deposit, 2) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                            <td>ค่าประกันเซตเครื่องประดับ{{ $orderdetail->detail_many_one_re->resermanytoonejewset->set_name }}
                            </td>
                            <td class="text-center" style="vertical-align: top;">1</td>
                            <td class="text-center" style="vertical-align: top;">
                                {{ number_format($orderdetail->damage_insurance, 2) }}</td>
                            <td class="text-center" style="vertical-align: top;">
                                {{ number_format($orderdetail->damage_insurance, 2) }}</td>
                        </tr>
                        @for ($i = $count_index; $i <= 11; $i++)
                            <tr>
                                <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                                <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                                <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                            </tr>
                        @endfor
                    @endif
                @else
                    @if ($orderdetail->detail_many_one_re->jewelry_id)
                        <tr>
                            <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                            <td>ค่าเช่า{{ $orderdetail->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->type_jewelry_name }}
                                {{ $orderdetail->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->specific_letter }}{{ $orderdetail->detail_many_one_re->resermanytoonejew->jewelry_code }}
                                <br>
                                <span class="sub-item" style="color: rgb(133, 126, 126) ; ">ชำระแล้ว (จ่ายเมื่อวันที่
                                    {{ \Carbon\Carbon::parse($date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                    {{ \Carbon\Carbon::parse($date->pickup_date)->year + 543 }}
                                    )</span><br>
                                <span class="sub-item">คงเหลือชำระ</span>
                            </td>
                            <td class="text-center" style="vertical-align: top;">1</td>
                            <td class="text-center" style="vertical-align: top;">
                                {{ number_format($orderdetail->price, 2) }}</td>
                            <td class="text-center" style="vertical-align: top;">
                                {{ number_format($orderdetail->price, 2) }} <br>
                                <span class="sub-item"
                                    style="color: rgb(133, 126, 126) ; ">{{ number_format($orderdetail->price, 2) }}</span><br>
                                <span class="sub-item">0.00 </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                            <td>ค่าประกัน{{ $orderdetail->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->type_jewelry_name }}
                                {{ $orderdetail->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->specific_letter }}{{ $orderdetail->detail_many_one_re->resermanytoonejew->jewelry_code }}
                                <br>
                                <span class="sub-item" style="color: rgb(133, 126, 126) ; ">ชำระแล้ว (จ่ายเมื่อวันที่
                                    {{ \Carbon\Carbon::parse($date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                    {{ \Carbon\Carbon::parse($date->pickup_date)->year + 543 }}
                                    )</span><br>
                                <span class="sub-item">คงเหลือชำระ</span>
                            </td>
                            <td class="text-center" style="vertical-align: top;">1</td>
                            <td class="text-center" style="vertical-align: top;">
                                {{ number_format($orderdetail->damage_insurance, 2) }}</td>
                            <td class="text-center" style="vertical-align: top;">
                                {{ number_format($orderdetail->damage_insurance, 2) }} <br>
                                <span class="sub-item"
                                    style="color: rgb(133, 126, 126) ; ">{{ number_format($orderdetail->damage_insurance, 2) }}
                                </span> <br>
                                <span class="sub-item">0.00 </span>

                            </td>
                        </tr>
                        @for ($i = $count_index; $i <= 10; $i++)
                            <tr>
                                <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                                <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                                <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                            </tr>
                        @endfor
                    @elseif($orderdetail->detail_many_one_re->jewelry_set_id)
                        <tr>
                            <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                            <td>ค่าเช่าเซตเครื่องประดับ{{ $orderdetail->detail_many_one_re->resermanytoonejewset->set_name }}
                                <br>
                                <span class="sub-item" style="color: rgb(133, 126, 126) ; ">ชำระแล้ว (จ่ายเมื่อวันที่
                                    {{ \Carbon\Carbon::parse($date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                    {{ \Carbon\Carbon::parse($date->pickup_date)->year + 543 }}
                                    )</span><br>
                                <span class="sub-item">คงเหลือชำระ</span>
                            </td>
                            <td class="text-center" style="vertical-align: top;">1</td>
                            <td class="text-center" style="vertical-align: top;">
                                {{ number_format($orderdetail->price, 2) }}</td>
                            <td class="text-center" style="vertical-align: top;">
                                {{ number_format($orderdetail->price, 2) }} <br>
                                <span class="sub-item"
                                    style="color: rgb(133, 126, 126) ; ">{{ number_format($orderdetail->price, 2) }}</span><br>
                                <span class="sub-item">0.00 </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                            <td>ค่าประกันเซตเครื่องประดับ{{ $orderdetail->detail_many_one_re->resermanytoonejewset->set_name }}
                                <br>
                                <span class="sub-item" style="color: rgb(133, 126, 126) ; ">ชำระแล้ว (จ่ายเมื่อวันที่
                                    {{ \Carbon\Carbon::parse($date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                    {{ \Carbon\Carbon::parse($date->pickup_date)->year + 543 }}
                                    )</span><br>
                                <span class="sub-item">คงเหลือชำระ</span>
                            </td>
                            <td class="text-center" style="vertical-align: top;">1</td>
                            <td class="text-center" style="vertical-align: top;">
                                {{ number_format($orderdetail->damage_insurance, 2) }}</td>
                            <td class="text-center" style="vertical-align: top;">
                                {{ number_format($orderdetail->damage_insurance, 2) }} <br>
                                <span class="sub-item"
                                    style="color: rgb(133, 126, 126) ; ">{{ number_format($orderdetail->damage_insurance, 2) }}
                                </span> <br>
                                <span class="sub-item">0.00 </span>

                            </td>
                        </tr>
                        @for ($i = $count_index; $i <= 10; $i++)
                            <tr>
                                <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                                <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                                <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                            </tr>
                        @endfor
                    @endif

                @endif
            @elseif($orderdetail->type_order == 4)
                @if ($check_payment)
                    <tr>
                        <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                        <td>ค่าเช่าตัด{{ $orderdetail->type_dress }}
                            <br>
                            <span class="sub-item" style="color: rgb(133, 126, 126) ; ">หักเงินมัดจำ (จ่ายเมื่อวันที่
                                {{ \Carbon\Carbon::parse($date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                {{ \Carbon\Carbon::parse($date->pickup_date)->year + 543 }})</span><br>
                            <span class="sub-item">คงเหลือชำระ</span>
                        </td>
                        <td class="text-center" style="vertical-align: top;">1</td>
                        <td class="text-center" style="vertical-align: top;">
                            {{ number_format($orderdetail->price, 2) }}</td>
                        <td class="text-center" style="vertical-align: top;">
                            {{ number_format($orderdetail->price, 2) }} <br>
                            <span class="sub-item"
                                style="color: rgb(133, 126, 126) ; ">{{ number_format($orderdetail->deposit, 2) }}</span><br>
                            <span
                                class="sub-item">{{ number_format($orderdetail->price - $orderdetail->deposit, 2) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                        <td>ค่าประกันเช่าตัด{{ $orderdetail->type_dress }}
                        </td>
                        <td class="text-center" style="vertical-align: top;">1</td>
                        <td class="text-center" style="vertical-align: top;">
                            {{ number_format($orderdetail->damage_insurance, 2) }}</td>
                        <td class="text-center" style="vertical-align: top;">
                            {{ number_format($orderdetail->damage_insurance, 2) }}</td>
                    </tr>

                    @foreach ($decoration as $item)
                        <tr>
                            <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                            <td>{{ $item->decoration_description }}</td>
                            <td class="text-center" style="vertical-align: top;">1</td>
                            <td class="text-center" style="vertical-align: top;">
                                {{ number_format($item->decoration_price, 2) }}</td>
                            <td class="text-center" style="vertical-align: top;">
                                {{ number_format($item->decoration_price, 2) }}</td>
                        </tr>
                    @endforeach


                    @for ($i = $count_index; $i <= 11; $i++)
                        <tr>
                            <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                            <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                            <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                        </tr>
                    @endfor
                @else
                    <tr>
                        <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                        <td>ค่าเช่าตัด{{ $orderdetail->type_dress }}
                            <br>
                            <span class="sub-item" style="color: rgb(133, 126, 126) ; ">ชำระแล้ว (จ่ายเมื่อวันที่
                                {{ \Carbon\Carbon::parse($date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                {{ \Carbon\Carbon::parse($date->pickup_date)->year + 543 }}
                                )</span><br>
                            <span class="sub-item">คงเหลือชำระ</span>
                        </td>
                        <td class="text-center" style="vertical-align: top;">1</td>
                        <td class="text-center" style="vertical-align: top;">
                            {{ number_format($orderdetail->price, 2) }}</td>
                        <td class="text-center" style="vertical-align: top;">
                            {{ number_format($orderdetail->price, 2) }} <br>
                            <span class="sub-item"
                                style="color: rgb(133, 126, 126) ; ">{{ number_format($orderdetail->price, 2) }}</span><br>
                            <span class="sub-item">0.00 </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                        <td>ค่าประกัน{{ $orderdetail->type_dress }}
                            <br>
                            <span class="sub-item" style="color: rgb(133, 126, 126) ; ">ชำระแล้ว (จ่ายเมื่อวันที่
                                {{ \Carbon\Carbon::parse($date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                {{ \Carbon\Carbon::parse($date->pickup_date)->year + 543 }}
                                )</span><br>
                            <span class="sub-item">คงเหลือชำระ</span>
                        </td>
                        <td class="text-center" style="vertical-align: top;">1</td>
                        <td class="text-center" style="vertical-align: top;">
                            {{ number_format($orderdetail->damage_insurance, 2) }}</td>
                        <td class="text-center" style="vertical-align: top;">
                            {{ number_format($orderdetail->damage_insurance, 2) }} <br>
                            <span class="sub-item"
                                style="color: rgb(133, 126, 126) ; ">{{ number_format($orderdetail->damage_insurance, 2) }}
                            </span> <br>
                            <span class="sub-item">0.00 </span>

                        </td>
                    </tr>
                    @foreach ($decoration as $item)
                    <tr>
                        <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                        <td>{{ $item->decoration_description }}</td>
                        <td class="text-center" style="vertical-align: top;">1</td>
                        <td class="text-center" style="vertical-align: top;">
                            {{ number_format($item->decoration_price, 2) }}</td>
                        <td class="text-center" style="vertical-align: top;">
                            {{ number_format($item->decoration_price, 2) }}</td>
                    </tr>
                @endforeach

                    @for ($i = $count_index; $i <= 10; $i++)
                        <tr>
                            <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                            <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                            <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                        </tr>
                    @endfor
                @endif
            @endif





            {{-- <tr>
                <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                <td>ค่าเช่าชุดไทย V3 <br>
                    <span class="sub-item" style="color: rgb(73, 66, 66) ; ">หักเงินมัดจำ (จ่ายเมื่อวันที่ 1 มกราคม 2567
                        )</span><br>
                    <span class="sub-item">คงเหลือชำระ</span>
                </td>
                <td class="text-center" style="vertical-align: top;">1</td>
                <td class="text-center" style="vertical-align: top;">1500</td>
                <td class="text-center" style="vertical-align: top;">1500 <br>
                    <span class="sub-item" style="color: rgb(73, 66, 66) ; ">450.00</span><br>
                    <span class="sub-item">1050.00</span>
                </td>
            </tr>
            <tr>
                <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                <td>ค่าประกันชุดไทย V3</td>
                <td class="text-center" style="vertical-align: top;">1</td>
                <td class="text-center" style="vertical-align: top;">1500</td>
                <td class="text-center" style="vertical-align: top;">1500</td>
            </tr> --}}









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


        @if ($orderdetail->type_order == 1)
        @elseif($orderdetail->type_order == 2)
            <span class="sub-item" style="margin-left: 20px;">- กำหนดคืนชุดวันที่
                {{ \Carbon\Carbon::parse($date->return_date)->locale('th')->isoFormat('D MMM') }}
                {{ \Carbon\Carbon::parse($date->return_date)->year + 543 }}</span><br>
            <span class="sub-item" style="margin-left: 20px;">-
                จะได้รับเงินประกันคืนเมื่อคืนชุดตามกำหนดและชุดอยู่ในสภาพสมบูรณ์</span><br>
            <span class="sub-item" style="margin-left: 20px;">- กรณีส่งคืนชุดล่าช้า หากเกินกำหนดวันคืนชุด
                ผู้เช่าจะต้องชำระค่าปรับเพิ่มเติม คิดเป็นวันละ 200 บาท/ชุด</span><br>
        @elseif($orderdetail->type_order == 3)
            <span class="sub-item" style="margin-left: 20px;">- กำหนดคืนเครื่องประดับวันที่
                {{ \Carbon\Carbon::parse($date->return_date)->locale('th')->isoFormat('D MMM') }}
                {{ \Carbon\Carbon::parse($date->return_date)->year + 543 }}</span><br>
            <span class="sub-item" style="margin-left: 20px;">-
                จะได้รับเงินประกันคืนเมื่อคืนเครื่องประดับตามกำหนดและชุดอยู่ในสภาพสมบูรณ์</span><br>
            <span class="sub-item" style="margin-left: 20px;">- กรณีส่งคืนล่าช้า หากเกินกำหนดวันคืน
                ผู้เช่าจะต้องชำระค่าปรับเพิ่มเติม คิดเป็นวันละ 200 บาท</span><br>
        @elseif($orderdetail->type_order == 4)
            <span class="sub-item" style="margin-left: 20px;">- กำหนดคืนชุดวันที่
                {{ \Carbon\Carbon::parse($date->return_date)->locale('th')->isoFormat('D MMM') }}
                {{ \Carbon\Carbon::parse($date->return_date)->year + 543 }}</span><br>
            <span class="sub-item" style="margin-left: 20px;">-
                จะได้รับเงินประกันคืนเมื่อคืนชุดตามกำหนดและชุดอยู่ในสภาพสมบูรณ์</span><br>
            <span class="sub-item" style="margin-left: 20px;">- กรณีส่งคืนชุดล่าช้า หากเกินกำหนดวันคืนชุด
                ผู้เช่าจะต้องชำระค่าปรับเพิ่มเติม คิดเป็นวันละ 200 บาท/ชุด</span><br>
        @endif



    </div>





</body>

</html>
