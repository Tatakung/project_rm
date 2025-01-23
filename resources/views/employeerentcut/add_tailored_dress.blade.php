@extends('layouts.adminlayout')

@section('content')
    <form action="{{ route('storeTailoredDresssaved', ['id' => $orderdetail->id]) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="container-fluid py-4">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-lg border-0 rounded-lg">
                        <div class="card-header text-dark py-3">
                            <h3 class="text-center mb-0">
                                <i class="fas fa-tshirt me-2"></i>
                                ชุดนี้เป็นชุดที่ได้มาจากการเช่าตัด และจะถูกเพิ่มเข้าสู่ระบบต่อไป
                            </h3>
                        </div>

                        <div class="card-body px-4 py-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="bg-light p-3 rounded mb-3">
                                        <h5 class=" mb-3">
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

                                            {{-- <div class="col-12">
                                                <p class="mb-1"><strong>รายละเอียดชุด:</strong></p>
                                                <textarea name="dress_details" class="form-control" rows="3" placeholder="เพิ่มรายละเอียดเกี่ยวกับชุด..."
                                                    required></textarea>
                                            </div> --}}

                                            <div class="col-6">
                                                <p class="mb-1"><strong>ลักษณะการเช่า:</strong></p>
                                                <p class="text-muted">
                                                    @if ($separate_type == 1)
                                                        เช่าแยกไม่ได้
                                                    @elseif($separate_type == 2)
                                                        เช่าแยกได้
                                                    @endif
                                                </p>
                                            </div>

                                            <div class="col-6" id="for_employee" style="display: none ; ">
                                                <p class="mb-1"><strong>ราคาเช่า:</strong></p>
                                                <p class="text-danger">ให้เจ้าของร้านเป็นคนกำหนด
                                                </p>
                                            </div>


                                            <div class="col-12" id="show_price" style="display: none ; ">
                                                <p class="mb-1"><strong>กำหนดราคาเช่าเป็นชุด:</strong></p>
                                                <input type="number" name="show_price_input" id="show_price_input" class="form-control"
                                                    min="0" placeholder="บาท">
                                            </div>
                                            <div class="col-6" id="show_price_shirt" style="display: none ; ">
                                                <p class="mb-1"><strong>ราคาเช่าเฉพาะเสื้อ:</strong></p>
                                                <input type="number" name="show_price_shirt_input" id="show_price_shirt_input" class="form-control"
                                                    min="0" placeholder="บาท">
                                            </div>
                                            <div class="col-6" id="show_price_skirt" style="display: none ; ">
                                                <p class="mb-1"><strong>ราคาเช่าเฉพาะผ้าถุง:</strong></p>
                                                <input type="number" name="show_price_skirt_input" id="show_price_skirt_input" class="form-control"
                                                    min="0" placeholder="บาท">
                                            </div>


                                            <script>
                                                var for_employee = document.getElementById('for_employee');
                                                var show_price = document.getElementById('show_price');
                                                var show_price_shirt = document.getElementById('show_price_shirt');
                                                var show_price_skirt = document.getElementById('show_price_skirt');


                                                var show_price_input = document.getElementById('show_price_input');
                                                var show_price_shirt_input = document.getElementById('show_price_shirt_input');
                                                var show_price_skirt_input = document.getElementById('show_price_skirt_input');

                                                var admin = '{{ $admin }}';
                                                var separate = '{{ $separate_type }}';

                                                if (admin == 0) {
                                                    for_employee.style.display = 'block';
                                                    show_price.style.display = 'none';
                                                    show_price_shirt.style.display = 'none';
                                                    show_price_skirt.style.display = 'none';
                                                    show_price_input.removeAttribute('required');
                                                    show_price_shirt_input.removeAttribute('required');
                                                    show_price_skirt_input.removeAttribute('required');
                                                } else if (admin == 1) {
                                                    if (separate == 1) {
                                                        console.log('แยกบ่ได้');
                                                        for_employee.style.display = 'none';
                                                        show_price.style.display = 'block';
                                                        show_price_shirt.style.display = 'none';
                                                        show_price_skirt.style.display = 'none';
                                                        show_price_input.setAttribute('required', 'required');
                                                        show_price_shirt_input.removeAttribute('required');
                                                        show_price_skirt_input.removeAttribute('required');
                                                    } else if (separate == 2) {
                                                        console.log('แยกได้');
                                                        for_employee.style.display = 'none';
                                                        show_price.style.display = 'block';
                                                        show_price_shirt.style.display = 'block';
                                                        show_price_skirt.style.display = 'block';
                                                        show_price_input.setAttribute('required', 'required');
                                                        show_price_shirt_input.setAttribute('required', 'required');
                                                        show_price_skirt_input.setAttribute('required', 'required');
                                                    }
                                                }
                                            </script>



                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    {{-- <div class="mt-3 bg-light p-3 rounded">
                                        <h5 class=" mb-3">
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
                                    </div> --}}
                                    <div class="bg-light p-3 rounded">
                                        <h5 class=" mb-3">
                                            <i class="fas fa-image me-2"></i>
                                            <strong>อัปโหลดรูปภาพ</strong>
                                        </h5>
                                        <div class="form-group">
                                            <label for="dressImage" class="form-label">เพิ่มรูปภาพชุด</label>
                                            <div class="input-group">
                                                <input type="file" name="dress_image" id="dressImage"
                                                    class="form-control" accept="image/*" required>

                                            </div>
                                            <small class="form-text text-muted">
                                                รองรับไฟล์ PNG, JPG (ขนาดไม่เกิน 5MB)
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <div class="card border-light">
                                        <div class="card-header bg-light">
                                            <h5 class=" mb-0">
                                                <i class="fas fa-comment-dots me-2"></i>
                                                รายละเอียดเพิ่มเติม
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <textarea name="dress_details" class="form-control" rows="3" placeholder="เพิ่มรายละเอียดเกี่ยวกับชุด..."
                                                required></textarea>
                                        </div>
                                    </div>
                                </div>


                                {{-- Measurement Details --}}
                                <div class="col-12 mt-3" id="big_total_dress">
                                    <div class="card border-light">
                                        <div class="card-header bg-light">
                                            <h5 class=" mb-0">
 
                                                ข้อมูลขนาดของ{{ $orderdetail->type_dress }}
                                                {{ $typedress->specific_letter }}{{ $next_code }} (หน่วยเป็นนิ้ว)
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            @foreach ($measurements as $item)
                                                <input type="hidden" value="{{$item->status}}" name="status_[]">
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
                                                                    step="0.01" min="0" required max="{{ $item->new_size }}">
                                                            </div>

                                                            <div class="col-md-3">
                                                                <input type="number" class="form-control"
                                                                    name="number_total_max_[]" placeholder="ขนาดสูงสุด"
                                                                    step="0.01" min="{{ $item->new_size }}" required>
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

                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="card-footer text-end">
                            <button type="submit" class="btn me-2" style="background-color:#ACE6B7">
                                บันทึกข้อมูลชุด
                            </button>
                            <button type="reset" class="btn btn-secondary">
                                ยกเลิก
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection