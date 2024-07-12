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







    <form action="{{ route('employee.savemanageitemrentjewelry', ['id' => $orderdetail->id]) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="container mt-4">
            <!-- กล่องแรกฟอร์มเพิ่มออเดอร์ -->
            <div class="shadow p-4 mb-5 bg-red rounded">
                <h4 class="mb-4" style="text-align: center">ข้อมูลเช่าเครื่องประดับ</h4>
                <div class="row mb-3">

                    <div class="col-sm-4">
                        <label for="dressType" class="form-label">ประเภทเครื่องประดับ</label>
                        <input type="text" class="form-control" id="" name="" value="{{$orderdetail->type_dress}}" readonly>
                    </div>
                    

                    <div class="col-md-4">
                            <label for="" class="form-label">หมายเลขเครื่องประดับ</label>
                            <input type="text" class="form-control" id="" name=""
                                value="{{ $jewelry->jewelry_code_new }}{{ $jewelry->jewelry_code }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="amount" class="form-label">จำนวนเครื่องประดับ</label>
                        <input type="number" class="form-control" id="update_amount" name="update_amount"
                            value="{{ $orderdetail->amount }}" min="1" required readonly>
                    </div>
                </div>
                <div class="row mb-3">
                
                    <div class="col-md-4">
                        <label for="price" class="form-label">ราคาเต็ม/ชิ้น</label>
                        <input type="number" class="form-control" id="update_price" name="update_price"
                            placeholder="จำนวนเงิน" min="1" step="0.01" value="{{ $orderdetail->price }}"
                            required readonly>
                    </div>

                    <div class="col-md-4">
                        <label for="deposit" class="form-label">ราคามัดจำ/ชิ้น</label>
                        <input type="number" class="form-control" id="update_deposit" name="update_deposit"
                            placeholder="จำนวนเงิน" min="1" step="0.01" value="{{ $orderdetail->deposit }}"
                            required readonly>
                    </div>
                    {{-- <div class="col-md-4">
                        <label for="update_color" class="form-label">สีของชุด</label>
                        <input type="text" class="form-control" id="update_color" name="update_color" value="{{$orderdetail->color}}" readonly>
                        
                    </div> --}}
                    
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="update_pickup_date" class="form-label">วันที่นัดรับเครื่องประดับ</label>
                        <input type="date" class="form-control" id="update_pickup_date" name="update_pickup_date"
                            value="{{ $orderdetail->pickup_date }}" >
                    </div>

                    <div class="col-md-4">
                        <label for="" class="form-label">วันที่นัดคืนเครื่องประดับ</label>
                        <input type="date" class="form-control" id="update_return_date" name="update_return_date" value="{{$orderdetail->return_date}}">
                    </div>

                    <div class="col-md-4">
                        <label for="amount" class="form-label">ค่าบริการขยายเวลาเช่าเครื่องประดับ :</label>
                        <input type="number" class="form-control" id="update_late_charge" name="update_late_charge" value="{{$orderdetail->late_charge}}" required
                            readonly>
                        **หมายเหตุ วันที่นัดรับเครื่องประดับ - วันที่นัดคืนเครื่องประดับ ทางร้านอนุญาตให้เช่าชุดสูงสุด 3 วัน
                        หากเกินกำหนดจะคิดค่าบริการขยายเวลาเช่าเครื่องประดับวันละ 20% ของราคาค่าเช่า
                    </div>


                </div>



                

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="update_color" class="form-label">ประกันค่าเสียหาย</label>
                        <input type="number" class="form-control" id="update_damage_insurance" name="update_damage_insurance"
                            placeholder="จำนวนเงิน" min="1" value="{{$orderdetail->damage_insurance}}">
                    </div>
                    

                    <div class="col-md-4">
                        <label class="form-label">การจ่ายเงิน</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="update_status_payment"
                                id="status_payment1" value="1"
                                {{ $orderdetail->status_payment == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_payment1">
                                จ่ายมัดจำ
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="update_status_payment"
                                id="status_payment2" value="2"
                                {{ $orderdetail->status_payment == '2' ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_payment2">
                                จ่ายเต็มจำนวน
                            </label>
                        </div>
                        {{-- **หมายเหตุ -ลูกค้าจะต้องจ่ายมัดจำหรือจ่ายเต็มจำนวนเท่านั้นพนักงานจึงจะสามารถบันทึกรายการให้ได้ --}}
                    </div>
                    <div class="col-md-4">
                        <label for="note" class="form-label">รายละเอียดอื่นๆ</label>
                        <textarea class="form-control" id="update_note" name="update_note" rows="4"
                            placeholder="ใส่รายละเอียดเพิ่มเติมที่เกี่ยวข้อง">{{ $orderdetail->note }}</textarea>
                    </div>

                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                            <label for="">รูปภาพเครื่องประดับ</label>
                            <p>
                                @foreach ($imagejewelry as $imagejewelry)
                                <img src="{{ asset('storage/' . $imagejewelry->jewelry_image) }}" alt=""
                                width="110px ; ">
                                @endforeach
                            </p>
                    </div>
                </div>
            </div>


            <!-- กล่องที่รูป: ฟอร์มรูปภาพ -->
            <div class="shadow p-4 mb-5 bg-white rounded">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-4">รูปภาพประกอบ / รูปภาพตัวแบบ / อื่นๆที่เกี่ยวข้อง</h4>
                    <button type="button" class="btn btn-primary" id="addimageitem">+ เพิ่มรูปภาพ</button>
                </div>

                <div id="aria_show_input_of_image">

                    {{-- @foreach ($imagerent as $showimage)
                        <div class="row mb-3">
                            <label for="image" class="col-sm-2 col-form-label">อัปโหลดรูปภาพ</label>
                            <div class="col-sm-7">
                                <input type="file" class="form-control" name="image"
                                    value="{{ $showimage->image }}">
                            </div>
                            <div class="col-sm-2">
                                <button class="form-control btn btn-danger" type="button">ลบ</button>
                            </div>
                        </div>
                    @endforeach --}}

                    {{-- ตัวแบบ --}}
                    {{-- <div class="row mb-3">
                <label for="add_image" class="col-sm-2 col-form-label">อัปโหลดรูปภาพ</label>
                <div class="col-sm-7">
                    <input type="file" class="form-control" id="add_image" name="add_image">
                </div>

                <div class="col-sm-2">
                    <button class="form-control btn btn-danger" type="button" onclick="removeimage()">ลบ</button>
                </div>

            </div> --}}
                </div>

                <script>
                    var aria_image = document.getElementById('aria_show_input_of_image');
                    var button_add_image = document.getElementById('addimageitem');
                    var count_image = 0;

                    button_add_image.addEventListener('click', function() {
                        count_image++;

                        var divmotherimage = document.createElement('div');
                        divmotherimage.className = 'row mb-3';
                        divmotherimage.id = 'divmotherimage' + count_image;

                        var label = document.createElement('label');
                        label.htmlFor = 'add_image' + count_image;
                        label.className = 'col-sm-2 col-form-label';
                        label.innerHTML = 'อัปโหลดรูปภาพ';


                        var divone = document.createElement('div');
                        divone.className = 'col-sm-7';

                        var input = document.createElement('input');
                        input.type = 'file';
                        input.className = 'form-control';
                        input.id = 'add_image' + count_image;
                        input.name = 'add_image_[' + count_image + ']';
                        input.required = true;

                        divone.appendChild(input);


                        var divtwo = document.createElement('div');
                        divtwo.className = 'col-sm-2';

                        var button = document.createElement('button');
                        button.className = 'form-control btn btn-danger';
                        button.type = 'button';
                        button.innerHTML = 'ลบ'
                        divtwo.appendChild(button);

                        divmotherimage.appendChild(label);
                        divmotherimage.appendChild(divone);
                        divmotherimage.appendChild(divtwo);
                        aria_image.appendChild(divmotherimage);

                        button.addEventListener('click', function() {
                            divmotherimage.remove();
                        });
                    });
                </script>



            </div>
            <!-- ปุ่มยืนยัน -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-block">ยืนยัน</button>
            </div>
        </div>
    </form>
@endsection
