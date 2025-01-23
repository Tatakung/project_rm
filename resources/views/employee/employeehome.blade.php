@extends('layouts.adminlayout')
@section('content')
    <style>
        .card {
            transition: transform 0.2s;
            border-radius: 15px;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .text-muted {
            color: #6c757d !important;
        }

        .shadow-sm {
            box-shadow: 0 .5rem .55rem rgba(0, 0, 0, .075) !important;
        }

        .scrollable-content {
            height: 150px;
            overflow-y: hidden;
            padding-right: 10px;
            transition: overflow 0.3s ease;
        }

        .scrollable-content:hover,
        .scrollable-content:focus {
            overflow-y: auto;
        }
    </style>
    <div class="container py-4">
        <!-- Header Section -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="mb-2">รายการงานทั้งหมด</h2>
            </div>
        </div>

        <!-- Priority Tasks Section -->
        <div class="row g-4 mb-2">
            <!-- รายการรับคืนชุด -->




            <!-- งานตัดที่รอดำเนินการ -->
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <h5 class="card-title mb-0"> ชุดที่รอการตัด</h5>
                        </div>
                        <div class="scrollable-content ps-4">


                            @if ($work_waiting_to_cut->count() > 0)
                                @foreach ($work_waiting_to_cut as $item)
                                    <div class="mb-3">

                                        @php
                                            $DATE_waiting = App\Models\Date::where('order_detail_id', $item->id)
                                                ->orderBy('created_at', 'desc')
                                                ->first();
                                        @endphp

                                        @if ($item->type_order == 1)
                                            <p class="text-muted ms-2">- ตัด{{ $item->type_dress }}
                                                <span id="work_waiting_to_cut_one{{ $item->id }}"
                                                    style="color: red ; font-size: 14px;"></span>
                                            </p>

                                            <script>
                                                var work_waiting_date_one = new Date('{{ $DATE_waiting->pickup_date }}');
                                                var now_waiting_one = new Date();
                                                var waiting_one_day = Math.ceil((work_waiting_date_one - now_waiting_one) / (1000 * 60 * 60 * 60));
                                                document.getElementById('work_waiting_to_cut_one{{ $item->id }}').innerHTML = 'อีก ' + waiting_one_day +
                                                    ' วันลูกค้าจะมารับชุด';
                                            </script>
                                        @elseif($item->type_order == 4)
                                            <p class="text-muted ms-2">- เช่าตัด{{ $item->type_dress }}
                                                <span id="work_waiting_to_cut_two{{ $item->id }}"
                                                    style="color: red ; font-size: 14px;"></span>
                                            </p>

                                            <script>
                                                var work_waiting_date_two = new Date('{{ $DATE_waiting->pickup_date }}');
                                                var now_waiting_two = new Date();
                                                var waiting_two_day = Math.ceil((work_waiting_date_two - now_waiting_two) / (1000 * 60 * 60 * 60));
                                                document.getElementById('work_waiting_to_cut_two{{ $item->id }}').innerHTML = 'อีก ' + waiting_two_day +
                                                    ' วันลูกค้าจะมารับชุด';
                                            </script>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted ms-2">ไม่มีรายการที่รอดำเนินงาน</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>






            <!-- รายการรับคืนเครื่องประดับ -->
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <!-- <i class="fas fa-gem text-warning me-2"></i> -->
                            <h5 class="card-title mb-0"> รายการชุด/เครื่องประดับที่รอรับคืน</h5>
                        </div>
                        <div class="scrollable-content ps-4">
                        @if ($return_jewelry_today->count() > 0 || $return_dress_today->count() > 0)
                            @foreach ($return_jewelry_today as $item)
                                @if ($item->jewelry_id)
                                    <p class="text-muted ms-2">
                                        -{{ $item->resermanytoonejew->jewelry_m_o_typejew->type_jewelry_name }}
                                        {{ $item->resermanytoonejew->jewelry_m_o_typejew->specific_letter }}{{ $item->resermanytoonejew->jewelry_code }}
                                    </p>
                                @elseif($item->jewelry_set_id)
                                    @php
                                        if ($item->jewelry_set_id) {
                                            $set_jew_item = App\Models\Jewelrysetitem::where(
                                                'jewelry_set_id',
                                                $item->jewelry_set_id,
                                            )->get();
                                        }
                                    @endphp

                                    @foreach ($set_jew_item as $item)
                                        <p class="text-muted ms-2">
                                            -{{ $item->jewitem_m_to_o_jew->jewelry_m_o_typejew->type_jewelry_name }}
                                            {{ $item->jewitem_m_to_o_jew->jewelry_m_o_typejew->specific_letter }}{{ $item->jewitem_m_to_o_jew->jewelry_code }}
                                        </p>
                                    @endforeach
                                @endif
                            @endforeach
                            @foreach ($return_dress_today as $item)
                                @if ($item->re_one_many_details->first() && in_array($item->re_one_many_details->first()->type_order, [2, 4]))
                                    @if ($item->shirtitems_id)
                                        <p class="text-muted ms-2">
                                            -{{ $item->reservation_many_to_one_dress->typedress->type_dress_name }}
                                            {{ $item->reservation_many_to_one_dress->typedress->specific_letter }}{{ $item->reservation_many_to_one_dress->dress_code }}
                                            (ผ้าถุง)
                                        </p>
                                    @elseif($item->skirtitems_id)
                                        <p class="text-muted ms-2">
                                            -{{ $item->reservation_many_to_one_dress->typedress->type_dress_name }}
                                            {{ $item->reservation_many_to_one_dress->typedress->specific_letter }}{{ $item->reservation_many_to_one_dress->dress_code }}
                                            (เสื้อ)
                                        </p>
                                    @else
                                        <p class="text-muted ms-2">
                                            -{{ $item->reservation_many_to_one_dress->typedress->type_dress_name }}
                                            {{ $item->reservation_many_to_one_dress->typedress->specific_letter }}{{ $item->reservation_many_to_one_dress->dress_code }}
                                            (ทั้งชุด)
                                        </p>
                                    @endif
                                @endif
                            @endforeach
                        @else
                            <p class="text-muted ms-2">ไม่มีรายการที่รอดำเนินงาน</p>
                        @endif
                    </div>
                </div>
                </div>
            </div>
        </div>



        <!-- Tasks In Progress Section -->
        <div class="row g-4 mb-2">





            <!-- ชุดที่รอดำเนินการซักทั้งหมด -->
            <div class="col-6 g-4 mb-2">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <!-- <i class="fas fa-calendar-check text-info me-2"></i> -->
                            <h5 class="card-title mb-0">รายการชุด/เครื่องประดับที่รอทำความสะอาด</h5>
                        </div>
                        <div class="scrollable-content ps-4">


                            @if ($clean_pending->count() > 0 || $clean_pending_jewelry->count() > 0)
                                @foreach ($clean_pending as $item)
                                    @php
                                        $near_dress = App\Models\Reservation::whereNot('id', $item->reservation_id)
                                            ->where('dress_id', $item->clean_one_to_one_reser->dress_id)
                                            ->where('status', 'ถูกจอง')
                                            ->orderByRaw("STR_TO_DATE(start_date,'%Y-%m-%d') asc")
                                            ->first();
                                    @endphp


                                    @if ($item->clean_one_to_one_reser->shirtitems_id)
                                        <p class="text-muted ms-2">
                                            -{{ $item->clean_one_to_one_reser->reservation_many_to_one_dress->typedress->type_dress_name }}
                                            {{ $item->clean_one_to_one_reser->reservation_many_to_one_dress->typedress->specific_letter }}{{ $item->clean_one_to_one_reser->reservation_many_to_one_dress->dress_code }}
                                            (เสื้อ)
                                            <span style="font-size: 13px; color: red ;"
                                                id="clean_next_dress_shirt{{ $item->id }}"></span>
                                            @if ($near_dress != null)
                                                <script>
                                                    var start_date = new Date("{{ $near_dress->start_date }}");
                                                    var now = new Date();
                                                    var day = start_date - now;
                                                    var total = Math.ceil(day / (1000 * 60 * 60 * 24));
                                                    document.getElementById('clean_next_dress_shirt{{ $item->id }}').innerHTML =
                                                        'ลูกค้าคนถัดไปจะมารับชุดในอีก ' + total + ' วัน';
                                                </script>
                                            @endif
                                        </p>
                                    @elseif($item->clean_one_to_one_reser->skirtitems_id)
                                        <p class="text-muted ms-2">
                                            -{{ $item->clean_one_to_one_reser->reservation_many_to_one_dress->typedress->type_dress_name }}
                                            {{ $item->clean_one_to_one_reser->reservation_many_to_one_dress->typedress->specific_letter }}{{ $item->clean_one_to_one_reser->reservation_many_to_one_dress->dress_code }}
                                            (ผ้าถุง)
                                            <span style="font-size: 13px; color: red ;"
                                                id="clean_next_dress_skirt{{ $item->id }}"></span>
                                            @if ($near_dress != null)
                                                <script>
                                                    var start_date = new Date("{{ $near_dress->start_date }}");
                                                    var now = new Date();
                                                    var day = start_date - now;
                                                    var total = Math.ceil(day / (1000 * 60 * 60 * 24));
                                                    document.getElementById('clean_next_dress_skirt{{ $item->id }}').innerHTML =
                                                        'ลูกค้าคนถัดไปจะมารับชุดในอีก ' + total + ' วัน';
                                                </script>
                                            @endif
                                        </p>
                                    @else
                                        <p class="text-muted ms-2">
                                            -{{ $item->clean_one_to_one_reser->reservation_many_to_one_dress->typedress->type_dress_name }}
                                            {{ $item->clean_one_to_one_reser->reservation_many_to_one_dress->typedress->specific_letter }}{{ $item->clean_one_to_one_reser->reservation_many_to_one_dress->dress_code }}
                                            (ทั้งชุด)
                                            <span style="font-size: 14px; color: red ;"
                                                id="clean_next_dress{{ $item->id }}"></span>
                                        </p>
                                        @if ($near_dress != null)
                                            <script>
                                                var start_date = new Date("{{ $near_dress->start_date }}");
                                                var now = new Date();
                                                var day = start_date - now;
                                                var total = Math.ceil(day / (1000 * 60 * 60 * 24));
                                                document.getElementById('clean_next_dress{{ $item->id }}').innerHTML =
                                                    'ลูกค้าคนถัดไปจะมารับชุดในอีก ' + total + ' วัน';
                                            </script>
                                        @endif
                                    @endif
                                @endforeach

                                @foreach ($clean_pending_jewelry as $item)
                                    @php
                                        $near_clean_pending_jewelry = App\Models\Reservationfilters::where(
                                            'jewelry_id',
                                            $item->jewelry_id,
                                        )
                                            ->whereNot('id', $item->id)
                                            ->where('status', 'ถูกจอง')
                                            ->where('status_completed', 0)
                                            ->orderByRaw("STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                                            ->first();
                                    @endphp
                                    <p class="text-muted ms-2">
                                        -{{ $item->jewvationtorefil->jewelry_m_o_typejew->type_jewelry_name }}
                                        {{ $item->jewvationtorefil->jewelry_m_o_typejew->specific_letter }}{{ $item->jewvationtorefil->jewelry_code }}
                                        <span style="font-size: 14px; color: red ;"
                                            id="show_near_clean_pending_jewelry{{ $item->id }}"></span>
                                    </p>

                                    @if ($near_clean_pending_jewelry)
                                        <script>
                                            var now_near_clean_pending_jewelry = new Date();
                                            var start_date_near_clean_pending_jewelry = new Date('{{ $near_clean_pending_jewelry->start_date }}');
                                            var finish_clean_pending_jewelry = Math.ceil((start_date_near_clean_pending_jewelry -
                                                now_near_clean_pending_jewelry) / (1000 * 60 * 60 * 24))
                                            document.getElementById('show_near_clean_pending_jewelry{{ $item->id }}').innerHTML =
                                                'ลูกค้าคนถัดไปจะมาในอีก ' + finish_clean_pending_jewelry + ' วัน';
                                        </script>
                                    @endif
                                @endforeach
                            @else
                                <p class="text-muted ms-2">ไม่มีรายการที่รอดำเนินงาน </p>
                            @endif





                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <!-- <i class="fas fa-calendar-check text-info me-2"></i> -->
                            {{-- <h5 class="card-title mb-0">ชุดที่รอซ่อม</h5> --}}
                            <h5 class="card-title mb-0">ชุด/เครื่องประดับที่รอซ่อม
                            </h5>

                        </div>
                        <div class="scrollable-content ps-4">
                            @if ($repair->count() > 0 || $repair_jewelry->count() > 0)
                                @foreach ($repair as $item)
                                    @php
                                        $near_dress_repair = App\Models\Reservation::whereNot(
                                            'id',
                                            $item->reservation_id,
                                        )
                                            ->where('dress_id', $item->repair_many_to_one_reser->dress_id)
                                            ->where('status', 'ถูกจอง')
                                            ->orderByRaw("STR_TO_DATE(start_date,'%Y-%m-%d') asc")
                                            ->first();

                                    @endphp
                                    @if ($item->repair_many_to_one_reser->shirtitems_id)
                                        <p class="text-muted ms-2">
                                            -{{ $item->repair_many_to_one_reser->reservation_many_to_one_dress->typedress->type_dress_name }}
                                            {{ $item->repair_many_to_one_reser->reservation_many_to_one_dress->typedress->specific_letter }}{{ $item->repair_many_to_one_reser->reservation_many_to_one_dress->dress_code }}
                                            (เสื้อ)
                                            <span style="font-size: 13px; color: red ;"
                                                id="repair_next_dress_shirt{{ $item->id }}"></span>
                                            @if ($near_dress_repair != null)
                                                <script>
                                                    var start_date = new Date("{{ $near_dress_repair->start_date }}");
                                                    var now = new Date();
                                                    var day = start_date - now;
                                                    var total = Math.ceil(day / (1000 * 60 * 60 * 24));
                                                    document.getElementById('repair_next_dress_shirt{{ $item->id }}').innerHTML =
                                                        'ลูกค้าคนถัดไปจะมารับชุดในอีก ' + total + ' วัน';
                                                </script>
                                            @endif
                                        </p>
                                    @elseif($item->repair_many_to_one_reser->skirtitems_id)
                                        <p class="text-muted ms-2">
                                            -{{ $item->repair_many_to_one_reser->reservation_many_to_one_dress->typedress->type_dress_name }}
                                            {{ $item->repair_many_to_one_reser->reservation_many_to_one_dress->typedress->specific_letter }}{{ $item->repair_many_to_one_reser->reservation_many_to_one_dress->dress_code }}
                                            (ผ้าถุง)
                                            <span style="font-size: 13px; color: red ;"
                                                id="repair_next_dress_skirt{{ $item->id }}"></span>
                                            @if ($near_dress_repair != null)
                                                <script>
                                                    var start_date = new Date("{{ $near_dress_repair->start_date }}");
                                                    var now = new Date();
                                                    var day = start_date - now;
                                                    var total = Math.ceil(day / (1000 * 60 * 60 * 24));
                                                    document.getElementById('repair_next_dress_skirt{{ $item->id }}').innerHTML =
                                                        'ลูกค้าคนถัดไปจะมารับชุดในอีก ' + total + ' วัน';
                                                </script>
                                            @endif
                                        </p>
                                    @else
                                        <p class="text-muted ms-2">
                                            -{{ $item->repair_many_to_one_reser->reservation_many_to_one_dress->typedress->type_dress_name }}
                                            {{ $item->repair_many_to_one_reser->reservation_many_to_one_dress->typedress->specific_letter }}{{ $item->repair_many_to_one_reser->reservation_many_to_one_dress->dress_code }}
                                            (ทั้งชุด)
                                            <span style="font-size: 13px; color: red ;"
                                                id="repair_next_dress{{ $item->id }}"></span>
                                            @if ($near_dress_repair != null)
                                                <script>
                                                    var start_date = new Date("{{ $near_dress_repair->start_date }}");
                                                    var now = new Date();
                                                    var day = start_date - now;
                                                    var total = Math.ceil(day / (1000 * 60 * 60 * 24));
                                                    document.getElementById('repair_next_dress{{ $item->id }}').innerHTML =
                                                        'ลูกค้าคนถัดไปจะมารับชุดในอีก ' + total + ' วัน';
                                                </script>
                                            @endif
                                        </p>
                                    @endif
                                @endforeach
                                @foreach ($repair_jewelry as $item)
                                    @php
                                        $near_repair_jewelry = App\Models\Reservationfilters::where(
                                            'jewelry_id',
                                            $item->repair_many_to_one_reservationfilter->jewelry_id,
                                        )
                                            ->whereNot('id', $item->repair_many_to_one_reservationfilter->id)
                                            ->where('status', 'ถูกจอง')
                                            ->where('status_completed', 0)
                                            ->orderByRaw("STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                                            ->first();
                                    @endphp

                                    <p class="text-muted ms-2">
                                        -{{ $item->repair_many_to_one_reservationfilter->jewvationtorefil->jewelry_m_o_typejew->type_jewelry_name }}
                                        {{ $item->repair_many_to_one_reservationfilter->jewvationtorefil->jewelry_m_o_typejew->specific_letter }}{{ $item->repair_many_to_one_reservationfilter->jewvationtorefil->jewelry_code }}
                                        <span style="font-size: 13px; color: red ;"
                                            id="show_near_near_repair_jewelry{{ $item->id }}"></span>
                                    </p>

                                    @if ($near_repair_jewelry)
                                        <script>
                                            var now_near_repair_jewelry = new Date();
                                            var start_date_near_repair_jewelry = new Date('{{ $near_repair_jewelry->start_date }}');
                                            var finish_near_repair_jewelry = Math.ceil((start_date_near_repair_jewelry -
                                                now_near_repair_jewelry) / (1000 * 60 * 60 * 24))
                                            document.getElementById('show_near_near_repair_jewelry{{ $item->id }}').innerHTML =
                                                'ลูกค้าคนถัดไปจะมาในอีก ' + finish_near_repair_jewelry + ' วัน';
                                        </script>
                                    @endif
                                @endforeach
                            @else
                                <p class="text-muted ms-2">ไม่มีรายการที่รอดำเนินงาน</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Fitting Appointments Section -->
        <div class="row ">
            {{-- <div class="col-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <!-- <i class="fas fa-calendar-check text-info me-2"></i> -->
                            <h5 class="card-title mb-0">นัดลองชุดวันนี้ (1)</h5>
                        </div>
                        <div class=" scrollable-contentps-4">
                            <p class="fw-bold mb-1">คุณวิภา สวยเสมอ</p>
                            <p class="text-muted ms-2">- เช่าตัดชุดไทยบรหม</p>
                        </div>
                    </div>
                </div>
            </div> --}}


            







        </div>










    </div>
@endsection
