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
    </style>

    <ol class="breadcrumb" style="background: white; ">
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

    <div class="row">
        <div class="col">
            <h2 class="py-4" style="text-align: center">รายละเอียดของหมายเลขชุด
                {{ $datadress->dress_code_new }}{{ $datadress->dress_code }}</h2>
        </div>
    </div>
    <div class="container">



        <div class="card mb-4 shadow-sm bg-body rounded">


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
                        <button class="btn btn-link p-0 ml-2 float-right" data-toggle="modal" data-target="#edittotal">
                            <i class="bi bi-pencil-square text-dark"></i>
                        </button>
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

                                <p><strong>ราคาเช่า:</strong> {{ number_format($datadress->dress_price, 2) }} บาท</p>
                                <p><strong>เงินมัดจำ:</strong> {{ number_format($datadress->dress_deposit, 2) }} บาท</p>
                                <p><strong>ค่าประกันชุด:</strong>
                                    {{ number_format($datadress->damage_insurance, 2) }} บาท</p>


                                <p><strong>จำนวนครั้งที่ถูกเช่า:</strong> {{ $datadress->dress_rental }} ครั้ง

                                </p>
                                <p><strong>คำอธิบายชุด:</strong> {{ $datadress->dress_description }}</p>
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


                            <div class="mt-3"
                                @if ($check_admin == 1) style="display: block ; "
                    @elseif($check_admin == 0)
                    style="display: none ; " @endif>
                                <a href="{{ route('admin.historydressrent', ['id' => $datadress->id]) }}"
                                    class="btn btn-outline-primary mr-2">
                                    <i class="bi bi-clock-history"></i> ประวัติการเช่า
                                </a>
                                <a href="{{ route('admin.historydressrepair', ['id' => $datadress->id]) }}"
                                    class="btn btn-outline-secondary">
                                    <i class="bi bi-tools"></i> ประวัติการซ่อม
                                </a>
                            </div>



                        </div>
                    </div>


                    <div class="container">
                        <h3>คิวการเช่าทั้งชุด</h3>
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
                    <div class="card-header">
                        <i class="bi bi-info-circle"></i> รายละเอียดเสื้อ
                        <button class="btn btn-link p-0 ml-2 float-right" data-toggle="modal" data-target="#edittotalshirt">
                            <i class="bi bi-pencil-square text-dark"></i>
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
                                <p>
                                    {{-- <strong>สถานะเสื้อปัจุบัน:</strong>
                                    <span>
                                        {{ $text_check_status_shirt }}
                                    </span> --}}
                                </p>
                                <p><strong>ราคาเช่า:</strong> {{ number_format($shirtitem->shirtitem_price, 2) }} บาท</p>
                                <p><strong>เงินมัดจำ:</strong> {{ number_format($shirtitem->shirtitem_deposit, 2) }} บาท
                                </p>
                                <p><strong>ค่าประกัน:</strong> {{ number_format($shirtitem->shirt_damage_insurance, 2) }}
                                    บาท</p>
                                <p><strong>จำนวนครั้งที่ถูกเช่า:</strong> {{ $shirtitem->shirtitem_rental }} ครั้ง
                                    {{-- <a href="">
                                        ดูประวัติ
                                    </a> --}}
                                </p>
                                <p><strong>จำนวนครั้งที่ซ่อม:</strong> รอ
                                    {{-- <a href="">ดูประวัติ</a> --}}
                                </p>
                            </div>
                            <div class="col-md-5">
                                <p>
                                    <strong>ขนาดของเสื้อ</strong> (ปรับแก้ ขยาย/ลด ได้):
                                    {{-- <button class="btn btn-link p-0 ml-2" data-toggle="modal" data-target="#add_mea_shirt">
                                        <i class="bi bi-plus-square text-dark"></i>
                                    </button> --}}
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
                                            {{-- @php
                                                $list_check_name_shirt[] = $item->measurementnow_dress_name;
                                            @endphp --}}
                                        @endforeach
                                    </table>

                                </div>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="container">
                        <h3>คิวการเช่าเสื้อ</h3>

                        <div id="calendar_shirt"></div>

                    </div>






                </div>

                <!-- ข้อมูลกางเกง -->
                <div class="tab-pane fade" id="pants" role="tabpanel" aria-labelledby="pants-tab">
                    <div class="card-header">
                        <i class="bi bi-info-circle"></i> รายละเอียดผ้าถุง
                        <button class="btn btn-link p-0 ml-2 float-right" data-toggle="modal"
                            data-target="#edittotalskirt">
                            <i class="bi bi-pencil-square text-dark
                            "></i>
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
                                {{-- <p><strong>สถานะผ้าถุงตอนนี้:</strong> <span>{{ $text_check_status_skirt }}</span> --}}
                                </p>
                                <p><strong>ราคาเช่า:</strong> {{ number_format($skirtitem->skirtitem_price, 2) }} บาท</p>
                                <p><strong>เงินมัดจำ:</strong> {{ number_format($skirtitem->skirtitem_deposit, 2) }} บาท
                                </p>
                                <p><strong>ค่าประกัน:</strong>
                                    {{ number_format($skirtitem->skirt_damage_insurance, 2) }} บาท</p>
                                <p><strong>จำนวนครั้งที่ถูกเช่า:</strong> {{ $skirtitem->skirtitem_rental }} ครั้ง
                                    {{-- <span>
                                        <a href="">ดูประวัติ</a>
                                    </span> --}}
                                </p>
                                <p><strong>จำนวนครั้งที่ซ่อม:</strong> รอ
                                    {{-- <span>
                                        <a href="">ดูประวัติ</a>
                                    </span> --}}
                                </p>
                            </div>
                            <div class="col-md-5">
                                <p>
                                    <strong>ขนาดของผ้าถุง</strong> (ปรับแก้ ขยาย/ลด ได้):
                                    {{-- <button class="btn btn-link p-0 ml-2" data-toggle="modal"
                                        data-target="#add_mea_skirt">
                                        <i class="bi bi-plus-square text-dark"></i>
                                    </button> --}}
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
                                            {{-- @php
                                                $list_check_name_skirt[] =
                                                    $measument_yes_separate_now_skirt->measurementnow_dress_name;
                                            @endphp --}}
                                        @endforeach
                                    </table>



                                </div>
                                </p>
                            </div>


                        </div>
                    </div>
                    <div class="container">
                        <h3>คิวการเช่าผ้าถุง</h3>

                        <div id="calendar_skirt"></div>


                    </div>
                </div>
            </div>

            {{-- modalแก้ไขชุด --}}
            <div class="modal fade" id="edittotal" role="dialog" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header text-dark" style="background-color: #EAD8C0;">
                            <h5 class="modal-title">แก้ไขข้อมูลชุด</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <!-- ข้อมูลชุด -->
                                <h5 class="mb-4">ข้อมูลชุด</h5>

                                <form action="{{ route('admin.updatedressnoyes', ['id' => $datadress->id]) }}"
                                    method="POST">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_dress_price">ราคา</label>
                                            <input type="number" class="form-control" name="update_dress_price"
                                                id="update_dress_price" value="{{ $datadress->dress_price }}"
                                                placeholder="กรุณากรอกราคา" min="1" step="0.01">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_dress_deposit">ราคามัดจำ</label>
                                            <input type="number" class="form-control" name="update_dress_deposit"
                                                id="update_dress_deposit" value="{{ $datadress->dress_deposit }}"
                                                placeholder="กรุณากรอกราคามัดจำ" readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_dress_deposit">ราคาประกันค่าเสียหาย</label>
                                            <input type="number" class="form-control"
                                                name="update_dress_damage_insurance" id="update_dress_damage_insurance"
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




                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_dress_description">คำอธิบายชุด</label>
                                            <textarea name="update_dress_description" id="update_dress_description" class="form-control" rows="3">{{ $datadress->dress_description }}</textarea>
                                        </div>
                                    </div>



                                    <hr>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                            <button type="submit" class="btn btn-success">บันทึก</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>





            {{-- modalแก้ไขเสื้อ+ข้อมูลการวัด --}}
            <div class="modal fade" id="edittotalshirt" role="dialog" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header text-dark" style="background-color: #EAD8C0;">
                            <h5 class="modal-title">แก้ไขข้อมูลเสื้อ</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('admin.updatedressyesshirt', ['id' => $shirtitem->id]) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="container-fluid">
                                    <!-- ข้อมูลชุด -->
                                    <h5 class="mb-4">ข้อมูลเสื้อ</h5>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_shirt_price">ราคา</label>
                                            <input type="number" class="form-control" name="update_shirt_price"
                                                id="update_shirt_price" value="{{ $shirtitem->shirtitem_price }}"
                                                placeholder="กรุณากรอกราคา" min="1" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_shirt_deposit">ราคามัดจำ</label>
                                            <input type="number" class="form-control" name="update_shirt_deposit"
                                                id="update_shirt_deposit" value="{{ $shirtitem->shirtitem_deposit }}"
                                                placeholder="กรุณากรอกราคามัดจำ" min="1" required readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_shirt_damage_insurance">ราคาประกันค่าเสียหาย</label>
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
                                <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                                <button type="submit" class="btn btn-success ">บันทึก</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>


            {{-- modalแก้ไขกระโปรง+ข้อมูลการวัด --}}
            <div class="modal fade" id="edittotalskirt" role="dialog" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header text-dark" style="background-color: #EAD8C0;">
                            <h5 class="modal-title">แก้ไขข้อมูลผ้าถุง</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('admin.updatedressyesskirt', ['id' => $skirtitem->id]) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="container-fluid">
                                    <!-- ข้อมูลชุด -->
                                    <h5 class="mb-4">ข้อมูลผ้าถุง</h5>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_skirt_price">ราคา</label>
                                            <input type="number" class="form-control" name="update_skirt_price"
                                                id="update_skirt_price" value="{{ $skirtitem->skirtitem_price }}"
                                                placeholder="กรุณากรอกราคา" min="1" step="0.01" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_skirt_deposit">ราคามัดจำ</label>
                                            <input type="number" class="form-control" name="update_skirt_deposit"
                                                id="update_skirt_deposit" value="{{ $skirtitem->skirtitem_deposit }}"
                                                placeholder="กรุณากรอกราคามัดจำ" step="0.01" required readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_skirt_deposit">ราคาประกันค่าเสียหาย</label>
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
                                <button type="button" data-dismiss="modal" class="btn btn-danger">ยกเลิก</button>
                                <button type="submit" class="btn btn-success">บันทึก</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>






        </div>

        <!--modal เพิ่มรูปภาพ-->
        <div class="modal fade" id="modaladdimage" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">เพิ่มรูปภาพ</div>
                    <form action="{{ route('admin.addimage', ['id' => $datadress->id]) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="addimage">รูปภาพ:</label>
                                <input type="file" class="form-control" name="addimage" id="addimage" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                            <button type="submit" class="btn btn-success">ยืนยัน</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- modalเพิ่มข้อมูลการวัด -->
        <div class="modal fade" id="add_mea_shirt" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form action="{{ route('admin.addmeasumentyesshirt', ['id' => $shirtitem->id]) }}" method="POST">
                        @csrf
                        <div class="modal-header text-dark" style="background-color:#EAD8C0 ;">
                            <h5 class="modal-title">เพิ่มข้อมูลการวัดเสื้อ</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class=" "><i class="bi bi-pencil-square text-dark"></i>ข้อมูลการวัด</h5>

                                    <button class="btn btn-success" type="button" id="add_measurement_shirt">
                                        <i class="bi bi-plus"></i> เพิ่มการวัด
                                    </button>
                                </div>
                                {{-- list_check_name_shirt --}}
                                <input type="hidden" name="dress_id" value="{{ $datadress->id }}">
                                <div id="aria_show_add_mea_input">

                                    <div class="row mb-3" id>
                                        <div class="col-md-3">
                                            {{-- <input type="text" class="form-control" name="add_mea_now_name_[1]"
                                                placeholder="ชื่อการวัด" > --}}
                                            <select class="form-control" name="add_mea_now_name_[1]" required>
                                                <option value="" disabled selected>เลือกรายการ</option>
                                                <option value="ยาวหน้า"
                                                    @if (in_array('ยาวหน้า', $list_check_name_shirt)) style="display: none;" @endif>ยาวหน้า
                                                </option>
                                                <option value="ยาวหลัง"
                                                    @if (in_array('ยาวหลัง', $list_check_name_shirt)) style="display: none;" @endif>ยาวหลัง
                                                </option>
                                                <option value="ไหล่กว้าง"
                                                    @if (in_array('ไหล่กว้าง', $list_check_name_shirt)) style="display: none;" @endif>
                                                    ไหล่กว้าง</option>
                                                <option value="บ่าหน้า"
                                                    @if (in_array('บ่าหน้า', $list_check_name_shirt)) style="display: none;" @endif>บ่าหน้า
                                                </option>
                                                <option value="บ่าหลัง"
                                                    @if (in_array('บ่าหลัง', $list_check_name_shirt)) style="display: none;" @endif>บ่าหลัง
                                                </option>
                                                <option value="รอบคอ"
                                                    @if (in_array('รอบคอ', $list_check_name_shirt)) style="display: none;" @endif>รอบคอ
                                                </option>
                                                <option value="รักแร้"
                                                    @if (in_array('รักแร้', $list_check_name_shirt)) style="display: none;" @endif>รักแท้
                                                </option>
                                                <option value="รอบอก"
                                                    @if (in_array('รอบอก', $list_check_name_shirt)) style="display: none;" @endif>รอบอก
                                                </option>
                                                <option value="อกห่าง"
                                                    @if (in_array('อกห่าง', $list_check_name_shirt)) style="display: none;" @endif>อกห่าง
                                                </option>
                                                <option value="อกสูง"
                                                    @if (in_array('อกสูง', $list_check_name_shirt)) style="display: none;" @endif>อกสูง
                                                </option>
                                                <option value="แขนยาว"
                                                    @if (in_array('แขนยาว', $list_check_name_shirt)) style="display: none;" @endif>แขนยาว
                                                </option>
                                                <option value="แขนกว้าง"
                                                    @if (in_array('แขนกว้าง', $list_check_name_shirt)) style="display: none;" @endif>
                                                    แขนกว้าง</option>
                                                <option value="เสื้อยาว"
                                                    @if (in_array('เสื้อยาว', $list_check_name_shirt)) style="display: none;" @endif>
                                                    เสื้อยาว</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="form-control" name="add_mea_now_number_[1]"
                                                placeholder="ค่าการวัด" min="0" max="100" step="0.01"
                                                required>
                                        </div>
                                        <div class="col-md-3">
                                            <p>นิ้ว</p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                            <button type="submit" class="btn btn-success">บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            var add_measurement = document.getElementById('add_measurement_shirt');
            var aria_show_add_mea_input = document.getElementById('aria_show_add_mea_input');
            var count_add_mea = 1;
            add_measurement.addEventListener('click', function() {
                count_add_mea++;

                var div = document.createElement('div');
                div.className = 'row mb-3';
                div.id = 'row_add_measurement' + count_add_mea;


                input =


                    '<div class="col-md-3">' +
                    '<select class="form-control" name="add_mea_now_name_[' + count_add_mea + ']">' +
                    '<option value="" disabled selected>เลือกรายการ</option>' +
                    '<option value="ยาวหน้า" @if (in_array('ยาวหน้า', $list_check_name_shirt)) style="display: none;" @endif>ยาวหน้า</option>' +
                    '<option value="ยาวหลัง" @if (in_array('ยาวหลัง', $list_check_name_shirt)) style="display: none;" @endif>ยาวหลัง</option>' +
                    '<option value="ไหล่กว้าง" @if (in_array('ไหล่กว้าง', $list_check_name_shirt)) style="display: none;" @endif>ไหล่กว้าง</option>' +
                    '<option value="บ่าหน้า" @if (in_array('บ่าหน้า', $list_check_name_shirt)) style="display: none;" @endif>บ่าหน้า</option>' +
                    '<option value="บ่าหลัง" @if (in_array('บ่าหลัง', $list_check_name_shirt)) style="display: none;" @endif>บ่าหลัง</option>' +
                    '<option value="รอบคอ" @if (in_array('รอบคอ', $list_check_name_shirt)) style="display: none;" @endif>รอบคอ</option>' +
                    '<option value="รักแท้" @if (in_array('รักแท้', $list_check_name_shirt)) style="display: none;" @endif>รักแท้</option>' +
                    '<option value="รอบอก" @if (in_array('รอบอก', $list_check_name_shirt)) style="display: none;" @endif>รอบอก</option>' +
                    '<option value="อกห่าง" @if (in_array('อกห่าง', $list_check_name_shirt)) style="display: none;" @endif>อกห่าง</option>' +
                    '<option value="อกสูง" @if (in_array('อกสูง', $list_check_name_shirt)) style="display: none;" @endif>อกสูง</option>' +
                    '<option value="แขนยาว" @if (in_array('แขนยาว', $list_check_name_shirt)) style="display: none;" @endif>แขนยาว</option>' +
                    '<option value="แขนกว้าง" @if (in_array('แขนกว้าง', $list_check_name_shirt)) style="display: none;" @endif>แขนกว้าง</option>' +
                    '<option value="เสื้อยาว" @if (in_array('เสื้อยาว', $list_check_name_shirt)) style="display: none;" @endif>เสื้อยาว</option>' +
                    '</select>' +
                    '</div>' +
                    '<div class="col-md-3">' +
                    '<input type="number" class="form-control" name="add_mea_now_number_[' + count_add_mea +
                    ']" placeholder="ค่าการวัด" min="0" max="100" step="0.01" required>' +
                    '</div>' +
                    '<div class="col-md-1">' +
                    '<p>นิ้ว</p>' +
                    '</div>' +
                    '<div class="input-group-append">' +
                    '<button class="btn btn-danger remove-measurement" onclick="remove_add_mea_now(' + count_add_mea +
                    ')" type="button">ลบ</button>' +
                    '</div>';
                div.innerHTML = input;
                aria_show_add_mea_input.appendChild(div);
            });

            function remove_add_mea_now(count_add_mea) {
                var delete_add_mea_now = document.getElementById('row_add_measurement' + count_add_mea);
                delete_add_mea_now.remove();
            }
        </script>





        <!-- modalเพิ่มข้อมูลการวัด -->
        <div class="modal fade" id="add_mea_skirt" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form action="{{ route('admin.addmeasumentyesskirt', ['id' => $skirtitem->id]) }}" method="POST">
                        @csrf
                        <div class="modal-header text-dark" style="background-color: #EAD8C0;">
                            <h5 class="modal-title">เพิ่มข้อมูลการวัดกระโปรง</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="mb-0"><i class="bi bi-pencil-square text-dark"></i>ข้อมูลการวัด</h5>
                                    {{-- <button class="btn btn-success" type="button" id="add_measurement">
                                        <i class="bi bi-plus"></i> เพิ่มการวัด
                                    </button> --}}
                                </div>
                                <input type="hidden" name="dress_id" value="{{ $datadress->id }}">

                                <div id="aria_show_add_mea_input">

                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <select class="form-control" name="add_mea_now_name_[1]" required>
                                                <option value="" selected disabled>เลือกรายการ</option>
                                                <option value="รอบเอว"
                                                    @if (in_array('รอบเอว', $list_check_name_skirt)) style="display: none;" @endif>รอบเอว
                                                </option>
                                                <option value="รอบสะโพก"
                                                    @if (in_array('รอบสะโพก', $list_check_name_skirt)) style="display: none;" @endif>
                                                    รอบสะโพก
                                                </option>
                                                <option value="กระโปรงยาว"
                                                    @if (in_array('กระโปรงยาว', $list_check_name_skirt)) style="display: none;" @endif>
                                                    กระโปรงยาว</option>
                                                <option value="ต้นขา"
                                                    @if (in_array('ต้นขา', $list_check_name_skirt)) style="display: none;" @endif>ต้นขา
                                                </option>
                                                <option value="ปลายขา"
                                                    @if (in_array('ปลายขา', $list_check_name_skirt)) style="display: none;" @endif>ปลายขา
                                                </option>
                                                <option value="เป้า"
                                                    @if (in_array('เป้า', $list_check_name_skirt)) style="display: none;" @endif>เป้า
                                                </option>
                                                <option value="กางเกงยาว"
                                                    @if (in_array('กางเกงยาว', $list_check_name_skirt)) style="display: none;" @endif>
                                                    กางเกงยาว</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="form-control" name="add_mea_now_number_[1]"
                                                placeholder="ค่าการวัด" min="1" max="100" step="0.01"
                                                required>
                                        </div>
                                        <div class="col-md-3">
                                            <p>นิ้ว</p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                            <button type="submit" class="btn btn-success">บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            var add_measurement_skirt = document.getElementById('add_measurement');
            var aria_show_add_mea_input = document.getElementById('aria_show_add_mea_input');
            var count_add_mea = 1;
            add_measurement_skirt.addEventListener('click', function() {
                count_add_mea++;
                var div = document.createElement('div');
                div.className = 'row mb-3';
                div.id = 'row_add_measurement' + count_add_mea;

                input =

                    '<div class="col-md-3">' +
                    '<input type="text" class="form-control" name="add_mea_now_name_[' + count_add_mea +
                    ']" placeholder="ชื่อการวัด">' +
                    '</div>' +
                    '<div class="col-md-3">' +
                    '<input type="number" class="form-control" name="add_mea_now_number_[' + count_add_mea +
                    ']" placeholder="หมายเลขการวัด">' +
                    '</div>' +
                    '<div class="col-md-3">' +
                    '<select class="form-control" name="add_mea_now_unit_[' + count_add_mea + ']">' +
                    '<option value="นิ้ว" selected>นิ้ว</option>' +
                    '<option value="เซนติเมตร">เซนติเมตร</option>' +
                    '<option value="มิลลิเมตร">มิลลิเมตร</option>' +
                    '</select>' +
                    '</div>' +
                    '<div class="input-group-append">' +
                    '<button class="btn btn-danger remove-measurement" onclick="remove_add_mea_now(' + count_add_mea +
                    ')" type="button"><i class="bi bi-trash"></i>ลบ</button>' +
                    '</div>';
                div.innerHTML = input;
                aria_show_add_mea_input.appendChild(div);
            });

            function remove_add_mea_now(count_add_mea) {
                var delete_add_mea_now = document.getElementById('row_add_measurement' + count_add_mea);
                delete_add_mea_now.remove();
            }
        </script>



        <!-- ข้อความแจ้งเตือน -->
        <div class="modal fade" id="showsuccessss" role="dialog" aria-hidden="true">
            <div class="modal-dialog custom-modal-dialog">
                <div class="modal-content custom-modal-content">
                    <div class="modal-body">{{ session('success') }}</div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="showfail" role="dialog" aria-hidden="true">
            <div class="modal-dialog custom-modal-dialog">
                <div class="modal-content custom-modal-content fail">
                    <div class="modal-body">{{ session('fail') }}</div>
                </div>
            </div>
        </div>

        <script>
            @if (session('success'))
                setTimeout(function() {
                    $('#showsuccessss').modal('show');
                    setTimeout(function() {
                        $('#showsuccessss').modal('hide');
                    }, 6000);
                }, 500);
            @endif
            @if (session('fail'))
                setTimeout(function() {
                    $('#showfail').modal('show');
                    setTimeout(function() {
                        $('#showfail').modal('hide');
                    }, 6000);
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
