@extends('layouts.adminlayout')
@section('content')
    <div class="modal fade" id="showfail" role="dialog" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog" role="document">
            <div class="modal-content custom-modal-content"
                style="max-width: 300px; height: 50px; width: 100%; margin: auto; background-color: #EE4E4E; border: 2px solid #EE4E4E; ">
                <div class="modal-body" style="padding: 5px; display: flex; align-items: center; justify-content: center;">
                    <p style="margin: 0; color: #ffffff;">{{ session('fail') }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="showsuccess" role="dialog" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog" role="document">
            <div class="modal-content custom-modal-content"
                style="max-width: 400px; height: 50px; width: 100%; margin: auto; background-color: #39d628; border: 2px solid #4fe227; ">
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




    <div class="container">
        <!-- Header -->

            <h1 class="text-start my-4">จัดการการซ่อมชุด</h1>
                <p >การซักชุด/ซ่อมชุดทุกชุดควรเสร็จภายใน 7 วันหลังจากลูกค้านำชุดมาคืน เพื่อให้สามารถตรวจสอบและซ่อมแซมชุดได้ทันท่วงที
            รวมถึงเตรียมพร้อมสำหรับการให้เช่าครั้งต่อไป</p>

        
            


        <!-- Laundry List -->



        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a href="#one" class="nav-link active" data-toggle="tab">รอดำเนินการ ({{ $repair_pending->count() }})</a>
            </li>
            <li class="nav-item">
                <a href="#two" class="nav-link" data-toggle="tab">กำลังซ่อม ({{ $repairs->count() }})</a>
            </li>
        </ul>


        <div class="tab-content">
            {{-- หน้าแรก --}}
            <div class="tab-pane active" id="one">

                <div class="table-responsive">
                    @if ($repair_pending->count() > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    {{-- <th>เลือก</th> --}}
                                    <th>รายการซ่อม</th>
                                    <th>รายละเอียดของการซ่อม</th>
                                    <th>สถานะ</th>
                                    <th>คิวเช่าต่อไป </th>
                                    <th>จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($repair_pending as $repair)
                                    <tr>
                                        {{-- <td>
                                                    <input type="checkbox" name="item_check_[]" value="{{ $repair->id }}"
                                                        class="class_page_one" onclick="page_one()">
                                                </td> --}}
                                        <script>
                                            function page_one() {
                                                var button_page_one = document.getElementById('button_page_one');
                                                var allitem = document.getElementsByClassName('class_page_one');
                                                var list_id_repair = [];
                                                var check = true;
                                                for (var i = 0; i < allitem.length; i++) {
                                                    if (allitem[i].checked) {
                                                        check = false;
                                                        list_id_repair.push(allitem[i].value);
                                                    }
                                                }
                                                if (check) {
                                                    button_page_one.disabled = true;
                                                } else {
                                                    button_page_one.disabled = false;
                                                }
                                                var aira_page_one = document.getElementById('aira_page_one');
                                                aira_page_one.innerHTML = '<input type="hidden" name="item_check" value="' + list_id_repair + '">';
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
                                            {{ $type_name }}
                                            {{ $dress->dress_code_new }}{{ $dress->dress_code }}

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

                                        <td>
                                            {{ $repair->repair_description }}
                                        </td>

                                        <td>{{ $repair->repair_status }}
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
                                        <td>
                                            <button class="btn btn-secondary" type="button" data-toggle="modal"
                                                data-target="#modalbuttonrepairrowpageone{{ $repair->id }}">อัพเดตสถานะ</button>
                                        </td>
                                        <div class="row mt-3">
                                            <div class="modal fade" id="modalbuttonrepairrowpageone{{ $repair->id }}"
                                                role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <form
                                                            action="{{ route('employee.buttonrepairrowpageone', ['id' => $repair->id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="modal-header"style="background-color:#EAD8C0 ;">
                                                                <h5 class="modal-title">อัพเดตสถานะ</h5>
                                                            </div>
                                                            <div class="modal-body">

                                                                ยืนยันว่าจะเปลี่ยนสถานะจาก 'รอดำเนินการ'เป็น 'กำลังซ่อม'

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn "
                                                                    style="background-color:#DADAE3;"
                                                                    data-dismiss="modal">ยกเลิก</button>
                                                                <button type="submit" class="btn "
                                                                    style="background-color:#ACE6B7;">ยืนยัน</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>





                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="col-md-12 d-flex justify-content-end">
                            {{-- <button type="button" id="button_page_one" data-toggle="modal" data-target="#showmodal"
                                        class="btn btn-primary" disabled
                                        style="background: #A7567F; border: #A7567F">อัพเดตสถานะ</button> --}}
                        </div>
                    @else
                        <p style="text-align: center ; ">ไม่มีรายการแสดงผล</p>
                    @endif
                </div>


                <div class="row mt-3">
                    <div class="modal fade" id="showmodal" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <form action="{{ route('employee.repairupdatestatus') }}" method="POST">
                                    @csrf
                                    <div class="modal-header"style="background-color:#EAD8C0 ;">
                                        <h5 class="modal-title">การอัพเดตสถานะ</h5>
                                    </div>
                                    <div class="modal-body" id="statusmessage">
                                        ยืนยันว่าจะเปลี่ยนสถานะจาก 'รอดำเนินการ'เป็น 'กำลังซ่อม'
                                        <span id="aira_page_one"></span>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn"style="background-color:#DADAE3;"
                                            data-dismiss="modal">ยกเลิก</button>
                                        <button type="submit" class="btn"
                                            style="background-color:#ACE6B7;">ยืนยัน</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


            </div>




            {{-- หน้าที่สอง --}}
            <div class="tab-pane" id="two">
                <div class="card-body">



                    @if ($repairs->count() > 0)
                        {{-- tableของ ซัก --}}
                        {{-- <form action="{{ route('employee.repairupdatestatustocleanorready') }}" method="POST">
                                @csrf --}}
                        <div class="table-responsive">
                            <h5 style="text-align: center ; ">ซักแล้วเลย</h5>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        {{-- <th>เลือก</th> --}}
                                        <th>รายการซ่อม</th>
                                        <th>รายละเอียดของการซ่อม</th>
                                        <th>สถานะ</th>
                                        <th>คิวเช่าต่อไป </th>
                                        <th>จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($repairs as $repair)
                                        <tr>
                                            {{-- <td>
                                                            <input type="checkbox" name="item_check_[]"
                                                                value="{{ $repair->id }}"
                                                                class="class_checkbox_page_two_table_two"
                                                                onclick="checkbox_page_two_table_two()">
                                                        </td> --}}

                                            <script>
                                                function checkbox_page_two_table_two() {
                                                    var allcheckbox = document.getElementsByClassName('class_checkbox_page_two_table_two');
                                                    var button = document.getElementById('buttonmodalrepairupdatestatustocleanorready');
                                                    var check_button = true;

                                                    for (var i = 0; i < allcheckbox.length; i++) {
                                                        if (allcheckbox[i].checked) {
                                                            check_button = false;
                                                        }
                                                    }
                                                    if (check_button) {
                                                        button.disabled = true;
                                                    } else {
                                                        button.disabled = false;
                                                    }
                                                }
                                            </script>
                                            <td>
                                                @php
                                                    $reservation = App\Models\Reservation::find(
                                                        $repair->reservation_id,
                                                    );
                                                    $dress_id = $reservation->dress_id;
                                                    $dress = App\Models\Dress::find($dress_id);
                                                    $type_name = App\Models\Typedress::where(
                                                        'id',
                                                        $dress->type_dress_id,
                                                    )->value('type_dress_name');
                                                @endphp
                                                {{ $type_name }}
                                                {{ $dress->dress_code_new }}{{ $dress->dress_code }}

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
                                                @elseif($repair->clean_id != null)
                                                    <p
                                                        style="font-size: 14px; margin-left: 10px; color: rgb(62, 160, 40) ; ">
                                                        -ซักแล้ว</p>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $repair->repair_description }}
                                            </td>

                                            <td style="color: #00BFFF">{{ $repair->repair_status }}</td>
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


                                            <td>
                                                <button type="button" class="btn btn-secondary" data-toggle="modal"
                                                    data-target="#butonpagetwocleanyes{{ $repair->id }}">อัพเดตสถานะ</button>
                                            </td>

                                            <div class="modal fade" role="dialog" aria-hidden="true"
                                                id="butonpagetwocleanyes{{ $repair->id }}" data-backdrop="static">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <form
                                                            action="{{ route('employee.repairupdatestatustocleanorreadybutton', ['id' => $repair->id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="modal-header"style="background-color:#EAD8C0 ;">
                                                                <h5 class="modal-title">การอัพเดตสถานะ</h5>

                                                                {{ $repair->id }}
                                                            </div>
                                                            <div class="modal-body">


                                                                @if ($repair->clean_id == null)
                                                                    ยืนยันว่าจะเปลี่ยนสถานะจาก 'กำลังซ่อม'เป็น
                                                                    'ซ่อมเสร็จแล้ว'
                                                                    และส่งซักต่อไป
                                                                @else
                                                                    <p class="fw-bold mb-3">กระบวนการต่อไปคือ:</p>
                                                                    <div class="form-check mb-2">
                                                                        <input class="form-check-input" type="radio"
                                                                            name="status_next" id="ready"
                                                                            value="1" style="accent-color: #0d6efd;"
                                                                            checked>
                                                                        <label class="form-check-label" for="ready">
                                                                            พร้อมให้เช่าต่อ
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio"
                                                                            name="status_next" id="toclean"
                                                                            value="2" style="accent-color: #0d6efd;">
                                                                        <label class="form-check-label" for="toclean">
                                                                            ส่งซักอีกครั้ง
                                                                        </label>
                                                                    </div>
                                                                @endif







                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn"
                                                                    data-dismiss="modal"style="background-color:#DADAE3;">ยกเลิก</button>
                                                                <button type="submit" class="btn" type="submit"
                                                                    style="background-color:#ACE6B7;">ยืนยัน</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                            </div>




                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="col-md-12 d-flex justify-content-end">
                                {{-- <button type="button" class="btn btn-primary" data-toggle="modal"
                                            id="buttonmodalrepairupdatestatustocleanorready"
                                            data-target="#modalrepairupdatestatustocleanorready"
                                            style="background: #A7567F; border: #A7567F" disabled>อัพเดตสถานะซักแล้ว</button> --}}
                            </div>



                            <div class="modal fade" id="modalrepairupdatestatustocleanorready" tabindex="-1"
                                role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header"
                                            style="background-color: #f8f9fa; border-bottom: 1px solid #dee2e6;">
                                            <h5 class="modal-title" id="modalLabel">อัพเดตสถานะ</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="lead">ต้องการอัพเดตสถานะจาก "กำลังซ่อม" เป็น
                                                "ซ่อมเสร็จแล้ว"
                                            </p>
                                            <p class="fw-bold mb-3">กระบวนการต่อไปคือ:</p>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="status_next"
                                                    id="ready" value="1" style="accent-color: #0d6efd;" checked>
                                                <label class="form-check-label" for="ready">
                                                    พร้อมให้เช่าต่อ
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="status_next"
                                                    id="toclean" value="2" style="accent-color: #0d6efd;">
                                                <label class="form-check-label" for="toclean">
                                                    ส่งซักอีกครั้ง
                                                </label>
                                            </div>
                                        </div>
                                        <div class="modal-footer"
                                            style="background-color: #f8f9fa; border-top: 1px solid #dee2e6;">
                                            <button class="btn" type="button" style="background-color:#DADAE3;"
                                                data-dismiss="modal">ยกเลิก</button>
                                            <button class="btn " type="submit"
                                                style="background-color:#ACE6B7;">ยืนยัน</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                        {{-- </form> --}}
                    @endif

                </div>
            </div>
        </div>

    </div>
@endsection