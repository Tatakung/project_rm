@extends('layouts.adminlayout')

@section('content')
    <div class="container mt-5">
        {{-- ส่วนหัวข้อ --}}
        <div class="row mb-3">
            <div class="col">
                <h5>รายละเอียดเซตเครื่องประดับ</h5>
            </div>

            <a href="{{ route('showjewsetrentedhistory', ['id' => $jewelryset->id]) }}" class="btn btn-primary"
                >
                <i class="bi bi-clock-history"></i> ประวัติการเช่า
            </a>
            <button class="btn btn-outline-secondary" type="button" data-toggle="modal" data-target="#priceHistoryModal"
                @if ($is_admin == 1) style="margin-right: 15px; display: block ; "
        @elseif($is_admin == 0)
            style="margin-right: 15px; display: none ; " @endif>
                <i class="bi bi-clock-history"></i>ประวัติการปรับแก้ไขราคาเช่า
            </button>


        </div>







        @if ($check_not_ready == true)
            <div class="alert alert-danger" role="alert">
                <div class="d-flex align-items-center">

                    <div>

                        {{-- <p class="mb-0">เซ็ตนี้ไม่สามารถให้เช่าได้ เนื่องจากเครื่องประดับบางชิ้นสูญหาย/ยุติการให้เช่า
                        </p> --}}
                        <p class="mb-0">เซ็ตนี้ไม่สามารถให้เช่าได้ เนื่องจาก

                            @foreach ($Jewelrysetitem as $item)
                                @if (
                                    $item->jewitem_m_to_o_jew->jewelry_status == 'สูญหาย' ||
                                        $item->jewitem_m_to_o_jew->jewelry_status == 'ยุติการให้เช่า')
                                    {{ $item->jewitem_m_to_o_jew->jewelry_m_o_typejew->type_jewelry_name }}
                                    {{ $item->jewitem_m_to_o_jew->jewelry_m_o_typejew->specific_letter }}{{ $item->jewitem_m_to_o_jew->jewelry_code }}
                                    : {{ $item->jewitem_m_to_o_jew->jewelry_status }}
                                @endif
                            @endforeach
                            ส่งผลให้ไม่ครบเซตจำเป็นต้องยุติการให้เช่า
                        </p>



                    </div>
                </div>
            </div>
        @endif






        {{-- ส่วนข้อมูลพื้นฐานของเซต --}}
        <div class="card mb-4 shadow position-relative">
            <!-- ปุ่มแก้ไขให้อยู่มุมขวาบนสุด -->
            <button class="btn btn-link p-0 position-absolute" style="top: 10px; right: 10px;" data-toggle="modal"
                data-target="#edittotal">
                <i class="bi bi-pencil-square text-dark"></i>
            </button>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <p class="text-muted mb-1">ชื่อเซต</p>
                            <p class="h6">{{ $jewelryset->set_name }}</p>
                        </div>
                    </div>

                    <div class="row col-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="text-muted mb-1">ราคาเช่ารวม</p>
                                <p class="h6">{{ number_format($jewelryset->set_price, 2) }} บาท</p>
                            </div>
                        </div>
                    </div>

                    @if ($jewelryset->set_status == 'ยุติการให้เช่า')
                        <div class="row col-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <p class="text-muted mb-1">สถานะ</p>
                                    <p class="h6">ยุติการให้เช่า</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row col-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <p class="text-muted mb-1">สถานะ</p>
                                    <p class="h6">{{ $jewelryset->set_status }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>






        {{-- ส่วนตารางรายการเครื่องประดับ --}}
        <div class="card shadow">
            <div class="card-body">
                <h5 class="card-title mb-4">รายการเครื่องประดับในเซต{{ $jewelryset->set_name }}</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ชื่อรายการ</th>
                                <th>ราคาเช่า</th>
                                <th>สถานะ</th>
                                <th width="80px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($Jewelrysetitem as $item)
                                <tr>
                                    <td>
                                        <div class="row col">
                                            {{-- ชื่อประเภทเครื่องประดับ + รหัส --}}
                                            <span class="d-block">
                                                {{ $item->jewitem_m_to_o_jew->jewelry_m_o_typejew->type_jewelry_name }}
                                                {{ $item->jewitem_m_to_o_jew->jewelry_m_o_typejew->specific_letter }}{{ $item->jewitem_m_to_o_jew->jewelry_code }}
                                            </span>

                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-medium ">
                                            {{ number_format($item->jewitem_m_to_o_jew->jewelry_price, 2) }} บาท
                                        </span>
                                    </td>
                                    <td>
                                        {{-- สถานะการเช่า (รอการพัฒนา) --}}
                                        <span style="color: #EBAF00;">รอดำเนินการ</span>
                                    </td>
                                    <td class="text-center col-4">
                                        <a href="{{ route('admin.jewelrydetail', ['id' => $item->jewelry_id]) }}"
                                            data-bs-toggle="tooltip" title="ดูรายละเอียด">
                                            <button class="btn btn-secondary">ดูรายละเอียด</button>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        ไม่พบรายการเครื่องประดับในเซตนี้
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



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














    <div class="ml-2"
        @if ($is_admin == 1) style="display: block ; "
    @elseif($is_admin == 0)
    style="display: none ; " @endif>


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

                            <form action="{{ route('admin.updatejewelryset', ['id' => $jewelryset->id]) }}" method="POST">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="update_price" style="font-weight:bold">ราคาเช่า</label>
                                        <input type="number" class="form-control" name="update_price" id="update_price"
                                            value="{{ $jewelryset->set_price }}" placeholder="กรุณากรอกราคา" required
                                            min="1" required>
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






















    @endsection
