@extends('layouts.adminlayout')

@section('content')
    <div class="container mt-5">
        {{-- ส่วนหัวข้อ --}}
        <div class="row mb-3">
            <div class="col">
                <h5>รายละเอียดเซตเครื่องประดับ</h5>
            </div>




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
            

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-info-circle"></i> รายละเอียดเซตเครื่องประดับ
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" data-toggle="modal"
                        data-target="#stopRentalModal"
                        @if ($is_admin == 1) 
                            @if ($check_not_ready == true)
                                style="display:none;"
                            @elseif($check_not_ready == false)
                                @if ($jewelryset->set_status == 'พร้อมให้เช่า')
                                    style="display:block;"
                                @elseif($jewelryset->set_status == 'ยุติการให้เช่า')
                                    style="display:none;" 
                                @endif
                            @endif
                    @elseif($is_admin == 0)
                        style="display:none;"
                        @endif
                        >
                        <i class="fas fa-stop"></i> ยุติการให้เช่า
                    </button>

                    <button type="button" class="btn btn-sm btn-outline-success" data-toggle="modal"
                    data-target="#reopenRentalModal"
                    @if ($is_admin == 1)
                        @if ($check_not_ready == true)
                            style="display:none;"
                        @elseif($check_not_ready == false)
                            @if ($jewelryset->set_status == 'พร้อมให้เช่า')
                                style="display:none;"
                            @elseif($jewelryset->set_status == 'ยุติการให้เช่า')
                                style="display:block;"
                            @endif
                        @endif
                    @elseif($is_admin == 0)
                        style="display:none;"
                    @endif

                    
                    >
                    <i class="fas fa-stop"></i> เปิดให้เช่าอีกครั้ง
                </button>

                    







                </div>


                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <p class="text-muted mb-1">ชื่อเซต :</p>
                            <p class="h6">{{ $jewelryset->set_name }}</p>
                        </div>
                    </div>

                    <div class="row col-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="text-muted mb-1">ราคาเช่ารวม :
                                    <button class="btn btn-link p-0 ml-2" data-toggle="modal" data-target="#edittotal"
                                        @if ($is_admin == 1) @if ($jewelryset->set_status == 'ยุติการให้เช่า')
                                    style="display: none ; " 
                                    @else

                                        style="display: inline-block ;" @endif
                                    @elseif($is_admin == 0) style="display: none ; " @endif
                                        >
                                        <i class="bi bi-pencil-square" style="color: rgb(138, 136, 136);"></i>
                                    </button>
                                </p>
                                <p class="h6">{{ number_format($jewelryset->set_price, 2) }} บาท</p>
                            </div>
                        </div>
                    </div>
                    @if ($jewelryset->set_status == 'ยุติการให้เช่า')
                        <div class="row col-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <p class="text-muted mb-1">สถานะ :</p>
                                    <p class="h6">ยุติการให้เช่า</p>
                                </div>
                            </div>
                        </div>
                    @elseif($jewelryset->set_status == 'พร้อมให้เช่า')
                        <div class="row col-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <p class="text-muted mb-1">สถานะ</p>
                                    <p class="h6"> ยังเปิดให้เช่า </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>


                <li>
                    <a href="{{ route('showjewsetrentedhistory', ['id' => $jewelryset->id]) }}" class="text-dark">
                        <i class="bi bi-clock-history"></i> ประวัติการเช่า
                    </a>

                </li>
                <li @if ($is_admin == 0) style="visibility: hidden;" @endif>
                    <a href="#" data-toggle="modal" data-target="#priceHistoryModal" class="text-dark">
                        <i class="bi bi-clock-history"></i> ประวัติการแก้ไขราคาเช่า
                    </a>
                </li>
            </div>






        </div>






        {{-- ส่วนตารางรายการเครื่องประดับ --}}
        <div class="card shadow">
            <div class="card-body">
                <h5 class="card-title mb-4">รายการเครื่องประดับในเซต{{ $jewelryset->set_name }}</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr style="text-align: center ; ">
                                <th>รูปภาพ</th>
                                <th>ชื่อรายการ</th>
                                <th>ราคาเช่า</th>
                                <th>สถานะ</th>
                                <th>รายละเอียด</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($Jewelrysetitem as $item)
                                <tr style="text-align: center ; ">
                                    <td>
                                        <img src="{{ asset('storage/' . $item->jewitem_m_to_o_jew->jewelryimages->first()->jewelry_image) }}"
                                            alt=""
                                            style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">
                                    </td>
                                    <td>

                                        {{ $item->jewitem_m_to_o_jew->jewelry_m_o_typejew->type_jewelry_name }}
                                        {{ $item->jewitem_m_to_o_jew->jewelry_m_o_typejew->specific_letter }}{{ $item->jewitem_m_to_o_jew->jewelry_code }}

                                    </td>
                                    <td>

                                        {{ number_format($item->jewitem_m_to_o_jew->jewelry_price, 2) }} บาท

                                    </td>
                                    <td>

                                        {{ $item->jewitem_m_to_o_jew->jewelry_status }}

                                    </td>


                                    <td>
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
                {{-- <i class="fas fa-exclamation-circle text-danger fa-3x mb-3"></i> --}}

                <p class="fs-5 mt-3">
                    คุณแน่ใจหรือไม่ว่าต้องการยุติการให้เช่าเซตเครื่องประดับนี้?
                    <span class="text-danger fw-bold">หากต้องการ สามารถเปิดให้เช่าอีกครั้งในภายหลังได้</span>
                </p>
                <!-- แสดงข้อความแจ้งเตือนหากมีลูกค้าจองชุดนี้ -->
            </div>


            @if ($stop_reservation->count() > 0)
                <!-- จำลองว่ามีลูกค้าจองอยู่ -->
                <div class="alert alert-warning text-start">

                    


                    <strong>มีลูกค้าที่จองเซตนี้ไว้ {{ $stop_reservation->count() }} คน</strong>
                    <ul class="mt-2">
                        @foreach ($stop_reservation as $item)
                            <li style="font-size : 14px;">
                                คุณ{{ $item->re_one_many_details->first()->order->customer->customer_fname }}
                                {{ $item->re_one_many_details->first()->order->customer->customer_lname }}
                                <span>(นัดรับวันที่
                                    {{ \Carbon\Carbon::parse($item->start_date)->isoFormat('D MMM') }}
                                    {{ \Carbon\Carbon::parse($item->start_date)->year + 543 }})</span>
                                
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
                <form action="{{ route('setjewelrystoprent', ['id' => $jewelryset->id]) }}" method="POST">
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

                            <form action="{{ route('admin.updatejewelryset', ['id' => $jewelryset->id]) }}"
                                method="POST">
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
                คุณต้องการเปิดให้เช่าเซตเครื่องประดับนี้อีกครั้งใช่หรือไม่?
                <span class="text-success fw-bold">หลังจากเปิดให้เช่าอีกครั้ง
                    ลูกค้าจะสามารถจองเซตเครื่องประดับนี้ได้ตามปกติ</span>
            </p>
        </div>
        <div class="modal-footer d-flex justify-content-center">
            <button type="button" class="btn btn-secondary px-4 py-2 rounded-pill" data-dismiss="modal">
                <i class="fas fa-times"></i> ยกเลิก
            </button>
            <form action="{{ route('setjewelryreopen', ['id' => $jewelryset->id]) }}" method="POST">
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
