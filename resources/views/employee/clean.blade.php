@extends('layouts.adminlayout')
@section('content')
<style>
        .btn-s {
        border-radius: 10px;

    }

    .btn-s:hover {
        background-color: #0056b3;
    }

    .btn-s i {
        margin-right: 2px;
        font-size: 14px;
    }
</style>
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
                <h1 class="text-start my-4">จัดการการส่งซัก</h1>
                <p >การซักชุดทุกชุดควรเสร็จภายใน 7 วันหลังจากลูกค้านำชุดมาคืน เพื่อให้สามารถตรวจสอบและซ่อมแซมชุดได้ทันท่วงที
                รวมถึงเตรียมพร้อมสำหรับการให้เช่าครั้งต่อไป</p>

        <!-- Status Summary Cards
        <div class="row mb-1">
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body"style="background-color:#EBE5B2; border:0;">
                        <h5 class="card-title">รอดำเนินการ</h5>
                        <p style="font-size: 20px;">{{ $countwait }} รายการ</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body "style="background-color:#EAC39D ; border:0;">
                        <h5 class="card-title">กำลังส่งซัก</h5>
                        <p style="font-size: 20px;">{{ $countdoing }} รายการ</p>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- <div class="alert alert-info text-center" role="alert">
            การซักชุดทุกชุดควรเสร็จภายใน 7 วันหลังจากลูกค้านำชุดมาคืน เพื่อให้สามารถตรวจสอบและซ่อมแซมชุดได้ทันท่วงที
            รวมถึงเตรียมพร้อมสำหรับการให้เช่าครั้งต่อไป
        </div> -->

        <!-- Laundry List -->
        <div class="card mb-4">



            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#one" class="nav-link active" data-toggle="tab">รอดำเนินการ ({{ $countwait }})</a>
                </li>
                <li class="nav-item">
                    <a href="#two" class="nav-link" data-toggle="tab">กำลังส่งซัก ({{ $countdoing }})</a>
                </li>
            </ul>


            <div class="tab-content">
                {{-- หน้าที่หนึ่ง --}}
                <div class="tab-pane active" id="one">
                    <div class="card-body">

                        <div class="table-responsive">
                            @if ($clean_pending->count() > 0)
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            {{-- <th>เลือก</th> --}}
                                            <th>รายการซัก</th>
                                            <th>สถานะ</th>
                                            <th>คิวเช่าต่อไป </th>
                                            <th>จัดการ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($clean_pending as $clean)
                                            <tr>
                                                {{-- <td>
                                                    <input type="checkbox" name="select_item_[]" value="{{ $clean->id }}"
                                                        class="select-item-class" data-status="{{ $clean->clean_status }}"
                                                        onclick="updatecheck()">
                                                </td> --}}
                                                <script>
                                                    function updatecheck() {
                                                        var alldata = document.getElementsByClassName('select-item-class');
                                                        var check_status = true;
                                                        var list_clean_id = [];
                                                        var button_page_one = document.getElementById('button_page_one');
                                                        for (var i = 0; i < alldata.length; i++) {
                                                            if (alldata[i].checked) {
                                                                check_status = alldata[i].getAttribute('data-status');
                                                                list_clean_id.push(alldata[i].value);
                                                                check_status = false;
                                                            }
                                                        }

                                                        if (check_status) {
                                                            button_page_one.disabled = true;
                                                        } else {
                                                            button_page_one.disabled = false;
                                                        }
                                                        var statuschangemessage = document.getElementById('statuschangemessage');
                                                        statuschangemessage.innerHTML = '<input type="text" name="select_item" value="' + list_clean_id + ' ">';
                                                    }
                                                </script>


                                                <td>
                                                    @php
                                                        $reservation = App\Models\Reservation::find(
                                                            $clean->reservation_id,
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

                                                <td style="color: #a22222 ; ">{{ $clean->clean_status }}</td>

                                                <td>
                                                    @php
                                                        $nearest = App\Models\Reservation::whereNot('id',$clean->reservation_id,)
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

                                                <td>
                                                    <button class="btn btn-s" type="button" data-toggle="modal"
                                                        data-target="#modalbuttoncleanrowpageone{{ $clean->id }}"style="background-color:#DADAE3;" >
                                                        อัพเดตสถานะ
                                                    </button>
                                                </td>
                                                <div class="row">
                                                    <div class="modal fade"
                                                        id="modalbuttoncleanrowpageone{{ $clean->id }}" role="dialog"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <form
                                                                    action="{{ route('employee.buttoncleanrowpageone', ['id' => $clean->id]) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <div class="modal-header"style="background-color:#EAD8C0 ;">
                                                                        <h5 class="modal-title" >การอัพเดตสถานะ</h5>
                                                                     
                                                                    </div>
                                                                    <div class="modal-body">

                                                                        <p>ยืนยันว่าจะเปลี่ยนสถานะจาก <span
                                                                                style="color: #EE4E4E ; ">'รอดำเนินการ'</span>เป็น
                                                                            <span
                                                                            style="color: #EE4E4E ; ">'กำลังส่งซัก'</span>
                                                                        </p>


                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn" style="background-color:#DADAE3;"
                                                                            data-dismiss="modal">ยกเลิก</button>
                                                                        <button type="submit"
                                                                            class="btn "style="background-color:#ACE6B7;">ยืนยัน</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                        </div>

                        </tr>
                        @endforeach
                        </tbody>
                        </table>
                        {{-- <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#showmodalwait"
                            id="button_page_one" disabled>อัพเดตสถานะ</button> --}}
                    @else
                        <p style="text-align: center ; ">ไม่มีรายการแสดงผล</p>
                        @endif
                    </div>

                    <div class="row mt-3">
                        <div class="modal fade" id="showmodalwait" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <form action="{{ route('employee.cleanupdatestatus') }}" method="POST">
                                        @csrf
                                        <div class="modal-header"style="background-color:#EAD8C0 ;">
                                                                <h5 class="modal-title">การอัพเดตสถานะ</h5>
                                                            </div>
                                        <div class="modal-body">
                                            <p>ยืนยันว่าจะเปลี่ยนสถานะจาก <span
                                                    style="color: #EE4E4E ; ">'รอดำเนินการ'</span>เป็น <span
                                                    style="color: rgb(24, 15, 206) ; ">'กำลังส่งซัก'</span></p>

                                            <span id="statuschangemessage"></span>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn " style="background-color:#DADAE3;" 
                                                data-dismiss="modal">ยกเลิก</button>
                                            <button type="submit" class="btn "  style="background-color:#ACE6B7;">ยืนยัน</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>





            {{-- หน้าที่สอง --}}
            <div class="tab-pane" id="two">
                <div class="card-body">

                    <div class="table-responsive">
                        @if ($clean_doing_wash->count() > 0)
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        {{-- <th>เลือก</th> --}}
                                        <th>รายการซัก</th>
                                        <th>สถานะ</th>
                                        <th>คิวเช่าต่อไป </th>
                                        <th>จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($clean_doing_wash as $clean)
                                        <tr>

                                            {{-- <td>
                                                <input type="checkbox" name="select_item_[]" value="{{ $clean->id }}"
                                                    class="select-item-class" data-status="{{ $clean->clean_status }}"
                                                    onclick="updatecheckdoingwash()">
                                            </td> --}}
                                            <script>
                                                function updatecheckdoingwash() {

                                                    var alldata = document.getElementsByClassName('select-item-class');
                                                    var check_status = null;
                                                    var list_clean_id = [];
                                                    var button_page_two = document.getElementById('button_page_two');
                                                    var checkdisableddata = true;
                                                    for (var i = 0; i < alldata.length; i++) {
                                                        if (alldata[i].checked) {
                                                            check_status = alldata[i].getAttribute('data-status');
                                                            list_clean_id.push(alldata[i].value);
                                                            checkdisableddata = false;
                                                        }
                                                    }
                                                    if (checkdisableddata) {
                                                        button_page_two.disabled = true;
                                                    } else {
                                                        button_page_two.disabled = false;
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
                                                    var showmeaasage_doing_wash = document.getElementById('showmeaasage_doing_wash');
                                                    if (check_status == "กำลังส่งซัก") {
                                                        showmeaasage_doing_wash.innerHTML =
                                                            'ยืนยันว่าจะเปลี่ยนสถานะจาก "กำลังส่งซัก" เป็นเสร็จแล้ว(พร้อมให้เช่าต่อ)' +
                                                            '<input type="hidden" name="ID_for_clean" value="' + list_clean_id + '">';
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
                                                {{ $type_name }}
                                                {{ $dress->dress_code_new }}{{ $dress->dress_code }}

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

                                            <td style="color: #a22222 ; ">{{ $clean->clean_status }}</td>

                                            <td>
                                                @php
                                                    $nearest = App\Models\Reservation::whereNot('id',$clean->reservation_id,)
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

                                            <td>
                                                <button class="btn" type="button" data-toggle="modal" style="background-color:#E3A499 ;"
                                                    data-target="#need_to_repair{{ $clean->id }}">ต้องซ่อม</button>
                                                <button class="btn " type="button" data-toggle="modal" style="background-color:#ACE6B7;border:0"
                                                    data-target="#modalbuttoncleanrowpagetwo{{ $clean->id }}">พร้อมให้เช่าต่อ</button>
                                            </td>



                                            <div class="modal fade" id="modalbuttoncleanrowpagetwo{{ $clean->id }}"
                                                role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <form
                                                            action="{{ route('employee.buttoncleanrowpagetwo', ['id' => $clean->id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="modal-header"style="background-color:#EAD8C0 ;">
                                                                <h5 class="modal-title">การอัพเดตสถานะ</h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                ยืนยันว่าจะเปลี่ยนสถานะจาก "กำลังส่งซัก"
                                                                เป็นเสร็จแล้ว
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn" type="button"style="background-color:#DADAE3;" 
                                                                    data-dismiss="modal">ยกเลิก</button>
                                                                <button class="btn"  style="background-color:#ACE6B7;"
                                                                    type="submit">ยืนยัน</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>






                                            <div class="modal fade" id="need_to_repair{{ $clean->id }}"
                                                role="dialog" aria-hidden="true" data-backdrop="static">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <form action="{{ route('employee.afterwashtorepair') }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="modal-header" style="background-color:#EAD8C0 ;">
                                                                <h5 class="model-title">ซักเสร็จแล้ว (ต้องซ่อมเนื่องจากเสียหาย) </h5>
                                                            </div>
                                                            <div class="modal-body">

                                                                @php
                                                                    $reservation = App\Models\Reservation::find(
                                                                        $clean->reservation_id,
                                                                    );
                                                                    $dress_id = $reservation->dress_id;

                                                                    $separable = App\Models\Dress::where(
                                                                        'id',
                                                                        $dress_id,
                                                                    )->value('separable');

                                                                    $shirt_id = $reservation->shirtitems_id;
                                                                    $skirt_id = $reservation->skirtitems_id;
                                                                @endphp



                                                                <p>รายละเอียดของการซ่อม</p>
                                                                <select name="typerepair" style="width: 20%;border-radius: 4px;">
                                                                    <option value="10"
                                                                        id="type_total_dress{{ $clean->id }}">
                                                                        ทั้งชุด</option>
                                                                    <option value="20"
                                                                        id="type_shirt{{ $clean->id }}">เสื้อ
                                                                    </option>
                                                                    <option value="30"
                                                                        id="type_skirt{{ $clean->id }}">ผ้าถุง
                                                                    </option>
                                                                </select>
                                                                <input type="hidden" name="clean_id"
                                                                    value="{{ $clean->id }}">
                                                                <textarea name="repair_detail" cols="60" rows="4" class="form-control"
                                                                    placeholder="กรุณากรอกรายละเอียดของการซ่อมที่ต้องการ..."></textarea>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn" style="background-color:#DADAE3;" 
                                                                    data-dismiss="modal">ยกเลิก</button>
                                                                <button type="submit"style="background-color:#ACE6B7;"
                                                                    class="btn ">ยืนยัน</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                var type_total_dress = document.getElementById('type_total_dress{{ $clean->id }}');
                                                var type_shirt = document.getElementById('type_shirt{{ $clean->id }}');
                                                var type_skirt = document.getElementById('type_skirt{{ $clean->id }}');
                                                var dress_id = '{{ $dress_id }}';
                                                var shirt_id = '{{ $shirt_id }}';
                                                var skirt_id = '{{ $skirt_id }}';

                                                var Separable = '{{ $separable }}';



                                                if (shirt_id) {
                                                    type_total_dress.style.display = 'none';
                                                    type_skirt.style.display = 'none';
                                                    type_shirt.selected = true;
                                                } else if (skirt_id) {
                                                    type_total_dress.style.display = 'none';
                                                    type_shirt.style.display = 'none';
                                                    type_skirt.selected = true;
                                                } else {

                                                    if (Separable == 1) {
                                                        console.log('แยกไม่ได้');

                                                        type_total_dress.style.display = 'block';
                                                        type_shirt.style.disabled = 'block';
                                                        type_skirt.style.disabled = 'block';
                                                        type_total_dress.selected = true;

                                                        type_skirt.style.display = 'none';

                                                        type_shirt.style.display = 'none';


                                                        




                                                    } else if (Separable == 2) {
                                                        type_total_dress.style.display = 'block';
                                                        type_shirt.style.disabled = 'block';
                                                        type_skirt.style.disabled = 'block';
                                                        type_total_dress.selected = true;

                                                    }


                                                }
                                            </script>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{-- <button class="btn btn-danger" type="button" data-toggle="modal"
                                data-target="#showmodalwash" id="button_page_two" disabled>อัพเดตสถานะ</button> --}}
                        @else
                            <p style="text-align: center ;">ไม่มีรายการแสดงผล</p>
                        @endif
                    </div>





                    <div class="row mt-3">
                        <div class="modal fade" id="showmodalwash" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <form action="{{ route('employee.cleanupdatestatuspagetwo') }}" method="POST">
                                        @csrf
                                        <div class="modal-header"style="background-color:#EAD8C0 ;">
                                                                <h5 class="modal-title">การอัพเดตสถานะ</h5>
                                                            </div>
                                        <div class="modal-body" id="showmeaasage_doing_wash">
                                            ยืนยันว่าจะเปลี่ยนสถานะจาก "กำลังส่งซัก" เป็น' ซักเสร็จแล้ว
                                            (พร้อมให้เช่าต่อ)
                                            <br><br>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn "style="background-color:#DADAE3;" 
                                                data-dismiss="modal">ยกเลิก</button>
                                            <button type="submit" class="btn "style="background-color:#ACE6B7;">ยืนยัน</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
@endsection