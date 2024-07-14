@extends('layouts.adminlayout')

@section('content')
    <style>
        .list-group-item {
            border-left: none;
            border-right: none;
            padding: 0.75rem 1.25rem;
        }

        .list-group-item:first-child {
            border-top: none;
        }

        .list-group-item:last-child {
            border-bottom: none;
        }
    </style>
    <div class="modal fade" id="showsuccessss" role="dialog" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog" role="document">
            <div class="modal-content custom-modal-content"
                style="max-width: 300px; height: 50px; width: 100%; margin: auto; background-color: #53b007;">
                <div class="modal-body" style="padding: 10px; display: flex; align-items: center; justify-content: center;">
                    <p style="margin: 0; color: #ffffff;">{{ session('success') }}</p>
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
    </script>



    <div class="container mt-4">
        <!-- กล่องแรก: ข้อมูลการเช่าชุด -->
        <div class="card shadow mb-5">
            <div class="card-header bg-secondary text-white">
                <h4 class="mb-0"><i class="bi bi-info-circle-fill me-2"></i>ข้อมูลการเช่าเครื่องประดับ</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>หมายเลข Order Detail:</strong></span>
                                <span class="badge bg-primary rounded-pill">{{ $orderdetail->id }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>รายการ:</strong></span>
                                <span>{{ $orderdetail->title_name }}</span>
                            </li>
               
                            {{-- <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>หมายเลขชุด:</strong></span>
                                <span>{{ $dress->dress_code_new }}{{ $dress->dress_code }}</span>
                            </li> --}}
                      
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>ราคา/ชิ้น:</strong></span>
                                <span>{{ number_format($orderdetail->price, 2) }} บาท</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>ราคามัดจำ/ชิ้น:</strong></span>
                                <span>{{ number_format($orderdetail->deposit, 2) }} บาท</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>ค่าบริการขยายเวลาเช่าเครื่องประดับ:</strong></span>
                                <span>{{ number_format($orderdetail->late_charge, 2) }} บาท</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>วันที่นัดรับเครื่องประดับ:</strong></span>
                                <span>
                                    {{ \Carbon\Carbon::parse($orderdetail->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                    {{ \Carbon\Carbon::parse($orderdetail->pickup_date)->year + 543 }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>วันที่นัดคืนเครื่องประดับ:</strong></span>
                                <span>
                                    {{ \Carbon\Carbon::parse($orderdetail->return_date)->locale('th')->isoFormat('D MMM') }}
                                    {{ \Carbon\Carbon::parse($orderdetail->return_date)->year + 543 }}
                                </span>
                            </li>
                            {{-- <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><strong>ประเภทชุด:</strong></span>
                            <span>{{ $orderdetail->type_dress }}</span>
                        </li> --}}
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>ประเภทออเดอร์:</strong></span>
                                <span>เช่าเครื่องประดับ</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>จำนวนเครื่องประดับที่เช่า:</strong></span>
                                <span>{{ $orderdetail->amount }}&nbsp;ชิ้น</span>
                            </li>

                            
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>ค่าประกันความเสียหาย:</strong></span>
                                <span>{{ number_format($orderdetail->damage_insurance, 2) }} บาท</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>สถานะออเดอร์:(ล่าสุด)</strong></span>
                                <span
                                    class="badge bg-{{ $orderdetail->status_detail == 'เสร็จสิ้น' ? 'success' : 'warning' }}">{{ $orderdetail->status_detail }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>สถานะจ่ายเงิน:</strong></span>
                                <span
                                    class="badge bg-{{ $orderdetail->status_payment == 'ชำระแล้ว' ? 'success' : 'danger' }}">
                                    @if ($orderdetail->status_payment == 1)
                                        จ่ายมัดจำแล้ว({{number_format($orderdetail->deposit,2)}})
                                    @else
                                        จ่ายเต็มจำนวนแล้ว({{number_format($orderdetail->price,2)}})
                                    @endif

                                </span>
                            </li>
                            <li class="list-group-item">
                                <strong>โน๊ต:</strong><br>
                                <small>{{ $orderdetail->note }}</small>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- คงไว้ส่วนอื่นๆ ตามเดิม -->

        <div class="row">
            <div class="col-md-6">
                <div class="card shadow mb-5">
                    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="bi bi-check-circle-fill me-2"></i>สถานะออเดอร์</h4>
                        <div style="display: block ; " id="button_status_pickup">
                            <button class="btn btn-warning" data-toggle="modal" data-target="#modalupdatestatusrentjewelry">
                                อัพเดตสถานะ(มารับเครื่องประดับ)
                            </button>
                        </div>
                        <div style="display: block ;" id="button_status_return">
                            <button class="btn btn-warning" data-toggle="modal"
                                data-target="#modalupdatestatusrentjewelrytwo">
                                อัพเดตสถานะ(คืนเครื่องประดับ)
                            </button>
                        </div>


                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>วันที่ทำรายการ</th>
                                <th>สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderdetailstatus as $orderdetailstatus)
                                <tr>
                                    <td>
                                        {{ \Carbon\Carbon::parse($orderdetailstatus->created_at)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($orderdetailstatus->created_at)->year + 543 }}
                                    </td>
                                    <td>{{ $orderdetailstatus->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>



            <div class="col-md-6">
                <div class="card shadow mb-5">
                    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="bi bi-cash-coin me-2"></i>ค่าใช้จ่าย</h4>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#modaladdcost">
                            บันทึกค่าใช้จ่าย
                        </button>
                    </div>
                    @if ($cost->count() > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ประเภทค่าใช้จ่าย</th>
                                    <th>ราคา</th>
                                    <th>action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cost as $cost)
                                    <tr>

                                        <td>{{ $cost->cost_type }}</td>
                                        <td>{{ $cost->cost_value }}</td>
                                        <td>
                                            <button type="button" data-toggle="modal"
                                                data-target="#modaleditcost{{ $cost->id }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="submit" data-toggle="modal"
                                                data-target="#modaldeletecost{{ $cost->id }}">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </td>
                                        {{-- modalลบcost --}}
                                        <div class="modal fade" id="modaldeletecost{{ $cost->id }}" role="dialog"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                        <h5 class="modal-title">ยืนยันการลบข้อมูลค่าใช้จ่าย</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form
                                                        action="{{ route('employee.actiondeletecost', ['id' => $cost->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="modal-body">
                                                            <p>แน่ใจหรือว่าต้องการจะลบรายการข้อมูลค่าใช้จ่าย</p>
                                                            <hr>

                                                            <div class="row">
                                                                <div class="col-md-4"><strong>ประเภทค่าใช้จ่าย:</strong>
                                                                </div>
                                                                <div class="col-md-8">{{ $cost->cost_type }}</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4"><strong>ราคา:</strong></div>
                                                                <div class="col-md-8">{{ $cost->cost_value }}</div>

                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-danger" type="button"
                                                                data-dismiss="modal">ยกเลิก</button>
                                                            <button class="btn btn-secondary"
                                                                type="submit">ยืนยัน</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>


                                        {{-- modalแก้ไขพวกค่าใช้จ่าย --}}
                                        <div class="modal fade" id="modaleditcost{{ $cost->id }}" tabindex="-1"
                                            role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">ค่าใช้จ่าย</h5>
                                                    </div>
                                                    <form
                                                        action="{{ route('employee.actionupdatecost', ['id' => $cost->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label"
                                                                    for="">ประเภทค่าใช้จ่าย:</label>
                                                                <input type="text" class="form-control"
                                                                    name="update_cost_type"
                                                                    value="{{ $cost->cost_type }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label"
                                                                    for="update_cost_value">ราคา:</label>
                                                                <input type="number" class="form-control"
                                                                    name="update_cost_value"
                                                                    value="{{ $cost->cost_value }}" min="1"
                                                                    step="0.01" required>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger"
                                                                    data-dismiss="modal">ยกเลิก</button>
                                                                <button type="submit"
                                                                    class="btn btn-secondary">ยืนยัน</button>
                                                            </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="card-body">
                            <p class="lead text-center">ไม่มีข้อมูลการบันทึกค่าใช้จ่าย</p>
                        </div>
                    @endif
                </div>
            </div>

            

        </div>

        <!-- แถวที่สาม: เพิ่มเติมลวดลาย และ รูปภาพ -->
        <div class="row">
            









            





        </div>

        <!-- แถวที่สี่: ค่าใช้จ่าย และ วันที่ -->
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow mb-5">
                    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="bi bi-calendar-fill me-2"></i>วันที่</h4>
                        <button class="btn btn-warning" id="button_edit_date">
                            แก้ไขวันที่
                        </button>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>นัดรับเครื่องประดับ</th>
                                <th>นัดคืนเครื่องประดับ</th>
                                <th>ค่าบริการขยายเวลาเช่า</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($date as $date)
                                <tr>
                                    <td>
                                        {{ \Carbon\Carbon::parse($date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($date->pickup_date)->year + 543 }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($date->return_date)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($date->return_date)->year + 543 }}
                                    </td>
                                    <td>0.00
                                        บาท
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow mb-5">
                    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="bi bi-image-fill me-2"></i>รูปภาพก่อนเช่าหรือที่เกี่ยวข้อง</h4>

                        <button class="btn btn-warning" data-toggle="modal" data-target="#modaladdimagerent"
                            id="button_add_image">
                            เพิ่มรูปภาพ
                        </button>
                    </div>
                    @if ($imagerent->count() > 0)
                        {{-- @foreach ($imagerent as $imagerent)
                        <img src="{{asset('storage/' .$imagerent->image)}}" alt="" width="140px ; ">
                        @endforeach --}}
                        <div class="row">
                            @foreach ($imagerent as $imagerent)
                                <div class="col-md-4">

                                    <img src="{{ asset('storage/' . $imagerent->image) }}" alt="" width="140px;"
                                        height="140px">
                                </div>
                            @endforeach

                        </div>
                    @else
                        <div class="card-body">
                            <p class="lead text-center">ไม่มีข้อมูลรูปภาพ</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>






        <div class="row">
            


        </div>

















    </div>

    </div>
    {{-- modalเพิ่มวันนัดลองชุด --}}
    <div class="modal fade" id="modaladdfitting" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    เพิ่มข้อมูลการนัด
                </div>
                <form action="{{ route('employee.actionaddfitting', ['id' => $orderdetail->id]) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-4"><strong>วันที่นัด:</strong></div>
                            <div class="col-md-8">
                                <input type="date" class="form-control" name="add_fitting_date">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4"><strong>รายละเอียด:</strong></div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="add_fitting_note">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-dismiss="modal">ยกเลิก</button>
                        <button class="btn btn-secondary" type="submit">ยืนยัน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- modalเพิ่มบันทึกค่าใช้จ่าย --}}
    <div class="modal fade" id="modaladdcost" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    เพิ่มค่าใช้จ่าย
                </div>
                <form action="{{ route('employee.actionaddcost', ['id' => $orderdetail->id]) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-4"><strong>ประเภทค่าใช้จ่าย:</strong></div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="add_cost_type">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4"><strong>ราคา:</strong></div>
                            <div class="col-md-8">
                                <input type="number" class="form-control" name="add_cost_value" min="1">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-dismiss="modal">ยกเลิก</button>
                        <button class="btn btn-secondary" type="submit">ยืนยัน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modalเพิ่มรูปภาพ --}}
    <div class="modal fade" id="modaladdimagerent" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    เพิ่มรูปภาพ
                </div>
                <form action="{{ route('employee.actionaddimagerent', ['id' => $orderdetail->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-4"><strong>แนบรูปภาพ:</strong></div>
                            <div class="col-md-8">
                                <input type="file" class="form-control" name="add_image" required>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-dismiss="modal">ยกเลิก</button>
                        <button class="btn btn-secondary" type="submit">ยืนยัน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- modalอัปเดตสถานะของเช่าเครื่องประดับ --}}
    <div class="modal fade" id="modalupdatestatusrentjewelry" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('employee.actionupdatestatusrentjewelry', ['id' => $orderdetail->id]) }}"
                    method="POST">
                    @csrf
                    <div class="modal-header">
                        อัปเดตสถานะ
                    </div>
                    <div class="modal-body">
                        ต้องการจะอัพเดตสถานะมารับเครื่องประดับของลูกค้า?
                        
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-dismiss="modal">ยกเลิก</button>
                        <button class="btn btn-secondary" type="submit">ยืนยัน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modalอัปเดตสถานะของเช่าชุด --}}
    <div class="modal fade" id="modalupdatestatusrentjewelrytwo" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('employee.actionupdatestatusrentjewelry', ['id' => $orderdetail->id]) }}"
                    method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">อัปเดตสถานะ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="damage_insurance">ประกันที่ลูกค้าจ่าย</label>
                            <p>{{ number_format($orderdetail->damage_insurance, 2) }} บาท </p>
                        </div>
                        <div class="form-group">
                            <label for="damage_insurance">หักเงินประกันจริง</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="total_damage_insurance"
                                    id="total_damage_insurance" value="0" step="0.01" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">บาท</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="why">เหตุผล</label>
                            <input type="text" class="form-control" name="cause_for_insurance"
                                id="cause_for_insurance" placeholder="เหตุผลในการหัก" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-dismiss="modal">ยกเลิก</button>
                        <button class="btn btn-primary" type="submit">ยืนยัน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>




   









@endsection
