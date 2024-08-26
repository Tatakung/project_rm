<div class="modal fade" id="showfail" role="dialog" aria-hidden="true">
    <div class="modal-dialog custom-modal-dialog" role="document">
        <div class="modal-content custom-modal-content" style="max-width: 350px; height: 50px; width: 100%; margin: auto; background-color: #EE4E4E; border: 2px solid #EE4E4E; ">
            <div class="modal-body" style="padding: 10px; display: flex; align-items: center; justify-content: center;">
                <p style="margin: 0; color: #ffffff;">{{ session('fail') }}</p>
            </div>
        </div>
    </div>
</div>



<script>
    @if(session('fail'))
    setTimeout(function() {
        $('#showfail').modal('show');
    }, 500);
    @endif
</script>


<div class="modal fade" id="showsuccessss" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #EAD8C0">
                บันทึกข้อมูลสำเร็จ
            </div>
            <div class="modal-body">
                <strong>กรุณานำรหัสชุดเหล่านี้ไปติดไว้ กำกับกับชุดที่ได้เพิ่ม:</strong>
                <ul>
                    @if (session('dressCodes'))
                    @foreach (session('dressCodes') as $code)
                    <li>{{ $code }}</li>
                    @endforeach
                    @endif
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">ตกลง</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
    @if(session('dressCodes'))
    setTimeout(function() {
        $('#showsuccessss').modal('show');
    }, 500);
    @endif
