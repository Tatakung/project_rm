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
                                    {{-- <th>สถานะ</th> --}}
                                    <th>คิวเช่าต่อไป </th>
                                    <th>จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($repair_pending as $item)
                                    <tr>
         
                                        <td>
                                            @if ($item->repair_many_to_one_filerdress->shirtitems_id)
                                                {{ $item->repair_many_to_one_filerdress->filterdress_many_to_one_dress->typedress->type_dress_name }}
                                                {{ $item->repair_many_to_one_filerdress->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->repair_many_to_one_filerdress->filterdress_many_to_one_dress->dress_code }}
                                                (เสื้อ)
                                            @elseif($item->repair_many_to_one_filerdress->skirtitems_id)
                                                {{ $item->repair_many_to_one_filerdress->filterdress_many_to_one_dress->typedress->type_dress_name }}
                                                {{ $item->repair_many_to_one_filerdress->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->repair_many_to_one_filerdress->filterdress_many_to_one_dress->dress_code }}
                                                (ผ้าถุง)
                                            @else
                                                {{ $item->repair_many_to_one_filerdress->filterdress_many_to_one_dress->typedress->type_dress_name }}
                                                {{ $item->repair_many_to_one_filerdress->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->repair_many_to_one_filerdress->filterdress_many_to_one_dress->dress_code }}
                                                (ทั้งชุด)
                                            @endif
                                            @if ($item->repair_type == 1)
                                                <p style="font-size: 14px; margin-left: 10px; color: #b11515 ; ">
                                                    -ยังไม่ได้ซัก</p>
                                            @else
                                                <p style="font-size: 14px; margin-left: 10px; color: rgb(62, 160, 40) ; ">
                                                    -ซักแล้ว</p>
                                            @endif
                                        </td>

                                        <td>
                                            {{$item->repair_description}}
                                        </td>

                                        {{-- <td style="color: #a22222 ; ">{{ $item->repair_status }}</td> --}}

                                        <td>



                                            <span id="next_station{{ $item->id }}"></span>

                                            @if ($item->repair_many_to_one_filerdress->filterdress_many_to_one_dress->separable == 1)
                                                @php
                                                    $near_separable_no_one = App\Models\Reservationfilterdress::where(
                                                        'dress_id',
                                                        $item->repair_many_to_one_filerdress->dress_id,
                                                    )
                                                        ->whereNot('id', $item->repair_many_to_one_filerdress->id)
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
                                            @elseif($item->repair_many_to_one_filerdress->filterdress_many_to_one_dress->separable == 2)
                                                @if ($item->repair_many_to_one_filerdress->shirtitems_id)
                                                
                                                    @php
                                                        $near_separable_yes_shirt_one = App\Models\Reservationfilterdress::where(
                                                            'shirtitems_id',
                                                            $item->repair_many_to_one_filerdress->shirtitems_id,
                                                        )
                                                            ->whereNot('id', $item->repair_many_to_one_filerdress->id)
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


                                                @elseif($item->repair_many_to_one_filerdress->skirtitems_id)


                                                @php
                                                        $near_separable_yes_skirt_one = App\Models\Reservationfilterdress::where(
                                                            'skirtitems_id',
                                                            $item->repair_many_to_one_filerdress->skirtitems_id,
                                                        )
                                                            ->whereNot('id', $item->repair_many_to_one_filerdress->id)
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
                                            <button class="btn btn-secondary" type="button" data-toggle="modal"
                                                data-target="#modalbuttonrepairrowpageone{{ $item->id }}">อัพเดตสถานะ</button>
                                        </td>
                                        <div class="row mt-3">
                                            <div class="modal fade" id="modalbuttonrepairrowpageone{{ $item->id }}"
                                                role="dialog" aria-hidden="true" data-backdrop="static">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <form
                                                            action="{{ route('employee.buttonrepairrowpageone', ['id' => $item->id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            <input type="hidden" value="{{$item->repair_many_to_one_filerdress->shirtitems_id}}" name="filter_shirtitems_id">
                                                            <input type="hidden" value="{{$item->repair_many_to_one_filerdress->skirtitems_id}}" name="filter_skirtitems_id">

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
                                        
                                        <th>คิวเช่าต่อไป </th>
                                        <th>จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($repairs as $item)
                                        <tr>

                                            <td>
                                                {{$item->reservationfilterdress_id}}

                                            </td>

                                            <td>
                                                @if ($item->repair_many_to_one_filerdress->shirtitems_id)
                                                    {{ $item->repair_many_to_one_filerdress->filterdress_many_to_one_dress->typedress->type_dress_name }}
                                                    {{ $item->repair_many_to_one_filerdress->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->repair_many_to_one_filerdress->filterdress_many_to_one_dress->dress_code }}
                                                    (เสื้อ)
                                                @elseif($item->repair_many_to_one_filerdress->skirtitems_id)
                                                    {{ $item->repair_many_to_one_filerdress->filterdress_many_to_one_dress->typedress->type_dress_name }}
                                                    {{ $item->repair_many_to_one_filerdress->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->repair_many_to_one_filerdress->filterdress_many_to_one_dress->dress_code }}
                                                    (ผ้าถุง)
                                                @else
                                                    {{ $item->repair_many_to_one_filerdress->filterdress_many_to_one_dress->typedress->type_dress_name }}
                                                    {{ $item->repair_many_to_one_filerdress->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->repair_many_to_one_filerdress->filterdress_many_to_one_dress->dress_code }}
                                                    (ทั้งชุด)
                                                @endif
                                                @if ($item->repair_type == 1)
                                                <p style="font-size: 14px; margin-left: 10px; color: #b11515 ; ">
                                                    -ยังไม่ได้ซัก</p>
                                            @else
                                                <p style="font-size: 14px; margin-left: 10px; color: rgb(62, 160, 40) ; ">
                                                    -ซักแล้ว</p>
                                            @endif
                                            </td>

                                            <td style="color: #a22222 ; ">{{ $item->repair_description }}</td>
                                            
                                            <td>



                                                <span id="next_station_two{{ $item->id }}"></span>

                                                @if ($item->repair_many_to_one_filerdress->filterdress_many_to_one_dress->separable == 1)
                                                    @php
                                                        $near_separable_no_two = App\Models\Reservationfilterdress::where(
                                                            'dress_id',
                                                            $item->repair_many_to_one_filerdress->dress_id,
                                                        )
                                                            ->whereNot('id', $item->repair_many_to_one_filerdress->id)
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
                                                @elseif($item->repair_many_to_one_filerdress->filterdress_many_to_one_dress->separable == 2)
                                                    @if ($item->repair_many_to_one_filerdress->shirtitems_id)
                                                        @php
                                                            $near_separable_yes_shirt_two = App\Models\Reservationfilterdress::where(
                                                                'shirtitems_id',
                                                                $item->repair_many_to_one_filerdress->shirtitems_id,
                                                            )
                                                                ->whereNot('id', $item->repair_many_to_one_filerdress->id)
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
                                                    @elseif($item->repair_many_to_one_filerdress->skirtitems_id)
                                                        @php
                                                            $near_separable_yes_skirt_two = App\Models\Reservationfilterdress::where(
                                                                'skirtitems_id',
                                                                $item->repair_many_to_one_filerdress->skirtitems_id,
                                                            )
                                                                ->whereNot('id', $item->repair_many_to_one_filerdress->id)
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
                                                <button type="button" class="btn btn-secondary" data-toggle="modal"
                                                    data-target="#butonpagetwocleanyes{{ $item->id }}">อัพเดตสถานะ</button>
                                            </td>

                                            <div class="modal fade" role="dialog" aria-hidden="true"
                                                id="butonpagetwocleanyes{{ $item->id }}" data-backdrop="static">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <form
                                                            action="{{ route('employee.repairupdatestatustocleanorreadybutton', ['id' => $item->id]) }}"
                                                            method="POST">
                                                            @csrf

                                                            <input type="hidden" value="{{$item->repair_many_to_one_filerdress->shirtitems_id}}" name="repair_for_shirt_id">
                                                            <input type="hidden" value="{{$item->repair_many_to_one_filerdress->skirtitems_id}}" name="repair_for_skirt_id">


                                                            <div class="modal-header"style="background-color:#EAD8C0 ;">
                                                                <h5 class="modal-title">การอัพเดตสถานะ</h5>

                                                                
                                                            </div>
                                                            <div class="modal-body">


                                                                @if($item->repair_type == 1 )
                                                                <p class="fw-bold mb-3">กระบวนการต่อไปคือ:</p>
                                                                    <div class="form-check mb-1">
                                                                        <input class="form-check-input" type="radio"
                                                                            name="status_next" id="toclean"
                                                                            value="1" style="accent-color: #0d6efd;"
                                                                            checked>
                                                                        <label class="form-check-label" for="toclean">
                                                                            ซ่อมเสร็จแล้ว และส่งทำความสะอาด
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
                                                                @elseif($item->repair_type == 2 )
                                                                 
                                                                <p class="fw-bold mb-3">กระบวนการต่อไปคือ:</p>
                                                                    <div class="form-check mb-1">
                                                                        <input class="form-check-input" type="radio"
                                                                            name="status_next" value="1"
                                                                            style="accent-color: #0d6efd;" checked>
                                                                        <label class="form-check-label">
                                                                            ซ่อมเสร็จแล้ว และพร้อมให้เช่าต่อ
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