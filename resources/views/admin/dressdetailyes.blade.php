@extends('layouts.adminlayout')
@section('content')
    <style>
        .modal-body {
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-header {
            background-color: #ffffff;
            font-weight: bold;
        }

        .custom-modal-body {
            background-color: #28a745;
            /* สีเขียวเข้ม */
            color: #fff;
            /* ข้อความสีขาว */
            padding: 20px;
            /* ระยะห่างภายใน */
            border-radius: 5px;
            /* ขอบโค้งมน */
            text-align: center;
            /* จัดข้อความให้อยู่ตรงกลาง */
        }
    </style>

    <ol class="breadcrumb" style="background-color: transparent;">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dresstotal') }}" style="color: black ; ">จัดการชุด</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.typedress', ['id' => $datadress->type_dress_id]) }}"
                style="color: black ;">ประเภท{{ $name_type }}</a>
        </li>
        <li class="breadcrumb-item active">
            รายละเอียดของหมายเลขชุด{{ $datadress->dress_code_new }}{{ $datadress->dress_code }}</li>

    </ol>
    <div class="container">
        <div class="row">
            <div class="col">
                <h2 class="py-4" style="text-align: start">รายละเอียดของหมายเลขชุด
                    {{ $datadress->dress_code_new }}{{ $datadress->dress_code }}</h2>
            </div>
        </div>




        <div class="card mb-4 shadow bg-body rounded">


            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="dress-tab" data-toggle="tab" href="#dress" role="tab"
                        aria-controls="dress" aria-selected="true">ข้อมูลชุด</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="shirt-tab" data-toggle="tab" href="#shirt" role="tab" aria-controls="shirt"
                        aria-selected="false">ข้อมูลเสื้อ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pants-tab" data-toggle="tab" href="#pants" role="tab" aria-controls="pants"
                        aria-selected="false">ข้อมูลผ้าถุง</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">

                <!-- ข้อมูลชุด -->
                <div class="tab-pane fade show active" id="dress" role="tabpanel" aria-labelledby="dress-tab">
                    <div class="card-header">
                        <i class="bi bi-info-circle"></i> รายละเอียดชุด


                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="d-flex">
                                @foreach ($imagedata as $image)
                                    <div class="col-md-4">
                                        <img src="{{ asset('storage/' . $image->dress_image) }}" alt=""
                                            style="max-height: 350px; width: auto;">
                                    </div>
                                @endforeach
                            </div>

                            <div class="col-md-4">
                                <p><strong>ประเภทชุด:</strong> {{ $name_type }}</p>
                                <!-- <p><strong>หมายเลขชุด:</strong> {{ $datadress->dress_code_new }}{{ $datadress->dress_code }}
                                                                                                                                                                                                                                                                </p> -->
                                {{-- <p><strong>สถานะชุด:</strong> <span
                                        @if ($datadress->dress_status == 'พร้อมให้เช่า') style="color: green;" @else style="color: red;" @endif>
                                        {{ $datadress->dress_status }}</span></p> --}}


                                {{-- <p><strong>สถานะปัจจุบันของชุด</strong></p>
                                <ul>
                                    <li>เสื้อ : {{ $text_check_status_shirt }}</li>
                                    <li>ผ้าถุง : {{ $text_check_status_skirt }}</li>
                                </ul> --}}


                                @if ($datadress->dress_price == 0)
                                    <p><strong>ราคาเช่า:</strong><span class="text-danger"> ยังไม่ได้กำหนด</span></p>
                                    <p><strong>เงินมัดจำ:</strong><span class="text-danger"> ยังไม่ได้กำหนด</span></p>
                                    <p><strong>ค่าประกันชุด:</strong><span class="text-danger"> ยังไม่ได้กำหนด</span></p>
                                @else
                                    <p><strong>ราคาเช่า:</strong> {{ number_format($datadress->dress_price, 2) }} บาท
                                        <button class="btn btn-link p-0 ml-2" data-toggle="modal" data-target="#edittotal"
                                            @if ($check_admin == 1) style="display: inline-block ;"
                                                @elseif($check_admin == 0)
                                style="display: none ; " @endif>
                                            <i class="bi bi-pencil-square" style="color: rgb(138, 136, 136);"></i>
                                        </button>
                                    </p>
                                    <p><strong>เงินมัดจำ:</strong> {{ number_format($datadress->dress_deposit, 2) }} บาท
                                    </p>
                                    <p><strong>ค่าประกันชุด:</strong> {{ number_format($datadress->damage_insurance, 2) }}
                                        บาท</p>
                                @endif

                                <p><strong>คำอธิบายชุด:</strong> <button class="btn btn-link p-0 ml-2" data-toggle="modal"
                                        data-target="#editdes"
                                        @if ($check_admin == 1) style="display: inline-block ;"
                                        @elseif($check_admin == 0)
                        style="display: none ; " @endif>
                                        <i class="bi bi-pencil-square" style="color: rgb(138, 136, 136);"></i>
                                    </button>
                                    <br>
                                    {{ $datadress->dress_description }}
                                </p>

                                </p>
                            </div>
                            <div class="col-md-5">
                                <p>
                                    <strong>ขนาดของชุด</strong> (ปรับแก้ ขยาย/ลด ได้):
                                <div class=" ">
                                    @php
                                        $list_check_name_shirt = [];
                                    @endphp
                                    <table class="table table-bordered-0">
                                        @foreach ($dress_mea_totaldress as $dress_mea_totaldress)
                                            <tr>
                                                <td>{{ $dress_mea_totaldress->mea_dress_name }}<span
                                                        style="font-size: 14px; color: rgb(197, 21, 21)"> (ปรับได้
                                                        {{ $dress_mea_totaldress->initial_min }}-{{ $dress_mea_totaldress->initial_max }}
                                                        นิ้ว)</span>
                                                </td>
                                                <td col-1>{{ $dress_mea_totaldress->current_mea }} </td>
                                                <td col-1>นิ้ว</td>
                                            </tr>
                                            {{-- @php
                                                $list_check_name_shirt[] = $item->measurementnow_dress_name;
                                            @endphp --}}
                                        @endforeach
                                    </table>

                                </div>
                                </p>
                            </div>



                        </div>

                        <li>
                            <a href="{{ route('admin.historydressrent', ['id' => $datadress->id]) }}" class="text-dark">
                                <i class="bi bi-clock-history"></i> ประวัติการเช่า
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.historydressrepair', ['id' => $datadress->id]) }}" class="text-dark">
                                <i class="bi bi-tools"></i> ประวัติการซ่อม
                            </a>
                        </li>
                        <li @if ($check_admin == 0) style="visibility: hidden;" @endif>
                            <a href="#" data-toggle="modal" data-target="#priceHistoryModal" class="text-dark">
                                <i class="fas fa-history"></i> ประวัติการปรับแก้ไขราคาเช่าทั้งชุด
                            </a>
                        </li>
                        <br>


                    </div>
                    <hr>

                    <div class="container">
                        <h3 class="mt-3"style="text-align: center">คิวการเช่าทั้งชุด</h3>
                        <div id="calendar_dress">
                        </div>
                        <p>
                            <span
                                style="display: inline-block; width: 12px; height: 12px; background-color: #ff0000; border-radius: 50%; margin-right: 5px;"></span>
                            เช่าทั้งชุด
                        </p>
                        <p>
                            <span
                                style="display: inline-block; width: 12px; height: 12px; background-color: #3788d8; border-radius: 50%; margin-right: 5px;"></span>
                            เช่าเฉพาะเสื้อ
                        </p>
                        <p>
                            <span
                                style="display: inline-block; width: 12px; height: 12px; background-color: #257e4a; border-radius: 50%; margin-right: 5px;"></span>
                            เช่าเฉพาะผ้าถุง
                        </p>


                    </div>

                </div>

                <!-- ข้อมูลเสื้อ -->

                <div class="tab-pane fade" id="shirt" role="tabpanel" aria-labelledby="shirt-tab">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-info-circle"></i> รายละเอียดเสื้อ
                        </div>

                        <button type="button" class="btn btn-outline-danger" data-toggle="modal"
                            data-target="#stopRentalModalShirt"
                            @if ($check_admin == 1) @if ($shirtitem->shirtitem_status == 'ยุติการให้เช่า' || $shirtitem->shirtitem_status == 'สูญหาย')
                                    style="display: none;"
                            @else
                                    style="display: block;" @endif
                        @elseif($check_admin == 0) style="display: none;" @endif
                            >
                            <i class="fas fa-stop"></i> ยุติการให้เช่า
                        </button>

                        <button type="button" class="btn btn-outline-success" data-toggle="modal"
                            data-target="#reopenRentalModalShirt"
                            @if ($check_admin == 1) @if ($shirtitem->shirtitem_status == 'ยุติการให้เช่า')
                            style="display: block ; "
                        @else
                            style="display: none ; " @endif
                        @elseif($check_admin == 0) style="display: none ; " @endif>
                            <i class="fas fa-stop"></i> เปิดให้เช่าอีกครั้ง
                        </button>

                    </div>







                    <div class="card-body">
                        <!-- เพิ่มข้อมูลเสื้อที่นี่ -->
                        <div class="row">
                            <div class="d-flex">
                                @foreach ($imagedata as $image)
                                    <div class="col-md-4">
                                        <img src="{{ asset('storage/' . $image->dress_image) }}" alt=""
                                            style="max-height: 350px; width: auto;">
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-md-4">



                                @if ($shirtitem->shirtitem_price == 0)
                                    <p><strong>ราคาเช่าเสื้อ:</strong><span class="text-danger"> ยังไม่ได้กำหนด</span></p>
                                    <p><strong>เงินมัดจำ:</strong><span class="text-danger"> ยังไม่ได้กำหนด</span></p>
                                    <p><strong>ค่าประกัน:</strong><span class="text-danger"> ยังไม่ได้กำหนด</span></p>
                                @else
                                    <p><strong>ราคาเช่าเสื้อ:</strong> {{ number_format($shirtitem->shirtitem_price, 2) }}
                                        บาท
                                        <button class="btn btn-link p-0 ml-2" data-toggle="modal"
                                            data-target="#edittotalshirt"
                                            @if ($check_admin == 1) @if ($shirtitem->shirtitem_status == 'ยุติการให้เช่า' || $shirtitem->shirtitem_status == 'สูญหาย')
                                                        style="display: none ; " 
                                                    @else
                                                        style="display: inline-block ;" @endif
                                        @elseif($check_admin == 0) style="display: none ; " @endif
                                            >
                                            <i class="bi bi-pencil-square text-dark"></i>
                                        </button>
                                    </p>
                                    <p><strong>เงินมัดจำ:</strong> {{ number_format($shirtitem->shirtitem_deposit, 2) }}
                                        บาท</p>
                                    <p><strong>ค่าประกัน:</strong>
                                        {{ number_format($shirtitem->shirt_damage_insurance, 2) }}บาท</p>
                                @endif



                                <p><strong>สถานะเสื้อ:</strong>
                                    @if ($shirtitem->shirtitem_status == 'พร้อมให้เช่า')
                                        <span class="badge bg-success rounded-pill px-3 py-2 ms-2">
                                            {{ $shirtitem->shirtitem_status }}
                                        </span>
                                    @elseif($shirtitem->shirtitem_status == 'กำลังถูกเช่า')
                                        <span class="badge bg-primary rounded-pill px-3 py-2 ms-2">
                                            {{ $shirtitem->shirtitem_status }}
                                        </span>
                                    @elseif($shirtitem->shirtitem_status == 'รอทำความสะอาด')
                                        <span class="badge bg-warning rounded-pill px-3 py-2 ms-2">
                                            {{ $shirtitem->shirtitem_status }}
                                        </span>
                                    @elseif($shirtitem->shirtitem_status == 'กำลังส่งซัก')
                                        <span class="badge bg-info rounded-pill px-3 py-2 ms-2">
                                            {{ $shirtitem->shirtitem_status }}
                                        </span>
                                    @elseif($shirtitem->shirtitem_status == 'รอดำเนินการซ่อม')
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 ms-2">
                                            {{ $shirtitem->shirtitem_status }}
                                        </span>
                                    @elseif($shirtitem->shirtitem_status == 'กำลังซ่อม')
                                        <span class="badge bg-danger rounded-pill px-3 py-2 ms-2">
                                            {{ $shirtitem->shirtitem_status }}
                                        </span>
                                    @elseif($shirtitem->shirtitem_status == 'สูญหาย')
                                        <span class="badge bg-danger rounded-pill px-3 py-2 ms-2">
                                            {{ $shirtitem->shirtitem_status }}
                                        </span>
                                    @elseif($shirtitem->shirtitem_status == 'ยุติการให้เช่า')
                                        <span class="badge bg-secondary rounded-pill px-3 py-2 ms-2">
                                            {{ $shirtitem->shirtitem_status }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill px-3 py-2 ms-2">
                                            {{ $shirtitem->shirtitem_status }}
                                        </span>
                                    @endif
                                </p>
















                            </div>
                            <div class="col-md-5">
                                <p>
                                    <strong>ขนาดของเสื้อ</strong> (ปรับแก้ ขยาย/ลด ได้):

                                <div class=" ">
                                    @php
                                        $list_check_name_shirt = [];
                                    @endphp
                                    <table class="table table-bordered-0">
                                        @foreach ($dress_mea_shirt as $dress_mea_shirt)
                                            <tr>
                                                <td>{{ $dress_mea_shirt->mea_dress_name }}<span
                                                        style="font-size: 14px; color: rgb(197, 21, 21)"> (ปรับได้
                                                        {{ $dress_mea_shirt->initial_min }}-{{ $dress_mea_shirt->initial_max }}
                                                        นิ้ว)</span>
                                                </td>
                                                <td col-1>{{ $dress_mea_shirt->current_mea }} </td>
                                                <td col-1>นิ้ว</td>
                                            </tr>
                                        @endforeach
                                    </table>

                                </div>
                                </p>
                            </div>

                        </div>

                        <br>
                        <li @if ($check_admin == 0) style="visibility: hidden;" @endif>
                            <a href="#" data-toggle="modal" data-target="#priceHistoryModalshirt" class="text-dark"
                                
                                >
                                <i class="fas fa-history"></i> ประวัติการปรับแก้ไขราคาเช่าเสื้อ
                            </a>
                        </li>

                    </div>
                    <hr>
                    <div class="container">
                        <h3 class="mt-3"style="text-align: center">คิวการเช่าเสื้อ</h3>

                        <div id="calendar_shirt"></div>

                    </div>






                </div>

                <!-- ข้อมูลกางเกง -->

                <div class="tab-pane fade" id="pants" role="tabpanel" aria-labelledby="pants-tab">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-info-circle"></i> รายละเอียดผ้าถุง
                        </div>

                        <button type="button" class="btn btn-outline-danger" data-toggle="modal"
                            data-target="#stopRentalModalSkirt"
                            @if ($check_admin == 1) @if ($skirtitem->skirtitem_status == 'ยุติการให้เช่า' || $skirtitem->skirtitem_status == 'สูญหาย')
                                    style="display: none;"
                                @else
                                    style="display: block;" @endif
                        @elseif($check_admin == 0) style="display: none;" @endif
                            >
                            <i class="fas fa-stop"></i> ยุติการให้เช่า
                        </button>

                        <button type="button" class="btn btn-outline-success" data-toggle="modal"
                            data-target="#reopenRentalModalSkirt"
                            @if ($check_admin == 1) @if ($skirtitem->skirtitem_status == 'ยุติการให้เช่า')
                                    style="display: block ; "
                                @else
                                    style="display: none ; " @endif
                        @elseif($check_admin == 0) style="display: none ; " @endif>
                            <i class="fas fa-stop"></i> เปิดให้เช่าอีกครั้ง
                        </button>

                    </div>


















                    <div class="card-body">
                        <!-- เพิ่มข้อมูลกางเกงที่นี่ -->
                        <div class="row">

                            <div class="d-flex">
                                @foreach ($imagedata as $image)
                                    <div class="col-md-4">
                                        <img src="{{ asset('storage/' . $image->dress_image) }}" alt=""
                                            style="max-height: 350px; width: auto;">
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-md-4">
                                </p>

                                @if ($skirtitem->skirtitem_price == 0)
                                    <p><strong>ราคาเช่าผ้าถุง:</strong><span class="text-danger"> ยังไม่ได้กำหนด</span></p>
                                    <p><strong>เงินมัดจำ:</strong><span class="text-danger"> ยังไม่ได้กำหนด</span></p>
                                    <p><strong>ค่าประกัน:</strong><span class="text-danger"> ยังไม่ได้กำหนด</span></p>
                                @else
                                    <p><strong>ราคาเช่าผ้าถุง: </strong>
                                        {{ number_format($skirtitem->skirtitem_price, 2) }} บาท
                                        <button class="btn btn-link p-0 ml-2" data-toggle="modal"
                                            data-target="#edittotalskirt"
                                            @if ($check_admin == 1) @if ($skirtitem->skirtitem_status == 'ยุติการให้เช่า' || $skirtitem->skirtitem_status == 'สูญหาย')
                                                        style="display: none ; " 
                                                    @else
                                                        style="display: inline-block ;" @endif
                                        @elseif($check_admin == 0) style="display: none ; " @endif>
                                            <i class="bi bi-pencil-square text-dark"></i>
                                        </button>
                                    </p>
                                    <p><strong>เงินมัดจำ: </strong> {{ number_format($skirtitem->skirtitem_deposit, 2) }}
                                        บาท</p>
                                    <p><strong>ค่าประกัน:
                                        </strong>{{ number_format($skirtitem->skirt_damage_insurance, 2) }}
                                        บาท</p>
                                @endif


                                <p><strong>สถานะผ้าถุง:</strong>
                                    @if ($skirtitem->skirtitem_status == 'พร้อมให้เช่า')
                                        <span class="badge bg-success rounded-pill px-3 py-2 ms-2">
                                            {{ $skirtitem->skirtitem_status }}
                                        </span>
                                    @elseif($skirtitem->skirtitem_status == 'กำลังถูกเช่า')
                                        <span class="badge bg-primary rounded-pill px-3 py-2 ms-2">
                                            {{ $skirtitem->skirtitem_status }}
                                        </span>
                                    @elseif($skirtitem->skirtitem_status == 'รอทำความสะอาด')
                                        <span class="badge bg-warning rounded-pill px-3 py-2 ms-2">
                                            {{ $skirtitem->skirtitem_status }}
                                        </span>
                                    @elseif($skirtitem->skirtitem_status == 'กำลังส่งซัก')
                                        <span class="badge bg-info rounded-pill px-3 py-2 ms-2">
                                            {{ $skirtitem->skirtitem_status }}
                                        </span>
                                    @elseif($skirtitem->skirtitem_status == 'รอดำเนินการซ่อม')
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 ms-2">
                                            {{ $skirtitem->skirtitem_status }}
                                        </span>
                                    @elseif($skirtitem->skirtitem_status == 'กำลังซ่อม')
                                        <span class="badge bg-danger rounded-pill px-3 py-2 ms-2">
                                            {{ $skirtitem->skirtitem_status }}
                                        </span>
                                    @elseif($skirtitem->skirtitem_status == 'สูญหาย')
                                        <span class="badge bg-danger rounded-pill px-3 py-2 ms-2">
                                            {{ $skirtitem->skirtitem_status }}
                                        </span>
                                    @elseif($skirtitem->skirtitem_status == 'ยุติการให้เช่า')
                                        <span class="badge bg-secondary rounded-pill px-3 py-2 ms-2">
                                            {{ $skirtitem->skirtitem_status }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill px-3 py-2 ms-2">
                                            {{ $skirtitem->skirtitem_status }}
                                        </span>
                                    @endif
                                </p>

                            </div>
                            <div class="col-md-5">
                                <p>
                                    <strong>ขนาดของผ้าถุง</strong> (ปรับแก้ ขยาย/ลด ได้):

                                <div class=" ">
                                    @php
                                        $list_check_name_skirt = [];
                                    @endphp
                                    <table class="table table-bordered-0">
                                        @foreach ($dress_mea_skirt as $dress_mea_skirt)
                                            <tr>
                                                <td>{{ $dress_mea_skirt->mea_dress_name }}<span
                                                        style="font-size: 14px; color: rgb(197, 21, 21)"> (ปรับได้
                                                        {{ $dress_mea_skirt->initial_min }}-{{ $dress_mea_skirt->initial_max }}
                                                        นิ้ว)</span>
                                                </td>

                                                </td>
                                                <td>{{ $dress_mea_skirt->current_mea }}
                                                </td>
                                                <td>นิ้ว</td>
                                            </tr>
                                        @endforeach
                                    </table>


                                </div>
                                </p>
                            </div>



                        </div>
                        <br>
                        <li @if ($check_admin == 0) style="visibility: hidden;" @endif>
                            <a href="#" data-toggle="modal" data-target="#priceHistoryModalskirt"
                                class="text-dark"
                                >
                                <i class="fas fa-history"></i> ประวัติการปรับแก้ไขราคาเช่าผ้าถุง
                            </a>
                        </li>
                    </div>
                    <hr>
                    <div class="container">
                        <h3 class="mt-3"style="text-align: center">คิวการเช่าผ้าถุง</h3>

                        <div id="calendar_skirt"></div>


                    </div>
                </div>
            </div>

            {{-- modalแก้ไขชุด --}}
            <div class="modal fade" id="edittotal" role="dialog" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #BACEE6;">
                            <h5 class="modal-title">แก้ไขราคาเช่าทั้งชุด</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <!-- ข้อมูลชุด -->

                                <form action="{{ route('admin.updatedressnoyes', ['id' => $datadress->id]) }}"
                                    method="POST">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_dress_price"
                                                style="font-weight:bold">ราคาเช่าทั้งชุด</label>
                                            <input type="number" class="form-control" name="update_dress_price"
                                                id="update_dress_price" value="{{ $datadress->dress_price }}"
                                                placeholder="กรุณากรอกราคา" min="1" step="0.01">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_dress_deposit" style="font-weight:bold">ราคามัดจำ</label>
                                            <input type="number" class="form-control" name="update_dress_deposit"
                                                id="update_dress_deposit" value="{{ $datadress->dress_deposit }}"
                                                placeholder="กรุณากรอกราคามัดจำ" readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_dress_deposit"
                                                style="font-weight:bold">ราคาประกันค่าเสียหาย</label>
                                            <input type="number" class="form-control" name="update_damage_insurance"
                                                id="update_dress_damage_insurance"
                                                value="{{ $datadress->damage_insurance }}"
                                                placeholder="กรุณากรอกราคาประกันค่าเสียหาย" readonly>
                                        </div>
                                    </div>

                                    <script>
                                        var update_dress_price = document.getElementById('update_dress_price');
                                        var update_dress_deposit = document.getElementById('update_dress_deposit');
                                        var update_dress_damage_insurance = document.getElementById('update_dress_damage_insurance');
                                        update_dress_price.addEventListener('input', function() {
                                            var float_price = parseFloat(update_dress_price.value);

                                            update_dress_deposit.value = float_price * 0.2;
                                            update_dress_damage_insurance.value = float_price;

                                        });
                                    </script>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn " data-dismiss="modal"
                                style="background-color:#DADAE3;">ยกเลิก</button>
                            <button type="submit" class="btn " style="background-color:#ACE6B7;">บันทึก</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- modalแก้ไขชุด --}}
            <div class="modal fade" id="editdes" role="dialog" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #BACEE6;">
                            <h5 class="modal-title">แก้ไขคำอธิบายชุด</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <!-- ข้อมูลชุด -->

                                <form action="{{ route('updatedressnoyesdes', ['id' => $datadress->id]) }}"
                                    method="POST">
                                    @csrf

                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_dress_description"
                                                style="font-weight:bold">คำอธิบายชุด</label>
                                            <textarea name="update_dress_description" id="update_dress_description" class="form-control" rows="3">{{ $datadress->dress_description }}</textarea>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn " data-dismiss="modal"
                                style="background-color:#DADAE3;">ยกเลิก</button>
                            <button type="submit" class="btn " style="background-color:#ACE6B7;">บันทึก</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>























            {{-- modalแก้ไขเสื้อ+ข้อมูลการวัด --}}
            <div class="modal fade" id="edittotalshirt" role="dialog" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#BACEE6;">
                            <h5 class="modal-title">แก้ไขราคาเช่าเสื้อ</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('admin.updatedressyesshirt', ['id' => $shirtitem->id]) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="container-fluid">
                                    <!-- ข้อมูลชุด -->
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_shirt_price" style="font-weight:bold">ราคาเช่าเสื้อ</label>
                                            <input type="number" class="form-control" name="update_shirt_price"
                                                id="update_shirt_price" value="{{ $shirtitem->shirtitem_price }}"
                                                placeholder="กรุณากรอกราคา" min="1" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_shirt_deposit" style="font-weight:bold">ราคามัดจำ</label>
                                            <input type="number" class="form-control" name="update_shirt_deposit"
                                                id="update_shirt_deposit" value="{{ $shirtitem->shirtitem_deposit }}"
                                                placeholder="กรุณากรอกราคามัดจำ" min="1" required readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_shirt_damage_insurance"
                                                style="font-weight:bold">ราคาประกันค่าเสียหาย</label>
                                            <input type="number" class="form-control"
                                                name="update_shirt_damage_insurance" id="update_shirt_damage_insurance"
                                                value="{{ $shirtitem->shirt_damage_insurance }}"
                                                placeholder="กรุณากรอกราคาประกันค่าเสียหาย" min="0" required
                                                readonly>
                                        </div>
                                    </div>

                                    <script>
                                        var update_shirt_price = document.getElementById('update_shirt_price');
                                        var update_shirt_deposit = document.getElementById('update_shirt_deposit');
                                        var update_shirt_damage_insurance = document.getElementById('update_shirt_damage_insurance');

                                        update_shirt_price.addEventListener('input', function() {
                                            var float_shirt_price = parseFloat(update_shirt_price.value);
                                            update_shirt_deposit.value = float_shirt_price * 0.2;
                                            update_shirt_damage_insurance.value = float_shirt_price;
                                        });
                                    </script>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn " data-dismiss="modal"
                                    style="background-color:#DADAE3;">ยกเลิก</button>
                                <button type="submit" class="btn " style="background-color:#ACE6B7;">บันทึก</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>


            {{-- modalแก้ไขกระโปรง+ข้อมูลการวัด --}}
            <div class="modal fade" id="edittotalskirt" role="dialog" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #BACEE6;">
                            <h5 class="modal-title">แก้ไขราคาเช่าผ้าถุง</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('admin.updatedressyesskirt', ['id' => $skirtitem->id]) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="container-fluid">
                                    <!-- ข้อมูลชุด -->

                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_skirt_price"
                                                style="font-weight:bold">ราคาเช่าผ้าถุง</label>
                                            <input type="number" class="form-control" name="update_skirt_price"
                                                id="update_skirt_price" value="{{ $skirtitem->skirtitem_price }}"
                                                placeholder="กรุณากรอกราคา" min="1" step="0.01" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_skirt_deposit" style="font-weight:bold">ราคามัดจำ</label>
                                            <input type="number" class="form-control" name="update_skirt_deposit"
                                                id="update_skirt_deposit" value="{{ $skirtitem->skirtitem_deposit }}"
                                                placeholder="กรุณากรอกราคามัดจำ" step="0.01" required readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_skirt_deposit"
                                                style="font-weight:bold">ราคาประกันค่าเสียหาย</label>
                                            <input type="number" class="form-control"
                                                name="update_skirt_damage_insurance" id="update_skirt_damage_insurance"
                                                value="{{ $skirtitem->skirt_damage_insurance }}"
                                                placeholder="กรุณากรอกราคามัดจำ" step="0.01" required readonly>
                                        </div>
                                    </div>

                                    <script>
                                        var update_skirt_price = document.getElementById('update_skirt_price');
                                        var update_skirt_deposit = document.getElementById('update_skirt_deposit');
                                        var update_skirt_damage_insurance = document.getElementById('update_skirt_damage_insurance');
                                        update_skirt_price.addEventListener('input', function() {
                                            var float_skirt_price = parseFloat(update_skirt_price.value);
                                            update_skirt_deposit.value = float_skirt_price * 0.2;
                                            update_skirt_damage_insurance.value = float_skirt_price;

                                        });
                                    </script>







                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" data-dismiss="modal" class="btn "
                                    style="background-color:#DADAE3;">ยกเลิก</button>
                                <button type="submit" class="btn"style="background-color:#ACE6B7;">บันทึก</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>






        </div>







        <!-- Modal แสดงประวัติการแก้ไขราคาทั้งชุด -->
        <div class="modal fade" id="priceHistoryModal" tabindex="-1" aria-labelledby="priceHistoryModalLabel"
            aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="priceHistoryModalLabel">ประวัติการปรับแก้ไขราคาเช่า -
                            {{ $name_type }} {{ $datadress->dress_code_new }}{{ $datadress->dress_code }}</h5>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>วันที่แก้ไข</th>
                                        <th>ราคาเดิม</th>
                                        <th>ราคาใหม่</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($historydress as $item)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($item->created_at)->locale('th')->isoFormat('D MMM') }}
                                                {{ \Carbon\Carbon::parse($item->created_at)->year + 543 }}
                                            </td>
                                            <td>{{ number_format($item->old_price, 2) }} บาท</td>
                                            <td>{{ number_format($item->new_price, 2) }} บาท</td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal แสดงประวัติการแก้ไขราคาเสื้อ -->
        <div class="modal fade" id="priceHistoryModalshirt" tabindex="-1" aria-labelledby="priceHistoryModalshirt"
            aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="priceHistoryModalshirt">ประวัติการปรับแก้ไขราคาเช่า -
                            {{ $name_type }} {{ $datadress->dress_code_new }}{{ $datadress->dress_code }} (เสื้อ)</h5>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>วันที่แก้ไข</th>
                                        <th>ราคาเดิม</th>
                                        <th>ราคาใหม่</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($historypriceshirt as $item)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($item->created_at)->locale('th')->isoFormat('D MMM') }}
                                                {{ \Carbon\Carbon::parse($item->created_at)->year + 543 }}
                                            </td>
                                            <td>{{ number_format($item->old_price, 2) }} บาท</td>
                                            <td>{{ number_format($item->new_price, 2) }} บาท</td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal แสดงประวัติการแก้ไขราคาผ้าถุง -->
        <div class="modal fade" id="priceHistoryModalskirt" tabindex="-1" aria-labelledby="priceHistoryModalskirt"
            aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="priceHistoryModalskirt">ประวัติการปรับแก้ไขราคาเช่า -
                            {{ $name_type }} {{ $datadress->dress_code_new }}{{ $datadress->dress_code }} (ผ้าถุง)
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>วันที่แก้ไข</th>
                                        <th>ราคาเดิม</th>
                                        <th>ราคาใหม่</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($historypriceskirt as $item)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($item->created_at)->locale('th')->isoFormat('D MMM') }}
                                                {{ \Carbon\Carbon::parse($item->created_at)->year + 543 }}
                                            </td>
                                            <td>{{ number_format($item->old_price, 2) }} บาท</td>
                                            <td>{{ number_format($item->new_price, 2) }} บาท</td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                    </div>
                </div>
            </div>
        </div>



        <div class="modal fade" id="stopRentalModalShirt" tabindex="-1" role="dialog"
            aria-labelledby="stopRentalModalShirtLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content shadow-lg border-0 rounded-3">
                    <div class="modal-header bg-danger text-white d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2 fa-lg"></i>
                        <h5 class="modal-title" id="stopRentalModalShirtLabel">ยืนยันการยุติการให้เช่า</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fas fa-exclamation-circle text-danger fa-3x mb-3"></i>

                        <p class="fs-5 mt-3">
                            คุณแน่ใจหรือไม่ว่าต้องการยุติการให้เช่าเสื้อนี้?
                            <span class="text-danger fw-bold">หากต้องการ สามารถเปิดให้เช่าอีกครั้งในภายหลังได้</span>
                        </p>
                        <!-- แสดงข้อความแจ้งเตือนหากมีลูกค้าจองชุดนี้ -->



                    </div>
                    @if ($reser_dress_stopRent_shirt->count() > 0)
                        <!-- จำลองว่ามีลูกค้าจองอยู่ -->
                        <div class="alert alert-warning text-start">
                            <strong>มีลูกค้าที่จองไว้ {{ $reser_dress_stopRent_shirt->count() }} คน</strong>
                            <ul class="mt-2">
                                @foreach ($reser_dress_stopRent_shirt as $item)
                                    <li style="font-size: 14px;">คุณ{{ $item->re_one_many_details->first()->order->customer->customer_fname }}
                                        {{ $item->re_one_many_details->first()->order->customer->customer_lname }}
                                        <span>(
                                            @if ($item->shirtitems_id)
                                                จองเสื้อ
                                            @elseif($item->skirtitems_id)
                                                จองผ้าถุง
                                            @else
                                                จองทั้งชุด
                                            @endif
                                            -นัดรับวันที่
                                            {{ \Carbon\Carbon::parse($item->start_date)->isoFormat('D MMM') }}
                                            {{ \Carbon\Carbon::parse($item->start_date)->year + 543 }})
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                            <p class="text-danger fw-bold mt-2">**กรุณาติดต่อแจ้งลูกค้าหลังจากที่ยุติการให้เช่า</p>
                        </div>
                    @endif
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="button" class="btn btn-secondary px-4 py-2 rounded-pill" data-dismiss="modal">
                            <i class="fas fa-times"></i> ยกเลิก
                        </button>
                        <form action="{{ route('stopRentalyesdressshirt', ['id' => $shirtitem->id]) }}" method="POST">
                            <!-- ตัวอย่าง id -->
                            @csrf
                            <button type="submit" class="btn btn-danger px-4 py-2 rounded-pill">
                                <i class="fas fa-check"></i> ยืนยัน
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="stopRentalModalSkirt" tabindex="-1" role="dialog"
            aria-labelledby="stopRentalModalSkirtLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content shadow-lg border-0 rounded-3">
                    <div class="modal-header bg-danger text-white d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2 fa-lg"></i>
                        <h5 class="modal-title" id="stopRentalModalSkirtLabel">ยืนยันการยุติการให้เช่า</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fas fa-exclamation-circle text-danger fa-3x mb-3"></i>

                        <p class="fs-5 mt-3">
                            คุณแน่ใจหรือไม่ว่าต้องการยุติการให้เช่าผ้าถุงนี้?
                            <span class="text-danger fw-bold">หากต้องการ สามารถเปิดให้เช่าอีกครั้งในภายหลังได้</span>
                        </p>
                        <!-- แสดงข้อความแจ้งเตือนหากมีลูกค้าจองชุดนี้ -->



                    </div>
                    @if ($reser_dress_stopRent_skirt->count() > 0)
                        <!-- จำลองว่ามีลูกค้าจองอยู่ -->
                        <div class="alert alert-warning text-start">
                            <strong>มีลูกค้าที่จองไว้ {{ $reser_dress_stopRent_skirt->count() }} คน</strong>
                            <ul class="mt-2">
                                @foreach ($reser_dress_stopRent_skirt as $item)
                                    <li style="font-size: 14px;">คุณ{{ $item->re_one_many_details->first()->order->customer->customer_fname }}
                                        {{ $item->re_one_many_details->first()->order->customer->customer_lname }}
                                        <span>(
                                            @if ($item->shirtitems_id)
                                                จองเสื้อ
                                            @elseif($item->skirtitems_id)
                                                จองผ้าถุง
                                            @else
                                                จองทั้งชุด
                                            @endif
                                            -นัดรับวันที่
                                            {{ \Carbon\Carbon::parse($item->start_date)->isoFormat('D MMM') }}
                                            {{ \Carbon\Carbon::parse($item->start_date)->year + 543 }})
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                            <p class="text-danger fw-bold mt-2">**กรุณาติดต่อแจ้งลูกค้าหลังจากที่ยุติการให้เช่า</p>
                        </div>
                    @endif
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="button" class="btn btn-secondary px-4 py-2 rounded-pill" data-dismiss="modal">
                            <i class="fas fa-times"></i> ยกเลิก
                        </button>
                        <form action="{{ route('stopRentalyesdressskirt', ['id' => $skirtitem->id]) }}" method="POST">
                            <!-- ตัวอย่าง id -->
                            @csrf
                            <button type="submit" class="btn btn-danger px-4 py-2 rounded-pill">
                                <i class="fas fa-check"></i> ยืนยัน
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>



        <!-- Modal ยืนยันการเปิดให้เช่าอีกครั้ง -->
        <div class="modal fade" id="reopenRentalModalShirt" tabindex="-1" role="dialog"
        aria-labelledby="reopenRentalModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg border-0 rounded-3">
                <div class="modal-header bg-success text-white d-flex align-items-center">
                    <i class="fas fa-check-circle me-2 fa-lg"></i>
                    <h5 class="modal-title" id="reopenRentalModalLabel">ยืนยันการเปิดให้เช่าอีกครั้ง</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-info-circle text-success fa-3x mb-3"></i>

                    <p class="fs-5 mt-3">
                        คุณต้องการเปิดให้เช่าชุดนี้อีกครั้งใช่หรือไม่?
                        <span class="text-success fw-bold">หลังจากเปิดให้เช่าอีกครั้ง
                            ลูกค้าจะสามารถจองเสื้อนี้ได้ตามปกติ</span>
                    </p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary px-4 py-2 rounded-pill" data-dismiss="modal">
                        <i class="fas fa-times"></i> ยกเลิก
                    </button>
                    <form action="{{ route('reopenRentalyesdressshirt', ['id' => $shirtitem->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success px-4 py-2 rounded-pill">
                            <i class="fas fa-check"></i> ยืนยัน
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal ยืนยันการเปิดให้เช่าอีกครั้ง -->
    <div class="modal fade" id="reopenRentalModalSkirt" tabindex="-1" role="dialog"
    aria-labelledby="reopenRentalModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0 rounded-3">
            <div class="modal-header bg-success text-white d-flex align-items-center">
                <i class="fas fa-check-circle me-2 fa-lg"></i>
                <h5 class="modal-title" id="reopenRentalModalLabel">ยืนยันการเปิดให้เช่าอีกครั้ง</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-info-circle text-success fa-3x mb-3"></i>

                <p class="fs-5 mt-3">
                    คุณต้องการเปิดให้เช่าชุดนี้อีกครั้งใช่หรือไม่?
                    <span class="text-success fw-bold">หลังจากเปิดให้เช่าอีกครั้ง
                        ลูกค้าจะสามารถจองผ้าถุงนี้ได้ตามปกติ</span>
                </p>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-secondary px-4 py-2 rounded-pill" data-dismiss="modal">
                    <i class="fas fa-times"></i> ยกเลิก
                </button>
                <form action="{{ route('reopenRentalyesdressskirt', ['id' => $skirtitem->id]) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success px-4 py-2 rounded-pill">
                        <i class="fas fa-check"></i> ยืนยัน
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>



























         <!-- Modals for success and failure messages -->
         <div class="modal fade" id="showsuccessss" role="dialog" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-lg">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="bi bi-check-circle-fill"></i> สำเร็จ</h5>
    
                    </div>
                    <div class="modal-body text-center p-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                        <p class="mt-3 text-success fw-bold">{{ session('success') }}</p>
                    </div>
                    <div class="modal-footer border-0 justify-content-center">
                        <button type="button" class="btn btn-success px-4" data-dismiss="modal">ตกลง</button>
                    </div>
                </div>
            </div>
        </div>
    
    
        <div class="modal fade" id="showfail" role="dialog" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-lg">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill"></i> เกิดข้อผิดพลาด</h5>
                    </div>
                    <div class="modal-body text-center p-4">
                        <i class="bi bi-x-circle-fill text-danger" style="font-size: 3rem;"></i>
                        <p class="mt-3 text-danger fw-bold">{{ session('fail') }}</p>
                    </div>
                    <div class="modal-footer border-0 justify-content-center">
                        <button type="button" class="btn btn-danger px-4" data-dismiss="modal">ปิด</button>
                    </div>
                </div>
            </div>
        </div>
    
        <script>
            @if (session('success'))
                setTimeout(function() {
                    $('#showsuccessss').modal('show');
                }, 500);
            @endif
            @if (session('fail'))
                setTimeout(function() {
                    $('#showfail').modal('show');
                }, 500);
            @endif
        </script>
    








        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet">

        <!-- FullCalendar CSS & JS -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>

        <style>
            #calendar_dress,
            #calendar_shirt,
            #calendar_skirt {
                max-width: 900px;
                margin: 50px auto;
            }
        </style>



        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize calendar for dress
                var calendarElDress = document.getElementById('calendar_dress');
                var calendarDress = new FullCalendar.Calendar(calendarElDress, {
                    initialView: 'dayGridMonth',
                    events: [
                        // ทั้งชุด
                        @foreach ($date_reservations_dress as $reservation)
                            {
                                @php
                                    $order_id = App\Models\Orderdetail::where('reservation_id', $reservation->id)->value('order_id');
                                    $customer_id = App\Models\Order::where('id', $order_id)->value('customer_id');
                                    $customer = App\Models\Customer::find($customer_id);
                                @endphp

                                title:
                                    'คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }} --- สถานะ: {{ $reservation->status }}',
                                    start: '{{ $reservation->start_date }}',
                                    end:
                                    '{{ \Carbon\Carbon::parse($reservation->end_date)->addDay()->format('Y-m-d') }}',
                                    color:
                                    '{{ $reservation->status == 'ถูกจอง' ? '#ff0000' : '#257e4a' }}' // สีแดงสำหรับเช่าทั้งชุด
                            },
                        @endforeach

                        // คิวสำหรับเช่าเฉพาะเสื้อ
                        @foreach ($date_reservations_shirt as $reservation)
                            {
                                @php
                                    $order_id = App\Models\Orderdetail::where('reservation_id', $reservation->id)->value('order_id');
                                    $customer_id = App\Models\Order::where('id', $order_id)->value('customer_id');
                                    $customer = App\Models\Customer::find($customer_id);
                                @endphp

                                title:
                                    'คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }} --- สถานะ: {{ $reservation->status }}',
                                    start: '{{ $reservation->start_date }}',
                                    end:
                                    '{{ \Carbon\Carbon::parse($reservation->end_date)->addDay()->format('Y-m-d') }}',
                                    color: '#3788d8' // สีน้ำเงินสำหรับเช่าเฉพาะเสื้อ
                            },
                        @endforeach



                        // คิวสำหรับเช่าเฉพาะผ้าถุง
                        @foreach ($date_reservations_skirt as $reservation)
                            {
                                @php
                                    $order_id = App\Models\Orderdetail::where('reservation_id', $reservation->id)->value('order_id');
                                    $customer_id = App\Models\Order::where('id', $order_id)->value('customer_id');
                                    $customer = App\Models\Customer::find($customer_id);
                                @endphp

                                title:
                                    'คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }} --- สถานะ: {{ $reservation->status }}',
                                    start: '{{ $reservation->start_date }}',
                                    end:
                                    '{{ \Carbon\Carbon::parse($reservation->end_date)->addDay()->format('Y-m-d') }}',
                                    color: '#257e4a' // สีเขียวสำหรับเช่าเฉพาะผ้าถุง
                            },
                        @endforeach










                    ],
                    locale: 'th'
                });
                calendarDress.render();

                // Event listener for when tab is shown
                $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                    var target = $(e.target).attr("href"); // Get the tab target

                    if (target === '#shirt') {
                        // Initialize calendar for shirt when the tab is shown
                        var calendarElShirt = document.getElementById('calendar_shirt');
                        var calendarShirt = new FullCalendar.Calendar(calendarElShirt, {
                            initialView: 'dayGridMonth',
                            events: [
                                @foreach ($date_reservations_shirt as $reservation)
                                    {
                                        @php

                                            $order_id = App\Models\Orderdetail::where('reservation_id', $reservation->id)->value('order_id');
                                            $customer_id = App\Models\Order::where('id', $order_id)->value('customer_id');
                                            $customer = App\Models\Customer::find($customer_id);

                                        @endphp
                                        title:
                                            'คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }} --- สถานะ:{{ $reservation->status }}',
                                            start: '{{ $reservation->start_date }}',
                                            end:
                                            '{{ \Carbon\Carbon::parse($reservation->end_date)->addDay()->format('Y-m-d') }}',
                                            color:
                                            '{{ $reservation->status == 'ถูกจอง' ? '#3788d8' : '#257e4a' }}'
                                    },
                                @endforeach
                            ],
                            locale: 'th'
                        });
                        calendarShirt.render();
                    }

                    if (target === '#pants') {
                        // Initialize calendar for pants (skirt) when the tab is shown
                        var calendarElSkirt = document.getElementById('calendar_skirt');
                        var calendarSkirt = new FullCalendar.Calendar(calendarElSkirt, {
                            initialView: 'dayGridMonth',
                            events: [
                                @foreach ($date_reservations_skirt as $reservation)
                                    {
                                        @php

                                            $order_id = App\Models\Orderdetail::where('reservation_id', $reservation->id)->value('order_id');
                                            $customer_id = App\Models\Order::where('id', $order_id)->value('customer_id');
                                            $customer = App\Models\Customer::find($customer_id);

                                        @endphp
                                        title:
                                            'คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }} --- สถานะ:{{ $reservation->status }}',
                                            start: '{{ $reservation->start_date }}',
                                            end:
                                            '{{ \Carbon\Carbon::parse($reservation->end_date)->addDay()->format('Y-m-d') }}',
                                            color:
                                            '{{ $reservation->status == 'ถูกจอง' ? '#3788d8' : '#257e4a' }}'
                                    },
                                @endforeach
                            ],
                            locale: 'th'
                        });
                        calendarSkirt.render();
                    }
                });
            });
        </script>
    @endsection
