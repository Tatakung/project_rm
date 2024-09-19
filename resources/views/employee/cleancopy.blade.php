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
                <h1 class="display-4">จัดการการส่งซัก</h1>
                <p class="lead">ดูและอัพเดตสถานะการซักของชุด</p>
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
                        <h5 class="card-title">กำลังส่งซัก</h5>
                        <p class="card-text display-4">{{ $countdoing }} รายการ</p>
                    </div>
                </div>
            </div>
            {{-- <div class="col-md-4 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">ซักเสร็จแล้ว</h5>
                        <p class="card-text display-4">{{ $countsuccess }} รายการ</p>
                    </div>
                </div>
            </div> --}}
        </div>
        <div class="alert alert-info text-center" role="alert">
            การซักชุดทุกชุดควรเสร็จภายใน 7 วันหลังจากลูกค้านำชุดมาคืน เพื่อให้สามารถตรวจสอบและซ่อมแซมชุดได้ทันท่วงที
            รวมถึงเตรียมพร้อมสำหรับการให้เช่าครั้งต่อไป
        </div>

        <!-- Laundry List -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-tshirt me-1"></i>
                รายการชุดที่รอดำเนินการ/กำลังซัก
            </div>



            <div class="card-body">
                {{-- <form action="{{ route('employee.cleanupdatestatus') }}" method="POST">
                    @csrf --}}
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>เลือก</th>
                                <th>รายการซัก</th>
                                <th>สถานะ</th>
                                <th>เหลือเวลาอีก (วัน)</th>
                                <th>คิวเช่าต่อไป </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clean as $clean)
                                @if ($clean->clean_status != 'ซักเสร็จแล้ว')
                                    <tr>

                                        <td>
                                            <input type="checkbox" name="select_item_[]" value="{{ $clean->id }}"
                                                class="select-item-class" data-status="{{ $clean->clean_status }}"
                                                onclick="updatecheck()">
                                        </td>

                                        <script>
                                            function updatecheck() {
                                                var alldata = document.getElementsByClassName('select-item-class');
                                                var check_status = null;
                                                var list_clean_id = [];
                                                for (var i = 0; i < alldata.length; i++) {
                                                    if (alldata[i].checked) {
                                                        check_status = alldata[i].getAttribute('data-status');
                                                        list_clean_id.push(alldata[i].value);
                                                    }
                                                }
                                                for (var i = 0; i < alldata.length; i++) {
                                                    if (check_status != null) {

                                                        if (check_status == alldata[i].getAttribute('data-status')) {
                                                            alldata[i].disabled = false;
                                                        } else {
                                                            alldata[i].disabled = true;
                                                        }
                                                    } else if (check_status == null) {
                                                        alldata[i].disabled = false;
                                                    }
                                                }

                                                var show_notification = document.getElementById('statusChangeMessage');
                                                console.log(list_clean_id);

                                                if (check_status == "รอดำเนินการ") {
                                                    show_notification.innerHTML = 'ยืนยันว่าจะเปลี่ยนสถานะจาก "รอดำเนินการ" เป็น "กำลังส่งซัก' +
                                                        '<input type="hidden" name="ID_for_clean" value="' + list_clean_id + '">';
                                                } else if (check_status == "กำลังส่งซัก") {
                                                    show_notification.innerHTML =
                                                        'ยืนยันว่าจะเปลี่ยนสถานะจาก "กำลังส่งซัก" เป็น' +
                                                        '<br><br>' +
                                                        '<input type="radio" id="ready" name="next_status" value="ซักเสร็จแล้ว" required>' +
                                                        '<input type="hidden" name="ID_for_clean" value="' + list_clean_id + '">' +
                                                        '<label for="ready">ซักเสร็จแล้ว (พร้อมให้เช่าต่อ) </label>' +
                                                        '<br>' +
                                                        '<input type="radio" id="repair" name="next_status" value="ต้องซ่อม">' +
                                                        '<label for="repair">ซักเสร็จแล้ว (ต้องซ่อม เนื่องจากเสียหาย)</label>';
                                                }
                                            }
                                        </script>



                                        <td>
                                            @php
                                                $reservation = App\Models\Reservation::find($clean->reservation_id);
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
                                                    $clean->reservation_id,
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
                                        </td>

                                        <td>{{ $clean->clean_status }}
                                        </td>
                                        <td>
                                            <span id="ltd_seven{{ $clean->id }}"></span>
                                            <script>
                                                var four_end_date = new Date('{{ $reservation->end_date }}');
                                                var four_end_date_add_seven = four_end_date.setDate(four_end_date.getDate() + 7);
                                                console.log(four_end_date_add_seven);
                                                var four_now = new Date();
                                                var four_day = four_end_date_add_seven - four_now;
                                                var four_show_day = Math.ceil(four_day / (1000 * 60 * 60 * 24));
                                                document.getElementById('ltd_seven{{ $clean->id }}').innerHTML = four_show_day + 'วัน';
                                            </script>
                                        </td>
                                        <td>
                                            @php
                                                $nearest = App\Models\Reservation::whereNot(
                                                    'id',
                                                    $clean->reservation_id,
                                                )
                                                    ->where('dress_id', $reservation->dress_id)
                                                    ->where('status', 'ถูกจอง')
                                                    ->orderByRaw("STR_TO_DATE(start_date, '%Y-%m-%d') asc")
                                                    ->first();
                                                //วันที่นัดรับชุดที่ใกล้ที่สุดที่จะมีคนมารับชุดนี้ต่อ
                                            @endphp
                                            @if ($reservation->shirtitems_id)
                                                <span id="showday{{ $clean->id }}" style="color: #852323 ;">
                                                    @if ($nearest != null)
                                                        <script>
                                                            var start_date = new Date("{{ $nearest->start_date }}");
                                                            var now = new Date();
                                                            var day = start_date - now;
                                                            var total = Math.ceil(day / (1000 * 60 * 60 * 24));
                                                            document.getElementById('showday{{ $clean->id }}').innerHTML =
                                                                'ลูกค้าคนถัดไปจะมารับชุดในอีก ' + total + ' วัน';
                                                        </script>
                                                    @elseif($nearest == null)
                                                        <span style="color: green ; ">ไม่มีคิวจองต่อ</span>
                                                    @endif
                                                </span>
                                            @elseif($reservation->skirtitems_id)
                                                <span id="showday{{ $clean->id }}" style="color: #852323 ;">
                                                    @if ($nearest != null)
                                                        <script>
                                                            var start_date = new Date("{{ $nearest->start_date }}");
                                                            var now = new Date();
                                                            var day = start_date - now;
                                                            var total = Math.ceil(day / (1000 * 60 * 60 * 24));
                                                            document.getElementById('showday{{ $clean->id }}').innerHTML =
                                                                'ลูกค้าคนถัดไปจะมารับชุดในอีก ' + total + ' วัน';
                                                        </script>
                                                    @elseif($nearest == null)
                                                        <span style="color: green ; ">ไม่มีคิวจองต่อ</span>
                                                    @endif
                                                </span>
                                            @else
                                                <span id="showday{{ $clean->id }}" style="color: #852323 ;">
                                                    @if ($nearest != null)
                                                        <script>
                                                            var start_date = new Date("{{ $nearest->start_date }}");
                                                            var now = new Date();
                                                            var day = start_date - now;
                                                            var total = Math.ceil(day / (1000 * 60 * 60 * 24));
                                                            document.getElementById('showday{{ $clean->id }}').innerHTML =
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
                                <form action="{{ route('employee.cleanupdatestatus') }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <p>หัว</p>
                                    </div>
                                    <div class="modal-body" id="statusChangeMessage">
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

        </div>

    </div>
@endsection
