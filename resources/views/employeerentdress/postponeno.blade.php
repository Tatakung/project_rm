@extends('layouts.adminlayout')
@section('content')
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
    {{-- 
    <script>
        @if (session('fail'))
            setTimeout(function() {
                $('#showfail').modal('show');
            }, 500);
        @endif
    </script> --}}

    <script>
        @if (session('success'))
            setTimeout(function() {
                $('#showsuccess').modal('show');
            }, 500);
        @endif
    </script>

    <ol class="breadcrumb" style="background-color: transparent;">
        <li class="breadcrumb-item">
            <a href="{{ route('employee.ordertotal') }}" style="color: black ; ">รายการออเดอร์ทั้งหมด</a>
        </li>

        <li class="breadcrumb-item">
            <a href="{{ route('employee.ordertotaldetail', ['id' => $orderdetail->order_id]) }}"
                style="color: black ; ">รายการออเดอร์ที่ {{ $orderdetail->order_id }} </a>
        </li>


        <li class="breadcrumb-item active">
            รายละเอียดเลื่อนวันนัดรับ-นัดคืนของรายละเอียดที่ {{ $orderdetail->id }}
        </li>
    </ol>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12" style="text-align: center ;">
                <h2>เลื่อนวันนัดรับ - นัดคืนชุด</h2>
            </div>
        </div>
        <div class="row mt-5">

            <div class="col-md-7">
                <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet">

                <!-- jQuery -->
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                <!-- FullCalendar JS -->
                <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
                <style>
                    #calendar {
                        max-width: 700px;
                        margin: 0 auto;
                    }
                </style>

                <div id='calendar'></div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var calendarEl = document.getElementById('calendar');
                        var calendar = new FullCalendar.Calendar(calendarEl, {
                            initialView: 'dayGridMonth',
                            events: [
                                // ข้อมูลการจองจะถูกเพิ่มที่นี่
                                @foreach ($reservation_dress_total as $reservation)
                                    {
                                        @php
                                            $order_id = App\Models\Orderdetail::where('reservation_id', $reservation->id)->value('order_id');
                                            $customer_id = App\Models\Order::where('id', $order_id)->value('customer_id');
                                            $customer = App\Models\Customer::find($customer_id);
                                        @endphp

                                        title:
                                            'คุณ {{ $customer->customer_fname }} {{ $customer->customer_lname }} - {{ $reservation->status }}',
                                            start: '{{ $reservation->start_date }}',
                                            end:
                                            '{{ \Carbon\Carbon::parse($reservation->end_date)->addDay()->format('Y-m-d') }}',
                                            color: '{{ $reservation->status == 'ถูกจอง' ? '#3788d8' : '#257e4a' }}'
                                    },
                                @endforeach
                                // เพิ่ม event สำหรับวันที่ปัจจุบัน
                                @if ($text_status != 'อยู่ในร้าน')
                                    {
                                        title: 'ชุด:{{ $text_status }}',
                                        start: new Date().toISOString().split('T')[0], // ใช้วันที่ปัจจุบัน
                                        color: '#8A2BE2' // สีแดง
                                    }
                                @endif
                            ],
                            locale: 'th'
                        });
                        calendar.render();
                    });
                </script>
            </div>
            <div class="col-md-5">
                <h2 class="card-title">ลำดับคิวเช่าของ{{ $typedress->type_dress_name }}
                    {{ $typedress->specific_letter }}{{ $dress->dress_code }} (ทั้งชุด)</h2>
                <ul>
                    <li>สถานะ {{ $typedress->type_dress_name }} {{ $typedress->specific_letter }}{{ $dress->dress_code }}
                        (ทั้งชุด) ปัจุจุบัน : {{ $dress->dress_status }}</li>
                </ul>
                คิวการเช่าเรียงตามวันที่นัดรับ
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">คิวที่</th>
                            <th scope="col">ชื่อลูกค้า</th>
                            <th scope="col">วันนัดรับ</th>
                            <th scope="col">วันนัดคืน</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservation_dress_total as $index => $item)
                            <tr style="text-align: center;">

                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span>คุณ{{ $item->re_one_many_details->first()->order->customer->customer_fname }}
                                        {{ $item->re_one_many_details->first()->order->customer->customer_lname }}</span>
                                </td>
                                <td>
                                    {{ \carbon\Carbon::parse($item->start_date)->locale('th')->isoFormat('D MMM') }}
                                    {{ \Carbon\Carbon::parse($item->start_date)->year + 543 }}
                                </td>
                                <td>
                                    {{ \carbon\Carbon::parse($item->end_date)->locale('th')->isoFormat('D MMM') }}
                                    {{ \Carbon\Carbon::parse($item->end_date)->year + 543 }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


        <div class="row mt-5 mb-5">
            <div class="col-md-12">
                <h3 style="text-align: start ; ">ข้อมูลการเช่า</h3>




                <div class="card shadow">
                    <div class="card-body">
                        <p>{{ $typedress->type_dress_name }} {{ $dress->dress_code_new }}{{ $dress->dress_code }}</p>


                        <p>ผู้เช่า : คุณ{{ $cus->customer_fname }} {{ $cus->customer_lname }}</p>
                        <p style="color: rgb(123, 120, 120); font-size: 15px;">วันที่นัดรับ - คืนเดิม :
                            {{ \carbon\Carbon::parse($reser->start_date)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($reser->start_date)->year + 543 }}
                            -
                            {{ \carbon\Carbon::parse($reser->end_date)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($reser->end_date)->year + 543 }}
                        </p>
                        @php
                            $today = \Carbon\Carbon::today()->toDateString();
                        @endphp


                        <form action="{{ route('employee.ordertotaldetailpostponechecked', ['id' => $orderdetail->id]) }}"
                            method="GET">
                            @csrf
                            <div class="form-group">
                                <label for="new_pickup_date">วันที่นัดรับชุดใหม่</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="bi bi-calendar-event"></i> <!-- ไอคอนปฏิทิน -->
                                        </span>
                                    </div>
                                    <input type="date" class="form-control" value="{{ $value_start_date }}"
                                        id="new_pickup_date" name="new_pickup_date" min="{{ $today }}" required>
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="new_return_date">วันที่นัดคืนชุดใหม่</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="bi bi-calendar-event"></i> <!-- ไอคอนปฏิทิน -->
                                        </span>
                                    </div>
                                    <input type="date" class="form-control" value="{{ $value_end_date }}"
                                        min="{{ $today }}" id="new_return_date" name="new_return_date" required>
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-secondary mb-2">ตรวจสอบ</button>
                        </form>
                        <h5>เงื่อนไขการเลื่อนวันนัดรับ-นัดคืนชุด</h5>
                        <p>
                            การเลื่อนวันนัดรับ-นัดคืนชุดจะต้องผ่านการตรวจสอบความพร้อมของชุดทั้ง 3 เงื่อนไขดังต่อไปนี้:
                        </p>
                        <ul>
                            <li>
                                <strong>เงื่อนไขที่ 1 (ช่วงก่อนวันนัดรับใหม่):</strong> ระบบจะตรวจสอบ 7
                                วันก่อนวันนัดรับใหม่ว่าชุดไม่ติดการจองหรือการเช่าของลูกค้าท่านอื่น
                            </li>
                            <li>
                                <strong>เงื่อนไขที่ 2 (ช่วงวันนัดรับใหม่ถึงวันนัดคืนใหม่):</strong>
                                ระบบจะตรวจสอบว่าในช่วงวันที่ต้องการเลื่อนไปใช้บริการนั้น
                                ชุดไม่ติดการจองหรือการเช่าของลูกค้าท่านอื่น
                            </li>
                            <li>
                                <strong>เงื่อนไขที่ 3 (ช่วงหลังวันนัดคืนใหม่):</strong> ระบบจะตรวจสอบ 7
                                วันหลังวันนัดคืนใหม่ว่าชุดไม่ติดการจองหรือการเช่าของลูกค้าท่านอื่น
                            </li>
                        </ul>
                        <p>
                            <strong>หมายเหตุ:</strong> การเลื่อนวันนัดรับ-นัดคืนจะสามารถทำได้ก็ต่อเมื่อผ่านทั้ง 3
                            เงื่อนไขข้างต้น
                            เพื่อให้มั่นใจว่ามีช่วงเวลาเพียงพอสำหรับการเตรียมชุดก่อนส่งมอบและการจัดการหลังคืนชุด
                            โดยไม่กระทบต่อการจองของลูกค้าท่านอื่น
                        </p>












                    </div>



                    <script>
                        var new_pickup_date = document.getElementById('new_pickup_date');
                        var new_return_date = document.getElementById('new_return_date');
                        var message_pass = document.getElementById('message_pass');
                        var message_fail = document.getElementById('message_fail');
                        var show_confirm = document.getElementById('show_confirm');
                        new_pickup_date.addEventListener('input', function() {
                            new_return_date.value = '';
                            new_return_date.min = new_pickup_date.value;
                            message_pass.style.display = 'none';
                            message_fail.style.display = 'none';
                            show_confirm.style.display = 'none';
                        });

                        new_return_date.addEventListener('input', function() {
                            message_pass.style.display = 'none';
                            message_fail.style.display = 'none';
                            show_confirm.style.display = 'none';
                        });
                    </script>




                </div>





            </div>
        </div>



        <script>
            @if (session('condition') == 'passsuccesst')
                setTimeout(function() {
                    $('#rescheduleModal').modal('show');
                }, 500);
            @elseif (session('condition') == 'failno')
                setTimeout(function() {
                    $('#rescheduleFailModal').modal('show');
                }, 500);
            @endif
        </script>







        <div class="modal fade" id="rescheduleFailModal" tabindex="-1" role="dialog"
            aria-labelledby="rescheduleFailModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rescheduleFailModalLabel">ตรวจสอบการเลื่อนวันนัด</h5>

                    </div>
                    <div class="modal-body text-center">
                        <img src="{{ asset('images/failimage.png') }}" alt="Fail"
                            style="width: 100px; height: auto; margin-bottom: 15px;">
                        <p style="font-size: 18px; font-weight: bold; color: #dc3545; margin-bottom: 20px;">
                            ไม่สามารถเลื่อนวันดังกล่าวได้ กรุณาเลือกวันใหม่</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="rescheduleModal" tabindex="-1" role="dialog"
            aria-labelledby="rescheduleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('employee.postponecheckedpass', ['id' => $orderdetail->id]) }}"
                        method="POST">


                        <input type="hidden" name="reservation_id" value="{{ $orderdetail->reservation_id }}">
                        <input type="hidden" id="s" name="start_date" value="{{ $value_start_date }}">
                        <input type="hidden" id="e" name="end_date" value="{{ $value_end_date }}">

                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="rescheduleModalLabel">ตรวจสอบการเลื่อนวันนัด</h5>

                        </div>
                        <div class="modal-body text-center">
                            <img src="{{ asset('images/successimage.png') }}" alt="Success"
                                style="width: 100px; height: auto; margin-bottom: 15px;">
                            <p style="font-size: 18px; font-weight: bold; color: #28a745; margin-bottom: 20px;">
                                สามารถเลื่อนวันได้</p>
                            <div class="row mb-2">
                                <div class="col-6 text-right">วันนัดรับใหม่:</div>
                                <div class="col-6 text-left"><span id="newPickupDate">
                                        {{ \Carbon\Carbon::parse($value_start_date)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($value_start_date)->year + 543 }}
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 text-right">วันนัดคืนใหม่:</div>
                                <div class="col-6 text-left"><span id="newReturnDate">
                                        {{ \Carbon\Carbon::parse($value_end_date)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($value_end_date)->year + 543 }}
                                    </span></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                            {{-- <a class="btn btn-secondary" href="{{route('employee.ordertotaldetailpostpone',['id' => $orderdetail->id])}}">
                                ยกเลิก
                            </a> --}}
                            <button type="submit" class="btn btn-primary"
                                id="confirmReschedule">ยืนยันการเลื่อนวัน</button>
                        </div>
                    </form>







                </div>
            </div>
        </div>










    </div>
    </div>




    </div>
@endsection
