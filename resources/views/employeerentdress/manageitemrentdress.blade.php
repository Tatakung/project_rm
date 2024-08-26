@extends('layouts.adminlayout')
@section('content')
    <ol class="breadcrumb" style="background: white ; ">
        <li class="breadcrumb-item">
            <a href="{{ route('employee.cart') }}" style="color: black ; ">ตะกร้าสินค้า</a>
        </li>
        <li class="breadcrumb-item active">
            จัดการข้อมูลเช่าชุด
        </li>
    </ol>

    <style>
        p {
            font-size: 15px;
        }
    </style>
    <div class="modal fade" id="showfail" role="dialog" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog" role="document">
            <div class="modal-content custom-modal-content"
                style="max-width: 300px; height: 50px; width: 100%; margin: auto; background-color: #EE4E4E; border: 2px solid #EE4E4E; ">
                <div class="modal-body" style="padding: 10px; display: flex; align-items: center; justify-content: center;">
                    <p style="margin: 0; color: #ffffff;">{{ session('fail') }}</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        @if (session('fail'))
            setTimeout(function() {
                $('#showfail').modal('show');
            }, 500);
        @endif
    </script>
    <div class="modal fade" id="showsuccess" role="dialog" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog" role="document">
            <div class="modal-content custom-modal-content"
                style="max-width: 300px; height: 50px; width: 100%; margin: auto; background-color: #39d628; border: 2px solid #4fe227; ">
                <div class="modal-body" style="padding: 10px; display: flex; align-items: center; justify-content: center;">
                    <p style="margin: 0; color: #ffffff;">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    </div>
    <script>
        @if (session('success'))
            setTimeout(function() {
                $('#showsuccess').modal('show');
            }, 500);
        @endif
    </script>


    <div class="container mt-5">
        <form action="{{ route('employee.savemanageitemrentdress', ['id' => $orderdetail->id]) }}" method="POST">
            @csrf
            <div class="row" style="margin-top: 15px;">
                <div class="col-md-12">
                    <p>ข้อมูลเช่าชุด</p>
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="media">
                        <img src="{{ asset('storage/' . $imagedress->first()->dress_image) }}" class="mr-5" alt="..."
                            style="width: 96px; height: 145px; border-radius: 2px;">
                        <div class="media-left">
                            <p>รายการ : {{ $orderdetail->title_name }}</p>
                            <p>ประเภทชุด : {{ $orderdetail->type_dress }}</p>
                            <p>หมายเลขชุด : {{ $dress->dress_code_new }}{{ $dress->dress_code }}</p>
                            <p>จำนวนชุด : {{ $orderdetail->amount }}&nbsp;ชุด</p>
                            <p>ราคาชุด : {{ number_format($orderdetail->price) }} บาท</p>
                        </div>
                        <div class="media-body text-center">
                            <p>ราคามัดจำ : {{ number_format($orderdetail->deposit) }} บาท</p>

                            <p>ประกันค่าเสียหาย : {{ number_format($orderdetail->damage_insurance) }} บาท</p>
                            <p>วันที่นัดรับ :
                                {{ \Carbon\Carbon::parse($orderdetail->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                {{ \Carbon\Carbon::parse($orderdetail->pickup_date)->year + 543 }}
                            </p>
                            <p>วันที่นัดคืน :
                                {{ \Carbon\Carbon::parse($orderdetail->return_date)->locale('th')->isoFormat('D MMM') }}
                                {{ \Carbon\Carbon::parse($orderdetail->return_date)->year + 543 }}
                            </p>
                            <p>ค่าบริการขยายเวลาเช่าชุด : {{ number_format($orderdetail->late_charge) }} บาท</p>
                        </div>
                        <div class="media-right">
                            <button class="btn btn-dark" type="button" data-toggle="modal" data-target="#editdate"
                                style="font-size: 15px ;">เปลี่ยนวันรับชุด/คืนชุด</button>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-7">

                    @if ($orderdetail->shirtitems_id)
                        <p>ปรับแก้ขนาดชุด(เสื้อ)</p>
                    @elseif($orderdetail->skirtitems_id)
                        <p>ปรับแก้ขนาดชุด(กระโปรง)</p>
                    @else
                        <p>ปรับแก้ขนาดชุด(ทั้งชุด)</p>
                    @endif
                    <hr>
                    <div id="aria_show_mea">
                        {{-- พืน้ที่แสดงผล  --}}
                        @foreach ($measurementorderdetail as $measurementorderdetail)
                            <div class="row">
                                <div class="col-md-4" style="text-align:center ;">
                                    {{-- <p>{{ $measurementorderdetail->measurement_name }}</p> --}}
                                    <label for="" class="form-label"
                                        style="font-size: 15px;margin-top: 10px;">{{ $measurementorderdetail->measurement_name }}<span
                                            style="color: #c40606 ; font-size: 13px;">(ปรับได้
                                            {{ $measurementorderdetail->measurement_number_start - 4 }}-{{ $measurementorderdetail->measurement_number_start + 4 }})</span></label>
                                </div>

                                <div class="col-md-4" style="display: flex; align-items: center;">
                                    <input type="hidden" value="{{ $measurementorderdetail->id }}"
                                        name="mea_order_detail_id_[]">
                                    <input type="number" name="mea_number_[]" class="form-control"
                                        style="width: 50%; height: 60%; font-size: 15px; margin-right: 20px; margin-bottom: 1px;"
                                        value="{{ $measurementorderdetail->measurement_number }}" step="0.01"
                                        min="{{ $measurementorderdetail->measurement_number_start - 4 }}"
                                        max="{{ $measurementorderdetail->measurement_number_start + 4 }}">
                                    <span style="margin-left: 5px; font-size: 15px;">นิ้ว</span>
                                </div>
                                <div class="col-md-4" style="padding-left: 1px; margin-top: 10px;">
                                    @if ($measurementorderdetail->status_measurement == 'รอการแก้ไข')
                                        <p style="color: #eb3131">
                                            ปรับเปลี่ยน:{{ $measurementorderdetail->measurement_number_old }}<i
                                                class="bi bi-arrow-right"></i>{{ $measurementorderdetail->measurement_number }}นิ้ว
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
                <div class="col-md-5">
                    <p>รายละเอียดอื่นๆ</p>
                    <hr>
                    <textarea name="note" id="" cols="1" rows="4" class="form-control">{{ $orderdetail->note }}</textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-dark" style="margin-top: 3%">ยืนยัน</button>
                </div>
            </div>

        </form>
    </div>

    <div class="modal fade" role="dialog" aria-hidden="true" id="editdate">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('employee.editdateitem', ['id' => $orderdetail->id]) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        {{-- เปลี่ยนวันรับชุด/คืนชุด --}}
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12 text-center">
                            <p style="font-size: 20px;">เปลี่ยนวันเช่าชุด - วันคืนชุด</p>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div style="display: flex; justify-content: center; align-items: center; height: 100%;">
                                    <input class="form-control" type="text" id="datepicker"
                                        style="width: 50%; height: 100%;">
                                    <input type="hidden" id="startDate" name="startDate">
                                    <input type="hidden" id="endDate" name="endDate">
                                    <input type="hidden" id="totalDay" name="totalDay">
                                    <input type="hidden" name="price" value="{{ $orderdetail->price }}">
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn" style="background-color: #EBE5AE; color: #000000; "
                            data-dismiss="modal">ยกเลิก</button>
                        <button class="btn" type="submit"
                            style="background-color: #A7545E; color: #FFFFFF; ">ยืนยัน</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr('#datepicker', {
                dateFormat: 'Y-m-d',
                locale: 'th',
                mode: 'range',
                minDate: 'today',
                maxDate: new Date().fp_incr(10),
                onChange: function(array, string, instance) {

                    if (array.length === 2) {
                        var startdate = array[0];
                        var enddate = array[1];

                        // เปลี่ยน oblect เป็น วัน
                        var strstartdate = instance.formatDate(startdate, 'Y-m-d');
                        var strenddate = instance.formatDate(enddate, 'Y-m-d');
                        document.getElementById('startDate').value = strstartdate;
                        document.getElementById('endDate').value = strenddate;
                        var totaldayminli = enddate - startdate;
                        var totalday = Math.ceil(totaldayminli / (1000 * 60 * 60 * 24));
                        document.getElementById('totalDay').value = totalday + 1;
                    }
                }
            });
        });
    </script>
@endsection