</script>
@extends('layouts.adminlayout')
@section('content')
<div class="container mt-4">
    <form action="{{ route('admin.savedress') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <h3 class="mb-4" style="text-align: center ; ">แบบฟอร์มเพิ่มชุด</h3>
        <div class="shadow p-4 mb-5 bg-white rounded">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="type_dress_id">ประเภทชุด</label>
                    <select name="type_dress_id" id="type_dress_id" class="form-control" required>
                        <option value="" disabled selected>ประเภทชุดที่ต้องการเพิ่ม</option>
                        @foreach ($typeDresses as $typeDress)
                        <option value="{{ $typeDress->id }}">{{ $typeDress->type_dress_name }}</option>
                        @endforeach
                        <option value="select_other">อื่นๆ</option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <div style="display: none" id="showinputother">
                        <label class="form-label" for="inputother">อื่นๆโปรดระบุ</label>
                        <input class="form-control" type="text" name="inputother" id="inputother">
                    </div>
                </div>
                <script>
                    var selectdresstype = document.getElementById('type_dress_id');
                    var showshowinputother = document.getElementById('showinputother');
                    var present_inputother = document.getElementById('inputother');
                    selectdresstype.addEventListener('change', function() {
                        if (selectdresstype.value == "select_other") {
                            showshowinputother.style.display = "block";
                            present_inputother.setAttribute('required', 'required')
                        } else {
                            showshowinputother.style.display = "none";
                            present_inputother.value = '';
                            present_inputother.removeAttribute('required');
                        }
                    });
                </script>
                <div class="col-md-3">
                    <label for="dress_price">ราคาชุด/ครั้ง</label>
                    <input type="number" name="dress_price" id="dress_price" class="form-control" placeholder="จำนวนบาท" required min="1">
                </div>
                <div class="col-md-3">
                    <label for="dress_deposit">ราคามัดจำ/ครั้ง</label>
                    <input type="number" name="dress_deposit" id="dress_deposit" class="form-control" placeholder="จำนวนบาท" required min="1">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="dress_count">ประกันค่าเสียหายชุด</label>
                    <input type="number" name="damage_insurance" id="damage_insurance" class="form-control" placeholder="จำนวนบาท" required min="0">
                </div>
                <div class="col-md-3">
                    <label for="dress_count">จำนวนชุด</label>
                    <input type="number" name="dress_count" id="dress_count" class="form-control" required min="1" value="1">
                </div>
                <div class="col-md-6">
                    <label for="note" class="form-label">รายละเอียดอื่นๆ</label>
                    <textarea class="form-control" id="dress_description" name="dress_description" rows="3" placeholder="ใส่รายละเอียดเพิ่มเติมที่เกี่ยวข้อง"></textarea>
                </div>
            </div>


            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">การเช่าชุด</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="separable" id="separate_rent_no" value="1" required>
                        <label class="form-check-label" for="separate_rent_no">
                            ไม่สามารถเช่าแยกได้(เช่าเป็นชุด)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="separable" id="separate_rent_yes" value="2" required>
                        <label class="form-check-label" for="separate_rent_yes">
                            สามารถเช่าแยกได้(เสื้อ+กระโปรง/กางเกง)
                        </label>
                    </div>

                </div>
                <div class="col-md-6">
                <div id="aria_input_show_image">                    
                    
                    <label for="imagerent">อัปโหลดรูปภาพ</label>    
                    <input type="file" class="form-control" id="add_image" name="add_image" required>
                    </div>
                </div>
            </div>
                <script>
                    var button_addimage = document.getElementById('addimage');
                    var aria_input_show_image = document.getElementById('aria_input_show_image');
                    var count_image = 1;
                    button_addimage.addEventListener('click', function() {
                        count_image++;
                        var divrow = document.createElement('div');
                        divrow.className = 'row mb-3';
                        divrow.id = 'row_image' + count_image;

                        var label = document.createElement('label');
                        label.htmlFor = 'label';
                        label.className = 'col-sm-2';
                        label.innerHTML = 'อัปโหลดรูปภาพ';

                        // divrow.appendChild(label) ; 

                        var divone = document.createElement('div');
                        divone.className = 'col-md-7';

                        var input = document.createElement('input');
                        input.type = 'file';
                        input.className = 'form-control';
                        input.id = 'imagerent' + count_image;
                        input.name = 'imagerent_[' + count_image + ']';
                        input.required = true;

                        divone.appendChild(input);

                        var divtwo = document.createElement('div');
                        divtwo.className = 'col-md-2';

                        var button = document.createElement('button');
                        button.className = 'form-control btn btn-danger';
                        button.type = 'button';
                        button.innerHTML = 'ลบ';

                        divtwo.appendChild(button);

                        divrow.appendChild(label);
                        divrow.appendChild(divone);
                        divrow.appendChild(divtwo);
                        aria_input_show_image.appendChild(divrow);

                        button.addEventListener('click', function() {
                            divrow.remove();
                        });


                    });
                </script>

        

        </div>



        {{-- กล่องกรณีที่เป็นชุด --}}
            <div id="Big_show_aria_no_separated" style="display: none;">
                <h3 style="text-align: center ; ">กรณีที่เป็นชุด</h3>
                <div class="shadow p-4 mb-5 bg-white rounded">
                <div class="row mb-2">
                    <div class="col-md-2">
                        {{-- <h4 class="mb-0">ข้อมูลเสื้อ</h4> --}}
                        <button type="button" class="btn btn-success" id="button_add_mea_no_separated">+
                            เพิ่มการวัด</button>
                    </div>
                    </div>
                    <div id="show_aria_no_separated">
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <th>ชื่อการวัด</th>
                            </div>
                            <div class="col-md-4">
                                <th>ขนาด</th>
                            </div>
                            <div class="col-md-4">
                                <th>หน่วย</th>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="no_shirt_measurement_dress_name_[1]"
                                    id="no_shirt_measurement_dress_name1" value="รอบอก" required readonly>
                            </div>
                            <div class="col-md-4">
                                <input type="number"
                                    name="no_shirt_measurement_dress_number_[1]"id="no_shirt_measurement_dress_number1"
                                    class="form-control" max="100" min="0" step="0.01" placeholder="ขนาด">
                            </div>
                            <div class="col-md-4">
                                <p class="form-control-static mt-2 ml-3 ">นิ้ว</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="no_shirt_measurement_dress_name_[2]"
                                    id="no_shirt_measurement_dress_name2" value="รอบเอว" required readonly>
                            </div>
                            <div class="col-md-4">
                                <input type="number"
                                    name="no_shirt_measurement_dress_number_[2]"id="no_shirt_measurement_dress_number2"
                                    class="form-control" max="100" min="0" step="0.01" placeholder="ขนาด">
                            </div>
                            <div class="col-md-4">
                                <p class="form-control-static mt-2 ml-3 ">นิ้ว</p>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="no_shirt_measurement_dress_name_[3]"
                                    id="no_shirt_measurement_dress_name3" value="รอบสะโพก" required readonly>
                            </div>
                            <div class="col-md-4">
                                <input type="number"
                                    name="no_shirt_measurement_dress_number_[3]"id="no_shirt_measurement_dress_number3"
                                    class="form-control" max="100" min="0" step="0.01" placeholder="ขนาด">
                            </div>
                            <div class="col-md-4">
                                <p class="form-control-static mt-2 ml-3">นิ้ว</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="no_shirt_measurement_dress_name_[4]"
                                    id="no_shirt_measurement_dress_name4" value="ไหล่กว้าง" required readonly>
                            </div>
                            <div class="col-md-4">
                                <input type="number"
                                    name="no_shirt_measurement_dress_number_[4]"id="no_shirt_measurement_dress_number4"
                                    class="form-control" max="100" min="0" step="0.01" placeholder="ขนาด">
                            </div>
                            <div class="col-md-4">
                                <p class="form-control-static mt-2 ml-3">นิ้ว</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="no_shirt_measurement_dress_name_[5]"
                                    id="no_shirt_measurement_dress_name5" value="เสื้อยาว" required readonly>
                            </div>
                            <div class="col-md-4">
                                <input type="number"
                                    name="no_shirt_measurement_dress_number_[5]"id="no_shirt_measurement_dress_number5"
                                    class="form-control" max="100" min="0" step="0.01" placeholder="ขนาด">
                            </div>
                            <div class="col-md-4">
                                <p class="form-control-static mt-2 ml-3">นิ้ว</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="no_shirt_measurement_dress_name_[6]"
                                    id="no_shirt_measurement_dress_name6" value="แขนยาว" required readonly>
                            </div>
                            <div class="col-md-4">
                                <input type="number"
                                    name="no_shirt_measurement_dress_number_[6]"id="no_shirt_measurement_dress_number6"
                                    class="form-control" max="100" min="0" step="0.01" placeholder="ขนาด">
                            </div>
                            <div class="col-md-4">
                                <p class="form-control-static mt-2 ml-3">นิ้ว</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="no_shirt_measurement_dress_name_[7]"
                                    id="no_shirt_measurement_dress_name7" value="กระโปรงยาว" required readonly>
                            </div>
                            <div class="col-md-4">
                                <input type="number"
                                    name="no_shirt_measurement_dress_number_[7]"id="no_shirt_measurement_dress_number7"
                                    class="form-control" max="100" min="0" step="0.01" placeholder="ขนาด">
                            </div>
                            <div class="col-md-4">
                                <p class="form-control-static mt-2 ml-3">นิ้ว</p>
                            </div>
                        </div>





                        <script>
                            var button_add_mea_no_separated = document.getElementById('button_add_mea_no_separated');
                            var show_aria_no_separated = document.getElementById('show_aria_no_separated');
                            var count_no_separated = 7;
                            button_add_mea_no_separated.addEventListener('click', function() {
                                count_no_separated++;
                                var div_mea_no = document.createElement('div');
                                div_mea_no.className = 'row';
                                div_mea_no.id = 'row_mea_no_separated' + count_no_separated;

                                input =

                                    '<div class="col-md-4">' +
                                    '<select name="" class="form-control" required name="no_shirt_measurement_dress_name_[' +
                                    count_no_separated +
                                    ']" >' +
                                    '<option value="" disabled selected>เลือกรายการ</option>' +
                                    '<option value="ยาวหน้า">ยาวหน้า</option>' +
                                    '<option value="ยาวหลัง">ยาวหลัง</option>' +
                                    '<option value="บ่าหน้า">บ่าหน้า</option>' +
                                    '<option value="บ่าหลัง">บ่าหลัง</option>' +
                                    '<option value="รอบคอ">รอบคอ</option>' +
                                    '<option value="รักแท้">รักแท้</option>' +
                                    '<option value="อกห่าง">อกห่าง</option>' +
                                    '<option value="อกสูง">อกสูง</option>' +
                                    '<option value="สะโพกเล็ก">สะโพกเล็ก</option>' +
                                    '<option value="แขนกว้าง">แขนกว้าง</option>' +
                                    '<option value="ต้นขา">ต้นขา</option>' +
                                    '<option value="ปลายขา">ปลายขา</option>' +
                                    '<option value="เป้า">เป้า</option>' +
                                    '<option value="กางเกงยาว">กางเกงยาว</option>' +
                                    '</select>' +
                                    '</div>' +
                                    '<div class="col-md-4">' +
                                    '<input type="number" name="no_shirt_measurement_dress_number_[' + count_no_separated +
                                    ']" id="no_shirt_measurement_dress_number ' + count_no_separated +
                                    ' " class="form-control" placeholder="ขนาด" required max="100" min="0" step="0.01">' +
                                    '</div>' +
                                    '<div class="col-md-1">' +
                                    '<p class="form-control-static mt-2 ml-3 ">นิ้ว</p>' +
                                    '</div>' +
                                    '<div class="col-md-3">' +
                                    '<button class="btn btn-danger" onclick="deletenoseparated(' + count_no_separated +
                                    ')">ลบ</button>' +
                                    '</div>';

                                div_mea_no.innerHTML = input;
                                show_aria_no_separated.appendChild(div_mea_no);
                            });

                            function deletenoseparated(count_no_separated) {
                                var Deletenoseparated = document.getElementById('row_mea_no_separated' + count_no_separated);
                                Deletenoseparated.remove();
                            }
                        </script>
                    </div>
                </div>

            </div>

            {{-- กล่องกรณีที่ชุดสามารถเช่าแยกได้ --}}
            <div id="Big_show_aria_yes_separated" style="display: none;">
                <h3 style="text-align: center ; ">กรณีที่ชุดสามารถเช่าแยกได้(เสื้อ + กระโปรง/กางเกง)</h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="shadow p-4 mb-5 bg-white rounded">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="mb-0">ข้อมูลเสื้อ</h4>
                                <button type="button" class="btn btn-success" id="button_add_mea_shirt">+
                                    เพิ่มการวัดเสื้อ</button>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="">ราคาเสื้อ</label>
                                    <input type="number" name="shirt_price" id="shirt_price" class="form-control"
                                        placeholder="ราคา" min="1">
                                </div>
                                <div class="col-md-4">
                                    <label for="">มัดจำราคาเสื้อ</label>
                                    <input type="number" name="shirt_deposit" id="shirt_deposit" class="form-control"
                                        placeholder="ราคา" min="1">
                                </div>
                                <div class="col-md-4">
                                    <label for="">ประกันค่าเสียหาย</label>
                                    <input type="number" name="shirt_damage_insurance" id="shirt_damage_insurance"
                                        class="form-control" placeholder="ราคา" min="1">
                                </div>
                            </div>
                            <div id="aria_input_shirt">
                                {{-- พื้นที่แสดงผล --}}
                                <div class="row">
                                    <div class="col-md-4">
                                        <p>ชื่อการวัด</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p>ขนาด</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p>หน่วย</p>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">

                                        <input type="text" class="form-control"
                                            name="yes_shirt_measurement_dress_name_[1]"
                                            id="yes_shirt_measurement_dress_name1" value="รอบอก" required readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number"
                                            name="yes_shirt_measurement_dress_number_[1]"id="yes_shirt_measurement_dress_number1"
                                            class="form-control" placeholder="ขนาด">
                                    </div>
                                    <div class="col-md-4">
                                        <p class="form-control-static mt-2 ml-3">นิ้ว</p>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control"
                                            name="yes_shirt_measurement_dress_name_[2]"
                                            id="yes_shirt_measurement_dress_name2" value="ไหล่กว้าง" required readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number"
                                            name="yes_shirt_measurement_dress_number_[2]"id="yes_shirt_measurement_dress_number2"
                                            class="form-control" placeholder="ขนาด">
                                    </div>
                                    <div class="col-md-4">
                                        <p class="form-control-static mt-2 ml-3">นิ้ว</p>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control"
                                            name="yes_shirt_measurement_dress_name_[3]"
                                            id="yes_shirt_measurement_dress_name3" value="เสื้อยาว" required readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number"
                                            name="yes_shirt_measurement_dress_number_[3]"id="yes_shirt_measurement_dress_number3"
                                            class="form-control" placeholder="ขนาด">
                                    </div>
                                    <div class="col-md-4">
                                        <p class="form-control-static mt-2 ml-3">นิ้ว</p>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control"
                                            name="yes_shirt_measurement_dress_name_[4]"
                                            id="yes_shirt_measurement_dress_name4" value="แขนยาว" required readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number"
                                            name="yes_shirt_measurement_dress_number_[4]"id="yes_shirt_measurement_dress_number4"
                                            class="form-control" placeholder="ขนาด">
                                    </div>
                                    <div class="col-md-4">
                                        <p class="form-control-static mt-2 ml-3">นิ้ว</p>
                                    </div>
                                </div>

                                <script>
                                    var button_add_mea_shirt = document.getElementById('button_add_mea_shirt'); //ปุ่มเพิ่มการวัด
                                    var aria_input_shirt = document.getElementById('aria_input_shirt'); // พื้นที่แสดงผล
                                    var count_shirt = 4;

                                    button_add_mea_shirt.addEventListener('click', function() {
                                        count_shirt++;
                                        var div = document.createElement('div');
                                        div.className = 'row mb-3';
                                        div.id = 'row_shirt' + count_shirt;


                                        input =

                                            '<div class="col-md-4">' +
                                            '<select class="form-control" required  name="yes_shirt_measurement_dress_name_[' + count_shirt +
                                            ']">' +
                                            '<option value="" disabled selected>เลือกรายการ</option>' +
                                            '<option value="ยาวหน้า">ยาวหน้า</option>' +
                                            '<option value="ยาวหลัง">ยาวหลัง</option> ' +
                                            '<option value="บ่าหน้า">บ่าหน้า</option>' +
                                            '<option value="บ่าหลัง">บ่าหลัง</option>' +
                                            '<option value="รอบคอ">รอบคอ</option>' +
                                            '<option value="รักแท้">รักแท้</option>' +
                                            '<option value="อกห่าง">อกห่าง</option>' +
                                            '<option value="อกสูง">อกสูง</option>' +
                                            '<option value="แขนกว้าง">แขนกว้าง</option>' +
                                            '</select>' +
                                            '</div>' +
                                            '<div class="col-md-4">' +
                                            '<input type="number" name="yes_shirt_measurement_dress_number_[' + count_shirt +
                                            ']"id="yes_shirt_measurement_dress_number ' + count_shirt +
                                            '  " class="form-control" placeholder="ขนาด" required>' +
                                            '</div>' +
                                            '<div class="col-md-2">' +
                                            '<p class="form-control-static mt-2 ml-3">นิ้ว</p>' +
                                            '</div>' +
                                            '<div class="col-md-1">' +
                                            '<button type="button" class="btn btn-danger" onclick="deleteshirt(' + count_shirt +
                                            ')">ลบ</button>' +
                                            '</div>';
                                        div.innerHTML = input;
                                        aria_input_shirt.appendChild(div);

                                    });

                                    function deleteshirt(count_shirt) {
                                        var deletediv = document.getElementById('row_shirt' + count_shirt);
                                        deletediv.remove();
                                    }
                                </script>
                            </div>

                            <div>
                            </div>
                        </div>
                    </div>


                    {{-- กล่องที่สาม --}}
                    <div class="col-md-6">
                        <div class="shadow p-4 mb-5 bg-white rounded">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="mb-0">ข้อมูลกระโปรง</h4>
                                <button type="button" class="btn btn-success" id="button_add_mea_skirt">+
                                    เพิ่มการวัดกระโปรง</button>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="skirt_price">ราคากระโปรง</label>
                                    <input type="number" name="skirt_price" id="skirt_price" class="form-control"
                                        placeholder="ราคา" min="1">
                                </div>
                                <div class="col-md-4">
                                    <label for="skirt_deposit">มัดจำราคากระโปรง</label>
                                    <input type="number" name="skirt_deposit" id="skirt_deposit" class="form-control"
                                        placeholder="ราคา" min="1">
                                </div>
                                <div class="col-md-4">
                                    <label for="">ประกันค่าเสียหาย&nbsp;</label>
                                    <input type="number" name="skirt_damage_insurance" id="skirt_damage_insurance"
                                        class="form-control" placeholder="ราคา" min="1">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <p>ชื่อการวัด</p>
                                </div>
                                <div class="col-md-4">
                                    <p>ขนาด</p>
                                </div>
                                <div class="col-md-4">
                                    <p>หน่วย</p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">

                                    <input type="text" class="form-control"
                                        name="yes_skirt_measurement_dress_name_[1]" id="yes_skirt_measurement_dress_name1"
                                        value="รอบเอว" required readonly>
                                </div>
                                <div class="col-md-4">

                                    <input type="number"
                                        name="yes_skirt_measurement_dress_number_[1]"id="yes_skirt_measurement_dress_number1"
                                        class="form-control" min="1" max="100" step="0.01" placeholder="ขนาด">
                                </div>
                                <div class="col-md-4">
                                    <p class="form-control-static mt-2 ml-3">นิ้ว</p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">

                                    <input type="text" class="form-control"
                                        name="yes_skirt_measurement_dress_name_[2]" id="yes_skirt_measurement_dress_name2"
                                        value="รอบสะโพก" required readonly>
                                </div>
                                <div class="col-md-4">

                                    <input type="number"
                                        name="yes_skirt_measurement_dress_number_[2]"id="yes_skirt_measurement_dress_number2"
                                        class="form-control" min="1" max="100" step="0.01" placeholder="ขนาด">
                                </div>
                                <div class="col-md-4">
                                    <p class="form-control-static mt-2 ml-3">นิ้ว</p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">

                                    <input type="text" class="form-control"
                                        name="yes_skirt_measurement_dress_name_[3]" id="yes_skirt_measurement_dress_name3"
                                        value="กระโปรงยาว" required readonly>
                                </div>
                                <div class="col-md-4">

                                    <input type="number"
                                        name="yes_skirt_measurement_dress_number_[3]"id="yes_skirt_measurement_dress_number3"
                                        class="form-control" min="1" max="100" step="0.01" placeholder="ขนาด">
                                </div>
                                <div class="col-md-4">
                                    <p class="form-control-static mt-2 ml-3">นิ้ว</p>
                                </div>
                            </div>
                            <div id="aria_input_skirt">
                                {{-- พื้นที่แสดงผล --}}


                            </div>

                            <script>
                                var button_add_mea_skirt = document.getElementById('button_add_mea_skirt'); //กดเพิ่ม
                                var aria_input_skirt = document.getElementById('aria_input_skirt'); //แสดงผล
                                var count_skirt = 3;
                                button_add_mea_skirt.addEventListener('click', function() {
                                    count_skirt++;

                                    var div_skirt = document.createElement('div');
                                    div_skirt.className = 'row mb-3';
                                    div_skirt.id = 'row_skirt' + count_skirt;

                                    input =

                                        '<div class="col-md-4">' +
                                        '<select class="form-control" required name="yes_skirt_measurement_dress_name_[' + count_skirt +
                                        ']">' +
                                        '<option value="" disabled selected>เลือกรายการ</option>' +
                                        '<option value="ต้นขา">ต้นขา</option>' +
                                        '<option value="ปลายขา">ปลายขา</option>' +
                                        '<option value="เป้า">เป้า</option>' +
                                        '<option value="กางเกงยาว">กางเกงยาว</option>' +
                                        '</select>' +

                                        '</div>' +
                                        '<div class="col-md-4">' +
                                        '<input type="number" name="yes_skirt_measurement_dress_number_[' + count_skirt +
                                        ']"id="yes_skirt_measurement_dress_number ' + count_skirt +
                                        ' " class="form-control" placeholder="ขนาด" min="1" max="100" step="0.01">' +
                                        '</div>' +
                                        '<div class="col-md-2">' +
                                        '<p class="form-control-static mt-2 ml-3">นิ้ว</p>' +
                                        '</div>' +
                                        '<div class="col-md-1">' +
                                        '<button type="button" class="btn btn-danger" onclick="deleteskirt(' + count_skirt +
                                        ')">ลบ</button>' +
                                        '</div>';
                                    div_skirt.innerHTML = input;
                                    aria_input_skirt.appendChild(div_skirt);
                                });

                                function deleteskirt(count_skirt) {
                                    var deleteskirt = document.getElementById('row_skirt' + count_skirt);
                                    deleteskirt.remove();
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            <div style="display: flex; justify-content: center;">
                <button class="btn btn-success" type="submit">ยืนยันการเพิ่มชุด</button>
            </div>
        </form>
    </div>


    <script>
        var Big_show_aria_no_separated = document.getElementById('Big_show_aria_no_separated');
        var Big_show_aria_yes_separated = document.getElementById('Big_show_aria_yes_separated');
        var separate_rent_yes = document.getElementById('separate_rent_yes');
        var separate_rent_no = document.getElementById('separate_rent_no');


        separate_rent_yes.addEventListener('change', function() {
            if (this.checked) {
                Big_show_aria_yes_separated.style.display = 'block';
                Big_show_aria_no_separated.style.display = 'none';
                document.getElementById('no_shirt_measurement_dress_number1').removeAttribute('required');
                document.getElementById('no_shirt_measurement_dress_number2').removeAttribute('required');
                document.getElementById('no_shirt_measurement_dress_number3').removeAttribute('required');

                document.getElementById('shirt_price').setAttribute('required', 'required');
                document.getElementById('shirt_deposit').setAttribute('required', 'required');
                document.getElementById('shirt_damage_insurance').setAttribute('required', 'required');


                document.getElementById('skirt_price').setAttribute('required', 'required');
                document.getElementById('skirt_deposit').setAttribute('required', 'required');
                document.getElementById('skirt_damage_insurance').setAttribute('required', 'required');

            }
        });

        separate_rent_no.addEventListener('change', function() {
            if (this.checked) {
                Big_show_aria_no_separated.style.display = 'block';
                Big_show_aria_yes_separated.style.display = 'none';
                document.getElementById('no_shirt_measurement_dress_number1').setAttribute('required', 'required');
                document.getElementById('no_shirt_measurement_dress_number2').setAttribute('required', 'required');
                document.getElementById('no_shirt_measurement_dress_number3').setAttribute('required', 'required');

                document.getElementById('shirt_price').removeAttribute('required');
                document.getElementById('shirt_deposit').removeAttribute('required');
                document.getElementById('shirt_damage_insurance').removeAttribute('required');


                document.getElementById('skirt_price').removeAttribute('required');
                document.getElementById('skirt_deposit').removeAttribute('required');
                document.getElementById('skirt_damage_insurance').removeAttribute('required');


            }
        });
    </script>
@endsection
