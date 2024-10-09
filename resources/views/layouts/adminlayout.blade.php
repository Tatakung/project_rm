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
            background-color: #EAD8C0;
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
    </style>




</head>

<body>
    {{-- เพิ่มเข้ามาใหม่ล่าสุดของการเลือกวันที่ --}}
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>

    {{-- สิ้นสุด --}}






    <div id="sidebar-overlay" class="overlay w-100 vh-100 position-fixed d-none"></div>

    @if (Auth::user() && Auth::user()->is_admin == 1)
        <!-- sidebar -->
        <div class="col-md-3 col-lg-2 px-0 position-fixed h-100 shadow-sm sidebar" id="sidebar">
            <h1 class="logo-container">
                <img src="{{ asset('images/logo5.png') }}" alt="logo" width="150" height="150">
            </h1>
            <div class="list-group ">
                {{-- <a href="" class="list-group-item list-group-item-action border-0 d-flex align-items-center"
                    id="d1">
                    <i class="bi bi-calendar-week"></i>
                    <span class="ml-2 ">ปฏิทินการทำงาน</span>
                </a> --}}

                <button class="list-group-item list-group-item-action border-0 d-flex align-items-center"
                    data-toggle="collapse" data-target="#sale-collapse" id="d1">
                    <div>
                        <span class="bi bi-cart-dash"></span>
                        <span class="ml-2">Dashboard</span>
                    </div>
                    <span class="bi bi-chevron-down small"></span>
                </button>
                <div class="collapse" id="sale-collapse" data-parent="#sidebar">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                            สำหรับตัดชุด</a>
                        <a href="#" class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                            สำหรับเช่าชุด</a>
                        <a href="#" class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                            สำหรับเช่าเครื่องประดับ</a>
                        <a href="#" class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                            สำหรับเช่าตัดชุด</a>
                    </div>
                </div>

                <button class="list-group-item list-group-item-action border-0 d-flex align-items-center"
                    data-toggle="collapse" data-target="#salee-collapse" id="d1">
                    <div>
                        <span class="bi bi-cart-dash"></span>
                        <span class="ml-2">การจัดการ</span>
                    </div>
                    <span class="bi bi-chevron-down small"></span>
                </button>
                <div class="collapse" id="salee-collapse" data-parent="#sidebar">
                    <div class="list-group">
                        <a href="{{ route('admin.formadddress') }}"
                            class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                            เพิ่มชุดใหม่</a>
                        <a href="{{ route('admin.dresstotal') }}"
                            class="list-group-item list-group-item-action border-0 pl-5" id="d">-
                            รายการชุด</a>

                    </div>
                </div>


                {{-- <a href="{{ route('admin.dresstotal') }}"
                    class="list-group-item @if (Route::currentRouteName() == 'admin.dresstotal') active @endif list-group-item-action border-0 align-items-center"
                    id="d1">
                    <i class="bi bi-kanban"></i>
                    <span class="ml-2 nav-pills">จัดการชุด</span>
                </a> --}}


                {{-- <a href="{{ route('admin.jewelrytotal') }}"
            class="list-group-item list-group-item-action border-0 align-items-center" id="d1">
            <span class="bi bi-box"></span>
            <span class="ml-2">จัดการเครื่องประดับ</span>
            </a> --}}

                <a href="{{ route('employeetotal') }}"
                    class="list-group-item @if (Route::currentRouteName() == 'employeetotal') active @endif list-group-item-action border-0 align-items-center"
                    id="d1">

                    <i class="bi bi-person-circle"></i>
                    <span class="ml-2">จัดการพนักงาน</span>
                </a>



                <a href="{{ route('admin.expense') }}"
                    class="list-group-item @if (Route::currentRouteName() == 'admin.expense') active @endif list-group-item-action border-0 align-items-center"
                    id="d1">
                    <i class="bi bi-cash-coin"></i>
                    <span class="ml-2">ค่าใช้จ่าย</span>
                </a>

                <a href="{{ route('admin.dresslist') }}"
                    class="list-group-item list-group-item-action border-0 align-items-center" id="d1">
                    <i class="bi bi-shirt"></i>
                    <span class="ml-2">รายการชุด</span>
                </a>

                <a href="{{ route('employee.addorder') }}"
                    class="list-group-item list-group-item-action border-0 align-items-center" id="d1">
                    <i class="bi bi-shirt"></i>
                    <span class="ml-2">เพิ่มออเดอร์</span>
                </a>


            </div>
        </div>
    @elseif(Auth::user() && Auth::user()->is_admin == 0)
        <!-- sidebar -->
        <div class="col-md-3 col-lg-2 px-0 position-fixed h-100 shadow p-1 sidebar" id="sidebar">
            <h1 class="logo-container">
                <img src="{{ asset('images/logo5.png') }}" alt="logo" width="150" height="150">
            </h1>
            {{-- <a href="{{ route('employee.calendar') }}"
                class="list-group-item list-group-item-action border-0 d-flex align-items-center" id="d1">
                <i class="bi bi-calendar-week"></i>
                <span class="ml-2 ">ปฏิทินการทำงาน</span>
            </a> --}}

            <!-- <div class="list-group rounded-0">
            <a href="" class="list-group-item border-0 align-items-center" id="d1">
                <span class="bi bi-speedometer2"></span>
                <span class="ml-2">Dashboard</span>
            </a> -->

            <a href="{{ route('employee.ordertotal') }}"
                class="list-group-item @if (Route::currentRouteName() == 'employee.ordertotal') active @endif list-group-item-action border-0 align-items-center"
                id="d1">
                <span class="bi bi-list-ul"></span>
                <span class="ml-2">รายการออเดอร์ทั้งหมด</span>
            </a>


            <a href="{{ route('admin.dresstotal') }}"
                class="list-group-item list-group-item-action border-0 align-items-center"
                id="d1">
                <span class="bi bi-list-ul"></span>
                <span class="ml-2">รายการชุด</span>
            </a>




            <a href="{{ route('employee.addorder') }}"
                class="list-group-item @if (Route::currentRouteName() == 'employee.addorder') active @endif list-group-item-action border-0 align-items-center"
                id="d1">
                <span class="bi bi-plus-circle"></span>
                <span class="ml-2">เพิ่มออเดอร์</span>
            </a>

            {{-- <a href="{{ route('employee.reservedress') }}"
            class="list-group-item list-group-item-action border-0 align-items-center" id="d1">
            <span class="bi bi-calendar-check"></span>
            <span class="ml-2">จัดการการจอง</span>
            </a> --}}

            {{-- <a href="" class="list-group-item list-group-item-action border-0 align-items-center"
                    id="d1">
                    <span class="bi bi-arrow-return-left"></span>
                    <span class="ml-2">จัดการชุดที่คืนแล้ว</span>
                </a> --}}
            <a href="{{ route('employee.cutdressadjust') }}"
                class="list-group-item list-group-item-action border-0 align-items-center" id="d1">
                <span class="bi bi-scissors"></span>
                <span class="ml-2">จัดการคิวงานตัดชุด
                    <span class="badge badge-danger ml-1" style="font-size: 0.8rem;">
                        @php
                            $cutdresss = App\Models\Orderdetail::where('type_order', 1)
                                ->whereNotIn('status_detail', ['ส่งมอบชุดแล้ว', 'ตัดชุดเสร็จสิ้น'])
                                ->orderByRaw(" STR_TO_DATE(pickup_date,'%Y-%m-%d') asc ")
                                ->get();
                        @endphp
                        {{ $cutdresss->count() }}
                    </span>
                </span>
            </a>


            <a href="{{ route('employee.dressadjust') }}"
                class="list-group-item list-group-item-action border-0 align-items-center" id="d1">
                <span class="bi bi-calendar"></span>
                <span class="ml-2">จัดการคิวงานเช่าชุด
                    <span class="badge badge-danger ml-1" style="font-size: 0.8rem;">
                        @php
                            $count_reservations = App\Models\Reservation::where('status_completed', 0)
                                ->where('status', 'ถูกจอง')
                                ->orderByRaw("STR_TO_DATE(start_date, '%Y-%m-%d') asc")
                                ->get();
                        @endphp
                        {{ $count_reservations->count() }}
                    </span>
                </span>
            </a>





            <a href="{{ route('employee.listdressreturn') }}"
                class="list-group-item  list-group-item-action border-0 align-items-center" id="d1">
                <!-- Replace 'bi-box-arrow-up' with your desired Bootstrap icon -->
                <span class="bi bi-box-arrow-up"></span>
                <span class="ml-2">รายการชุดที่รอส่งคืน
                    <span class="badge badge-danger ml-1" style="font-size: 0.8rem;">
                        @php
                            $listdressreturns = App\Models\Reservation::where('status_completed', 0)
                                ->orderByRaw("STR_TO_DATE(end_date,'%Y-%m-%d') asc")
                                ->where('status', 'กำลังเช่า')
                                ->get();
                        @endphp
                        {{ $listdressreturns->count() }}
                    </span>
                </span>
            </a>
            <a href="{{ route('employee.clean') }}"
                class="list-group-item @if (Route::currentRouteName() == 'employee.clean') active @endif list-group-item-action border-0 align-items-center"
                id="d1">
                <span class="bi bi-water"></span>
                <span class="ml-2">รายการชุดซัก
                    <span class="badge badge-danger ml-1" style="font-size: 0.8rem;">
                        @php
                            $cleancount = App\Models\Clean::whereIn('clean_status', [
                                'รอดำเนินการ',
                                'กำลังส่งซัก',
                            ])->count();
                        @endphp
                        {{ $cleancount }}
                    </span>
                </span>

            </a>

            <a href="{{ route('employee.repair') }}"
                class="list-group-item @if (Route::currentRouteName() == 'employee.repair') active @endif list-group-item-action border-0 align-items-center"
                id="d1">
                <span class="bi bi-tools"></span>
                <span class="ml-2">รายการซ่อมชุด
                    <span class="badge badge-danger ml-1" style="font-size: 0.8rem;">
                        @php
                            $repaircount = App\Models\Repair::whereIn('repair_status', [
                                'รอดำเนินการ',
                                'กำลังซ่อม',
                            ])->count();
                        @endphp
                        {{ $repaircount }}
                    </span>
                </span>
            </a>

            {{-- <a href=""
                    class="list-group-item list-group-item-action border-0 align-items-center" id="d1">
                    <span class="bi bi-eye"></span>
                    <span class="ml-2">ตรวจสอบสถานะชุด</span>
                </a> --}}

            {{-- <a href="" class="list-group-item list-group-item-action border-0 align-items-center"
                    id="d1">
                    <span class="bi bi-exclamation-triangle"></span>
                    <span class="ml-2">รายงานปัญหา</span>
                </a> --}}

            ' {{-- <a href="" class="list-group-item @if (Route::currentRouteName() == 'employee.typerentdress') active @endif list-group-item-action border-0 align-items-center" id="d1">
                <span class="bi bi-clock-history"></span>
                <span class="ml-2">ประวัติการทำงาน</span>
            </a>' --}}
        </div>
        </div>
    @endif



    <div class="col-md-9 col-lg-10 ml-md-auto px-0 ms-md-auto">
        <!-- top nav -->
        {{-- แอดมิน --}}
        <nav class="d-flex shadow-sm">

            <button class="btn py-0 d-lg-none" id="open-sidebar">
                <span class="bi bi-list text-primary h3"></span>
            </button>

            <div class="ml-auto d-flex">
                <div class="d-flex align-items-center ml-3">
                    @if (Auth::user()->is_admin == 0)
                        <span class="text-dark h5" style="font-size: 16px;">
                            ผู้ใช้งาน : คุณ{{ Auth::user()->name }} {{ Auth::user()->lname }}
                        </span>
                    @else
                        <span class="text-dark h5" style="font-size: 16px;">
                            ผู้ใช้งาน : Admin
                        </span>
                    @endif
                </div>
                <div class="dropdown">
                    <a class="btn py-0 d-flex align-items-center" id="cart-dropdown"
                        href="{{ route('employee.cart') }}" style="color: #000000">
                        <span class="bi bi-cart text-dark h4"></span>
                        <span class=" mb-2 small text-dark">
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ App\Models\Order::where('user_id', Auth::user()->id)->where('order_status', 0)->value('total_quantity') ?? 0 }}
                            </span>
                    </a>
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
