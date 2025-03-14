@extends('layouts.adminlayout')
@section('content')
    <style>
        .btn-c {
            background-color: rgb(255, 255, 255);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            color: black;
            border: none;
            font-weight: bold;
        }
    </style>
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
    <div class="container-fluid py-4">
        <div class="card mb-4 shadow">
            <div class="card-header">
                <h2 class="card-title mb-2">ชุดรอกำหนดราคา</h2>
                <p class="text-muted ml-2">พบ {{ $count_dress }} รายการที่รอการกำหนดราคา</p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>รูปภาพ</th>
                                <th>ประเภทชุด</th>
                                <th>สถานะ</th>
                                <th class="text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dress_wait as $item)
                                <tr>
                                    <td>
                                        <img src="{{ asset($item->dressimages->first()->dress_image) }}"
                                            alt="" class="img-thumbnail"
                                            style="width: 100px; height: 120px; object-fit: cover;">
                                    </td>
                                    <td class="align-middle">{{ $item->typedress->type_dress_name }}
                                        {{ $item->typedress->specific_letter }}{{ $item->dress_code }}</td>
                                    <td class="align-middle">
                                        <span style="color:rgb(178, 153, 41);"><strong>รอกำหนดราคา</strong></span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <button type="button" class="btn btn-c" data-toggle="modal"
                                            data-target="#priceModal{{ $item->id }}">
                                            กำหนดราคา
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal for each item -->
                                <div class="modal fade" id="priceModal{{ $item->id }}" tabindex="-1" aria-hidden="true"
                                    data-backdrop="static">
                                    <div class="modal-dialog" style="max-width: 50%;">
                                        <div class="modal-content">
                                            <form action="{{ route('dresswaitpricesaved', ['id' => $item->id]) }}"
                                                method="POST">
                                                @csrf

                                                <div class="modal-header">
                                                    <h5 class="mb-3">กำหนดราคาเช่า{{ $item->typedress->type_dress_name }}
                                                        {{ $item->typedress->specific_letter }}{{ $item->dress_code }}
                                                    </h5>
                                                </div>


                                                <div class="modal-body">
                                                    <!-- หัวข้อและรูปภาพ -->


                                                    <div class="text-center mb-4">
                                                        <img src="{{ asset($item->dressimages->first()->dress_image) }}"
                                                            alt="ภาพชุด" class="img-thumbnail"
                                                            style="width: 200px; height: 230px; object-fit: cover;">
                                                            <br>
                                                            <span style="color: #EE4E4E ; ">*ชุดนี้มาจากการเช่าตัด</span>
                                                    </div>

                                                    <div class="mb-4">

                                                        <div class="row g-3">
                                                            <div class="col-md-4">
                                                                <label class="form-label">ราคาเช่าทั้งชุด:</label>
                                                                <input type="number" class="form-control"
                                                                    placeholder="ราคาเช่า" name="price_dress" step="0.01"
                                                                    id="price_dress{{ $item->id }}" required>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label">เงินมัดจำ (30% ของราคาเช่า)</label>
                                                                <input type="number" class="form-control"
                                                                    placeholder="เงินมัดจำ" name="deposit_dress"
                                                                    id="deposit_dress{{ $item->id }}" step="0.01"
                                                                    required readonly>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label">ค่าประกัน (เท่ากับราคาเช่า)</label>
                                                                <input type="number" class="form-control"
                                                                    placeholder="ค่าประกัน" name="insurance_dress"
                                                                    id="insurance_dress{{ $item->id }}" step="0.01"
                                                                    required readonly>
                                                            </div>
                                                        </div>
                                                        <script>
                                                            document.addEventListener('DOMContentLoaded', function() {
                                                                var input_price = document.getElementById('price_dress{{ $item->id }}');
                                                                var value_deposit_dress = document.getElementById('deposit_dress{{ $item->id }}');
                                                                var value_insurance_dress = document.getElementById('insurance_dress{{ $item->id }}');
                                                                input_price.addEventListener('input', function() {

                                                                    var change_input_price = parseFloat(input_price.value);
                                                                    if (change_input_price == 0) {
                                                                        value_deposit_dress.value = '';
                                                                        value_insurance_dress.value = '';
                                                                    } else {
                                                                        value_deposit_dress.value = Math.ceil(change_input_price * 0.3);
                                                                        value_insurance_dress.value = Math.ceil(change_input_price);

                                                                    }

                                                                });
                                                            });
                                                        </script>

                                                    </div>

                                                    <div class="mb-4"
                                                        @if ($item->separable == 2) style="display: block;" @else style="display: none;" @endif>

                                                        <div class="row g-3">
                                                            <div class="col-md-4">
                                                                <label class="form-label">ราคาเช่าเสื้อ:</label>
                                                                <input type="number" class="form-control"
                                                                    placeholder="ราคาเช่า" name="price_shirt"
                                                                    id="price_shirt{{ $item->id }}" step="0.01"
                                                                    @if ($item->separable == 2) required @endif>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label">เงินมัดจำ (30% ของราคาเช่า)</label>
                                                                <input type="number" class="form-control"
                                                                    placeholder="เงินมัดจำ" name="deposit_shirt"
                                                                    id="deposit_shirt{{ $item->id }}" step="0.01"
                                                                    @if ($item->separable == 2) required @endif
                                                                    readonly>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label">ค่าประกัน (เท่ากับราคาเช่า)</label>
                                                                <input type="number" class="form-control"
                                                                    placeholder="ค่าประกัน" name="insurance_shirt"
                                                                    id="insurance_shirt{{ $item->id }}"
                                                                    step="0.01"
                                                                    @if ($item->separable == 2) required @endif
                                                                    readonly>
                                                            </div>
                                                        </div>

                                                        <script>
                                                            document.addEventListener('DOMContentLoaded', function() {
                                                                var input_price_shirt = document.getElementById('price_shirt{{ $item->id }}');
                                                                var value_deposit_shirt = document.getElementById('deposit_shirt{{ $item->id }}');
                                                                var value_insurance_shirt = document.getElementById('insurance_shirt{{ $item->id }}');
                                                                input_price_shirt.addEventListener('input', function() {
                                                                    var change_input_price_shirt = parseFloat(input_price_shirt.value);
                                                                    if (change_input_price_shirt == 0) {
                                                                        value_deposit_shirt.value = '';
                                                                        value_insurance_shirt.value = '';
                                                                    } else {
                                                                        value_deposit_shirt.value = Math.ceil(change_input_price_shirt * 0.3);
                                                                        value_insurance_shirt.value = Math.ceil(change_input_price_shirt);

                                                                    }

                                                                });
                                                            });
                                                        </script>
                                                    </div>

                                                    <div class="mb-4"
                                                        @if ($item->separable == 2) style="display: block;" @else style="display: none;" @endif>
                                                        <div class="row g-3">
                                                            <div class="col-md-4">
                                                                <label class="form-label">ราคาเช่าผ้าถุง:</label>
                                                                <input type="number" class="form-control"
                                                                    placeholder="ราคาเช่า" name="skirt_price"
                                                                    id="skirt_price{{ $item->id }}" step="0.01"
                                                                    @if ($item->separable == 2) required @endif>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label">เงินมัดจำ (30% ของราคาเช่า)</label>
                                                                <input type="number" class="form-control"
                                                                    placeholder="เงินมัดจำ" name="skirt_deposit"
                                                                    id="skirt_deposit{{ $item->id }}" step="0.01"
                                                                    @if ($item->separable == 2) required @endif
                                                                    required readonly>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label">ค่าประกัน (เท่ากับราคาเช่า)</label>
                                                                <input type="number" class="form-control"
                                                                    placeholder="ค่าประกัน" name="skirt_insurance"
                                                                    id="skirt_insurance{{ $item->id }}"
                                                                    step="0.01"
                                                                    @if ($item->separable == 2) required @endif
                                                                    readonly>
                                                            </div>
                                                        </div>

                                                        <script>
                                                            document.addEventListener('DOMContentLoaded', function() {
                                                                var input_skirt_price = document.getElementById('skirt_price{{ $item->id }}');
                                                                var value_skirt_deposit = document.getElementById('skirt_deposit{{ $item->id }}');
                                                                var value_skirt_insurance = document.getElementById('skirt_insurance{{ $item->id }}');
                                                                input_skirt_price.addEventListener('input', function() {
                                                                    var change_input_skirt_price = parseFloat(input_skirt_price.value);
                                                                    if (change_input_skirt_price == 0) {
                                                                        value_skirt_deposit.value = '';
                                                                        value_skirt_insurance.value = '';
                                                                    } else {
                                                                        value_skirt_deposit.value = Math.ceil(change_input_skirt_price * 0.3);
                                                                        value_skirt_insurance.value = Math.ceil(change_input_skirt_price);

                                                                    }

                                                                });
                                                            });
                                                        </script>


                                                    </div>



                                                </div>

                                                <div class="modal-footer">
                                                    <div class="text-end">

                                                        {{-- <a href="{{ route('dresswaitprice') }}"
                                                            class="btn btn-light me-2">
                                                            ยกเลิก
                                                        </a> --}}
                                                        
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                                                        <button type="submit"
                                                            class="btn"style="background-color:#ACE6B7">บันทึกราคา</button>
                                                    </div>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $dress_wait->links() }}



                </div>
            </div>
        </div>
    </div>
@endsection
