@extends('layouts.adminlayout')
@section('content')
    <style>
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
        .form-label{
            font-weight: bold;
        }
    </style>




    <ol class="breadcrumb" style="background-color: transparent;">
        <li class="breadcrumb-item">
            <a href="{{ route('employee.addorder') }}" style="color: black ; ">เพิ่มออเดอร์ใหม่</a>
        </li>
        <li class="breadcrumb-item active">เพิ่มรายการตัดชุด</li>
    </ol>

    <div class="modal fade" id="showfail" role="dialog" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog" role="document">
            <div class="modal-content custom-modal-content"
                style="max-width: 300px; height: 50px; width: 100%; margin: auto; background-color: #EE4E4E; border: 2px solid #EE4E4E; ">
                <div class="modal-body" style="padding: 5px; display: flex; align-items: center; justify-content: center;">
                    <p style="margin: 0; color: #ffffff;">{{ session('fail') }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="showsuccess" role="dialog" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog" role="document">
            <div class="modal-content custom-modal-content"
                style="max-width: 400px; height: 50px; width: 100%; margin: auto; background-color: #39d628; border: 2px solid #4fe227; ">
                <div class="modal-body" style="padding: 10px; display: flex; align-items: center; justify-content: center;">
                    <p style="margin: 0; color: #ffffff;">{{ session('success') }}</p>
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

    <script>
        @if (session('success'))
            setTimeout(function() {
                $('#showsuccess').modal('show');
            }, 500);
        @endif
    </script>
    <form action="{{ route('employee.savecutdress') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="container mt-2">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="type_dress" class="form-label">ประเภทชุดที่ตัด</label>
                            <select name="type_dress" id="type_dress" class="form-control" required>
                                <option value="" disabled selected>เลือกรายการ</option>
                                <option value="ชุดผ้าไหม">ชุดผ้าไหม</option>
                                <option value="ชุดราตรี">ชุดราตรี</option>
                                <option value="ชุดเดรส">ชุดเดรส</option>
                                <option value="other_type">อื่นๆ</option>
                            </select>
                        </div>

                        <div class="col-md-6" id="show_other_input" style="display: none;">
                            <label for="" class="form-label">ระบุประเภทชุด</label>
                            <input type="text" name="other_input" id="other_input" class="form-control">
                        </div>
                        <script>
                            var type_dress = document.getElementById('type_dress');
                            var show_other_input = document.getElementById('show_other_input');
                            type_dress.addEventListener('change', function() {
                                if (type_dress.value === 'other_type') {
                                    show_other_input.style.display = 'block';
                                    document.getElementById('other_input').setAttribute('required', 'required');
                                } else {
                                    show_other_input.style.display = 'none';
                                    document.getElementById('other_input').value = '';
                                    document.getElementById('other_input').removeAttribute('required');;
                                }
                            });
                        </script>





                        <div class="col-md-4" style="display: none ; ">
                            <label for="amount" class="form-label">จำนวนชุดที่ตัด</label>
                            <input class="form-control" type="number" value="1" min="1" max="100"
                                name="amount" id="amount" required>
                        </div>

                    </div>




                    <div class="row mt-4">
                        <div class="col-md-6">
                            <label for="" class="form-label">ที่มาของผ้า</label>
                            <select name="cloth" id="cloth" class="form-control" required>
                                <option value="1" selected>ลูกค้านำผ้ามาเอง</option>
                                <option value="2">ทางร้านหาผ้าให้</option>
                            </select>
                        </div>

                        @php
                            $today = \Carbon\Carbon::today()->toDateString();
                        @endphp

                        <div class="col-md-6">
                            <label for="" class="form-label">วันที่นัดส่งมอบชุด</label>
                            <input type="date" name="pickup_date" class="form-control" min="{{ $today }}"
                                required>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <label for="" class="form-label">ราคาตัดชุด (บาท)</label>
                            <input type="number" name="price" id="price" class="form-control" min="0"
                                step="0.01" required placeholder="กรอกจำนวนเงิน">
                        </div>

                        <div class="col-md-6">
                            <label for="" class="form-label">เงินมัดจำ (บาท)</label>
                            <input type="number" class="form-control" name="deposit" id="deposit" min="0"
                                step="0.01" required placeholder="กรอกจำนวนเงิน">
                        </div>
                    </div>
                    <script>
                        var price = document.getElementById('price');
                        var deposit = document.getElementById('deposit');

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














                    <div class="row mt-4">
                        <div class="col-md-12">
                            <label class="form-label">รายละเอียดเพิ่มเติม</label>
                            <textarea class="form-control" id="note" name="note" rows="3" placeholder="ใส่รายละเอียด(หากมี)"></textarea>

                        </div>
                    </div>


                    <div class="row mt-5">
                        <div class="col-md-12">
                            <p><strong>ข้อมูลการวัดตัว (หน่วยเป็นนิ้ว)</strong>
                                <button type="button" id="button_add_mea">+ เพิ่มการวัดเพิ่มเติม</button>
                            </p>
                        </div>
                    </div>

                    {{-- พื้นที่แสดงผล --}}
                    <div class="row mt-1" id="aria_show_mea">




                    </div>


                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var aria_show_mea_ter = document.getElementById('aria_show_mea');
                            var select_type_dress = document.getElementById('type_dress');
                            var list_mea = [
                                'ยาวหน้า', 'ยาวหลัง', 'ไหล่กว้าง', 'บ่าหน้า', 'บ่าหลัง', 'รอบคอ', 'อกสูง',
                                'รอบเอว', 'สะโพกเล็ก', 'สะโพก', 'กระโปรงยาว', 'แขนยาว', 'แขนกว้าง',
                                'เสื้อยาว', 'ต้นขา', 'ปลายขา', 'เป้า', 'กางเกงยาว'
                            ];
                            var count_add = 0;

                            function loop_mea() {
                                aria_show_mea_ter.innerHTML = '';
                                list_mea.forEach(element => {
                                    count_add++;
                                    var div = document.createElement('div');
                                    div.className = 'col-md-6';
                                    div.id = 'aria_div' + count_add;
                                    input =
                                        '<div class="row mb-4">' +
                                        '<div class="col-md-4">' +
                                        '<input type="text" required name="name_[' + count_add + ']" value="' + element +
                                        '" class="form-control" placeholder="ชื่อการวัด" readonly>' +
                                        '</div>' +
                                        '<div class="col-md-4">' +
                                        '<input type="number" name="number_[' + count_add +
                                        ']" class="form-control" placeholder="ค่าการวัด" required step="0.01" min="0">' +
                                        '</div>' +
                                        '<div class="col-md-4">' +
                                        '<button class="btn" onclick="remove_mea(' + count_add +
                                        ')"><i class="bi bi-x-circle"></i></button>' +
                                        '</div>' +
                                        '</div>';
                                    div.innerHTML = input;
                                    aria_show_mea_ter.appendChild(div);
                                });
                            }
                            loop_mea();
                            // เพิ่ม
                            var button_add_mea = document.getElementById('button_add_mea');
                            button_add_mea.addEventListener('click', function() {
                                count_add++;
                                var div = document.createElement('div');
                                div.className = 'col-md-6';
                                div.id = 'aria_div' + count_add;
                                input =
                                    '<div class="row mb-4">' +
                                    '<div class="col-md-4">' +
                                    '<input type="text" name="name_[' + count_add +
                                    ']" class="form-control" placeholder="ชื่อการวัด" required>' +
                                    '</div>' +
                                    '<div class="col-md-4">' +
                                    '<input type="number" name="number_[' + count_add +
                                    ']" class="form-control" required placeholder="ค่าการวัด" step="0.01" min="0">' +
                                    '</div>' +
                                    '<div class="col-md-4">' +
                                    '<button class="btn" onclick="remove_mea(' + count_add +
                                    ')"><i class="bi bi-x-circle"></i></button>' +
                                    '</div>' +
                                    '</div>';
                                div.innerHTML = input;
                                aria_show_mea_ter.appendChild(div);
                            });


                            select_type_dress.addEventListener('change', function() {
                                if (select_type_dress.value === "ชุดไทย") {
                                    list_mea = ['ยาวหน้า', 'ยาวหลัง', 'ไหล่กว้าง', 'รอบคอ', 'รอบอก', 'รอบเอว', 'สะโพก',
                                        'เสื้อยาว', 'แขนยาว', 'กระโปรงยาว'
                                    ];
                                    count_add = 0;
                                    loop_mea();
                                } else if (select_type_dress.value === 'ชุดราตรี') {
                                    list_mea = ['ยาวหน้า', 'ยาวหลัง', 'ไหล่กว้าง', 'รอบคอ', 'รอบอก', 'อกห่าง', 'อกสูง',
                                        'รอบเอว', 'สะโพก', 'กระโปรงยาว', 'แขนยาว'
                                    ];
                                    count_add = 0;
                                    loop_mea();

                                } else {
                                    list_mea = [
                                        'ยาวหน้า', 'ยาวหลัง', 'ไหล่กว้าง', 'บ่าหน้า', 'บ่าหลัง', 'รอบคอ', 'อกสูง',
                                        'รอบเอว', 'สะโพกเล็ก', 'สะโพก', 'กระโปรงยาว', 'แขนยาว', 'แขนกว้าง',
                                        'เสื้อยาว', 'ต้นขา', 'ปลายขา', 'เป้า', 'กางเกงยาว'
                                    ];
                                    count_add = 0;
                                    loop_mea();

                                }

                            });
                        });
                    </script>
                    <script>
                        function remove_mea(count_add) {
                            var delete_aria_div = document.getElementById('aria_div' + count_add);
                            delete_aria_div.remove();
                        }
                    </script>



                    <div class="row mt-3">
                        <div class="col-md-12">
                            <p><strong>อัปโหลดแบบตัวอย่างสำหรับตัดชุด (หากมี)</strong><button style="margin-left: 10px;"
                                    type="button" id="button_add_image">+ เพิ่มรูปภาพ</button></p>
                        </div>
                    </div>


                    <div class="row mt-1" id="aria_show_mage">
                        {{-- พื้นที่แสดงผล --}}


                        <div class="col-md-6" id="div_image1">
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <input type="file" id="imagerent1" name="file_image_[1]"
                                                class="form-control mb-3" accept="image/*">
                                            <textarea class="form-control" name="note_image_[1]" placeholder="ใส่รายละเอียดเกี่ยวกับรูปภาพ..."></textarea>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <script>
                            var button_add_image = document.getElementById('button_add_image');
                            var aria_show_mage = document.getElementById('aria_show_mage')
                            var count_image = 1;

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
                                    '<input type="file" name="file_image_[' + count_image +
                                    ']" class="form-control mb-3" accept="image/*" required>' +
                                    '<textarea class="form-control" name="note_image_[' + count_image +
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
                    <div class="row mt-4">
                        <div class="col-md-12" style="text-align: end ; ">
                            <button type="submit" class="btn btn-success">บันทึกข้อมูลและเพิ่มลงในตะกร้า</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection