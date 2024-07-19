@extends('layouts.adminlayout')

@section('content')
<div class="container mt-5">
    <h3 class="text-center mb-4">ออเดอร์บิลที่ {{$order_id}}</h3>
    <div class="d-flex justify-content-between mb-3">
        {{-- <button class="btn btn-danger">พิมพ์บิลรวม</button>
        <button class="btn btn-danger">พิมพ์สัญญาเช่าชุด/เครื่องประดับ(กรณีเช่า)</button> --}}
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr class="text-center">
                    <th>ลำดับที่</th>
                    <th>ประเภทออเดอร์</th>
                    <th>วันนัดรับชุด</th>
                    <th>วันนัดคืนชุด</th>
                    <th>สถานะออเดอร์</th>
                    <th>จำนวนที่เช่า</th>
                    <th>รายละเอียดบิล</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orderdetail as $index => $orderdetail)
                <tr class="text-center">
                    <td>{{$index + 1}}</td>
                    <td style="color: coral">
                        @if ($orderdetail->type_order == 1)
                            ตัดชุด
                        @elseif($orderdetail->type_order == 2)
                            เช่าชุด
                        @elseif($orderdetail->type_order == 3)
                            เช่าเครื่องประดับ
                        @elseif($orderdetail->type_order)
                            เช่าตัด
                        @endif
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($orderdetail->pickup_date)->locale('th')->isoFormat('D MMM') }}
                        {{ \Carbon\Carbon::parse($orderdetail->pickup_date)->year + 543 }}
                    </td>
                    <td>
                        @if($orderdetail->return_date)
                        {{ \Carbon\Carbon::parse($orderdetail->return_date)->locale('th')->isoFormat('D MMM') }}
                        {{ \Carbon\Carbon::parse($orderdetail->return_date)->year + 543 }}
                        @else
                        -
                        @endif
                    </td>
                    {{-- <td>{{$orderdetail->return_date}}</td> --}}
                    <td style="color: rebeccapurple">{{ $orderdetail->status_detail }}</td>
                    <td>{{ $orderdetail->amount }}</td>
                    <td>
                        <a href="{{route('employee.ordertotaldetailshow',['id'=>$orderdetail->id])}}" class="btn btn-info btn-sm">จัดการ</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
