<div class="modal fade" id="showfail" role="dialog" aria-hidden="true">
    <div class="modal-dialog custom-modal-dialog" role="document">
        <div class="modal-content custom-modal-content"
            style="max-width: 350px; height: 50px; width: 100%; margin: auto; background-color: #EE4E4E; border: 2px solid #EE4E4E; ">
            <div class="modal-body" style="padding: 10px; display: flex; align-items: center; justify-content: center;">
                <p style="margin: 0; color: #ffffff;">{{ session('fail') }}</p>
            </div>
        </div>
    </div>
</div>

<style>
    #button_add_mea_total {
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

    #button_add_mea_total:hover {
        background-color: #2980b9;
        /* เปลี่ยนสีเมื่อชี้ */
    }

    #add_mea_shirt {
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

    #add_mea_shirt:hover {
        background-color: #2980b9;
        /* เปลี่ยนสีเมื่อชี้ */
    }

    #add_mea_skirt {
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

    #add_mea_skirt:hover {
        background-color: #2980b9;
        /* เปลี่ยนสีเมื่อชี้ */
    }

    .form-label {
        font-weight: bold;
    }
</style>



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
    @if (session('dressCodes'))
        setTimeout(function() {
            $('#showsuccessss').modal('show');
        }, 500);
    @endif
</script>

