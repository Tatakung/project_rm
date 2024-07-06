<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ร้านตัดชุดเปลือกไหม</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="main.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Barlow&display=swap');

        body {
            /* font-family: 'Barlow', sans-serif; */
            font-family: "Prompt", sans-serif;
        }

        a:hover {
            text-decoration: none;
        }

        .border-left {
            border-left: 2px solid var(--primary) !important;
        }


        .sidebar {
            top: 0;
            left: 0;
            z-index: 100;
            overflow-y: auto;
            background-color: #EEEEEE;
        }


        .overlay {
            background-color: rgb(0 0 0 / 45%);
            z-index: 99;
        }

        /* sidebar for small screens */
        @media screen and (max-width: 767px) {

            .sidebar {
                /* width: 256px;
    height: 100vh;
    display: flex;
    flex-direction: column;
    background-color: #FFFFFF;
    padding: 24px;
    border-radius: 30px; */
                max-width: 18rem;
                transform: translateX(-100%);
                transition: transform 0.4s ease-out;
                border-radius: 30px;
            }

            .sidebar.active {
                transform: translateX(0);
            }

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
            background-color: #EEEEEE;
            color: #FFFFFF;
            padding: 7mm;
            width: 100%;
        }

        /* เพิ่ม */
        .list-group-item.active {
            background-color: #868686;
            color: #f4e8e8;
        }


        #test {
            background-color: #868686;
            color: #f4e8e8;

        }

        #d {
            background-color: #868686;
            color: #f4e8e8;
        }

        #d1 {
            background-color: #868686;
            color: #f4e8e8;
        }

        /* เปลี่ยนสีปุ่มก่อน (Previous) */
        .carousel-control-prev-icon {
            background-color: #000000;
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

    <div id="sidebar-overlay" class="overlay w-100 vh-100 position-fixed d-none"></div>

    @if (Auth::user() && Auth::user()->is_admin == 1)
        <!-- sidebar -->
        <div class="col-md-3 col-lg-2 px-0 position-fixed h-100 shadow-sm sidebar" id="sidebar">
            <h1 class="logo-container">
                <img src="{{ asset('images/logo123456.jpg') }}" alt="logo" width="70" height="70">
            </h1>
            <div class="list-group rounded-0">
                <a href="#"
                    class="list-group-item list-group-item-action active border-0 d-flex align-items-center">
                    <span class="bi bi-border-all"></span>
                    <span class="ml-2 ">Today</span>
                </a>

                <button
                    class="list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center"
                    data-toggle="collapse" data-target="#sale-collapse" id="test">
                    <div>
                        <span class="bi bi-cart-dash"></span>
                        <span class="ml-2">Dashboard</span>
                    </div>
                    <span class="bi bi-chevron-down small"></span>
                </button>
                <div class="collapse" id="sale-collapse" data-parent="#sidebar">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action border-0 pl-5"
                            id="d">-สำหรับตัดชุด</a>
                        <a href="#" class="list-group-item list-group-item-action border-0 pl-5"
                            id="d">-สำหรับเช่าชุด</a>
                        <a href="#" class="list-group-item list-group-item-action border-0 pl-5"
                            id="d">-สำหรับเช่าเครื่องประดับ</a>
                        <a href="#" class="list-group-item list-group-item-action border-0 pl-5"
                            id="d">-สำหรับเช่าตัดชุด</a>
                    </div>
                </div>


                <a href="{{ route('admin.dresstotal') }}"
                    class="list-group-item list-group-item-action border-0 align-items-center " id="d1">
                    <span class="bi bi-box "></span>
                    <span class="ml-2 nav-pills">จัดการชุด</span>
                </a>


                <a href="{{ route('admin.jewelrytotal') }}"
                    class="list-group-item list-group-item-action border-0 align-items-center" id="d1">
                    <span class="bi bi-box"></span>
                    <span class="ml-2">จัดการเครื่องประดับ</span>
                </a>

                <a href="{{ route('employeetotal') }}"
                    class="list-group-item list-group-item-action border-0 align-items-center" id="d1">

                    <span class="bi bi-box"></span>
                    <span class="ml-2">จัดการพนักงาน</span>
                </a>



                <a href="{{ route('admin.expense') }}"
                    class="list-group-item list-group-item-action border-0 align-items-center" id="d1">
                    <span class="bi bi-box"></span>
                    <span class="ml-2">ค่าใช้จ่าย</span>
                </a>


            </div>
        </div>
    @elseif(Auth::user() && Auth::user()->is_admin == 0)
        <!-- sidebar -->
        <div class="col-md-3 col-lg-2 px-0 position-fixed h-100 shadow-sm sidebar" id="sidebar">
            <h1 class="logo-container">
                <img src="{{ asset('images/logo123456.jpg') }}" alt="logo" width="70" height="70">
            </h1>
            <div class="list-group rounded-0">
                <a href="#"
                    class="list-group-item list-group-item-action active border-0 d-flex align-items-center">
                    <span class="bi bi-border-all"></span>
                    <span class="ml-2 ">Today</span>
                </a>

                <button
                    class="list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center"
                    data-toggle="collapse" data-target="#sale-collapse" id="test">
                    <div>
                        <span class="bi bi-cart-dash"></span>
                        <span class="ml-2">Dashboard</span>
                    </div>
                    <span class="bi bi-chevron-down small"></span>
                </button>
                <div class="collapse" id="sale-collapse" data-parent="#sidebar">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action border-0 pl-5"
                            id="d">-สำหรับตัดชุด</a>
                        <a href="#" class="list-group-item list-group-item-action border-0 pl-5"
                            id="d">-สำหรับเช่าชุด</a>
                        <a href="#" class="list-group-item list-group-item-action border-0 pl-5"
                            id="d">-สำหรับเช่าเครื่องประดับ</a>
                        <a href="#" class="list-group-item list-group-item-action border-0 pl-5"
                            id="d">-สำหรับเช่าตัดชุด</a>
                    </div>
                </div>


                <a href="" class="list-group-item list-group-item-action border-0 align-items-center "
                    id="d1">
                    <span class="bi bi-box "></span>
                    <span class="ml-2 nav-pills">รายการออเดอร์ทั้งหมด</span>
                </a>


                <a href="{{route('employee.addorder')}}" class="list-group-item list-group-item-action border-0 align-items-center"
                    id="d1">
                    <span class="bi bi-box"></span>
                    <span class="ml-2">เพิ่มออเดอร์</span>
                </a>

                <a href="" class="list-group-item list-group-item-action border-0 align-items-center"
                    id="d1">

                    <span class="bi bi-box"></span>
                    <span class="ml-2">-</span>
                </a>



                <a href="" class="list-group-item list-group-item-action border-0 align-items-center"
                    id="d1">
                    <span class="bi bi-box"></span>
                    <span class="ml-2">-</span>
                </a>


            </div>
        </div>
    @endif




    <div class="col-md-9 col-lg-10 ml-md-auto px-0 ms-md-auto">
        <!-- top nav -->

        {{-- แอดมิน --}}
        <nav class="d-flex shadow-sm">

            <!-- close sidebar -->
            <button class="btn py-0 d-lg-none" id="open-sidebar">
                <span class="bi bi-list text-primary h3"></span>
            </button>

            @if(Auth::user() && Auth::user()->is_admin == 0 )
            <!-- ปุ่มตะกร้า -->
            <button class="btn py-0 ml-auto" id="cart-button">
                <span class="bi bi-cart text-primary h4">
                    <a href="{{route('employee.cart')}}">ตะกร้า {{ App\Models\Order::where('user_id',Auth::user()->id)
                        ->where('order_status',0)
                        ->value('total_quantity') ?? 0 }}</a>
                </span>
            </button>
            @endif


            <div class="dropdown ml-auto">
                <button class="btn py-0 d-flex align-items-center" id="logout-dropdown" data-toggle="dropdown"
                    aria-expanded="false">
                    <span class="bi bi-person text-primary h4"></span>
                    <span class="bi bi-chevron-down ml-1 mb-2 small text-white"></span>
                </button>

                <div class="dropdown-menu dropdown-menu-right border-0 shadow-sm" aria-labelledby="logout-dropdown">

                    {{-- จัดการโปรไฟล์ --}}
                    <li><a class="dropdown-item" href="{{ route('admin.adminprofile') }}">profile</a></li>

                    {{-- ออกจากระบบ --}}
                    <li><a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                  document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a></li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>



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
        $(document).ready(() => {

            $('#open-sidebar').click(() => {

                // add class active on #sidebar
                $('#sidebar').addClass('active');

                // show sidebar overlay
                $('#sidebar-overlay').removeClass('d-none');

            });


            $('#sidebar-overlay').click(function() {

                // add class active on #sidebar
                $('#sidebar').removeClass('active');

                // show sidebar overlay
                $(this).addClass('d-none');

            });

        });
    </script>
</body>

</html>
