@extends('layouts.adminlayout')
@section('content')
    <ol class="breadcrumb" style="background: white ; ">
        <li class="breadcrumb-item">
            <a href="{{ route('employee.addorder') }}" style="color: black ; ">เพิ่มออเดอร์ใหม่</a>
        </li>
        <li class="breadcrumb-item active">เพิ่มรายการตัดชุด</li>
    </ol>
    <style>
        body {
            font-size: 15px;
        }

        .form-control,
        .form-select,
        .btn,
        .input-group-text {
            font-size: 15px;
        }

        h4 {
            font-size: 18px;
        }

        h5 {
            font-size: 16px;
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





    <div class="container mt-5">
        <form action="{{ route('employee.savecutdress') }}" method="POST">
            @csrf
            <div class="card shadow-sm">
                <div class="card shadow-sm">
                    <div class="card-header text-dark" style="background-color: #EAD8C0;">
                        <h4 class="mb-0">บันทึกข้อมูลการตัดชุด</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('employee.savecutdress') }}" method="POST" id="cutDressForm">
                        @csrf
                        <div class="row g-3">
                            <!-- ข้อมูลการตัดชุด -->
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">ข้อมูลการตัดชุด</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="type_dress" class="form-label">ประเภทชุด</label>
                                                <select class="form-control" id="type_dress" name="type_dress" required>
                                                    <option value="" selected disabled>เลือกประเภทชุด</option>
                                                    @foreach ($type_dress as $dressType)
                                                        <option value="{{ $dressType->type_dress_name }}">
                                                            {{ $dressType->type_dress_name }}</option>
                                                    @endforeach
                                                    <option value="other_type">อื่นๆ</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3" id="showinput" style="display:none;">
                                                <label for="other_input" class="form-label">ระบุประเภทชุดอื่นๆ</label>
                                                <input type="text" class="form-control" id="other_input"
                                                    name="other_input">
                                            </div>
                                        </div>
                                        <script>
                                            var select_type_dress = document.getElementById('type_dress');
                                            var showinput = document.getElementById('showinput');
                                            var input_other_input = document.getElementById('other_input');

                                            select_type_dress.addEventListener('change', function() {
                                                if (select_type_dress.value === "other_type") {
                                                    showinput.style.display = 'block';
                                                    input_other_input.setAttribute('required', 'required')
                                                } else {
                                                    showinput.style.display = 'none';
                                                    input_other_input.value = '';
                                                    input_other_input.removeAttribute('required');
                                                }
                                            });
                                        </script>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="price" class="form-label">ราคา</label>
                                                <input type="number" class="form-control" id="price" name="price"
                                                    required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="deposit" class="form-label">ราคามัดจำ</label>
                                                <input type="number" class="form-control" id="deposit" name="deposit"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="amount" class="form-label">จำนวนชุด</label>
                                            <input type="number" class="form-control" id="amount" name="amount"
                                                value="1" min="1" max="100" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">ผ้า</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="cloth" id="cloth1"
                                                    value="1" checked>
                                                <label class="form-check-label" for="cloth1">ลูกค้านำผ้ามาเอง</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="cloth"
                                                    id="cloth2" value="2">
                                                <label class="form-check-label" for="cloth2">ทางร้านหาผ้าให้</label>
                                            </div>
                                        </div>


                                        @php
                                            $today = \Carbon\Carbon::today()->toDateString();
                                        @endphp



                                        <div class="mb-3">
                                            <label for="pickup_date" class="form-label">วันที่นัดรับ</label>
                                            <input type="date" class="form-control" id="pickup_date"
                                                name="pickup_date" min="{{ $today }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="note" class="form-label">รายละเอียดเพิ่มเติม</label>
                                            <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- บันทึกการวัดตัว -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">บันทึกการวัดตัว (นิ้ว)</h5>
                                        <button type="button" class="btn btn-sm btn btn-success" id="button_add_mea">เพิ่มการวัด
                                        </button>
                                    </div>
                                    {{-- list_mea --}}
                                    <div class="card-body">
                                        <div id="aria_show_mea">
                                            {{-- <div class="col-md-4 text-center">
                                                <input type="text"  name="" class="form-control"  placeholder="ชื่อการวัด" required style="height: 30px;">
                                            </div> --}}
                                        </div>


                                        <script>
                                            var aria_show_mea = document.getElementById('aria_show_mea');
                                            var selecttype_dress = document.getElementById('type_dress');
                                            var button_add_mea = document.getElementById('button_add_mea');
                                            var count_mea = 0;
                                            var list_mea = [
                                                'ยาวหน้า', 'ยาวหลัง', 'ไหล่กว้าง', 'บ่าหน้า', 'บ่าหลัง', 'รอบคอ', 'อกสูง',
                                                'รอบเอว', 'สะโพกเล็ก', 'สะโพก', 'กระโปรงยาว', 'แขนยาว', 'แขนกว้าง',
                                                'เสื้อยาว', 'ต้นขา', 'ปลายขา', 'เป้า', 'กางเกงยาว'
                                            ];
                                            @php
                                                $list_mea = ['ยาวหน้า', 'ยาวหลัง', 'ไหล่กว้าง', 'บ่าหน้า', 'บ่าหลัง', 'รอบคอ', 'อกสูง', 'รอบเอว', 'สะโพกเล็ก', 'สะโพก', 'กระโปรงยาว', 'แขนยาว', 'แขนกว้าง', 'เสื้อยาว', 'ต้นขา', 'ปลายขา', 'เป้า', 'กางเกงยาว'];
                                            @endphp
                                            document.addEventListener('DOMContentLoaded', function() {
                                                function loopmea() {
                                                    aria_show_mea.innerHTML = '';
                                                    list_mea.forEach(function(list, index) {
                                                        // console.log(list);
                                                        count_mea++;
                                                        var div = document.createElement('div');
                                                        div.className = 'row';
                                                        div.id = 'row_mea' + count_mea;
                                                        input =
                                                            '<div class="col-md-4 text-center">' +
                                                        '<input type="text"  name="add_mea_name_[' + count_mea +']" class="form-control"  placeholder="ชื่อการวัด" required style="height: 30px;" value=" '+list+' " readonly>' + 
                                                        '</div>' +
                                                            '<div class="col-md-4">' +
                                                            '<input type="number" name="add_mea_number_[' + count_mea +']" class="form-control" style="height: 30px;" min="1" max="100" required step="0.01">' +
                                                            '</div>' +
                                                            '<div class="col-md-2">' +
                                                            '<button class="btn" onclick="deletemea(' + count_mea + ')">' +
                                                            '<i class="bi bi-x-circle"></i>' +
                                                            '</button>' +
                                                            '</div>';
                                                        div.innerHTML = input;
                                                        aria_show_mea.appendChild(div);
                                                    });
                                                }
                                                loopmea();
                                                button_add_mea.addEventListener('click', function() {
                                                    count_mea++;
                                                    // console.log(count_mea);
                                                    var div = document.createElement('div');
                                                    div.className = 'row';
                                                    div.id = 'row_mea' + count_mea;
                                                    input =
                                                        '<div class="col-md-4 text-center">' +
                                                        '<input type="text"  name="add_mea_name_[' + count_mea +']" class="form-control"  placeholder="ชื่อการวัด" required style="height: 30px;">' + 
                                                        '</div>' +
                                                        '<div class="col-md-4">' +
                                                        '<input type="number" name="add_mea_number_[' + count_mea +']" class="form-control" style="height: 30px;" placeholder="ค่าการวัด" min="1" max="100" required step="0.01">' +
                                                        '</div>' +
                                                        '<div class="col-md-2">' +
                                                        '<button class="btn" onclick="deletemea(' + count_mea +
                                                        ')"> <i class="bi bi-x-circle"></i></button>' +
                                                        '</div>';

                                                    div.innerHTML = input;
                                                    aria_show_mea.appendChild(div);
                                                });


                                                selecttype_dress.addEventListener('change', function() {
                                                    if (selecttype_dress.value == "ชุดราตรี") {
                                                        // console.log('ตรี');
                                                        list_mea = ['ยาวหน้า', 'ยาวหลัง', 'ไหล่กว้าง', 'รอบคอ', 'รอบอก', 'อกห่าง', 'อกสูง',
                                                            'รอบเอว', 'สะโพก', 'กระโปรงยาว', 'แขนยาว'
                                                        ];
                                                        count_mea = 0;
                                                        loopmea();

                                                    } else if (selecttype_dress.value == "ชุดไทย") {
                                                        // console.log('ไทย');
                                                        list_mea = ['ยาวหน้า', 'ยาวหลัง', 'ไหล่กว้าง', 'รอบคอ', 'รอบอก', 'รอบเอว', 'สะโพก',
                                                            'เสื้อยาว', 'แขนยาว', 'กระโปรงยาว'
                                                        ];
                                                        count_mea = 0;
                                                        loopmea();
                                                    } else {
                                                        // console.log('อื่นๆ');
                                                        list_mea = [
                                                            'ยาวหน้า', 'ยาวหลัง', 'ไหล่กว้าง', 'บ่าหน้า', 'บ่าหลัง', 'รอบคอ', 'อกสูง',
                                                            'รอบเอว', 'สะโพกเล็ก', 'สะโพก', 'กระโปรงยาว', 'แขนยาว', 'แขนกว้าง',
                                                            'เสื้อยาว', 'ต้นขา', 'ปลายขา', 'เป้า', 'กางเกงยาว'
                                                        ];

                                                        count_mea = 0;
                                                        loopmea();
                                                    }
                                                });
                                            });

                                            function deletemea(count_mea) {
                                                var deleterow = document.getElementById('row_mea' + count_mea);
                                                deleterow.remove();
                                            }
                                        </script>










                                        {{-- <script>
                                            var aria_show_mea = document.getElementById('aria_show_mea');
                                            var count_mea = 0;
                                            var list_mea = [
                                                'ยาวหน้า', 'ยาวหลัง', 'ไหล่', 'บ่าหน้า', 'บ่าหลัง', 'รอบคอ', 'อกสูง',
                                                'รอบเอว', 'สะโพกเล็ก', 'สะโพก', 'กระโปรงยาว', 'แขนยาว', 'แขนกว้าง',
                                                'เสื้อยาว', 'ต้นขา', 'ปลายขา', 'เป้า', 'กางเกงยาว'
                                            ];

                                            document.addEventListener('DOMContentLoaded', function() {
                                                list_mea.forEach(function(mea, index) {
                                                    count_mea++;
                                                    var div = document.createElement('div');
                                                    div.id = 'mea_row' + count_mea;
                                                    div.className = 'row mb-2';
                                                    input =
                                                        '<div class="col-md-4">' +
                                                        '<input type="text" class="form-control" value=" ' + mea + ' " name="add_mea_name_[' +
                                                        count_mea + ']" readonly style="width: 120px; height: 30px; font-size: 14px;">' +
                                                        '</div>' +
                                                        '<div class="col-md-4">' +
                                                        '<input type="number" class="form-control" name="add_mea_number_[' + count_mea + ']"  step="0.1" required style="width: 90px; height: 30px; font-size: 14px;">' +
                                                        '</div>' +
                                                        '<div class="col-md-4">' +
                                                        '<button type="button" class="btn btn-danger delete-btn" style="padding: 2px 10px; font-size: 14px;" onclick="deletemea(' +
                                                        count_mea + ')" >ลบ</button>' +
                                                        '</div>';

                                                    div.innerHTML = input;
                                                    aria_show_mea.appendChild(div);
                                                });
                                            });
                                            function deletemea(count_mea) {
                                                    var deleterow = document.getElementById('mea_row' + count_mea);
                                                    deleterow.remove();
                                                }
                                        </script> --}}







                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-lg w-100 btn btn-success">บันทึกข้อมูล</button>
                        </div>
                    </form>
                </div>
            </div>
        </form>
    </div>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            var button_add_mea = document.getElementById('button_add_mea');
            var aria_show_mea = document.getElementById('aria_show_mea');
            var count_mea = 0;
            var list = ['ยาวหน้า', 'ยาวหลัง', 'ไหล่', 'บ่าหน้า', 'รอบคอ', 'รักแร้', 'รอบอก', 'อกห่าง', 'รอบเอว',
                'สะโพก', 'กระโปรงยาว', 'เสื้อยาว'
            ];

            function datameasurent() {
                aria_show_mea.innerHTML = '';
                list.forEach(function(measurement, index) {
                    count_mea++;
                    console.log(measurement);
                    var div = document.createElement('div');
                    div.id = 'row_aria_mea' + count_mea;
                    div.className = 'row';
                    input_mea =

                        '<div class="col-md-4">' +
                        '<input required type="text" name="add_mea_name_[' + count_mea +
                        ']" class="form-control" placeholder="ชื่อการวัด" value="' + measurement + '">' +
                        '</div>' +
                        '<div class="col-md-6">' +
                        '<div class="input-group">' +
                        '<input type="number" required name="add_mea_number_[' + count_mea +
                        ']" class="form-control" step="0.01">' +
                        '<span class="input-group-text">นิ้ว</span>' +
                        '</div>' +
                        '</div>' +
                        '<div class="col-md-2">' +
                        '<button type="button" class="btn btn-outline-danger btn-sm" onclick="deletemea(' +
                        count_mea + ')"><i class="bi bi-trash"></i></button>' +
                        '</div>';

                    div.innerHTML = input_mea;
                    aria_show_mea.appendChild(div);
                });
            }


            button_add_mea.addEventListener('click', function() {
                count_mea++;
                var div = document.createElement('div');
                div.id = 'row_aria_mea' + count_mea;
                div.className = 'row';

                var input_mea =
                    '<div class="col-md-4">' +
                    '<input re type="text" required name="add_mea_name_[' + count_mea +
                    ']" class="form-control"  placeholder="ชื่อการวัด">' +
                    '</div>' +
                    '<div class="col-md-6">' +
                    '<div class="input-group">' +
                    '<input type="number" required name="add_mea_number_[' + count_mea +
                    ']" class="form-control" step="0.01">' +
                    '<span class="input-group-text">นิ้ว</span>' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-md-2">' +
                    '<button type="button" class="btn btn-outline-danger btn-sm" onclick="deletemea(' +
                    count_mea + ')"><i class="bi bi-trash"></i></button>' +
                    '</div>';

                div.innerHTML = input_mea;
                aria_show_mea.appendChild(div);
            });
            document.getElementById('type_dress').addEventListener('change', function() {
                var type_dress = document.getElementById('type_dress').value;
                if (type_dress === "ชุดไทย") {
                    list = ['ยาวหน้า', 'ยาวหลัง', 'ไหล่กว้าง', 'รอบคอ', 'รอบอก', 'รอบเอว', 'สะโพก',
                        'เสื้อยาว', 'แขนยาว', 'กระโปรงยาว'
                    ];
                    count_mea = 0;
                    datameasurent();
                } else if (type_dress === "ชุดราตรี") {
                    list = ['ยาวหน้า', 'ยาวหลัง', 'ไหล่กว้าง', 'รอบคอ', 'รอบอก', 'อกห่าง', 'อกสูง',
                        'รอบเอว', 'สะโพก', 'กระโปรงยาว', 'แขนยาว'
                    ];
                    count_mea = 0;
                    datameasurent();
                } else {
                    list = ['ยาวหน้า', 'ยาวหลัง', 'ไหล่', 'บ่าหน้า', 'บ่าหลัง', 'รอบคอ', 'รักแร้', 'รอบอก',
                        'อกห่าง', 'รอบเอว', 'สะโพก', 'กระโปรงยาว', 'แขนยาว', 'เสื้อยาว'
                    ];
                    datameasurent();
                }
            });
            datameasurent();
        });

        function deletemea(count_mea) {
            var deleterow = document.getElementById('row_aria_mea' + count_mea);
            deleterow.remove();

        }
    </script> --}}
@endsection
