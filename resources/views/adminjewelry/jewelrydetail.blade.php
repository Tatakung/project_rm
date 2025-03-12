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
            color: #fff;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }
    </style>


    


    <ol class="breadcrumb" style="background-color: transparent;">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.jewelrytotal') }}" style="color: black ; ">รายการเครื่องประดับ</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.typejewelry', ['id' => $datajewelry->type_jewelry_id]) }}"
                style="color: black ;">ประเภท{{ $data_type->type_jewelry_name }}</a>
        </li>
        <li class="breadcrumb-item active">
            รายละเอียดของ{{ $data_type->type_jewelry_name }} {{ $data_type->specific_letter }}{{ $datajewelry->jewelry_code }}
        </li>
    </ol>





    <div class="container">
        <div class="row">
            <div class="col">
                <h2 class="py-4" style="text-align: start">รายละเอียดของหมายเลขเครื่องประดับ
                    {{ $data_type->specific_letter }}{{ $datajewelry->jewelry_code }}</h2>
            </div>
        </div>



        <div class="card mb-4 shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-info-circle"></i> รายละเอียดเครื่องประดับ
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-danger" data-toggle="modal"
                        data-target="#stopRentalModal"
                        @if ($is_admin == 1) @if ($datajewelry->jewelry_status == 'ยุติการให้เช่า' || $datajewelry->jewelry_status == 'สูญหาย')
                                style="display: none;"
                            @else
                                style="display: inline-block;" @endif
                    @elseif($is_admin == 0) style="display: none;" @endif
                        >
                        <i class="fas fa-stop"></i> ยุติการให้เช่า
                    </button>

                    <button type="button" class="btn btn-sm btn-outline-success" data-toggle="modal"
                        data-target="#reopenRentalModal"
                        @if ($is_admin == 1) @if ($datajewelry->jewelry_status == 'ยุติการให้เช่า')
                                style="display: inline-block;"
                            @else
                                style="display: none;" @endif
                    @elseif($is_admin == 0) style="display: none;" @endif
                        >
                        <i class="fas fa-check"></i> เปิดให้เช่าอีกครั้ง
                    </button>
                </div>
            </div>
            {{-- </div> --}}



            <div class="card-body">
                <div class="row">
                    <div class="d-flex">
                        <div class="p-2">
                            <img src="{{ asset('storage/' . $dataimage->jewelry_image) }}" alt=""
                                style="max-height: 350px; width: auto;">

                            

                        </div>
                    </div>
                    <div class="col-md-5">
                        <p><strong>ประเภทเครื่องประดับ:</strong> {{ $data_type->type_jewelry_name }}</p>
                        <p><strong>ราคาเช่า:</strong> {{ number_format($datajewelry->jewelry_price, 2) }} บาท
                            <button class="btn btn-link p-0 ml-2" data-toggle="modal" data-target="#editprice"
                                @if ($is_admin == 1) @if ($datajewelry->jewelry_status == 'ยุติการให้เช่า' || $datajewelry->jewelry_status == 'สูญหาย')
                                    style="display: none ; " 
                                @else
                                    style="display: inline-block ;" @endif
                            @elseif($is_admin == 0) style="display: none ; " @endif
                                >
                                <i class="bi bi-pencil-square"style="color: #949495 ; "></i>
                            </button>
                        </p>
                        <p><strong>เงินมัดจำ:</strong> {{ number_format($datajewelry->jewelry_deposit, 2) }} บาท</p>
                        <p><strong>ค่าประกัน:</strong> {{ number_format($datajewelry->damage_insurance, 2) }} บาท





                        <p><strong>สถานะเครื่องประดับ:</strong>
                            @if ($datajewelry->jewelry_status == 'พร้อมให้เช่า')
                                <span class="badge bg-success rounded-pill px-3 py-2 ms-2">
                                    {{ $datajewelry->jewelry_status }}
                                </span>
                            @elseif($datajewelry->jewelry_status == 'กำลังถูกเช่า')
                                <span class="badge bg-primary rounded-pill px-3 py-2 ms-2">
                                    {{ $datajewelry->jewelry_status }}
                                </span>
                            @elseif($datajewelry->jewelry_status == 'รอทำความสะอาด')
                                <span class="badge bg-warning rounded-pill px-3 py-2 ms-2">
                                    {{ $datajewelry->jewelry_status }}
                                </span>
                            @elseif($datajewelry->jewelry_status == 'กำลังทำความสะอาด')
                                <span class="badge bg-info rounded-pill px-3 py-2 ms-2">
                                    {{ $datajewelry->jewelry_status }}
                                </span>
                            @elseif($datajewelry->jewelry_status == 'รอดำเนินการซ่อม')
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2 ms-2">
                                    {{ $datajewelry->jewelry_status }}
                                </span>
                            @elseif($datajewelry->jewelry_status == 'กำลังซ่อม')
                                <span class="badge bg-danger rounded-pill px-3 py-2 ms-2">
                                    {{ $datajewelry->jewelry_status }}
                                </span>
                            @elseif($datajewelry->jewelry_status == 'สูญหาย')
                                <span class="badge bg-danger rounded-pill px-3 py-2 ms-2">
                                    {{ $datajewelry->jewelry_status }}
                                </span>
                            @elseif($datajewelry->jewelry_status == 'ยุติการให้เช่า')
                                <span class="badge bg-secondary rounded-pill px-3 py-2 ms-2">
                                    {{ $datajewelry->jewelry_status }}
                                </span>
                            @else
                                <span class="badge bg-secondary rounded-pill px-3 py-2 ms-2">
                                    {{ $datajewelry->jewelry_status }}
                                </span>
                            @endif
                        </p>













                        <p><strong>คำอธิบาย:</strong><button class="btn btn-link p-0 ml-2" data-toggle="modal"
                                data-target="#editdesc"
                                @if ($is_admin == 1) @if ($datajewelry->jewelry_status == 'ยุติการให้เช่า' || $datajewelry->jewelry_status == 'สูญหาย')
                                style="display: none ; " 
                            @else
                                style="display:inline-block;" @endif
                            @elseif($is_admin == 0) style="display: none ; " @endif>
                                <i class="bi bi-pencil-square" style="color: #949495 ; "></i>
                            </button>
                            <br>
                            {{ $datajewelry->jewelry_description }}
                        </p>




                    </div>
                </div>
                <div>
                    <li>
                        <a href="{{ route('showrentedhistory', ['id' => $datajewelry->id]) }}" class="text-dark">
                            <i class="bi bi-clock-history"></i> ประวัติการเช่า
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('showrepairjewelryhistory', ['id' => $datajewelry->id]) }}" class="text-dark">
                            <i class="bi bi-tools"></i> ประวัติการซ่อม
                        </a>
                    </li>
                    <li @if ($is_admin == 0) style="visibility: hidden;" @endif>
                        <a href="#" data-toggle="modal" data-target="#priceHistoryModal" class="text-dark"><i
                                class="bi bi-clock-history"></i> ประวัติการปรับแก้ไขราคาเช่า</a>
                    </li>
                </div>
















                <!-- Modal แสดงประวัติการแก้ไขราคา -->
                <div class="modal fade" id="priceHistoryModal" tabindex="-1" aria-labelledby="priceHistoryModalLabel"
                    aria-hidden="true" data-backdrop="static">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="priceHistoryModalLabel">ประวัติการปรับแก้ไขราคาเช่า -
                                </h5>
                            </div>

                            <div class="modal-body">
                                @if ($historyprice->count() > 0)
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
                                                @foreach ($historyprice as $item)
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
















            </div>



        </div>
    </div>



    <!-- Modal ยืนยันการยุติการให้เช่า -->
    <div class="modal fade" id="stopRentalModal" tabindex="-1" role="dialog" aria-labelledby="stopRentalModalLabel"
        aria-hidden="true" data-backdrop="static">
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
                        คุณแน่ใจหรือไม่ว่าต้องการยุติการให้เช่าเครื่องประดับนี้?
                        <span class="text-danger fw-bold">หากต้องการ สามารถเปิดให้เช่าอีกครั้งในภายหลังได้</span>
                    </p>
                    <!-- แสดงข้อความแจ้งเตือนหากมีลูกค้าจองชุดนี้ -->
                </div>


                @if ($stop_re->count() > 0)
                    <!-- จำลองว่ามีลูกค้าจองอยู่ -->
                    <div class="alert alert-warning text-start">

                        @if ($set_name->count() > 0)
                            <div class="mb-2 text-danger fw-bold">
                                คำเตือน: เครื่องประดับชิ้นนี้อยู่ใน {{ $set_name->count() }} เซต
                                การยุติการให้เช่าจะทำให้ทั้ง {{ $set_name->count() }} เซตไม่สามารถให้เช่าได้ครบถ้วน
                                และเซตเหล่านั้นจะถูกยุติการให้เช่าโดยอัตโนมัติ
                            </div>
                            <ul class="mb-2" style="font-size: 14px;">
                                @foreach ($set_name as $item)
                                    <li>เซต{{ $item->set_name }}</li>
                                @endforeach


                            </ul>
                            {{-- <p class="text-danger fw-bold">การยุติการให้เช่าจะทำให้ทั้ง {{$set_name->count()}} เซตไม่สามารถให้เช่าได้ครบถ้วน และจะถูกยุติการให้เช่าโดยอัตโนมัติ</p> --}}
                        @endif


                        <strong>มีลูกค้าที่จองไว้ {{ $stop_re->count() }} คน</strong>
                        <ul class="mt-2">
                            @foreach ($stop_re as $item)
                                <li style="font-size : 14px;">
                                    คุณ{{ $item->re_one_many_details->first()->order->customer->customer_fname }}
                                    {{ $item->re_one_many_details->first()->order->customer->customer_lname }}
                                    <span>(นัดรับวันที่
                                        {{ \Carbon\Carbon::parse($item->start_date)->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($item->start_date)->year + 543 }})</span>
                                    @if ($item->jewelry_id)
                                        <i class="fas fa-check-circle text-success fa-1x mb-3"></i> จองเป็นรายชิ้น
                                    @elseif($item->jewelry_set_id)
                                        <i class="fas fa-check-circle text-success fa-1x mb-3"></i> จองเป็นเซต
                                    @endif

                                </li>
                            @endforeach
                        </ul>
                        <p class="text-danger fw-bold mt-2">**กรุณาพิจารณาผลกระทบก่อนยืนยันการดำเนินการ
                            และติดต่อแจ้งลูกค้าหลังจากที่ยุติการให้เช่า</p>
                    </div>
                @endif
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary px-4 py-2 rounded-pill" data-dismiss="modal">
                        <i class="fas fa-times"></i> ยกเลิก
                    </button>
                    <form action="{{ route('jewelrystoprent', ['id' => $datajewelry->id]) }}" method="POST">
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







    @if ($jew_in_set->count() > 0)
        <div class="container mt-2">
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <strong>เซตที่มีเครื่องประดับชิ้นนี้</strong>
                </div>
                <div class="card-body">
                    @foreach ($jew_in_set as $item)
                        <a href="{{ route('admin.setjewelrydetail', ['id' => $item->jewelry_set_id]) }}">

                            <div class="d-flex justify-content-between align-items-center p-3 mb-2 border rounded">
                                <div>
                                    <div style="color:#000"><strong>{{ $item->jewitem_m_to_o_jewset->set_name }}</strong>
                                    </div>
                                    <div class="text-muted">รหัสเซต: SET{{ $item->jewitem_m_to_o_jewset->id }}</div>
                                </div>
                                <div>
                                    <strong
                                        style="color:#000">{{ number_format($item->jewitem_m_to_o_jewset->set_price, 2) }}
                                        บาท</strong>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif



    <div class="modal fade" id="editprice" role="dialog" aria-hidden="true" data-backdrop="static">
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
                        <form action="{{ route('admin.updatejewelry', ['id' => $datajewelry->id]) }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="update_price" style="font-weight:bold">ราคาเช่า</label>
                                    <input type="number" class="form-control" name="update_price" id="update_price"
                                        value="{{ $datajewelry->jewelry_price }}" placeholder="กรุณากรอกราคา" required
                                        min="1" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="update_deposit"style="font-weight:bold">ราคามัดจำ</label>
                                    <input type="number" class="form-control" name="update_deposit" id="update_deposit"
                                        value="{{ $datajewelry->jewelry_deposit }}" placeholder="กรุณากรอกราคามัดจำ"
                                        required min="1" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label
                                        for="update_damage_insurance"style="font-weight:bold">ราคาประกันค่าเสียหาย</label>
                                    <input type="number" class="form-control" name="update_damage_insurance"
                                        id="update_damage_insurance" value="{{ $datajewelry->damage_insurance }}"
                                        placeholder="กรุณากรอกราคาประกันค่าเสียหาย" min="0" required readonly>
                                </div>
                            </div>

                            <script>
                                var update_price = document.getElementById('update_price');
                                var update_deposit = document.getElementById('update_deposit');
                                var update_damage_insurance = document.getElementById('update_damage_insurance');


                                update_price.addEventListener('input', function() {

                                    var float_price = parseFloat(update_price.value);

                                    update_deposit.value = Math.ceil(float_price * 0.3);
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


    <div class="modal fade" id="editdesc" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header text-dark"style="background-color: #BACEE6;">
                    <h5 class="modal-title">แก้ไขคำอธิบายเครื่องประดับ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <!-- ข้อมูลชุด -->
                        <form action="{{ route('admin.updatejewelrydes', ['id' => $datajewelry->id]) }}" method="POST">
                            @csrf

                            <div class="row mb-4">
                                <div class="col-12">
                                    <label for="dress_description"style="font-weight:bold">คำอธิบายเครื่องประดับ</label>
                                    <textarea name="update_dress_description" id="update_dress_description" class="form-control" rows="3"
                                        placeholder="กรุณากรอกคำอธิบาย">{{ $datajewelry->jewelry_description }}</textarea>
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
                            ลูกค้าจะสามารถจองเครื่องประดับนี้ได้ตามปกติ</span>
                    </p>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary px-4 py-2 rounded-pill" data-dismiss="modal">
                        <i class="fas fa-times"></i> ยกเลิก
                    </button>
                    <form action="{{ route('jewelryreopen', ['id' => $datajewelry->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success px-4 py-2 rounded-pill">
                            <i class="fas fa-check"></i> ยืนยัน
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>














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
