{{-- @extends('layouts.adminlayout')
@section('content')
    <div class="container d-flex justify-content-start">
        <div class="table-responsive text-start" style="width: 100%;">
            <div class="card border-0">
                <h2 class="text text-center py-4">แบบฟอร์มเพิ่มชุด</h2>



                <div class="modal fade" id="showfail" role="dialog" aria-hidden="true">
                    <div class="modal-dialog custom-modal-dialog" role="document">
                        <div class="modal-content custom-modal-content"
                            style="max-width: 300px; height: 50px; width: 100%; margin: auto; background-color: #EE4E4E; border: 2px solid #EE4E4E; ">
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
                        }, 500);
                    @endif
                </script>


                <div class="modal fade" id="showsuccessss" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                บันทึกข้อมูลสำเร็จ
                            </div>
                            <div class="modal-body">
                                <strong>กรุณานำรหัสชุดเหล่านี้ไปติดไว้ กำกับกับชุดที่ท่านได้เพิ่ม:</strong>
                                <ul>
                                    @if (session('dressCodes'))
                                        @foreach (session('dressCodes') as $code)
                                            <li>{{ $code }}</li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">ตกลง</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
                <script>
                    @if (session('dressCodes'))
                        setTimeout(function() {
                            $('#showsuccessss').modal('show');
                        }, 500);
                    @endif
                </script>









                <form action="{{ route('admin.savedress') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row ">
                        <div class="col-12">
                            <label for="type_dress_id">ประเภทชุด</label>
                            <select name="type_dress_id" id="type_dress_id" class="form-control" required>
                                <option value="" disabled selected>กรุณาเลือกประเภทชุดที่ต้องการเพิ่ม</option>
                                @foreach ($typeDresses as $typeDress)
                                    <option value="{{ $typeDress->id }}">{{ $typeDress->type_dress_name }}</option>
                                @endforeach
                                <option value="select_other">อื่นๆ</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <div style="display: none" id="showinputother">
                                <label class="form-label" for="inputother">อื่นๆโปรดระบุ</label>
                                <input type="text" name="inputother" id="inputother">
                            </div>
                        </div> 
                        <script>
                            var selectdresstype = document.getElementById('type_dress_id'); 
                            var showshowinputother = document.getElementById('showinputother'); 
                            var present_inputother = document.getElementById('inputother');
                            selectdresstype.addEventListener('click', function() {
                                if (selectdresstype.value == "select_other") {
                                    showshowinputother.style.display = "block";
                                } else {
                                    showshowinputother.style.display = "none";
                                    present_inputother.value = '';
                                }
                            });
                        </script>

                        <div class="col-sm-3">
                            <label for="dress_title_name">ชื่อชุดtitle(ลบออกไม่มีหรอก)</label>
                            <input type="text" name="dress_title_name" id="dress_title_name"
                                class="form-control shadow-sm p-3 mb-2 bg-body-tertiary rounded" required>
                        </div>

                        <div class="col-sm-3">
                            <label for="dress_color">สีของชุด(ลบออก)</label>
                            <select name="dress_color" id=""
                                class="form-select shadow-sm p-3 mb-2 bg-body-tertiary rounded">
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


                        <div class="col-sm-3">
                            <label for="dress_price">ราคาชุด/ครั้ง</label>
                            <input type="number" name="dress_price" id="dress_price"
                                class="form-control shadow-sm p-3 mb-2 bg-body-tertiary rounded" placeholder="จำนวนบาท"
                                required min="1">
                        </div>


                        <div class="col-sm-3">
                            <label for="dress_deposit">ราคามัดจำ/ครั้ง</label>
                            <input type="number" name="dress_deposit" id="dress_deposit"
                                class="form-control shadow-sm p-3 mb-2 bg-body-tertiary rounded" placeholder="จำนวนบาท"
                                required min="1">
                        </div>

                        <div class="col-sm-3">
                            <label for="dress_count">จำนวนชุด</label>
                            <input type="number" name="dress_count" id="dress_count"
                                class="form-control shadow-sm p-3 mb-2 bg-body-tertiary rounded" required min="1"
                                value="1">
                        </div>

                        <div class="col-sm-3">
                            <label for="dress_type">ประเภท</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="dress_type" id="separable" value="separable" required>
                                <label class="form-check-label" for="separable">
                                    เช่าชุดแยกได้
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="dress_type" id="inseparable" value="inseparable" required>
                                <label class="form-check-label" for="inseparable">
                                    เช่าชุดแยกไม่ได้
                                </label>
                            </div>
                        </div>
                        



                        <div class="col-sm-3">
                            <label for="">เสื้อ</label>
                            <input type="number" name="" id=""
                                class="form-control shadow-sm p-3 mb-2 bg-body-tertiary rounded" placeholder="จำนวนบาท"
                                required min="1">
                        </div>


                        <div class="col-sm-3">
                            <label for="">กางเกง/กระโปรง</label>
                            <input type="number" name="" id=""
                                class="form-control shadow-sm p-3 mb-2 bg-body-tertiary rounded" placeholder="จำนวนบาท"
                                required min="1">
                        </div>






                        <div class="col-sm-3">
                            <label for="dress_description">รายละเอียด</label>
                            <textarea name="dress_description" id="dress_description" cols="30" rows="1"
                                class="form-control shadow-sm p-3 mb-2 bg-body-tertiary rounded"></textarea>
                        </div>
                        <div class="col-sm-3">
                        </div>


                        <div class="col-sm-4">
                            <label for="measurement_dress_name">ชื่อการวัด</label>
                            <input type="text" class="form-control shadow-sm p-3 mb-2 bg-body-tertiary rounded"
                                name="measurement_dress_name_[1]" id="measurement_dress_name1" value="รอบอก" required
                                readonly>
                        </div>
                        <div class="col-sm-4">
                            <label for="measurement_dress_number">ขนาด</label>
                            <input type="number" name="measurement_dress_number_[1]"id="measurement_dress_number1"
                                class="form-control shadow-sm p-3 mb-2 bg-body-tertiary rounded">
                        </div>

                        <div class="col-sm-4">
                            <label for="measurement_dress_unit">หน่วย</label>
                            <select name="measurement_dress_unit_[1]" id="measurement_dress_unit1"
                                class="form-select shadow-sm p-3 mb-2 bg-body-tertiary rounded">
                                <option value="นิ้ว" selected>นิ้ว</option>
                                <option value="ซ.ม">ซ.ม</option>
                            </select>
                        </div>


                        <div class="col-sm-4">
                            <label for="measurement_dress_name">ชื่อการวัด</label>
                            <input type="text" class="form-control shadow-sm p-3 mb-2 bg-body-tertiary rounded"
                                name="measurement_dress_name_[2]" id="measurement_dress_name2" value="รอบเอว" required
                                readonly>
                        </div>
                        <div class="col-sm-4">
                            <label for="measurement_dress_number">ขนาด</label>
                            <input type="number" name="measurement_dress_number_[2]"id="measurement_dress_number2"
                                class="form-control shadow-sm p-3 mb-2 bg-body-tertiary rounded">
                        </div>

                        <div class="col-sm-4">
                            <label for="measurement_dress_unit">หน่วย</label>
                            <select name="measurement_dress_unit_[2]" id="measurement_dress_unit2"
                                class="form-select shadow-sm p-3 mb-2 bg-body-tertiary rounded">
                                <option value="นิ้ว" selected>นิ้ว</option>
                                <option value="ซ.ม">ซ.ม</option>
                            </select>
                        </div>


                        <div class="col-sm-4">
                            <label for="measurement_dress_name">ชื่อการวัด</label>
                            <input type="text" class="form-control shadow-sm p-3 mb-2 bg-body-tertiary rounded"
                                name="measurement_dress_name_[3]" id="measurement_dress_name3" value="รอบสะโพก" required
                                readonly>
                        </div>
                        <div class="col-sm-4">
                            <label for="measurement_dress_number">ขนาด</label>
                            <input type="number" name="measurement_dress_number_[3]"id="measurement_dress_number3"
                                class="form-control shadow-sm p-3 mb-2 bg-body-tertiary rounded">
                        </div>

                        <div class="col-sm-4">
                            <label for="measurement_dress_unit">หน่วย</label>
                            <select name="measurement_dress_unit_[3]" id="measurement_dress_unit3"
                                class="form-select shadow-sm p-3 mb-2 bg-body-tertiary rounded">
                                <option value="นิ้ว" selected>นิ้ว</option>
                                <option value="ซ.ม">ซ.ม</option>
                            </select>
                        </div>

                        <div class="col-sm-3">
                            <label for="dsds">คลิ๊กเพื่อเพิ่ม</label>
                            <button type="button" class="btn btn-success" id="add_for_mea">+
                                คลิ๊กเพื่อเพิ่มขนาด</button>
                            <button type="button" id="addimagerent" class="btn btn-success ml-3">+ เพิ่มรูปภาพ</button>
                        </div>

                        <div id="ariamea">

                        </div>

                        <div id="areaimage" class="col-sm-3">
                            <!--พื้นที่สำหรับแสดงสำหรับเพิ่มรูปชุดนะ -->
                        </div>

                        
                    </div>

                    <script>
                        var addcost = document.getElementById('add_for_mea') 
                        var ariashow = document.getElementById('ariamea') 
                        var count = 3;

                        addcost.addEventListener('click', function() {
                            count++;

                            var creatediv = document.createElement('div'); 
                            creatediv.id = 'mea' + count;

                            input =

                                '<div class="col-sm-4">' +
                                '<label for="measurement_dress_name' + count + '">กรอกชื่อ</label>' +
                                ' <input type="text" name="measurement_dress_name_[' + count + ']" id="measurement_dress_name' + count + '">' +
                                '</div>' +

                                '<div class="col-sm-4">' +
                                '<label for="measurement_dress_number' + count + '">กรอกขนาด</label>' +
                                '<input type="number" name="measurement_dress_number_[' + count +
                                ']" id="measurement_dress_number' + count + '">' +
                                '</div>' +

                                '<div class="col-sm-4">' +
                                '<label for="measurement_dress_unit' + count + '">กรอกหน่วย</label>' +
                                '<select name="measurement_dress_unit_[' + count + ']" id="measurement_dress_unit' + count +'" class="form-select">' +
                                '<option value="นิ้ว" selected>นิ้ว</option>' +
                                '<option value="ซ.ม">ซ.ม</option>' +
                                '</select>' +
                                '</div>' +

                                '<button type="button" class="btn btn-danger" onclick="removefitting(' + count + ')">ลบ</button>';

                            creatediv.innerHTML = input;
                            ariashow.appendChild(creatediv);
                        });

                        function removefitting(index) {
                            var deleteID = document.getElementById('mea' + index)
                            deleteID.remove();
                        }
                    </script>

                    <script>
                        var addimage = document.getElementById('addimagerent'); 
                        var areaimage = document.getElementById('areaimage'); 
                        var count = 0;

                        addimage.addEventListener('click', function() {
                            count++;
                            var creatediv = document.createElement('div');
                            creatediv.id = 'imagerent' + count;


                            var label = document.createElement('label');
                            label.htmlFor = 'imagerent' + count;
                            label.innerHTML = count + 'เพิ่มรูปชุด'
                            label.className = 'py-3'

                            var input = document.createElement('input');
                            input.type = 'file';
                            input.name = 'imagerent_[' + count + ']';
                            input.id = 'imagerent' + count;

                            var button = document.createElement('button');
                            button.type = 'button';
                            button.className = 'btn btn-danger';
                            button.innerHTML = "ลบ";

                            creatediv.appendChild(label);
                            creatediv.appendChild(input);
                            creatediv.appendChild(button);
                            areaimage.appendChild(creatediv);

                            button.addEventListener('click', function() {
                                removeimage(creatediv); 
                            });

                        });

                        function removeimage(creatediv) {
                            creatediv.remove();
                        }
                    </script>

                    <div style="display: flex; justify-content: end;">
                        <button class="btn btn-success" type="submit">ยืนยันการเพิ่มชุด</button>
                    </div>
                </form>

            </div>


        </div>

    </div>
