@extends('layouts.adminlayout')
@section('content')
    <style>
        .table-container {
            height: 400px;
            /* กำหนดความสูงของตาราง */
            overflow-y: scroll;
            /* แสดงแถวเลื่อนแนวนอน */
        }

        .table::-webkit-scrollbar {
            width: 10px;
            /* กำหนดความกว้างของลูกกลิ้ง */
            height: 8px;
            /* กำหนดความสูงของลูกกลิ้ง */
        }

        .table::-webkit-scrollbar-thumb {
            background: #888;
            /* กำหนดสีพื้นหลังของลูกกลิ้ง */
            border-radius: 5px;
            /* กำหนดมุมโค้งมนของลูกกลิ้ง */
        }

        .table::-webkit-scrollbar-track {
            background: #ccc;
            /* กำหนดสีพื้นหลังของแถบเลื่อน */
        }
    </style>
    <div class="container d-flex justify-content-start">


        {{-- @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('fail'))
            {{ session('fail') }}
        @endif --}}

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

            {{-- modalเพิ่มรูปภาพ --}}
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





                {{-- modalแก้ไขชุด --}}
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


            {{-- modalแก้ไขราคา --}}
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
                                {{-- modalแก้ไขข้อมูลการวัด --}}
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


                                {{-- modalลบข้อมูลการวัด --}}
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








            {{-- modalเพิ่มข้อมูลการวัด --}}
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



        @endsection
