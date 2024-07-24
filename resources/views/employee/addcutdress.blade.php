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



    <form action="{{ route('employee.savecutdress') }}" method="POST" enctype="multipart/form-data">
        <div class="container mt-4">
            <!-- กล่องแรก: ฟอร์มเพิ่มออเดอร์ -->
            <h4 class="mb-4" style="text-align: center ; ">ข้อมูลตัดชุด</h4>

            <div class="shadow p-4 mb-5  rounded" style="background-color: #EEEEEE">
                @csrf
                <div class="row mb-3">

                    <div class="col-md-3">
                        <label for="dressType" class="form-label">ประเภทชุดที่ตัด</label>
                        <select class="form-control" id="type_dress" name="type_dress" required>
                            <option value="" selected disabled>เลือกรายการ</option>
                            @foreach ($type_dress as $dressType)
                                <option value="{{ $dressType->type_dress_name }}">{{ $dressType->type_dress_name }}</option>
                            @endforeach
                            <option value="other_type">อื่นๆ</option>
                        </select>
                    </div>
                    <div class="col-md-3" style="display: none;" id="showinput">
                        <label for="" class="form-label">กรอกประเภทชุด อื่นๆ</label>
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
                    <div class="col-md-3">
                        <label for="pickup_date" class="form-label">วันที่นัดรับชุด</label>
                        @php
                            $today = \Carbon\Carbon::today()->toDateString();
                        @endphp
                        <input type="date" class="form-control" id="pickup_date" name="pickup_date"
                            min="{{ $today }}" required>
                    </div>
                    <div class="col-md-3">
                        <label for="amount" class="form-label">จำนวนชุด</label>
                        <input type="number" class="form-control" id="amount" name="amount" value="1"
                            min="1" required>
                    </div>
                    <div class="col-md-3">
                        <label for="price" class="form-label">ราคาเต็ม/ชุด</label>
                        <input type="number" class="form-control" id="price" name="price" placeholder="กรอกราคา"
                            min="1" step="0.01" required>
                    </div>

                    <div class="col-md-3">
                        <label for="deposit" class="form-label">ราคามัดจำ/ชุด</label>
                        <input type="number" class="form-control" id="deposit" name="deposit" placeholder="กรอกคารา"
                            min="1" step="0.01" required>
                    </div>


                </div>


                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">ผ้า</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="cloth" id="cloth1" value="1">
                            <label class="form-check-label" for="cloth1">
                                ลูกค้านำผ้ามาเอง
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="cloth" id="cloth2"
                                value="2">
                            <label class="form-check-label" for="cloth2">
                                ทางร้านหาผ้าให้
                            </label>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <label class="form-label">การจ่ายเงิน</label>
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

                    <div class="col-md-6">
                        <label for="note" class="form-label">รายละเอียดอื่นๆ</label>
                        <textarea class="form-control" id="note" name="note" rows="4"
                            placeholder="ใส่รายละเอียดเพิ่มเติม(หากมี)"></textarea>
                    </div>






                </div>



            </div>



            {{-- กล่องที่สอง --}}
            <div class="shadow p-4 mb-5  rounded" style="background-color: #EEEEEE">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0"><i class="bi bi-rulers"></i>บันทึกการวัดตัว</h4>
                    <button type="button" class="btn btn-primary" id="addMeasurementField"><i
                            class="bi bi-plus"></i>เพิ่มการวัด</button>
                </div>

                <div id="aria_show_measurement">
                    <div class="row mb-3" id="showmea0">
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="mea_name0" name="mea_name_[0]"
                                placeholder="เพิ่มชื่อการวัดเช่น รอบอก" value="ยาวหน้า">
                        </div>

                        <div class="col-sm-3">
                            <input type="number" class="form-control" id="mea_number0" name="mea_number_[0]"
                                placeholder="ค่า" step="0.01">
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

                    <div class="row mb-3" id="showmea0">
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="mea_name0" name="mea_name_[0]"
                                placeholder="เพิ่มชื่อการวัดเช่น รอบอก" value="ยาวหลัง">
                        </div>

                        <div class="col-sm-3">
                            <input type="number" class="form-control" id="mea_number0" name="mea_number_[0]"
                                placeholder="ค่า" step="0.01">
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

                    <div class="row mb-3" id="showmea0">
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="mea_name0" name="mea_name_[0]"
                                placeholder="เพิ่มชื่อการวัดเช่น รอบอก" value="ไหล่กว้าง">
                        </div>

                        <div class="col-sm-3">
                            <input type="number" class="form-control" id="mea_number0" name="mea_number_[0]"
                                placeholder="ค่า" step="0.01">
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

                    <div class="row mb-3" id="showmea0">
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="mea_name0" name="mea_name_[0]"
                                placeholder="เพิ่มชื่อการวัดเช่น รอบอก" value="บ่าหน้า">
                        </div>

                        <div class="col-sm-3">
                            <input type="number" class="form-control" id="mea_number0" name="mea_number_[0]"
                                placeholder="ค่า" step="0.01">
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


                    <div class="row mb-3" id="showmea0">
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="mea_name0" name="mea_name_[0]"
                                placeholder="เพิ่มชื่อการวัดเช่น รอบอก" value="บ่าหลัง">
                        </div>

                        <div class="col-sm-3">
                            <input type="number" class="form-control" id="mea_number0" name="mea_number_[0]"
                                placeholder="ค่า" step="0.01">
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

                    <div class="row mb-3" id="showmea0">
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="mea_name0" name="mea_name_[0]"
                                placeholder="เพิ่มชื่อการวัดเช่น รอบอก" value="รอบคอ">
                        </div>

                        <div class="col-sm-3">
                            <input type="number" class="form-control" id="mea_number0" name="mea_number_[0]"
                                placeholder="ค่า" step="0.01">
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
                    <div class="row mb-3" id="showmea0">
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="mea_name0" name="mea_name_[0]"
                                placeholder="เพิ่มชื่อการวัดเช่น รอบอก" value="รักแร้">
                        </div>

                        <div class="col-sm-3">
                            <input type="number" class="form-control" id="mea_number0" name="mea_number_[0]"
                                placeholder="ค่า" step="0.01">
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
                    <div class="row mb-3" id="showmea0">
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="mea_name0" name="mea_name_[0]"
                                placeholder="เพิ่มชื่อการวัดเช่น รอบอก" value="รอบอก">
                        </div>

                        <div class="col-sm-3">
                            <input type="number" class="form-control" id="mea_number0" name="mea_number_[0]"
                                placeholder="ค่า" step="0.01">
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
                    <div class="row mb-3" id="showmea0">
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="mea_name0" name="mea_name_[0]"
                                placeholder="เพิ่มชื่อการวัดเช่น รอบอก" value="อกห่าง">
                        </div>

                        <div class="col-sm-3">
                            <input type="number" class="form-control" id="mea_number0" name="mea_number_[0]"
                                placeholder="ค่า" step="0.01">
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
                    <div class="row mb-3" id="showmea0">
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="mea_name0" name="mea_name_[0]"
                                placeholder="เพิ่มชื่อการวัดเช่น รอบอก" value="อกสูง">
                        </div>

                        <div class="col-sm-3">
                            <input type="number" class="form-control" id="mea_number0" name="mea_number_[0]"
                                placeholder="ค่า" step="0.01">
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

                    <div class="row mb-3" id="showmea0">
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="mea_name0" name="mea_name_[0]"
                                placeholder="เพิ่มชื่อการวัดเช่น รอบอก" value="รอบเอว">
                        </div>

                        <div class="col-sm-3">
                            <input type="number" class="form-control" id="mea_number0" name="mea_number_[0]"
                                placeholder="ค่า" step="0.01">
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


                    <div class="row mb-3" id="showmea0">
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="mea_name0" name="mea_name_[0]"
                                placeholder="เพิ่มชื่อการวัดเช่น รอบอก" value="สะโพกเล็ก">
                        </div>

                        <div class="col-sm-3">
                            <input type="number" class="form-control" id="mea_number0" name="mea_number_[0]"
                                placeholder="ค่า" step="0.01">
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


                    <div class="row mb-3" id="showmea0">
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="mea_name0" name="mea_name_[0]"
                                placeholder="เพิ่มชื่อการวัดเช่น รอบอก" value="สะโพก">
                        </div>

                        <div class="col-sm-3">
                            <input type="number" class="form-control" id="mea_number0" name="mea_number_[0]"
                                placeholder="ค่า" step="0.01">
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



                    <div class="row mb-3" id="showmea0">
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="mea_name0" name="mea_name_[0]"
                                placeholder="เพิ่มชื่อการวัดเช่น รอบอก" value="กระโปรงยาว">
                        </div>

                        <div class="col-sm-3">
                            <input type="number" class="form-control" id="mea_number0" name="mea_number_[0]"
                                placeholder="ค่า" step="0.01">
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

                    <div class="row mb-3" id="showmea0">
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="mea_name0" name="mea_name_[0]"
                                placeholder="เพิ่มชื่อการวัดเช่น รอบอก" value="แขนยาว">
                        </div>

                        <div class="col-sm-3">
                            <input type="number" class="form-control" id="mea_number0" name="mea_number_[0]"
                                placeholder="ค่า" step="0.01">
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


                    <div class="row mb-3" id="showmea0">
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="mea_name0" name="mea_name_[0]"
                                placeholder="เพิ่มชื่อการวัดเช่น รอบอก" value="แขนกว้าง">
                        </div>

                        <div class="col-sm-3">
                            <input type="number" class="form-control" id="mea_number0" name="mea_number_[0]"
                                placeholder="ค่า" step="0.01">
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



                    {{-- <div class="row mb-3" id="showmea0">
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="mea_name0" name="mea_name_[0]"
                                placeholder="เพิ่มชื่อการวัดเช่น รอบอก" value="ต้นขา">
                        </div>

                        <div class="col-sm-3">
                            <input type="number" class="form-control" id="mea_number0" name="mea_number_[0]"
                                placeholder="ใส่ตัวเลข" step="0.01">
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
                    </div> --}}



                    {{-- <div class="row mb-3" id="showmea0">
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="mea_name0" name="mea_name_[0]"
                                placeholder="เพิ่มชื่อการวัดเช่น รอบอก" value="ปลายขา">
                        </div>

                        <div class="col-sm-3">
                            <input type="number" class="form-control" id="mea_number0" name="mea_number_[0]"
                                placeholder="ใส่ตัวเลข" step="0.01">
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
                    </div> --}}



                    {{-- <div class="row mb-3" id="showmea0">
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="mea_name0" name="mea_name_[0]"
                                placeholder="เพิ่มชื่อการวัดเช่น รอบอก" value="เอวข้อเท้า">
                        </div>

                        <div class="col-sm-3">
                            <input type="number" class="form-control" id="mea_number0" name="mea_number_[0]"
                                placeholder="ใส่ตัวเลข" step="0.01">
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
                    </div> --}}




                    {{-- <div class="row mb-3" id="showmea0">
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="mea_name0" name="mea_name_[0]"
                                placeholder="เพิ่มชื่อการวัดเช่น รอบอก" value="รอบข้อเท้า">
                        </div>

                        <div class="col-sm-3">
                            <input type="number" class="form-control" id="mea_number0" name="mea_number_[0]"
                                placeholder="ใส่ตัวเลข" step="0.01">
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
                    </div> --}}




                    {{-- <div class="row mb-3" id="showmea0">
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="mea_name0" name="mea_name_[0]"
                                placeholder="เพิ่มชื่อการวัดเช่น รอบอก" value="เป้า">
                        </div>

                        <div class="col-sm-3">
                            <input type="number" class="form-control" id="mea_number0" name="mea_number_[0]"
                                placeholder="ค่า" step="0.01">
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
                    </div> --}}






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
                            ' " name="mea_name_[' + count_mea + ']" placeholder="ชื่อการวัด" required >' +
                            '</div>' +

                            '<div class="col-sm-3">' +
                            '<input type="number" class="form-control" id="mea_number' + count_mea +
                            ' " name="mea_number_[' + count_mea + ']" placeholder="ค่า" required >' +
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
                    <h4 class="mb-0">นัดลูกค้าลองชุด(หากมี)</h4>
                    <button type="button" class="btn btn-primary" id="addfitting">
                        <i class="bi bi-plus"></i>เพิ่มวันนัดลองชุด</button>
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

                @php
                    $past = \Carbon\Carbon::today()->toDateString();
                @endphp


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
                            '" name="fitting_date_[' + count_fitting + ']" min="{{ $past }}">' +
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








            <!-- ปุ่มยืนยัน -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-block">เพิ่มลงในตะกร้า</button>
            </div>
    </form>
    </div>
@endsection
