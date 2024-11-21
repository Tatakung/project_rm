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
        <div class="row mt-4">
            <div class="col-12 text-center">
                <h1 class="display-4">จัดการทำความสะอาดเครื่องประดับ</h1>
                <p class="lead">ดูและอัพเดตสถานะการของเครื่องประดับ</p>
            </div>
        </div>

        <!-- Status Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body"style="background-color:#EBE5B2; border:0;">
                        <h5 class="card-title">รอดำเนินการ</h5>
                        <p class="card-text display-4">{{ $clean_pending->count() }} รายการ</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body "style="background-color:#EAC39D ; border:0;">
                        <h5 class="card-title">กำลังทำความสะอาด</h5>
                        <p class="card-text display-4">{{ $clean_doing_wash->count() }} รายการ</p>
                    </div>
                </div>
            </div>

        </div>
        <div class="alert alert-info text-center" role="alert">
            การทำความสะอาดเครื่องประดับควรเสร็จภายใน 7 วันหลังจากลูกค้านำมาคืน
            เพื่อให้สามารถตรวจสอบและซ่อมแซมเครื่องประดับได้ทันท่วงที
            รวมถึงเตรียมพร้อมสำหรับการให้เช่าครั้งต่อไป
        </div>

        <!-- Laundry List -->
        <div class="card mb-4">



            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#one" class="nav-link active" data-toggle="tab">รอดำเนินการ
                        ({{ $clean_pending->count() }})</a>
                </li>
                <li class="nav-item">
                    <a href="#two" class="nav-link" data-toggle="tab">กำลังทำความสะอาด
                        ({{ $clean_doing_wash->count() }})</a>
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


                                                <td>
                                                    {{ $clean->jewvationtorefil->jewelry_m_o_typejew->type_jewelry_name }}
                                                    {{ $clean->jewvationtorefil->jewelry_m_o_typejew->specific_letter }}{{ $clean->jewvationtorefil->jewelry_code }}
                                                </td>

                                                <td style="color: #a22222 ; ">{{ $clean->status }}</td>

                                                <td>

                                                    {{ $clean->jewelry_id }}
                                                </td>

                                                <td>
                                                    <button class="btn btn-s" type="button" data-toggle="modal"
                                                        data-target="#modalbuttoncleanrowpageone{{ $clean->id }}"style="background-color:#DADAE3;">
                                                        อัพเดตสถานะ
                                                    </button>
                                                </td>
                                                <div class="row">
                                                    <div class="modal fade"
                                                        id="modalbuttoncleanrowpageone{{ $clean->id }}" role="dialog"
                                                        aria-hidden="true" data-backdrop="static">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <form
                                                                    action="{{ route('jewelryupdatetocleaning', ['id' => $clean->id]) }}"
                                                                    method="POST">
                                                                    @csrf

                                                                    <div
                                                                        class="modal-header"style="background-color:#EAD8C0 ;">
                                                                        <h5 class="modal-title">การอัพเดตสถานะ</h5>

                                                                    </div>
                                                                    <div class="modal-body">

                                                                        <input type="hidden" name="jewelry_id"
                                                                            value="{{ $clean->jewelry_id }}">
                                                                        <p>ยืนยันว่าจะเปลี่ยนสถานะจาก <span
                                                                                style="color: #EE4E4E ; ">'รอดำเนินการ'</span>เป็น
                                                                            <span
                                                                                style="color: #EE4E4E ; ">'กำลังทำความสะอาด'</span>
                                                                        </p>


                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn"
                                                                            style="background-color:#DADAE3;"
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
                                            <button type="submit" class="btn "
                                                style="background-color:#ACE6B7;">ยืนยัน</button>
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

                                            <td>
                                                {{ $clean->jewvationtorefil->jewelry_m_o_typejew->type_jewelry_name }}
                                                {{ $clean->jewvationtorefil->jewelry_m_o_typejew->specific_letter }}{{ $clean->jewvationtorefil->jewelry_code }}
                                            </td>


                                            <td style="color: #a22222 ; ">{{ $clean->status }}</td>
                                            <td>

                                                {{ $clean->jewelry_id }}
                                            </td>

                                            <td>
                                                <button class="btn" type="button" data-toggle="modal"
                                                    style="background-color:#E3A499 ;"
                                                    data-target="#need_to_repair{{ $clean->id }}">ต้องซ่อม</button>
                                                <button class="btn " type="button" data-toggle="modal"
                                                    style="background-color:#ACE6B7;border:0"
                                                    data-target="#modalbuttoncleanrowpagetwo{{ $clean->id }}">พร้อมให้เช่าต่อ</button>
                                            </td>



                                            <div class="modal fade" id="modalbuttoncleanrowpagetwo{{ $clean->id }}"
                                                role="dialog" aria-hidden="true" data-backdrop="static">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <form
                                                            action="{{ route('jewelryupdatetocleaned', ['id' => $clean->id]) }}"
                                                            method="POST">
                                                            @csrf

                                                            <div class="modal-header"style="background-color:#EAD8C0 ;">
                                                                <h5 class="modal-title">การอัพเดตสถานะ</h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input type="hidden" name="jew_id"
                                                                    value="{{ $clean->jewelry_id }}">
                                                                ยืนยันว่าจะเปลี่ยนสถานะจาก "กำลังทำความสะอาด"เป็นเสร็จแล้ว  และพร้อมให้เช่าต่อ
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn"
                                                                    type="button"style="background-color:#DADAE3;"
                                                                    data-dismiss="modal">ยกเลิก</button>
                                                                <button class="btn" style="background-color:#ACE6B7;"
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
                                                        <form action="{{ route('jewelryupdatetocleanedbutrepair',['id' => $clean->id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="modal-header" style="background-color:#EAD8C0 ;">
                                                                <h5 class="model-title">ทำความสะอาดเสร็จแล้ว
                                                                    (ต้องซ่อมเนื่องจากเสียหาย)
                                                                </h5>
                                                            </div>
                                                            <div class="modal-body">


                                                                <input type="hidden" name="jew_id" value="{{ $clean->jewelry_id }}">

                                                                <p>รายละเอียดของการซ่อม</p>

                                                                <textarea name="repair_detail" cols="60" rows="4" class="form-control"
                                                                    placeholder="กรุณากรอกรายละเอียดของการซ่อมที่ต้องการ..." required></textarea>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn"
                                                                    style="background-color:#DADAE3;"
                                                                    data-dismiss="modal">ยกเลิก</button>
                                                                <button type="submit"style="background-color:#ACE6B7;"
                                                                    class="btn ">ยืนยัน</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
                                            <button type="submit"
                                                class="btn "style="background-color:#ACE6B7;">ยืนยัน</button>
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

@endsection
