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
            }, 500);
        @endif
    </script>







    <form action="{{ route('employee.savemanageitem', ['id' => $orderdetail->id]) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="container mt-4">
            <!-- กล่องแรกฟอร์มเพิ่มออเดอร์ -->
            <div class="shadow p-4 mb-5 bg-red rounded">
                @if ($orderdetail->type_order == 1)
                    <h4 class="mb-4" style="text-align: center">ข้อมูลตัดชุด</h4>
                @elseif($orderdetail->type_order == 2)
                    <h4 class="mb-4" style="text-align: center">ข้อมูลเช่าชุด</h4>
                @elseif($orderdetail->type_order == 3)
                    <h4 class="mb-4" style="text-align: center">ข้อมูลเช่าเครื่องประดับ</h4>
                @elseif($orderdetail->type_order == 4)
                    <h4 class="mb-4" style="text-align: center">ข้อมูลเช่าตัด</h4>
                @endif

                @csrf
                <div class="row mb-3">
                    
                    <div class="col-sm-4">
                        <label for="dressType" class="form-label">ประเภทชุด</label>
                        <select class="form-control" id="type_dress" name="type_dress" required @if($orderdetail->type_order !=1) disabled @endif>
                            <option value="" selected disabled>เลือกรายการ</option>
                            @foreach ($type_dress as $dressType)
                                <option value="{{ $dressType->type_dress_name }}"
                                    {{ $orderdetail->type_dress == $dressType->type_dress_name ? 'selected' : '' }}>
                                    {{ $dressType->type_dress_name }}</option>
                            @endforeach
                            <option value="other_type">อื่นๆ</option>
                        </select>
                    </div>
                    <div class="col-md-4" style="display: none;" id="showinput">
                        <label for="" class="form-label">ประเภทชุดอื่นๆ</label>
                        <input type="text" class="form-control" id="other_input" name="other_input"
                            placeholder="กรอกประเภทชุดอื่นๆ">
                    </div>

                    <div class="col-md-4" @if($orderdetail->type_order !=1) style='display:block;' @endif>
                        @if($dress)
                        <label for="" class="form-label">หมายเลขชุด</label>
                        <input type="text" class="form-control" id="" name="" value="{{$dress->dress_code_new}}{{$dress->dress_code}}" readonly>
                        @endif
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
                    <div class="col-md-4">
                        <label for="update_pickup_date" class="form-label">วันที่นัดรับชุด</label>
                        <input type="date" class="form-control" id="update_pickup_date" name="update_pickup_date"
                            value="{{ $orderdetail->pickup_date }}" required>
                    </div>

                    <div class="col-md-4" @if ($orderdetail->type_order == 1) style = "display:none ;" @endif>
                        <label for="" class="form-label">วันที่นัดคืนชุด</label>
                        <input type="date" class="form-control" id="" name="">
                    </div>

                    <div class="col-md-4" @if($orderdetail->type_order == 1) style='display:none;' @endif >
                        <label for="amount" class="form-label">Late Charge หรือ ค่าบริการขยายเวลาเช่าชุด :</label>
                        <input type="number" class="form-control" id="" name="" value="" required
                            @if ($orderdetail->type_order != 1) readonly @endif>
                            **หมายเหตุ วันที่นัดรับชุด - วันที่นัดคืนชุด ทางร้านอนุญาตให้เช่าชุดสูงสุด 3 วัน หากเกินกำหนดจะคิดค่าบริการขยายเวลาเช่าชุดวันละ 20% ของราคาค่าเช่าชุด
                    </div>
















                    
                </div>
 


                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="amount" class="form-label">จำนวนชุด</label>
                        <input type="number" class="form-control" id="update_amount" name="update_amount"
                            value="{{ $orderdetail->amount }}" min="1" required
                            @if ($orderdetail->type_order != 1) readonly @endif>
                    </div>

                    <div class="col-md-4">
                        <label for="price" class="form-label">ราคาเต็ม/ชุด</label>
                        <input type="number" class="form-control" id="update_price" name="update_price"
                            placeholder="จำนวนเงิน" min="1" step="0.01" value="{{ $orderdetail->price }}" required
                            @if ($orderdetail->type_order != 1) readonly @endif>
                    </div>

                    <div class="col-md-4" @if($orderdetail->type_order == 1) style="display:none;" @endif >
                        <label for="deposit" class="form-label">ราคามัดจำ/ชุด</label>
                        <input type="number" class="form-control" id="update_deposit" name="update_deposit"
                            placeholder="จำนวนเงิน" min="1" step="0.01" value="{{ $orderdetail->deposit }}"
                            required @if ($orderdetail->type_order != 1) readonly @endif>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="update_color" class="form-label">ประกันค่าเสียหาย</label>
                        <input type="number" class="form-control" id="" name=""
                        placeholder="จำนวนเงิน" min="1">
                    </div>
                    <div class="col-md-4">
                        <label for="update_color" class="form-label">สีของชุด</label>
                        <select class="form-control" id="update_color" name="update_color" required
                            @if ($orderdetail->type_order != 1) disabled @endif>
                            <option value="" disabled selected>--สี--</option>
                            <option value="ขาว"  {{ $orderdetail->color == 'ขาว' ? 'selected' : '' }}>ขาว</option>
                            <option value="ครีม" {{ $orderdetail->color == 'ครีม' ? 'selected' : '' }}>ครีม</option>
                            <option value="ชมพู" {{ $orderdetail->color == 'ชมพู' ? 'selected' : '' }}>ชมพู</option>
                            <option value="ดำ" {{ $orderdetail->color == 'ดำ' ? 'selected' : '' }}>ดำ</option>
                            <option value="ทอง" {{ $orderdetail->color == 'ทอง' ? 'selected' : '' }}>ทอง</option>
                            <option value="น้ำตาล" {{ $orderdetail->color == 'น้ำตาล' ? 'selected' : '' }}>น้ำตาล</option>
                            <option value="น้ำเงิน" {{ $orderdetail->color == 'น้ำเงิน' ? 'selected' : '' }}>น้ำเงิน
                            </option>
                            <option value="บานเย็น" {{ $orderdetail->color == 'บานเย็น' ? 'selected' : '' }}>บานเย็น
                            </option>
                            <option value="พิ้งค์โกลด์" {{ $orderdetail->color == 'พิ้งค์โกลด์' ? 'selected' : '' }}>
                                พิ้งค์โกลด์</option>
                            <option value="ฟ้า" {{ $orderdetail->color == 'ฟ้า' ? 'selected' : '' }}>ฟ้า</option>
                            <option value="ม่วง" {{ $orderdetail->color == 'ม่วง' ? 'selected' : '' }}>ม่วง</option>
                            <option value="ส้ม" {{ $orderdetail->color == 'ส้ม' ? 'selected' : '' }}>ส้ม</option>
                            <option value="เขียว" {{ $orderdetail->color == 'เขียว' ? 'selected' : '' }}>เขียว</option>
                            <option value="เทา" {{ $orderdetail->color == 'เทา' ? 'selected' : '' }}>เทา</option>
                            <option value="เหลือง" {{ $orderdetail->color == 'เหลือง' ? 'selected' : '' }}>เหลือง</option>
                            <option value="แดง" {{ $orderdetail->color == 'แดง' ? 'selected' : '' }}>แดง</option>
                            <option value="ไม่ระบุ" {{ $orderdetail->color == 'ไม่ระบุ' ? 'selected' : '' }}>ไม่ระบุ
                            </option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">การจ่ายเงิน</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="update_status_payment"
                                id="status_payment1" value="1"
                                {{ $orderdetail->status_payment == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_payment1">
                                จ่ายมัดจำ
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="update_status_payment"
                                id="status_payment2" value="2"
                                {{ $orderdetail->status_payment == '2' ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_payment2">
                                จ่ายเต็มจำนวน
                            </label>
                        </div>
                        **หมายเหตุ -ลูกค้าจะต้องจ่ายมัดจำหรือจ่ายเต็มจำนวนเท่านั้นพนักงานจึงจะสามารถบันทึกรายการให้ได้
                    </div>

                    <div class="col-md-4" @if($orderdetail->type_order !=1) style='display:none;' @endif>
                        <label class="form-label">ผ้า</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="update_cloth" id="cloth1"
                                value="1" {{ $orderdetail->cloth == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="cloth1">
                                ลูกค้านำผ้ามาเอง
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="update_cloth" id="cloth2"
                                value="2" {{ $orderdetail->cloth == 2 ? 'checked' : '' }}>
                            <label class="form-check-label" for="cloth2">
                                ทางร้านหาผ้าให้
                            </label>
                        </div>

                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="note" class="form-label">รายละเอียดอื่นๆ</label>
                        <textarea class="form-control" id="update_note" name="update_note" rows="4"
                            placeholder="ใส่รายละเอียดเพิ่มเติมที่เกี่ยวข้อง">{{ $orderdetail->note }}</textarea>
                    </div>

                    
                    <div class="col-md-4" @if($orderdetail->type_order!=1) style='display:block;' @endif>
                        @if($imagedress)
                        <label for="">รูปภาพชุด</label>
                        <p>
                            <img src="{{asset('storage/' .$imagedress->dress_image)}}" alt="" width="110px ; " >
                        </p>
                        @endif
                        
                    </div>




                </div>





                



            </div>



            {{-- กล่องที่สอง --}}
            <div class="shadow p-4 mb-5 bg-white rounded">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">ข้อมูลการวัดสำหรับตัดชุด</h4>
                    <button type="button" class="btn btn-primary" id="addMeasurementitem">+ เพิ่มการวัด</button>
                </div>

                <div id="aria_show_measurement">
                    @if ($measurementorderdetail->count() > 0)
                        @foreach ($measurementorderdetail as $showmea)
                            <div class="row mb-3">

                                <div class="col-sm-3">
                                    <input type="hidden" class="form-control" name="mea_id_[]"
                                        placeholder="เพิ่มชื่อการวัดเช่น รอบอก" value="{{ $showmea->id }}">

                                    <input type="text" class="form-control" name="mea_name_[]"
                                        placeholder="เพิ่มชื่อการวัดเช่น รอบอก" value="{{ $showmea->measurement_name }}">
                                </div>

                                <div class="col-sm-3">
                                    <input type="number" class="form-control" name="mea_number_[]"
                                        placeholder="ใส่ตัวเลข" value="{{ $showmea->measurement_number }}">
                                </div>

                                <div class="col-sm-3">
                                    <select class="form-control" name="mea_unit_[]">
                                        <option value="นิ้ว"
                                            {{ $showmea->measurement_unit == 'นิ้ว' ? 'selected' : '' }}>
                                            นิ้ว
                                        </option>
                                        <option value="เซนติเมตร"
                                            {{ $showmea->measurement_unit == 'เซนติเมตร' ? 'selected' : '' }}>เซนติเมตร
                                        </option>
                                        <option value="มิลลิเมตร"
                                            {{ $showmea->measurement_unit == 'มิลลิเมตร' ? 'selected' : '' }}>มิลลิเมตร
                                        </option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <form action="{{ route('employee.deletemeasurementitem', ['id' => $showmea->id]) }}"
                                        method="POST">
                                        @csrf
                                        <button class="form-control btn btn-danger" type="submit">ลบ</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <script>
                var aria_mea = document.getElementById('aria_show_measurement'); //พื้นที่แสดง
                var add_button = document.getElementById('addMeasurementitem');
                var count_mea = 0;
                add_button.addEventListener('click', function() {
                    count_mea++;

                    var divbig = document.createElement('div');
                    divbig.className = 'row mb-3';
                    divbig.id = 'mothermea' + count_mea;

                    input =

                        '<div class="col-sm-3">' +
                        '<input type="text" class="form-control" id="add_mea_name' + count_mea + ' " name="add_mea_name_[' +
                        count_mea + ']" placeholder="เพิ่มชื่อการวัดเช่น รอบอก" required >' +
                        '</div>' +

                        '<div class="col-sm-3">' +
                        '<input type="number" class="form-control" id="add_mea_number' + count_mea +
                        '  " name="add_mea_number_[' + count_mea + ']" placeholder="ใส่ตัวเลข" required>' +
                        '</div>' +

                        '<div class="col-sm-3">' +
                        '<select class="form-control" id="add_mea_unit' + count_mea + ' " name="add_mea_unit_[' +
                        count_mea + ']" required>' +
                        '<option value="นิ้ว">นิ้ว</option>' +
                        '<option value="เซนติเมตร">เซนติเมตร</option>' +
                        '<option value="มิลลิเมตร">มิลลิเมตร</option>' +
                        '</select>' +
                        '</div>' +

                        '<div class="col-sm-2">' +
                        '<button class="form-control btn btn-danger" type="button" onclick="removemea(' + count_mea +
                        ')">ลบ</button>' +
                        '</div>';

                    divbig.innerHTML = input;
                    aria_mea.appendChild(divbig);
                });

                function removemea(count_mea) {
                    var delete_div = document.getElementById('mothermea' + count_mea)
                    delete_div.remove();
                }
            </script>




            {{-- กล่องที่สอง --}}
            <div class="shadow p-4 mb-5 bg-white rounded">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">ข้อมูลการนัดลูกค้าลองชุด</h4>
                    <button type="button" class="btn btn-primary" id="addfittingitem">+ เพิ่มวันนัดลองชุด</button>
                </div>


                <div id="ariafitting">
                    @if ($fitting->count() > 0)
                        @foreach ($fitting as $showfitting)
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <label class="form-label">วันที่นัดลองชุด</label>
                                </div>
                                <div class="col-sm-3">

                                    <input type="hidden" class="form-control" name="fitting_id_[]"
                                        value="{{ $showfitting->id }}">


                                    <input type="date" class="form-control" name="fitting_date_[]"
                                        value="{{ $showfitting->fitting_date }}">
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="fitting_note_[]"
                                        placeholder="รายละเอียด" value="{{ $showfitting->fitting_note }}">
                                </div>
                                <div class="col-sm-2">
                                    <form action="{{ route('employee.deletefittingitem', ['id' => $showfitting->id]) }}"
                                        method="POST">
                                        @csrf
                                        <button class="form-control btn btn-danger" type="submit">ลบ</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @endif


                </div>

                <script>
                    var aria_fitting = document.getElementById('ariafitting');
                    var button_add_fitting = document.getElementById('addfittingitem');
                    var count_fitting_index = 0;
                    button_add_fitting.addEventListener('click', function() {
                        count_fitting_index++;

                        var divmotherfiting = document.createElement('div');
                        divmotherfiting.className = 'row mb-3';
                        divmotherfiting.id = 'motherfitting' + count_fitting_index;
                        input =

                            '<div class="col-sm-3">' +
                            '<label class="form-label">วันที่นัดลองชุด</label>' +
                            '</div>' +
                            '<div class="col-sm-3">' +
                            '<input type="date" class="form-control" id="add_fitting_date' + count_fitting_index +
                            ' " name="add_fitting_date_[' + count_fitting_index + ']">' +
                            '</div>' +
                            '<div class="col-sm-3">' +
                            '<input type="text" class="form-control" id="add_fitting_note' + count_fitting_index +
                            ' " name="add_fitting_note_[' + count_fitting_index + ']" placeholder="รายละเอียด">' +
                            '</div>' +
                            '<div class="col-sm-2">' +
                            '<button class="form-control btn btn-danger" type="button" onclick="removefittinf(' +
                            count_fitting_index + ')">ลบ</button>' +
                            '</div>';

                        divmotherfiting.innerHTML = input;
                        aria_fitting.appendChild(divmotherfiting);
                    });

                    function removefittinf(count_fitting_index) {
                        var delete_div_fitting = document.getElementById('motherfitting' + count_fitting_index);
                        delete_div_fitting.remove();
                    }
                </script>

            </div>


            <!-- กล่องที่สาม: ฟอร์มรูปภาพ -->
            <div class="shadow p-4 mb-5 bg-white rounded">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-4">รูปภาพประกอบ / รูปภาพตัวแบบ / อื่นๆที่เกี่ยวข้อง</h4>
                    <button type="button" class="btn btn-primary" id="addimageitem">+ เพิ่มรูปภาพ</button>
                </div>

                <div id="aria_show_input_of_image">

                    {{-- @foreach ($imagerent as $showimage)
                        <div class="row mb-3">
                            <label for="image" class="col-sm-2 col-form-label">อัปโหลดรูปภาพ</label>
                            <div class="col-sm-7">
                                <input type="file" class="form-control" name="image"
                                    value="{{ $showimage->image }}">
                            </div>
                            <div class="col-sm-2">
                                <button class="form-control btn btn-danger" type="button">ลบ</button>
                            </div>
                        </div>
                    @endforeach --}}

                    {{-- ตัวแบบ --}}
                    {{-- <div class="row mb-3">
                <label for="add_image" class="col-sm-2 col-form-label">อัปโหลดรูปภาพ</label>
                <div class="col-sm-7">
                    <input type="file" class="form-control" id="add_image" name="add_image">
                </div>

                <div class="col-sm-2">
                    <button class="form-control btn btn-danger" type="button" onclick="removeimage()">ลบ</button>
                </div>

            </div> --}}
                </div>

                <script>
                    var aria_image = document.getElementById('aria_show_input_of_image');
                    var button_add_image = document.getElementById('addimageitem');
                    var count_image = 0;

                    button_add_image.addEventListener('click', function() {
                        count_image++;

                        var divmotherimage = document.createElement('div');
                        divmotherimage.className = 'row mb-3';
                        divmotherimage.id = 'divmotherimage' + count_image;

                        var label = document.createElement('label');
                        label.htmlFor = 'add_image' + count_image;
                        label.className = 'col-sm-2 col-form-label';
                        label.innerHTML = 'อัปโหลดรูปภาพ';


                        var divone = document.createElement('div');
                        divone.className = 'col-sm-7';

                        var input = document.createElement('input');
                        input.type = 'file';
                        input.className = 'form-control';
                        input.id = 'add_image' + count_image;
                        input.name = 'add_image_[' + count_image + ']';
                        input.required = true;

                        divone.appendChild(input);


                        var divtwo = document.createElement('div');
                        divtwo.className = 'col-sm-2';

                        var button = document.createElement('button');
                        button.className = 'form-control btn btn-danger';
                        button.type = 'button';
                        button.innerHTML = 'ลบ'
                        divtwo.appendChild(button);

                        divmotherimage.appendChild(label);
                        divmotherimage.appendChild(divone);
                        divmotherimage.appendChild(divtwo);
                        aria_image.appendChild(divmotherimage);

                        button.addEventListener('click', function() {
                            divmotherimage.remove();
                        });
                    });
                </script>



            </div>
            <!-- ปุ่มยืนยัน -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-block">ยืนยัน</button>
            </div>
        </div>
    </form>
@endsection
