@extends('layouts.adminlayout')
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
                        </div> {{-- script สำหรับ แสดงช่อง input --}}
                        <script>
                            var selectdresstype = document.getElementById('type_dress_id'); //เลือกประเภทชุด
                            var showshowinputother = document.getElementById('showinputother'); //แสดงกล่องสำหรับinput เพิ่ทใหม่ 
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
                            <label for="dress_title_name">ชื่อชุดtitle</label>
                            <input type="text" name="dress_title_name" id="dress_title_name"
                                class="form-control shadow-sm p-3 mb-2 bg-body-tertiary rounded" required>
                        </div>

                        <div class="col-sm-3">
                            <label for="dress_color">สีของชุด</label>
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
                            {{-- พื้นที่สำหรับแสดงสำหรับเพิ่มรูปชุดนะ --}}
                        </div>

                        
                    </div>

                    <script>
                        var addcost = document.getElementById('add_for_mea') //เพิ่มบันทึกการวัด
                        var ariashow = document.getElementById('ariamea') // พื้นที่แสดงช่องinput
                        var count = 3;

                        addcost.addEventListener('click', function() {
                            count++;

                            var creatediv = document.createElement('div'); //สร้างdiv 
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


                    {{-- <button type="button" id="addimagerent" class="btn btn-success ml-3">+ เพิ่มรูปภาพ</button> --}}

                    <script>
                        var addimage = document.getElementById('addimagerent'); //กดรูปภาพ
                        var areaimage = document.getElementById('areaimage'); //พื้นที่แสดงผล
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

                            // button.innerHTML = "ลบ";
                            creatediv.appendChild(label);
                            creatediv.appendChild(input);
                            creatediv.appendChild(button);
                            areaimage.appendChild(creatediv);

                            button.addEventListener('click', function() {
                                removeimage(creatediv); // ส่ง creatediv เพื่อให้รู้ว่าจะลบ div นี้
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
@endsection