@endsection --}}



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


<div class="modal fade" id="showsuccessss" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                บันทึกข้อมูลสำเร็จ
            </div>
            <div class="modal-body">
                <strong>กรุณานำรหัสชุดเหล่านี้ไปติดไว้ กำกับกับชุดที่ท่านได้เพิ่ม:</strong>
                <ul>
                    @if (session('dressCodes'))
                        @foreach (session('dressCodes') as $code)
                            <li>{{ $code }}</li>
                        @endforeach
                    @endif
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">ตกลง</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
    @if (session('dressCodes'))
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
        <h4 class="mb-4" style="text-align: center ; ">แบบฟอร์มเพิ่มชุด</h4>
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
                    selectdresstype.addEventListener('click', function() {
                        if (selectdresstype.value == "select_other") {
                            showshowinputother.style.display = "block";
                        } else {
                            showshowinputother.style.display = "none";
                            present_inputother.value = '';
                        }
                    });
                </script>
                <div class="col-md-3">
                    <label for="dress_price">ราคาชุด/ครั้ง</label>
                    <input type="number" name="dress_price" id="dress_price" class="form-control" placeholder="จำนวนบาท"
                        required min="1">
                </div>
                <div class="col-md-3">
                    <label for="dress_deposit">ราคามัดจำ/ครั้ง</label>
                    <input type="number" name="dress_deposit" id="dress_deposit" class="form-control"
                        placeholder="จำนวนบาท" required min="1">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="dress_count">ประกันค่าเสียหายชุด</label>
                    <input type="number" name="damage_insurance" id="damage_insurance" class="form-control" placeholder="จำนวนบาท"
                        required min="0">
                </div>
                <div class="col-md-3">
                    <label for="dress_count">จำนวนชุด</label>
                    <input type="number" name="dress_count" id="dress_count" class="form-control" required min="1"
                        value="1">
                </div>
                <div class="col-md-3">
                    <label for="note" class="form-label">รายละเอียดอื่นๆ</label>
                    <textarea class="form-control" id="dress_description" name="dress_description" rows="4"
                        placeholder="ใส่รายละเอียดเพิ่มเติมที่เกี่ยวข้อง"></textarea>
                </div>
            </div>


            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">การเช่าชุด</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="separable" id="separate_rent_no"
                            value="1">
                        <label class="form-check-label" for="separate_rent_no">
                            ไม่สามารถเช่าแยกได้(เช่าเป็นชุด)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="separable" id="separate_rent_yes"
                            value="2">
                        <label class="form-check-label" for="separate_rent_yes">
                            สามารถเช่าแยกได้(เสื้อ+กระโปรง/กางเกง)
                        </label>
                    </div>

                </div>
            </div>
        </div>


         {{-- กล่องที่สอง --}}
         <h3 style="text-align: center ; ">รูปภาพชุด</h3>
         <div class="shadow p-4 mb-5 bg-white rounded">
            <div class="d-flex justify-content-between align-items-center mb-3">
                {{-- <h4 class="mb-0">ข้อมูลเสื้อ</h4> --}}
                <button type="button" class="btn btn-primary" id="addimage" >+ เพิ่ม</button>
            </div>


            <div id="aria_input_show_image">

                <div class="row mb-3" id="">
                    <label for="imagerent" class="col-sm-2">อัปโหลดรูปภาพ</label>
                    <div class="col-md-7">
                        <input type="file" class="form-control" id="imagerent1" name="imagerent_[1]">
                    </div>
                    {{-- <div class="col-md-2">
                        <button class="form-control btn btn-danger" type="button" onclick="removeimage()">ลบ</button>
                    </div> --}}
                </div>


            </div>
            <script>
                var button_addimage = document.getElementById('addimage') ; 
                var aria_input_show_image = document.getElementById('aria_input_show_image') ;
                var count_image = 1 ;  
                button_addimage.addEventListener('click',function(){
                    count_image++ ; 
                    var divrow = document.createElement('div') ; 
                    divrow.className = 'row mb-3' ; 
                    divrow.id = 'row_image' + count_image ; 

                    var label = document.createElement('label') ; 
                    label.htmlFor = 'label' ; 
                    label.className = 'col-sm-2' ; 
                    label.innerHTML = 'อัปโหลดรูปภาพ' ; 

                    // divrow.appendChild(label) ; 

                    var divone = document.createElement('div') ; 
                    divone.className = 'col-md-7' ; 

                    var input = document.createElement('input') ; 
                    input.type = 'file' ; 
                    input.className = 'form-control' ; 
                    input.id = 'imagerent' + count_image ; 
                    input.name = 'imagerent_['+count_image+']' ; 

                    divone.appendChild(input) ; 

                    var divtwo = document.createElement('div') ; 
                    divtwo.className = 'col-md-2' ; 

                    var button = document.createElement('button') ; 
                    button.className = 'form-control btn btn-danger' ; 
                    button.type = 'button' ;
                    button.innerHTML = 'ลบ' ; 
                
                    divtwo.appendChild(button) ; 

                    divrow.appendChild(label) ; 
                    divrow.appendChild(divone) ; 
                    divrow.appendChild(divtwo) ; 
                    aria_input_show_image.appendChild(divrow) ; 

                    button.addEventListener('click',function(){
                        divrow.remove() ; 
                    }) ; 
                    

                }) ; 


            </script>
            






        
        </div>













        {{-- กล่องกรณีที่เป็นชุด --}}
        <div id="Big_show_aria_no_separated" style="display: none;">
            <h3 style="text-align: center ; ">กรณีที่เป็นชุด</h3>
            <div class="shadow p-4 mb-5 bg-white rounded">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    {{-- <h4 class="mb-0">ข้อมูลเสื้อ</h4> --}}
                    <button type="button" class="btn btn-primary" id="button_add_mea_no_separated">+
                        เพิ่มการวัด</button>
                </div>

                <div id="show_aria_no_separated">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="no_shirt_measurement_dress_name1">ชื่อการวัด</label>
                            <input type="text" class="form-control" name="no_shirt_measurement_dress_name_[1]"
                                id="no_shirt_measurement_dress_name1" value="รอบอก" required readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="no_shirt_measurement_dress_number1">ขนาด</label>
                            <input type="number"
                                name="no_shirt_measurement_dress_number_[1]"id="no_shirt_measurement_dress_number1"
                                class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="no_shirt_measurement_dress_unit1">หน่วย</label>
                            <select name="no_shirt_measurement_dress_unit_[1]" id="no_shirt_measurement_dress_unit1"
                                class="form-control">
                                <option value="นิ้ว">นิ้ว</option>
                                <option value="เซนติเมตร">เซนติเมตร</option>
                                <option value="มิลลิเมตร">มิลลิเมตร</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="no_shirt_measurement_dress_name_[2]"
                                id="no_shirt_measurement_dress_name2" value="รอบเอว" required readonly>
                        </div>
                        <div class="col-md-3">
                            <input type="number"
                                name="no_shirt_measurement_dress_number_[2]"id="no_shirt_measurement_dress_number2"
                                class="form-control">
                        </div>
                        <div class="col-md-3">
                            <select name="no_shirt_measurement_dress_unit_[2]" id="no_shirt_measurement_dress_unit2"
                                class="form-control">
                                <option value="นิ้ว">นิ้ว</option>
                                <option value="เซนติเมตร">เซนติเมตร</option>
                                <option value="มิลลิเมตร">มิลลิเมตร</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="no_shirt_measurement_dress_name_[3]"
                                id="no_shirt_measurement_dress_name3" value="รอบสะโพก" required readonly>
                        </div>
                        <div class="col-md-3">
                            <input type="number"
                                name="no_shirt_measurement_dress_number_[3]"id="no_shirt_measurement_dress_number3"
                                class="form-control">
                        </div>
                        <div class="col-md-3">
                            <select name="no_shirt_measurement_dress_unit_[3]" id="no_shirt_measurement_dress_unit3"
                                class="form-control">
                                <option value="นิ้ว">นิ้ว</option>
                                <option value="เซนติเมตร">เซนติเมตร</option>
                                <option value="มิลลิเมตร">มิลลิเมตร</option>
                            </select>
                        </div>
                    </div>



                    <script>
                        var button_add_mea_no_separated = document.getElementById('button_add_mea_no_separated');
                        var show_aria_no_separated = document.getElementById('show_aria_no_separated');
                        var count_no_separated = 3;
                        button_add_mea_no_separated.addEventListener('click', function() {
                            count_no_separated++;
                            var div_mea_no = document.createElement('div');
                            div_mea_no.className = 'row mb-3';
                            div_mea_no.id = 'row_mea_no_separated' + count_no_separated;

                            input =

                                '<div class="col-md-3">' +
                                '<input type="text" class="form-control" name="no_shirt_measurement_dress_name_[' +
                                count_no_separated + ']" id="no_shirt_measurement_dress_name ' + count_no_separated +
                                ' " placeholder="ชื่อการวัด">' +
                                '</div>' +
                                '<div class="col-md-3">' +
                                '<input type="number" name="no_shirt_measurement_dress_number_[' + count_no_separated +
                                ']"id="no_shirt_measurement_dress_number ' + count_no_separated +
                                ' " class="form-control" placeholder="ขนาด">' +
                                '</div>' +
                                '<div class="col-md-3">' +
                                '<select name="no_shirt_measurement_dress_unit_[' + count_no_separated +
                                ']" id="no_shirt_measurement_dress_unit ' + count_no_separated + ' " class="form-control">' +
                                '<option value="นิ้ว">นิ้ว</option>' +
                                '<option value="เซนติเมตร">เซนติเมตร</option>' +
                                '<option value="มิลลิเมตร">มิลลิเมตร</option>' +
                                '</select>' +
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
                            <button type="button" class="btn btn-primary" id="button_add_mea_shirt">+
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
                                <input type="number" name="shirt_damage_insurance" id="shirt_damage_insurance" class="form-control"
                                    placeholder="ราคา" min="1">
                            </div>
                        </div>
                        <div id="aria_input_shirt">
                            {{-- พื้นที่แสดงผล --}}
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="yes_shirt_measurement_dress_name1">ชื่อการวัด</label>
                                    <input type="text" class="form-control" name="yes_shirt_measurement_dress_name_[1]"
                                        id="yes_shirt_measurement_dress_name1" value="รอบอก" required readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="yes_shirt_measurement_dress_number1">ขนาด</label>
                                    <input type="number"
                                        name="yes_shirt_measurement_dress_number_[1]"id="yes_shirt_measurement_dress_number1"
                                        class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="yes_shirt_measurement_dress_unit1">หน่วย</label>
                                    <select name="yes_shirt_measurement_dress_unit_[1]" id="yes_shirt_measurement_dress_unit1"
                                        class="form-control">
                                        <option value="นิ้ว">นิ้ว</option>
                                        <option value="เซนติเมตร">เซนติเมตร</option>
                                        <option value="มิลลิเมตร">มิลลิเมตร</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="yes_shirt_measurement_dress_name_[2]"
                                        id="yes_shirt_measurement_dress_name2" value="รอบเอว" required readonly>
                                </div>
                                <div class="col-md-4">
                                    <input type="number"
                                        name="yes_shirt_measurement_dress_number_[2]"id="yes_shirt_measurement_dress_number2"
                                        class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <select name="yes_shirt_measurement_dress_unit_[2]" id="yes_shirt_measurement_dress_unit2"
                                        class="form-control">
                                        <option value="นิ้ว">นิ้ว</option>
                                        <option value="เซนติเมตร">เซนติเมตร</option>
                                        <option value="มิลลิเมตร">มิลลิเมตร</option>
                                    </select>
                                </div>
                            </div>

                            <script>
                                var button_add_mea_shirt = document.getElementById('button_add_mea_shirt'); //ปุ่มเพิ่มการวัด
                                var aria_input_shirt = document.getElementById('aria_input_shirt'); // พื้นที่แสดงผล
                                var count_shirt = 2;

                                button_add_mea_shirt.addEventListener('click', function() {
                                    count_shirt++;
                                    var div = document.createElement('div');
                                    div.className = 'row mb-3';
                                    div.id = 'row_shirt' + count_shirt;

                                    input =

                                        '<div class="col-md-3">' +
                                        '<input type="text" class="form-control" name="yes_shirt_measurement_dress_name_[' + count_shirt +
                                        ']" id="yes_shirt_measurement_dress_name ' + count_shirt + ' " placeholder="ชื่อการวัด">' +
                                        '</div>' +
                                        '<div class="col-md-3">' +
                                        '<input type="number" name="yes_shirt_measurement_dress_number_[' + count_shirt +
                                        ']"id="yes_shirt_measurement_dress_number ' + count_shirt +
                                        '  " class="form-control" placeholder="ขนาด">' +
                                        '</div>' +
                                        '<div class="col-md-3">' +
                                        '<select name="yes_shirt_measurement_dress_unit_[' + count_shirt +
                                        ']" id="yes_shirt_measurement_dress_unit ' + count_shirt + ' " class="form-control">' +
                                        '<option value="นิ้ว">นิ้ว</option>' +
                                        '<option value="เซนติเมตร">เซนติเมตร</option>' +
                                        '<option value="มิลลิเมตร">มิลลิเมตร</option>' +
                                        '</select>' +
                                        '</div>' +
                                        '<div class="col-md-3">' +
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
                            <h4 class="mb-0">ข้อมูลกระโปรง/กางเกง</h4>
                            <button type="button" class="btn btn-primary" id="button_add_mea_skirt">+
                                เพิ่มการวัดกระโปรง</button>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="skirt_price">ราคากระโปรง/กางเกง</label>
                                <input type="number" name="skirt_price" id="skirt_price" class="form-control"
                                    placeholder="ราคา" min="1">
                            </div>
                            <div class="col-md-4">
                                <label for="skirt_deposit">มัดจำราคากระโปรง/กางเกง</label>
                                <input type="number" name="skirt_deposit" id="skirt_deposit" class="form-control"
                                    placeholder="ราคา" min="1">
                            </div>
                            <div class="col-md-4">
                                <label for="">ประกันค่าเสียหาย&nbsp;</label>
                                <input type="number" name="skirt_damage_insurance" id="skirt_damage_insurance" class="form-control"
                                    placeholder="ราคา" min="1">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="yes_skirt_measurement_dress_name">ชื่อการวัด</label>
                                <input type="text" class="form-control" name="yes_skirt_measurement_dress_name_[1]"
                                    id="yes_skirt_measurement_dress_name1" value="รอบสะโพก" required readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="yes_skirt_measurement_dress_number">ขนาด</label>
                                <input type="number"
                                    name="yes_skirt_measurement_dress_number_[1]"id="yes_skirt_measurement_dress_number1"
                                    class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="yes_skirt_measurement_dress_unit">หน่วย</label>
                                <select name="yes_skirt_measurement_dress_unit_[1]" id="yes_skirt_measurement_dress_unit1"
                                    class="form-control">
                                    <option value="นิ้ว">นิ้ว</option>
                                    <option value="เซนติเมตร">เซนติเมตร</option>
                                    <option value="มิลลิเมตร">มิลลิเมตร</option>
                                </select>
                            </div>
                        </div>
                        <div id="aria_input_skirt">
                            {{-- พื้นที่แสดงผล --}}


                        </div>

                        <script>
                            var button_add_mea_skirt = document.getElementById('button_add_mea_skirt'); //กดเพิ่ม
                            var aria_input_skirt = document.getElementById('aria_input_skirt'); //แสดงผล
                            var count_skirt = 1;
                            button_add_mea_skirt.addEventListener('click', function() {
                                count_skirt++;

                                var div_skirt = document.createElement('div');
                                div_skirt.className = 'row mb-3';
                                div_skirt.id = 'row_skirt' + count_skirt;

                                input =

                                    '<div class="col-md-3">' +
                                    '<input type="text" class="form-control" name="yes_skirt_measurement_dress_name_[' + count_skirt +
                                    ']" id="yes_skirt_measurement_dress_name ' + count_skirt + ' " placeholder="ชื่อการวัด">' +
                                    '</div>' +
                                    '<div class="col-md-3">' +
                                    '<input type="number" name="yes_skirt_measurement_dress_number_[' + count_skirt +
                                    ']"id="yes_skirt_measurement_dress_number ' + count_skirt +
                                    ' " class="form-control" placeholder="ขนาด">' +
                                    '</div>' +
                                    '<div class="col-md-3">' +
                                    '<select name="yes_skirt_measurement_dress_unit_[' + count_skirt +
                                    ']" id="yes_skirt_measurement_dress_unit ' + count_skirt + ' " class="form-control">' +
                                    '<option value="นิ้ว">นิ้ว</option>' +
                                    '<option value="เซนติเมตร">เซนติเมตร</option>' +
                                    '<option value="มิลลิเมตร">มิลลิเมตร</option>' +
                                    '</select>' +
                                    '</div>' +
                                    '<div class="col-md-3">' +
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

                // document.getElementById('no_shirt_measurement_dress_number1').removeAttribute('required') ; 
                // document.getElementById('no_shirt_measurement_dress_number2').removeAttribute('required') ; 
                // document.getElementById('no_shirt_measurement_dress_number3').removeAttribute('required') ; 

                // document.getElementById('no_shirt_measurement_dress_number1').value = '' ; 
                // document.getElementById('no_shirt_measurement_dress_number2').value = '' ; 
                // document.getElementById('no_shirt_measurement_dress_number3').value = '' ; 



                // document.getElementById('shirt_price').setAttribute('required','required') ; 
                // document.getElementById('shirt_deposit').setAttribute('required','reuired') ; 
                // document.getElementById('skirt_price').setAttribute('required','required') ; 
                // document.getElementById('skirt_deposit').setAttribute('required','reuired') ; 

                // document.getElementById('yes_shirt_measurement_dress_number1').setAttribute('required','required') ; 
                // document.getElementById('yes_shirt_measurement_dress_number2').setAttribute('required','required') ; 
                // document.getElementById('yes_skirt_measurement_dress_number1').setAttribute('required','required') ; 

             

            }
        });
        separate_rent_no.addEventListener('change', function() {
            if (this.checked) {
                Big_show_aria_no_separated.style.display = 'block';
                Big_show_aria_yes_separated.style.display = 'none';
                // document.getElementById('no_shirt_measurement_dress_number1').setAttribute('required','required') ; 
                // document.getElementById('no_shirt_measurement_dress_number2').setAttribute('required','required') ; 
                // document.getElementById('no_shirt_measurement_dress_number3').setAttribute('required','required') ; 

                

                // document.getElementById('shirt_price').value = '' ; 
                // document.getElementById('shirt_deposit').value = '' ; 
                // document.getElementById('skirt_price').value = '' ; 
                // document.getElementById('skirt_deposit').value = '' ; 

                // document.getElementById('yes_shirt_measurement_dress_number1').value = '' ; 
                // document.getElementById('yes_shirt_measurement_dress_number2').value = '' ; 
                // document.getElementById('yes_skirt_measurement_dress_number1').value = '' ; 

                // document.getElementById('yes_shirt_measurement_dress_number1').removeAttribute('required') ; 
                // document.getElementById('yes_shirt_measurement_dress_number2').removeAttribute('required') ; 
                // document.getElementById('yes_skirt_measurement_dress_number1').removeAttribute('required') ; 


            }
        });
    </script>
@endsection
