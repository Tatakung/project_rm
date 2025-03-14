@extends('layouts.adminlayout')
<style>
    .form-label{
        font-weight: bold;
    }
</style>
@section('content')
<div class="modal fade" id="showsuccessss" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header"style="background-color: #EAD8C0">
                บันทึกข้อมูลสำเร็จ
            </div>
            <div class="modal-body">
                {{-- <strong>กรุณานำรหัสชุดเหล่านี้ไปติดไว้ กำกับกับชุดที่ท่านได้เพิ่ม:</strong> --}}
                <ul>
                    @if (session('warn'))
                        @foreach (session('warn') as $warn)
                            <li>{{ $warn }}</li>
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
    @if (session('warn'))
        setTimeout(function() {
            $('#showsuccessss').modal('show');
        }, 500);
    @endif
</script>
    <form action="{{ route('admin.savejewelry') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="container mt-5">
            <div class="card shadow">
                <div class="card-body">
            <h3>แบบฟอร์มเพิ่มเครื่องประดับ</h3>
            <div class="row">
                <div class="col-md-6">
                    <label for="select_type" class="form-label">เลือกประเภทเครื่องประดับที่ต้องการเพิ่ม</label>
                    <select name="select_type" id="select_type" class="form-control" required>
                        <option value="" disabled selected>ประเภทเครื่องประดับที่ต้องการเพิ่ม</option>
                        @foreach ($typejewelry as $item)
                            <option value="{{ $item->id }}">{{ $item->type_jewelry_name }}</option>
                        @endforeach
                        <option value="select_other">อื่นๆ</option>
                    </select>
                </div>

                {{-- หากเลือกอื่นๆ --}}
                <div class="col-md-6" id="show_input_other" style="display: none ; ">
                    <label for="" class="form-label">ระบุประเภทเครื่องประดับที่เพิ่ม</label>
                    <input type="text" name="input_other" id="input_other" class="form-control">
                </div>
                <script>
                    var select_type = document.getElementById('select_type');
                    var show_input_other = document.getElementById('show_input_other');
                    var input_other = document.getElementById('input_other');
                    select_type.addEventListener('change', function() {
                        if (select_type.value === "select_other") {
                            show_input_other.style.display = 'block';
                            input_other.setAttribute('required', 'required');
                        } else {
                            show_input_other.style.display = 'none';
                            input_other.removeAttribute('required');
                            input_other.value = '';
                        }
                    });
                </script>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="" class="form-label">ราคาเช่า</label>
                    <input type="number" name="jewelry_price" id="jewelry_price" class="form-control"
                        placeholder="กรอกจำนวนเงิน">
                </div>

                <div class="col-md-6">
                    <label for="" class="form-label">ราคามัดจำ (บาท) 30% ของราคาเช่า</label>
                    <input type="number" name="jewelry_deposit" id="jewelry_deposit" class="form-control" readonly>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="" class="form-label">ประกันค่าเสียหาย (บาท) เท่ากับราคาเช่า</label>
                    <input type="number" name="damage_insurance" id="damage_insurance" class="form-control" readonly>
                </div>

                <script>
                    var jewelry_price = document.getElementById('jewelry_price');
                    var jewelry_deposit = document.getElementById('jewelry_deposit');
                    var damage_insurance = document.getElementById('damage_insurance');
                    jewelry_price.addEventListener('input', function() {
                        var convert_price = parseFloat(jewelry_price.value);
                        jewelry_deposit.value = Math.ceil(convert_price * 0.3)
                        damage_insurance.value = Math.ceil(convert_price);
                    });
                </script>

                <div class="col-md-6" style="display:none ; ">
                    <label for="" class="form-label">จำนวน</label>
                    <input type="number" name="jewelry_count" id="jewelry_count" min="1" max="100"
                        class="form-control" value="1">
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <label for="" class="form-label">รายละเอียดเพิ่มเติม</label>
                    <textarea class="form-control" rows="3" placeholder="ใส่รายละเอียด(หากมี)" name="jewelry_description"
                        id="jewelry_description"></textarea>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <label for="imageUpload" class="form-label">อัปโหลดรูปภาพเครื่องประดับ</label>
                    <input type="file" class="form-control" accept="image/*" name="jewelry_image" id="jewelry_image">
                </div>
            </div>
        </div>
        </div>
        </div>
        <div class="container mt-3 mb-5" style="text-align: right ; ">
            <div class="col-md-12">
                <button class="btn btn-success" type="submit">ยืนยันการเพิ่มเครื่องประดับ</button>
            </div>
        </div>
    </form>
@endsection