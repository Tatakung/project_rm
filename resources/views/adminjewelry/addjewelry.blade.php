@extends('layouts.adminlayout')
@section('content')
    <div class="container d-flex justify-content-start">
        <div class="table-responsive text-start" style="width: 100%;">
            <div class="card border-0">
                <h2 class="text text-center py-4">แบบฟอร์มเพิ่มเครื่องประดับ</h2>



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
                                    @if (session('warn'))
                                        @foreach (session('warn') as $warn)
                                            <li>{{ $warn }}</li>
                                        @endforeach
                                    @endif
                                    {{-- @if (session('dressCodes'))
                                        @foreach (session('dressCodes') as $code)
                                            <li>{{ $code }}</li>
                                        @endforeach
                                    @endif --}}


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
                    @if (session('warn'))
                        setTimeout(function() {
                            $('#showsuccessss').modal('show');
                        }, 500);
                    @endif
                </script>









                <form action="{{ route('admin.savejewelry') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row ">
                        <div class="col-12">
                            <label for="type_jewelry_id">ประเภทชุด</label>
                            <select name="type_jewelry_id" id="type_jewelry_id" class="form-control" required>
                                <option value="" disabled selected>กรุณาเลือกประเภทเครื่องประดับที่ต้องการเพิ่ม
                                </option>
                                @foreach ($typejewelry as $typejewelry)
                                    <option value="{{ $typejewelry->id }}">{{ $typejewelry->type_jewelry_name }}</option>
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
                            var selectjewelrytype = document.getElementById('type_jewelry_id'); //เลือกประเภทเครื่องประดับ
                            var showshowinputother = document.getElementById('showinputother'); //แสดงกล่องสำหรับinput เพิ่ทใหม่ 
                            var present_inputother = document.getElementById('inputother');
                            selectjewelrytype.addEventListener('click', function() {
                                if (selectjewelrytype.value == "select_other") {
                                    showshowinputother.style.display = "block";
                                } else {
                                    showshowinputother.style.display = "none";
                                    present_inputother.value = '';
                                }
                            });
                        </script>

                        <div class="col-sm-3">
                            <label for="jewelry_title_name">ชื่อเครื่องประดับtitle</label>
                            <input type="text" name="jewelry_title_name" id="jewelry_title_name"
                                class="form-control shadow-sm p-3 mb-2 bg-body-tertiary rounded" required>
                        </div>

                        <div class="col-sm-3">
                            {{-- <label for="dress_color">สีของชุด</label>
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
                            </select> --}}
                        </div>


                        <div class="col-sm-3">
                            <label for="jewelry_price">ราคาชุด/ครั้ง</label>
                            <input type="number" name="jewelry_price" id="jewelry_price"
                                class="form-control shadow-sm p-3 mb-2 bg-body-tertiary rounded" placeholder="จำนวนบาท"
                                required min="1">
                        </div>


                        <div class="col-sm-3">
                            <label for="jewelry_deposit">ราคามัดจำ/ครั้ง</label>
                            <input type="number" name="jewelry_deposit" id="jewelry_deposit"
                                class="form-control shadow-sm p-3 mb-2 bg-body-tertiary rounded" placeholder="จำนวนบาท"
                                required min="1">
                        </div>

                        <div class="col-sm-3">
                            <label for="jewelry_count">จำนวนเครื่องประดับ</label>
                            <input type="number" name="jewelry_count" id="jewelry_count"
                                class="form-control shadow-sm p-3 mb-2 bg-body-tertiary rounded" required min="1"
                                value="1">
                        </div>



                        <div class="col-sm-3">
                            <label for="jewelry_description">รายละเอียด</label>
                            <textarea name="jewelry_description" id="jewelry_description" cols="30" rows="1"
                                class="form-control shadow-sm p-3 mb-2 bg-body-tertiary rounded"></textarea>
                        </div>
                        <div class="col-sm-3">
                        </div>


                        <button class="btn btn-success" id="addimage" type="button">+เพิ่มรูปภาพ</button>

                        <div id="ariaimage">
                            {{-- พื้นที่แสดงผลของรูปภาพ --}}
                        </div>

                        <script>
                            var buttonaddimage = document.getElementById('addimage'); //กดเพิ่มรูปภาพ
                            var ariashow = document.getElementById('ariaimage'); //พื้นที่แสดงเวลาที่กดแต่ละ div จะแสดงผลโอเคไหม
                            count = 0;
                            buttonaddimage.addEventListener('click', function() {
                                count++;
                                var div = document.createElement('div');
                                div.className = 'col-sm-3';
                                div.id = 'image' + count;

                                var label = document.createElement('label');
                                label.htmlFor = 'jewelry_image' + count;
                                label.innerHTML = "เพิ่มรูปภาพ";

                                var input = document.createElement('input');
                                input.type = 'file';
                                input.name = 'jewelry_image_[' + count + ']';
                                input.id = 'jewelry_image' + count;

                                var button = document.createElement('button');
                                button.type = 'button';
                                button.className = "btn btn-danger";
                                button.innerHTML = 'ยกเลิก';;

                                div.appendChild(label);
                                div.appendChild(input);
                                div.appendChild(button);
                                ariashow.appendChild(div);


                                button.addEventListener('click', function() {
                                    removediv(div);
                                });
                            });

                            function removediv(div) {
                                div.remove();
                            }
                        </script>

                    </div>

                    <div style="display: flex; justify-content: end;">
                        <button class="btn btn-success" type="submit">ยืนยันการเพิ่มชุด</button>
                    </div>
                </form>

            </div>


        </div>

    </div>
@endsection
