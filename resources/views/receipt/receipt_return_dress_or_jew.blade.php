<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <title>ใบเสร็จรับเงินมัดจำรวม</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: 'THSarabunNew', sans-serif;
            margin: 20px;
            font-size: 20px;
            line-height: 0.4;
            /* ปรับระยะห่างของบรรทัด */
        }

        .receipt {
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
            /* border: 1px solid #000000; */
        }

        .header,
        .footer {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="receipt">
        {{-- ใหญ่สุด --}}


        <div class="header">
            <h2>ร้านเปลือกไหม</h2>
            <p>81/1 ถ.โพนพิสัย ต.หมากแข้ง อ.เมือง จ.อุดรธานี 41000</p>
            <p>โทร: 081-8717791</p>

            @if ($orderdetail->type_order == 2)
                <h2>ใบรับคืนเงินประกัน</h2>
            @elseif($orderdetail->type_order == 3)
                <h2>ใบรับคืนเงินประกัน</h2>
            @elseif($orderdetail->type_order == 4)
                <h2>ใบรับคืนเงินประกัน</h2>
            @endif
        </div>

        <div class="row">
            <div class="col-md-6">
                <p><strong>เลขที่ใบเสร็จ:</strong> REC20241201-001</p>
                <p><strong>วันที่:</strong>
                    {{ \Carbon\Carbon::parse($date_now)->locale('th')->isoFormat('D MMM') }}
                    {{ \Carbon\Carbon::parse($date_now)->year + 543 }}
                </p>
                <p><strong>เวลา:</strong> {{ \Carbon\Carbon::parse($date_now)->locale('th')->isoFormat('H:mm') }} น.</p>
            </div>
            <div class="col-md-6">
                <p><strong>ชื่อลูกค้า:</strong> คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }}</p>
                <p><strong>เบอร์โทร:</strong> {{ $customer->customer_phone }}</p>
            </div>
        </div>

        @if ($orderdetail->type_order == 2)
            @if ($orderdetail->shirtitems_id)
                <p><strong>รายการ: เช่า{{ $orderdetail->orderdetailmanytoonedress->typedress->type_dress_name }}
                        {{ $orderdetail->orderdetailmanytoonedress->typedress->specific_letter }}{{ $orderdetail->orderdetailmanytoonedress->dress_code }}
                        (เสื้อ)</strong></p>
            @elseif($orderdetail->skirtitems_id)
                <p><strong>รายการ: เช่า{{ $orderdetail->orderdetailmanytoonedress->typedress->type_dress_name }}
                        {{ $orderdetail->orderdetailmanytoonedress->typedress->specific_letter }}{{ $orderdetail->orderdetailmanytoonedress->dress_code }}
                        (ผ้าถุง)</strong></p>
            @else
                <p><strong>รายการ: เช่า{{ $orderdetail->orderdetailmanytoonedress->typedress->type_dress_name }}
                        {{ $orderdetail->orderdetailmanytoonedress->typedress->specific_letter }}{{ $orderdetail->orderdetailmanytoonedress->dress_code }}</strong>
                </p>
            @endif
        @elseif($orderdetail->type_order == 3)
            @if ($orderdetail->detail_many_one_re->jewelry_id)
                <p><strong>รายการ:
                        เช่า{{ $orderdetail->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->type_jewelry_name }}
                        {{ $orderdetail->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->specific_letter }}{{ $orderdetail->detail_many_one_re->resermanytoonejew->jewelry_code }}</strong>
                </p>
            @elseif($orderdetail->detail_many_one_re->detail_many_one_re->jewelry_set_id)
                <p><strong>รายการ:
                        เช่าเซต{{ $orderdetail->detail_many_one_re->resermanytoonejewset->set_name }}</strong>
                </p>
            @endif
        @elseif($orderdetail->type_order == 4)
            <p><strong>รายการ:
                    เช่าตัด{{$orderdetail->type_dress}}</strong>
            </p>
        @endif

        <table>
            <thead>
                <tr>
                    <th>รายการ</th>
                    <th>จำนวนเงิน</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>เงินประกันที่รับมา</td>
                    <td> {{ number_format($orderdetail->damage_insurance, 2) }} </td>
                </tr>


                @foreach ($addittionnal as $item)
                    @if ($item->charge_type == 1)
                        <tr>
                            <td>หัก : ค่าเสียหาย</td>
                            <td> {{ number_format($item->amount, 2) }} </td>
                        </tr>
                    @endif
                @endforeach


                @if (!$check_has_damage_insurance)
                    <tr>
                        <td>หัก : ค่าเสียหาย (ไม่มี)</td>
                        <td>0.00</td>
                    </tr>
                @endif







                @foreach ($addittionnal as $item)
                    @if ($item->charge_type == 2)
                        <tr>
                            <td>หัก : ค่าปรับส่งคืนชุดล่าช้า</td>
                            <td> {{ number_format($item->amount, 2) }} </td>
                        </tr>
                    @endif
                @endforeach

                @foreach ($addittionnal as $item)
                    @if ($item->charge_type == 3)
                        <tr>
                            <td>หัก : ค่าธรรมเนียมขยายระยะเวลาเช่า</td>
                            <td> {{ number_format($item->amount, 2) }} </td>
                        </tr>
                    @endif
                @endforeach




            </tbody>
        </table>

        <p class="total"><strong>รวมทั้งสิ้น:</strong>บาท</p>
        {{-- <p class="total"><strong>ยอดคงเหลือทั้งหมด:</strong> 3,700.00 บาท</p> --}}

        <div class="footer">
            <p>____________________</p>
            <p>ลายเซ็นผู้รับเงิน</p>
            <p>____________________</p>
            <p>ลายเซ็นลูกค้า</p>
        </div>
        <div>
            <p><strong>หมายเหตุ:</strong></p>

            @if (!$check_has_damage_insurance)
                <p>ชุดได้รับการตรวจสอบแล้ว อยู่ในสภาพสมบูรณ์</p>
            @else
                <p>ชุดมีความเสียหาย</p>
            @endif



        </div>
    </div>
</body>

</html>
