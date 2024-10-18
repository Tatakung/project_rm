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

    .btn-primary {
        border-radius: 20px;
        background-color: #007bff;
        border: none;
        padding: 5px 15px;
        display: flex;
        align-items: center;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-primary i {
        margin-right: 2px;
        font-size: 14px;
    }
</style>
<div class="container  mt-5">
        <div class="col">
            <h1 class="font-bold">รายการออเดอร์ทั้งหมด</h1>
        </div>



    <div class="card mb-4">

        <div class="card-header ">
            <form action="{{ route('employee.searchordertotal') }}" class="d-flex justify-content-end">
                <div class="row">
                    <div class="col-8">
                        <input type="text" name="name_search" class="form-control" placeholder="กรอกชื่อ" value="{{$name_search}}">
                    </div>
                    <div class="col">
                        <button class="btn btn-primary">
                            <i class="bi bi-search"></i> ค้นหา
                        </button>
                    </div>
                </div>
            </form>

        </div>



        <div class="card-body">
            <table id="ordersTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>หมายเลขออเดอร์</th>
                        <th>วันที่ทำรายการ</th>
                        <th>ชื่อ-สกุลลูกค้า</th>
                        <th>ยอดรวม</th>
                        <th>รายการ</th>
                        <th>รายละเอียด</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                    @foreach ($customer->orders as $order)
                    @if ($order->order_status == 1)
                    <tr>
                        <td>OR{{ $order->id }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($order->created_at)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($order->created_at)->year + 543 }}
                        </td>









                        
                        <td>คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }}</td>
                        <td>{{ number_format($order->total_price, 2) }} บาท</td>
                        <td>{{ $order->total_quantity }} รายการ</td>
                        <td>
                            <a href="{{ route('employee.ordertotaldetail', ['id' => $order->id]) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> ดูรายละเอียด
                            </a>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#ordersTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Thai.json"
            },
            "order": [
                [1, "desc"]
            ],
            "pageLength": 25
        });
    });
</script>
@endsection