@extends('layouts.adminlayout')

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="">หน้าแรก</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employee.ordertotal') }}">รายการออเดอร์ทั้งหมด</a></li>
        <li class="breadcrumb-item active">รายละเอียดออเดอร์ที่ {{ $order_id }}</li>
    </ol>

    <div class="container mt-5">
        <h3 style="text-align: center ; ">ออเดอร์ที่ {{ $order_id }}</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ประเภทบริการ</th>
                    <th>วันนัดรับชุด</th>
                    <th>วันนัดคืนชุด</th>
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
                            @elseif($orderdetail->type_order)
                                เช่าตัด
                            @endif
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($orderdetail->pickup_date)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($orderdetail->pickup_date)->year + 543 }}
                            <p style="color: rgb(195, 23, 23);" id="showday{{$orderdetail->id}}"></p>
                            <script>
                                var pickup_date = new Date("{{$orderdetail->pickup_date}}") ; 
                                var now = new Date() ; //วันที่ปัจจุบัน

                                var day = pickup_date - now ; 
                                var totalday = Math.ceil(day / (1000 * 60 * 60 *24)) ; 

                                document.getElementById('showday{{$orderdetail->id}}').innerHTML = 'เหลืออีก '  +totalday+ ' วัน ' ; 



                            </script>
                        </td>
                        <td>
                            @if ($orderdetail->return_date)
                                {{ \Carbon\Carbon::parse($orderdetail->return_date)->locale('th')->isoFormat('D MMM') }}
                                {{ \Carbon\Carbon::parse($orderdetail->return_date)->year + 543 }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            {{ $orderdetail->status_detail }}
                        </td>

                        {{-- <td>
                            @if ($orderdetail->status_fix_measurement == 'รอการแก้ไข')
                                <span class="badge bg-warning text-dark">รอการปรับแก้</span>
                            @elseif($orderdetail->status_fix_measurement == 'แก้ไขแล้ว')
                                <span class="badge bg-success text-white">ปรับแก้ชุดแล้ว</span>
                            @else
                                <span class="badge bg-secondary text-white">ไม่มีการแก้ไข</span>
                            @endif
                        </td> --}}

                        <td>
                            <a href="{{ route('employee.ordertotaldetailshow', ['id' => $orderdetail->id]) }}"
                                class="btn btn-info btn-sm">จัดการ</a>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
@endsection