@extends('layouts.adminlayout')
@section('content')
    <form action="{{ route('admin.savedress') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="container mt-5">
            <div class="card shadow">
                <div class="card-body">

                    <h3 style="text-align: start ;">แบบฟอร์มการเพิ่มชุด</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="type_dress" class="form-label ">เลือกประเภทชุดที่ต้องการเพิ่ม</label>
                            <select name="type_dress_id" id="type_dress_id" class="form-control" required>
                                <option value="" disabled selected>ประเภทชุดที่ต้องการเพิ่ม</option>
                                @foreach ($typeDresses as $typeDress)
                                    <option value="{{ $typeDress->id }}">{{ $typeDress->type_dress_name }}</option>
                                @endforeach
                                <option value="select_other">อื่นๆ</option>
                            </select>
                        </div>

                        <div class="col-md-6" id="show_other_input" style="display: none;">
                            <label for="" class="form-label">ระบุประเภทชุดที่เพิ่ม</label>
                            <input type="text" name="inputother" id="other_input" class="form-control">
                        </div>
                        <script>
                            var type_dress = document.getElementById('type_dress_id');
                            var show_other_input = document.getElementById('show_other_input');
                            type_dress.addEventListener('change', function() {
                                if (type_dress.value === 'select_other') {
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
                        <div class="col-md-6">
                            <label for="" class="form-label ">ราคาเช่า (บาท)</label>
                            <input type="number" class="form-control" name="dress_price" id="dress_price" min="0"
                                step="0.01" required placeholder="กรอกจำนวนเงิน">
                        </div>

                        <div class="col-md-6">
                            <label for="" class="form-label">ราคามัดจำ (บาท) 30% ของราคาเช่า</label>
                            <input type="number" class="form-control" name="dress_deposit" id="dress_deposit"
                                min="0" step="0.01" required readonly>
                        </div>
                    </div>


                    <div class="row mt-4">
                        <div class="col-md-6">
                            <label for="" class="form-label">ประกันค่าเสียหายชุด (บาท) เท่ากับราคาเช่า</label>
                            <input type="number" class="form-control" min="0" step="0.01" required
                                name="damage_insurance" id="damage_insurance" readonly>
                        </div>

                        <div class="col-md-6" id="coun">
                            <label for="" class="form-label">จำนวนชุด</label>
                            <input type="number" class="form-control" min="1" required placeholder="กรอกจำนวนชุด"
                                name="dress_count" id="dress_count" value="1">
                        </div>
                    </div>
                    <script>
                        var dress_price = document.getElementById('dress_price');
                        var dress_deposit = document.getElementById('dress_deposit');
                        var damage_insurance = document.getElementById('damage_insurance');
                        dress_price.addEventListener('input', function() {
                            var value_input_price = dress_price.value;
                            var convert_price = parseFloat(value_input_price);
                            if (convert_price) {
                                dress_deposit.value = Math.ceil(convert_price * 0.3);
                                damage_insurance.value = Math.ceil(convert_price * 1);
                            } else {
                                dress_deposit.value = '';
                                damage_insurance.value = '';
                            }

                        });
                    </script>


                    <div class="row mt-4">
                        <div class="col-md-12">
                            <label for="" class="form-label">รายละเอียดเพิ่มเติม</label>
                            <textarea class="form-control" rows="3" placeholder="ใส่รายละเอียด(หากมี)" name="dress_description"
                                id="dress_description"></textarea>

                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <label for="imageUpload" class="form-label">อัปโหลดรูปภาพชุด</label>
                            <input type="file" class="form-control" accept="image/*" name="add_image" id="add_image"
                                required>
                        </div>
                    </div>


                    <div class="row mt-4">
                        <div class="col-md-12">
                            <label for="" class="form-label">ประเภทการให้เช่า</label><br>
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




                    <div class="row mt-5" id="show_div_dress_total" style="display: none ; ">
                        <div class="col-md-12">
                            <div id="header_dress_total">
                                <p>ข้อมูลการวัดตัวของชุด (หน่วยเป็นนิ้ว)
                                    <button type="button" id="button_add_mea_total">+ เพิ่มการวัดเพิ่มเติม</button>
                                </p>
                            </div>

                            <div class="row mt-1" id="show_dress_total">
                                <div class="col-md-12" id="aria_dress_total1">
                                    <div class="row mb-3">
                                        <div class="col-md-2">
                                            <input type="text" class="form-control" name="name_total_[1]"
                                                placeholder="ชื่อการวัด" value="รอบอก"  readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control" name="number_total_[1]"
                                                placeholder="ขนาด" step="0.01" min="0" >
                                        </div>

                                        <div class="col-md-2">
                                            <input type="number" class="form-control" name="number_total_min_[1]"
                                                placeholder="ขนาดต่ำสุด" step="0.01" min="0">
                                        </div>

                                        <div class="col-md-2">
                                            <input type="number" class="form-control" name="number_total_max_[1]"
                                                placeholder="ขนาดสูงสุด" step="0.01" min="0">
                                        </div>

                                        <div class="col-md-2">
                                            <button class="btn" onclick="delete_dress_total(1)"><i
                                                    class="bi bi-x-circle"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12" id="aria_dress_total2">
                                    <div class="row mb-3">
                                        <div class="col-md-2">
                                            <input type="text" class="form-control" name="name_total_[2]"
                                                placeholder="ชื่อการวัด" value="รอบเอว"  readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control" name="number_total_[2]"
                                                placeholder="ขนาด" step="0.01" min="0" >
                                        </div>

                                        <div class="col-md-2">
                                            <input type="number" class="form-control" name="number_total_min_[2]"
                                                placeholder="ขนาดต่ำสุด" step="0.01" min="0" >
                                        </div>

                                        <div class="col-md-2">
                                            <input type="number" class="form-control" name="number_total_max_[2]"
                                                placeholder="ขนาดสูงสุด" step="0.01" min="0" >
                                        </div>

                                        <div class="col-md-2">
                                            <button class="btn" onclick="delete_dress_total(2)"><i
                                                    class="bi bi-x-circle"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12" id="aria_dress_total3">
                                    <div class="row mb-3">
                                        <div class="col-md-2">
                                            <input type="text" class="form-control" name="name_total_[3]"
                                                placeholder="ชื่อการวัด" value="รอบสะโพก"  readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control" name="number_total_[3]"
                                                placeholder="ขนาด" step="0.01" min="0" >
                                        </div>

                                        <div class="col-md-2">
                                            <input type="number" class="form-control" name="number_total_min_[3]"
                                                placeholder="ขนาดต่ำสุด" step="0.01" min="0" >
                                        </div>

                                        <div class="col-md-2">
                                            <input type="number" class="form-control" name="number_total_max_[3]"
                                                placeholder="ขนาดสูงสุด" step="0.01" min="0" >
                                        </div>

                                        <div class="col-md-2">
                                            <button class="btn" onclick="delete_dress_total(3)"><i
                                                    class="bi bi-x-circle"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12" id="aria_dress_total4">
                                    <div class="row mb-3">
                                        <div class="col-md-2">
                                            <input type="text" class="form-control" name="name_total_[4]"
                                                placeholder="ชื่อการวัด" value="ความกว้างของไหล่"  readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control" name="number_total_[4]"
                                                placeholder="ขนาด" step="0.01" min="0" >
                                        </div>

                                        <div class="col-md-2">
                                            <input type="number" class="form-control" name="number_total_min_[4]"
                                                placeholder="ขนาดต่ำสุด" step="0.01" min="0" >
                                        </div>

                                        <div class="col-md-2">
                                            <input type="number" class="form-control" name="number_total_max_[4]"
                                                placeholder="ขนาดสูงสุด" step="0.01" min="0" >
                                        </div>

                                        <div class="col-md-2">
                                            <button class="btn" onclick="delete_dress_total(4)"><i
                                                    class="bi bi-x-circle"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12" id="aria_dress_total5">
                                    <div class="row mb-3">
                                        <div class="col-md-2">
                                            <input type="text" class="form-control" name="name_total_[5]"
                                                placeholder="ชื่อการวัด" value="ความยาวชุด"  readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control" name="number_total_[5]"
                                                placeholder="ขนาด" step="0.01" min="0" >
                                        </div>

                                        <div class="col-md-2">
                                            <input type="number" class="form-control" name="number_total_min_[5]"
                                                placeholder="ขนาดต่ำสุด" step="0.01" min="0" >
                                        </div>

                                        <div class="col-md-2">
                                            <input type="number" class="form-control" name="number_total_max_[5]"
                                                placeholder="ขนาดสูงสุด" step="0.01" min="0">
                                        </div>

                                        <div class="col-md-2">
                                            <button class="btn" onclick="delete_dress_total(5)"><i
                                                    class="bi bi-x-circle"></i></button>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-12" id="aria_dress_total6">
                                    <div class="row mb-3">
                                        <div class="col-md-2">
                                            <input type="text" class="form-control" name="name_total_[6]"
                                                placeholder="ชื่อการวัด" value="ความยาวแขน"  readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control" name="number_total_[6]"
                                                placeholder="ขนาด" step="0.01" min="0" >
                                        </div>

                                        <div class="col-md-2">
                                            <input type="number" class="form-control" name="number_total_min_[6]"
                                                placeholder="ขนาดต่ำสุด" step="0.01" min="0" >
                                        </div>

                                        <div class="col-md-2">
                                            <input type="number" class="form-control" name="number_total_max_[6]"
                                                placeholder="ขนาดสูงสุด" step="0.01" min="0" >
                                        </div>

                                        <div class="col-md-2">
                                            <button class="btn" onclick="delete_dress_total(6)"><i
                                                    class="bi bi-x-circle"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12" id="aria_dress_total7">
                                    <div class="row mb-3">
                                        <div class="col-md-2">
                                            <input type="text" class="form-control" name="name_total_[7]"
                                                placeholder="ชื่อการวัด" value="ความยาวกระโปรง"  readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" class="form-control" name="number_total_[7]"
                                                placeholder="ขนาด" step="0.01" min="0" >
                                        </div>

                                        <div class="col-md-2">
                                            <input type="number" class="form-control" name="number_total_min_[7]"
                                                placeholder="ขนาดต่ำสุด" step="0.01" min="0" >
                                        </div>

                                        <div class="col-md-2">
                                            <input type="number" class="form-control" name="number_total_max_[7]"
                                                placeholder="ขนาดสูงสุด" step="0.01" min="0" >
                                        </div>
                                        <div class="col-md-2">
                                            <button class="btn" onclick="delete_dress_total(7)"><i
                                                    class="bi bi-x-circle"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    var button_add_mea_total = document.getElementById('button_add_mea_total');
                                    var show_dress_total = document.getElementById('show_dress_total');
                                    var count_dress_total = 7;
                                    button_add_mea_total.addEventListener('click', function() {
                                        count_dress_total++;
                                        var div_dress_total = document.createElement('div');
                                        div_dress_total.id = 'aria_dress_total' + count_dress_total;
                                        div_dress_total.className = 'col-md-12';

                                        input_dress_total =

                                            '<div class="row mb-3">' +
                                            '<div class="col-md-2">' +
                                            '<input type="text" required class="form-control" name="name_total_[' +
                                            count_dress_total +
                                            ']" placeholder="ชื่อการวัด">' +
                                            '</div>' +
                                            '<div class="col-md-2">' +
                                            '<input type="number" class="form-control" name="number_total_[' +
                                            count_dress_total +
                                            ']" placeholder="ขนาด" required step="0.01" min="0">' +
                                            '</div>' +

                                            '<div class="col-md-2">' +
                                            '<input type="number" class="form-control" name="number_total_min_[' +
                                            count_dress_total +
                                            ']" placeholder="ขนาดต่ำสุด" required step="0.01" min="0">' +
                                            '</div>' +

                                            '<div class="col-md-2">' +
                                            '<input type="number" class="form-control" name="number_total_max_[' +
                                            count_dress_total +
                                            ']" placeholder="ขนาดสูงสุด" required step="0.01" min="0">' +
                                            '</div>' +

                                            '<div class="col-md-2">' +
                                            '<button class="btn" onclick="delete_dress_total(' + count_dress_total +
                                            ')" ><i class="bi bi-x-circle"></i></button>' +
                                            '</div>' +
                                            '</div>';

                                        div_dress_total.innerHTML = input_dress_total;
                                        show_dress_total.appendChild(div_dress_total);
                                    });
                                </script>

                                <script>
                                    function delete_dress_total(count_dress_total) {
                                        var delete_total_dress = document.getElementById('aria_dress_total' + count_dress_total)
                                        delete_total_dress.remove();
                                    }
                                </script>

                            </div>
                        </div>
                    </div>





                    {{-- เสื้อ/ผ้าถุง --}}
                    <div id="show_div_shirt_skirt" style="display: none ; ">


                        <div class="row mt-5">
                            <div class="col-md-12">
                                <h4>ข้อมูลเสื้อ</h4>
                                <div class="row mt-1">
                                    <div class="col-md-4">
                                        <label for="" class="form-label">ราคาเช่า (บาท)</label>
                                        <input type="number" class="form-control" name="shirt_price" id="shirt_price"
                                            min="0" step="0.01" placeholder="กรอกจำนวนเงิน">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="" class="form-label">ราคามัดจำ (บาท)</label>
                                        <input type="number" class="form-control" name="shirt_deposit"
                                            id="shirt_deposit" min="0" step="0.01" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="" class="form-label">ประกันค่าเสียหาย (บาท)</label>
                                        <input type="number" class="form-control" name="shirt_damage_insurance"
                                            id="shirt_damage_insurance" min="0" step="0.01" readonly>
                                    </div>
                                </div>


                                <script>
                                    var shirt_price = document.getElementById('shirt_price');
                                    var shirt_deposit = document.getElementById('shirt_deposit');
                                    var shirt_damage_insurance = document.getElementById('shirt_damage_insurance');
                                    shirt_price.addEventListener('input', function() {
                                        var convert_price_shirt = parseFloat(shirt_price.value);
                                        if (convert_price_shirt) {
                                            shirt_deposit.value = Math.ceil(convert_price_shirt * 0.3);
                                            shirt_damage_insurance.value = Math.ceil(convert_price_shirt);
                                        } else {
                                            shirt_deposit.value = '';
                                            shirt_damage_insurance.value = '';
                                        }
                                    });
                                </script>

                                <div class="row mt-1">
                                    <div class="col-md-12 mt-3">
                                        <p>ข้อมูลการวัดตัวของเสื้อ (หน่วยเป็นนิ้ว)
                                            <button type="button" id="add_mea_shirt">+ เพิ่มการวัดเพิ่มเติม</button>
                                        </p>
                                    </div>
                                </div>

                                <div class="row" id="show_shirt_total">
                                    {{-- พื้นที่แสดงผล --}}
                                    <div class="col-md-12" id="aria_dress_shirt1">
                                        <div class="row mb-3">
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" name="name_shirt_[1]"
                                                    placeholder="ชื่อการวัด" value="รอบอก"  readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" class="form-control" name="number_shirt_[1]"
                                                    placeholder="ขนาด" step="0.01" min="0" >
                                            </div>

                                            <div class="col-md-2">
                                                <input type="number" class="form-control" name="number_shirt_min_[1]"
                                                    placeholder="ขนาดต่ำสุด" step="0.01" min="0" >
                                            </div>

                                            <div class="col-md-2">
                                                <input type="number" class="form-control" name="number_shirt_max_[1]"
                                                    placeholder="ขนาดสูงสุด" step="0.01" min="0" >
                                            </div>

                                            <div class="col-md-2">
                                                <button class="btn" onclick="delete_dress_shirt(1)"><i
                                                        class="bi bi-x-circle"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" id="aria_dress_shirt2">
                                        <div class="row mb-3">
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" name="name_shirt_[2]"
                                                    placeholder="ชื่อการวัด" value="ความกว้างของไหล่"  readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" class="form-control" name="number_shirt_[2]"
                                                    placeholder="ขนาด" step="0.01" min="0" >
                                            </div>

                                            <div class="col-md-2">
                                                <input type="number" class="form-control" name="number_shirt_min_[2]"
                                                    placeholder="ขนาดต่ำสุด" step="0.01" min="0" >
                                            </div>

                                            <div class="col-md-2">
                                                <input type="number" class="form-control" name="number_shirt_max_[2]"
                                                    placeholder="ขนาดสูงสุด" step="0.01" min="0" >
                                            </div>

                                            <div class="col-md-2">
                                                <button class="btn" onclick="delete_dress_shirt(2)"><i
                                                        class="bi bi-x-circle"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" id="aria_dress_shirt3">
                                        <div class="row mb-3">
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" name="name_shirt_[3]"
                                                    placeholder="ชื่อการวัด" value="ความยาวเสื้อ"  readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" class="form-control" name="number_shirt_[3]"
                                                    placeholder="ขนาด" step="0.01" min="0" >
                                            </div>

                                            <div class="col-md-2">
                                                <input type="number" class="form-control" name="number_shirt_min_[3]"
                                                    placeholder="ขนาดต่ำสุด" step="0.01" min="0" >
                                            </div>

                                            <div class="col-md-2">
                                                <input type="number" class="form-control" name="number_shirt_max_[3]"
                                                    placeholder="ขนาดสูงสุด" step="0.01" min="0" >
                                            </div>

                                            <div class="col-md-2">
                                                <button class="btn" onclick="delete_dress_shirt(3)"><i
                                                        class="bi bi-x-circle"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" id="aria_dress_shirt4">
                                        <div class="row mb-3">
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" name="name_shirt_[4]"
                                                    placeholder="ชื่อการวัด" value="ความยาวแขน"  readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" class="form-control" name="number_shirt_[4]"
                                                    placeholder="ขนาด" step="0.01" min="0" >
                                            </div>

                                            <div class="col-md-2">
                                                <input type="number" class="form-control" name="number_shirt_min_[4]"
                                                    placeholder="ขนาดต่ำสุด" step="0.01" min="0">
                                            </div>

                                            <div class="col-md-2">
                                                <input type="number" class="form-control" name="number_shirt_max_[4]"
                                                    placeholder="ขนาดสูงสุด" step="0.01" min="0">
                                            </div>

                                            <div class="col-md-2">
                                                <button class="btn" onclick="delete_dress_shirt(4)"><i
                                                        class="bi bi-x-circle"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        var show_shirt_total = document.getElementById('show_shirt_total');
                                        var add_mea_shirt = document.getElementById('add_mea_shirt');
                                        var count_add_shirt = 4;
                                        add_mea_shirt.addEventListener('click', function() {
                                            count_add_shirt++;

                                            var div = document.createElement('div');
                                            div.className = 'col-md-12';
                                            div.id = 'aria_dress_shirt' + count_add_shirt;

                                            input =
                                                '<div class="row mb-3">' +
                                                '<div class="col-md-2">' +
                                                '<input type="text" required class="form-control" name="name_shirt_[' + count_add_shirt +
                                                ']" placeholder="ชื่อการวัด">' +
                                                '</div>' +
                                                '<div class="col-md-2">' +
                                                '<input type="number" class="form-control" name="number_shirt_[' + count_add_shirt +
                                                ']" placeholder="ขนาด" required step="0.01" min="0">' +
                                                '</div>' +

                                                '<div class="col-md-2">' +
                                                '<input type="number" class="form-control" name="number_shirt_min_[' + count_add_shirt +
                                                ']" placeholder="ขนาดต่ำสุด" required step="0.01" min="0">' +
                                                '</div>' +

                                                '<div class="col-md-2">' +
                                                '<input type="number" class="form-control" name="number_shirt_max_[' + count_add_shirt +
                                                ']" placeholder="ขนาดสูงสุด" required step="0.01" min="0">' +
                                                '</div>' +

                                                '<div class="col-md-2">' +
                                                '<button class="btn" onclick="delete_dress_shirt(' + count_add_shirt +
                                                ')"><i class="bi bi-x-circle"></i></button>' +
                                                '</div>' +
                                                '</div>';
                                            div.innerHTML = input;
                                            show_shirt_total.appendChild(div);

                                        });

                                        function delete_dress_shirt(count_add_shirt) {
                                            var delete_count_add_shirt = document.getElementById('aria_dress_shirt' + count_add_shirt);
                                            delete_count_add_shirt.remove();
                                        }
                                    </script>

                                </div>
                            </div>
                        </div>




                        <div class="row mt-5">
                            <div class="col-md-12">
                                <h4>ข้อมูลผ้าถุง</h4>
                                <div class="row mt-1">
                                    <div class="col-md-4">
                                        <label for="" class="form-label">ราคาเช่า (บาท)</label>
                                        <input type="number" class="form-control" name="skirt_price" id="skirt_price"
                                            min="0" step="0.01" placeholder="กรอกจำนวนเงิน">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="" class="form-label">ราคามัดจำ (บาท)</label>
                                        <input type="number" class="form-control" name="skirt_deposit"
                                            id="skirt_deposit" min="0" step="0.01" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="" class="form-label">ประกันค่าเสียหาย (บาท)</label>
                                        <input type="number" class="form-control" name="skirt_damage_insurance"
                                            id="skirt_damage_insurance" min="0" step="0.01" readonly>
                                    </div>
                                </div>

                                <script>
                                    var skirt_price = document.getElementById('skirt_price');
                                    var skirt_deposit = document.getElementById('skirt_deposit');
                                    var skirt_damage_insurance = document.getElementById('skirt_damage_insurance');
                                    skirt_price.addEventListener('input', function() {
                                        var convert_skirt_price = parseFloat(skirt_price.value);
                                        if (convert_skirt_price) {
                                            skirt_deposit.value = Math.ceil(convert_skirt_price * 0.3);
                                            skirt_damage_insurance.value = Math.ceil(convert_skirt_price);
                                        } else {
                                            skirt_deposit.value = '';
                                            skirt_damage_insurance.value = '';
                                        }
                                    });
                                </script>




                                <div class="row mt-1">
                                    <div class="col-md-12 mt-3">
                                        <p>ข้อมูลการวัดตัวของผ้าถุง (หน่วยเป็นนิ้ว)
                                            <button type="button" id="add_mea_skirt">+ เพิ่มการวัดเพิ่มเติม</button>
                                        </p>
                                    </div>
                                </div>

                                <div class="row" id="show_skirt_total">
                                    {{-- พื้นที่แสดงผล --}}
                                    <div class="col-md-12" id="aria_dress_skirt1">
                                        <div class="row mb-3">
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" name="name_skirt_[1]"
                                                    placeholder="ชื่อการวัด" value="รอบเอว"  readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" class="form-control" name="number_skirt_[1]"
                                                    placeholder="ขนาด" step="0.01" min="0" >
                                            </div>

                                            <div class="col-md-2">
                                                <input type="number" class="form-control" name="number_skirt_min_[1]"
                                                    placeholder="ขนาดต่ำสุด" step="0.01" min="0" >
                                            </div>

                                            <div class="col-md-2">
                                                <input type="number" class="form-control" name="number_skirt_max_[1]"
                                                    placeholder="ขนาดสูงสุด" step="0.01" min="0" >
                                            </div>

                                            <div class="col-md-2">
                                                <button class="btn" onclick="delete_dress_skirt(1)"><i
                                                        class="bi bi-x-circle"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" id="aria_dress_skirt2">
                                        <div class="row mb-3">
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" name="name_skirt_[2]"
                                                    placeholder="ชื่อการวัด" value="รอบสะโพก"  readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" class="form-control" name="number_skirt_[2]"
                                                    placeholder="ขนาด" step="0.01" min="0" >
                                            </div>

                                            <div class="col-md-2">
                                                <input type="number" class="form-control" name="number_skirt_min_[2]"
                                                    placeholder="ขนาดต่ำสุด" step="0.01" min="0" >
                                            </div>

                                            <div class="col-md-2">
                                                <input type="number" class="form-control" name="number_skirt_max_[2]"
                                                    placeholder="ขนาดสูงสุด" step="0.01" min="0" >
                                            </div>

                                            <div class="col-md-2">
                                                <button class="btn" onclick="delete_dress_skirt(2)"><i
                                                        class="bi bi-x-circle"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" id="aria_dress_skirt3">
                                        <div class="row mb-3">
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" name="name_skirt_[3]"
                                                    placeholder="ชื่อการวัด" value="ความยาวผ้าถุง"  readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" class="form-control" name="number_skirt_[3]"
                                                    placeholder="ขนาด" step="0.01" min="0" >
                                            </div>

                                            <div class="col-md-2">
                                                <input type="number" class="form-control" name="number_skirt_min_[3]"
                                                    placeholder="ขนาดต่ำสุด" step="0.01" min="0" >
                                            </div>

                                            <div class="col-md-2">
                                                <input type="number" class="form-control" name="number_skirt_max_[3]"
                                                    placeholder="ขนาดสูงสุด" step="0.01" min="0" >
                                            </div>

                                            <div class="col-md-2">
                                                <button class="btn" onclick="delete_dress_skirt(3)"><i
                                                        class="bi bi-x-circle"></i></button>
                                            </div>
                                        </div>
                                    </div>




                                    <script>
                                        var show_skirt_total = document.getElementById('show_skirt_total');
                                        var add_mea_shirt = document.getElementById('add_mea_skirt');
                                        var count_add_skirt = 3;
                                        add_mea_skirt.addEventListener('click', function() {
                                            count_add_skirt++;

                                            var div = document.createElement('div');
                                            div.className = 'col-md-12';
                                            div.id = 'aria_dress_skirt' + count_add_skirt;

                                            input =
                                                '<div class="row mb-3">' +
                                                '<div class="col-md-2">' +
                                                '<input type="text" required class="form-control" name="name_skirt_[' + count_add_skirt +
                                                ']" placeholder="ชื่อการวัด">' +
                                                '</div>' +
                                                '<div class="col-md-2">' +
                                                '<input type="number" class="form-control" name="number_skirt_[' + count_add_skirt +
                                                ']" placeholder="ขนาด" required step="0.01" min="0">' +
                                                '</div>' +

                                                '<div class="col-md-2">' +
                                                '<input type="number" class="form-control" name="number_skirt_min_[' + count_add_skirt +
                                                ']" placeholder="ขนาดต่ำสุด" required step="0.01" min="0">' +
                                                '</div>' +

                                                '<div class="col-md-2">' +
                                                '<input type="number" class="form-control" name="number_skirt_max_[' + count_add_skirt +
                                                ']" placeholder="ขนาดสูงสุด" required step="0.01" min="0">' +
                                                '</div>' +

                                                '<div class="col-md-2">' +
                                                '<button class="btn" onclick="delete_dress_skirt(' + count_add_skirt +
                                                ')"><i class="bi bi-x-circle"></i></button>' +
                                                '</div>' +
                                                '</div>';
                                            div.innerHTML = input;
                                            show_skirt_total.appendChild(div);

                                        });

                                        function delete_dress_skirt(count_add_skirt) {
                                            var delete_count_add_skirt = document.getElementById('aria_dress_skirt' + count_add_skirt);
                                            delete_count_add_skirt.remove();
                                        }
                                    </script>

                                </div>
                            </div>
                        </div>





                    </div>
                    <script>
                        var show_div_dress_total = document.getElementById('show_div_dress_total');
                        var show_div_shirt_skirt = document.getElementById('show_div_shirt_skirt');
                        var rental_option1 = document.getElementById('rental_option1');
                        var rental_option2 = document.getElementById('rental_option2');
                        rental_option1.addEventListener('change', function() {
                            if (this.checked) {
                                console.log('1');
                                show_div_dress_total.style.display = 'block';
                                show_div_shirt_skirt.style.display = 'none';
                            }
                        });


                        rental_option2.addEventListener('change', function() {
                            if (this.checked) {
                                console.log('2');
                                show_div_shirt_skirt.style.display = 'block';
                                show_div_dress_total.style.display = 'none';
                            }
                        });
                    </script>
                </div>
            </div>




            <div class="row mt-4">
                <div class="col-md-12" style="text-align: end ; ">
                    <button type="submit" class="btn btn-success">ยืนยันการเพิ่มชุด</button>
                </div>
            </div>







        </div>
    </form>
@endsection
