@extends('layouts.adminlayout')
@section('content')
    <div class="container mt-4">
        <!-- กล่องแรก: ฟอร์มเพิ่มออเดอร์ -->
        <div class="shadow p-4 mb-5 bg-white rounded">
            <h4 class="mb-4">ตะกร้าสินค้า ({{ $order->total_quantity ?? 0 }})</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">ลำดับ</th>
                        <th scope="col">รายการ</th>
                        <th scope="col">ราคาต่อชิ้น</th>
                        <th scope="col">จำนวน</th>
                        <th scope="col">ราคารวม</th>
                        <th scope="col">Action</th>

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
                                    <a href="{{route('employee.manageitem',['id' => $detail->id])}}" class="btn btn-warning btn-sm">จัดการ</a>
                                    <form action="{{route('employee.deletelist',['id' => $detail->id])}}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('แน่ใจใช่ไหมว่าคุณต้องการนำออก?')">นำออก</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        
                        <tr>
                            <th scope="row">รวม</th>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{ $order->total_price }}</td>
                            <td>
                                <button class="btn btn-danger btn-sm">ยืนยันการเพิ่มออเดอร์</button>
                            </td>
                        </tr>
                    @endif


                </tbody>
            </table>
        </div>
    </div>
@endsection
