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

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12" style="text-align: center ;">
                <h2>เลื่อนวันนัดรับ - นัดคืนชุด</h2>
                <p>{{ $typedress->type_dress_name }} {{ $dress->dress_code_new }}{{ $dress->dress_code }}</p>

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
                                // เช่าเฉพาะทั้งชุด
                                @foreach ($reservation_dress_total as $reservation)
                                    {
                                        @php
                                            $order_id = App\Models\Orderdetail::where('reservation_id', $reservation->id)->value('order_id');
                                            $customer_id = App\Models\Order::where('id', $order_id)->value('customer_id');
                                            $customer = App\Models\Customer::find($customer_id);
                                        @endphp

                                        title: 'คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }} - {{ $reservation->status }}',
                                        start: '{{ $reservation->start_date }}',
                                        end: '{{ \Carbon\Carbon::parse($reservation->end_date)->addDay()->format('Y-m-d') }}',
                                            color: '{{ $reservation->status == 'ถูกจอง' ? '#ff0000' : '#257e4a' }}'
                                    },
                                @endforeach

                                //เช่าเฉพาะเสื้อ
                                @foreach ($reservation_dress_shirt as $reservation)
                                    {
                                        @php
                                            $order_id = App\Models\Orderdetail::where('reservation_id', $reservation->id)->value('order_id');
                                            $customer_id = App\Models\Order::where('id', $order_id)->value('customer_id');
                                            $customer = App\Models\Customer::find($customer_id);
                                        @endphp

                                        title:
                                            'คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }} - {{ $reservation->status }}',
                                            start: '{{ $reservation->start_date }}',
                                            end:
                                            '{{ \Carbon\Carbon::parse($reservation->end_date)->addDay()->format('Y-m-d') }}',
                                            color: '#3788d8' // สีน้ำเงินสำหรับเช่าเฉพาะเสื้อ
                                    },
                                @endforeach
                                // เช่าเฉพาะผ้าถุง
                                @foreach ($reservation_dress_skirt as $reservation)
                                    {
                                        @php
                                            $order_id = App\Models\Orderdetail::where('reservation_id', $reservation->id)->value('order_id');
                                            $customer_id = App\Models\Order::where('id', $order_id)->value('customer_id');
                                            $customer = App\Models\Customer::find($customer_id);
                                        @endphp

                                        title:
                                            'คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }} - {{ $reservation->status }}',
                                            start: '{{ $reservation->start_date }}',
                                            end:
                                            '{{ \Carbon\Carbon::parse($reservation->end_date)->addDay()->format('Y-m-d') }}',
                                            color: '#257e4a' // สีเขียวสำหรับเช่าเฉพาะผ้าถุง
                                    },
                                @endforeach

                                // เพิ่ม event สำหรับวันที่ปัจจุบัน
                                @if ($text_status != 'อยู่ในร้าน')
                                    {
                                        title: 'ชุด:{{ $text_status }}',
                                        start: new Date().toISOString().split('T')[0], // ใช้วันที่ปัจจุบัน
                                        color: '#ff0000' // สีแดง
                                    }
                                @endif
                            ],
                            locale: 'th'
                        });
                        calendar.render();
                    });
                </script>
                <p>
                    <span
                        style="display: inline-block; width: 12px; height: 12px; background-color: #ff0000; border-radius: 50%; margin-right: 5px;"></span>
                    เช่าทั้งชุด
                </p>
                <p>
                    <span
                        style="display: inline-block; width: 12px; height: 12px; background-color: #3788d8; border-radius: 50%; margin-right: 5px;"></span>
                    เช่าเฉพาะเสื้อ
                </p>
                <p>
                    <span
                        style="display: inline-block; width: 12px; height: 12px; background-color: #257e4a; border-radius: 50%; margin-right: 5px;"></span>
                    เช่าเฉพาะผ้าถุง
                </p>
            </div>
            <div class="col-md-5">
                <h2 class="card-title">ลำดับคิว</h2>
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
                        @foreach ($reservation_dress_index as $index => $item)
                            <tr>

                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @php
                                        $order_id_s = App\Models\Orderdetail::where('reservation_id', $item->id)->value(
                                            'order_id',
                                        );
                                        $customer_id_s = App\Models\Order::where('id', $order_id_s)->value(
                                            'customer_id',
                                        );
                                        $customer_s = App\Models\Customer::find($customer_id_s);
                                    @endphp
                                    <span>คุณ{{ $customer_s->customer_fname }} {{ $customer_s->customer_lname }}</span>
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


                        <form action="{{ route('employee.ordertotaldetailpostponecheckeddresstotal', ['id' => $orderdetail->id]) }}"
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


                        <h5>เงื่อนไขการเลื่อนวันนัดรับ-นัดคืน</h5>
                        <li>สามารถเลื่อนวันนัดล่วงหน้าได้ไม่เกิน 30 วัน</li>
                        <li>ไม่สามารถเลื่อนวันนัดย้อนหลังได้</li>
                        <li>ระยะเวลาการเช่าต้องไม่เกิน 7 วัน</li>
                        <li>วันคืนชุดต้องมากกว่าวันรับชุด</li>
                        <li>สามารถเลื่อนวันนัดได้ 1 ครั้งต่อการเช่า 1 รายการ</li>


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
