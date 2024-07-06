@extends('layouts.adminlayout')

@section('content')
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
            },500);
        @endif
    </script>








    <form action="{{ route('employee.savecutdress') }}" method="POST" enctype="multipart/form-data">
        <div class="container mt-4">
            <!-- กล่องแรก: ฟอร์มเพิ่มออเดอร์ -->
            <div class="shadow p-4 mb-5 bg-white rounded">
                <h4 class="mb-4">ข้อมูลตัดชุด</h4>
                @csrf
                <div class="row mb-3">
                    <label for="dressType" class="col-sm-2 col-form-label">ประเภทชุดที่ตัด</label>
                    <div class="col-sm-3">
                        <select class="form-control" id="type_dress" name="type_dress" required>
                            <option value="" selected disabled>เลือกรายการ</option>
                            @foreach ($type_dress as $dressType)
                                <option value="{{ $dressType->type_dress_name }}">{{ $dressType->type_dress_name }}</option>
                            @endforeach
                            <option value="other_type">อื่นๆ</option>
                        </select>
                    </div>
                    <div class="col-sm-3" style="display: none;" id="showinput">
                        <input type="text" class="form-control" id="other_input" name="other_input"
                            placeholder="กรอกประเภทชุด">
                    </div>

                    <script>
                        var select_type = document.getElementById('type_dress');
                        var show_input_other = document.getElementById('showinput');
                        var input_type = document.getElementById('other_input');
                        select_type.addEventListener('change', function() {
                            if (select_type.value === 'other_type') {
                                show_input_other.style.display = 'block';
                            } else {
                                show_input_other.style.display = 'none';
                                input_type.value = '';

                            }
                        });
                    </script>

                </div>

                <div class="row mb-3">
                    <label for="pickup_date" class="col-sm-2 col-form-label">วันที่นัดรับชุด</label>
                    <div class="col-sm-3">
                        <input type="date" class="form-control" id="pickup_date" name="pickup_date" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="amount" class="col-sm-2 col-form-label">จำนวนชุด</label>
                    <div class="col-sm-3">
                        <input type="number" class="form-control" id="amount" name="amount" value="1"
                            min="1" required>
                    </div>


                </div>
                <div class="row mb-3">
                    <label for="price" class="col-sm-2 col-form-label">ราคาเต็ม/ชุด</label>
                    <div class="col-sm-3">
                        <input type="number" class="form-control" id="price" name="price" placeholder="ใส่ตัวเลข"
                            min="1" step="0.01" required>
                    </div>

                </div>
                <div class="row mb-3">
                    <label for="deposit" class="col-sm-2 col-form-label">ราคามัดจำ/ชุด</label>
                    <div class="col-sm-3">
                        <input type="number" class="form-control" id="deposit" name="deposit" placeholder="ใส่ตัวเลข"
                            min="1" step="0.01" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">ผ้า</label>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="cloth" id="cloth1" value="1">
                            <label class="form-check-label" for="cloth1">
                                ลูกค้านำผ้ามาเอง
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="cloth" id="cloth2" value="2">
                            <label class="form-check-label" for="cloth2">
                                ทางร้านหาผ้าให้
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="color" class="col-sm-2 col-form-label">สีของชุด</label>
                    <div class="col-sm-3">
                        <select class="form-control" id="color" name="color" required>
                            <option value="" disabled selected>--สี--</option>
                            <option value="ขาว">ขาว</option>
                            <option value="ครีม">ครีม</option>
                            <option value="ชมพู">ชมพู</option>
                            <option value="ดำ">ดำ</option>
                            <option value="ทอง">ทอง</option>
                            <option value="น้ำตาล">น้ำตาล</option>
                            <option value="น้ำเงิน">น้ำเงิน</option>
                            <option value="บานเย็น">บานเย็น</option>
                            <option value="พิ้งค์โกลด์">พิ้งค์โกลด์</option>
                            <option value="ฟ้า">ฟ้า</option>
                            <option value="ม่วง">ม่วง</option>
                            <option value="ส้ม">ส้ม</option>
                            <option value="เขียว">เขียว</option>
                            <option value="เทา">เทา</option>
                            <option value="เหลือง">เหลือง</option>
                            <option value="แดง">แดง</option>
                            <option value="ไม่ระบุ">ไม่ระบุ</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">การจ่ายเงิน</label>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status_payment" id="status_payment1"
                                value="1" checked>
                            <label class="form-check-label" for="status_payment1">
                                จ่ายมัดจำ
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status_payment" id="status_payment2"
                                value="2">
                            <label class="form-check-label" for="status_payment2">
                                จ่ายเต็มจำนวน
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="note" class="col-sm-2 col-form-label">รายละเอียดอื่นๆ</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" id="note" name="note" rows="4"
                            placeholder="ใส่รายละเอียดเพิ่มเติมที่เกี่ยวข้อง"></textarea>
                    </div>
                </div>



            </div>



            {{-- กล่องที่สอง --}}
            <div class="shadow p-4 mb-5 bg-white rounded">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">ข้อมูลการวัดสำหรับตัดชุด</h4>
                    <button type="button" class="btn btn-primary" id="addMeasurementField">+ เพิ่มการวัด</button>
                </div>

                <div id="aria_show_measurement">
                    <div class="row mb-3" id="showmea0">
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="mea_name0" name="mea_name_[0]"
                                placeholder="เพิ่มชื่อการวัดเช่น รอบอก">
                        </div>

                        <div class="col-sm-3">
                            <input type="number" class="form-control" id="mea_number0" name="mea_number_[0]"
                                placeholder="ใส่ตัวเลข">
                        </div>

                        <div class="col-sm-3">
                            <select class="form-control" name="mea_unit_[0]" id="mea_unit0">
                                <option value="นิ้ว">นิ้ว</option>
                                <option value="เซนติเมตร">เซนติเมตร</option>
                                <option value="มิลลิเมตร">มิลลิเมตร</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <button class="form-control btn btn-danger" type="button" onclick="remove(0)">ลบ</button>
                        </div>
                    </div>
                </div>
                <script>
                    var aria_show_measurement = document.getElementById('aria_show_measurement');
                    var button_add_measurement = document.getElementById('addMeasurementField');

                    var count_mea = 0;

                    button_add_measurement.addEventListener('click', function() {
                        count_mea++;

                        var creatediv = document.createElement('div')
                        creatediv.id = 'showmea' + count_mea;
                        creatediv.className = 'row mb-3';

                        input =

                            '<div class="col-sm-3">' +
                            '<input type="text" class="form-control" id="mea_name' + count_mea +
                            ' " name="mea_name_[' + count_mea + ']" placeholder="เพิ่มชื่อการวัดเช่น รอบอก" required >' +
                            '</div>' +

                            '<div class="col-sm-3">' +
                            '<input type="number" class="form-control" id="mea_number' + count_mea +
                            ' " name="mea_number_[' + count_mea + ']" placeholder="ใส่ตัวเลข" required >' +
                            '</div>' +

                            '<div class="col-sm-3">' +
                            '<select class="form-control" name="mea_unit_[' + count_mea + ']" id="mea_unit' +
                            count_mea + ' " required >' +
                            '<option value="นิ้ว">นิ้ว</option>' +
                            '<option value="เซนติเมตร">เซนติเมตร</option>' +
                            '<option value="มิลลิเมตร">มิลลิเมตร</option>' +
                            '</select>' +
                            '</div>' +
                            '<div class="col-sm-2">' +
                            '<button class="form-control btn btn-danger" type="button" onclick="remove(' + count_mea +
                            ')">ลบ</button>' +
                            '</div>';

                        creatediv.innerHTML = input;
                        aria_show_measurement.appendChild(creatediv);
                    });

                    function remove(count_mea) {
                        var deleteID = document.getElementById('showmea' + count_mea)
                        deleteID.remove();
                    }
                </script>
            </div>




            {{-- กล่องที่สอง --}}
            <div class="shadow p-4 mb-5 bg-white rounded">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">ข้อมูลการนัดลูกค้าลองชุด</h4>
                    <button type="button" class="btn btn-primary" id="addfitting">+ เพิ่มวันนัดลองชุด</button>
                </div>


                <div id="ariafitting">
                    {{-- <div class="row mb-3">
                    <div class="col-sm-3">
                        <label class="form-label">วันที่นัดลองชุด</label>
                    </div>
                    <div class="col-sm-3">
                        <input type="date" class="form-control" id="fitting_date" name="fitting_date">
                    </div>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="fitting_note" name="fitting_note" placeholder="รายละเอียด">
                    </div>
                    <div class="col-sm-2">
                        <button class="form-control btn btn-danger" type="button" onclick="removefitting()">ลบ</button>
                    </div>
                </div>   --}}
                </div>


                <script>
                    var button_add_fitting = document.getElementById('addfitting');
                    var aria_fitting = document.getElementById('ariafitting');

                    var count_fitting = 0;

                    button_add_fitting.addEventListener('click', function() {
                        count_fitting++;
                        var div = document.createElement('div');
                        div.className = 'row mb-3';
                        div.id = 'fitting' + count_fitting;

                        var input =

                            '<div class="col-sm-3">' +
                            '<label class="form-label">วันที่นัดลองชุด</label>' +
                            '</div>' +

                            '<div class="col-sm-3">' +
                            '<input type="date" class="form-control" id="fitting_date' + count_fitting +
                            '" name="fitting_date_[' + count_fitting + ']">' +
                            '</div>' +

                            '<div class="col-sm-3">' +
                            '<input type="text" class="form-control" id="fitting_note' + count_fitting +
                            ' " name="fitting_note_[' + count_fitting + ']" placeholder="รายละเอียด">' +
                            '</div>' +

                            '<div class="col-sm-2">' +
                            '<button class="form-control btn btn-danger" type="button"  onclick="removefitting(' +
                            count_fitting + ')" >ลบ</button>' +
                            '</div>';

                        div.innerHTML = input;
                        aria_fitting.appendChild(div);
                    });

                    function removefitting(count_fitting) {
                        var deleteElement = document.getElementById('fitting' + count_fitting)
                        deleteElement.remove();
                    }
                </script>






            </div>


            <!-- กล่องที่สาม: ฟอร์มรูปภาพ -->
            <div class="shadow p-4 mb-5 bg-white rounded">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-4">รูปภาพประกอบ / รูปภาพตัวแบบ / อื่นๆที่เกี่ยวข้อง</h4>
                    <button type="button" class="btn btn-primary" id="addimage">+ เพิ่มรูปภาพ</button>
                </div>

                <div id="aria_show_input_of_image">


                    {{-- <div class="row mb-3" id="image1">
                    <label for="image1" class="col-sm-2 col-form-label">อัปโหลดรูปภาพ</label>
                    <div class="col-sm-7">
                        <input type="file" class="form-control" id="image1" name="image_[1]">
                    </div>
                    <div class="col-sm-2">
                        <button class="form-control btn btn-danger" type="button" onclick="removeimage('image1')">ลบ</button>
                    </div>
                </div> --}}

                </div>

                <script>
                    var aria_show_input_of_image = document.getElementById('aria_show_input_of_image');
                    var button_add_image = document.getElementById('addimage');
                    var count_index = 1;
                    button_add_image.addEventListener('click', function() {
                        count_index++;

                        var divbig = document.createElement('div');
                        divbig.id = 'image' + count_index;
                        divbig.className = 'row mb-3';

                        var label = document.createElement('label');
                        label.htmlFor = 'image' + count_index;
                        label.className = 'col-sm-2 col-form-label';
                        label.innerHTML = 'อัปโหลดรูปภาพ';


                        var div_one = document.createElement('div');
                        div_one.className = 'col-sm-7';

                        var input_one = document.createElement('input');
                        input_one.type = 'file';
                        input_one.className = 'form-control';
                        input_one.id = 'image' + count_index;
                        input_one.name = 'image_[' + count_index + ']';

                        div_one.appendChild(input_one);


                        var div_two = document.createElement('div');
                        div_two.className = 'col-sm-2';

                        var button = document.createElement('button');
                        button.className = 'form-control btn btn-danger';
                        button.type = 'button';
                        button.innerHTML = 'ลบ';

                        div_two.appendChild(button);

                        divbig.appendChild(label);
                        divbig.appendChild(div_one);
                        divbig.appendChild(div_two);

                        aria_show_input_of_image.appendChild(divbig);

                        button.addEventListener('click', function() {
                            removeimage(divbig);
                        });
                    });

                    function removeimage(divbig) {
                        divbig.remove();
                    }
                </script>

            </div>
            <!-- ปุ่มยืนยัน -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-block">เพิ่มลงในตะกร้า</button>
            </div>
    </form>
    </div>
@endsection
