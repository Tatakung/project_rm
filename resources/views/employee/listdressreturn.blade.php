@extends('layouts.adminlayout')

@section('content')


<style>
    .btn-s {
        border-radius: 10px;
    }

    .btn-s:hover {
        background-color: #0056b3;
    }

    .btn-s i {
        margin-right: 2px;
        font-size: 14px;
    }
</style>





<div class="container">
    <h1 class="text-start my-4">
        คิวรอลูกค้าส่งคืนชุด
    </h1>

    <p>รายการนี้แสดงการรับคืนชุดจากลูกค้า โดยเรียงตามวันที่ลูกค้าจะมาคืนชุด</p>
    <div class="row mb-3 ">
        <div class="col-md-12" style="text-align: right ; ">
            {{-- <button>
                    เฉพาะวันนี้
                </button>
                <button>
                    ทั้งหมด
                </button> --}}





            <form action="{{route('employee.listdressreturnfilter')}}" method="GET">
                @csrf
                <div class="filter-buttons">
                    <button class="btn" type="submit" name="filter_click" value="today"
                        @if ($filer=='today' ) style="border: 1px solid #ccc;background-color: rgb(238, 77, 45) ; color: #ffffff ;"
                        @else
                        style="border: 1px solid #ccc;" @endif>เฉพาะวันนี้</button>
                    <button class="btn" type="submit" name="filter_click" value="total"
                        @if ($filer=='total' ) style="border: 1px solid #ccc;background-color: rgb(238, 77, 45) ; color: #ffffff ;"
                        @else
                        style="border: 1px solid #ccc;" @endif>ทั้งหมด</button>

                </div>
            </form>


        </div>
    </div>





@if($listdressreturns->count() > 0 )
<table class="table shadow-sm" style="width: 100%; ">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th>ชุด</th>
            <th>ชื่อลูกค้า</th>
            <th>วันที่คืนชุด</th>
            {{-- <th>ค่าปรับ(หากล่าช้า)</th> --}}
            <th>สถานะชุด</th>
            <th>ดูรายละเอียด</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($listdressreturns as $index => $reservation)
        {{-- @if ($reservation->re_one_many_details->first() && $reservation->re_one_many_details->first()->type_order == 2) --}}
        @if (
            $reservation->re_one_many_details->first() && in_array($reservation->re_one_many_details->first()->type_order, [2, 4]))
        <tr style="border-bottom: 1px solid #e6e6e6;">

            @php
            $orderdetail = App\Models\Orderdetail::where('reservation_id', $reservation->id)->first();
            $customer_id = App\Models\Order::where('id', $orderdetail->order_id)->value('customer_id');
            $customer = App\Models\Customer::find($customer_id);
            @endphp



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

            <td >
                {{ \Carbon\Carbon::parse($reservation->end_date)->locale('th')->isoFormat('D MMM') }}
                {{ \Carbon\Carbon::parse($reservation->end_date)->year + 543 }}
                <span style="color: red ; " id="showday{{ $reservation->id }}"></span>
            </td>
            {{-- <td >
                <p id="late{{ $reservation->id }}"></p>
            </td> --}}
            <script>
                var end_date = new Date('{{ $reservation->end_date }}');
                end_date.setHours(0, 0, 0, 0);
                var now = new Date();
                now.setHours(0, 0, 0, 0);
                var day = end_date - now;
                var totalday = Math.ceil(day / (1000 * 60 * 60 * 24));


                document.getElementById('showday{{ $reservation->id }}').innerHTML
                if (totalday == 0) {
                    document.getElementById('showday{{ $reservation->id }}').innerHTML = "คืนชุดวันนี้";
                    document.getElementById('late{{ $reservation->id }}').innerHTML = '-';
                } else if (totalday < 0) {
                    document.getElementById('showday{{ $reservation->id }}').innerHTML = "เลยกำหนด " + Math.abs(totalday) + ' วัน';
                    document.getElementById('late{{ $reservation->id }}').innerHTML = 200 * Math.abs(totalday) + ' บาท';
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
                {{-- <a href="{{ route('employee.ordertotaldetailshow', ['id' => $orderdetail->id]) }}"
                    class="btn btn-s" style="background-color:#DADAE3;">
                    ดูรายละเอียด
                </a> --}}


                <a href="{{ route('employee.ordertotaldetail', ['id' => $orderdetail->order_id]) }}"
                    class="btn btn-sm" style="background-color:#DADAE3;">
                    ดูรายละเอียด
                </a>




            </td>










        </tr>
        @endif
        @endforeach
    </tbody>
</table>
@else
<p style="text-align: center ; ">ไม่มีรายการแสดงผล</p>
</div>
@endif
@endsection