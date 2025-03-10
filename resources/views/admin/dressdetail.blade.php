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
                        @if ($historydress->count() > 0)
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
                                                <td>{{ number_format($item->old_price, 2) }} บาท</td>
                                                <td>{{ number_format($item->new_price, 2) }} บาท</td>

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
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <i class="bi bi-info-circle"></i>
                    รายละเอียดชุด
                </div>
                <div>
                    
                    <button type="button" class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#stopRentalModal"
                        @if ($check_admin == 1) @if ($datadress->dress_status == 'ยุติการให้เช่า' || $datadress->dress_status == 'สูญหาย')
                                style="display: none ; "
                            @else
                                style="display: block ; " @endif
                    @elseif($check_admin == 0) style="display: none ; " @endif>
                        <i class="fas fa-stop"></i> ยุติการให้เช่า
                    </button>

                    <button type="button" class="btn btn-sm btn-outline-success" data-toggle="modal"
                        data-target="#reopenRentalModal"
                        @if ($check_admin == 1) @if ($datadress->dress_status == 'ยุติการให้เช่า')
                            style="display: block ; "
                        @else
                            style="display: none ; " @endif
                    @elseif($check_admin == 0) style="display: none ; " @endif>
                        <i class="fas fa-stop"></i> เปิดให้เช่าอีกครั้ง
                    </button>

                    
                </div>
            </div>

            <!-- Modal ยืนยันการเปิดให้เช่าอีกครั้ง -->
            <div class="modal fade" id="reopenRentalModal" tabindex="-1" role="dialog"
                aria-labelledby="reopenRentalModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content shadow-lg border-0 rounded-3">
                        <div class="modal-header bg-success text-white d-flex align-items-center">
                            <i class="fas fa-check-circle me-2 fa-lg"></i>
                            <h5 class="modal-title" id="reopenRentalModalLabel">ยืนยันการเปิดให้เช่าอีกครั้ง</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <i class="fas fa-info-circle text-success fa-3x mb-3"></i>

                            <p class="fs-5 mt-3">
                                คุณต้องการเปิดให้เช่าชุดนี้อีกครั้งใช่หรือไม่?
                                <span class="text-success fw-bold">หลังจากเปิดให้เช่าอีกครั้ง
                                    ลูกค้าจะสามารถจองชุดนี้ได้ตามปกติ</span>
                            </p>
                        </div>
                        <div class="modal-footer d-flex justify-content-center">
                            <button type="button" class="btn btn-secondary px-4 py-2 rounded-pill" data-dismiss="modal">
                                <i class="fas fa-times"></i> ยกเลิก
                            </button>
                            <form action="{{ route('reopenRentalnodress', ['id' => $datadress->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success px-4 py-2 rounded-pill">
                                    <i class="fas fa-check"></i> ยืนยัน
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Modal ยืนยันการยุติการให้เช่า -->
            <div class="modal fade" id="stopRentalModal" tabindex="-1" role="dialog"
                aria-labelledby="stopRentalModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content shadow-lg border-0 rounded-3">
                        <div class="modal-header bg-danger text-white d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle me-2 fa-lg"></i>
                            <h5 class="modal-title" id="stopRentalModalLabel">ยืนยันการยุติการให้เช่า</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <i class="fas fa-exclamation-circle text-danger fa-3x mb-3"></i>
                            <p class="fs-5 mt-3">
                                คุณแน่ใจหรือไม่ว่าต้องการยุติการให้เช่าชุดนี้?
                                <span class="text-danger fw-bold">หากต้องการ สามารถเปิดให้เช่าอีกครั้งในภายหลังได้</span>
                            </p>
                        </div>
                        @if ($reser_dress_stopRent->count() > 0)
                            <div class="alert alert-warning text-start">
                                <strong>มีลูกค้าที่จองชุดนี้ไว้ {{ $reser_dress_stopRent->count() }} คน</strong>
                                <ul class="mt-2">
                                    @foreach ($reser_dress_stopRent as $item)
                                        <li style="font-size : 14px;">คุณ{{ $item->re_one_many_details->first()->order->customer->customer_fname }}
                                            {{ $item->re_one_many_details->first()->order->customer->customer_lname }}
                                            <span style="font-size : 14px;">(นัดรับวันที่
                                                {{ \Carbon\Carbon::parse($item->start_date)->isoFormat('D MMM') }}
                                                {{ \Carbon\Carbon::parse($item->start_date)->year + 543 }})</span>
                                        </li>
                                    @endforeach
                                </ul>
                                <p class="text-danger fw-bold mt-2">**กรุณาติดต่อแจ้งลูกค้าหลังจากที่ยุติการให้เช่า</p>
                            </div>
                        @endif
                        <div class="modal-footer d-flex justify-content-center">
                            <button type="button" class="btn btn-secondary px-4 py-2 rounded-pill" data-dismiss="modal">
                                <i class="fas fa-times"></i> ยกเลิก
                            </button>
                            <form action="{{ route('stopRentalnodress', ['id' => $datadress->id]) }}" method="POST">
                                <!-- ตัวอย่าง id -->
                                @csrf
                                <button type="submit" class="btn btn-danger px-4 py-2 rounded-pill">
                                    <i class="fas fa-check"></i> ยืนยัน
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="edittotal" role="dialog" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header text-dark"style="background-color: #BACEE6;">
                            <h5 class="modal-title">แก้ไขราคาเช่า</h5>
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


            <div class="modal fade" id="editdes" role="dialog" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header text-dark"style="background-color: #BACEE6;">
                            <h5 class="modal-title">แก้ไขคำอธิบายชุด</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <!-- ข้อมูลชุด -->

                                <form action="{{ route('updatedressnoyesdes', ['id' => $datadress->id]) }}"
                                    method="POST">
                                    @csrf


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
                        


                        @if ($datadress->dress_price == 0)
                            <p><strong>ราคาเช่า:</strong><span class="text-danger"> ยังไม่ได้กำหนด</span></p>
                        @else
                            <p><strong>ราคาเช่า:</strong> {{ number_format($datadress->dress_price, 2) }} บาท
                                <button class="btn btn-link p-0 ml-2" data-toggle="modal" data-target="#edittotal"
                                    @if ($check_admin == 1) 
                                        @if($datadress->dress_status == 'ยุติการให้เช่า' || $datadress->dress_status == 'สูญหาย')
                                        style="display: none ; " 
                                        @else
                                            style="display: inline-block ;"
                                        @endif
                                    @elseif($check_admin == 0) 
                                        style="display: none ; " 
                                    @endif>
                                    <i class="bi bi-pencil-square" style="color: rgb(138, 136, 136);"></i>
                                </button>
                            </p>
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
                        <p><strong>สถานะชุด:</strong>
                            @if ($datadress->dress_status == 'พร้อมให้เช่า')
                                <span class="badge bg-success rounded-pill px-3 py-2 ms-2">
                                    {{ $datadress->dress_status }}
                                </span>
                            @elseif($datadress->dress_status == 'กำลังถูกเช่า')
                                <span class="badge bg-primary rounded-pill px-3 py-2 ms-2">
                                    {{ $datadress->dress_status }}
                                </span>
                            @elseif($datadress->dress_status == 'รอทำความสะอาด')
                                <span class="badge bg-warning rounded-pill px-3 py-2 ms-2">
                                    {{ $datadress->dress_status }}
                                </span>
                            @elseif($datadress->dress_status == 'กำลังส่งซัก')
                                <span class="badge bg-info rounded-pill px-3 py-2 ms-2">
                                    {{ $datadress->dress_status }}
                                </span>
                            @elseif($datadress->dress_status == 'รอดำเนินการซ่อม')
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2 ms-2">
                                    {{ $datadress->dress_status }}
                                </span>
                            @elseif($datadress->dress_status == 'กำลังซ่อม')
                                <span class="badge bg-danger rounded-pill px-3 py-2 ms-2">
                                    {{ $datadress->dress_status }}
                                </span>
                            @elseif($datadress->dress_status == 'สูญหาย')
                                <span class="badge bg-danger rounded-pill px-3 py-2 ms-2">
                                    {{ $datadress->dress_status }}
                                </span>
                            @elseif($datadress->dress_status == 'ยุติการให้เช่า')
                                <span class="badge bg-secondary rounded-pill px-3 py-2 ms-2">
                                    {{ $datadress->dress_status }}
                                </span>
                            @else
                                <span class="badge bg-secondary rounded-pill px-3 py-2 ms-2">
                                    {{ $datadress->dress_status }}
                                </span>
                            @endif
                        </p>

                        <p><strong>คำอธิบายชุด:</strong><span><button class="btn btn-link p-0 ml-2" data-toggle="modal"
                                    data-target="#editdes"
                                    @if ($check_admin == 1) 
                                        @if($datadress->dress_status == 'ยุติการให้เช่า' || $datadress->dress_status == 'สูญหาย')
                                 style="display: none ; " 
                                        @else
                                                           style="display: inline-block ;"
                                        @endif
                                    @elseif($check_admin == 0) 
                                        style="display: none ; " 
                                    @endif>
                                    <i class="bi bi-pencil-square" style="color: rgb(138, 136, 136);"></i>
                                </button>
                                <br>
                                {{ $datadress->dress_description }}</span></p>




                    </div>


                    <div class="col-md-5">
                        <p>
                            <strong>ขนาดของชุด</strong> (ปรับแก้ ขยาย/ลด ได้):




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

                </div>

                <li>
                <a href="{{ route('admin.historydressrent', ['id' => $datadress->id]) }}" class="text-dark">
                    <i class="bi bi-clock-history"></i> ประวัติการเช่า
                </a>
                
                </li>
                <li>
                <a href="{{ route('admin.historydressrepair', ['id' => $datadress->id]) }}" class="text-dark">
                    <i class="bi bi-tools"></i> ประวัติการซ่อม
                </a>
                </li>
                
                <li @if ($check_admin == 0) style="visibility: hidden;" @endif>
                <a href="#" data-toggle="modal" data-target="#priceHistoryModal" class="text-dark">
                    <i class="fas fa-history"></i> ประวัติการปรับแก้ไขราคาเช่า
                </a>
                
                </li>


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




             <!-- Modals for success and failure messages -->
             <div class="modal fade" id="showsuccessss" role="dialog" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-lg">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title"><i class="bi bi-check-circle-fill"></i> สำเร็จ</h5>
        
                        </div>
                        <div class="modal-body text-center p-4">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                            <p class="mt-3 text-success fw-bold">{{ session('success') }}</p>
                        </div>
                        <div class="modal-footer border-0 justify-content-center">
                            <button type="button" class="btn btn-success px-4" data-dismiss="modal">ตกลง</button>
                        </div>
                    </div>
                </div>
            </div>
        
        
            <div class="modal fade" id="showfail" role="dialog" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-lg">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill"></i> เกิดข้อผิดพลาด</h5>
                        </div>
                        <div class="modal-body text-center p-4">
                            <i class="bi bi-x-circle-fill text-danger" style="font-size: 3rem;"></i>
                            <p class="mt-3 text-danger fw-bold">{{ session('fail') }}</p>
                        </div>
                        <div class="modal-footer border-0 justify-content-center">
                            <button type="button" class="btn btn-danger px-4" data-dismiss="modal">ปิด</button>
                        </div>
                    </div>
                </div>
            </div>
        
            <script>
                @if (session('success'))
                    setTimeout(function() {
                        $('#showsuccessss').modal('show');
                    }, 500);
                @endif
                @if (session('fail'))
                    setTimeout(function() {
                        $('#showfail').modal('show');
                    }, 500);
                @endif
            </script>
        






@endsection
