@extends('layouts.adminlayout')
@section('content')
    <style>
        .form-control {
            border-radius: 25px;
            border: 1px solid #ced4da;
            padding: 12px 20px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #80bdff;
            box-shadow: none;
        }

        .btn-s {
            border-radius: 20px;
            background-color: #007bff;
            border: none;
            padding: 5px 15px;
            display: flex;
            align-items: center;
            transition: background-color 0.3s ease;
        }

        .btn-s:hover {
            background-color: #0056b3;
        }

        .btn-s i {
            margin-right: 2px;
            font-size: 14px;
        }
    </style>
    <div class="container  mt-5">
        <div class="col">
            <h1 class="font-bold">รายการออเดอร์ทั้งหมด</h1>
        </div>



        <div class="card mb-4">





            <div class="card-body">
                <table id="ordersTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>ประเภทออเดอร์</th>
                            <th style="width: 200px;">วันที่ทำรายการ</th>
                            <th>หมายเลขออเดอร์</th>
                            <th>ชื่อ-สกุลลูกค้า</th>
                            {{-- <th>ยอดรวม</th> --}}
                            <th>รายการ</th>
                            <th>รายละเอียด</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order as $item)
                            <tr>
                                <td>
                                    @if ($item->type_order == 1)
                                        ตัดชุด
                                    @elseif($item->type_order == 2)
                                        เช่า
                                    @elseif($item->type_order == 3)
                                        เช่าตัด
                                    @endif
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($item->created_at)->locale('th')->isoFormat('D MMM') }}
                                    {{ \Carbon\Carbon::parse($item->created_at)->year + 543 }}
                                    <span style="font-size: 12px; color: red ; " id="show_day{{ $item->id }}">(2
                                        วันที่แล้ว)</span>

                                    <script>
                                        var created_at_order = new Date('{{ $item->created_at }}');
                                        var now = new Date();
                                        var total_days = Math.ceil((now - created_at_order) / (1000 * 60 * 60 * 24) - 1);
                                        if (total_days == 0) {
                                            document.getElementById('show_day{{ $item->id }}').innerHTML = '(วันนี้)';

                                        } else {
                                            document.getElementById('show_day{{ $item->id }}').innerHTML = '( ' + total_days + ' วันที่แล้ว )';

                                        }
                                    </script>


                                </td>
                                <td>OR{{ $item->id }}</td>
                                <td>คุณ{{ $item->customer->customer_fname }} {{ $item->customer->customer_lname }}</td>
                                {{-- <td>
                                            @php
                                                $orderdetail_price = App\Models\Orderdetail::where(
                                                    'order_id',
                                                    $order->id,
                                                )->sum('price');
                                            @endphp

                                            {{ number_format($orderdetail_price) }}
                                        </td> --}}
                                <td>{{ $item->total_quantity }} รายการ</td>
                                <td>
                                    <a href="{{ route('employee.ordertotaldetail', ['id' => $item->id]) }}"
                                        class="btn btn-sm" style="background-color:#DADAE3;">
                                        ดูรายละเอียด
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- ปุ่มเปลี่ยนหน้า -->
                <div class="d-flex justify-content-center">
                    {!! $order->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
