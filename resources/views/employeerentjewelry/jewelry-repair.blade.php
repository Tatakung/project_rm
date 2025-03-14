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

        <h1 class="my-4 text-start">จัดการการซ่อมเครื่องประดับ</h1>
        <p>การทำความสะอาด/ซ่อมเครื่องประดับทุกชิ้นควรเสร็จภายใน 7 วันหลังจากลูกค้านำเครื่องประดับมาคืน
            เพื่อให้สามารถตรวจสอบและซ่อมแซมเครื่องประดับได้ทันท่วงที
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
                                    
                                    <th>คิวเช่าต่อไป </th>
                                    <th>จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($repair_pending as $repair)
                                    <tr>


                                        <td>

                                            {{ $repair->repair_many_to_one_reservationfilter->jewvationtorefil->jewelry_m_o_typejew->type_jewelry_name }}
                                            {{ $repair->repair_many_to_one_reservationfilter->jewvationtorefil->jewelry_m_o_typejew->specific_letter }}{{ $repair->repair_many_to_one_reservationfilter->jewvationtorefil->jewelry_code }}



                                            @if ($repair->repair_type == 1)
                                                <p style="font-size: 14px; margin-left: 10px; color: #b11515 ; ">
                                                    -ยังไม่ได้ทำความสะอาด</p>
                                            @elseif($repair->repair_type == 2)
                                                <p style="font-size: 14px; margin-left: 10px; color: rgb(62, 160, 40) ; ">
                                                    -ทำความสะอาดแล้ว</p>
                                            @endif
                                        </td>

                                        <td>
                                            {{ $repair->repair_description }}
                                        </td>

                                        

                                        <td>


                                            <span id="day{{ $repair->id }}"></span>
                                            @php
                                                $next_q = App\Models\Reservationfilters::where(
                                                    'jewelry_id',
                                                    $repair->repair_many_to_one_reservationfilter->jewelry_id,
                                                )
                                                    ->whereNot('id', $repair->repair_many_to_one_reservationfilter->id)
                                                    ->where('status_completed', 0)
                                                    ->orderByRaw("STR_TO_DATE(start_date, '%Y-%m-%d') asc")
                                                    ->where('status', 'ถูกจอง')
                                                    ->first();
                                            @endphp
                                            @if ($next_q)
                                                <script>
                                                    var now = new Date();
                                                    var start_date = new Date('{{ $next_q->start_date }}');
                                                    var day = start_date - now;
                                                    var total = Math.ceil(day / (1000 * 60 * 60 * 24));
                                                    var show = document.getElementById('day{{ $repair->id }}');
                                                    show.innerHTML = 'ลูกค้าคนถัดไปจะมารับในอีก ' + total + ' วัน';
                                                </script>
                                            @else
                                                ไม่มีคิวจองต่อ
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-secondary" type="button" data-toggle="modal"
                                                data-target="#modalbuttonrepairrowpageone{{ $repair->id }}">อัพเดตสถานะ</button>
                                        </td>
                                        <div class="row mt-3">
                                            <div class="modal fade" id="modalbuttonrepairrowpageone{{ $repair->id }}"
                                                role="dialog" aria-hidden="true" data-backdrop="static">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <form
                                                            action="{{ route('jewelryupdatetorepairing', ['id' => $repair->id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="modal-header"style="background-color:#EAD8C0 ;">
                                                                <h5 class="modal-title">อัพเดตสถานะ</h5>
                                                            </div>
                                                            <div class="modal-body">

                                                                <input type="hidden" name="jewelry_id"
                                                                    value="{{ $repair->repair_many_to_one_reservationfilter->jewvationtorefil->id }}">
                                                                <input type="hidden" name="reservationfilter_id"
                                                                    value="{{ $repair->reservationfilter_id }}">

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
                    {{-- tableของ ยังไม่ได้ซัก --}}
                    @if ($repairs->count() > 0)
                        <div class="table-responsive">
                            {{-- <h5 style="text-align: center ; ">ยังไม่ได้ซัก</h5> --}}
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        {{-- <th>เลือก</th> --}}
                                        <th>รายการซ่อม</th>
                                        <th>รายละเอียดของการซ่อม</th>
                                        
                                        <th>คิวเช่าต่อไป </th>
                                        <th>จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($repairs as $repair)
                                        <tr>


                                            <td>


                                                {{ $repair->repair_many_to_one_reservationfilter->jewvationtorefil->jewelry_m_o_typejew->type_jewelry_name }}
                                                {{ $repair->repair_many_to_one_reservationfilter->jewvationtorefil->jewelry_m_o_typejew->specific_letter }}{{ $repair->repair_many_to_one_reservationfilter->jewvationtorefil->jewelry_code }}


                                                @if ($repair->repair_type == 1)
                                                    <p style="font-size: 14px; margin-left: 10px; color: #b11515 ; ">
                                                        -ยังไม่ได้ทำความสะอาด</p>
                                                @elseif($repair->repair_type == 2)
                                                    <p
                                                        style="font-size: 14px; margin-left: 10px; color: rgb(62, 160, 40) ; ">
                                                        -ทำความสะอาดแล้ว</p>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $repair->repair_description }}
                                            </td>

                                            
                                            
                                            <td>
                                                <span id="days{{ $repair->id }}"></span>
                                                @php
                                                    $next_qq = App\Models\Reservationfilters::where(
                                                        'jewelry_id',
                                                        $repair->repair_many_to_one_reservationfilter->jewelry_id,
                                                    )
                                                        ->whereNot(
                                                            'id',
                                                            $repair->repair_many_to_one_reservationfilter->id,
                                                        )
                                                        ->where('status_completed', 0)
                                                        ->orderByRaw("STR_TO_DATE(start_date, '%Y-%m-%d') asc")
                                                        ->where('status', 'ถูกจอง')
                                                        ->first();
                                                @endphp
                                                @if ($next_qq)
                                                    <script>
                                                        var noww = new Date();
                                                        var start_datee = new Date('{{ $next_qq->start_date }}');
                                                        var dayy = start_datee - noww;
                                                        var totall = Math.ceil(dayy / (1000 * 60 * 60 * 24));
                                                        var showw = document.getElementById('days{{ $repair->id }}');
                                                        showw.innerHTML = 'ลูกค้าคนถัดไปจะมารับในอีก ' + totall + ' วัน';
                                                    </script>
                                                @else
                                                    ไม่มีคิวจองต่อ
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-secondary" data-toggle="modal"
                                                    data-target="#update_two_no_clean{{ $repair->id }}">อัพเดตสถานะ</button>
                                            </td>

                                            <div class="modal fade" id="update_two_no_clean{{ $repair->id }}"
                                                role="dialog" aria-hidden="true" data-backdrop="static">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <form
                                                            action="{{ route('jewelryupdatetorepaired', ['id' => $repair->id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="modal-header"style="background-color:#EAD8C0 ;">
                                                                <h5 class="modal-title">การอัพเดตสถานะ</h5>
                                                            </div>
                                                            <div class="modal-body">

                                                                <input type="hidden" name="repair_type"
                                                                    value="{{ $repair->repair_type }}">

                                                                <input type="hidden" name="jewelry_id"
                                                                    value="{{ $repair->repair_many_to_one_reservationfilter->jewvationtorefil->id }}">

                                                                <input type="hidden" name="reser_fil"
                                                                    value="{{ $repair->reservationfilter_id }}">


                                                                @if ($repair->repair_type == 1)
                                                                    <p class="fw-bold mb-3">กระบวนการต่อไปคือ:</p>
                                                                    <div class="form-check mb-1">
                                                                        <input class="form-check-input" type="radio"
                                                                            name="status_next" id="toclean"
                                                                            value="1" style="accent-color: #0d6efd;"
                                                                            checked>
                                                                        <label class="form-check-label" for="toclean">
                                                                            ทำความสะอาด
                                                                        </label>
                                                                    </div>

                                                                    <div class="form-check mb-1">
                                                                        <input class="form-check-input" type="radio"
                                                                            name="status_next" value="2"
                                                                            style="accent-color: #0d6efd;">
                                                                        <label class="form-check-label">
                                                                            ซ่อมไม่ได้ ไม่สามารถให้เช่าต่อได้
                                                                        </label>
                                                                    </div>
                                                                @elseif($repair->repair_type == 2)
                                                                    <p class="fw-bold mb-3">กระบวนการต่อไปคือ:</p>
                                                                    <div class="form-check mb-1">
                                                                        <input class="form-check-input" type="radio"
                                                                            name="status_next" value="1"
                                                                            style="accent-color: #0d6efd;" checked>
                                                                        <label class="form-check-label">
                                                                            พร้อมให้เช่าต่อ
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check mb-1">
                                                                        <input class="form-check-input" type="radio"
                                                                            name="status_next" id="toclean"
                                                                            value="2" style="accent-color: #0d6efd;">
                                                                        <label class="form-check-label" for="toclean">
                                                                            ทำความสะอาดอีกครั้ง
                                                                        </label>
                                                                    </div>

                                                                    <div class="form-check mb-1">
                                                                        <input class="form-check-input" type="radio"
                                                                            name="status_next" value="3"
                                                                            style="accent-color: #0d6efd;">
                                                                        <label class="form-check-label">
                                                                            ซ่อมไม่ได้ ไม่สามารถให้เช่าต่อได้
                                                                        </label>
                                                                    </div>
                                                                @endif


                                                            </div>



                                                            <div class="modal-footer">
                                                                <button class="btn" type="button"
                                                                    data-dismiss="modal"
                                                                    style="background-color:#DADAE3;">ยกเลิก</button>
                                                                <button class="btn" type="submit"
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
                            </div>

                            <div class="modal fade" id="showmodalrepairupdatestatustoclean" role="dialog"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header"style="background-color:#EAD8C0 ;">
                                            <h5 class="modal-title">อัพเดตสถานะ</h5>
                                        </div>
                                        <div class="modal-body" id="statusmessage">
                                            ยืนยันว่าจะเปลี่ยนสถานะจาก 'กำลังซ่อม'เป็น 'ซ่อมเสร็จแล้ว'
                                            และส่งซักต่อไป
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger"
                                                data-dismiss="modal"style="background-color:#DADAE3;">ยกเลิก</button>
                                            <button type="submit" class="btn"
                                                style="background-color:#ACE6B7;">ยืนยัน</button>
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
