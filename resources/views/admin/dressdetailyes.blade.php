@extends('layouts.adminlayout')
@section('content')
    <style>
        .table-container {
            height: 400px;
            overflow-y: scroll;
        }

        .table::-webkit-scrollbar {
            width: 10px;
            height: 8px;
        }

        .table::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 5px;
        }

        .table::-webkit-scrollbar-track {
            background: #ccc;
        }

        .modal-dialog.custom-modal-dialog {
            max-width: 300px;
            margin: auto;
        }

        .modal-content.custom-modal-content {
            height: 50px;
            width: 100%;
            background-color: #53b007;
        }

        .modal-content.custom-modal-content.fail {
            background-color: #db430c;
        }

        .modal-body {
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-header {
            background-color: #f8f9fa;
            font-weight: bold;
        }
    </style>

    <div class="container my-5">


        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-info-circle"></i>ภาพชุด
                <button class="btn btn-link p-0 ml-2 float-right" data-toggle="modal" data-target="#modaladdimage">
                    <i class="bi bi-plus-square text-primary"></i>เพิ่มรูปภาพ</i>
                </button>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap">
                    @foreach ($imagedata as $image)
                        <div class="p-2">
                            <img src="{{ asset('storage/' . $image->dress_image) }}" alt=""
                                style="max-height: 200px; width: auto;">
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

        {{-- ต้องการสร้างปุม button หรือ สร้าง nav nav-tabs ให้หน่อยสิ แบบ มี 3 อย่างคือ ข้อมูลชุด ข้อมูลเสื้อ ข้อมูลกางเกง --}}

        <div class="card mb-4">
            {{-- <div class="card-header">
                <i class="bi bi-info-circle"></i> รายละเอียดชุด
                <button class="btn btn-link p-0 ml-2 float-right" data-toggle="modal" data-target="#edittotal">
                    <i class="bi bi-pencil-square text-primary">แก้ไข</i>
                </button>
            </div> --}}

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
                        aria-selected="false">ข้อมูลกางเกง</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <!-- ข้อมูลชุด -->
                <div class="tab-pane fade show active" id="dress" role="tabpanel" aria-labelledby="dress-tab">
                    <div class="card-header">
                        <i class="bi bi-info-circle"></i> รายละเอียดชุด
                        <button class="btn btn-link p-0 ml-2 float-right" data-toggle="modal" data-target="#edittotal">
                            <i class="bi bi-pencil-square text-primary">แก้ไข</i>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>ประเภทชุด:</strong> {{ $name_type }}</p>
                                <p><strong>รหัสชุด:</strong> {{ $datadress->dress_code_new }}{{ $datadress->dress_code }}
                                </p>
                                <p><strong>ราคา:</strong> {{ number_format($datadress->dress_price, 2) }} บาท</p>
                                <p><strong>ราคามัดจำ:</strong> {{ number_format($datadress->dress_deposit, 2) }} บาท</p>
                                <p><strong>ราคาประกันค่าเสียหาย:</strong> {{ number_format($datadress->damage_insurance,2) }} บาท</p>

                            </div>
                            <div class="col-md-6">
                                <p><strong>จำนวนชุด:</strong> {{ $datadress->dress_count }} ชุด</p>
                                <p><strong>สถานะชุด:</strong> <span
                                        style="color: red;">{{ $datadress->dress_status }}</span></p>
                                <p><strong>จำนวนครั้งที่ถูกเช่า:</strong> {{ $datadress->dress_rental }} ครั้ง</p>
                                <p><strong>ชุด:</strong>
                                    @if ($datadress->separable == 1)
                                        <i class="bi bi-x-circle-fill text-danger"></i> ไม่สามารถเช่าแยกได้
                                    @elseif($datadress->separable == 2)
                                        <i class="bi bi-check-circle-fill text-success"></i> สามารถเช่าแยกได้
                                    @endif
                                </p>
                                <p><strong>คำอธิบายชุด:</strong> {{ $datadress->dress_description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ข้อมูลเสื้อ -->
                <div class="tab-pane fade" id="shirt" role="tabpanel" aria-labelledby="shirt-tab">
                    <div class="card-header">
                        <i class="bi bi-info-circle"></i> รายละเอียดชุด
                        <button class="btn btn-link p-0 ml-2 float-right" data-toggle="modal" data-target="#edittotalshirt">
                            <i class="bi bi-pencil-square text-primary">แก้ไข</i>
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- เพิ่มข้อมูลเสื้อที่นี่ -->
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>ราคา:</strong> {{ number_format($shirtitem->shirtitem_price, 2) }} บาท</p>
                                <p><strong>ราคามัดจำ:</strong> {{ number_format($shirtitem->shirtitem_deposit, 2) }} บาท</p>
                                <p><strong>ราคาประกันค่าเสียหาย:</strong> {{ number_format($shirtitem->shirt_damage_insurance, 2) }} บาท</p>

                            </div>
                            <div class="col-md-6">
                                <p><strong>จำนวนเสื้อ:</strong> 1 ตัว</p>
                                <p><strong>สถานะเสื้อ:</strong> <span
                                        style="color: red;">{{ $shirtitem->shirtitem_status }}</span></p>
                                <p><strong>จำนวนครั้งที่ถูกเช่า:</strong> {{ $shirtitem->shirtitem_rental }} ครั้ง</p>
                            </div>
                        </div>
                        <!-- ข้อมูลการวัดของชุดเริ่มต้น -->
                        <h5 class="mt-4">ขนาดของเสื้อเริ่มต้น</h5>
                        <div>
                            @foreach ($measument_yes_separate_shirt as $measument_yes_separate_shirt)
                                {{ $measument_yes_separate_shirt->measurement_dress_name }}&nbsp;{{ $measument_yes_separate_shirt->measurement_dress_number }}&nbsp;{{ $measument_yes_separate_shirt->measurement_dress_unit }}
                            @endforeach
                        </div>
                        <!-- ข้อมูลการวัดของชุดล่าสุด -->
                        <h5 class="mt-4">ขนาดของเสื้อล่าสุด
                            <button class="btn btn-link p-0 ml-2" data-toggle="modal" data-target="#add_mea_shirt">
                                <i class="bi bi-plus-square text-primary"></i> เพิ่มข้อมูลการวัด
                            </button>

                        </h5>
                        <div>
                            @foreach ($measument_yes_separate_now_shirt as $measument_yes_separate_now_shirt)
                                {{ $measument_yes_separate_now_shirt->measurementnow_dress_name }}&nbsp;{{ $measument_yes_separate_now_shirt->measurementnow_dress_number }}&nbsp;{{ $measument_yes_separate_now_shirt->measurementnow_dress_unit }}
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- ข้อมูลกางเกง -->
                <div class="tab-pane fade" id="pants" role="tabpanel" aria-labelledby="pants-tab">
                    <div class="card-header">
                        <i class="bi bi-info-circle"></i> รายละเอียดชุด
                        <button class="btn btn-link p-0 ml-2 float-right" data-toggle="modal" data-target="#edittotalskirt">
                            <i class="bi bi-pencil-square text-primary">แก้ไข</i>
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- เพิ่มข้อมูลกางเกงที่นี่ -->
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>ราคา:</strong> {{ number_format($skirtitem->skirtitem_price, 2) }} บาท</p>
                                <p><strong>ราคามัดจำ:</strong> {{ number_format($skirtitem->skirtitem_deposit, 2) }} บาท</p>
                                <p><strong>ราคาประกันค่าเสียหาย:</strong> {{ number_format($skirtitem->skirt_damage_insurance, 2) }} บาท</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>จำนวนกระโปรง/กางเกง:</strong> 1 ตัว</p>
                                <p><strong>สถานะกระโปรง/กางเกง:</strong> <span
                                        style="color: red;">{{ $skirtitem->skirtitem_status }}</span></p>
                                <p><strong>จำนวนครั้งที่ถูกเช่า:</strong> {{ $skirtitem->skirtitem_rental }} ครั้ง</p>
                            </div>
                        </div>
                        <!-- ข้อมูลการวัดของชุดเริ่มต้น -->
                        <h5 class="mt-4">ขนาดของกระโปรง/กางเกงเริ่มต้น</h5>
                        <div>
                            @foreach ($measument_yes_separate_skirt as $measument_yes_separate_skirt)
                                {{ $measument_yes_separate_skirt->measurement_dress_name }}&nbsp;{{ $measument_yes_separate_skirt->measurement_dress_number }}&nbsp;{{ $measument_yes_separate_skirt->measurement_dress_unit }}
                            @endforeach
                        </div>
                        <!-- ข้อมูลการวัดของชุดล่าสุด -->
                        <h5 class="mt-4">ขนาดของกระโปรง/กางเกงล่าสุด
                            <button class="btn btn-link p-0 ml-2" data-toggle="modal" data-target="#add_mea_skirt">
                                <i class="bi bi-plus-square text-primary"></i> เพิ่มข้อมูลการวัด
                            </button>
                        </h5>
                        <div>
                            @foreach ($measument_yes_separate_now_skirt as $measument_yes_separate_now_skirt)
                                {{ $measument_yes_separate_now_skirt->measurementnow_dress_name }}&nbsp;{{ $measument_yes_separate_now_skirt->measurementnow_dress_number }}&nbsp;{{ $measument_yes_separate_now_skirt->measurementnow_dress_unit }}
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- modalแก้ไขชุด --}}
        <div class="modal fade" id="edittotal" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">แก้ไขข้อมูลชุด</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
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
                                            placeholder="กรุณากรอกราคา">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="update_dress_deposit">ราคามัดจำ</label>
                                        <input type="number" class="form-control" name="update_dress_deposit"
                                            id="update_dress_deposit" value="{{ $datadress->dress_deposit }}"
                                            placeholder="กรุณากรอกราคามัดจำ">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="update_dress_deposit">ราคาประกันค่าเสียหาย</label>
                                        <input type="number" class="form-control" name="update_dress_damage_insurance"
                                            id="update_dress_damage_insurance" value="{{ $datadress->damage_insurance }}"
                                            placeholder="กรุณากรอกราคาประกันค่าเสียหาย">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="dress_status">สถานะชุด</label>
                                        <select name="update_dress_status" id="update_dress_status" class="form-control">
                                            <option value="พร้อมให้เช่า"
                                                {{ $datadress->dress_status == 'พร้อมให้เช่า' ? 'selected' : '' }}>
                                                พร้อมให้เช่า</option>
                                            <option value="ถูกจองแล้ว"
                                                {{ $datadress->dress_status == 'ถูกจองแล้ว' ? 'selected' : '' }}>ถูกจองแล้ว
                                            </option>
                                            <option value="กำลังเช่า"
                                                {{ $datadress->dress_status == 'กำลังเช่า' ? 'selected' : '' }}>กำลังเช่า
                                            </option>
                                            <option value="ส่งทำความสะอาด"
                                                {{ $datadress->dress_status == 'ส่งทำความสะอาด' ? 'selected' : '' }}>
                                                ส่งทำความสะอาด</option>
                                            <option value="ซ่อมแซม"
                                                {{ $datadress->dress_status == 'ซ่อมแซม' ? 'selected' : '' }}>ซ่อมแซม
                                            </option>
                                            <option value="เลิกให้เช่า"
                                                {{ $datadress->dress_status == 'เลิกให้เช่า' ? 'selected' : '' }}>
                                                เลิกให้เช่า</option>
                                            <option value="สูญหาย"
                                                {{ $datadress->dress_status == 'สูญหาย' ? 'selected' : '' }}>สูญหาย
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="update_dress_description">คำอธิบายชุด</label>
                                        <textarea name="update_dress_description" id="update_dress_description" class="form-control" rows="3">{{ $datadress->dress_description }}</textarea>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                            </form>

                            <hr>


                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- modalแก้ไขเสื้อ+ข้อมูลการวัด --}}
        <div class="modal fade" id="edittotalshirt" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">แก้ไขข้อมูลเสื้อ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <!-- ข้อมูลชุด -->
                            <h5 class="mb-4">ข้อมูลเสื้อ</h5>

                            <form action="{{ route('admin.updatedressyesshirt', ['id' => $shirtitem->id]) }}"
                                method="POST">
                                @csrf
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
                                            placeholder="กรุณากรอกราคามัดจำ" min="1" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="update_shirt_deposit">ราคาประกันค่าเสียหาย</label>
                                        <input type="number" class="form-control" name="update_shirt_damage_insurance"
                                            id="update_shirt_damage_insurance" value="{{ $shirtitem->shirt_damage_insurance }}"
                                            placeholder="กรุณากรอกราคาประกันค่าเสียหาย" min="0" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="update_shirt_status">สถานะชุด</label>
                                        <select name="update_shirt_status" id="update_shirt_status" class="form-control">
                                            <option value="พร้อมให้เช่า"
                                                {{ $shirtitem->shirtitem_status == 'พร้อมให้เช่า' ? 'selected' : '' }}>
                                                พร้อมให้เช่า</option>
                                            <option value="ถูกจองแล้ว"
                                                {{ $shirtitem->shirtitem_status == 'ถูกจองแล้ว' ? 'selected' : '' }}>
                                                ถูกจองแล้ว
                                            </option>
                                            <option value="กำลังเช่า"
                                                {{ $shirtitem->shirtitem_status == 'กำลังเช่า' ? 'selected' : '' }}>
                                                กำลังเช่า
                                            </option>
                                            <option value="ส่งทำความสะอาด"
                                                {{ $shirtitem->shirtitem_status == 'ส่งทำความสะอาด' ? 'selected' : '' }}>
                                                ส่งทำความสะอาด</option>
                                            <option value="ซ่อมแซม"
                                                {{ $shirtitem->shirtitem_status == 'ซ่อมแซม' ? 'selected' : '' }}>ซ่อมแซม
                                            </option>
                                            <option value="เลิกให้เช่า"
                                                {{ $shirtitem->shirtitem_status == 'เลิกให้เช่า' ? 'selected' : '' }}>
                                                เลิกให้เช่า</option>
                                            <option value="สูญหาย"
                                                {{ $shirtitem->shirtitem_status == 'สูญหาย' ? 'selected' : '' }}>สูญหาย
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <!-- ข้อมูลการวัด -->
                                <h5 class="mb-4">ขนาดของชุด</h5>

                                @foreach ($measument_yes_separate_now_shirt_modal as $measument_yes_separate_now_shirt_modal)
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <input type="hidden" name="mea_now_id_[]"
                                                value="{{ $measument_yes_separate_now_shirt_modal->id }}">
                                            <input type="text" class="form-control" name="mea_now_name_[]"
                                                value="{{ $measument_yes_separate_now_shirt_modal->measurementnow_dress_name }}"
                                                placeholder="ชื่อการวัด" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="number" class="form-control" name="mea_now_number_[]"
                                                value="{{ $measument_yes_separate_now_shirt_modal->measurementnow_dress_number }}"
                                                placeholder="หมายเลขการวัด">
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-control" name="mea_now_unit_[]">
                                                <option value="นิ้ว"
                                                    {{ $measument_yes_separate_now_shirt_modal->measurementnow_dress_number == 'นิ้ว' ? 'selected' : '' }}>
                                                    นิ้ว</option>
                                                <option value="เซนติเมตร"
                                                    {{ $measument_yes_separate_now_shirt_modal->measurementnow_dress_number == 'เซนติเมตร' ? 'selected' : '' }}>
                                                    เซนติเมตร</option>
                                                <option value="มิลลิเมตร"
                                                    {{ $measument_yes_separate_now_shirt_modal->measurementnow_dress_number == 'มิลลิเมตร' ? 'selected' : '' }}>
                                                    มิลลิเมตร</option>
                                            </select>
                                        </div>
                                    </div>
                                @endforeach


                                <button type="submit" class="btn btn-primary">บันทึก</button>
                            </form>

                            <hr>


                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        {{-- modalแก้ไขกระโปรง+ข้อมูลการวัด --}}
        <div class="modal fade" id="edittotalskirt" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">แก้ไขข้อมูลกระโปรง/กางเกง</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <!-- ข้อมูลชุด -->
                            <h5 class="mb-4">ข้อมูลกระโปรง/กางเกง</h5>

                            <form action="{{ route('admin.updatedressyesskirt', ['id' => $skirtitem->id]) }}"
                                method="POST">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="update_skirt_price">ราคา</label>
                                        <input type="number" class="form-control" name="update_skirt_price"
                                            id="update_skirt_price" value="{{ $skirtitem->skirtitem_price }}"
                                            placeholder="กรุณากรอกราคา">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="update_skirt_deposit">ราคามัดจำ</label>
                                        <input type="number" class="form-control" name="update_skirt_deposit"
                                            id="update_skirt_deposit" value="{{ $skirtitem->skirtitem_deposit }}"
                                            placeholder="กรุณากรอกราคามัดจำ">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="update_skirt_deposit">ราคาประกันค่าเสียหาย</label>
                                        <input type="number" class="form-control" name="update_skirt_damage_insurance"
                                            id="update_skirt_damage_insurance" value="{{ $skirtitem->skirt_damage_insurance	 }}"
                                            placeholder="กรุณากรอกราคามัดจำ">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="update_skirt_status">สถานะชุด</label>
                                        <select name="update_skirt_status" id="update_skirt_status" class="form-control">
                                            <option value="พร้อมให้เช่า"
                                                {{ $skirtitem->skirtitem_status == 'พร้อมให้เช่า' ? 'selected' : '' }}>
                                                พร้อมให้เช่า</option>
                                            <option value="ถูกจองแล้ว"
                                                {{ $skirtitem->skirtitem_status == 'ถูกจองแล้ว' ? 'selected' : '' }}>
                                                ถูกจองแล้ว
                                            </option>
                                            <option value="กำลังเช่า"
                                                {{ $skirtitem->skirtitem_status == 'กำลังเช่า' ? 'selected' : '' }}>
                                                กำลังเช่า
                                            </option>
                                            <option value="ส่งทำความสะอาด"
                                                {{ $skirtitem->skirtitem_status == 'ส่งทำความสะอาด' ? 'selected' : '' }}>
                                                ส่งทำความสะอาด</option>
                                            <option value="ซ่อมแซม"
                                                {{ $skirtitem->skirtitem_status == 'ซ่อมแซม' ? 'selected' : '' }}>ซ่อมแซม
                                            </option>
                                            <option value="เลิกให้เช่า"
                                                {{ $skirtitem->skirtitem_status == 'เลิกให้เช่า' ? 'selected' : '' }}>
                                                เลิกให้เช่า</option>
                                            <option value="สูญหาย"
                                                {{ $skirtitem->skirtitem_status == 'สูญหาย' ? 'selected' : '' }}>สูญหาย
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <!-- ข้อมูลการวัด -->
                                <h5 class="mb-4">ขนาดของกระโปรง/กางเกง</h5>

                                @foreach ($measument_yes_separate_now_skirt_modal as $measument_yes_separate_now_skirt_modal)
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <input type="hidden" name="mea_now_id_[]"
                                                value="{{ $measument_yes_separate_now_skirt_modal->id }}">
                                            <input type="text" class="form-control" name="mea_now_name_[]"
                                                value="{{ $measument_yes_separate_now_skirt_modal->measurementnow_dress_name }}"
                                                placeholder="ชื่อการวัด" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="number" class="form-control" name="mea_now_number_[]"
                                                value="{{ $measument_yes_separate_now_skirt_modal->measurementnow_dress_number }}"
                                                placeholder="หมายเลขการวัด">
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-control" name="mea_now_unit_[]">
                                                <option value="นิ้ว"
                                                    {{ $measument_yes_separate_now_skirt_modal->measurementnow_dress_number == 'นิ้ว' ? 'selected' : '' }}>
                                                    นิ้ว</option>
                                                <option value="เซนติเมตร"
                                                    {{ $measument_yes_separate_now_skirt_modal->measurementnow_dress_number == 'เซนติเมตร' ? 'selected' : '' }}>
                                                    เซนติเมตร</option>
                                                <option value="มิลลิเมตร"
                                                    {{ $measument_yes_separate_now_skirt_modal->measurementnow_dress_number == 'มิลลิเมตร' ? 'selected' : '' }}>
                                                    มิลลิเมตร</option>
                                            </select>
                                        </div>
                                    </div>
                                @endforeach


                                <button type="submit" class="btn btn-primary">บันทึก</button>
                            </form>
                            <hr>
                        </div>
                    </div>
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
                        <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-secondary">ยืนยัน</button>
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
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">เพิ่มข้อมูลการวัด</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="mb-0"><i class="bi bi-pencil-square text-primary"></i>ข้อมูลการวัด</h5>
                                <button class="btn btn-outline-secondary" type="button" id="add_measurement">
                                    <i class="bi bi-plus"></i> เพิ่มการวัด
                                </button>
                            </div>

                            <div id="aria_show_add_mea_input">

                                <div class="row mb-3" id>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" name="add_mea_now_name_[1]"
                                            placeholder="ชื่อการวัด">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control" name="add_mea_now_number_[1]"
                                            placeholder="หมายเลขการวัด">
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="add_mea_now_unit_[1]">
                                            <option value="นิ้ว" selected>นิ้ว</option>
                                            <option value="เซนติเมตร">เซนติเมตร</option>
                                            <option value="มิลลิเมตร">มิลลิเมตร</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var add_measurement = document.getElementById('add_measurement');
        var aria_show_add_mea_input = document.getElementById('aria_show_add_mea_input');
        var count_add_mea = 1;
        add_measurement.addEventListener('click', function() {
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





    <!-- modalเพิ่มข้อมูลการวัด -->
    <div class="modal fade" id="add_mea_skirt" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.addmeasumentyesskirt', ['id' => $skirtitem->id]) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">เพิ่มข้อมูลการวัด</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="mb-0"><i class="bi bi-pencil-square text-primary"></i>ข้อมูลการวัด</h5>
                                <button class="btn btn-outline-secondary" type="button" id="add_measurement">
                                    <i class="bi bi-plus"></i> เพิ่มการวัด
                                </button>
                            </div>

                            <div id="aria_show_add_mea_input">

                                <div class="row mb-3" >
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" name="add_mea_now_name_[1]"
                                            placeholder="ชื่อการวัด">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control" name="add_mea_now_number_[1]"
                                            placeholder="หมายเลขการวัด">
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="add_mea_now_unit_[1]">
                                            <option value="นิ้ว" selected>นิ้ว</option>
                                            <option value="เซนติเมตร">เซนติเมตร</option>
                                            <option value="มิลลิเมตร">มิลลิเมตร</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var add_measurement = document.getElementById('add_measurement');
        var aria_show_add_mea_input = document.getElementById('aria_show_add_mea_input');
        var count_add_mea = 1;
        add_measurement.addEventListener('click', function() {
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
@endsection
