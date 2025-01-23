@extends('layouts.adminlayout')
@section('content')
    {{-- views/notifications/lost-items.blade.php --}}

    <div class="container mt-4">
        <div class="alert alert-danger" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <div>
                    <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4>
                    <p class="mb-0">พบรายการที่ได้รับผลกระทบจากสินค้าสูญหาย กรุณาติดต่อลูกค้าเพื่อแจ้งยกเลิกและคืนเงินมัดจำ
                    </p>
                </div>
            </div>
        </div>


        <div class="card mb-4">
            <div class="card-header bg-white">

            </div>
            <div class="card-body">


                <h6 class="mb-3">รายการจองที่ได้รับผลกระทบ:</h6>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>รายการ</th>
                                <th>ชื่อลูกค้า</th>
                                <th>เบอร์โทร</th>
                                <th>วันนัดรับ</th>
                                <th class="text-center">การดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderdetail as $item)
                                <tr>
                                    <td>
                                        @if ($item->detail_many_one_re->jewelry_id)
                                            เช่า{{ $item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->type_jewelry_name }}
                                            {{ $item->detail_many_one_re->resermanytoonejew->jewelry_m_o_typejew->specific_letter }}{{ $item->detail_many_one_re->resermanytoonejew->jewelry_code }}
                                        @elseif($item->detail_many_one_re->jewelry_set_id)
                                            เช่าเซตเครื่องประดับ{{ $item->detail_many_one_re->resermanytoonejewset->set_name }}
                                        @endif
                                    </td>
                                    <td>
                                        คุณ{{ $item->order->customer->customer_fname }}
                                        {{ $item->order->customer->customer_lname }}
                                    </td>
                                    <td>
                                        {{ $item->order->customer->customer_phone }}
                                    </td>
                                    <td>
                                        @php
                                            $date = App\Models\Date::where('order_detail_id', $item->id)
                                                ->orderBy('created_at', 'desc')
                                                ->first();
                                        @endphp
                                        {{ \Carbon\Carbon::parse($date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($date->pickup_date)->year + 543 }}
                                    </td>

                                    <td class="text-center">
                                        {{-- <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#cancelModal{{$item->id}}">
                                            ยกเลิกการจอง
                                        </button> --}}
                                        <a href="{{ route('employee.ordertotaldetail', ['id' => $item->order_id]) }}"
                                            class="btn btn-sm" style="background-color:#DADAE3;">
                                            ดูรายละเอียด
                                        </a>
                                    </td>



                                    {{-- Modal ยืนยันการยกเลิก --}}
                                    <div class="modal fade" data-backdrop="static" id="cancelModal{{$item->id}}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">ยืนยันการยกเลิกรายการ</h5>
                                                    <button type="button" class="btn-close"
                                                        data-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>คุณต้องการยกเลิกรายการนี้และคืนเงินมัดจำให้ลูกค้าใช่หรือไม่?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">ยกเลิก</button>
                                                    <form id="cancelDetailForm" method="POST" action="">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit"
                                                            class="btn btn-danger">ยืนยันการยกเลิก</button>
                                                    </form>
                                                </div>
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

    {{-- Modal ยืนยันการยกเลิก --}}
    <div class="modal fade" id="cancelModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ยืนยันการยกเลิกรายการ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>คุณต้องการยกเลิกรายการนี้และคืนเงินมัดจำให้ลูกค้าใช่หรือไม่?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <form id="cancelDetailForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger">ยืนยันการยกเลิก</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
