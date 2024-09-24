@extends('layouts.adminlayout')

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="">หน้าแรก</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employee.ordertotal') }}">รายการออเดอร์ทั้งหมด</a></li>
        <li class="breadcrumb-item"><a
                href="{{ route('employee.ordertotaldetail', ['id' => $orderdetail->order_id]) }}">รายละเอียดออเดอร์ที่
                {{ $orderdetail->order_id }}</a></li>
        <li class="breadcrumb-item active">{{ $orderdetail->title_name }}</li>
    </ol>


    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 90%;
            margin-top: 30px;
        }

        .card {
            border: none;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #A7567F;
            color: white;
        }

        .status-timeline {
            position: relative;
            padding: 20px 0;
        }

        .status-timeline::before {
            content: '';
            position: absolute;
            height: 100%;
            width: 2px;
            background-color: #dee2e6;
            left: 15px;
            top: 0;
        }

        .status-step {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .status-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #fff;
            border: 2px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
            margin-right: 15px;
        }


        .status-text {
            flex-grow: 1;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background-color: #f8f9fa;
        }

        .info-box {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
    </style>

    {{-- ชุดแยกได้กับชุดอยกไม่ได้  --}}
    @php
        $dress_separable = App\Models\Dress::where('id', $orderdetail->dress_id)->value('separable');
    @endphp


    <div class="container">
        <div class="card">
            <div class="card-header text-dark" style="background-color:#EAD8C0 ;">
                <h4 class="mb-0">รายการเช่า : {{ $orderdetail->title_name }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        {{-- <div class="info-box">
                            <img src="{{ asset('storage/' .$dressimage->dress_image) }}" alt="" width="80px;" height="auto">
                       
                        </div> --}}

                        <div class="info-box">
                            <img src="{{ asset('storage/' . $dressimage->dress_image) }}" alt="" width="120px;"
                                height="auto">
                            <hr>
                            {{-- <div class="current-status">
                                <h2>สถานะปัจจุบันของชุด</h2>
                                <p class="status">{{ $status_if_dress->status }}</p>


                            

                                <p>อัพเดตล่าสุด: 18/09/2024 15:30</p>
                            </div> --}}
                            <hr>

                            <h5 class="mt-4">ข้อมูลการเช่า</h5>
                            <p><strong>วันที่นัดรับ:</strong>
                                {{ \Carbon\Carbon::parse($orderdetail->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                {{ \Carbon\Carbon::parse($orderdetail->pickup_date)->year + 543 }}
                                <span class="text-danger" id="showday"></span>
                            </p>
                            <script>
                                var pickup_date = new Date("{{ $orderdetail->pickup_date }}");
                                var now = new Date(); //วันที่ปัจจุบัน
                                var day = pickup_date - now;
                                var totalday = Math.ceil(day / (1000 * 60 * 60 * 24));
                                document.getElementById('showday').innerHTML = '(ลูกค้ามารับชุดใน ' + totalday + ' วัน)';
                            </script>

                            <p><strong>วันที่นัดคืน:</strong>
                                {{ \Carbon\Carbon::parse($orderdetail->return_date)->locale('th')->isoFormat('D MMM') }}
                                {{ \Carbon\Carbon::parse($orderdetail->return_date)->year + 543 }}
                                <span class="text-danger" id="showdayreturn"></span>
                            </p>
                            <script>
                                var pickup_date = new Date("{{ $orderdetail->return_date }}");
                                var now = new Date(); //วันที่ปัจจุบัน
                                var day = pickup_date - now;
                                var totalday = Math.ceil(day / (1000 * 60 * 60 * 24));
                                document.getElementById('showdayreturn').innerHTML = 'อีก ' + totalday + ' วัน';
                            </script>



                            <p><strong>ราคาเช่า:</strong> {{ number_format($orderdetail->price, 2) }} บาท</p>
                            <p><strong>ราคามัดจำเช่า:</strong> {{ number_format($orderdetail->deposit, 2) }} บาท
                            </p>
                            <p><strong>ประกันค่าเสียหาย:</strong> {{ number_format($orderdetail->damage_insurance, 2) }}
                                บาท</p>
                            <p><strong>จำนวน:</strong> {{ $orderdetail->amount }} ชุด</p>


                            <p><strong>สถานะจ่ายเงิน:</strong> <span class="badge bg-success">
                                    @if ($orderdetail->status_payment == 1)
                                        ชำระเงินมัดจำแล้ว
                                    @elseif($orderdetail->status_payment == 2)
                                        ชำระเงินเต็มจำนวนแล้ว
                                    @endif
                                </span></p>
                            <p><strong>โน๊ต:</strong>{{ $orderdetail->note }}
                                <button type="button" class="btn" data-toggle="modal" data-target="#editnote"
                                    @if ($orderdetail->status_detail == 'รับชุดแล้ว') style="display: none;" @endif>
                                    <img src="{{ asset('images/edit.png') }}" alt="" width="22px;" height="auto">
                                </button>
                            </p>

                        </div>
                        {{-- เอาไว้นับเงื่อนไขของปุ่มว่ามีชุดที่ปรับแก้ไขไหมนะ  --}}
                        @php
                            $check_button_updatestatusadjust = false;
                            foreach ($dress_mea_adjust_button as $dress_mea_adjust_button) {
                                $dress_mea = App\Models\Dressmea::where(
                                    'id',
                                    $dress_mea_adjust_button->dressmea_id,
                                )->first();
                                if ($dress_mea_adjust_button->new_size != $dress_mea->current_mea) {
                                    $check_button_updatestatusadjust = true; //แปลว่าต้องแก้
                                }
                            }

                        @endphp


                    </div>
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 style="margin-top: 10px;">สถานะออเดอร์</h5>
                            </div>
                            <div class="col-md-6 text-right"
                                @if ($orderdetail->status_detail == 'ถูกจอง' && $check_button_updatestatusadjust == false) style="display: block ; "
                                @else
                                style="display: none ; " @endif>
                                <button class="btn" style="background: #FF8343; color: #ffffff;" data-toggle="modal"
                                    data-target="#updatestatus">อัพเดตสถานะ</button>
                            </div>


                            <div class="col-md-6 text-right"
                                @if ($orderdetail->status_detail == 'กำลังเช่า') style="display: block ; "@else style="display: none ; " @endif>
                                <button class="btn" style="background: #FF8343; color: #ffffff;" data-toggle="modal"
                                    data-target="#updatestatus_return">อัพเดตสถานะ</button>
                            </div>

                        </div>

                        <!-- Modal อัพเดตสถานะ -->
                        <div class="modal fade" id="updatestatus" tabindex="-1" role="dialog" aria-hidden="true"
                            data-backdrop="static">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <form
                                    action="{{ route('employee.actionupdatestatusrentdress', ['id' => $orderdetail->id]) }}"
                                    method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color: #FF8343; color: #ffffff;">
                                            <h5 class="modal-title" style="font-weight: bold;">สถานะการเช่าได้รับการอัพเดต
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>ลูกค้าได้รับชุดแล้ว สถานะของชุดนี้ได้รับการอัพเดตเป็น 'กำลังเช่า'
                                                เรียบร้อยแล้ว</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn" data-dismiss="modal"
                                                style="background-color: #f8f9fa; color: #000;">ยกเลิก</button>
                                            <button type="submit" class="btn btn-success">ยืนยัน</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>












                        <div class="modal fade" id="updatestatus_return" tabindex="-1" role="dialog" aria-hidden="true"
                            data-backdrop="static">
                            <div class="modal-dialog modal-lg" role="document">
                                <form
                                    action="{{ route('employee.actionupdatestatusrentdress', ['id' => $orderdetail->id]) }}"
                                    method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color: #FF8343; color: #ffffff;">
                                            <h5 class="modal-title" style="font-weight: bold;">อัพเดตสถานะการคืนชุด</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>ราคาประกันค่าเสียหาย:<span
                                                        id="damage_insurance">{{ $orderdetail->damage_insurance }}</span>
                                                    บาท</label>
                                            </div>
                                            <div class="form-group">
                                                <label for="total_damage_insurance">หักค่าเสียหายจริง (บาท):</label>
                                                <input type="number" class="form-control" id="total_damage_insurance"
                                                    name="total_damage_insurance" min="0"
                                                    max="{{ $orderdetail->damage_insurance }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="cause_for_insurance">เหตุผลในการหัก()หากมี:</label>
                                                <textarea class="form-control" id="cause_for_insurance" name="cause_for_insurance" rows="2"></textarea>
                                            </div>

                                            <script>
                                                var damage_insurance = document.getElementById('damage_insurance');
                                                var total_damage_insurance = document.getElementById('total_damage_insurance');
                                                var max_damage_insurance = parseFloat(damage_insurance.textContent);
                                                total_damage_insurance.addEventListener('input', function() {
                                                    if (parseFloat(total_damage_insurance.value) > max_damage_insurance) {
                                                        total_damage_insurance.value = max_damage_insurance;
                                                    }

                                                });
                                            </script>


                                            <hr>
                                            <div class="form-group">
                                                <label for="return_status">การดำเนินการหลังรับคืนชุด:</label>
                                                <select class="form-control" id="return_status" name="return_status"
                                                    required>
                                                    <option value="" selected disabled>เลือกสถานะ</option>
                                                    <option value="ส่งซัก">ส่งซัก</option>
                                                    <option value="ต้องซ่อมแซม">ต้องซ่อมแซม</option>
                                                    {{-- <option value="ไม่สามารถให้เช่าต่อได้">ไม่สามารถให้เช่าต่อได้</option> --}}
                                                </select>
                                            </div>
                                            
                                            <div class="form-group" id="repair_details_group" style="display: none;">
                                                <label for="repair_details">รายละเอียดการซ่อม:</label>
                                                <select class="form-select" id="repair_type" name="repair_type">
                                                    <option value="10" id="type_total_dress">ทั้งชุด</option>
                                                    <option value="20" id="type_shirt">เสื้อ</option>
                                                    <option value="30" id="type_skirt">ผ้าถุง</option>
                                                </select>
                                                <textarea class="form-control" id="repair_details" name="repair_details" rows="3"></textarea>
                                            </div>



                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn" data-dismiss="modal"
                                                style="background-color: #f8f9fa; color: #000;">ยกเลิก</button>
                                            <button type="submit" class="btn btn-success">ยืนยัน</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>



                        <script>
                            var select_return_status = document.getElementById('return_status');
                            var repair_details_group = document.getElementById('repair_details_group');
                            var repair_details = document.getElementById('repair_details');

                            var dress_separable = '{{ $dress_separable }}';
                            
                            var type_total_dress = document.getElementById('type_total_dress');
                            var type_shirt = document.getElementById('type_shirt');
                            var type_skirt = document.getElementById('type_skirt');

                            var dress_id = '{{ $orderdetail->dress_id }}';
                            var shirt_id = '{{ $orderdetail->shirtitems_id }}';
                            var skirt_id = '{{ $orderdetail->skirtitems_id }}';


                            select_return_status.addEventListener('change', function() {
                                if (select_return_status.value === 'ต้องซ่อมแซม') {
                                    repair_details_group.style.display = 'block';
                                    repair_details.setAttribute('required', 'required');

                                    if(shirt_id){
                                        console.log('เสื้อ') ; 
                                        type_total_dress.style.display = 'none' ; 
                                        type_skirt.style.display = 'none' ; 
                                        type_shirt.selected = true ; 
                                    }
                                    else if(skirt_id){
                                        console.log('ผ้าถุง') ; 
                                        type_total_dress.style.display = 'none' ; 
                                        type_shirt.style.display = 'none' ; 
                                        type_skirt.selected = true ; 
                                    }
                                    else{
                                        console.log('ทั้งชุด') ; 
                                        type_total_dress.style.display = 'block' ; 
                                        type_shirt.style.display = 'block' ; 
                                        type_skirt.style.display = 'block' ; 
                                        type_total_dress.selected = true ; 

                                    }
                                    


                                } else {
                                    repair_details_group.style.display = 'none';
                                    repair_details.value = '';
                                    repair_details.removeAttribute('required');

                                }

                            });
                        </script>












                        <div class="status-timeline">
                            @foreach ($orderdetailstatus as $index => $orderdetailstatus)
                                <div class="status-step">

                                    @if ($orderdetailstatus->status == 'รับชุดแล้ว')
                                        <div class="status-icon active"
                                            style="background-color: #f9e746;color: white;border-color: #f9e746;">
                                            <i class="fas fa-tshirt"></i>
                                        </div>
                                    @else
                                        <div class="status-icon active"
                                            style="background-color: #A7567F;color: white;border-color: #A7567F;">
                                            <i class="fas fa-tshirt"></i>
                                        </div>
                                    @endif

                                    <div class="status-text">
                                        <h6 class="mb-0">{{ $orderdetailstatus->status }}</h6>

                                        <small class="text-muted">
                                            ทำรายการเมื่อ{{ \Carbon\Carbon::parse($orderdetailstatus->created_at)->addHours(7)->format('d/m/Y H:i') }}
                                        </small><br>
                                        @if ($orderdetailstatus->status == 'แก้ไขชุด')
                                            @foreach ($mea_orderdetail as $item)
                                                @if ($item->status_measurement == 'รอการแก้ไข')
                                                    <p style="color: red; font-size: 15px; margin: 2px;">
                                                        ปรับแก้{{ $item->measurement_name }}
                                                        {{ $item->measurement_number_old }} ->
                                                        {{ $item->measurement_number }} นิ้ว</p>
                                                @endif
                                            @endforeach
                                        @endif

                                    </div>
                                </div>
                            @endforeach
                        </div>





                        <div class="row">
                            <div class="col-6">
                                <h5 class="mt-4" style="text-align: left;">รายการปรับแก้ขนาดชุด</h5>
                            </div>
                            <div class="col-6 justify-content-end"
                                @if ($check_button_updatestatusadjust == false) style="display: none;" @endif>
                                <button class="btn btn-success" data-toggle='modal'
                                    data-target="#updatestatusadjust">ยืนยันการปรับแก้</button>
                            </div>

                        </div>

                        <table class="table table-bordered">
                            @foreach ($dress_mea_adjust as $index => $dress_mea_adjust)
                                <tr>
                                    @php
                                        $dress_mea = App\Models\Dressmea::where(
                                            'id',
                                            $dress_mea_adjust->dressmea_id,
                                        )->first();
                                    @endphp
                                    <th>{{ $dress_mea->mea_dress_name }}
                                        <span style="font-size: 12px; color: rgb(140, 35, 35) ; ">
                                            @php
                                                $mea_local = App\models\Dressmea::find($dress_mea_adjust->dressmea_id) ; 
                                            @endphp
                                            (ปรับได้ {{$mea_local->initial_mea - 4 }} - {{$mea_local->initial_mea + 4 }})
                                        </span>
                                    </th>
                                    <td>{{ $dress_mea_adjust->new_size }}</td>

                                    @if ($dress_mea->current_mea != $dress_mea_adjust->new_size)
                                        <td style="color: #eb3131 ; font-size: 14px;">
                                            ปรับแก้:จาก{{ $dress_mea->current_mea }}<i
                                                class="bi bi-arrow-right"></i>{{ $dress_mea_adjust->new_size }}นิ้ว
                                            {{-- <span
                                                style="color: #00BCD4">(ยังไม่ได้ปรับแก้)</span> --}}
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div class="modal fade" id="editmea" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">แก้ไขชุด</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        {{-- @foreach ($mea_orderdetailforedit as $item)
                            <div class="form-group row align-items-center">
                                <div class="col-md-5">
                                    <input class="form-control" type="text" value="{{ $item->measurement_name }}">
                                </div>
                                <div class="col-md-5">
                                    <input type="number" class="form-control" value="{{ $item->measurement_number }}">
                                </div>
                                <div class="col-md-2">
                                    <p>นิ้ว</p>
                                </div>
                            </div>
                        @endforeach --}}
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-success">บันทึก</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="editnote" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #A7567F ; color: #ffffff">
                    <h5 class="modal-title" id="editModalLabel">แก้ไขโน๊ต</h5>
                </div>


                <form action="{{ route('employee.actionupdatenotecutdress', ['id' => $orderdetail->id]) }}"
                    method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="note" class="form-label">โน๊ต:</label>
                            <textarea class="form-control" id="note" name="note" rows="3">{{ $orderdetail->note }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal"
                            style="background-color: #f8f9fa; color: #000;">ยกเลิก</button>
                        <button type="submit" class="btn"
                            style="background-color: #A7567F; color: #ffffff;">บันทึกการแก้ไข</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="editdate" tabindex="-1" aria-labelledby="editdate" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #A7567F ; color: #ffffff">
                    <h5 class="modal-title" id="editModalLabel">แก้ไขโน๊ต</h5>
                </div>

                <form action="{{ route('employee.actionupdatedatecutdress', ['id' => $orderdetail->id]) }}"
                    method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <p style="font-size: 20px;">แก้ไขวันที่นัดรับ</p>
                            </div>
                            @php
                                $today = \Carbon\Carbon::today()->toDateString();
                            @endphp
                            <div class="col-md-12 d-flex justify-content-center align-items-center position-relative">
                                <input style="background: #A7567F ; color: #ffffff " class="form-control" type="date"
                                    id="datepicker" name="datepicker" min="{{ $today }}"
                                    value="{{ $orderdetail->pickup_date }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal"
                            style="background-color: #f8f9fa; color: #000;">ยกเลิก</button>
                        <button type="submit" class="btn"
                            style="background-color: #A7567F; color: #ffffff;">บันทึกการแก้ไข</button>
                    </div>
                </form>


            </div>
        </div>
    </div>



    <!-- Modal อัพเดตสถานะ -->
    <div class="modal fade" id="updatestatusadjust" tabindex="-1" role="dialog" aria-hidden="true"
        data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ route('employee.actionupdatestatusadjustdress', ['id' => $orderdetail->id]) }}"
                method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #A7567F; color: #ffffff;">
                        <h5 class="modal-title" style="font-weight: bold;">ยืนยันการแก้ไข</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5>คุณได้แก้ไขขนาดชุดให้ลูกค้าเรียบร้อยแล้วใช่หรือไม่?</h5>
                        <table class="table table-bordered">
                            @foreach ($dress_mea_adjust_modal as $index => $dress_mea_adjust_modal)
                                <input type="hidden" name="dressmea_id_[]"
                                    value="{{ $dress_mea_adjust_modal->dressmea_id }}">
                                <input type="hidden" name="new_size_[]"
                                    value="{{ $dress_mea_adjust_modal->new_size }}">
                            @endforeach
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal"
                            style="background-color: #f8f9fa; color: #000;">ยกเลิก</button>
                        <button type="submit" class="btn"
                            style="background-color: #A7567F; color: #ffffff;">ยืนยันการแก้ไข</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
