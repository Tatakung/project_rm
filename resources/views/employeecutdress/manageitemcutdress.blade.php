@extends('layouts.adminlayout')
@section('content')
    <ol class="breadcrumb" style="background-color: transparent; ">
        <li class="breadcrumb-item">
            <a href="{{ route('employee.cart') }}" style="color: black ; ">ตะกร้าสินค้า</a>
        </li>
        <li class="breadcrumb-item active">
            จัดการข้อมูลเช่าชุด
        </li>
    </ol>

    <style>
        p {
            font-size: 16px;
        }

        .card-header {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        #button_add_mea {
            background-color: #3498db;
            /* ปุ่มสีฟ้า */
            color: #fff;
            /* ตัวอักษรสีขาว */
            border: none;
            /* ลบขอบปุ่ม */
            border-radius: 4px;
            /* มุมปุ่มโค้ง */
            padding: 8px 12px;
            /* ระยะห่างด้านในของปุ่ม */
            font-size: 14px;
            /* ขนาดตัวอักษรของปุ่ม */
            cursor: pointer;
            /* เปลี่ยนเคอร์เซอร์เมื่อชี้ที่ปุ่ม */
            margin-left: 10px;
            /* ระยะห่างจากข้อความ */
            transition: background-color 0.3s ease;
            /* เอฟเฟกต์เปลี่ยนสี */
        }

        #button_add_mea:hover {
            background-color: #2980b9;
            /* เปลี่ยนสีเมื่อชี้ */
        }

        #button_add_image {
            background-color: #3498db;
            /* ปุ่มสีฟ้า */
            color: #fff;
            /* ตัวอักษรสีขาว */
            border: none;
            /* ลบขอบปุ่ม */
            border-radius: 4px;
            /* มุมปุ่มโค้ง */
            padding: 8px 12px;
            /* ระยะห่างด้านในของปุ่ม */
            font-size: 14px;
            /* ขนาดตัวอักษรของปุ่ม */
            cursor: pointer;
            /* เปลี่ยนเคอร์เซอร์เมื่อชี้ที่ปุ่ม */
            margin-left: 10px;
            /* ระยะห่างจากข้อความ */
            transition: background-color 0.3s ease;
            /* เอฟเฟกต์เปลี่ยนสี */
        }

        #button_add_image:hover {
            background-color: #2980b9;
            /* เปลี่ยนสีเมื่อชี้ */
        }
    </style>
    <div class="modal fade" id="showfail" role="dialog" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog" role="document">
            <div class="modal-content custom-modal-content"
                style="max-width: 300px; height: 50px; width: 100%; margin: auto; background-color: #EE4E4E; border: 2px solid #EE4E4E; ">
                <div class="modal-body" style="padding: 10px; display: flex; align-items: center; justify-content: center;">
                    <p style="margin: 0; color: #ffffff;">{{ session('fail') }}</p>
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
    <div class="modal fade" id="showsuccess" role="dialog" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog" role="document">
            <div class="modal-content custom-modal-content"
                style="max-width: 300px; height: 50px; width: 100%; margin: auto; background-color: #39d628; border: 2px solid #4fe227; ">
                <div class="modal-body" style="padding: 10px; display: flex; align-items: center; justify-content: center;">
                    <p style="margin: 0; color: #ffffff;">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    </div>
    <script>
        @if (session('success'))
            setTimeout(function() {
                $('#showsuccess').modal('show');
            }, 500);
        @endif
    </script>


    <form action="{{ route('employee.savemanageitemcutdress', ['id' => $orderdetail->id]) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="container mt-5">
            <div class="card shadow">
                <div class="card-header"><i class="bi bi-info-circle"></i> ข้อมูลตัดชุด
                </div>
                <div class="card-body">




                    <div class="row">
                        <div class="col-md-12">
                            <div class="media">
                                {{-- <img src="{{ asset('storage/' . $imagedress->first()->dress_image) }}" class="mr-5"
                                    alt="..." style="max-height: 350px; width: auto;"> --}}
                                <div
                                    style="max-height: 350px; width: auto; border-radius: 2px; display: flex; justify-content: center; align-items: center; background-color: #f8f9fa;">
                                    <i class="bi bi-scissors" style="font-size: 48px;"></i>
                                </div>


                                <div class="media-left">

                                    <p><strong>รายการ : ตัด{{ $orderdetail->type_dress }}</strong></p>


                                    <p class="d-flex align-items-center">
                                        <strong>ราคาตัด (บาท) :</strong>
                                        <input type="number" name="update_price" id="update_price"
                                            value="{{ $orderdetail->price }}" min="1" required
                                            class="form-control mx-2" style="width: 200px;">
                                    </p>

                                    <p class="d-flex align-items-center">
                                        <strong>เงินมัดจำ (บาท) :</strong>
                                        <input type="number" name="update_deposit" id="update_deposit" value="{{ $orderdetail->deposit }}"
                                            min="1" required class="form-control mx-2" style="width: 200px;">
                                    </p>

                                    <script>
                                        var price = document.getElementById('update_price');
                                        var deposit = document.getElementById('update_deposit');
                
                                        deposit.addEventListener('input', function() {
                                            var convert_deposit = parseFloat(deposit.value);
                                            var convert_price = parseFloat(price.value);
                                            if (convert_deposit > convert_price) {
                                                deposit.value = convert_price;
                                            }
                                        });
                                        price.addEventListener('input', function() {
                                            deposit.value = '';
                
                                        });
                                    </script>





                                    <p class="d-flex align-items-center">
                                        <strong>จำนวนชุด :</strong>
                                        <input type="number" name="update_amount" value="{{ $orderdetail->amount }}"
                                            min="1" required max="100" class="form-control mx-2"
                                            style="width: 200px;">
                                    </p>

                                    <p class="d-flex align-items-center">
                                        <strong>ที่มาของผ้า :</strong>
                                        <select name="update_cloth" id="update_cloth" class="form-control">
                                            <option value="1" {{ $orderdetail->cloth == 1 ? 'selected' : '' }}>
                                                ลูกค้านำผ้ามาเอง</option>
                                            <option value="2" {{ $orderdetail->cloth == 2 ? 'selected' : '' }}>
                                                ทางร้านหาผ้าให้</option>
                                        </select>
                                    </p>

                                    @php
                                        $today = \carbon\Carbon::today()->toDateString();
                                    @endphp

                                    <p class="d-flex align-items-center">
                                        <strong>วันที่นัดส่งมอบ :</strong>
                                        <input type="date" name="update_pickup_date" value="{{ $Date->pickup_date }}"
                                            min="{{ $today }}" class="form-control mx-2" style="width: 200px;">
                                    </p>


                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow mt-3">
                <div class="card-header">
                    {{-- <p>ข้อมูลการวัดตัวสำหรับตัดชุด (นิ้ว)</p> --}}
                    <div class="col-md-12">
                        <p><strong>ข้อมูลการวัดตัว (หน่วยเป็นนิ้ว)</strong>
                            <button type="button" id="button_add_mea">+ เพิ่มการวัดเพิ่มเติม</button>
                        </p>
                    </div>
                </div>
                <div class="card-body">
                    <div id="aria_show_mea">
                        {{-- พืน้ที่แสดงผล  --}}

                        @foreach ($measurementadjusts as $measurementorderdetail)
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="hidden" name="mea_id_[]" value="{{ $measurementorderdetail->id }}">
                                    <input type="text" name="update_mea_name_[]" class="form-control"
                                        style="font-size: 15px; margin-top: 8px; width: 90%; height: 70%;"
                                        value="{{ $measurementorderdetail->name }}" placeholder="ชื่อการวัด" required>
                                </div>

                                <div class="col-md-4" style="display: flex; align-items: center;">
                                    <input type="hidden" value="{{ $measurementorderdetail->id }}"
                                        name="mea_order_detail_id_[]">

                                    <input type="number" class="form-control" name="update_mea_number_[]"
                                        style="width: 50%; height: 60%; font-size: 15px; margin-right: 20px; margin-bottom: 1px;"
                                        value="{{ $measurementorderdetail->new_size }}" placeholder="ค่าการวัด"
                                        step="0.01" required>
                                </div>

                                <div class="col-md-4" style="padding-left: 1px; margin-top: 12px;">

                                    <a
                                        href="{{ route('employee.deletemeasurementitem', ['id' => $measurementorderdetail->id]) }}">
                                        <button class="btn btn-danger"><i class="bi bi-x-circle"></i></button>
                                    </a>

                                </div>
                            </div>
                        @endforeach

                        <script>
                            var aria_show_mea = document.getElementById('aria_show_mea');
                            var button_add_mea = document.getElementById('button_add_mea');
                            var count_add = 0;

                            button_add_mea.addEventListener('click', function() {
                                count_add++;

                                var div = document.createElement('div');
                                div.className = 'row';
                                div.id = 'row_aria_mea' + count_add;

                                var input =
                                    '<div class="col-md-4">' +
                                    '<input type="text" class="form-control" name="add_mea_name_[]" style="font-size: 15px; margin-top: 8px; width: 90%; height: 70%;" placeholder="ชื่อการวัด" required>' +
                                    '</div>' +
                                    '<div class="col-md-4" style="display: flex; align-items: center;">' +
                                    '<input type="number" class="form-control" name="add_mea_number_[]" style="width: 50%; height: 60%; font-size: 15px; margin-right: 20px; margin-bottom: 1px;" placeholder="ค่าการวัด" step="0.01" required>' +
                                    '</div>' +
                                    '<div class="col-md-4" style="padding-left: 1px; margin-top: 12px;">' +
                                    '<button type="button" class="btn btn-danger" onclick="deletemea(' + count_add +
                                    ')"><i class="bi bi-x-circle"></i></button>' +
                                    '</div>';

                                div.innerHTML = input;
                                aria_show_mea.appendChild(div);
                            });

                            function deletemea(count_add) {
                                var delete_mea = document.getElementById('row_aria_mea' + count_add);
                                delete_mea.remove();
                            }
                        </script>




                    </div>
                </div>



                <div class="card-body">
                    <div class="col-md-8">
                        <p>รายละเอียดอื่นๆ</p>
                        <textarea name="update_note" id="" cols="1" rows="4" class="form-control">{{ $orderdetail->note }}</textarea>

                    </div>

                </div>
            </div>


            <div class="card shadow mt-3">
                <div class="card-header">
                    {{-- <p>ข้อมูลการวัดตัวสำหรับตัดชุด (นิ้ว)</p> --}}
                    <div class="col-md-12">
                        <p><strong>อัปโหลดแบบตัวอย่างสำหรับตัดชุด (หากมี)</strong>
                            <button type="button" id="button_add_image">+ เพิ่มรูปภาพ</button>
                        </p>
                    </div>
                </div>
                <div class="card-body">

                    <div class="row" id="aria_show_mage">
                        @foreach ($image_rent as $item)
                            <div class="col-md-6">
                                <div class="card h-100 shadow-sm">
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="Image description"
                                        style="width: 100%; height: 300px;">
                                    <div class="card-body">
                                        <p class="card-text">{{ $item->description }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach


                        <script>
                            var button_add_image = document.getElementById('button_add_image');
                            var aria_show_mage = document.getElementById('aria_show_mage')
                            var count_image = 0;

                            button_add_image.addEventListener('click', function() {
                                count_image++;

                                var div = document.createElement('div');
                                div.className = 'col-md-6';
                                div.id = 'div_image' + count_image;

                                input =

                                    '<div class="row mb-4">' +
                                    '<div class="col-md-12">' +
                                    '<div class="card">' +
                                    '<div class="card-body">' +
                                    '<input required type="file" name="file_image_[' + count_image +
                                    ']" class="form-control mb-3" accept="image/*" required>' +
                                    '<textarea required class="form-control" name="note_image_[' + count_image +
                                    ']" placeholder="ใส่รายละเอียดเกี่ยวกับรูปภาพ..."></textarea>' +

                                    '<button class="btn  btn-block mt-3" onclick="remove_image(' + count_image +
                                    ')"><i class="bi bi-x-circle"></i> ลบ</button>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';
                                div.innerHTML = input;
                                aria_show_mage.appendChild(div);
                            });

                            function remove_image(count_image) {
                                var remove_count_image = document.getElementById('div_image' + count_image);
                                remove_count_image.remove();
                            }
                        </script>
                    </div>

                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">ยืนยัน</button>
                        </div>
                    </div>



                </div>
            </div>







        </div>
    </form>
@endsection
