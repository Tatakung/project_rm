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
        }

        .items-table th {
            border-bottom: 1px solid black;
            border-right: 1px solid black;
            padding: 10px;
            background-color: #f5f5f5;
            text-align: left;
        }

        .items-table td {
            border-right: 1px solid black;
            padding: 10px;
            vertical-align: top;
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
            width: 150px;
        }
        .item-number {
            vertical-align: top;
            text-align: center;
            padding-top: 5px !important;
        }

        .main-item {
            display: block;
            margin-bottom: 5px;
        }

        .sub-item {
            display: block;
            margin-left: 20px;
        }
    </style>
</head>

<body>
    <!-- ส่วนหัว -->
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
                สำเนา
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
                            ชื่อลูกค้า: คุณผกาสินี โกมารชัย<br>
                            เบอร์โทร: 098-147-2866 <br>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 40%;">
                <table class="info-box" style="width: 100%;">
                    <tr>
                        <td>
                            เลขที่ใบเสร็จ: 112017000017<br>
                            วันที่: 04/07/2561 13:04 <br>
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
                <th width="12%" style="text-align: center;">ลำดับ</th>
                <th width="46%" style="text-align: center;">รายการ</th>
                <th width="11%" style="text-align: center;">จำนวน</th>
                <th width="14%" style="text-align: center;">ราคาต่อหน่วย</th>
                <th width="15%" style="text-align: center;">จำนวนเงิน</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="item-number">1</td>
                <td>
                    <span class="main-item">ชำระมัดจำตัดชุดราตรี</span>
                    <span class="sub-item">- ราคาตัด 5000 บาท</span>
                </td>
                <td class="text-center">1.00</td>
                <td class="text-center">1000.00</td>
                <td class="text-center">1000.00</td>
            </tr>
            <tr>
                <td class="item-number">2</td>
                <td>
                    <span class="main-item">ชำระค่าเช่าชุดไทย A01 + เงินประกันชุด</span>
                    <span class="sub-item">- ราคาเช่า 1300 บาท</span>
                    <span class="sub-item">- ค่าประกันชุด 1300 บาท</span>
                </td>
                <td class="text-center">1.00</td>
                <td class="text-center">2600.00</td>
                <td class="text-center">2600.00</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            <tr>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            <tr>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            <tr>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="text-center" colspan="4" style="border-top: 1px solid black;">รวมเงิน</td>
                <td class="text-center" style="border-top: 1px solid black;">2500.00</td>
            </tr>


        </tbody>
    </table>

     <!-- ส่วนสรุปยอด -->
     <table style="width: 300px; margin-left: auto;">
        <tr>
            <td>ยอดรวม</td>
            <td class="text-right">700.00</td>
        </tr>
        <tr>
            <td>ส่วนลดเป็นเงินทั้งหมด</td>
            <td class="text-right">350.00</td>
        </tr>

        <tr>
            <td><strong>ยอดสุทธิ</strong></td>
            <td class="text-right"><strong>374.50</strong></td>
        </tr>
    </table>
    




    <!-- ส่วนลายเซ็น -->
    <table style="margin-top: 10px;">
        <tr>
            <td width="50%" class="text-center">
                (...................................................)<br>
                ชื่อผู้รับเงิน<br>
                วันที่ 2 มกราคม 2567
            </td>
            <td width="50%" class="text-center">
                (...................................................)<br>
                ชื่อลูกค้า<br>
                วันที่ 2 มกราคม 2567
            </td>
        </tr>
    </table>

    <div style="margin-top: 10px;">
        <p>หมายเหตุ :</p>
        <div style="margin-left: 20px;">
            <p style="line-height: 1.2;">ตัดชุดไทย นัดรับวันที่ 25/12/2567</p>
            <p style="line-height: 1.2;">เช่าชุดราตรี นัดรับวันที่ 10/12/2567 นัดคืนวันที่ 13/12/2567</p>
        </div>
    </div>








</body>

</html>
