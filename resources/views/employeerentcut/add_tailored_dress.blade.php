@extends('layouts.adminlayout')

@section('content')
    <form action="{{route('storeTailoredDresssaved',['id' => $orderdetail->id])}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="container-fluid py-4">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-lg border-0 rounded-lg">
                        <div class="card-header bg-gradient-primary text-dark py-3">
                            <h3 class="text-center mb-0">
                                <i class="fas fa-tshirt me-2"></i>
                                ชุดนี้เป็นชุดที่ได้มาจากการเช่าตัด และจะถูกเพิ่มเข้าสู่ระบบต่อไป
                            </h3>
                        </div>

                        <div class="card-body px-4 py-4">
                            <div class="row g-4">
                                {{-- Dress Information Section --}}
                                <div class="col-md-6">
                                    <div class="bg-light p-3 rounded mb-3">
                                        <h5 class="text-primary mb-3">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>ข้อมูลชุด</strong>
                                        </h5>
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <p class="mb-1"><strong>ประเภทชุด:</strong></p>
                                                <p class="text-muted">{{ $orderdetail->type_dress }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="mb-1"><strong>หมายเลขชุด:</strong></p>
                                                <p class="text-muted">{{ $typedress->specific_letter }}{{ $next_code }}
                                                </p>
                                            </div>
                                            <div class="col-6">
                                                <p class="mb-1"><strong>จำนวนชุด:</strong></p>
                                                <p class="text-muted">1</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="mb-1"><strong>ราคาเช่า:</strong></p>
                                                <p class="text-success">{{ number_format($orderdetail->price, 2) }} บาท</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="mb-1"><strong>ราคามัดจำ (30%):</strong></p>
                                                <p class="text-warning">{{ number_format($orderdetail->price * 0.3, 2) }}
                                                    บาท
                                                </p>
                                            </div>
                                            <div class="col-6">
                                                <p class="mb-1"><strong>ประกันค่าเสียหายชุด:</strong></p>
                                                <p class="text-danger">{{ number_format($orderdetail->price, 2) }} บาท</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Image Upload Section --}}
                                <div class="col-md-6">
                                    <div class="mt-3 bg-light p-3 rounded">
                                        <h5 class="text-primary mb-3">
                                            <i class="fas fa-ruler me-2"></i>
                                            <strong>ขนาดชุด(นิ้ว)</strong>
                                        </h5>

                                        <div class="row">
                                            @foreach ($measurements as $item)
                                                <div class="col-md-6">
                                                    <p>{{ $item->name }} : <span>{{ $item->new_size }}</span></p>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                    <div class="bg-light p-3 rounded">
                                        <h5 class="text-primary mb-3">
                                            <i class="fas fa-image me-2"></i>
                                            <strong>อัปโหลดรูปภาพ</strong>
                                        </h5>
                                        <div class="form-group">
                                            <label for="dressImage" class="form-label">เพิ่มรูปภาพชุด</label>
                                            <div class="input-group">
                                                <input type="file" name="dress_image" id="dressImage"
                                                    class="form-control" accept="image/*">
                                                <button class="btn btn-outline-secondary" type="button">
                                                    <i class="fas fa-upload"></i>
                                                </button>
                                            </div>
                                            <small class="form-text text-muted">
                                                รองรับไฟล์ PNG, JPG (ขนาดไม่เกิน 5MB)
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                {{-- Additional Details --}}
                                <div class="col-12 mt-3">
                                    <div class="card border-light">
                                        {{-- <div class="card-header bg-light">
                                        <h5 class="text-primary mb-0">
                                            <i class="fas fa-comment-dots me-2"></i>
                                            รายละเอียดเพิ่มเติม
                                        </h5>
                                    </div> --}}
                                        <div class="card-body">
                                            <textarea name="dress_details" class="form-control" rows="3" placeholder="เพิ่มรายละเอียดเกี่ยวกับชุด..."></textarea>
                                        </div>
                                    </div>
                                </div>

                                {{-- Rental Type --}}
                                <div class="col-12 mt-3">
                                    <div class="card border-light">
                                        <div class="card-header bg-light">
                                            <h5 class="text-primary mb-0">
                                                <i class="fas fa-exchange-alt me-2"></i>
                                                ประเภทการให้เช่า
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="btn-group" role="group">
                                                <input type="radio" class="btn-check" name="rental_type" id="rental_whole"
                                                    value="1" checked>
                                                <label class="btn btn-outline-primary" for="rental-whole">
                                                    เช่าแยกไม่ได้
                                                </label>

                                                <input type="radio" class="btn-check" name="rental_type"
                                                    id="rental_separate" value="2">
                                                <label class="btn btn-outline-primary" for="rental-separate">
                                                    เช่าแยกได้
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Measurement Details --}}
                                <div class="col-12 mt-3" id="big_total_dress">
                                    <div class="card border-light">
                                        <div class="card-header bg-light">
                                            <h5 class="text-primary mb-0">
                                                <i class="fas fa-ruler me-2"></i>
                                                ข้อมูลการวัดตัวของชุด (หน่วยเป็นนิ้ว)
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            @foreach ($measurements as $item)
                                                <div class="row mt-1" id="show_dress_total">
                                                    <div class="col-md-12" id="aria_dress_total1">
                                                        <div class="row mb-3">
                                                            <div class="col-md-3">
                                                                <input type="text" class="form-control"
                                                                    name="name_total_[]" placeholder="ชื่อการวัด"
                                                                    value="{{ $item->name }}" readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="number" class="form-control"
                                                                    name="number_total_[]" placeholder="ขนาด"
                                                                    step="0.01" min="0"
                                                                    value="{{ $item->new_size }}" readonly>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <input type="number" class="form-control"
                                                                    name="number_total_min_[]" placeholder="ขนาดต่ำสุด"
                                                                    step="0.01" min="0">
                                                            </div>

                                                            <div class="col-md-3">
                                                                <input type="number" class="form-control"
                                                                    name="number_total_max_[]" placeholder="ขนาดสูงสุด"
                                                                    step="0.01" min="0">
                                                            </div>

                                                            {{-- <div class="col-md-2">
                                                        <button class="btn" onclick="delete_dress_total(1)"><i
                                                                class="bi bi-x-circle"></i></button>
                                                    </div> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>



                                {{-- แยกได้ --}}
                                <div class="col-12 mt-3" id="big_total_shirt" style="display: none ; ">
                                    <div class="card border-light">
                                        <div class="card-header bg-light">
                                            <h5 class="text-primary mb-0">
                                                <i class="fas fa-ruler me-2"></i>
                                                ข้อมูลเสื้อ
                                            </h5>
                                        </div>
                                        <div class="card-body">


                                            <div class="row mt-1 mb-3">
                                                <div class="col-md-4">
                                                    <label for="" class="form-label">ราคาเช่า (บาท)</label>
                                                    <input type="number" class="form-control" name="shirt_price"
                                                        id="shirt_price" min="0" step="0.01"
                                                        placeholder="กรอกจำนวนเงิน">
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="" class="form-label">ราคามัดจำ (บาท)</label>
                                                    <input type="number" class="form-control" name="shirt_deposit"
                                                        id="shirt_deposit" min="0" step="0.01" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="" class="form-label">ประกันค่าเสียหาย
                                                        (บาท)</label>
                                                    <input type="number" class="form-control"
                                                        name="shirt_damage_insurance" id="shirt_damage_insurance"
                                                        min="0" step="0.01" readonly>
                                                </div>
                                            </div>

                                            <script>
                                                var shirt_price = document.getElementById('shirt_price');
                                                var shirt_deposit = document.getElementById('shirt_deposit');
                                                var shirt_damage_insurance = document.getElementById('shirt_damage_insurance');
                                                shirt_price.addEventListener('input', function() {
                                                    var value_shirt_price = parseFloat(shirt_price.value);
                                                    shirt_deposit.value = Math.ceil(value_shirt_price * 0.3);
                                                    shirt_damage_insurance.value = Math.ceil(value_shirt_price);
                                                });
                                            </script>
                                            <p>ข้อมูลการวัดตัวของเสื้อ (หน่วยเป็นนิ้ว) <button type="button"
                                                    id="add_mea_shirt">+ เพิ่มการวัดเพิ่มเติม</button></p>

                                            <div class="row mt-1 mb-3">
                                                <div class="col-md-12" id="aria_show_mea_shirt">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    var add_mea_shirt = document.getElementById('add_mea_shirt');
                                    var aria_show_mea_shirt = document.getElementById('aria_show_mea_shirt');
                                    var count_shirt = 0;
                                    add_mea_shirt.addEventListener('click', function() {
                                        count_shirt++;
                                        var div_shirt = document.createElement('div');
                                        div_shirt.className = 'row mb-3';
                                        div_shirt.id = 'div_sh' + count_shirt;

                                        input_sh =

                                            '<div class="col-md-3">' +
                                            '<input type="text" class="form-control" name="name_shirt_[' + count_shirt +
                                            ']" placeholder="ชื่อการวัด">' +
                                            '</div>' +
                                            '<div class="col-md-2">' +
                                            '<input type="number" class="form-control" name="number_shirt_[' + count_shirt +
                                            ']" placeholder="ขนาด" step="0.01" min="0">' +
                                            '</div>' +

                                            '<div class="col-md-2">' +
                                            '<input type="number" class="form-control" name="number_shirt_min_[' + count_shirt +
                                            ']" placeholder="ขนาดต่ำสุด" step="0.01" min="0">' +
                                            '</div>' +

                                            '<div class="col-md-2">' +
                                            '<input type="number" class="form-control" name="number_shirt_max_[' + count_shirt +
                                            ']" placeholder="ขนาดสูงสุด" step="0.01" min="0">' +
                                            '</div>' +

                                            '<div class="col-md-3">' +
                                            '<button class="btn" onclick="delete_shirt(' + count_shirt +
                                            ')"><i class="bi bi-x-circle"></i></button>' +
                                            '</div>';

                                        div_shirt.innerHTML = input_sh;
                                        aria_show_mea_shirt.appendChild(div_shirt);
                                    });

                                    function delete_shirt(count_shirt) {
                                        var dele_sh = document.getElementById('div_sh' + count_shirt);
                                        dele_sh.remove();
                                    }
                                </script>

                                <div class="col-12 mt-3" id="big_total_skirt" style="display: none ;">
                                    <div class="card border-light">
                                        <div class="card-header bg-light">
                                            <h5 class="text-primary mb-0">
                                                <i class="fas fa-ruler me-2"></i>
                                                ข้อมูลผ้าถุง
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mt-1 mb-3">
                                                <div class="col-md-4">
                                                    <label for="" class="form-label">ราคาเช่า (บาท)</label>
                                                    <input type="number" class="form-control" name="skirt_price"
                                                        id="skirt_price" min="0" step="0.01"
                                                        placeholder="กรอกจำนวนเงิน">
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="" class="form-label">ราคามัดจำ (บาท)</label>
                                                    <input type="number" class="form-control" name="skirt_deposit"
                                                        id="skirt_deposit" min="0" step="0.01" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="" class="form-label">ประกันค่าเสียหาย
                                                        (บาท)</label>
                                                    <input type="number" class="form-control"
                                                        name="skirt_damage_insurance" id="skirt_damage_insurance"
                                                        min="0" step="0.01" readonly>
                                                </div>
                                            </div>

                                            <script>
                                                var skirt_price = document.getElementById('skirt_price');
                                                var skirt_deposit = document.getElementById('skirt_deposit');
                                                var skirt_damage_insurance = document.getElementById('skirt_damage_insurance');
                                                skirt_price.addEventListener('input', function() {
                                                    var value_skirt_price = parseFloat(skirt_price.value);
                                                    skirt_deposit.value = Math.ceil(value_skirt_price * 0.3);
                                                    skirt_damage_insurance.value = Math.ceil(value_skirt_price);
                                                });
                                            </script>
                                            <p>ข้อมูลการวัดตัวของผ้าถุง (หน่วยเป็นนิ้ว) <button type="button"
                                                    id="add_mea_skirt">+ เพิ่มการวัดเพิ่มเติม</button></p>
                                            <div class="row mt-1 mb-3" id="show_dress_total">
                                                <div class="col-md-12" id="aria_show_mea_skirt">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    var add_mea_skirt = document.getElementById('add_mea_skirt');
                                    var aria_show_mea_skirt = document.getElementById('aria_show_mea_skirt');
                                    var count_skirt = 0;
                                    add_mea_skirt.addEventListener('click', function() {
                                        count_skirt++;
                                        var div_skirt = document.createElement('div');
                                        div_skirt.className = 'row mb-3';
                                        div_skirt.id = 'div_sk' + count_skirt;

                                        input_sk =

                                            '<div class="col-md-3">' +
                                            '<input type="text" class="form-control" name="name_skirt_[' + count_skirt +
                                            ']" placeholder="ชื่อการวัด">' +
                                            '</div>' +
                                            '<div class="col-md-2">' +
                                            '<input type="number" class="form-control" name="number_skirt_[' + count_skirt +
                                            ']" placeholder="ขนาด" step="0.01" min="0">' +
                                            '</div>' +

                                            '<div class="col-md-2">' +
                                            '<input type="number" class="form-control" name="number_skirt_min_[' + count_skirt +
                                            ']" placeholder="ขนาดต่ำสุด" step="0.01" min="0">' +
                                            '</div>' +

                                            '<div class="col-md-2">' +
                                            '<input type="number" class="form-control" name="number_skirt_max_[' + count_skirt +
                                            ']" placeholder="ขนาดสูงสุด" step="0.01" min="0">' +
                                            '</div>' +

                                            '<div class="col-md-3">' +
                                            '<button class="btn" onclick="delete_skirt(' + count_skirt +
                                            ')"><i class="bi bi-x-circle"></i></button>' +
                                            '</div>';

                                        div_skirt.innerHTML = input_sk;
                                        aria_show_mea_skirt.appendChild(div_skirt);
                                    });

                                    function delete_skirt(count_skirt) {
                                        var dele_sk = document.getElementById('div_sk' + count_skirt);
                                        dele_sk.remove();
                                    }
                                </script>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="card-footer text-center">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-save me-2"></i>บันทึกข้อมูลชุด
                            </button>
                            <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-undo me-2"></i>ยกเลิก
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <script>
        var big_total_dress = document.getElementById('big_total_dress');
        var big_total_shirt = document.getElementById('big_total_shirt');
        var big_total_skirt = document.getElementById('big_total_skirt');
        var rental_whole = document.getElementById('rental_whole');
        var rental_separate = document.getElementById('rental_separate');
        rental_whole.addEventListener('change', function() {
            if (this.checked) {
                big_total_shirt.style.display = 'none';
                big_total_skirt.style.display = 'none';
                big_total_dress.style.display = 'block';
            }
        });
        rental_separate.addEventListener('change', function() {
            if (this.checked) {
                big_total_shirt.style.display = 'block';
                big_total_skirt.style.display = 'block';
                big_total_dress.style.display = 'none';
            }
        });
    </script>
@endsection
