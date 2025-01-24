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
            <a href="" style="color: black ; ">จัดการเครื่องประดับ</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.typejewelry', ['id' => $datajewelry->type_jewelry_id]) }}"
                style="color: black ;">ประเภท{{ $data_type->type_jewelry_name }}</a>
        </li>
        <li class="breadcrumb-item active">
            รายละเอียดของหมายเลขเครื่องประดับ {{ $data_type->specific_letter }}{{ $datajewelry->jewelry_code }}
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
            <div class="card-header"><i class="bi bi-info-circle"></i> รายละเอียดเครื่องประดับ
                <button class="btn btn-link p-0 ml-2 float-right" data-toggle="modal" data-target="#edittotal">
                    <i class="bi bi-pencil-square text-dark"></i>
                </button>
            </div>

            <div class="modal fade" id="edittotal" role="dialog" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header text-dark"style="background-color: #BACEE6;">
                            <h5 class="modal-title">แก้ไขข้อมูลเครื่องประดับ</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <!-- ข้อมูลชุด -->

                                <form action="{{ route('admin.updatejewelry', ['id' => $datajewelry->id]) }}"
                                    method="POST">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_price" style="font-weight:bold">ราคาเช่า</label>
                                            <input type="number" class="form-control" name="update_price" id="update_price"
                                                value="{{ $datajewelry->jewelry_price }}" placeholder="กรุณากรอกราคา"
                                                required min="1" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_deposit"style="font-weight:bold">ราคามัดจำ</label>
                                            <input type="number" class="form-control" name="update_deposit"
                                                id="update_deposit" value="{{ $datajewelry->jewelry_deposit }}"
                                                placeholder="กรุณากรอกราคามัดจำ" required min="1" readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label
                                                for="update_damage_insurance"style="font-weight:bold">ราคาประกันค่าเสียหาย</label>
                                            <input type="number" class="form-control" name="update_damage_insurance"
                                                id="update_damage_insurance" value="{{ $datajewelry->damage_insurance }}"
                                                placeholder="กรุณากรอกราคาประกันค่าเสียหาย" min="0" required
                                                readonly>
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





                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <label
                                                for="dress_description"style="font-weight:bold">คำอธิบายเครื่องประดับ</label>
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


            <div class="card-body">
                <div class="row">
                    <div class="d-flex">

                        <div class="p-2 ml-3">
                            <img src="{{ asset('storage/' . $dataimage->jewelry_image) }}" alt=""
                                style="max-height: 350px; width: auto;">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <p><strong>ประเภทเครื่องประดับ:</strong> {{ $data_type->type_jewelry_name }}</p>
                        <p><strong>ราคาเช่า:</strong> {{ number_format($datajewelry->jewelry_price, 2) }} บาท</p>
                        <p><strong>เงินมัดจำ:</strong> {{ number_format($datajewelry->jewelry_deposit, 2) }} บาท</p>
                        <p><strong>ค่าประกัน:</strong> {{ number_format($datajewelry->damage_insurance, 2) }} บาท
                        <p><strong>คำอธิบายชุด: </strong>{{ $datajewelry->jewelry_description }}</p>



                        @if ($datajewelry->jewelry_status == 'สูญหาย' || $datajewelry->jewelry_status == 'ยุติการให้เช่า')
                            <p><strong>สถานะของเครื่องประดับ: </strong><span
                                    style="color: red ; ">{{ $datajewelry->jewelry_status }}</span></p>
                        @else
                            <p><strong>สถานะของเครื่องประดับ: </strong>{{ $datajewelry->jewelry_status }}</p>
                        @endif


                    </div>







                </div>

                <div class="ml-2"
                    @if ($is_admin == 1) style="display: block;  "
                    @elseif($is_admin == 0)
                        style="display: none ; " @endif>
                    <a href="{{ route('showrentedhistory', ['id' => $datajewelry->id]) }}"
                        class="btn btn-outline-primary mr-2">
                        <i class="bi bi-clock-history"></i> ประวัติการเช่า
                    </a>
                    <a href="{{ route('showrepairjewelryhistory', ['id' => $datajewelry->id]) }}"
                        class="btn btn-outline-secondary">
                        <i class="bi bi-tools"></i> ประวัติการซ่อม
                    </a>

                    <button class="btn btn-outline-secondary" type="button" data-toggle="modal"
                        data-target="#priceHistoryModal">
                        <i class="bi bi-clock-history"></i>ประวัติการปรับแก้ไขราคาเช่า
                    </button>
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
