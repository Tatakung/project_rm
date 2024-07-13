@extends('layouts.adminlayout')

@section('content')
<div class="container mt-5">
    <h3 class="text-center mb-4">ออเดอร์ทั้งหมด</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr style="text-align: center">
                    <th>เลขที่บิล</th>
                    <th>วันที่ทำรายการ</th>
                    <th>ชื่อ-สกุลลูกค้า</th>
                    <th>รายการทั้งหมด</th>
                    <th>ดูรายละเอียด</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($customers as $customer)
                    @foreach ($customer->orders as $order)
                        <tr style="text-align: center">
                            @if($order->order_status == 1)
                            <td>{{$order->id}}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($order->created_at)->locale('th')->isoFormat('D MMM') }} 
                                {{ \Carbon\Carbon::parse($order->created_at)->year + 543 }} 
                            </td>
                            
                            <td>คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }}</td>
                            
                            <td>{{ $order->total_quantity }}</td>
                            <td>
                                {{-- {{$order->id}} --}}
                                <a href="{{route('employee.ordertotaldetail',['id' => $order->id])}}" class="btn btn-info btn-sm">
                                    ดูรายละเอียด
                                </a>
                            </td>

                            @endif




                        </tr>
                    @endforeach
                @endforeach

            </tbody>
        </table>
    </div>
</div>
@endsection
