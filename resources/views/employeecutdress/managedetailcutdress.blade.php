@extends('layouts.adminlayout')

@section('content')
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 90%;
            margin-top: 30px;
        }

        .card {
            border: none;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #EAD8C0;
            color: dark;
        }

        .status-timeline {
            position: relative;
            padding: 20px 0;
        }

        .status-timeline::before {
            content: '';
            position: absolute;
            height: 100%;
            width: 2px;
            background-color: #dee2e6;
            left: 15px;
            top: 0;
        }

        .status-step {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .status-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #fff;
            border: 2px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
            margin-right: 15px;
        }


        .status-text {
            flex-grow: 1;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background-color: #f8f9fa;
        }

        .info-box {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
    </style>

    <ol class="breadcrumb" style="background: white ; ">
    
        <li class="breadcrumb-item"><a href="{{ route('employee.ordertotal') }}"
                style="color: black ; ">รายการออเดอร์ทั้งหมด</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employee.ordertotaldetail', ['id' => $orderdetail->order_id]) }}"
                style="color: black ; ">รายละเอียดออเดอร์ที่ {{ $orderdetail->order_id }}</a></li>
        <li class="breadcrumb-item active">{{ $orderdetail->title_name }}</li>
    </ol>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0">รายการตัดชุด : {{ $orderdetail->title_name }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                    

                        <div class="info-box">
                            <h5>ข้อมูลการตัดชุด</h5>
                            <p><strong>ราคาตัด:</strong> {{ number_format($orderdetail->price, 2) }} บาท</p>
                            <p><strong>ราคามัดจำตัด:</strong> {{ number_format($orderdetail->deposit, 2) }} บาท</p>
                            <p><strong>จำนวน:</strong> {{ $orderdetail->amount }} ชุด</p>
                            <p><strong>ผ้า:</strong>
                                @if ($orderdetail->cloth == 1)
                                    ลูกค้านำผ้ามาเอง
                                @elseif($orderdetail->cloth == 2)
                                    ทางร้านหาผ้าให้
                                @endif
                            </p>

                            <p><strong>สถานะจ่ายเงิน:</strong> <span class="badge bg-warning">
                                    @if ($orderdetail->status_payment == 1)
                                        ชำระเงินมัดจำแล้ว
                                    @elseif($orderdetail->status_payment == 2)
                                        ชำระเงินเต็มจำนวนแล้ว
                                    @endif
                                </span></p>
                            <p><strong>โน๊ต:</strong>{{ $orderdetail->note }}
                                <button type="button" class="btn" data-toggle="modal" data-target="#editnote"
                                    @if ($orderdetail->status_detail == 'รับชุดแล้ว') style="display: none;" @endif>
                                    <img src="{{ asset('images/edit.png') }}" alt="" width="22px;" height="auto">
                                </button>
                            </p>

                        </div>
                        {{-- <button class="btn btn-warning w-100 mb-3" data-bs-toggle="modal" data-bs-target="#editModal">
                            <i class="fas fa-edit"></i> แก้ไขชุด
                        </button> --}}
                    </div>
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 style="margin-top: 10px;">สถานะออเดอร์</h5>
                            </div>
                            <div class="col-md-6 text-right"
                                @if ($orderdetail->status_detail != 'เริ่มดำเนินการตัด') style="display: none;" @endif>
                                <button class="btn" style="background: #A7567F; color: #ffffff;" data-toggle="modal"
                                    data-target="#updatestatus">อัพเดตสถานะ</button>
                            </div>
                            <div class="col-md-6 text-right"
                                @if ($orderdetail->status_detail != 'ตัดชุดเสร็จสิ้น') style="display: none;" @endif>
                                <button class="btn" style="background: #A7567F; color: #ffffff;" data-toggle="modal"
                                    data-target="#updatestatusone">อัพเดตสถานะ</button>
                            </div>
                            <div class="col-md-6 text-right"
                                @if ($orderdetail->status_detail != 'แก้ไขชุด') style="display: none;" @endif>
                                <button class="btn" style="background: #A7567F; color: #ffffff;" data-toggle="modal"
                                    data-target="#updatestatus">อัพเดตสถานะ</button>
                            </div>
                            <div class="col-md-6 text-right"
                                @if ($orderdetail->status_detail != 'แก้ไขชุดเสร็จสิ้น') style="display: none;" @endif>
                                <button class="btn" style="background: #A7567F; color: #ffffff;" data-toggle="modal"
                                    data-target="#updatestatus">อัพเดตสถานะ</button>
                            </div>
                        </div>

                        <!-- Modal อัพเดตสถานะ -->
                        <div class="modal fade" id="updatestatus" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <form
                                    action="{{ route('employee.actionupdatestatuscutdress', ['id' => $orderdetail->id]) }}"
                                    method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color: #A7567F; color: #ffffff;">
                                            <h5 class="modal-title" style="font-weight: bold;">อัพเดตสถานะ</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            @if ($orderdetail->status_detail == 'เริ่มดำเนินการตัด')
                                                <p>ต้องการอัพเดตสถานะจาก "เริ่มดำเนินการตัด" เป็น "ตัดชุดเสร็จสิ้น" ?</p>
                                            @elseif($orderdetail->status_detail == 'แก้ไขชุด')
                                                <p>ต้องการอัพเดตสถานะจาก "แก้ไขชุด" เป็น "แก้ไขชุดเสร็จสิ้น" ?</p>
                                            @elseif($orderdetail->status_detail == 'แก้ไขชุดเสร็จสิ้น')
                                                <p>ต้องการอัพเดตสถานะจาก "แก้ไขชุดเสร็จสิ้น" เป็น "รับชุดแล้ว" ?</p>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn" data-dismiss="modal"
                                                style="background-color: #f8f9fa; color: #000;">ยกเลิก</button>
                                            <button type="submit" class="btn"
                                                style="background-color: #A7567F; color: #ffffff;">ยืนยัน</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Modal อัพเดตสถานะ -->
                        <div class="modal fade" id="updatestatusone" tabindex="-1" role="dialog" aria-hidden="true"
                            data-backdrop="static">
                            <div class="modal-dialog modal-lg" role="document">
                                <form
                                    action="{{ route('employee.actionupdatestatuscutdress', ['id' => $orderdetail->id]) }}"
                                    method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color: #A7567F; color: #ffffff;">
                                            <h5 class="modal-title" style="font-weight: bold;">อัพเดตสถานะ</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>กรุณาเลือกสถานะของชุด:</p>
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="radio" name="dressStatus"
                                                    id="statusOK" value="yes" required>
                                                <label class="form-check-label" for="statusOK">
                                                    ชุดเรียบร้อย พร้อมให้ลูกค้านำกลับบ้าน
                                                </label>
                                            </div>
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="radio" name="dressStatus"
                                                    id="statusAdjust" value="no" required>
                                                <label class="form-check-label" for="statusAdjust">
                                                    ชุดต้องมีการปรับแก้ไข
                                                </label>
                                            </div>

                                            <div id="showmeaforedit"
                                                style="max-height: 300px; overflow-y: auto; overflow-x: hidden; display: none;">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <p>วันที่นัดรับชุด</p>
                                                        <input type="date" name="" class="form-control"
                                                            value="{{ $orderdetail->pickup_date }}">
                                                    </div>
                                                </div>
                                                @foreach ($mea_orderdetailforedit as $item)
                                                    <div class="form-group row align-items-center mb-2">
                                                        <div class="col-4">
                                                            <span
                                                                style="font-size: 0.875rem;">{{ $item->measurement_name }}</span>
                                                        </div>
                                                        <div class="col-4">
                                                            <input type="hidden" name="id_for_edit_mea_cut_[]"
                                                                value="{{ $item->id }}">
                                                            <input type="number" class="form-control"
                                                                value="{{ $item->measurement_number }}"
                                                                name="edit_mea_cut_[]" step="0.01">
                                                        </div>
                                                        <div class="col-3">
                                                            <span style="font-size: 0.875rem;">นิ้ว</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn" data-dismiss="modal"
                                                style="background-color: #f8f9fa; color: #000;">ยกเลิก</button>
                                            <button type="submit" class="btn"
                                                style="background-color: #A7567F; color: #ffffff;">ยืนยัน</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>



                        <script>
                            var statusOK = document.getElementById('statusOK');
                            var statusAdjust = document.getElementById('statusAdjust');
                            var showmeaforedit = document.getElementById('showmeaforedit');

                            document.addEventListener('DOMContentLoaded', function() {

                                statusAdjust.addEventListener('change', function() {
                                    if (statusAdjust.checked) {
                                        showmeaforedit.style.display = 'block';
                                    }
                                });
                                statusOK.addEventListener('change', function() {
                                    if (statusOK.checked) {
                                        showmeaforedit.style.display = 'none';
                                    }
                                });
                            });
                        </script>



                        <div class="status-timeline">
                            @foreach ($orderdetailstatus as $index => $orderdetailstatus)
                                <div class="status-step">

                                    @if ($orderdetailstatus->status == 'รับชุดแล้ว')
                                        <div class="status-icon active"
                                            style="background-color: #f9e746;color: white;border-color: #f9e746;">
                                            <i class="fas fa-tshirt"></i>
                                        </div>
                                    @else
                                        <div class="status-icon active"
                                            style="background-color: #A7567F;color: white;border-color: #A7567F;">
                                            <i class="fas fa-tshirt"></i>
                                        </div>
                                    @endif

                                    <div class="status-text">
                                        <h6 class="mb-0">{{ $orderdetailstatus->status }}</h6>

                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($orderdetailstatus->created_at)->addHours(7)->format('d/m/Y H:i') }}
                                        </small><br>
                                        @if ($orderdetailstatus->status == 'แก้ไขชุด')
                                            @foreach ($mea_orderdetail as $item)
                                                @if ($item->status_measurement == 'รอการแก้ไข')
                                                    <p style="color: red; font-size: 15px; margin: 2px;">
                                                        ปรับแก้{{ $item->measurement_name }}
                                                        {{ $item->measurement_number_old }} ->
                                                        {{ $item->measurement_number }} นิ้ว</p>
                                                @endif
                                            @endforeach
                                        @endif

                                    </div>
                                </div>
                            @endforeach
                        </div>


                        <div class="row">
                            <div class="col-6">
                                <h5 class="mt-4" style="text-align: left;">ข้อมูลการวัดตัวของลูกค้า</h5>
                            </div>
                            {{-- <div class="col-6 d-flex justify-content-end">
                                <button class="btn" style="background: #A7567F; margin-top: 12px; color: #ffffff"
                                    data-toggle='modal' data-target="#editmea">ปรับแก้การวัดของลูกค้า</button>
                            </div> --}}
                        </div>

                        <table class="table table-bordered">
                            @foreach ($mea_orderdetail as $index => $mea_orderdetail)
                                @if ($index % 2 == 0)
                                    <tr>
                                @endif
                                <th>{{ $mea_orderdetail->measurement_name }}</th>
                                <td>{{ $mea_orderdetail->measurement_number }} นิ้ว</td>

                                @if ($index % 2 == 1)
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div class="modal fade" id="editmea" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">แก้ไขชุด</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        @foreach ($mea_orderdetailforedit as $item)
                            <div class="form-group row align-items-center">
                                <div class="col-md-5">
                                    <input class="form-control" type="text" value="{{ $item->measurement_name }}">
                                </div>
                                <div class="col-md-5">
                                    <input type="number" class="form-control" value="{{ $item->measurement_number }}">
                                </div>
                                <div class="col-md-2">
                                    <p>นิ้ว</p>
                                </div>
                            </div>
                        @endforeach
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-secondary">บันทึก</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="editnote" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #A7567F ; color: #ffffff">
                    <h5 class="modal-title" id="editModalLabel">แก้ไขโน๊ต</h5>
                </div>


                <form action="{{ route('employee.actionupdatenotecutdress', ['id' => $orderdetail->id]) }}"
                    method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="note" class="form-label">โน๊ต:</label>
                            <textarea class="form-control" id="note" name="note" rows="3">{{ $orderdetail->note }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal"
                            style="background-color: #f8f9fa; color: #000;">ยกเลิก</button>
                        <button type="submit" class="btn"
                            style="background-color: #A7567F; color: #ffffff;">บันทึกการแก้ไข</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="editdate" tabindex="-1" aria-labelledby="editdate" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #A7567F ; color: #ffffff">
                    <h5 class="modal-title" id="editModalLabel">แก้ไขโน๊ต</h5>
                </div>

                <form action="{{ route('employee.actionupdatedatecutdress', ['id' => $orderdetail->id]) }}"
                    method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <p style="font-size: 20px;">แก้ไขวันที่นัดรับชุด</p>
                            </div>
                            @php
                                $today = \Carbon\Carbon::today()->toDateString();
                            @endphp
                            <div class="col-md-12 d-flex justify-content-center align-items-center position-relative">
                                <input style="background: #A7567F ; color: #ffffff " class="form-control" type="date"
                                    id="datepicker" name="datepicker" min="{{ $today }}"
                                    value="{{ $orderdetail->pickup_date }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal"
                            style="background-color: #f8f9fa; color: #000;">ยกเลิก</button>
                        <button type="submit" class="btn"
                            style="background-color: #A7567F; color: #ffffff;">บันทึกการแก้ไข</button>
                    </div>
                </form>


            </div>
        </div>
    </div>
@endsection