<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ใบเสร็จคืนเงินประกัน</title>
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
                โทรศัพท์ 098-1472-866<br>
                เลขประจำตัวผู้เสียภาษี 1623651970
            </td>
            <td width="35%" style="text-align: right;">
                <strong style="font-size: 25px;">ใบเสร็จคืนเงินประกัน</strong><br>
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

            @php
                $count_index = 1;
            @endphp

            @foreach ($orderdetails as $item)
                @if ($item->type_order == 2)
                    @if ($item->shirtitems_id)
                        <tr>
                            <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                            <td>เงินประกันที่รับมา
                                (เช่า{{ $item->detail_many_one_re->reservation_many_to_one_dress->typedress->type_dress_name }}{{ $item->detail_many_one_re->reservation_many_to_one_dress->typedress->specific_letter }}{{ $item->detail_many_one_re->reservation_many_to_one_dress->dress_code }}
                                เสื้อ)
                            </td>
                            <td class="text-center" style="vertical-align: top;">1</td>
                            <td class="text-center" style="vertical-align: top;">
                                {{ number_format($item->damage_insurance, 2) }}</td>
                            <td class="text-center" style="vertical-align: top;">
                                {{ number_format($item->damage_insurance, 2) }} <br>
                            </td>
                        </tr>
                    @elseif($item->skirtitems_id)
                        <tr>
                            <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                            <td>เงินประกันที่รับมา
                                (เช่า{{ $item->detail_many_one_re->reservation_many_to_one_dress->typedress->type_dress_name }}{{ $item->detail_many_one_re->reservation_many_to_one_dress->typedress->specific_letter }}{{ $item->detail_many_one_re->reservation_many_to_one_dress->dress_code }}
                                ผ้าถุง)
                            </td>
                            <td class="text-center" style="vertical-align: top;">1</td>
                            <td class="text-center" style="vertical-align: top;">
                                {{ number_format($item->damage_insurance, 2) }}</td>
                            <td class="text-center" style="vertical-align: top;">
                                {{ number_format($item->damage_insurance, 2) }} <br>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                            <td>เงินประกันที่รับมา
                                (เช่า{{ $item->detail_many_one_re->reservation_many_to_one_dress->typedress->type_dress_name }}{{ $item->detail_many_one_re->reservation_many_to_one_dress->typedress->specific_letter }}{{ $item->detail_many_one_re->reservation_many_to_one_dress->dress_code }}
                                ทั้งชุด)
                            </td>
                            <td class="text-center" style="vertical-align: top;">1</td>
                            <td class="text-center" style="vertical-align: top;">
                                {{ number_format($item->damage_insurance, 2) }}</td>
                            <td class="text-center" style="vertical-align: top;">
                                {{ number_format($item->damage_insurance, 2) }} <br>
                            </td>
                        </tr>
                    @endif
                @elseif($item->type_order == 3)
                    @if ($item->detail_many_one_re->jewelry_id)
                        <tr>
                            <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                            <td>เงินประกันที่รับมา
                                (เช่า{{ $item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->type_jewelry_name }}{{ $item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->specific_letter }}{{ $item->detail_many_one_re->resermanytoonejew->jewelry_code }})
                            </td>
                            <td class="text-center" style="vertical-align: top;">1</td>
                            <td class="text-center" style="vertical-align: top;">
                                {{ number_format($item->damage_insurance, 2) }}</td>
                            <td class="text-center" style="vertical-align: top;">
                                {{ number_format($item->damage_insurance, 2) }} <br>
                            </td>
                        </tr>
                    @elseif($item->detail_many_one_re->jewelry_set_id)
                        <tr>
                            <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                            <td>เงินประกันที่รับมา
                                (เช่าเซตเครื่องประดับ{{ $item->detail_many_one_re->resermanytoonejewset->set_name }})
                            </td>
                            <td class="text-center" style="vertical-align: top;">1</td>
                            <td class="text-center" style="vertical-align: top;">
                                {{ number_format($item->damage_insurance, 2) }}</td>
                            <td class="text-center" style="vertical-align: top;">
                                {{ number_format($item->damage_insurance, 2) }} <br>
                            </td>
                        </tr>
                    @endif
                @elseif($item->type_order == 4)
                    <tr>
                        <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                        <td>เงินประกันที่รับมา
                            (เช่าตัด{{ $item->type_dress }})
                        </td>
                        <td class="text-center" style="vertical-align: top;">1</td>
                        <td class="text-center" style="vertical-align: top;">
                            {{ number_format($item->damage_insurance, 2) }}</td>
                        <td class="text-center" style="vertical-align: top;">
                            {{ number_format($item->damage_insurance, 2) }} <br>
                        </td>
                    </tr>
                @endif
            @endforeach

            @if ($price_damage_insurance == 0)
                <tr>
                    <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                    <td>หักค่าเสียหายทั้งหมด
                    </td>
                    <td class="text-center" style="vertical-align: top;">1</td>
                    <td class="text-center" style="vertical-align: top;">
                        0.00</td>
                    <td class="text-center" style="vertical-align: top;">
                        0.00 <br>
                    </td>
                </tr>
            @elseif($price_damage_insurance > 0)
                <tr>
                    <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                    <td>หักค่าเสียหายทั้งหมด
                    </td>
                    <td class="text-center" style="vertical-align: top;">1</td>
                    <td class="text-center" style="vertical-align: top;">
                        {{ number_format($price_damage_insurance, 2) }}</td>
                    <td class="text-center" style="vertical-align: top;">
                        {{ number_format($price_damage_insurance, 2) }} <br>
                    </td>
                </tr>
            @endif


            @if ($price_return_late > 0)
                <tr>
                    <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                    <td>หักค่าปรับส่งคืนล่าช้า
                    </td>
                    <td class="text-center" style="vertical-align: top;">1</td>
                    <td class="text-center" style="vertical-align: top;">
                        {{ number_format($price_return_late, 2) }}</td>
                    <td class="text-center" style="vertical-align: top;">
                        {{ number_format($price_return_late, 2) }} <br>
                    </td>
                </tr>
            @endif
            @if ($price_extend_time > 0)
                <tr>
                    <td class="text-center" style="vertical-align: top;">{{ $count_index++ }}</td>
                    <td>หักค่าธรรมเนียมขยายเวลาเช่า
                    </td>
                    <td class="text-center" style="vertical-align: top;">1</td>
                    <td class="text-center" style="vertical-align: top;">
                        {{ number_format($price_extend_time, 2) }}</td>
                    <td class="text-center" style="vertical-align: top;">
                        {{ number_format($price_extend_time, 2) }} <br>
                    </td>
                </tr>
            @endif




            @for ($i = $count_index; $i <= 13; $i++)
                <tr>
                    <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                    <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                    <td class="text-center" style="vertical-align: top;">&nbsp;</td>
                </tr>
            @endfor

            @if ($receipt->total_price >= 0)
                
                <tr>
                    <td class="text-right" style="vertical-align: top ;   border-top: 1px solid black;" colspan="4">
                        <span>คืนเงินประกัน (บาท)</span><br>
                        <span>ภาษีมูลค่าเพิ่ม 7 % (บาท)</span><br>
                        <span><strong>จำนวนคืนเงินประกันทั้งสิ้น (บาท)</strong></span>
                    </td>
                    <td class="text-center" style="vertical-align: top; border-top: 1px solid black;">
                        <span>{{ $receipt->total_price }}</span><br>
                        <span>0.00</span><br>
                        <span><strong>{{ $receipt->total_price }}</strong></span>
    
                    </td>
                </tr>
    

            @elseif($receipt->total_price < 0)
                


                <tr>
                    <td class="text-right" style="vertical-align: top ;   border-top: 1px solid black;" colspan="4">
                        <span>ลูกค้าต้องชำระเพิ่ม (<i>ค่าเสียหายเกินวงเกินประกัน</i>)</span><br>
                        <span>ภาษีมูลค่าเพิ่ม 7 % (บาท)</span><br>
                        <span><strong>จำนวนเงินที่ต้องชำระเพิ่มทั้งสิ้น (บาท)</strong></span>
                    </td>
                    <td class="text-center" style="vertical-align: top; border-top: 1px solid black;">
                        <span>{{ number_format(abs($receipt->total_price) , 2 ) }}</span><br>
                        <span>0.00</span><br>
                        <span><strong>{{ number_format(abs($receipt->total_price) , 2) }}</strong></span>
    
                    </td>
                </tr>






            @endif
        </tbody>
    </table>
    <!-- ส่วนลายเซ็น -->
    <table style="margin-top: 10px;">

        @if($receipt->total_price >= 0 )
        <tr>
            <td width="50%" class="text-center">
                (..........................................................)<br>
                ผู้จ่ายเงิน<br>
                คุณ{{$receipt->receiptreturn_many_to_one_user->name}} {{$receipt->receiptreturn_many_to_one_user->lname}} (พนักงาน)<br>
                วันที่ {{ \Carbon\Carbon::parse($receipt->created_at)->locale('th')->isoFormat('D MMM') }}
                {{ \Carbon\Carbon::parse($receipt->created_at)->year + 543 }}
            </td>
            <td width="50%" class="text-center">
                (..........................................................)<br>
                ผู้รับเงิน<br>
                คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }}<br>
                วันที่ {{ \Carbon\Carbon::parse($receipt->created_at)->locale('th')->isoFormat('D MMM') }}
                {{ \Carbon\Carbon::parse($receipt->created_at)->year + 543 }}
            </td>
        </tr>
        @elseif($receipt->total_price < 0 )

        <tr>
            <td width="50%" class="text-center">
                (..........................................................)<br>
                ผู้รับเงิน<br>
                คุณ{{$receipt->receiptreturn_many_to_one_user->name}} {{$receipt->receiptreturn_many_to_one_user->lname}} (พนักงาน)<br>
                วันที่ {{ \Carbon\Carbon::parse($receipt->created_at)->locale('th')->isoFormat('D MMM') }}
                {{ \Carbon\Carbon::parse($receipt->created_at)->year + 543 }}
            </td>
            <td width="50%" class="text-center">
                (..........................................................)<br>
                ผู้ชำระเงิน<br>
                คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }}<br>
                วันที่ {{ \Carbon\Carbon::parse($receipt->created_at)->locale('th')->isoFormat('D MMM') }}
                {{ \Carbon\Carbon::parse($receipt->created_at)->year + 543 }}
            </td>
        </tr>

        @endif

        


        
    </table>


    <div style="margin-top: 10px;">
        <p>หมายเหตุ :</p>






    </div>





</body>

</html>
