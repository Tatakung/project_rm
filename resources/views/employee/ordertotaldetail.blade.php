@extends('layouts.adminlayout')

@section('content')
<style>
        .btn-c{
        background-color: #EBDE88;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
</style>
    <ol class="breadcrumb" style="background-color: transparent; ">
        <li class="breadcrumb-item"><a href=""style="color: black ;">หน้าแรก</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employee.ordertotal') }}" style="color: black ;">รายการออเดอร์ทั้งหมด</a></li>
        <li class="breadcrumb-item active">รายละเอียดออเดอร์ที่ {{ $order_id }}</li>
    </ol>

    <div class="container mt-5">
        <h3 style="text-align:start ; ">รายละเอียดของ OR{{ $order_id }}</h3>
        <table class="table table-striped ">
            <thead>
                <tr>
                    <th>ประเภทบริการ</th>
                    <th>วันนัดรับ</th>
                    <th>วันนัดคืน</th>
                    <th>สถานะออเดอร์</th>
                    {{-- <th>สถานะการปรับแก้ชุด</th> --}}
                    <th>การดำเนินการ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orderdetail as $index => $orderdetail)
                    <tr>
                        <td>
                            @if ($orderdetail->type_order == 1)
                                🪡 ตัดชุด
                            @elseif($orderdetail->type_order == 2)
                                🎭 เช่าชุด
                            @elseif($orderdetail->type_order == 3)
                                เช่าเครื่องประดับ
                            @elseif($orderdetail->type_order == 4 )
                                เช่าตัด
                            @endif
                        </td>
                        <td>

                            @php
                                $DATE = App\Models\Date::where('order_detail_id',$orderdetail->id)
                                            ->orderBy('created_at','desc')
                                            ->first() ; 
                            @endphp     

                            {{ \Carbon\Carbon::parse($DATE->pickup_date)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($DATE->pickup_date)->year + 543 }}
                            
                        </td>
                        <td>
                            @if ($DATE->return_date)
                                {{ \Carbon\Carbon::parse($DATE->return_date)->locale('th')->isoFormat('D MMM') }}
                                {{ \Carbon\Carbon::parse($DATE->return_date)->year + 543 }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            {{ $orderdetail->status_detail }}
                        </td>
                        <td>
                            <a href="{{ route('employee.ordertotaldetailshow', ['id' => $orderdetail->id]) }}"
                                class="btn btn-c btn-sm">จัดการ</a>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
@endsection