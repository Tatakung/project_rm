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
    .btn-m {
        border-radius: 10px;
    }

    .btn-m:hover {
        background-color: #0056b3;
    }

    .btn-m i {
        margin-right: 2px;
        font-size: 14px;
    }
</style>



<div class="container">
    <h1 class="text-start my-4" style="color: #3d3d3d;">
        คิวการจัดเตรียมชุดสำหรับลูกค้า
    </h1>
    <p>รายการนี้แสดงลำดับคิวการจัดเตรียมชุดสำหรับลูกค้า โดยเรียงตามวันที่ลูกค้าจะมารับ</p>
    <div class="row ">
        <div class="col-md-12" style="text-align: right ; ">
            {{-- <button>
                    เฉพาะวันนี้
                </button>
                <button>
                    ทั้งหมด
                </button> --}}


            <form action="{{ route('employee.dressadjustfilter') }}" method="GET">
                @csrf
                <div class="filter-buttons mb-3">
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



    @if ($reservations->count() > 0)
    <table class="table shadow-sm" style="width: 100%; background-color: #ffffff; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">วันที่นัดรับ</th>
                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">ชุด</th>
                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">ชื่อลูกค้า</th>
                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">สถานะชุด</th>
                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">จัดการ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reservations as $index => $reservation)
            @php
            $orderdetail = App\Models\Orderdetail::where('reservation_id', $reservation->id)->first();
            $customer_id = App\Models\Order::where('id', $orderdetail->order_id)->value('customer_id');
            $customer = App\Models\Customer::find($customer_id);
            $dress_mea_adjust = App\Models\Dressmeaadjustment::where(
            'order_detail_id',
            $orderdetail->id,
            )->get();

            $validate = false;
            foreach ($dress_mea_adjust as $index => $dressmeaadjust) {
            $dressmea = App\Models\Dressmea::where('id', $dressmeaadjust->dressmea_id)->value(
            'current_mea',
            );
            if ($dressmea != $dressmeaadjust->new_size) {
            $validate = true;
            }
            }
            if ($validate) {
            $edit_message_mea = 'รอการปรับแก้ขนาด';
            } else {
            $edit_message_mea = 'ไม่ต้องปรับแก้ขนาด';
            }

            $dress = App\Models\Dress::where('id', $reservation->dress_id)->first();
            $type_dress = App\Models\Typedress::where('id', $dress->type_dress_id)->first();
            @endphp

            <tr style="border-bottom: 1px solid #e6e6e6;">
                <td style="padding: 16px;">
                    {{ \Carbon\Carbon::parse($reservation->start_date)->locale('th')->isoFormat('D MMM') }}
                    {{ \Carbon\Carbon::parse($reservation->start_date)->year + 543 }}
                    <p id="showday{{ $reservation->id }}" class="mt-2" style="color: #8d1e1e;"></p>

                    <script>
                        var now = new Date();
                        now.setHours(0, 0, 0, 0); // ตั้งเวลาให้เป็น 00:00:00
                        var start_date = new Date("{{ $reservation->start_date }}");
                        start_date.setHours(0, 0, 0, 0);

                        console.log(start_date);
                        var day = start_date - now;
                        var totalday = Math.ceil(day / (1000 * 60 * 60 * 24));


                        if (totalday > 0) {
                            document.getElementById('showday{{ $reservation->id }}').innerHTML = "เหลืออีก " + totalday + ' วัน ';
                        } else if (totalday == 0) {
                            document.getElementById('showday{{ $reservation->id }}').innerHTML = "มารับชุดวันนี้ ";
                        } else {
                            document.getElementById('showday{{ $reservation->id }}').innerHTML = "เลยวันนัดรับชุด " + Math.abs(totalday) + ' วัน ';

                        }
                    </script>
                </td>


                @php
                $list_one = [];
                $find_dress = App\Models\Reservation::where('status_completed', 0)
                ->where('status', 'ถูกจอง')
                ->where('dress_id', $reservation->dress_id)
                ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                ->get();

                foreach ($find_dress as $key => $value) {
                $list_one[] = $value->id;
                }
                $total = App\Models\Reservation::whereIn('id', $list_one)
                ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                ->get();

                foreach ($total as $index_dress => $item) {
                if ($item->id == $reservation->id) {
                $number = $index_dress + 1;
                break;
                }
                }
                @endphp

                <td style="padding: 16px;">
                    เช่า {{ $type_dress->type_dress_name }} {{ $dress->dress_code_new }}{{ $dress->dress_code }}
                    <span>
                        @if ($reservation->shirtitems_id)
                        <span>(เสื้อ)</span>
                        @elseif($reservation->skirtitems_id)
                        <span>(กระโปรง/ผ้าถุง)</span>
                        @else
                        <span>(ทั้งชุด)</span>
                        @endif
                    </span>
                    <span class="d-block mt-2" style="font-size: 14px;">
                        @if ($validate)
                        <span style="color: #CC2828; font-size: 14px;">- รอการปรับแก้ขนาด</span>
                        @else
                        <span style="color: #28a745; font-size: 14px;">- ไม่ต้องปรับแก้ขนาด</span>
                        @endif
                    </span>
                </td>

                <td style="padding: 16px;">
                    คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }}
                </td>


                <td style="width: 200px;">
                    @php
                    $status_now = App\Models\Reservation::where('status_completed', 0)
                    ->where('dress_id', $reservation->dress_id)
                    ->whereNull('shirtitems_id')
                    ->whereNull('skirtitems_id')

                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                    ->first();
                    @endphp
                    @if ($reservation->shirtitems_id)
                    @php
                    // ตรวจสอบเฉพาะเสื้อก่อน
                    $status_shirt = App\Models\Reservation::where('status_completed', 0)
                    ->where('dress_id', $reservation->dress_id)
                    ->where('shirtitems_id', $reservation->shirtitems_id)
                    ->whereNull('skirtitems_id')
                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                    ->get();
                    // ตรวจอสอบเช่าเฉพาะทั้งชุด แต่ห้ามเอาเช่าเฉพาะผ้าถุงมาเกี่ยวข้อง เพราะอย่าไปนับคิวด้วย
                    $status_total_dress = App\Models\Reservation::where('status_completed', 0)
                    ->where('dress_id', $reservation->dress_id)
                    ->whereNull('shirtitems_id')
                    ->whereNull('skirtitems_id')
                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                    ->get();
                    $list__for__one = [];

                    foreach ($status_shirt as $item) {
                    $list__for__one[] = $item->id;
                    }
                    foreach ($status_total_dress as $item) {
                    $list__for__one[] = $item->id;
                    }

                    $final = App\Models\reservation::whereIn('id', $list__for__one)
                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                    ->first();
                    $final_queue = App\Models\reservation::whereIn('id', $list__for__one)
                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                    ->where('status', 'ถูกจอง')
                    ->first();
                    @endphp
                    @if ($reservation->id == $final_queue->id)
                    @if ($final->status == 'ถูกจอง')
                    อยู่ที่ร้าน
                    @elseif($final->status == 'กำลังเช่า')
                    ถูกเช่าโดยลูกค้าท่านก่อนหน้า
                    @else
                    {{ $final->status }}
                    @endif
                    @else
                    รอคิว
                    @endif
                    @elseif($reservation->skirtitems_id)
                    @php
                    // ตรวจสอบเฉพาะผ้าถุงก่อน
                    $status_skirt = App\Models\Reservation::where('status_completed', 0)
                    ->where('dress_id', $reservation->dress_id)
                    ->where('skirtitems_id', $reservation->skirtitems_id)
                    ->whereNull('shirtitems_id')
                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                    ->get();

                    // ตรวจอสอบเช่าเฉพาะทั้งชุด แต่ห้ามเอาเช่าเฉพาะเสื้อมาเกี่ยวข้อง เพราะอย่าไปนับคิวด้วย
                    $status_total_dress = App\Models\Reservation::where('status_completed', 0)
                    ->where('dress_id', $reservation->dress_id)
                    ->whereNull('shirtitems_id')
                    ->whereNull('skirtitems_id')
                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                    ->get();
                    $list__for__one = [];

                    foreach ($status_skirt as $item) {
                    $list__for__one[] = $item->id;
                    }
                    foreach ($status_total_dress as $item) {
                    $list__for__one[] = $item->id;
                    }
                    $final = App\Models\reservation::whereIn('id', $list__for__one)
                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                    ->first();
                    $final_queue = App\Models\reservation::whereIn('id', $list__for__one)
                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                    ->where('status', 'ถูกจอง')
                    ->first();
                    @endphp
                    @if ($reservation->id == $final_queue->id)
                    @if ($final->status == 'ถูกจอง')
                    อยู่ที่ร้าน
                    @elseif($final->status == 'กำลังเช่า')
                    ถูกเช่าโดยลูกค้าท่านก่อนหน้า
                    @else
                    {{ $final->status }}
                    @endif
                    @else
                    รอคิว
                    @endif
                    @else
                    @php
                    // ตรวจอสอบเช่าเฉพาะทั้งชุด + เฉพาะเสื้อ + เฉพาะผ้าถุง เราเลยเอาแค่ dress_id กำกับก็พอ เพราะ
                    // เช่าทั้งชุดหรือเช่าปแค่เื้อหือเช่าแค่ผ้าถุงมันก็มี dress_id กำกกับ
                    $status_total_dress = App\Models\Reservation::where('status_completed', 0)
                    ->where('dress_id', $reservation->dress_id)
                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                    ->get();
                    $list__for__one = [];

                    foreach ($status_total_dress as $item) {
                    $list__for__one[] = $item->id;
                    }
                    $final = App\Models\reservation::whereIn('id', $list__for__one)
                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                    ->first();

                    $final_queue = App\Models\reservation::whereIn('id', $list__for__one)
                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                    ->where('status', 'ถูกจอง')
                    ->first();
                    @endphp


                    @if ($reservation->id == $final_queue->id)
                    @if ($final->status == 'ถูกจอง')
                    อยู่ที่ร้าน
                    @elseif($final->status == 'กำลังเช่า')
                    ถูกเช่าโดยลูกค้าท่านก่อนหน้า
                    @else
                    {{ $final->status }}
                    @endif
                    @else
                    รอคิว
                    @endif
                    @endif















                </td>

                <td style="padding: 16px;">
                    <a href="{{ route('employee.ordertotaldetailshow', ['id' => $orderdetail->id]) }}"
                    class="btn btn-s" style="background-color:#DADAE3;">
                        ดูรายละเอียด
                    </a>

                    <a href="{{route('employee.ordertotaldetailpostpone',['id' => $orderdetail->id])}}"
                        class="btn btn-m" style="background-color:#BACEE6 ;">
                        เลื่อนวัน
                    </a>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="text-align: center ; ">ไม่มีรายการแสดงผล</p>
    @endif
</div>
@endsection