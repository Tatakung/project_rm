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
                <h4 class="mb-0"><i class="bi bi-info-circle-fill me-2"></i>ข้อมูลการเช่าชุด</h4>
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
                            <span><strong>ชุด:</strong></span>
                            <span>{{ $orderdetail->dress_id }}</span>
                        </li> --}}
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>หมายเลขชุด:</strong></span>
                                <span>{{ $dress->dress_code_new }}{{ $dress->dress_code }}</span>
                            </li>
                            {{-- <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><strong>พนักงานรับออเดอร์:</strong></span>
                            <span>คุณ{{ $customer->name }}&nbsp;{{ $customer->lname }}</span>
                        </li> --}}
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>ราคา/ชุด:</strong></span>
                                <span>{{ number_format($orderdetail->price, 2) }} บาท</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>ราคามัดจำ/ชุด:</strong></span>
                                <span>{{ number_format($orderdetail->deposit, 2) }} บาท</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>ค่าบริการขยายเวลาเช่าชุด:</strong></span>
                                <span>{{ number_format($orderdetail->late_charge, 2) }} บาท</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>วันที่นัดรับชุด:</strong></span>
                                <span>
                                    {{ \Carbon\Carbon::parse($orderdetail->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                    {{ \Carbon\Carbon::parse($orderdetail->pickup_date)->year + 543 }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>วันที่นัดคืนชุด:</strong></span>
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
                                <span>เช่าชุด</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>จำนวนชุดที่เช่า:</strong></span>
                                <span>{{ $orderdetail->amount }}&nbsp;ชุด</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><strong>สีของชุด:</strong></span>
                                <span>{{ $orderdetail->color }}</span>
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
                        <div id="button_status_pickup" >
                            <button class="btn btn-warning" data-toggle="modal" data-target="#modalupdatestatusrentdress">
                                อัพเดตสถานะ(มารับชุด)
                            </button>
                        </div>
                        <div style="display: block ;" id="button_status_return">
                            <button class="btn btn-warning" data-toggle="modal"
                                data-target="#modalupdatestatusrentdresstwo">
                                อัพเดตสถานะ(คืนชุด)
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
                        <h4 class="mb-0"><i class="bi bi-calendar-event-fill me-2"></i>วันที่นัดลองชุด</h4>
                        <div>
                            <button class="btn btn-warning" data-toggle="modal" data-target="#modaladdfitting"
                                id="button_add_fitting">
                                เพิ่มวันนัดลองชุด
                            </button>

                        </div>
                    </div>

                    @if ($fitting->count() > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>วันที่นัด</th>
                                    <th>สถานะ</th>
                                    <th>action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fitting as $fit)
                                    <tr>
                                        <td>
                                            {{ \Carbon\Carbon::parse($fit->fitting_date)->locale('th')->isoFormat('D MMM') }}
                                            {{ \Carbon\Carbon::parse($fit->fitting_date)->year + 543 }}

                                        </td>
                                        <td>{{ $fit->fitting_status }}</td>
                                        <td>

                                            <button type="button" data-toggle="modal"
                                                data-target="#modaleditfitting{{ $fit->id }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="submit" data-toggle="modal" id="button_delete_fitting"
                                                data-target="#modaldeletefitting{{ $fit->id }}"
                                                @if ($fit->fitting_status == 'มาลองแล้ว') style="display: none" @endif>
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </td>
                                    </tr>


                                    {{-- modalลบfitting --}}
                                    <div class="modal fade" id="modaldeletefitting{{ $fit->id }}" role="dialog"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h5 class="modal-title">ยืนยันการลบข้อมูลนัดลองชุด</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form
                                                    action="{{ route('employee.actiondeletefitting', ['id' => $fit->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-body">
                                                        <p>แน่ใจหรือว่าต้องการจะลบรายการข้อมูลนัดลองชุดนี้</p>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-md-4"><strong>วันที่นัด:</strong></div>
                                                            <div class="col-md-8">
                                                                {{ \Carbon\Carbon::parse($fit->fitting_date)->locale('th')->isoFormat('D MMM') }}
                                                                {{ \Carbon\Carbon::parse($fit->fitting_date)->year + 543 }}
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4"><strong>สถานะ:</strong></div>
                                                            <div class="col-md-8">{{ $fit->fitting_status }}</div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4"><strong>โน๊ต:</strong></div>
                                                            <div class="col-md-8">{{ $fit->fitting_note }}</div>

                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-danger" type="button"
                                                            data-dismiss="modal">ยกเลิก</button>
                                                        <button class="btn btn-secondary" type="submit">ยืนยัน</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- modalแก้ไขเกี่ยวกับการนัดลองชุด --}}
                                    <div class="modal fade" id="modaleditfitting{{ $fit->id }}" tabindex="-1"
                                        role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">นัดลองชุด</h5>
                                                </div>
                                                <form
                                                    action="{{ route('employee.actionupdatefitting', ['id' => $fit->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="update_dress_type">วันที่นัด:
                                                                {{ \Carbon\Carbon::parse($fit->fitting_date)->locale('th')->isoFormat('D MMM') }}
                                                                {{ \Carbon\Carbon::parse($fit->fitting_date)->year + 543 }}</label>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label" for="">สถานะ:
                                                                {{ $fit->fitting_status }}</label>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label" for="">โน๊ต:
                                                                {{ $fit->fitting_note }}</label>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label" for="">โน๊ต:</label>
                                                            <input type="text" class="form-control"
                                                                name="update_fitting_note"
                                                                value="{{ $fit->fitting_note }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label"
                                                                for="update_fitting_status">สถานะ:</label>
                                                            <select class="form-control" name="update_fitting_status"
                                                                required>
                                                                <option value="ยังไม่มาลอง"
                                                                    {{ $fit->fitting_status == 'ยังไม่มาลอง' ? 'selected' : '' }}
                                                                    @if ($fit->fitting_status == 'มาลองแล้ว') disabled @endif>
                                                                    ยังไม่มาลอง</option>
                                                                <option value="มาลองแล้ว"
                                                                    {{ $fit->fitting_status == 'มาลองแล้ว' ? 'selected' : '' }}>
                                                                    มาลองแล้ว</option>
                                                            </select>
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
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="card-body">
                            <p class="lead text-center">ไม่มีข้อมูลการนัด</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- แถวที่สาม: เพิ่มเติมลวดลาย และ รูปภาพ -->
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow mb-5">
                    <div class="card-header bg-secondary text-dark d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="bi bi-brush-fill me-2"></i>เพิ่มเติมอื่นๆ</h4>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#modaladddecoration"
                            id="button_add_decoration">
                            เพิ่มข้อมูลเติมอื่นๆ
                        </button>
                    </div>

                    @if ($decoration->count() > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>รายละเอียด</th>
                                    <th>ราคา</th>
                                    <th>action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($decoration as $decoration)
                                    <tr>
                                        <td>{{ $decoration->decoration_description }}</td>
                                        <td>{{ $decoration->decoration_price }}</td>
                                        <td id="botton_action_decoration1" >
                                            <button type="button" data-toggle="modal" data-target="#modalupdatedecoration{{ $decoration->id }}"
                                                @if($orderdetail->status_detail == "กำลังเช่า") style="display: none;" @endif>
                                                <i class="bi bi-pencil"></i>
                                            </button>                                            <button type="submit" data-toggle="modal"
                                                data-target="#modaldeletedecotaion{{ $decoration->id }}">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </td>

                                        {{-- modalลบเพิ่มเติมอื่นๆ --}}
                                        <div class="modal fade" id="modaldeletedecotaion{{ $decoration->id }}"
                                            role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                        <h5 class="modal-title">ยืนยันการลบข้อมูลเพิ่มเติมอื่นๆ</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form
                                                        action="{{ route('employee.actiondeletedecoration', ['id' => $decoration->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="modal-body">
                                                            <p>แน่ใจหรือว่าต้องการจะลบรายการ</p>
                                                            <hr>

                                                            <div class="row">
                                                                <div class="col-md-4"><strong>รายละเอียด:</strong>
                                                                </div>
                                                                <div class="col-md-8">
                                                                    {{ $decoration->decoration_description }}</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4"><strong>ราคา:</strong></div>
                                                                <div class="col-md-8">{{ $decoration->decoration_price }}
                                                                </div>
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

                                        {{-- modalแก้ไขdecotaion --}}
                                        <div class="modal fade" id="modalupdatedecoration{{ $decoration->id }}"
                                            tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">เพิ่มเติมอื่นๆ</h5>
                                                    </div>
                                                    <form
                                                        action="{{ route('employee.actionupdatedecoration', ['id' => $decoration->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label"
                                                                    for="update_decoration_description">รายละเอียด:</label>
                                                                <input type="text" class="form-control"
                                                                    name="update_decoration_description"
                                                                    value="{{ $decoration->decoration_description }}"
                                                                    required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label"
                                                                    for="update_decoration_price">ราคา:</label>
                                                                <input type="number" class="form-control"
                                                                    name="update_decoration_price"
                                                                    value="{{ $decoration->decoration_price }}"
                                                                    min="0" step="0.01" required>
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
                        <div class="card-body text-center">
                            <p class="text-muted">ไม่มีข้อมูลเพิ่มเติม</p>
                        </div>
                    @endif
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
                                <th>วันที่นัดรับชุด</th>
                                <th>วันที่นัดคืนชุด</th>
                                <th>ค่าบริการขยายเวลาเช่าชุด</th>
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
                        <h4 class="mb-0"><i class="bi bi-image-fill me-2"></i>รูปภาพก่อนเช่าชุด</h4>

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
                            <p class="lead text-center">ไม่มีข้อมูลรูปภาพก่อนเช่าชุด</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>






        <div class="row">
            <div class="col-md-6">
                <div class="card shadow mb-5">
                    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="bi bi-calendar-fill me-2"></i>ข้อมูลการวัด</h4>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#modaladdmeaorderdetail"
                            id="botton_add_mea">
                            เพิ่มข้อมูลการวัด
                        </button>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ชื่อการวัด</th>
                                <th>ตัวเลข</th>
                                <th>หน่วยวัด</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mea_dress as $mea_dress)
                                <tr>
                                    <td>{{ $mea_dress->measurement_dress_name }}</td>
                                    <td>{{ $mea_dress->measurement_dress_number }}</td>
                                    <td>{{ $mea_dress->measurement_dress_unit }}</td>
                                    <td>
                                        <button type="button" data-toggle="modal"
                                            data-target="#modaleditmeadress{{ $mea_dress->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </td>
                                    {{-- modalแก้ไขข้อมูลชุด --}}
                                    <div class="modal fade" id="modaleditmeadress{{ $mea_dress->id }}" tabindex="-1"
                                        role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">ข้อมูลการวัด</h5>
                                                </div>
                                                <form
                                                    action="{{ route('employee.actionupdatemeadress', ['id' => $mea_dress->id]) }}"method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="">ชื่อการวัด:
                                                                {{ $mea_dress->measurement_dress_name }}</label>
                                                        </div>


                                                        <div class="mb-3">
                                                            <label class="form-label"
                                                                for="update_measurement_dress_number">ตัวเลข:</label>
                                                            <input type="number" class="form-control"
                                                                name="update_measurement_dress_number"
                                                                value="{{ $mea_dress->measurement_dress_number }}"
                                                                step="0.01" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label"
                                                                for="update_measurement_dress_unit">สถานะ:</label>
                                                            <select class="form-control"
                                                                name="update_measurement_dress_unit" required>
                                                                <option
                                                                    value="นิ้ว"{{ $mea_dress->measurement_dress_unit == 'นิ้ว' ? 'selected' : '' }}>
                                                                    นิ้ว</option>
                                                                <option
                                                                    value="เซนติเมตร"{{ $mea_dress->measurement_dress_unit == 'เซนติเมตร' ? 'selected' : '' }}>
                                                                    เซนติเมตร</option>
                                                                <option
                                                                    value="มิลลิเมตร"{{ $mea_dress->measurement_dress_unit == 'มิลลิเมตร' ? 'selected' : '' }}>
                                                                    มิลลิเมตร</option>
                                                            </select>
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
                            @foreach ($mea_orderdetail as $mea_orderdetail)
                                <tr>
                                    <td>{{ $mea_orderdetail->measurement_name }}</td>
                                    <td>{{ $mea_orderdetail->measurement_number }}</td>
                                    <td>{{ $mea_orderdetail->measurement_unit }}</td>
                                    <td id="bonton_action_mea">
                                        <button type="button" data-toggle="modal"
                                            data-target="#modaleditmeaorderdetail{{ $mea_orderdetail->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="submit" data-toggle="modal"
                                            data-target="#modaldeletemeaorderdetail{{ $mea_orderdetail->id }}">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </td>

                                    {{-- modalลบข้อมูลการวัดorderdetail --}}
                                    <div class="modal fade" id="modaldeletemeaorderdetail{{ $mea_orderdetail->id }}"
                                        role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h5 class="modal-title">ยืนยันการลบข้อมูลการวัด</h5>

                                                </div>
                                                <form
                                                    action="{{ route('employee.actiondeletemeaorderdetail', ['id' => $mea_orderdetail->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-body">
                                                        <p>แน่ใจหรือว่าต้องการจะลบรายการข้อมูลการวัดของลูกค้า</p>
                                                        <hr>

                                                        <div class="row">
                                                            <div class="col-md-4"><strong>ชื่อการวัด:</strong></div>
                                                            <div class="col-md-8">{{ $mea_orderdetail->measurement_name }}
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4"><strong>ตัวเลข:</strong></div>
                                                            <div class="col-md-8">
                                                                {{ $mea_orderdetail->measurement_number }}</div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4"><strong>หน่วยวัด:</strong></div>
                                                            <div class="col-md-8">{{ $mea_orderdetail->measurement_unit }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-danger" type="button"
                                                            data-dismiss="modal">ยกเลิก</button>
                                                        <button class="btn btn-secondary" type="submit">ยืนยัน</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- modalแก้ไขการวัด meaorderdetail --}}
                                    <div class="modal fade" id="modaleditmeaorderdetail{{ $mea_orderdetail->id }}"
                                        tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">ข้อมูลการวัด</h5>
                                                </div>
                                                <form
                                                    action="{{ route('employee.actionupdatemeaorderdetail', ['id' => $mea_orderdetail->id]) }}"method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="">ชื่อการวัด:</label>
                                                            <input type="text" class="form-control"
                                                                name="update_measurement_name"
                                                                value="{{ $mea_orderdetail->measurement_name }}" required>
                                                        </div>


                                                        <div class="mb-3">
                                                            <label class="form-label" for="">ตัวเลข:</label>
                                                            <input type="number" class="form-control"
                                                                name="update_measurement_number"
                                                                value="{{ $mea_orderdetail->measurement_number }}"
                                                                step="0.01" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label" for="">สถานะ:</label>
                                                            <select class="form-control" name="update_measurement_unit"
                                                                required>
                                                                <option
                                                                    value="นิ้ว"{{ $mea_orderdetail->measurement_unit == 'นิ้ว' ? 'selected' : '' }}>
                                                                    นิ้ว</option>
                                                                <option
                                                                    value="เซนติเมตร"{{ $mea_orderdetail->measurement_unit == 'เซนติเมตร' ? 'selected' : '' }}>
                                                                    เซนติเมตร</option>
                                                                <option
                                                                    value="มิลลิเมตร"{{ $mea_orderdetail->measurement_unit == 'มิลลิเมตร' ? 'selected' : '' }}>
                                                                    มิลลิเมตร</option>
                                                            </select>
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
                </div>
            </div>


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


    {{-- modalเพิ่มข้อมูลเติมอื่นๆ --}}
    <div class="modal fade" id="modaladddecoration" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    เพิ่มข้อมูลเติมอื่นๆ
                </div>
                <form action="{{ route('employee.actionadddecoration', ['id' => $orderdetail->id]) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-4"><strong>รายละเอียด:</strong></div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="add_decoration_description" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4"><strong>ราคา:</strong></div>
                            <div class="col-md-8">
                                <input type="number" class="form-control" name="add_decoration_price" min="1"
                                    required>
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

    {{-- modalเพิ่มข้อมูลการวัด --}}
    <div class="modal fade" id="modaladdmeaorderdetail" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    เพิ่มข้อมูลการวัด
                </div>
                <form action="{{ route('employee.actionaddmeaorderdetail', ['id' => $orderdetail->id]) }}"
                    method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-4"><strong>ชื่อการวัด:</strong></div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="add_measurement_name" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4"><strong>ตัวเลข:</strong></div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="add_measurement_number" required
                                    step="0.01">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4"><strong>หน่วยวัด:</strong></div>
                            <div class="col-md-8">
                                <select class="form-control" name="add_measurement_unit" required>
                                    <option value="นิ้ว">
                                        นิ้ว</option>
                                    <option value="เซนติเมตร">
                                        เซนติเมตร</option>
                                    <option value="มิลลิเมตร">
                                        มิลลิเมตร</option>
                                </select>
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


    {{-- modalอัปเดตสถานะของเช่าชุด --}}
    <div class="modal fade" id="modalupdatestatusrentdress" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('employee.actionupdatestatusrentdress', ['id' => $orderdetail->id]) }}"
                    method="POST">
                    @csrf
                    <div class="modal-header">
                        อัปเดตสถานะ
                    </div>
                    <div class="modal-body">
                        ต้องการจะอัพเดตสถานะของลูกค้า
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
    <div class="modal fade" id="modalupdatestatusrentdresstwo" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('employee.actionupdatestatusrentdress', ['id' => $orderdetail->id]) }}"
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
                            <label for="damage_insurance">ประกันที่จ่าย</label>
                            <p>{{ number_format($orderdetail->damage_insurance, 2) }} บาท ณ วันที่มารับชุด</p>
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




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var button_status_pickup = document.getElementById('button_status_pickup'); //ปุ่มอัพเดตสถาะนะรับชุด
            var button_status_return = document.getElementById('button_status_return'); //ปุ่มอัพเดตสถาะนะคืนชุด
            var button_add_fitting = document.getElementById('button_add_fitting'); //ปุ่มเพิ่มวันนัดรับชุด
            var button_add_decoration = document.getElementById('button_add_decoration'); //ปุ่มเพิ่มเติมอื่นๆ
            var button_edit_date = document.getElementById('button_edit_date'); //ปุ่มแก้ไขวันที่
            var button_add_image = document.getElementById('button_add_image'); //ปุ่มเพิ่มรูปภาพ
            var botton_add_mea = document.getElementById('botton_add_mea'); //ปุ่มเพิ่มข้อมูลการวัด

            // var button_delete_fitting = document.getElementById('button_delete_fitting'); //ปุ่มลบวันนัดลองชุด
            // var bonton_action_mea = document.getElementById('bonton_action_mea'); //ปุ่ม actionของ การวัด
            // var botton_action_decoration = document.getElementById('botton_action_decoration'); //ปุ่ม action ของ อื่นๆ

            var check_status = "{{ $valuestatus }}"; //รับค่ามาเช็ค

            if(check_status === "จองชุด"){
                button_status_return.style.display = 'none' ; 
            }
            if (check_status === "กำลังเช่า") {
                button_status_pickup.style.display = 'none';
                button_add_fitting.style.display = 'none' ;   
                button_add_decoration.style.display = 'none' ; 
                button_edit_date.style.display = 'none' ; 
                button_add_image.style.display = 'none' ; 
                botton_add_mea.style.display = 'none' ; 
            }
            if(check_status ==== 'คืนชุดแล้ว'){
                button_status_pickup.style.display = 'none';
                button_add_fitting.style.display = 'none' ;   
                button_add_decoration.style.display = 'none' ; 
                button_edit_date.style.display = 'none' ; 
                button_add_image.style.display = 'none' ; 
                botton_add_mea.style.display = 'none' ; 
            }

        });
    </script>










@endsection
