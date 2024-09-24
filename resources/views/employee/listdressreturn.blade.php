@extends('layouts.adminlayout')

@section('content')
    <h1 class="text-center my-4" style="color: #3d3d3d; font-family: 'Prompt', sans-serif; font-weight: 600;">
        "คิวการจัดเตรียมชุดสำหรับลูกค้า"
    </h1>

    <div class="alert alert-info" role="alert" style="font-family: 'Prompt', sans-serif;">
        <h4 class="alert-heading">คำแนะนำสำหรับพนักงาน</h4>
        <p>รายการนี้แสดงการรับคืนชุดจากลูกค้า โดยเรียงตามวันที่ลูกค้าจะมาคืนชุด</p>
        {{-- <hr>
        <p class="mb-0">กรุณาจัดเตรียมชุดตามลำดับคิว <strong>คิวที่ 1 <span style="color: red;">&#9733;</span>
                มีความสำคัญสูงสุดและต้องจัดเตรียมก่อน</strong></p> --}}
    </div>
    @if($listdressreturns->count() > 0 )
    <table class="table shadow-sm" style="width: 100%; background-color: #ffffff; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>ลำดับ</th>
                <th>ชุด</th>
                <th>ชื่อลูกค้า</th>
                <th>วันที่คืนชุด</th>
                <th>ค่าปรับ(หากล่าช้า)</th>
                <th>สถานะชุด</th>
                <th>ดูรายละเอียด</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($listdressreturns as $index => $reservation)
                <tr style="border-bottom: 1px solid #e6e6e6;">

                    @php
                        $orderdetail = App\Models\Orderdetail::where('reservation_id', $reservation->id)->first();
                        $customer_id = App\Models\Order::where('id', $orderdetail->order_id)->value('customer_id');
                        $customer = App\Models\Customer::find($customer_id);
                    @endphp



                    <td>
                        {{ $index + 1 }}
                    </td>
                    <td>

                        @php
                            $dress = App\Models\Dress::where('id', $reservation->dress_id)->first();
                            $typedress = App\Models\Typedress::where('id', $dress->type_dress_id)->first();
                        @endphp
                        {{ $typedress->type_dress_name }} {{ $dress->dress_code_new }}{{ $dress->dress_code }}

                        @if ($reservation->shirtitems_id)
                            (เสื้อ)
                        @elseif($reservation->skirtitems_id)
                            (ผ้าถุง)
                        @elseif($reservation->dress_id)
                            (ทั้งชุด)
                        @endif
                    </td>

                    <td>
                        คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }}
                    </td>

                    <td style="width: 120px;">
                        {{ \Carbon\Carbon::parse($reservation->end_date)->locale('th')->isoFormat('D MMM') }}
                        {{ \Carbon\Carbon::parse($reservation->end_date)->year + 543 }}
                        <span style="color: red ; " id="showday{{ $reservation->id }}"></span>
                    </td>
                    <td style="width: 300px;">
                        <p id="late{{ $reservation->id }}"></p>
                    </td>
                    <script>
                        var end_date = new Date('{{ $reservation->end_date }}');
                        var now = new Date();
                        var day = end_date - now;
                        var totalday = Math.ceil(day / (1000 * 60 * 60 * 24));
                        document.getElementById('showday{{ $reservation->id }}').innerHTML
                        if (totalday == 0) {
                            document.getElementById('showday{{ $reservation->id }}').innerHTML = "คืนชุดวันนี้";
                            document.getElementById('late{{ $reservation->id }}').innerHTML = '-';
                        } else if (totalday < 0) {
                            document.getElementById('showday{{ $reservation->id }}').innerHTML = "เลยกำหนด " + Math.abs(totalday) + ' วัน';
                            document.getElementById('late{{ $reservation->id }}').innerHTML = 300 * Math.abs(totalday) + ' บาท';
                            console.log(typeof(totalday));
                        } else {
                            document.getElementById('showday{{ $reservation->id }}').innerHTML = 'อีก ' + totalday + ' วัน';
                            document.getElementById('late{{ $reservation->id }}').innerHTML = '-';
                        }
                    </script>
                    <td>
                        <span style="color: darkorange;">{{ $reservation->status }}</span>
                    </td>
                    <td style="padding: 16px;">
                        <a href="{{ route('employee.ordertotaldetailshow', ['id' => $orderdetail->id]) }}"
                            class="btn btn-primary" style="padding: 6px 12px;">
                            ดูรายละเอียด
                        </a>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="text-align: center ; ">ไม่มีรายการแสดงผล</p>
    @endif
@endsection
