@extends('layouts.adminlayout')
@section('content')

    @php
        $list_order_detail_id = [] ; //สร้าง list ขึ้นมาเก็ฐข้อมูล order_detail_id 
    @endphp
    <div class="container mt-4">
        <!-- กล่องแรก: ฟอร์มเพิ่มออเดอร์ -->
        <div class="shadow p-5 mb-5 bg-white rounded">
            <h4 class="mb-4">ตะกร้าสินค้า ({{ $order->total_quantity ?? 0 }})</h4>
            @if($order)
            <div class="customer-info p-4 mb-4 bg-light rounded">
                <h5 class="mb-4">ข้อมูลลูกค้า</h5>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="firstName" class="form-label">ชื่อ</label>
                        <input type="text" name="firstName" id="firstName" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="lastName" class="form-label">นามสกุล</label>
                        <input type="text" name="lastName" id="lastName" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="phone" class="form-label">เบอร์ติดต่อ</label>
                        <input type="text" name="phone" id="phone" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button class="btn btn-warning w-100">บันทึก</button>
                    </div>
                </div>
            </div>
            @endif
            
            
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">ลำดับ</th>
                        <th scope="col">รายการ</th>
                        <th scope="col">ราคาต่อชิ้น</th>
                        <th scope="col">จำนวน</th>
                        <th scope="col">ราคารวม</th>
                        <th scope="col">Action</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @if ($order)
                        @foreach ($order->order_one_many_orderdetails as $index => $detail)
                            <tr>
                                <th scope="row">{{ $index + 1 }}</th>
                                <td>{{ $detail->title_name }}</td>
                                <td>{{ $detail->price }}</td>
                                <td>{{ $detail->amount }}</td>
                                <td>{{ number_format($detail->price * $detail->amount, 2) }}</td>
                                <td>
                                    <form action="{{ route('employee.manageitem', ['id' => $detail->id]) }}" method="GET"
                                        style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="type_order" value="{{ $detail->type_order }}">
                                        <button type="submit" class="btn btn-warning btn-sm">จัดการ</button>
                                    </form>




                                    <form action="{{ route('employee.deletelist', ['id' => $detail->id]) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('แน่ใจใช่ไหมว่าคุณต้องการนำออก?')">นำออก</button>
                                    </form>
                                </td>
                                @php
                                        $list_order_detail_id[] = $detail->id ; 
                                @endphp
                            </tr>
                        @endforeach
                        
                            <tr>
                                <th scope="row">รวม</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{ number_format($order->total_price, 2) }}</td>
                                <td>
                                    <form action="{{ route('employee.confirmorder', ['id' => $order->id]) }}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="order_detail_id" id="order_detail_id" value="{{ json_encode($list_order_detail_id) }}">
                                        <button class="btn btn-danger btn-sm" type="submit">ยืนยันการเพิ่มออเดอร์</button>
                                    </form>
                                </td>
                                
                            </tr>
                        
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
