@extends('layouts.adminlayout')
@section('content')

<ol class="breadcrumb" style="background: white ; ">
    <li class="breadcrumb-item">
        <a href="{{route('employee.cart')}}" style="color: black ; ">ตะกร้าสินค้า</a>
    </li>
    <li class="breadcrumb-item active">
        จัดการข้อมูลตัดชุด
    </li>
</ol>


    <style>
        p {
            font-size: 15px;
        }
    </style>
    <div class="modal fade" id="showfail" role="dialog" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog" role="document">
            <div class="modal-content custom-modal-content"
                style="max-width: 500px; height: 50px; width: 100%; margin: auto; background-color: #A7567F; border: 2px solid #ffffff; ">
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
                style="max-width: 400px; height: 50px; width: 100%; margin: auto; background-color: #ffffff; border: 1px solid #6f6f6f; ">
                <div class="modal-body" style="padding: 10px; display: flex; align-items: center; justify-content: center;">
                    <p style="margin: 0; color: #A7567F;">{{ session('success') }}</p>
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

    <form action="{{ route('employee.savemanageitemcutdress', ['id' => $orderdetail->id]) }}" method="POST">
        @csrf
        <div class="container mt-5">
            <div class="row" style="margin-top: 15px;">
                <div class="col-md-6">
                    <p>ข้อมูลตัดชุด</p>
                    <hr>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <!-- ข้อความ "ปรับแก้ขนาดชุด" ทางซ้ายมือ -->
                        <p class="mb-0">ปรับแก้ขนาดชุด</p>

                        <!-- ปุ่ม "เพิ่มการวัด" ทางขวามือสุด -->
                        <button type="button" class="btn  btn-sm" id="button_add_mea" style="background-color: #A7567F;">
                            <i class="bi bi-plus-circle" style="color: #ffffff"></i><span
                                style="color: #ffffff">เพิ่มการวัด</span>
                        </button>
                    </div>
                    <hr>
                </div>

            </div>



            <div class="row">
                <div class="col-md-2">
                    <div
                        style="width: 96px; height: 145px; border-radius: 2px; display: flex; justify-content: center; align-items: center; background-color: #f8f9fa;">
                        <i class="bi bi-scissors" style="font-size: 48px;"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <!-- ประเภทชุด -->
                    <div class="mb-3">
                        <p style="font-size: 17px;">รายการ : ตัด{{ $orderdetail->type_dress }}</p>
                    </div>





                    <div class="mb-3">
                        <label for="" class="form-label"><span>ราคาตัดชุด (บาท)</span></label>
                        <input type="number" style="font-size: 15px;  width: 70%; height: 70%;" class="form-control"
                            name="update_price" value="{{ $orderdetail->price }}" min="1" required>
                    </div>



                    <div class="mb-3">
                        <label for="" class="form-label"><span>เงินมัดจำ (บาท)</span></label>
                        <input type="number" style="font-size: 15px;  width: 70%; height: 70%;" class="form-control"
                            name="update_deposit" value="{{ $orderdetail->deposit }}" min="1" required>
                    </div>
                    <div class="mb-3">
                        <div class="flex-fill pe-2">
                            <label for="" class="form-label"><span>จำนวนชุด</span></label>
                            <input type="number" style="font-size: 15px;  width: 70%; height: 70%;" class="form-control"
                                name="update_amount" value="{{ $orderdetail->amount }}" min="1" required max="100">
                        </div>
                    </div>
                    


                    <div class="mb-3">
                        <label for="update_cloth" class="form-label"><span>ที่มาของผ้า</span></label>
                        <select name="update_cloth" id="update_cloth" class="form-control">
                            <option value="1" {{ $orderdetail->cloth == 1 ? 'selected' : ''  }} >ลูกค้านำผ้ามาเอง</option>
                            <option value="2" {{ $orderdetail->cloth == 2 ? 'selected' : ''}} >ทางร้านหาผ้าให้</option>
                        </select>
                    </div>






                    @php
                        $today = \Carbon\Carbon::today()->toDateString();
                        $date_order_detail = App\Models\Date::where('order_detail_id',$orderdetail->id)
                                            ->orderBy('created_at','desc')
                                            ->first() ; 
                    @endphp
                    <div class="mb-3">
                        <label for="" class="form-label"><span>วันที่นัดส่งมอบชุด</span></label>
                        <input type="date" style="font-size: 15px;  width: 70%; height: 70%;" class="form-control"
                            name="update_pickup_date" value="{{ $date_order_detail->pickup_date }}" min="{{ $today }}">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label"><span>รายละเอียดอื่นๆ(หากมี)</span></label>
                        <textarea name="update_note" id="" cols="1" rows="4" class="form-control">{{ $orderdetail->note }}</textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="aria_show_mea" style="margin-left: 80px;">
                        {{-- พื้นที่แสดงผล --}}
                        @foreach ($measurementadjusts as $measurementorderdetail)
                            <div class="row">
                                <div class="col-md-4" style="text-align: center;">
                                    <input type="hidden" name="mea_id_[]" value="{{ $measurementorderdetail->id }}">
                                    <input type="text" name="update_mea_name_[]" class="form-control"
                                        style="font-size: 15px; margin-top: 8px; width: 90%; height: 70%;"
                                        value="{{ $measurementorderdetail->name }}" placeholder="ชื่อการวัด">
                                </div>
                                <div class="col-md-4" style="display: flex; align-items: center;">
                                    <input type="hidden" value="{{ $measurementorderdetail->id }}"
                                        name="mea_order_detail_id_[]">
                                    <input type="number" name="update_mea_number_[]" class="form-control"
                                        style="width: 90%; height: 70%; font-size: 15px; margin-right: 10px;"
                                        value="{{ $measurementorderdetail->new_size }}" step="0.01"
                                        min="0" max="100" placeholder="ค่าวัด">
                                    <span style="font-size: 15px; margin-left: 20px;">นิ้ว</span>
                                </div>
                                <div class="col-md-4">
                                   

                                    <a href="{{ route('employee.deletemeasurementitem', ['id' => $measurementorderdetail->id]) }}">
                                        <button class="btn d-flex justify-content-center align-items-center"
                                            style="width: 25px; height: 25px; border-radius: 50%; padding: 0; margin-top: 10px; background-color: #A7567F;">
                                            <i class="bi bi-x" style="font-size: 16px; margin: 0; color: white;"></i>
                                        </button>
                                    </a>



                                </div>
                            </div>
                        @endforeach

                    </div>

                </div>

            </div>

            <script>
                var button_add_mea = document.getElementById('button_add_mea');
                var aria_show_mea = document.getElementById('aria_show_mea');
                var count_add_mea = 0;
                button_add_mea.addEventListener('click', function() {
                    count_add_mea++;
                    var divrow = document.createElement('div');
                    divrow.id = 'row_aria_mea' + count_add_mea;
                    divrow.className = 'row';

                    input =

                        '<div class="col-md-4" style="text-align: center;">' +
                        '<input required type="text" name="add_mea_name_[' + count_add_mea +
                        ']" class="form-control" style="font-size: 15px; margin-top: 8px; width: 90%; height: 70%;"  placeholder="ชื่อการวัด">' +
                        '</div>' +
                        '<div class="col-md-4" style="display: flex; align-items: center;">' +
                        '<input type="number" name="add_mea_number_[' + count_add_mea +
                        ']" class="form-control" style="width: 90%; height: 70%; font-size: 15px; margin-right: 10px;" step="0.01" min="0" max="100" required placeholder="ค่าวัด">' +
                        '<span style="margin-left: 5px; font-size: 15px;">นิ้ว</span>' +
                        '</div>' +
                        '<div class="col-md-4">' +
                        '<button class="btn d-flex justify-content-center align-items-center" style="width: 25px; height: 25px; border-radius: 50%; padding: 0; margin-top: 10px; background-color: #A7567F;" onclick="deletemea(' +
                        count_add_mea + ')">' +
                        '<i class="bi bi-x" style="font-size: 16px; margin: 0; color: white;"></i>' +
                        '</button>' +
                        '</div>';
                    divrow.innerHTML = input;
                    aria_show_mea.appendChild(divrow);

                });

                function deletemea(count_add_mea) {
                    var deleterow = document.getElementById('row_aria_mea' + count_add_mea);
                    deleterow.remove();
                }
            </script>


            <div class="row">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-dark" style="margin-top: 3%">ยืนยัน</button>
                </div>
            </div>

        </div>
    </form>


















@endsection
