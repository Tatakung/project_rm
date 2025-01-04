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
            font-size: 16px;
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
            <p>โทร: 081-8717-791</p>
            <h2>ใบเสร็จรับเงินมัดจำ</h2>
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
                {{-- <p><strong>อีเมล:</strong> ploy@email.com</p> --}}
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>รายการ</th>
                    <th>ราคาเต็ม</th>
                    <th>มัดจำ</th>
                    <th>ประกัน</th>
                    <th>คงเหลือ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orderdetail as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>


                            @if ($item->type_order == 1)
                                ตัด{{ $item->type_dress }}
                            @elseif($item->type_order == 2)
                                @if ($item->shirtitems_id)
                                    เช่า{{ $item->orderdetailmanytoonedress->typedress->type_dress_name }}
                                    {{ $item->orderdetailmanytoonedress->typedress->specific_letter }}{{ $item->orderdetailmanytoonedress->dress_code }}
                                    (เสื้อ)
                                @elseif($item->skirtitems_id)
                                    เช่า{{ $item->orderdetailmanytoonedress->typedress->type_dress_name }}
                                    {{ $item->orderdetailmanytoonedress->typedress->specific_letter }}{{ $item->orderdetailmanytoonedress->dress_code }}
                                    (ผ้าถุง)
                                @else
                                    เช่า{{ $item->orderdetailmanytoonedress->typedress->type_dress_name }}
                                    {{ $item->orderdetailmanytoonedress->typedress->specific_letter }}{{ $item->orderdetailmanytoonedress->dress_code }}
                                    (ทั้งชุด)
                                @endif
                            @elseif($item->type_order == 3)
                                @if ($item->detail_many_one_re->jewelry_id)
                                    เช่า{{$item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->type_jewelry_name}} {{$item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->specific_letter}}{{$item->detail_many_one_re->resermanytoonejew->jewelry_code}}
                                @elseif($item->detail_many_one_re->jewelry_set_id)
                                    {{$item->detail_many_one_re->resermanytoonejewset->set_name}}
                                @endif
                            @elseif($item->type_order == 4)
                                เช่าตัด{{$item->type_dress}}
                            @endif




                        </td>
                        <td>{{ number_format($item->price, 2) }}</td>
                        <td>{{ number_format($item->deposit, 2) }}
                            <p><span>จากราคาเต็ม ({{$item->price}})</span></p>


                        </td>
                        <td>{{ number_format($item->price - $item->deposit, 2) }}</td>
                        <td>
                            {{ number_format($item->damage_insurance , 2 ) }}
                        </td>
                        
                        

                    </tr>
                @endforeach
            </tbody>
        </table>

        <p class="total"><strong>รวมมัดจำทั้งสิ้น:</strong> {{ number_format($total_deposit, 2) }} บาท</p>
        {{-- <p class="total"><strong>ยอดคงเหลือทั้งหมด:</strong> 3,700.00 บาท</p> --}}

        <div class="footer">
            <p>____________________</p>
            <p>ลายเซ็นผู้รับเงิน</p>
            <p>____________________</p>
            <p>ลายเซ็นลูกค้า</p>
        </div>
        <div>
            <p><strong>หมายเหตุ:</strong></p>
            {{-- <p>1. กรุณานำใบเสร็จมาด้วยทุกครั้งที่มารับชุด</p> --}}
            <p>1. สำหรับชุดเช่า/เช่าเครื่องประดับ /เช่าตัดชุด ต้องชำระค่าประกันในวันรับชุด</p>
            {{-- <p>3. วันรับชุดเช่า: 10 ธ.ค. 2024 / วันคืนชุดเช่า: 12 ธ.ค. 2024</p> --}}
        </div>
    </div>
</body>

</html>
