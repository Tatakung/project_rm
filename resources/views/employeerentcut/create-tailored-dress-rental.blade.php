@extends('layouts.adminlayout')
@section('content')
    <style>
        #add_mea_skirt{
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
        #add_mea_shirt{
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

        #button_add_image,
        #button_add_fiiting {
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

        .form-label {
            font-weight: bold;
        }
    </style>




    <ol class="breadcrumb" style="background-color: transparent;">
        <li class="breadcrumb-item">
            <a href="{{ route('employee.addorder') }}" style="color: black ; ">เพิ่มออเดอร์ใหม่</a>
        </li>
        <li class="breadcrumb-item active">เพิ่มรายการเช่าตัด</li>
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
    <form action="{{ route('employee.saveaddrentcut') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="container mt-2">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="type_dress" class="form-label">ประเภทชุดที่เช่าตัด</label>
                            <select name="type_dress" id="type_dress" class="form-control" required>
                                <option value="" disabled selected>เลือกรายการ</option>
                                @foreach ($type_dress as $dressType)
                                    <option value="{{ $dressType->type_dress_name }}">
                                        {{ $dressType->type_dress_name }}
                                    </option>
                                @endforeach
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





                        
                    </div>




                    <div class="row mt-4">
                        {{-- <div class="col-md-6">
                            <label for="" class="form-label">ที่มาของผ้า</label>
                            <select name="cloth" id="cloth" class="form-control" required>
                                <option value="1" selected>ลูกค้านำผ้ามาเอง</option>
                                <option value="2">ทางร้านหาผ้าให้</option>
                            </select>
                        </div> --}}

                        @php
                            $today = \Carbon\Carbon::today()->toDateString();
                        @endphp

                        <div class="col-md-6">
                            <label for="" class="form-label">วันที่นัดรับชุด</label>
                            <input type="date" id="pickup_date" name="pickup_date" class="form-control"
                                min="{{ $today }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="" class="form-label">วันที่นัดคืนชุด</label>
                            <input type="date" id="return_date" name="return_date" class="form-control"
                                min="{{ $today }}" required>
                        </div>

                        <script>
                            var set_pickup_date = document.getElementById('pickup_date');
                            var set_return_date = document.getElementById('return_date');
                            set_pickup_date.addEventListener('input', function() {
                                set_return_date.value = '';
                                set_return_date.min = set_pickup_date.value;
                            });
                        </script>




                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <label for="" class="form-label">ราคาเช่าตัดชุด (บาท)</label>
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

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <label class="form-label">ประเภทการให้เช่า</label>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="rental_option" id="rental_option1"
                                    value="1" required>
                                <label class="form-check-label" for="rental_option1">
                                    เช่าแยกไม่ได้
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="rental_option" id="rental_option2"
                                    value="2" required>
                                <label class="form-check-label" for="rental_option2">
                                    เช่าแยกได้
                                </label>
                            </div>
                        </div>
                    </div>


                    <div id="for_dress_separated_no" style="display: none ; ">
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
                                            ']" class="form-control" placeholder="ค่าการวัด"  step="0.01" min="0">' +
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
                    </div>

                    <div id="for_dress_separated_yes" style="display: none ; ">


                        <div class="row mt-5">
                            <div class="col-md-12">
                                <p><strong>ข้อมูลการวัดตัวของเสื้อ (หน่วยเป็นนิ้ว)</strong>
                                    <button type="button" id="add_mea_shirt">+ เพิ่มการวัดเพิ่มเติมเสื้อ</button>
                                </p>
                            </div>
                        </div>

                        {{-- พื้นที่แสดงผลเสื้อ --}}
                        <div class="row mt-1" id="aria_show_mea_shirt">

                            <div class="col-md-6" id='aria_div_shirt1'>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <input type="text" name="name_shirt_[1]" class="form-control"
                                            placeholder="ชื่อการวัด" value="รอบอก" required readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" name="number_shirt_[1]" class="form-control"
                                            placeholder="ค่าการวัด" step="0.01" min="0">
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn" onclick="remove_shirt(1)"><i
                                                class="bi bi-x-circle"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" id='aria_div_shirt2'>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <input type="text" name="name_shirt_[2]" class="form-control"
                                            placeholder="ชื่อการวัด" value="ความกว้างของไหล่" required readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" name="number_shirt_[2]" class="form-control"
                                            placeholder="ค่าการวัด" step="0.01" min="0">
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn" onclick="remove_shirt(2)"><i
                                                class="bi bi-x-circle"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" id='aria_div_shirt3'>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <input type="text" name="name_shirt_[3]" class="form-control"
                                            placeholder="ชื่อการวัด" value="ความยาวเสื้อ" required readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" name="number_shirt_[3]" class="form-control"
                                            placeholder="ค่าการวัด" step="0.01" min="0">
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn" onclick="remove_shirt(3)"><i
                                                class="bi bi-x-circle"></i></button>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6" id='aria_div_shirt4'>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <input type="text" name="name_shirt_[4]" class="form-control"
                                            placeholder="ชื่อการวัด" value="ความยาวแขน" required readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" name="number_shirt_[4]" class="form-control"
                                            placeholder="ค่าการวัด" step="0.01" min="0">
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn" onclick="remove_shirt(4)"><i
                                                class="bi bi-x-circle"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="row mt-5">
                            <div class="col-md-12">
                                <p><strong>ข้อมูลการวัดตัวของผ้าถุง (หน่วยเป็นนิ้ว)</strong>
                                    <button type="button" id="add_mea_skirt">+ เพิ่มการวัดเพิ่มเติมผ้าถุง</button>
                                </p>
                            </div>
                        </div>

                        {{-- พื้นที่แสดงผลผ้าถุง --}}
                        <div class="row mt-1" id="aria_show_mea_skirt">

                            <div class="col-md-6" id='aria_div_skirt1'>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <input type="text" name="name_skirt_[1]" class="form-control"
                                            placeholder="ชื่อการวัด" value="รอบเอว" required readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" name="number_skirt_[1]" class="form-control"
                                            placeholder="ค่าการวัด" step="0.01" min="0">
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn" onclick="remove_skirt(1)"><i
                                                class="bi bi-x-circle"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" id='aria_div_skirt2'>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <input type="text" name="name_skirt_[2]" class="form-control"
                                            placeholder="ชื่อการวัด" value="รอบสะโพก" required readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" name="number_skirt_[2]" class="form-control"
                                            placeholder="ค่าการวัด" step="0.01" min="0">
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn" onclick="remove_skirt(2)"><i
                                                class="bi bi-x-circle"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" id='aria_div_skirt3'>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <input type="text" name="name_skirt_[3]" class="form-control"
                                            placeholder="ชื่อการวัด" value="ความยาวผ้า" required readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" name="number_skirt_[3]" class="form-control"
                                            placeholder="ค่าการวัด" step="0.01" min="0">
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn" onclick="remove_skirt(3)"><i
                                                class="bi bi-x-circle"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        var add_mea_shirt = document.getElementById('add_mea_shirt');
                        var aria_show_mea_shirt = document.getElementById('aria_show_mea_shirt');
                        var count_shirt = 4;
                        add_mea_shirt.addEventListener('click', function() {
                            count_shirt++;
                            var div_big_shirt = document.createElement('div');
                            div_big_shirt.className = 'col-md-6';
                            div_big_shirt.id = 'aria_div_shirt' + count_shirt;


                            shirt_input =

                                '<div class="row mb-4">' +
                                '<div class="col-md-4">' +
                                '<input type="text" name="name_shirt_[' + count_shirt +
                                ']" class="form-control" placeholder="ชื่อการวัด"  required>' +
                                '</div>' +
                                '<div class="col-md-4">' +
                                '<input type="number" name="number_shirt_[' + count_shirt +
                                ']" class="form-control" placeholder="ค่าการวัด" step="0.01" min="0">' +
                                '</div>' +
                                '<div class="col-md-4">' +
                                '<button class="btn" onclick="remove_shirt(' + count_shirt +
                                ')"><i class="bi bi-x-circle"></i></button>' +
                                '</div>' +
                                '</div>';
                            div_big_shirt.innerHTML = shirt_input;
                            aria_show_mea_shirt.appendChild(div_big_shirt);
                        });

                        function remove_shirt(count_shirt) {
                            var delete_shirt = document.getElementById('aria_div_shirt' + count_shirt);
                            delete_shirt.remove();
                        }
                    </script>
                    <script>
                        var add_mea_skirt = document.getElementById('add_mea_skirt');
                        var aria_show_mea_skirt = document.getElementById('aria_show_mea_skirt');
                        var count_skirt = 3;
                        add_mea_skirt.addEventListener('click', function() {
                            count_skirt++;
                            var div_big_skirt = document.createElement('div');
                            div_big_skirt.className = 'col-md-6';
                            div_big_skirt.id = 'aria_div_skirt' + count_skirt;


                            skirt_input =

                                '<div class="row mb-4">' +
                                '<div class="col-md-4">' +
                                '<input type="text" name="name_skirt_[' + count_skirt +
                                ']" class="form-control" placeholder="ชื่อการวัด"  required>' +
                                '</div>' +
                                '<div class="col-md-4">' +
                                '<input type="number" name="number_skirt_[' + count_skirt +
                                ']" class="form-control" placeholder="ค่าการวัด" step="0.01" min="0">' +
                                '</div>' +
                                '<div class="col-md-4">' +
                                '<button class="btn" onclick="remove_skirt(' + count_skirt +
                                ')"><i class="bi bi-x-circle"></i></button>' +
                                '</div>' +
                                '</div>';
                            div_big_skirt.innerHTML = skirt_input;
                            aria_show_mea_skirt.appendChild(div_big_skirt);
                        });

                        function remove_skirt(count_skirt) {
                            var delete_skirt = document.getElementById('aria_div_skirt' + count_skirt);
                            delete_skirt.remove();
                        }
                    </script>



                    <div class="row mt-3">
                        <div class="col-md-12">
                            <p><strong>อัปโหลดแบบตัวอย่างสำหรับเช่าตัดชุด (หากมี)</strong><button
                                    style="margin-left: 10px;" type="button" id="button_add_image">+
                                    เพิ่มรูปภาพ</button></p>
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

                        <script>
                            var for_dress_separated_no = document.getElementById('for_dress_separated_no') ; 
                            var for_dress_separated_yes = document.getElementById('for_dress_separated_yes') ; 


                            var rental_option1 = document.getElementById('rental_option1') ; 
                            var rental_option2 = document.getElementById('rental_option2') ; 
                            rental_option1.addEventListener('change',function(){
                                if(this.checked){
                                    for_dress_separated_no.style.display = 'block' ; 
                                    for_dress_separated_yes.style.display = 'none' ; 
                                }
                            }) ; 
                            rental_option2.addEventListener('change',function(){
                                if(this.checked){
                                    for_dress_separated_no.style.display = 'none' ; 
                                    for_dress_separated_yes.style.display = 'block' ; 
                                }
                            }) ; 

                            
                
                        </script>








                    </div>


                    <div class="row mt-3">
                        <div class="col-md-12">
                            <p><strong>นัดลองชุดลูกค้า (หากมี)</strong></p>
                        </div>
                    </div>

                    <div class="row mt-1" id="aria_show_fiiting">
                        {{-- พื้นที่แสดงผล --}}
                        <div class="col-md-6" id="div_fitting1">
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <input type="date" id="fitting_set" name="fitting"
                                                class="form-control mb-3" min="{{ $today }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <script>
                        var pickup_date_set_fitting = document.getElementById('pickup_date');
                        var fitting_set = document.getElementById('fitting_set');
                        pickup_date_set_fitting.addEventListener('input', function() {
                            fitting_set.value = '';
                            fitting_set.max = pickup_date_set_fitting.value;
                        });
                    </script>



                    <div class="row mt-4">
                        <div class="col-md-12" style="text-align: end ; ">
                            <button type="submit" class="btn btn-success">บันทึกข้อมูลและเพิ่มลงในตะกร้า</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        var select_type_dress_for_type = document.getElementById('type_dress') ; 
        var show_rental_option1 = document.getElementById('rental_option1') ; 
        var show_timple_for_dress_separated_no = document.getElementById('for_dress_separated_no') ; 
        select_type_dress_for_type.addEventListener('change',function(){
            if(select_type_dress_for_type.value == 'ชุดราตรี'){
                show_rental_option1.checked = true ; 
                show_timple_for_dress_separated_no.style.display = 'block' ; 
            }
            
            

        }) ; 
    </script>







@endsection