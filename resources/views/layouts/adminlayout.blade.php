<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ร้านตัดชุดเปลือกไหม</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="main.css">
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
    <script src="main.js"></script> --}}
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Barlow&display=swap');

        body {
            /* font-family: 'Barlow', sans-serif; */
            /* font-family: "Prompt", sans-serif; */
            font-family: "Bai Jamjuree", sans-serif;
        }

        a:hover {
            text-decoration: none;
        }


        .overlay {
            background-color: rgb(0 0 0 / 45%);
            z-index: 99;
        }


        .sidebar .list-group-item {
            border-radius: 0;
            padding: 10px 20px;
        }

        .sidebar .list-group-item.active {
            background-color: #ffffff !important;
            color: black !important;
        }


        .logo-container {
            display: flex;
            justify-content: center;
            /* จัดให้อยู่ตรงกลางแนวนอน */
            align-items: center;
            /* จัดให้อยู่ตรงกลางแนวตั้ง */
            margin-bottom: 10px;
            /* ปรับระยะห่างต่ำสุดตามความเหมาะสม */
            margin-top: 10px;
        }

        nav {
            background-color: #ffffff;
            color: #000000;
            padding: 7mm;
            width: 100%;
        }

        /* เพิ่ม */
        .sidebar {
            overflow-y: auto;
            background-color: #EAD8C0;
        }

        #d1 {
            background-color: #EAD8C0;
            color: #000000;
        }

        #d {
            background-color: #EAD8C0;
            color: #000000;
        }


        /* เปลี่ยนสีปุ่มก่อน (Previous) */
        .carousel-control-prev-icon {
            background-color: #b54343;
            /* เปลี่ยนสีตามที่คุณต้องการ */
            border-radius: 20px;
            padding: 10px;
        }

        /* เปลี่ยนสีปุ่มถัดไป (Next) */
        .carousel-control-next-icon {
            background-color: #000000;
            /* เปลี่ยนสีตามที่คุณต้องการ */
            border-radius: 20px;
            padding: 10px;
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
            /* พื้นหลังสีดำทึบที่เหมาะสม */
            opacity: 0.4 !important;
            /* ปรับค่าความทึบแสงให้อยู่ตรงกลาง */
        }

        .custom-badge {
            background-color: #EB7E52;
            /* สีพื้นหลัง */
            color: white;
            /* สีข้อความ */
            padding: 0.2em 0.45em;
            /* ระยะห่างด้านใน */
            border-radius: 1rem;
            /* ทำมุมให้โค้ง */
            font-size: 0.7em;
            /* ขนาดตัวอักษร */
        }
    </style>
</head>

<body>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>

    {{-- สิ้นสุด --}}

    {{-- ไอคอนเพิ่มมาใหม่ 20/12/2567 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">






    <div id="sidebar-overlay" class="overlay w-100 vh-100 position-fixed d-none"></div>

    @if (Auth::user() && Auth::user()->is_admin == 1)
        <!-- sidebar -->
        <div class="col-md-3 col-lg-2 px-0 position-fixed h-100 shadow sidebar" id="sidebar">
            <h1 class="logo-container">
                <img src="{{ asset('images/logo5.png') }}" alt="logo" width="150" height="150">
            </h1>
            <div class="list-group ">





                <button class="list-group-item list-group-item-action border-0 d-flex align-items-center"
                    data-toggle="collapse" data-target="#ds-collapse" id="d1">
                    <div>
                        <span class="bi bi-card-checklist"></span>
                        <span class="ml-2">ภาพรวม</span>
                    </div>
                    <span class="bi bi-chevron-down small"></span>
                </button>
                <div class="collapse" id="ds-collapse" data-parent="#sidebar">
                    <div class="list-group">
                        <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action border-0 pl-5"
                            id="d">-
                            แดชบอร์ด</a>
                        <a href="{{ route('dashboardpopular') }}"
                            class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                            แดชบอร์ดที่นิยม</a>
                        <a href="{{ route('employee.ordertotal') }}"
                            class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                            รายการออเดอร์ทั้งหมด</a>
                    </div>
                </div>


                <button class="list-group-item list-group-item-action border-0 d-flex align-items-center"
                    data-toggle="collapse" data-target="#dress-collapse" id="d1">
                    <div>
                        <span class="bi bi-card-checklist"></span>
                        <span class="ml-2">จัดการชุด</span>
                    </div>
                    <span class="bi bi-chevron-down small"></span>
                </button>
                <div class="collapse" id="dress-collapse" data-parent="#sidebar">
                    <div class="list-group">
                        <a href="{{ route('admin.dresstotal') }}"
                            class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                            รายการชุด</a>
                        <a href="{{ route('admin.formadddress') }}"
                            class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                            เพิ่มชุดใหม่</a>
                        <a href="{{ route('dresswaitprice') }}"
                            class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                            ชุดที่รอกำหนดราคา</a>
                    </div>
                </div>


                <button class="list-group-item list-group-item-action border-0 d-flex align-items-center"
                    data-toggle="collapse" data-target="#jewry-collapse" id="d1">
                    <div>
                        <span class="bi bi-card-checklist"></span>
                        <span class="ml-2">จัดการเครื่องประดับ</span>
                    </div>
                    <span class="bi bi-chevron-down small"></span>
                </button>
                <div class="collapse" id="jewry-collapse" data-parent="#sidebar">
                    <div class="list-group">
                        <a href="{{ route('admin.jewelrytotal') }}"
                            class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                            รายการเครื่องประดับ</a>
                        <a href="{{ route('admin.formaddjewelry') }}"
                            class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                            เพิ่มเครื่องประดับใหม่</a>
                        <a href="{{ route('admin.managesetjewelry') }}"
                            class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                            จัดเซตเครื่องประดับ</a>
                    </div>
                </div>


                <button class="list-group-item list-group-item-action border-0 d-flex align-items-center"
                    data-toggle="collapse" data-target="#shop-collapse" id="d1">
                    <div>
                        <span class="bi bi-card-checklist"></span>
                        <span class="ml-2">จัดการร้าน</span>
                    </div>
                    <span class="bi bi-chevron-down small"></span>
                </button>
                <div class="collapse" id="shop-collapse" data-parent="#sidebar">
                    <div class="list-group">

                        <a href="{{ route('admin.expense') }}"
                            class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                            บันทึกรายจ่ายของร้าน</a>
                        <a href="{{ route('jewelryproblemcancel') }}"
                            class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                            การจองที่ได้รับผลกระทบ</a>
                        <a href="{{ route('employeetotal') }}"
                            class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                            จัดการพนักงาน</a>
                        <a href="{{ route('register') }}"
                            class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                            เพิ่มพนักงาน</a>

                    </div>
                </div>









                <a href="{{ route('employee.addorder') }}"
                    class="list-group-item list-group-item-action border-0 align-items-center" id="d1">
                    <i class="bi bi-clipboard-plus"></i>
                    <span class="ml-2">เพิ่มออเดอร์</span>
                </a>


                <button class="list-group-item list-group-item-action border-0 d-flex align-items-center"
                    data-toggle="collapse" data-target="#cutdress-collapse" id="d1">
                    <div>
                        <span class="bi bi-scissors"></span>
                        <span class="ml-2">บริการตัดชุด</span>
                    </div>
                    <span class="bi bi-chevron-down small"></span>
                </button>
                <div class="collapse" id="cutdress-collapse" data-parent="#sidebar">
                    <div class="list-group">
                        <a href="{{ route('employee.cutdressadjust') }}"
                            class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                            คิวงานตัด
                            @php
                                $cutdresss = App\Models\Orderdetail::where('type_order', 1)
                                    ->whereNotIn('status_detail', ['ส่งมอบชุดแล้ว', 'ตัดชุดเสร็จสิ้น'])
                                    ->orderByRaw(" STR_TO_DATE(pickup_date,'%Y-%m-%d') asc ")
                                    ->get();
                            @endphp
                            @if ($cutdresss->count() > 0)
                                <span class="badge custom-badge ml-1" style="font-size: 0.8rem;">
                                    {{ $cutdresss->count() }}
                                </span>
                            @endif

                        </a>
                    </div>
                </div>
                <button class="list-group-item list-group-item-action border-0 d-flex align-items-center"
                    data-toggle="collapse" data-target="#rentdress-collapse" id="d1">
                    <div>
                        <i class="fas fa-tshirt"></i> <!-- ไอคอนเสื้อ -->
                        <span class="ml-2">บริการเช่าชุด</span>
                    </div>
                    <span class="bi bi-chevron-down small"></span>
                </button>
                <div class="collapse" id="rentdress-collapse" data-parent="#sidebar">
                    <div class="list-group">
                        <a href="{{ route('employee.dressadjust') }}"
                            class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                            รายการรอรับชุด
                            @php
                                $c_d_p = App\Models\Reservation::where('status_completed', 0)
                                    ->where('status', 'ถูกจอง')
                                    ->get();
                                $index_count_start_dress_pickup = 0;
                                foreach ($c_d_p as $key => $value) {
                                    $orderdetail = App\Models\Orderdetail::where('reservation_id', $value->id)->first();
                                    if ($orderdetail->type_order == 2 || $orderdetail->type_order == 4) {
                                        $index_count_start_dress_pickup += 1;
                                    }
                                }

                            @endphp
                            @if ($index_count_start_dress_pickup > 0)
                                <span class="badge custom-badge ml-1" style="font-size: 0.8rem;">
                                    {{ $index_count_start_dress_pickup }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('employee.listdressreturn') }}"
                            class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                            รายการรอคืนชุด
                            @php
                                $c_d_r = App\Models\Reservation::where('status_completed', 0)
                                    ->where('status', 'กำลังเช่า')
                                    ->get();
                                $count_dress_return = 0;
                                foreach ($c_d_r as $key => $value) {
                                    $orderdetail = App\Models\Orderdetail::where('reservation_id', $value->id)->first();
                                    if ($orderdetail->type_order == 2 || $orderdetail->type_order == 4) {
                                        $count_dress_return += 1;
                                    }
                                }
                            @endphp
                            @if ($count_dress_return > 0)
                                <span class="badge custom-badge ml-1" style="font-size: 0.8rem;">
                                    {{ $count_dress_return }}
                                </span>
                            @endif
                        </a>
                        {{-- <a href="{{ route('employee.clean') }}"
                        class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                        รายการซักชุด
                        @php
                            $cleancountdress = App\Models\Clean::whereIn('clean_status', [
                                'รอดำเนินการ',
                                'กำลังส่งซัก',
                            ])->count();
                        @endphp
                        @if ($cleancountdress != 0)
                            <span class="badge custom-badge ml-1" style="font-size: 0.8rem;">
                                {{ $cleancountdress }}
                            </span>
                        @endif
                    </a> --}}

                        <a href="{{ route('cleanningdress') }}"
                            class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                            รายการซักชุด

                            @php
                                $clean_dress = App\Models\Reservationfilterdress::where('status_completed', 0)
                                    ->whereIn('status', ['รอทำความสะอาด', 'กำลังส่งซัก'])
                                    ->count();

                            @endphp
                            @if ($clean_dress > 0)
                                <span class="badge custom-badge ml-1"
                                    style="font-size: 0.8rem;">{{ $clean_dress }}</span>
                            @endif




                        </a>







                        

                        <a href="{{ route('dressrepair') }}"
                            class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                            รายการซ่อมชุด

                            @php
                            $repair_dress = App\Models\Repair::whereNotNull('reservationfilterdress_id')
                                ->whereIn('repair_status', ['รอดำเนินการ', 'กำลังซ่อม'])
                                ->count();
                        @endphp
                        @if ($repair_dress > 0)
                            <span class="badge custom-badge ml-1"
                                style="font-size: 0.8rem;">{{ $repair_dress }}</span>
                        @endif
                        </a>


                    </div>
                </div>





                <button class="list-group-item list-group-item-action border-0 d-flex align-items-center"
                    data-toggle="collapse" data-target="#jewelry-collapse" id="d1">
                    <div>
                        <span class="fa fa-crown"></span>
                        <span class="ml-2">บริการเช่าเครื่องประดับ</span>
                    </div>
                    <span class="bi bi-chevron-down small"></span>
                </button>
                <div class="collapse" id="jewelry-collapse" data-parent="#sidebar">
                    <div class="list-group">
                        <a href="{{ route('showpickupqueuejewelry') }}"
                            class="list-group-item list-group-item-action border-0 pl-5" id="d">-

                            คิวเช่า
                            @php
                                $c_j_p = App\Models\Reservation::where('status_completed', 0)
                                    ->where('status', 'ถูกจอง')
                                    ->get();
                                $count_jew_pickup = 0;
                                foreach ($c_j_p as $key => $value) {
                                    $orderdetail = App\Models\Orderdetail::where('reservation_id', $value->id)->first();
                                    if ($orderdetail->type_order == 3) {
                                        $count_jew_pickup += 1;
                                    }
                                }
                            @endphp
                            @if ($count_jew_pickup > 0)
                                <span class="badge custom-badge ml-1" style="font-size: 0.8rem;">
                                    {{ $count_jew_pickup }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('showreturnqueuejewelry') }}"
                            class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                            รายการที่รอส่งคืน
                            @php
                                $c_j_r = App\Models\Reservation::where('status_completed', 0)
                                    ->where('status', 'กำลังเช่า')
                                    ->get();
                                $count_jew_return = 0;
                                foreach ($c_j_r as $key => $value) {
                                    $orderdetail = App\Models\Orderdetail::where('reservation_id', $value->id)->first();
                                    if ($orderdetail->type_order == 3) {
                                        $count_jew_return += 1;
                                    }
                                }
                            @endphp
                            @if ($count_jew_return > 0)
                                <span class="badge custom-badge ml-1" style="font-size: 0.8rem;">
                                    {{ $count_jew_return }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('showcleanjewelry') }}"
                            class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                            รายการทำความสะอาด
                            @php
                                $clean_jewelry = App\Models\Reservationfilters::where('status_completed', 0)
                                    ->whereIn('status', ['รอทำความสะอาด', 'กำลังทำความสะอาด'])
                                    ->count();

                            @endphp
                            @if ($clean_jewelry > 0)
                                <span class="badge custom-badge ml-1"
                                    style="font-size: 0.8rem;">{{ $clean_jewelry }}</span>
                            @endif
                        </a>
                        <a href="{{ route('showrepairjewelry') }}"
                            class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                            รายการซ่อม
                            @php
                                $repair_jewelry = App\Models\Repair::whereNotNull('reservationfilter_id')
                                    ->whereIn('repair_status', ['รอดำเนินการ', 'กำลังซ่อม'])
                                    ->count();
                            @endphp
                            @if ($repair_jewelry > 0)
                                <span class="badge custom-badge ml-1"
                                    style="font-size: 0.8rem;">{{ $repair_jewelry }}</span>
                            @endif
                        </a>
                        {{-- <a href="{{ route('jewelryproblemcancel') }}"
                        class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                        การจองที่ได้รับผลกระทบ
                        <span class="badge custom-badge ml-1" style="font-size: 0.8rem;"></span>
                    </a> --}}


                    </div>
                </div>

                <button class="list-group-item list-group-item-action border-0 d-flex align-items-center"
                    data-toggle="collapse" data-target="#rentcut-collapse" id="d1">
                    <div>
                        <span class="bi bi-scissors"></span>
                        <span class="ml-2">บริการเช่าตัดชุด

                        </span>
                    </div>
                    <span class="bi bi-chevron-down small"></span>
                </button>
                <div class="collapse" id="rentcut-collapse" data-parent="#sidebar">
                    <div class="list-group">
                        <a href="{{ route('queuerentcuttotal') }}"
                            class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                            คิวเช่าตัด

                            @php
                                $count_rent_cut = App\Models\Orderdetail::where('type_order', 4)
                                    ->whereIn('status_detail', ['รอดำเนินการตัด', 'เริ่มดำเนินการตัด'])
                                    ->count();
                            @endphp

                            @if ($count_rent_cut > 0)
                                <span class="badge custom-badge ml-1" style="font-size: 0.8rem;">
                                    {{ $count_rent_cut }}
                                </span>
                            @endif


                        </a>
                    </div>
                </div>











            </div>
        </div>
    @elseif(Auth::user() && Auth::user()->is_admin == 0)
        <!-- sidebar -->
        <div class="col-md-3 col-lg-2 px-0 position-fixed h-100 shadow p-1 sidebar" id="sidebar">
            <h1 class="logo-container">
                <img src="{{ asset('images/logo5.png') }}" alt="logo" width="150" height="150">
            </h1>


            <a href="{{ route('employee.homepage') }}"
                class="list-group-item @if (Route::currentRouteName() == 'employee.homepage') active @endif list-group-item-action border-0 align-items-center"
                id="d1">
                <span class="bi bi-house"></span>
                <span class="ml-2">หน้าแรก</span>
            </a>





            <a href="{{ route('employee.ordertotal') }}"
                class="list-group-item @if (Route::currentRouteName() == 'employee.ordertotal') active @endif list-group-item-action border-0 align-items-center"
                id="d1">
                <span class="bi bi-clipboard-data"></span>

                <span class="ml-2">รายการออเดอร์ทั้งหมด</span>
            </a>


            <a href="{{ route('admin.dresstotal') }}"
                class="list-group-item @if (Route::currentRouteName() == 'admin.dresstotal') active @endif list-group-item-action border-0 align-items-center"
                id="d1">
                <span class="bi bi-clipboard-data"></span>
                <span class="ml-2">รายการชุด</span>
            </a>


            <a href="{{ route('admin.jewelrytotal') }}"
                class="list-group-item @if (Route::currentRouteName() == 'admin.jewelrytotal') active @endif list-group-item-action border-0 align-items-center"
                id="d1">
                <span class="bi bi-list-ul"></span>
                <span class="ml-2">รายการเครื่องประดับ</span>
            </a>




            <a href="{{ route('employee.addorder') }}"
                class="list-group-item @if (Route::currentRouteName() == 'employee.addorder') active @endif list-group-item-action border-0 align-items-center"
                id="d1">
                <span class="bi bi-plus-circle"></span>
                <span class="ml-2">เพิ่มออเดอร์</span>
            </a>
            <a href="{{ route('admin.expense') }}"
                class="list-group-item @if (Route::currentRouteName() == 'admin.expense') active @endif list-group-item-action border-0 align-items-center"
                id="d1">
                <span class="bi bi-plus-circle"></span>
                <span class="ml-2">บันทึกรายจ่าย</span>
            </a>

            <a href="{{ route('jewelryproblemcancel') }}"
                class="list-group-item @if (Route::currentRouteName() == 'jewelryproblemcancel') active @endif list-group-item-action border-0 align-items-center"
                id="d1">
                <span class="bi bi-plus-circle"></span>
                <span class="ml-2">การจองที่ได้รับผลกระทบ</span>
            </a>





            <button class="list-group-item list-group-item-action border-0 d-flex align-items-center"
                data-toggle="collapse" data-target="#cutdress-collapse" id="d1">
                <div>
                    <span class="bi bi-scissors"></span>
                    <span class="ml-2">บริการตัดชุด</span>
                </div>
                <span class="bi bi-chevron-down small"></span>
            </button>
            <div class="collapse" id="cutdress-collapse" data-parent="#sidebar">
                <div class="list-group">
                    <a href="{{ route('employee.cutdressadjust') }}"
                        class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                        คิวงานตัด
                        @php
                            $cutdresss = App\Models\Orderdetail::where('type_order', 1)
                                ->whereNotIn('status_detail', ['ส่งมอบชุดแล้ว', 'ตัดชุดเสร็จสิ้น'])
                                ->orderByRaw(" STR_TO_DATE(pickup_date,'%Y-%m-%d') asc ")
                                ->get();
                        @endphp
                        @if ($cutdresss->count() > 0)
                            <span class="badge custom-badge ml-1" style="font-size: 0.8rem;">
                                {{ $cutdresss->count() }}
                            </span>
                        @endif

                    </a>
                </div>
            </div>
            <button class="list-group-item list-group-item-action border-0 d-flex align-items-center"
                data-toggle="collapse" data-target="#rentdress-collapse" id="d1">
                <div>
                    <i class="fas fa-tshirt"></i> <!-- ไอคอนเสื้อ -->
                    <span class="ml-2">บริการเช่าชุด</span>
                </div>
                <span class="bi bi-chevron-down small"></span>
            </button>
            <div class="collapse" id="rentdress-collapse" data-parent="#sidebar">
                <div class="list-group">
                    <a href="{{ route('employee.dressadjust') }}"
                        class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                        รายการรอรับชุด
                        @php
                            $c_d_p = App\Models\Reservation::where('status_completed', 0)
                                ->where('status', 'ถูกจอง')
                                ->get();
                            $index_count_start_dress_pickup = 0;
                            foreach ($c_d_p as $key => $value) {
                                $orderdetail = App\Models\Orderdetail::where('reservation_id', $value->id)->first();
                                if ($orderdetail->type_order == 2 || $orderdetail->type_order == 4) {
                                    $index_count_start_dress_pickup += 1;
                                }
                            }

                        @endphp
                        @if ($index_count_start_dress_pickup > 0)
                            <span class="badge custom-badge ml-1" style="font-size: 0.8rem;">
                                {{ $index_count_start_dress_pickup }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('employee.listdressreturn') }}"
                        class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                        รายการรอคืนชุด
                        @php
                            $c_d_r = App\Models\Reservation::where('status_completed', 0)
                                ->where('status', 'กำลังเช่า')
                                ->get();
                            $count_dress_return = 0;
                            foreach ($c_d_r as $key => $value) {
                                $orderdetail = App\Models\Orderdetail::where('reservation_id', $value->id)->first();
                                if ($orderdetail->type_order == 2 || $orderdetail->type_order == 4) {
                                    $count_dress_return += 1;
                                }
                            }
                        @endphp
                        @if ($count_dress_return > 0)
                            <span class="badge custom-badge ml-1" style="font-size: 0.8rem;">
                                {{ $count_dress_return }}
                            </span>
                        @endif
                    </a>


                    <a href="{{ route('cleanningdress') }}"
                        class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                        รายการซักชุด
                        @php
                            $clean_dress = App\Models\Reservationfilterdress::where('status_completed', 0)
                                ->whereIn('status', ['รอทำความสะอาด', 'กำลังส่งซัก'])
                                ->count();

                        @endphp
                        @if ($clean_dress > 0)
                            <span class="badge custom-badge ml-1"
                                style="font-size: 0.8rem;">{{ $clean_dress }}</span>
                        @endif





                    </a>




                    <a href="{{ route('dressrepair') }}" class="list-group-item list-group-item-action border-0 pl-5"
                        id="d">-
                        รายการซ่อมชุด


                        @php
                            $repair_dress = App\Models\Repair::whereNotNull('reservationfilterdress_id')
                                ->whereIn('repair_status', ['รอดำเนินการ', 'กำลังซ่อม'])
                                ->count();
                        @endphp
                        @if ($repair_dress > 0)
                            <span class="badge custom-badge ml-1"
                                style="font-size: 0.8rem;">{{ $repair_dress }}</span>
                        @endif



                    </a>


                </div>
            </div>





            <button class="list-group-item list-group-item-action border-0 d-flex align-items-center"
                data-toggle="collapse" data-target="#jewelry-collapse" id="d1">
                <div>
                    <span class="fa fa-crown"></span>
                    <span class="ml-2">บริการเครื่องประดับ</span>
                </div>
                <span class="bi bi-chevron-down small"></span>
            </button>
            <div class="collapse" id="jewelry-collapse" data-parent="#sidebar">
                <div class="list-group">
                    <a href="{{ route('showpickupqueuejewelry') }}"
                        class="list-group-item list-group-item-action border-0 pl-5" id="d">-

                        คิวเช่า
                        @php
                            $c_j_p = App\Models\Reservation::where('status_completed', 0)
                                ->where('status', 'ถูกจอง')
                                ->get();
                            $count_jew_pickup = 0;
                            foreach ($c_j_p as $key => $value) {
                                $orderdetail = App\Models\Orderdetail::where('reservation_id', $value->id)->first();
                                if ($orderdetail->type_order == 3) {
                                    $count_jew_pickup += 1;
                                }
                            }
                        @endphp
                        @if ($count_jew_pickup > 0)
                            <span class="badge custom-badge ml-1" style="font-size: 0.8rem;">
                                {{ $count_jew_pickup }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('showreturnqueuejewelry') }}"
                        class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                        รายการที่รอส่งคืน
                        @php
                            $c_j_r = App\Models\Reservation::where('status_completed', 0)
                                ->where('status', 'กำลังเช่า')
                                ->get();
                            $count_jew_return = 0;
                            foreach ($c_j_r as $key => $value) {
                                $orderdetail = App\Models\Orderdetail::where('reservation_id', $value->id)->first();
                                if ($orderdetail->type_order == 3) {
                                    $count_jew_return += 1;
                                }
                            }
                        @endphp
                        @if ($count_jew_return > 0)
                            <span class="badge custom-badge ml-1" style="font-size: 0.8rem;">
                                {{ $count_jew_return }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('showcleanjewelry') }}"
                        class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                        รายการทำความสะอาด
                        @php
                            $clean_jewelry = App\Models\Reservationfilters::where('status_completed', 0)
                                ->whereIn('status', ['รอทำความสะอาด', 'กำลังทำความสะอาด'])
                                ->count();

                        @endphp
                        @if ($clean_jewelry > 0)
                            <span class="badge custom-badge ml-1"
                                style="font-size: 0.8rem;">{{ $clean_jewelry }}</span>
                        @endif
                    </a>
                    <a href="{{ route('showrepairjewelry') }}"
                        class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                        รายการซ่อม
                        @php
                            $repair_jewelry = App\Models\Repair::whereNotNull('reservationfilter_id')
                                ->whereIn('repair_status', ['รอดำเนินการ', 'กำลังซ่อม'])
                                ->count();
                        @endphp
                        @if ($repair_jewelry > 0)
                            <span class="badge custom-badge ml-1"
                                style="font-size: 0.8rem;">{{ $repair_jewelry }}</span>
                        @endif
                    </a>
                    {{-- <a href="{{ route('jewelryproblemcancel') }}"
                        class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                        การจองที่ได้รับผลกระทบ
                        <span class="badge custom-badge ml-1" style="font-size: 0.8rem;"></span>
                    </a> --}}


                </div>
            </div>

            <button class="list-group-item list-group-item-action border-0 d-flex align-items-center"
                data-toggle="collapse" data-target="#rentcut-collapse" id="d1">
                <div>
                    <span class="bi bi-scissors"></span>
                    <span class="ml-2">บริการเช่าตัดชุด

                    </span>
                </div>
                <span class="bi bi-chevron-down small"></span>
            </button>
            <div class="collapse" id="rentcut-collapse" data-parent="#sidebar">
                <div class="list-group">
                    <a href="{{ route('queuerentcuttotal') }}"
                        class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                        คิวเช่าตัด

                        @php
                            $count_rent_cut = App\Models\Orderdetail::where('type_order', 4)
                                ->whereIn('status_detail', ['รอดำเนินการตัด', 'เริ่มดำเนินการตัด'])
                                ->count();
                        @endphp

                        @if ($count_rent_cut > 0)
                            <span class="badge custom-badge ml-1" style="font-size: 0.8rem;">
                                {{ $count_rent_cut }}
                            </span>
                        @endif


                    </a>
                </div>
            </div>





        </div>
    @endif
    <div class="col-md-9 col-lg-10 ml-md-auto px-0 ms-md-auto">
        <!-- top nav -->
        {{-- แอดมิน --}}
        <nav class="d-flex shadow">
            <button class="btn py-0 d-lg-none" id="open-sidebar">
                <span class="bi bi-list text-primary h3"></span>
            </button>
            <div class="ml-auto d-flex">
                <div class="dropdown">
                    <a class="btn py-0 d-flex align-items-center" id="cart-dropdown"
                        href="{{ route('employee.cart') }}" style="color: #000000">
                        <span class="bi bi-cart text-dark h4"></span>
                        <span class=" mb-2 small text-dark">


                            @php
                                $count_cart = App\Models\Order::where('user_id', Auth::user()->id)
                                    ->where('order_status', 0)
                                    ->value('total_quantity');
                            @endphp
                            @if ($count_cart != 0)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill custom-badge">
                                    {{ $count_cart }}
                                </span>
                            @endif







                    </a>
                </div>
                <div class="d-flex align-items-center ml-3">
                    @if (Auth::user()->is_admin == 0)
                        <span class="text-dark h5" style="font-size: 16px;">
                            คุณ{{ Auth::user()->name }} {{ Auth::user()->lname }}
                        </span>
                    @else
                        <span class="text-dark h5" style="font-size: 16px;">
                            Admin
                        </span>
                    @endif
                </div>
                <div class="dropdown ml-2">
                    <button class="btn py-0 d-flex" id="logout-dropdown" data-toggle="dropdown"
                        aria-expanded="false">
                        <span class="bi bi-person text-dark h4"></span>
                        <span class="bi bi-chevron-down ml-1 mb-2 small text-dark"></span>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right border-0 shadow-sm"
                        aria-labelledby="logout-dropdown">
                        <li><a class="dropdown-item" href="{{ route('admin.adminprofile') }}">profile</a></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>
                        </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>

            </div>
        </nav>
        @yield('content')
        </main>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
    <script src="main.js"></script>
    <script>
        $(document).ready(function() {
            $('.list-group-item').click(function() {
                $('.list-group-item').removeClass('active');
                $(this).addClass('active');
            });
        });
    </script>
</body>

</html>
