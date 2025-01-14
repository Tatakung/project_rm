@extends('layouts.adminlayout')
@section('content')
    <form action="{{ route('rentcutmakingdresssave', ['id' => $orderdetail->id]) }}" method="POST">
        @csrf
        <input type="hidden" name="fitting_id" value="{{ $fitting->id }}">
        <div class="container mt-4">


            @php
                $round = App\Models\AdjustmentRound::where('order_detail_id', $orderdetail->id)->max('round_number');
                $round = $round + 1;
            @endphp

            <h2 class="mb-4" style="text-align: start;">รายละเอียดการนัดลองชุดวันที่
                {{ \Carbon\Carbon::parse($fitting->fitting_date)->locale('th')->isoFormat('D MMM') }}
                {{ \Carbon\Carbon::parse($fitting->fitting_date)->year + 543 }}
            </h2>

            ของคุณ {{ $customer->customer_fname }} {{ $customer->customer_lname }}


            <div class="card mb-4 shadow">

                <div class="modal-header">
                    <h5 class="modal-title">ข้อมูลการวัดตัวของลูกค้า (นิ้ว)</h5>
                </div>
                <div class="card-body">

                    <div class="row">
                        @foreach ($dress_adjusts as $item)
                            <div class="col-md-6 mb-4">
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
                                        <span style="color: red ; " id="show_warn{{ $item->id }}"></span>
                                    </div>
                                </div>
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    var local = '{{ $item->new_size }}';
                                    var convert_local = parseFloat(local);

                                    var input_new = document.getElementById('input_new{{ $item->id }}');

                                    var show_warn = document.getElementById('show_warn{{ $item->id }}');
                                    var aria_show_modal = document.getElementById('aria_show_modal');
                                    input_new.addEventListener('input', function() {
                                        var this_now = parseFloat(this.value);
                                        if (this_now != convert_local) {
                                            show_warn.innerHTML = 'ปรับจาก ' + convert_local + ' เป็น ' + this_now + ' นิ้ว';
                                        } else {
                                            show_warn.innerHTML = '';
                                        }
                                    });

                                });
                            </script>
                        @endforeach
                    </div>

                </div>
            </div>


            @php
                $today = \Carbon\Carbon::today()->toDateString();
            @endphp

            <div class="card mb-4 shadow">
                <div class="card-header">
                    <h5 class="modal-title">รายละเอียดการนัดลองชุด</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="col-md-6">
                            <textarea class="form-control" rows="5" required name="note_fitting" placeholder="กรุณากรอกรายละเอียดสำหรับลองชุด"></textarea>
                        </div>
                    </div>
                    

                </div>


                <div class="modal fade" id="show_modal_edit" role="dialog" aria-hidden="true" data-backdrop="static">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                หัว
                            </div>
                            <div class="modal-body">
                                <div id="aria_show_modal">
                                    <p><strong>ส่วนที่ปรับแก้</strong></p>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                                <button type="submit" class="btn btn-success">ยืนยัน</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4 shadow">

                <div class="modal-header">
                    <h5 class="modal-title">รายการเพิ่มเติมพิเศษ</h5>
                </div>
                <div class="card-body">
                    <div id="special-items">
                        <button type="button" class="btn btn-primary mb-2" id="add_decoration">+ เพิ่มรายการพิเศษ</button>


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
            <button type="submit" class="btn mb-3" style="background-color:#ACE6B7;"
                        id="button_save">บันทึกการแก้ไข</button>
                    <a href="{{ route('detaildoingrentcut', ['id' => $orderdetail->id]) }}" class="btn  mb-3"
                        style="background-color:#DADAE3;">ยกเลิก</a>
        </div>
        

    </form>
@endsection
