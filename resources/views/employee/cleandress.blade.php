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

        <div class="card mb-4">



            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#one" class="nav-link active" data-toggle="tab">รอดำเนินการ
                        ({{ $clean_pending->count() }})</a>
                </li>
                <li class="nav-item">
                    <a href="#two" class="nav-link" data-toggle="tab">กำลังส่งซัก ({{ $clean_doing_wash->count() }})</a>
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
                                        @foreach ($clean_pending as $item)
                                            <tr>



                                                <td>
                                                    @if ($item->shirtitems_id)
                                                        {{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}
                                                        {{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}
                                                        (เสื้อ)
                                                    @elseif($item->skirtitems_id)
                                                        {{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}
                                                        {{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}
                                                        (ผ้าถุง)
                                                    @else
                                                        {{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}
                                                        {{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}
                                                        (ทั้งชุด)
                                                    @endif
                                                </td>

                                                <td style="color: #a22222 ; ">{{ $item->status }}</td>

                                                <td>



                                                    <span id="next_station{{ $item->id }}"></span>

                                                    @if ($item->filterdress_many_to_one_dress->separable == 1)
                                                        @php
                                                            $near_separable_no_one = App\Models\Reservationfilterdress::where(
                                                                'dress_id',
                                                                $item->dress_id,
                                                            )
                                                                ->whereNot('id', $item->id)
                                                                ->where('status_completed', 0)
                                                                ->orderByRaw("STR_TO_DATE(start_date, '%Y-%m-%d') asc")
                                                                ->where('status', 'ถูกจอง')
                                                                ->first();
                                                        @endphp
                                                        @if ($near_separable_no_one)
                                                            <script>
                                                                var now_separable_no_one = new Date();
                                                                var start_date_separable_no_one = new Date('{{ $near_separable_no_one->start_date }}');
                                                                var day_separable_no_one = start_date_separable_no_one - now_separable_no_one;
                                                                var total_separable_no_one = Math.ceil(day_separable_no_one / (1000 * 60 * 60 * 24));
                                                                document.getElementById('next_station{{ $item->id }}').innerHTML = 'ลูกค้าคนถัดไปจะมารับในอีก ' +
                                                                    total_separable_no_one + ' วัน';
                                                            </script>
                                                        @else
                                                            ไม่มีคิวจองต่อ
                                                        @endif
                                                    @elseif($item->filterdress_many_to_one_dress->separable == 2)
                                                        @if ($item->shirtitems_id)
                                                            @php
                                                                $near_separable_yes_shirt_one = App\Models\Reservationfilterdress::where(
                                                                    'shirtitems_id',
                                                                    $item->shirtitems_id,
                                                                )
                                                                    ->whereNot('id', $item->id)
                                                                    ->where('status_completed', 0)
                                                                    ->orderByRaw(
                                                                        "STR_TO_DATE(start_date, '%Y-%m-%d') asc",
                                                                    )
                                                                    ->where('status', 'ถูกจอง')
                                                                    ->first();
                                                            @endphp
                                                            @if ($near_separable_yes_shirt_one)
                                                                <script>
                                                                    var now_separable_yes_shirt_one = new Date();
                                                                    var start_date_separable_yes_shirt_one = new Date('{{ $near_separable_yes_shirt_one->start_date }}');
                                                                    var day_separable_yes_shirt_one = start_date_separable_yes_shirt_one - now_separable_yes_shirt_one;
                                                                    var total_separable_yes_shirt_one = Math.ceil(day_separable_yes_shirt_one / (1000 * 60 * 60 * 24));
                                                                    document.getElementById('next_station{{ $item->id }}').innerHTML = 'ลูกค้าคนถัดไปจะมารับในอีก ' +
                                                                        total_separable_yes_shirt_one + ' วัน';
                                                                </script>
                                                            @else
                                                                ไม่มีคิวจองต่อ
                                                            @endif
                                                        @elseif($item->skirtitems_id)
                                                            @php
                                                                $near_separable_yes_skirt_one = App\Models\Reservationfilterdress::where(
                                                                    'skirtitems_id',
                                                                    $item->skirtitems_id,
                                                                )
                                                                    ->whereNot('id', $item->id)
                                                                    ->where('status_completed', 0)
                                                                    ->orderByRaw(
                                                                        "STR_TO_DATE(start_date, '%Y-%m-%d') asc",
                                                                    )
                                                                    ->where('status', 'ถูกจอง')
                                                                    ->first();
                                                            @endphp
                                                            @if ($near_separable_yes_skirt_one)
                                                                <script>
                                                                    var now_separable_yes_skirt_one = new Date();
                                                                    var start_date_separable_yes_skirt_one = new Date('{{ $near_separable_yes_skirt_one->start_date }}');
                                                                    var day_separable_yes_skirt_one = start_date_separable_yes_skirt_one - now_separable_yes_skirt_one;
                                                                    var total_separable_yes_skirt_one = Math.ceil(day_separable_yes_skirt_one / (1000 * 60 * 60 * 24));
                                                                    document.getElementById('next_station{{ $item->id }}').innerHTML = 'ลูกค้าคนถัดไปจะมารับในอีก ' +
                                                                        total_separable_yes_skirt_one + ' วัน';
                                                                </script>
                                                            @else
                                                                ไม่มีคิวจองต่อ
                                                            @endif
                                                        @endif
                                                    @endif







                                                </td>

                                                <td>
                                                    <button class="btn btn-s" type="button" data-toggle="modal"
                                                        data-target="#modalbuttoncleanrowpageone{{ $item->id }}"style="background-color:#DADAE3;">
                                                        อัพเดตสถานะ
                                                    </button>
                                                </td>
                                                <div class="row">
                                                    <div class="modal fade"
                                                        id="modalbuttoncleanrowpageone{{ $item->id }}" role="dialog"
                                                        aria-hidden="true" data-backdrop="static">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <form
                                                                    action="{{ route('employee.buttoncleanrowpageone', ['id' => $item->id]) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <div
                                                                        class="modal-header"style="background-color:#EAD8C0 ;">
                                                                        <h5 class="modal-title">การอัพเดตสถานะ</h5>

                                                                    </div>
                                                                    <div class="modal-body">

                                                                        <input type="hidden"
                                                                            value="{{ $item->shirtitems_id }}"
                                                                            name="filterdress_shirt">
                                                                        <input type="hidden"
                                                                            value="{{ $item->skirtitems_id }}"
                                                                            name="filterdress_skirt">
                                                                        <p>ยืนยันว่าจะเปลี่ยนสถานะจาก'รอดำเนินการ'เป็น'กำลังส่งซัก'
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
                                    @foreach ($clean_doing_wash as $item)
                                        <tr>

                                            <td>
                                                {{$item->id}}
                                            </td>

                                            <td>
                                                @if ($item->shirtitems_id)
                                                    {{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}
                                                    {{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}
                                                    (เสื้อ)
                                                @elseif($item->skirtitems_id)
                                                    {{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}
                                                    {{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}
                                                    (ผ้าถุง)
                                                @else
                                                    {{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}
                                                    {{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}
                                                    (ทั้งชุด)
                                                @endif
                                            </td>

                                            <td style="color: #a22222 ; ">{{ $item->status }}</td>

                                            <td>



                                                <span id="next_station_two{{ $item->id }}"></span>

                                                @if ($item->filterdress_many_to_one_dress->separable == 1)
                                                    @php
                                                        $near_separable_no_two = App\Models\Reservationfilterdress::where(
                                                            'dress_id',
                                                            $item->dress_id,
                                                        )
                                                            ->whereNot('id', $item->id)
                                                            ->where('status_completed', 0)
                                                            ->orderByRaw("STR_TO_DATE(start_date, '%Y-%m-%d') asc")
                                                            ->where('status', 'ถูกจอง')
                                                            ->first();
                                                    @endphp
                                                    @if ($near_separable_no_two)
                                                        <script>
                                                            var now_separable_no_two = new Date();
                                                            var start_date_separable_no_two = new Date('{{ $near_separable_no_two->start_date }}');
                                                            var day_separable_no_two = start_date_separable_no_two - now_separable_no_two;
                                                            var total_separable_no_two = Math.ceil(day_separable_no_two / (1000 * 60 * 60 * 24));
                                                            document.getElementById('next_station_two{{ $item->id }}').innerHTML = 'ลูกค้าคนถัดไปจะมารับในอีก ' +
                                                                total_separable_no_two + ' วัน';
                                                        </script>
                                                    @else
                                                        ไม่มีคิวจองต่อ
                                                    @endif
                                                @elseif($item->filterdress_many_to_one_dress->separable == 2)
                                                    @if ($item->shirtitems_id)
                                                        @php
                                                            $near_separable_yes_shirt_two = App\Models\Reservationfilterdress::where(
                                                                'shirtitems_id',
                                                                $item->shirtitems_id,
                                                            )
                                                                ->whereNot('id', $item->id)
                                                                ->where('status_completed', 0)
                                                                ->orderByRaw("STR_TO_DATE(start_date, '%Y-%m-%d') asc")
                                                                ->where('status', 'ถูกจอง')
                                                                ->first();
                                                        @endphp
                                                        @if ($near_separable_yes_shirt_two)
                                                            <script>
                                                                var now_separable_yes_shirt_two = new Date();
                                                                var start_date_separable_yes_shirt_two = new Date('{{ $near_separable_yes_shirt_two->start_date }}');
                                                                var day_separable_yes_shirt_two = start_date_separable_yes_shirt_two - now_separable_yes_shirt_two;
                                                                var total_separable_yes_shirt_two = Math.ceil(day_separable_yes_shirt_two / (1000 * 60 * 60 * 24));
                                                                document.getElementById('next_station_two{{ $item->id }}').innerHTML = 'ลูกค้าคนถัดไปจะมารับในอีก ' +
                                                                    total_separable_yes_shirt_two + ' วัน';
                                                            </script>
                                                        @else
                                                            ไม่มีคิวจองต่อ
                                                        @endif
                                                    @elseif($item->skirtitems_id)
                                                        @php
                                                            $near_separable_yes_skirt_two = App\Models\Reservationfilterdress::where(
                                                                'skirtitems_id',
                                                                $item->skirtitems_id,
                                                            )
                                                                ->whereNot('id', $item->id)
                                                                ->where('status_completed', 0)
                                                                ->orderByRaw("STR_TO_DATE(start_date, '%Y-%m-%d') asc")
                                                                ->where('status', 'ถูกจอง')
                                                                ->first();
                                                        @endphp
                                                        @if ($near_separable_yes_skirt_two)
                                                            <script>
                                                                var now_separable_yes_skirt_two = new Date();
                                                                var start_date_separable_yes_skirt_two = new Date('{{ $near_separable_yes_skirt_two->start_date }}');
                                                                var day_separable_yes_skirt_two = start_date_separable_yes_skirt_two - now_separable_yes_skirt_two;
                                                                var total_separable_yes_skirt_two = Math.ceil(day_separable_yes_skirt_two / (1000 * 60 * 60 * 24));
                                                                document.getElementById('next_station_two{{ $item->id }}').innerHTML = 'ลูกค้าคนถัดไปจะมารับในอีก ' +
                                                                    total_separable_yes_skirt_two + ' วัน';
                                                            </script>
                                                        @else
                                                            ไม่มีคิวจองต่อ
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>





                                            <td>
                                                <button class="btn" type="button" data-toggle="modal"
                                                    style="background-color:#E3A499 ;"
                                                    data-target="#need_to_repair{{ $item->id }}">ต้องซ่อม</button>
                                                <button class="btn " type="button" data-toggle="modal"
                                                    style="background-color:#ACE6B7;border:0"
                                                    data-target="#modalbuttoncleanrowpagetwo{{ $item->id }}">พร้อมให้เช่าต่อ</button>
                                            </td>



                                            <div class="modal fade" id="modalbuttoncleanrowpagetwo{{ $item->id }}"
                                                role="dialog" aria-hidden="true" data-backdrop="static">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <form
                                                            action="{{ route('employee.buttoncleanrowpagetwo', ['id' => $item->id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="modal-header"style="background-color:#EAD8C0 ;">
                                                                <h5 class="modal-title">การอัพเดตสถานะ</h5>
                                                            </div>
                                                            <input type="hidden" value="{{ $item->shirtitems_id }}"
                                                                name="filterdress_shirt_two">
                                                            <input type="hidden" value="{{ $item->skirtitems_id }}"
                                                                name="filterdress_skirt_two">
                                                            <div class="modal-body">
                                                                ยืนยันว่าจะเปลี่ยนสถานะจาก "กำลังส่งซัก"เป็นเสร็จแล้ว
                                                                และพร้อมให้เช่าต่อ
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






                                            <div class="modal fade" id="need_to_repair{{ $item->id }}"
                                                role="dialog" aria-hidden="true" data-backdrop="static">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <form
                                                            action="{{ route('employee.afterwashtorepair', ['id' => $item->id]) }}"
                                                            method="POST">
                                                            @csrf

                                                            <input type="hidden" value="{{ $item->shirtitems_id }}"
                                                                name="filterdress_shirt_two_repair">
                                                            <input type="hidden" value="{{ $item->skirtitems_id }}"
                                                                name="filterdress_skirt_two_repair">
                                                            <div class="modal-header" style="background-color:#EAD8C0 ;">
                                                                <h5 class="model-title">ซักเสร็จแล้ว
                                                                    (ต้องซ่อมเนื่องจากเสียหาย) </h5>
                                                            </div>



                                                            <div class="modal-body">



                                                                @if ($item->shirtitems_id)
                                                                    <p>รายละเอียดของการซ่อมของ{{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}
                                                                        {{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}
                                                                        (เสื้อ)</p>
                                                                @elseif($item->skirtitems_id)
                                                                    <p>รายละเอียดของการซ่อมของ{{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}
                                                                        {{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}
                                                                        (ผ้าถุง)</p>
                                                                @else
                                                                    <p>รายละเอียดของการซ่อมของ{{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}
                                                                        {{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}
                                                                        (ทั้งชุด)</p>
                                                                @endif




                                                                <textarea name="repair_detail" cols="60" rows="4" class="form-control"
                                                                    placeholder="กรุณากรอกรายละเอียดของการซ่อมที่ต้องการ..."></textarea>
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
    </div>
@endsection
