{{-- @extends('layouts.adminlayout')
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
    </style>
    <div class="container d-flex justify-content-start">


      

        <div class="modal fade" id="showsuccessss" role="dialog" aria-hidden="true">
            <div class="modal-dialog custom-modal-dialog" role="document">
                <div class="modal-content custom-modal-content"
                    style="max-width: 300px; height: 50px; width: 100%; margin: auto; background-color: #53b007;">
                    <div class="modal-body"
                        style="padding: 10px; display: flex; align-items: center; justify-content: center;">
                        <p style="margin: 0; color: #ffffff;">{{ session('success') }}</p>
                    </div>
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
        </script>



        <div class="modal fade" id="showfail" role="dialog" aria-hidden="true">
            <div class="modal-dialog custom-modal-dialog" role="document">
                <div class="modal-content custom-modal-content"
                    style="max-width: 300px; height: 50px; width: 100%; margin: auto; background-color: #db430c;">
                    <div class="modal-body"
                        style="padding: 10px; display: flex; align-items: center; justify-content: center;">
                        <p style="margin: 0; color: #ffffff;">{{ session('fail') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <script>
            @if (session('fail'))
                setTimeout(function() {
                    $('#showfail').modal('show');
                    setTimeout(function() {
                        $('#showfail').modal('hide');
                    }, 6000);
                }, 500);
            @endif
        </script>

        <div>

        </div>



        <div class="table-responsive text-start" style="width: 100%;">





            @foreach ($imagedata as $imagedata)
                <img src="{{ asset('storage/' . $imagedata->dress_image) }}" alt=""
                    style="max-height: 300px; width: auto;">
            @endforeach

            <button class="btn btn-secondary" type="button" data-toggle="modal"
                data-target="#modaladdimage">เพิ่มรูปภาพ</button>

            <!-- modalเพิ่มรูปภาพ -->
            <div class="modal fade" id="modaladdimage" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            เพิ่มรูปภาพ?
                        </div>
                        <form action="{{ route('admin.addimage', ['id' => $datadress->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">

                                <p>เพิ่มข้อมูลการวัด</p>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-md-4"><strong>รูปภาพ:</strong></div>
                                    <div class="col-md-8">
                                        <input type="file" class="form-control" name="addimage" id="addimage" required>

                                    </div>
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



            <h2 class="text text-start pt-5 ">รายละเอียดชุด</h2>
            <div class=”grid-container”>
                <div class="card mb-3 border-2" style="max-width: 1080px;">
                    <div class="row g-0">
                        <div class="col-md-4">


                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <button type="button" class="btn btn-danger" data-toggle="modal"
                                    data-target="#showeditdress">แก้ไข</button>
                                <button type="button" class="btn btn-danger" data-toggle="modal"
                                    data-target="#showeditprice">ปรับเปลี่ยนราคา</button>
                                <p class="card-text">
                                    <span style="font-weight: bold;">ประเภทชุด : </span> {{ $name_type }}
                                </p>

                                <p class="card-text">
                                    <span style="font-weight: bold;">รหัสชุด : </span>{{ $datadress->dress_code_new }}{{ $datadress->dress_code }}
                                </p>

                                <p class="card-text">
                                    <span style="font-weight: bold;">ชื่อชุด : </span> {{ $datadress->dress_title_name }}
                                </p>
                                <p class="card-text">
                                    <span style="font-weight: bold;">สี : </span> {{ $datadress->dress_color }}
                                </p>
                                <p class="card-text">
                                    <span style="font-weight: bold;">ราคา : </span> {{ $datadress->dress_price }}&nbsp;บาท
                                </p>
                                <p class="card-text">
                                    <span style="font-weight: bold;">ราคามัดจำ : </span>
                                    {{ $datadress->dress_deposit }}&nbsp;บาท
                                </p>

                                <p class="card-text">
                                    <span style="font-weight: bold;">จำนวนชุด : </span>
                                    {{ $datadress->dress_count }}&nbsp;ชุด
                                </p>

                                <p class="card-text">
                                    <span style="font-weight: bold;">สถานะชุด : </span> {{ $datadress->dress_status }}
                                </p>

                                <p class="card-text">
                                    <span style="font-weight: bold;">จำนวนครั้งที่ถูกเช่า : </span>
                                    {{ $datadress->dress_rental }} ครั้ง
                                </p>


                                <p class="card-text">
                                    <span style="font-weight: bold;">คำอธิบายชุด : </span>
                                    {{ $datadress->dress_description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- modalแก้ไขชุด -->
                <div class="modal fade" id="showeditdress" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">แก้ไขชุด</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <form action="{{ route('admin.updatedress', ['id' => $datadress->id]) }}" method="POST">
                                @csrf
                                <div class="modal-body">

                                    <div class="mb-3">
                                        <label class="form-label" for="update_dress_type">ประเภทชุด:
                                            {{ $name_type }}</label>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="update_dress_code">หมายเลขชุด:
                                            {{ $datadress->dress_code }}</label>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="update_dress_price">ราคา:
                                            {{ $datadress->dress_price }} บาท</label>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="update_dress_deposit">ราคามัดจำ:
                                            {{ $datadress->dress_deposit }} บาท</label>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="update_dress_count">จำนวนชุด:
                                            {{ $datadress->dress_count }} ชุด</label>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="update_dress_status">สถานะชุด:
                                            {{ $datadress->dress_status }}</label>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="update_dress_rental">จำนวนครั้งที่ถูกเช่า:
                                            {{ $datadress->dress_rental }} ครั้ง</label>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="update_dress_title_name">ชื่อชุด:</label>
                                        <input type="text" class="form-control" id="update_dress_title_name"
                                            name="update_dress_title_name" value="{{ $datadress->dress_title_name }}"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="update_dress_color">สี:</label>
                                        <select class="form-control" id="update_dress_color" name="update_dress_color">
                                            <option value="ขาว"
                                                {{ $datadress->dress_color == 'ขาว' ? 'selected' : '' }}>ขาว</option>
                                            <option value="ครีม"
                                                {{ $datadress->dress_color == 'ครีม' ? 'selected' : '' }}>ครีม</option>
                                            <option value="ชมพู"
                                                {{ $datadress->dress_color == 'ชมพู' ? 'selected' : '' }}>ชมพู</option>
                                            <option value="ดำ"
                                                {{ $datadress->dress_color == 'ดำ' ? 'selected' : '' }}>
                                                ดำ</option>
                                            <option value="ทอง"
                                                {{ $datadress->dress_color == 'ทอง' ? 'selected' : '' }}>ทอง</option>
                                            <option value="น้ำตาล"
                                                {{ $datadress->dress_color == 'น้ำตาล' ? 'selected' : '' }}>น้ำตาล</option>
                                            <option value="น้ำเงิน"
                                                {{ $datadress->dress_color == 'น้ำเงิน' ? 'selected' : '' }}>น้ำเงิน
                                            </option>
                                            <option value="บานเย็น"
                                                {{ $datadress->dress_color == 'บานเย็น' ? 'selected' : '' }}>บานเย็น
                                            </option>
                                            <option value="พิ้งค์โกลด์"
                                                {{ $datadress->dress_color == 'พิ้งค์โกลด์' ? 'selected' : '' }}>
                                                พิ้งค์โกลด์</option>
                                            <option value="ฟ้า"
                                                {{ $datadress->dress_color == 'ฟ้า' ? 'selected' : '' }}>ฟ้า</option>
                                            <option value="ม่วง"
                                                {{ $datadress->dress_color == 'ม่วง' ? 'selected' : '' }}>ม่วง</option>
                                            <option value="ส้ม"
                                                {{ $datadress->dress_color == 'ส้ม' ? 'selected' : '' }}>ส้ม</option>
                                            <option value="เขียว"
                                                {{ $datadress->dress_color == 'เขียว' ? 'selected' : '' }}>เขียว</option>
                                            <option value="เทา"
                                                {{ $datadress->dress_color == 'เทา' ? 'selected' : '' }}>เทา</option>
                                            <option value="เหลือง"
                                                {{ $datadress->dress_color == 'เหลือง' ? 'selected' : '' }}>เหลือง</option>
                                            <option value="แดง"
                                                {{ $datadress->dress_color == 'แดง' ? 'selected' : '' }}>แดง</option>
                                            <option value="ไม่ระบุ"
                                                {{ $datadress->dress_color == 'ไม่ระบุ' ? 'selected' : '' }}>ไม่ระบุ
                                            </option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="update_dress_description">คำอธิบายชุด:</label>
                                        <textarea class="form-control" id="update_dress_description" name="update_dress_description" rows="3"
                                            required>{{ $datadress->dress_description }}</textarea>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger"
                                            data-dismiss="modal">ยกเลิก</button>
                                        <button type="submit" class="btn btn-secondary">ยืนยัน</button>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- modalแก้ไขราคา -->
            <div class="modal fade" id="showeditprice" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            แก้ไขราคา?
                        </div>
                        <form action="{{ route('admin.updateprice', ['id' => $datadress->id]) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label" for="update_dress_price">ราคา:</label>
                                    <input type="number" class="form-control" id="update_dress_price"
                                        name="update_dress_price" value="{{ $datadress->dress_price }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="update_dress_deposit">ราคามัดจำ:</label>
                                    <input type="number" class="form-control" id="update_dress_deposit"
                                        name="update_dress_deposit" value="{{ $datadress->dress_deposit }}" required>
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

            <div style="display: flex; justify-content: start;" class="py-2">
                <button type="button" class="btn btn-success" data-toggle="modal"
                    data-target="#showmodaladdmea">เพิ่มข้อมูลการวัด</button>
            </div>
            <div class="table-container">
                <table class="table table-bordered table-hover text-start">
                    <thead>
                        <tr>
                            <th>meaid</th>
                            <th>ชื่อขนาด</th>
                            <th>ค่าขนาด</th>
                            <th>หน่วยวัด</th>
                            <th>action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($measument as $show)
                            <tr>
                                <td>{{ $show->id }}</td>
                                <td>{{ $show->measurement_dress_name }}</td>
                                <td>{{ $show->measurement_dress_number }}</td>
                                <td>{{ $show->measurement_dress_unit }}</td>
                                <td>
                                    <button type="button" data-toggle="modal"
                                        data-target="#showmodaleditmea{{ $show->id }}">
                                        <img src="{{ asset('images/edit.png') }}" alt="auto" width="20"
                                            height="25">
                                    </button>
                                    <button type="button" data-toggle="modal"
                                        data-target="#showmodaldeletemea{{ $show->id }}">
                                        <img src="{{ asset('images/icondelete.jpg') }}" alt="auto" width="20"
                                            height="25">
                                    </button>
                                </td>
                                <!-- modalแก้ไขข้อมูลการวัด -->
                                <div class="modal fade" id="showmodaleditmea{{ $show->id }}" role="dialog"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">ยืนยันการแก้ข้อมูล</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('admin.updatemeasument', ['id' => $show->id]) }}"
                                                method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="row mb-3">
                                                        <div class="col-md-4"><strong>ชื่อขนาด:</strong></div>
                                                        <div class="col-md-8">
                                                            <p class="form-control-static">
                                                                {{ $show->measurement_dress_name }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-4"><strong>ค่าขนาด:</strong></div>
                                                        <div class="col-md-8">
                                                            <input type="number" class="form-control"
                                                                name="update_measurement_dress_number"
                                                                value="{{ $show->measurement_dress_number }}"
                                                                step="0.01" required>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-4"><strong>หน่วยวัด:</strong></div>
                                                        <div class="col-md-8">
                                                            <select class="form-control"
                                                                name="update_measurement_dress_unit" required>
                                                                <option value="นิ้ว"
                                                                    {{ $show->measurement_dress_unit == 'นิ้ว' ? 'selected' : '' }}>
                                                                    นิ้ว</option>
                                                                <option value="เซนติเมตร"
                                                                    {{ $show->measurement_dress_unit == 'เซนติเมตร' ? 'selected' : '' }}>
                                                                    เซนติเมตร</option>
                                                                <option value="มิลลิเมตร"
                                                                    {{ $show->measurement_dress_unit == 'มิลลิเมตร' ? 'selected' : '' }}>
                                                                    มิลลิเมตร</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-danger" type="button"
                                                        data-dismiss="modal">ยกเลิก</button>
                                                    <button class="btn btn-secondary" type="submit">ยืนยัน</button>
                                                </div>

                                            </form>

                                        </div>
                                    </div>
                                </div>
                                <!-- modalลบข้อมูลการวัด -->
                                <div class="modal fade" id="showmodaldeletemea{{ $show->id }}" role="dialog"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h5 class="modal-title">ยืนยันการลบข้อมูลการวัด</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('admin.deletemeasument', ['id' => $show->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-body">
                                                    <p>แน่ใจหรือว่าต้องการจะลบรายการข้อมูลการวัดนี้</p>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-4"><strong>ชื่อขนาด:</strong></div>
                                                        <div class="col-md-8">{{ $show->measurement_dress_name }}</div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4"><strong>ค่าขนาด:</strong></div>
                                                        <div class="col-md-8">{{ $show->measurement_dress_number }}</div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4"><strong>หน่วยวัด:</strong></div>
                                                        <div class="col-md-8">{{ $show->measurement_dress_unit }}</div>

                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-danger" type="button"
                                                        data-dismiss="modal">ยกเลิก</button>
                                                    <button class="btn btn-secondary" type="submit">ยืนยัน</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- modalเพิ่มข้อมูลการวัด -->
            <div class="modal fade" id="showmodaladdmea" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            เพิ่มข้อมูลการวัด
                        </div>
                        <form action="{{ route('admin.addmeasument', ['id' => $datadress->id]) }}" method="POST">
                            @csrf
                            <div class="modal-body">


                                <p>เพิ่มข้อมูลการวัด</p>
                                <hr>
                                <div class="row mb-3">
                                    <div class="col-md-4"><strong>ชื่อขนาด:</strong></div>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="measurement_dress_name"
                                            placeholder="กรอกชื่อขนาดเช่น รอบสะโพก" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4"><strong>ค่าขนาด:</strong></div>
                                    <div class="col-md-8">
                                        <input type="number" class="form-control" name="measurement_dress_number"
                                            placeholder="กรอกขนาดเช่น 10" min="0" step="0.000001" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4"><strong>หน่วยวัด:</strong></div>
                                    <div class="col-md-8">
                                        <select class="form-control" name="measurement_dress_unit" required>
                                            <option value="นิ้ว" selected>นิ้ว</option>
                                            <option value="เซนติเมตร">เซนติเมตร</option>
                                            <option value="มิลลิเมตร">มิลลิเมตร</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-danger" type="button" data-dismiss="modal">ยกเลิก</button>
                                <button class="btn btn-secondary" type="submit">ยืนยัน</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>



        @endsection --}}
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
        {{-- @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('fail'))
                    <div class="alert alert-danger">{{ session('fail') }}</div>
                @endif --}}

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
                {{-- <button class="btn btn-secondary mt-3" type="button" data-toggle="modal"
                    data-target="#modaladdimage">เพิ่มรูปภาพ</button> --}}
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-info-circle"></i>รายละเอียดชุด
                <button class="btn btn-link p-0 ml-2 float-right" data-toggle="modal" data-target="#edittotal">
                    <i class="bi bi-pencil-square text-primary">แก้ไข</i>
                </button>
            </div>

            


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

                                <form action="{{ route('admin.updatedressnoyes', ['id' => $datadress->id]) }}" method="POST">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_dress_price">ราคา</label>
                                            <input type="number" class="form-control" name="update_dress_price"
                                                id="update_dress_price" value="{{ $datadress->dress_price }}"
                                                placeholder="กรุณากรอกราคา" required min="1">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_dress_deposit">ราคามัดจำ</label>
                                            <input type="number" class="form-control" name="update_dress_deposit"
                                                id="update_dress_deposit" value="{{ $datadress->dress_deposit }}"
                                                placeholder="กรุณากรอกราคามัดจำ" required min="1">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_dress_deposit">ราคาประกันค่าเสียหาย</label>
                                            <input type="number" class="form-control" name="update_damage_insurance"
                                                id="update_damage_insurance" value="{{ $datadress->damage_insurance }}"
                                                placeholder="กรุณากรอกราคาประกันค่าเสียหาย" min="0">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="dress_status">สถานะชุด</label>
                                            <select name="update_dress_status" id="update_dress_status"
                                                class="form-control">
                                                <option value="พร้อมให้เช่า"
                                                    {{ $datadress->dress_status == 'พร้อมให้เช่า' ? 'selected' : '' }}>
                                                    พร้อมให้เช่า</option>
                                                <option value="ถูกจองแล้ว"
                                                    {{ $datadress->dress_status == 'ถูกจองแล้ว' ? 'selected' : '' }}>ถูกจองแล้ว
                                                </option>
                                                <option value="กำลังเช่า"
                                                    {{ $datadress->dress_status == 'กำลังเช่า' ? 'selected' : '' }}>
                                                    กำลังเช่า</option>
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
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <label for="dress_description">คำอธิบายชุด</label>
                                            <textarea name="update_dress_description" id="update_dress_description" class="form-control" rows="3"
                                                placeholder="กรุณากรอกคำอธิบาย">{{ $datadress->dress_description }}</textarea>
                                        </div>
                                    </div>

                                   
                                    <!-- ข้อมูลการวัด -->
                                    <h5 class="mb-4">ขนาดของชุด</h5>

                                    @foreach ($measument_no_separate_now_modal as $measument_no_separate_now_modal)
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <input type="hidden" name="mea_now_id_[]"
                                                    value="{{ $measument_no_separate_now_modal->id }}">
                                                <input type="text" class="form-control" name="mea_now_name_[]"
                                                    value="{{ $measument_no_separate_now_modal->measurementnow_dress_name }}"
                                                    placeholder="ชื่อการวัด" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="number" class="form-control" name="mea_now_number_[]"
                                                    value="{{ $measument_no_separate_now_modal->measurementnow_dress_number }}"
                                                    placeholder="หมายเลขการวัด">
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control" name="mea_now_unit_[]">
                                                    <option value="นิ้ว">นิ้ว</option>
                                                    <option value="เซนติเมตร">เซนติเมตร</option>
                                                    <option value="มิลลิเมตร">มิลลิเมตร</option>
                                                </select>
                                            </div>
                                        </div>
                                    @endforeach

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


            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>ประเภทชุด:</strong> {{ $name_type }}</p>
                        <p><strong>รหัสชุด:</strong> {{ $datadress->dress_code_new }}{{ $datadress->dress_code }}</p>
                        <p><strong>ราคา:</strong> {{ number_format($datadress->dress_price, 2) }} บาท</p>
                        <p><strong>ราคามัดจำ:</strong> {{ number_format($datadress->dress_deposit, 2) }} บาท</p>
                        <p><strong>ราคาประกันค่าเสียหาย:</strong> {{ number_format($datadress->damage_insurance, 2) }} บาท</p>

                    </div>
                    <div class="col-md-6">
                        <p><strong>จำนวนชุด:</strong> {{ $datadress->dress_count }} ชุด</p>
                        <p>
                            <strong>สถานะชุด:</strong>
                            <span style="color: red;">{{ $datadress->dress_status }}</span>

                            
                        </p>
                        <p><strong>จำนวนครั้งที่ถูกเช่า:</strong> {{ $datadress->dress_rental }} ครั้ง</p>
                        <p><strong>ชุด:</strong>
                            @if ($datadress->separable == 1)
                                <i class="bi bi-x-circle-fill text-danger"></i> ไม่สามารถเช่าแยกได้
                            @elseif($datadress->separable == 2)
                                <i class="bi bi-check-circle-fill text-success"></i> สามารถเช่าแยกได้
                            @endif
                        </p>
                        <p><strong>คำอธิบายชุด:</strong>{{ $datadress->dress_description }}</p>
                    </div>
                </div>

                <!-- ข้อมูลการวัดของชุดเริ่มต้น -->
                <h5 class="mt-4">ขนาดของชุดเริ่มต้น</h5>
                <div>
                    @foreach ($measument_no_separate as $measument_no_separate)
                        {{ $measument_no_separate->measurement_dress_name }}&nbsp;{{ $measument_no_separate->measurement_dress_number }}&nbsp;{{ $measument_no_separate->measurement_dress_unit }}
                    @endforeach
                </div>

                <!-- ข้อมูลการวัดของชุดล่าสุด -->
                <h5 class="mt-4">ขนาดของชุดล่าสุด
                    <button class="btn btn-link p-0 ml-2" data-toggle="modal" data-target="#add_mea">
                        <i class="bi bi-plus-square text-primary"></i> เพิ่มข้อมูลการวัด
                    </button>

                </h5>
                <div>
                    @foreach ($measument_no_separate_now as $measument_no_separate_now)
                        {{ $measument_no_separate_now->measurementnow_dress_name }}&nbsp;{{ $measument_no_separate_now->measurementnow_dress_number }}&nbsp;{{ $measument_no_separate_now->measurementnow_dress_unit }}
                    @endforeach
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
    <div class="modal fade" id="add_mea" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.addmeasumentno', ['id' => $datadress->id]) }}" method="POST">
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








    <!-- Modals for success and failure messages -->
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
