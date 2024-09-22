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

    <script>
        @if (session('fail'))
            setTimeout(function() {
                $('#showfail').modal('show');
            }, 500);
        @endif
    </script>



    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h1 class="display-4">จัดการการซ่อมชุด</h1>
                <p class="lead">ดูและอัพเดตสถานะการซ่อมของชุด</p>
            </div>
        </div>

        <!-- Status Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h5 class="card-title">รอดำเนินการ</h5>
                        <p class="card-text display-4">{{ $countwait }} รายการ</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">กำลังซ่อม</h5>
                        <p class="card-text display-4">{{ $countdoing }} รายการ</p>
                    </div>
                </div>
            </div>

        </div>
        <div class="alert alert-info text-center" role="alert">
            การซักชุด/ซ่อมชุดทุกชุดควรเสร็จภายใน 7 วันหลังจากลูกค้านำชุดมาคืน เพื่อให้สามารถตรวจสอบและซ่อมแซมชุดได้ทันท่วงที
            รวมถึงเตรียมพร้อมสำหรับการให้เช่าครั้งต่อไป
        </div>

        <!-- Laundry List -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-tshirt me-1"></i>
                รายการชุดที่รอดำเนินการ/กำลังซ่อม
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>เลือก</th>
                                <th>รายการซ่อม</th>
                                <th>สถานะ</th>
                                <th>เหลือเวลาอีก (วัน)</th>
                                <th>คิวเช่าต่อไป </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($repair as $repair)
                                @if ($repair->repair_status != 'ซ่อมเสร็จแล้ว')
                                    <tr>

                                        <td>
                                            <input type="checkbox" name="item_check" value="{{ $repair->id }}"
                                                class="item_checkbox" data-status="{{ $repair->repair_status }}"
                                                onclick="checkboxclick()">
                                        </td>

                                        <script>
                                            function checkboxclick() {
                                                var allitem = document.getElementsByClassName('item_checkbox');
                                                var select_checkbox = null;
                                                var list_for_repair_id = [];
                                                for (var i = 0; i < allitem.length; i++) {
                                                    if (allitem[i].checked) {
                                                        select_checkbox = allitem[i].getAttribute('data-status');
                                                        list_for_repair_id.push(allitem[i].value); //เพิ่มrepair_id ลงในlistทั้งหมดตอนท่มันเลือก
                                                    }
                                                }
                                                for (var i = 0; i < allitem.length; i++) {
                                                    if (select_checkbox != null) {
                                                        if (select_checkbox == allitem[i].getAttribute('data-status')) {
                                                            allitem[i].disabled = false;
                                                        } else {
                                                            allitem[i].disabled = true;
                                                        }
                                                    } else if (select_checkbox == null) {
                                                        allitem[i].disabled = false;
                                                    }
                                                }


                                                var aria_statusmessage = document.getElementById('statusmessage');

                                                if (select_checkbox == null) {
                                                    aria_statusmessage.innerHTML = 'กรุณาเลือกรายการที่ต้องการอัพเดต';
                                                } else if (select_checkbox != null) {

                                                    if (select_checkbox == "รอดำเนินการ") {
                                                        aria_statusmessage.innerHTML = 'ยืนยันว่าจะเปลี่ยนสถานะจาก "รอดำเนินการ" เป็น "กำลังส่งซัก' +
                                                            '<input type="hidden" name="repair_id_array" value=" ' + list_for_repair_id + ' ">';
                                                    } else if (select_checkbox == "กำลังซ่อม") {
                                                        aria_statusmessage.innerHTML =
                                                            'ยืนยันว่าจะเปลี่ยนสถานะจาก "กำลังส่งซัก" เป็น' +
                                                            '<input type="hidden" name="repair_id_array" value=" ' + list_for_repair_id + ' ">' +
                                                            '<p>' +
                                                            '<input type="radio" name="status_select" id="test1" value="ซ่อมเสร็จแล้ว" required>' +
                                                            '<label for="test1">ซ่อมเสร็จแล้ว(พร้อมให้เช่าต่อ) </label>' +
                                                            '</p>' +
                                                            '<p>' +
                                                            '<input type="radio" name="status_select" id="test1" value="ส่งซัก" required>' +
                                                            '<label for="test1">ซ่อมเสร็จแล้ว(ส่่งซัก) </label>' +
                                                            '</p>';
                                                    }

                                                }
                                            }
                                        </script>
                                        <td>
                                            @php
                                                $reservation = App\Models\Reservation::find($repair->reservation_id);
                                                $dress_id = $reservation->dress_id;
                                                $dress = App\Models\Dress::find($dress_id);
                                                $type_name = App\Models\Typedress::where(
                                                    'id',
                                                    $dress->type_dress_id,
                                                )->value('type_dress_name');
                                            @endphp
                                            {{ $type_name }} {{ $dress->dress_code_new }}{{ $dress->dress_code }}

                                            @php
                                                $nearest = App\Models\Reservation::whereNot(
                                                    'id',
                                                    $repair->reservation_id,
                                                )
                                                    ->where('dress_id', $reservation->dress_id)
                                                    ->where('status', 'ถูกจอง')
                                                    ->orderByRaw("STR_TO_DATE(start_date, '%Y-%m-%d') asc")
                                                    ->first();
                                                //วันที่นัดรับชุดที่ใกล้ที่สุดที่จะมีคนมารับชุดนี้ต่อ
                                            @endphp
                                            @if ($reservation->shirtitems_id)
                                                (เสื้อ)
                                            @elseif($reservation->skirtitems_id)
                                                (กระโปรง/ผ้าถุง)
                                            @else
                                                (ทั้งชุด)
                                            @endif

                                            @if ($repair->clean_id == null)
                                                <p style="font-size: 14px; margin-left: 10px; color: #b11515 ; ">
                                                    -ยังไม่ได้ซัก</p>
                                            @else
                                                <p style="font-size: 14px; margin-left: 10px; color: rgb(62, 160, 40) ; ">
                                                    -ซักแล้ว</p>
                                            @endif
                                        </td>

                                        <td>{{ $repair->repair_status }}
                                        </td>
                                        <td>
                                            <span id="ltd_seven{{ $repair->id }}"></span>
                                            <script>
                                                var four_end_date = new Date('{{ $reservation->end_date }}');
                                                var four_end_date_add_seven = four_end_date.setDate(four_end_date.getDate() + 7);
                                                var four_now = new Date();
                                                var four_day = four_end_date_add_seven - four_now;
                                                var four_show_day = Math.ceil(four_day / (1000 * 60 * 60 * 24));
                                                document.getElementById('ltd_seven{{ $repair->id }}').innerHTML = four_show_day + 'วัน';
                                            </script>
                                        </td>
                                        <td>
                                            @php
                                                $nearest = App\Models\Reservation::whereNot(
                                                    'id',
                                                    $repair->reservation_id,
                                                )
                                                    ->where('dress_id', $reservation->dress_id)
                                                    ->where('status', 'ถูกจอง')
                                                    ->orderByRaw("STR_TO_DATE(start_date, '%Y-%m-%d') asc")
                                                    ->first();
                                                //วันที่นัดรับชุดที่ใกล้ที่สุดที่จะมีคนมารับชุดนี้ต่อ
                                            @endphp
                                            @if ($reservation->shirtitems_id)
                                                <span id="showday{{ $repair->id }}" style="color: #852323 ;">
                                                    @if ($nearest != null)
                                                        <script>
                                                            var start_date = new Date("{{ $nearest->start_date }}");
                                                            var now = new Date();
                                                            var day = start_date - now;
                                                            var total = Math.ceil(day / (1000 * 60 * 60 * 24));
                                                            document.getElementById('showday{{ $repair->id }}').innerHTML =
                                                                'ลูกค้าคนถัดไปจะมารับชุดในอีก ' + total + ' วัน';
                                                        </script>
                                                    @elseif($nearest == null)
                                                        <span style="color: green ; ">ไม่มีคิวจองต่อ</span>
                                                    @endif
                                                </span>
                                            @elseif($reservation->skirtitems_id)
                                                <span id="showday{{ $repair->id }}" style="color: #852323 ;">
                                                    @if ($nearest != null)
                                                        <script>
                                                            var start_date = new Date("{{ $nearest->start_date }}");
                                                            var now = new Date();
                                                            var day = start_date - now;
                                                            var total = Math.ceil(day / (1000 * 60 * 60 * 24));
                                                            document.getElementById('showday{{ $repair->id }}').innerHTML =
                                                                'ลูกค้าคนถัดไปจะมารับชุดในอีก ' + total + ' วัน';
                                                        </script>
                                                    @elseif($nearest == null)
                                                        <span style="color: green ; ">ไม่มีคิวจองต่อ</span>
                                                    @endif
                                                </span>
                                            @else
                                                <span id="showday{{ $repair->id }}" style="color: #852323 ;">
                                                    @if ($nearest != null)
                                                        <script>
                                                            var start_date = new Date("{{ $nearest->start_date }}");
                                                            var now = new Date();
                                                            var day = start_date - now;
                                                            var total = Math.ceil(day / (1000 * 60 * 60 * 24));
                                                            document.getElementById('showday{{ $repair->id }}').innerHTML =
                                                                'ลูกค้าคนถัดไปจะมารับชุดในอีก ' + total + ' วัน';
                                                        </script>
                                                    @elseif($nearest == null)
                                                        <span style="color: green ; ">ไม่มีคิวจองต่อ</span>
                                                    @endif
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>


                <div class="row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <button type="button" id="buttonupdate" data-toggle="modal" data-target="#showmodal"
                            class="btn btn-primary" style="background: #A7567F; border: #A7567F">อัพเดตสถานะ</button>
                    </div>

                    <div class="modal fade" id="showmodal" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <form action="{{ route('employee.repairupdatestatus') }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <p>หัว</p>
                                    </div>
                                    <div class="modal-body" id="statusmessage">
                                        {{-- พื้นที่แสดงผล --}}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                                        <button type="submit" class="btn btn-secondary">ยืนยัน</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            {{-- สิ้นสุด --}}



        </div>








    </div>
@endsection
