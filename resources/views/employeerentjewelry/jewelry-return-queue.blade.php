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
            คิวรอลูกค้าส่งคืนเครื่องประดับ
        </h1>

        <p>รายการนี้แสดงการรับคืนเครื่องประดับจากลูกค้า โดยเรียงตามวันที่ลูกค้าจะมาคืน</p>
        <div class="row mb-3 ">
            <div class="col-md-12" style="text-align: right ; ">
                {{-- <button>
                    เฉพาะวันนี้
                </button>
                <button>
                    ทั้งหมด
                </button> --}}





                <form action="{{ route('showreturnqueuejewelryfilter') }}" method="GET">
                    @csrf
                    <div class="filter-buttons">
                        <button class="btn" type="submit" name="filter_click" value="today"
                            @if ($filer == 'today') style="border: 1px solid #ccc;background-color: rgb(238, 77, 45) ; color: #ffffff ;"
                        @else
                        style="border: 1px solid #ccc;" @endif>เฉพาะวันนี้</button>
                        <button class="btn" type="submit" name="filter_click" value="total"
                            @if ($filer == 'total') style="border: 1px solid #ccc;background-color: rgb(238, 77, 45) ; color: #ffffff ;"
                        @else
                        style="border: 1px solid #ccc;" @endif>ทั้งหมด</button>

                    </div>
                </form>


            </div>
        </div>





        @if ($listdressreturns->count() > 0)
            <table class="table shadow-sm" style="width: 100%; ">
                <thead>
                    <tr style="background-color: #f2f2f2;">
                        <th>รายการ</th>
                        <th>ชื่อลูกค้า</th>
                        <th>วันที่นัดคืน</th>
                        {{-- <th>ค่าปรับ(หากล่าช้า)</th> --}}
                        <th>สถานะ</th>
                        <th>ดูรายละเอียด</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listdressreturns as $index => $reservation)
                        @if ($reservation->re_one_many_details->first() && $reservation->re_one_many_details->first()->type_order == 3)
                            <tr style="border-bottom: 1px solid #e6e6e6;">

                                @php
                                    $orderdetail = App\Models\Orderdetail::where(
                                        'reservation_id',
                                        $reservation->id,
                                    )->first();
                                    $customer_id = App\Models\Order::where('id', $orderdetail->order_id)->value(
                                        'customer_id',
                                    );
                                    $customer = App\Models\Customer::find($customer_id);
                                @endphp



                                <td>

                                    @if ($reservation->jewelry_id)
                                        เช่า{{ $reservation->resermanytoonejew->jewelry_m_o_typejew->type_jewelry_name }}
                                        {{ $reservation->resermanytoonejew->jewelry_m_o_typejew->specific_letter }}{{ $reservation->resermanytoonejew->jewelry_code }}
                                    @elseif($reservation->jewelry_set_id)
                                        เช่าเซต{{ $reservation->resermanytoonejewset->set_name }}
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
                                {{-- <td style="width: 300px;">
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
                                        document.getElementById('showday{{ $reservation->id }}').innerHTML = "คืนวันนี้";
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
                                    <a href="{{ route('employee.ordertotaldetailshow', ['id' => $orderdetail->id]) }}"
                                        class="btn btn-s" style="background-color:#DADAE3;">
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
