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

<script>
    @if (session('fail'))
        setTimeout(function() {
            $('#showfail').modal('show');
        }, 500);
    @endif
</script>

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
                <h5>เลื่อนวันนัดรับ - นัดคืนชุด</h5>
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
                                {
                                    title: 'ชุด:{{ $text_status }}',
                                    start: new Date().toISOString().split('T')[0], // ใช้วันที่ปัจจุบัน
                                    color: '#ff0000' // สีแดง
                                }
                            ],
                            locale: 'th'
                        });
                        calendar.render();
                    });
                </script>
            </div>
            <div class="col-md-5">
                <p><strong>ลำดับคิว</strong></p>
                <table class="table table-bordered">
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
                            <tr>

                                <td @if ($reser->id == $item->id) style="color: red;" @endif>{{ $index + 1 }}</td>
                                <td @if ($reser->id == $item->id) style="color: red;" @endif>
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
                                <td @if ($reser->id == $item->id) style="color: red;" @endif>
                                    {{ \carbon\Carbon::parse($item->start_date)->locale('th')->isoFormat('D MMM') }}
                                    {{ \Carbon\Carbon::parse($item->start_date)->year + 543 }}
                                </td>
                                <td @if ($reser->id == $item->id) style="color: red;" @endif>
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
                <h5 style="text-align: center ; ">ข้อมูลการเช่า</h5>




                <div class="card" style="margin-left: 250px; margin-right: 250px;">
                    <div class="card-body">
                        <p>{{ $typedress->type_dress_name }} {{ $dress->dress_code_new }}{{ $dress->dress_code }}</p>


                        <p style="color: red;">ผู้เช่า : คุณ{{ $cus->customer_fname }} {{ $cus->customer_lname }}</p>
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
                        <span class="ml-3" id="message_pass"
                            @if ($condition === 'pass') style="display: block ; color: green ; "
                                    @else
                                    style="display: none ; " @endif>ผ่านเงื่อนไข</span>
                        <span class="ml-3" id="message_fail"
                            @if ($condition === 'fail') style="display: block ; color: red ; "
                                    @else
                                    style="display: none ; " @endif>ไม่ผ่านเงื่อนไข</span>


                    </div>



                    <form action="{{route('employee.postponecheckedpass',['id' => $orderdetail->id])}}" method="POST">
                        @csrf
                        <input type="hidden" name="reservation_id" value="{{$orderdetail->reservation_id}}" >
                        <input type="hidden" id="s" name="start_date" value="{{$value_start_date}}">
                        <input type="hidden" id="e" name="end_date" value="{{$value_end_date}}">
                        <div class="col-md-12"
                            @if ($condition === 'no') style="text-align: right;display: none ; "
                                    @elseif($condition === 'pass')
                                        style="text-align: right;display: block ; "
                                    @elseif($condition === 'fail')
                                    style="text-align: right;display: none ; " @endif>
                            <button class="btn btn-primary" id="show_confirm" type="submit">ยืนยันการเลื่อนวัน</button>
                        </div>
                    </form>


                    <script>
                        var new_pickup_date = document.getElementById('new_pickup_date') ; 
                        var new_return_date = document.getElementById('new_return_date') ; 
                        var message_pass = document.getElementById('message_pass') ; 
                        var message_fail = document.getElementById('message_fail') ; 
                        var show_confirm = document.getElementById('show_confirm') ; 
                        new_pickup_date.addEventListener('input',function(){
                            new_return_date.value = '' ; 
                            new_return_date.min = new_pickup_date.value ; 
                            message_pass.style.display = 'none' ; 
                            message_fail.style.display = 'none' ; 
                            show_confirm.style.display = 'none' ; 
                        }) ; 

                        new_return_date.addEventListener('input',function(){
                            message_pass.style.display = 'none' ; 
                            message_fail.style.display = 'none' ; 
                            show_confirm.style.display = 'none' ; 
                        }) ; 





                    </script>
                  



                </div>





            </div>
        </div>





    </div>
    </div>




    </div>
@endsection
