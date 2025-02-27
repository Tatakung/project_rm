@extends('layouts.adminlayout')
@section('content')
    <style>
        .modal-body {
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-header {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .custom-modal-body {
            background-color: #28a745;
            /* สีเขียวเข้ม */
            color: #fff;
            /* ข้อความสีขาว */
            padding: 20px;
            /* ระยะห่างภายใน */
            border-radius: 5px;
            /* ขอบโค้งมน */
            text-align: center;
            /* จัดข้อความให้อยู่ตรงกลาง */
        }
    </style>


    <ol class="breadcrumb" style="background-color: transparent;">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dresstotal') }}" style="color: black ; ">จัดการชุด</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.typedress', ['id' => $datadress->type_dress_id]) }}"
                style="color: black ;">ประเภท{{ $name_type }}</a>
        </li>
        <li class="breadcrumb-item active">
            รายละเอียดของหมายเลขชุด {{ $datadress->dress_code_new }}{{ $datadress->dress_code }}
        </li>
    </ol>



    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="py-4" style="text-align: start">รายละเอียดของ{{ $name_type }}หมายเลขชุด
                    {{ $datadress->dress_code_new }}{{ $datadress->dress_code }}</h2>
            </div>

        </div>

        <!-- Modal แสดงประวัติการแก้ไขราคา -->
        <div class="modal fade" id="priceHistoryModal" tabindex="-1" aria-labelledby="priceHistoryModalLabel"
            aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="priceHistoryModalLabel">ประวัติการปรับแก้ไขราคาเช่า -
                            {{ $name_type }} {{ $datadress->dress_code_new }}{{ $datadress->dress_code }}</h5>
                    </div>
                    
                    <div class="modal-body">
                        @if($historydress->count() > 0 )
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>วันที่แก้ไข</th>
                                        <th>ราคาเดิม</th>
                                        <th>ราคาใหม่</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($historydress as $item)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($item->created_at)->locale('th')->isoFormat('D MMM') }}
                                                {{ \Carbon\Carbon::parse($item->created_at)->year + 543 }}
                                            </td>
                                            <td>{{number_format($item->old_price , 2 )}} บาท</td>
                                            <td>{{number_format($item->new_price , 2 )}} บาท</td>
                                            
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p style="text-align: center ; ">ไม่มีรายการประวัติการปรับแก้ไขราคาเช่า</p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                    </div>
                </div>
            </div>
        </div>


















        <div class="card mb-4 shadow">
            <div class="card-header"><i class="bi bi-info-circle"></i> รายละเอียดชุด
                <button class="btn btn-link p-0 ml-2 float-right" data-toggle="modal" data-target="#edittotal"
                    @if ($check_admin == 1) style="display: block ; "
                    @elseif($check_admin == 0)
                        style="display: none ; " @endif>
                    <i class="bi bi-pencil-square text-dark"></i>
                </button>
            </div>

            <div class="modal fade" id="edittotal" role="dialog" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header text-dark"style="background-color: #BACEE6;">
                            <h5 class="modal-title">แก้ไขข้อมูลชุด</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <!-- ข้อมูลชุด -->

                                <form action="{{ route('admin.updatedressnoyes', ['id' => $datadress->id]) }}"
                                    method="POST">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_dress_price" style="font-weight:bold">ราคาเช่า</label>
                                            <input type="number" class="form-control" name="update_dress_price"
                                                id="update_dress_price" value="{{ $datadress->dress_price }}"
                                                placeholder="กรุณากรอกราคา" required min="1" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_dress_deposit"style="font-weight:bold">ราคามัดจำ</label>
                                            <input type="number" class="form-control" name="update_dress_deposit"
                                                id="update_dress_deposit" value="{{ $datadress->dress_deposit }}"
                                                placeholder="กรุณากรอกราคามัดจำ" required min="1" readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label
                                                for="update_dress_deposit"style="font-weight:bold">ราคาประกันค่าเสียหาย</label>
                                            <input type="number" class="form-control" name="update_damage_insurance"
                                                id="update_damage_insurance" value="{{ $datadress->damage_insurance }}"
                                                placeholder="กรุณากรอกราคาประกันค่าเสียหาย" min="0" required
                                                readonly>
                                        </div>
                                    </div>

                                    <script>
                                        var update_dress_price = document.getElementById('update_dress_price');
                                        var update_dress_deposit = document.getElementById('update_dress_deposit');
                                        var update_damage_insurance = document.getElementById('update_damage_insurance');


                                        update_dress_price.addEventListener('input', function() {

                                            var float_price = parseFloat(update_dress_price.value);
                                            var float_deposit = parseFloat(update_dress_deposit.value);
                                            var damage_insurance = parseFloat(update_damage_insurance.value);

                                            console.log(damage_insurance);

                                            update_dress_deposit.value = Math.ceil(float_price * 0.3);
                                            update_damage_insurance.value = Math.ceil(float_price);
                                        });
                                    </script>





                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <label for="dress_description"style="font-weight:bold">คำอธิบายชุด</label>
                                            <textarea name="update_dress_description" id="update_dress_description" class="form-control" rows="3"
                                                placeholder="กรุณากรอกคำอธิบาย">{{ $datadress->dress_description }}</textarea>
                                        </div>
                                    </div>



                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn" data-dismiss="modal"
                                style="background-color:#DADAE3;">ยกเลิก</button>
                            <button type="submit" class="btn" style="background-color:#ACE6B7;">บันทึก</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>


            <div class="card-body">
                <div class="row">
                    <div class="d-flex">
                        @foreach ($imagedata as $image)
                            <div class="p-2">
                                <img src="{{ asset('storage/' . $image->dress_image) }}" alt=""
                                    style="max-height: 350px; width: auto;">
                            </div>
                        @endforeach
                    </div>
                    <div class="col-md-4">
                        <p><strong>ประเภทชุด:</strong> {{ $name_type }}</p>
                        <!-- <p><strong>รหัสชุด:</strong> {{ $datadress->dress_code_new }}{{ $datadress->dress_code }}</p> -->

                        @if ($datadress->dress_price == 0)
                            <p><strong>ราคาเช่า:</strong><span class="text-danger"> ยังไม่ได้กำหนด</span></p>
                        @else
                            <p><strong>ราคาเช่า:</strong> {{ number_format($datadress->dress_price, 2) }} บาท</p>
                        @endif


                        @if ($datadress->dress_deposit == 0)
                            <p><strong>เงินมัดจำ:</strong><span class="text-danger"> ยังไม่ได้กำหนด</span></p>
                        @else
                            <p><strong>เงินมัดจำ:</strong> {{ number_format($datadress->dress_deposit, 2) }} บาท</p>
                        @endif

                        @if ($datadress->damage_insurance == 0)
                            <p><strong>ค่าประกันชุด:</strong><span class="text-danger"> ยังไม่ได้กำหนด</span></p>
                        @else
                            <p><strong>ค่าประกันชุด:</strong> {{ number_format($datadress->damage_insurance, 2) }} บาท</p>
                        @endif

                        @if ($datadress->source_type == 2)
                            <span style="color: rgb(132, 126, 126)">*ชุดนี้มาจากรายการเช่าตัด* </span>
                        @endif







                        </p>


                        {{-- สำหรับแสดงประวัติการซ่อม --}}
                        @php
                            $reservation = App\Models\Reservation::where('dress_id', $datadress->id)->get();
                            $list_one = [];
                            $list_two = [];
                            foreach ($reservation as $re) {
                                $list_one[] = $re->id;
                            }
                            foreach ($list_one as $reservation_id) {
                                $repair = App\Models\Repair::where('reservation_id', $reservation_id)
                                    ->where('repair_status', 'ซ่อมเสร็จแล้ว')
                                    ->get();
                                if ($repair->isNotEmpty()) {
                                    foreach ($repair as $index) {
                                        $list_two[] = $index->id;
                                    }
                                }
                            }

                            $historyrepair = App\Models\Repair::whereIn('id', $list_two)->get();
                        @endphp
                        <!-- <p><strong>จำนวนครั้งที่ซ่อม</strong>
                                                    {{ $historyrepair->count() }} ครั้ง
                                                    {{-- <span
                                @if ($check_admin == 1) style="display: block ; "
                            @elseif($check_admin == 2)
                            style="display: none ; " @endif><a
                                    href="">ดูประวัติ</a></span> --}}
                                                </p>
                                                <p><strong>คำอธิบายชุด: </strong>{{ $datadress->dress_description }}</p> -->

                    </div>


                    <div class="col-md-5">
                        <p>
                            <strong>ขนาดของชุด</strong> (ปรับแก้ ขยาย/ลด ได้):


                            {{-- <button class="btn btn-link p-0 ml-2" data-toggle="modal" data-target="#add_mea">
                                <i class="bi bi-plus-square text-dark"></i>
                            </button> --}}

                            @php
                                $list_check_mea = [];
                            @endphp
                        <table class="table table-bordered-0">
                            <thead>

                            </thead>
                            </p>
                            <tbody>
                                @foreach ($mea_dress as $index => $mea_dress)
                                    <tr>
                                        <td>{{ $mea_dress->mea_dress_name }}<span
                                                style="font-size: 14px; color: rgb(197, 21, 21)"> (ปรับได้
                                                {{ $mea_dress->initial_min }}-{{ $mea_dress->initial_max }} นิ้ว)</span>
                                        </td>
                                        <td>{{ $mea_dress->current_mea }}</td>
                                        <td>นิ้ว</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="ml-4"
                        >
                        <div class="row">
                            <a href="{{ route('admin.historydressrent', ['id' => $datadress->id]) }}"
                                class="btn btn-outline-primary mr-2">
                                <i class="bi bi-clock-history"></i> ประวัติการเช่า
                            </a>
                            <a href="{{ route('admin.historydressrepair', ['id' => $datadress->id]) }}"
                                class="btn btn-outline-secondary">
                                <i class="bi bi-tools"></i> ประวัติการซ่อม
                            </a>
                            <button type="button" class="btn btn-outline-dark" data-toggle="modal"
                                data-target="#priceHistoryModal"
                                @if ($check_admin == 1)
                                style="display: block ; "
                            @elseif($check_admin == 0)
                                style="display: none ; " 
                            @endif
                                >
                                <i class="fas fa-history"></i> ประวัติการปรับแก้ไขราคาเช่า
                            </button>
    
                        </div>
                    </div>


                    








                </div>









            </div>








        </div>
    </div>


    <br>

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
    <style>
        #calendar {
            max-width: 900px;
            margin: 0 auto;
        }
    </style>

    <div id='calendar'></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: [
                    // ข้อมูลการจองจะถูกเพิ่มที่นี่
                    @foreach ($date_reservations as $reservation)
                        {
                            @php
                                $order_id = App\Models\Orderdetail::where('reservation_id', $reservation->id)->value('order_id');
                                $customer_id = App\Models\Order::where('id', $order_id)->value('customer_id');
                                $customer = App\Models\Customer::find($customer_id);
                            @endphp

                            title:
                                'คุณ {{ $customer->customer_fname }} {{ $customer->customer_lname }} - {{ $reservation->status }}',
                                start: '{{ $reservation->start_date }}',
                                end:
                                '{{ \Carbon\Carbon::parse($reservation->end_date)->addDay()->format('Y-m-d') }}',
                                color: '{{ $reservation->status == 'ถูกจอง' ? '#3788d8' : '#257e4a' }}'
                        },
                    @endforeach
                ],
                locale: 'th'
            });
            calendar.render();
        });
    </script>















    <!--modal เพิ่มรูปภาพ-->
    <div class="modal fade" id="modaladdimage" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">เพิ่มรูปภาพ</div>
                <form action="{{ route('admin.addimage', ['id' => $datadress->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="addimage">รูปภาพ:</label>
                            <input type="file" class="form-control" name="addimage" id="addimage" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-secondary">ยืนยัน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>








    {{-- ประวัติการปรับแก้ --}}
    <div class="modal fade" id="history_adjust" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">ประวัติการปรับแก้ชุด</div>
                <form action="{{ route('admin.addimage', ['id' => $datadress->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        @php
                            $list_one = [];
                            $list_two = [];
                            $meadress = App\Models\Dressmea::where('dress_id', $datadress->id)->get();
                            foreach ($meadress as $item) {
                                $list_one[] = $item->id;
                            }
                            $dress_adjust = App\Models\Dressmeaadjustment::whereIn('dressmea_id', $list_one)
                                ->where('status', 'แก้ไข')
                                ->get();

                            foreach ($dress_adjust as $item) {
                                $list_two[] = $item->id;
                            }
                            $show_his = App\Models\Dressmeaadjustment::whereIn('id', $list_two)->get();
                        @endphp
                        @if ($show_his->count() > 0)
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr style="background-color: #f2f2f2;">
                                    <th style="border: 1px solid #ddd; padding: 8px;">ส่วนที่ปรับ</th>
                                    <th style="border: 1px solid #ddd; padding: 8px;">ขนาดเดิม</th>
                                    <th style="border: 1px solid #ddd; padding: 8px;">ขนาดที่ปรับ</th>
                                </tr>

                                @foreach ($show_his as $item)
                                    <tr>
                                        @php
                                            $dress_mea = App\Models\Dressmea::find($item->dressmea_id);
                                        @endphp
                                        <td style="border: 1px solid #ddd; padding: 8px;">
                                            {{ $dress_mea->mea_dress_name }}
                                        </td>
                                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $dress_mea->initial_mea }}
                                        </td>
                                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $item->new_size }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        @else
                            <p style="text-align: center ; ">ไม่มีรายการประวัติการปรับแก้</p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                    </div>
                </form>
            </div>
        </div>
    </div>














    <!-- modalเพิ่มข้อมูลการวัด -->
    <div class="modal fade" id="add_mea" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.addmeasumentno', ['id' => $datadress->id]) }}" method="POST">
                    @csrf
                    <div class="modal-header text-dark" style="background-color:#EAD8C0 ;">
                        <h5 class="modal-title">เพิ่มข้อมูลการวัด</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="mb-0"><i class="bi bi-pencil-square text-dark"></i>ข้อมูลการวัด</h5>
                                <button class="btn btn-success" type="button" id="add_measurement">
                                    <i class="bi bi-plus"></i> เพิ่มการวัด
                                </button>
                            </div>

                            <div id="aria_show_add_mea_input">

                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        {{-- <input type="text" class="form-control" name="add_mea_now_name_[1]" placeholder="ชื่อการวัด" required> --}}
                                        <select class="form-control" name="add_mea_now_name_[1]" required>
                                            <option value="" disabled selected>เลือกรายการ</option>
                                            <option value="ยาวหน้า"
                                                @if (in_array('ยาวหน้า', $list_check_mea)) style="display: none;" @endif>ยาวหน้า
                                            </option>
                                            <option value="ยาวหลัง"
                                                @if (in_array('ยาวหลัง', $list_check_mea)) style="display: none;" @endif>ยาวหลัง
                                            </option>
                                            <option value="ไหล่กว้าง"
                                                @if (in_array('ไหล่กว้าง', $list_check_mea)) style="display: none;" @endif>ไหล่กว้าง
                                            </option>
                                            <option value="บ่าหน้า"
                                                @if (in_array('บ่าหน้า', $list_check_mea)) style="display: none;" @endif>บ่าหน้า
                                            </option>
                                            <option value="บ่าหลัง"
                                                @if (in_array('บ่าหลัง', $list_check_mea)) style="display: none;" @endif>บ่าหลัง
                                            </option>
                                            <option value="รอบคอ"
                                                @if (in_array('รอบคอ', $list_check_mea)) style="display: none;" @endif>รอบคอ
                                            </option>
                                            <option value="รักแท้"
                                                @if (in_array('รักแท้', $list_check_mea)) style="display: none;" @endif>รักแท้
                                            </option>
                                            <option value="รอบอก"
                                                @if (in_array('รอบอก', $list_check_mea)) style="display: none;" @endif>รอบอก
                                            </option>
                                            <option value="อกห่าง"
                                                @if (in_array('อกห่าง', $list_check_mea)) style="display: none;" @endif>อกห่าง
                                            </option>
                                            <option value="อกสูง"
                                                @if (in_array('อกสูง', $list_check_mea)) style="display: none;" @endif>อกสูง
                                            </option>
                                            <option value="รอบเอว"
                                                @if (in_array('รอบเอว', $list_check_mea)) style="display: none;" @endif>รอบเอว
                                            </option>
                                            <option value="รอบสะโพก"
                                                @if (in_array('รอบสะโพก', $list_check_mea)) style="display: none;" @endif>รอบสะโพก
                                            </option>
                                            <option value="กระโปรงยาว"
                                                @if (in_array('กระโปรงยาว', $list_check_mea)) style="display: none;" @endif>กระโปรงยาว
                                            </option>
                                            <option value="แขนยาว"
                                                @if (in_array('แขนยาว', $list_check_mea)) style="display: none;" @endif>แขนยาว
                                            </option>
                                            <option value="แขนกว้าง"
                                                @if (in_array('แขนกว้าง', $list_check_mea)) style="display: none;" @endif>แขนกว้าง
                                            </option>
                                            <option value="เสื้อยาว"
                                                @if (in_array('เสื้อยาว', $list_check_mea)) style="display: none;" @endif>เสื้อยาว
                                            </option>
                                            <option value="ต้นขา"
                                                @if (in_array('ต้นขา', $list_check_mea)) style="display: none;" @endif>ต้นขา
                                            </option>
                                            <option value="ปลายขา"
                                                @if (in_array('ปลายขา', $list_check_mea)) style="display: none;" @endif>ปลายขา
                                            </option>
                                            <option value="เป้า"
                                                @if (in_array('เป้า', $list_check_mea)) style="display: none;" @endif>เป้า
                                            </option>
                                            <option value="กางเกงยาว"
                                                @if (in_array('กางเกงยาว', $list_check_mea)) style="display: none;" @endif>กางเกงยาว
                                            </option>
                                        </select>

                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control" name="add_mea_now_number_[1]"
                                            placeholder="หมายเลขการวัด" min="1" max="100" step="0.01"
                                            required>
                                    </div>
                                    <div class="col-md-3">
                                        <p>นิ้ว</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-success">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var add_measurement = document.getElementById('add_measurement');
        var aria_show_add_mea_input = document.getElementById('aria_show_add_mea_input');
        var count_add_mea = 1;
        add_measurement.addEventListener('click', function() {
            count_add_mea++;

            var div = document.createElement('div');
            div.className = 'row mb-3';
            div.id = 'row_add_measurement' + count_add_mea;


            input =


                '<div class="col-md-3">' +
                '<select class="form-control" name="add_mea_now_name_[' + count_add_mea + ']" required>' +
                '<option value="" disabled selected>เลือกรายการ</option>' +
                '<option value="ยาวหน้า" @if (in_array('ยาวหน้า', $list_check_mea)) style="display: none;" @endif>ยาวหน้า</option>' +
                '<option value="ยาวหลัง" @if (in_array('ยาวหลัง', $list_check_mea)) style="display: none;" @endif>ยาวหลัง</option>' +
                '<option value="ไหล่กว้าง" @if (in_array('ไหล่กว้าง', $list_check_mea)) style="display: none;" @endif>ไหล่กว้าง</option>' +
                '<option value="บ่าหน้า" @if (in_array('บ่าหน้า', $list_check_mea)) style="display: none;" @endif>บ่าหน้า</option>' +
                '<option value="บ่าหลัง" @if (in_array('บ่าหลัง', $list_check_mea)) style="display: none;" @endif>บ่าหลัง</option>' +
                '<option value="รอบคอ" @if (in_array('รอบคอ', $list_check_mea)) style="display: none;" @endif>รอบคอ</option>' +
                '<option value="รักแท้" @if (in_array('รักแท้', $list_check_mea)) style="display: none;" @endif>รักแท้</option>' +
                '<option value="รอบอก" @if (in_array('รอบอก', $list_check_mea)) style="display: none;" @endif>รอบอก</option>' +
                '<option value="อกห่าง" @if (in_array('อกห่าง', $list_check_mea)) style="display: none;" @endif>อกห่าง</option>' +
                '<option value="อกสูง" @if (in_array('อกสูง', $list_check_mea)) style="display: none;" @endif>อกสูง</option>' +
                '<option value="รอบเอว" @if (in_array('รอบเอว', $list_check_mea)) style="display: none;" @endif>รอบเอว</option>' +
                '<option value="รอบสะโพก" @if (in_array('รอบสะโพก', $list_check_mea)) style="display: none;" @endif>รอบสะโพก</option>' +
                '<option value="กระโปรงยาว" @if (in_array('กระโปรงยาว', $list_check_mea)) style="display: none;" @endif>กระโปรงยาว</option>' +
                '<option value="แขนยาว" @if (in_array('แขนยาว', $list_check_mea)) style="display: none;" @endif>แขนยาว</option>' +
                '<option value="แขนกว้าง" @if (in_array('แขนกว้าง', $list_check_mea)) style="display: none;" @endif>แขนกว้าง</option>' +
                '<option value="เสื้อยาว" @if (in_array('เสื้อยาว', $list_check_mea)) style="display: none;" @endif>เสื้อยาว</option>' +
                '<option value="ต้นขา" @if (in_array('ต้นขา', $list_check_mea)) style="display: none;" @endif>ต้นขา</option>' +
                '<option value="ปลายขา" @if (in_array('ปลายขา', $list_check_mea)) style="display: none;" @endif>ปลายขา</option>' +
                '<option value="เป้า" @if (in_array('เป้า', $list_check_mea)) style="display: none;" @endif>เป้า</option>' +
                '<option value="กางเกงยาว" @if (in_array('กางเกงยาว', $list_check_mea)) style="display: none;" @endif>กางเกงยาว</option>' +
                '</select>' +
                '</div>' +
                '<div class="col-md-3">' +
                '<input type="number" class="form-control" name="add_mea_now_number_[' + count_add_mea +
                ']" placeholder="หมายเลขการวัด" min="1" max="100" step="0.01" required >' +
                '</div>' +
                '<div class="col-md-3">' +
                '<p>นิ้ว</p>' +
                '</div>' +
                '<div class="input-group-append">' +
                '<button class="btn btn-danger remove-measurement" onclick="remove_add_mea_now(' + count_add_mea +
                ')" type="button"><i class="bi bi-trash"></i>ลบ</button>' +
                '</div>';
            div.innerHTML = input;
            aria_show_add_mea_input.appendChild(div);
        });

        function remove_add_mea_now(count_add_mea) {
            var delete_add_mea_now = document.getElementById('row_add_measurement' + count_add_mea);
            delete_add_mea_now.remove();
        }
    </script>








    <!-- Modals for success and failure messages -->
    <div class="modal fade" id="showsuccessss" role="dialog" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog">
            <div class="modal-content custom-modal-content">
                <div class="modal-body custom-modal-body">
                    {{ session('success') }}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="showfail" role="dialog" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog">
            <div class="modal-content custom-modal-content fail">
                <div class="modal-body">{{ session('fail') }}</div>
            </div>
        </div>
    </div>

    <script>
        @if (session('success'))
            setTimeout(function() {
                $('#showsuccessss').modal('show');
                setTimeout(function() {
                    $('#showsuccessss').modal('hide');
                }, 6000);
            }, 500);
        @endif
        @if (session('fail'))
            setTimeout(function() {
                $('#showfail').modal('show');
                setTimeout(function() {
                    $('#showfail').modal('hide');
                }, 6000);
            }, 500);
        @endif
    </script>
@endsection
