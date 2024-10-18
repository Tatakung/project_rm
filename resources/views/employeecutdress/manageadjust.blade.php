@extends('layouts.adminlayout')
@section('content')
    <form action="{{ route('employee.savecutadjust', ['id' => $orderdetail->id]) }}" method="POST">
        @csrf

        <div class="container mt-4">


            @php
                $round = App\Models\AdjustmentRound::where('order_detail_id', $orderdetail->id)->max('round_number');
                $round = $round + 1;
            @endphp

            <h2 class="mb-4" style="text-align: center;">รายละเอียดการปรับแก้ตัดชุด ครั้งที่ {{ $round }} </h2>

            <div class="card mb-4">
                <div class="card-header">
                    ข้อมูลการวัดตัวของลูกค้า (นิ้ว)
                </div>
                <div class="card-body">
                    {{-- <div class="row">
                        @foreach ($dress_adjusts as $item)
                            <div class="col-md-3 mb-3">
                                <label for="chest">{{ $item->name }}:</label>
                                <input type="hidden" name="adjust_id_[]" value="{{ $item->id }}">
                                <input type="hidden" name="adjust_name_[]" value="{{ $item->name }}">
                                <input type="hidden" name="old_[]" value="{{ $item->new_size }}">
                                <input type="number" class="form-control" name="new_[]" value="{{ $item->new_size }}"
                                    step="0.01" min="0" required>
                            </div>
                        @endforeach
                    </div> --}}
                    <div class="row">
                        @foreach ($dress_adjusts as $item)
                            <div class="col-md-6 mb-4"> <!-- ปรับจาก div ที่ไม่จำเป็นเป็น col-md-12 -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="chest">{{ $item->name }}:</label>
                                        <input type="hidden" name="adjust_id_[]" value="{{ $item->id }}">
                                        <input type="hidden" name="adjust_name_[]" value="{{ $item->name }}">
                                        <input type="hidden" name="old_[]" value="{{ $item->new_size }}">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control" placeholder="ค่าการวัด" required
                                            step="0.01" min="0" name="new_[]" id="input_new{{ $item->id }}"
                                            value="{{ $item->new_size }}">
                                    </div>
                                    <div class="col-md-6">
                                        <span id="show_warn{{ $item->id }}"></span>
                                    </div>
                                </div>
                            </div>
                            <script>
                                var local = '{{$item->new_size}}';
                                var convert_local = parseFloat(local);
                                var input_new = document.getElementById('input_new{{$item->id}}');
                                var show_warn = document.getElementById('show_warn{{ $item->id }}');
                                input_new.addEventListener('input', function() {
                                    var convert_input_new = parseFloat(this.value);
                                    console.log('ค้าใหม่คือ' + convert_input_new) ; 
                                    console.log('ค่าเก่าคือ' + convert_local) ; 
                                    if (convert_input_new != convert_local) {
                                        show_warn.innerHTML = 'ปรับแก้จาก ' + convert_local + ' เป็น ' + convert_input_new + ' นิ้ว';
                                    }
                                });
                            </script>
                        @endforeach
                    </div>

                    
                </div>
            </div>

            {{-- <div class="card mb-4">
            <div class="card-header">
                รายละเอียดการแก้ไข/หรือเพิ่มเติม
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="edit_details">รายละเอียดที่ต้องแก้ไข:</label>
                    <textarea class="form-control" id="edit_details" name="edit_details" rows="4" required></textarea>
                </div>
            </div>
        </div> --}}

            <div class="card mb-4">
                <div class="card-header">
                    รายการเพิ่มเติมพิเศษ
                </div>
                <div class="card-body">
                    <div id="special-items">
                        <button type="button" class="btn btn-secondary mb-2" id="add_decoration">+เพิ่มรายการพิเศษ</button>


                        <div id="aria_show_dec">
                            {{-- พื้นที่แสดงผล --}}
                        </div>


                        <script>
                            var aria_show_dec = document.getElementById('aria_show_dec');
                            var add_decoration = document.getElementById('add_decoration');
                            var count_decoration = 0;
                            add_decoration.addEventListener('click', function() {
                                count_decoration++;
                                var div = document.createElement('div');
                                div.className = 'row mb-4 mt-2';
                                div.id = 'decoration' + count_decoration;


                                input =

                                    '<div class="col-md-6">' +
                                    '<input type="text" class="form-control" name="dec_des_[' + count_decoration +
                                    ']" placeholder="รายละเอียด" required>' +
                                    '</div>' +
                                    '<div class="col-md-4">' +
                                    '<input type="number" class="form-control" name="dec_price_[' + count_decoration +
                                    ']" placeholder="ราคา (บาท)" required min="0">' +
                                    '</div>' +
                                    '<div class="col-md-2">' +
                                    '<button  class="btn btn-danger" onclick="delete_dec(' + count_decoration + ')">ลบ</button>' +
                                    '</div>';

                                div.innerHTML = input;
                                aria_show_dec.appendChild(div);

                            });

                            function delete_dec(count_decoration) {
                                var delete_decoration = document.getElementById('decoration' + count_decoration);
                                delete_decoration.remove();
                            }
                        </script>
                    </div>

                </div>
            </div>





            @php
                $today = \Carbon\Carbon::today()->toDateString();
            @endphp


            <div class="form-group">
                <div class="col-md-6">
                    <label for="new_date">วันที่นัดส่งมอบใหม่:</label>
                    <input type="date" class="form-control" name="new_date" required value="{{ $date->pickup_date }}"
                        min="{{ $today }}">

                </div>
            </div>

            <button type="submit" class="btn btn-primary mb-3">บันทึกการแก้ไข</button>
            <a href="{{ route('employee.ordertotaldetailshow', ['id' => $orderdetail->id]) }}"
                class="btn btn-secondary mb-3">ยกเลิก</a>

        </div>
    </form>
@endsection
