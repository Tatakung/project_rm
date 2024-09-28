@extends('layouts.adminlayout')

@section('content')
    <h1 class="text-center my-4" style="color: #3d3d3d; font-family: 'Prompt', sans-serif; font-weight: 600;">
        "คิวการจัดเตรียมชุดสำหรับลูกค้า"
    </h1>

    <div class="alert alert-info" role="alert" style="font-family: 'Prompt', sans-serif;">
        <h4 class="alert-heading">คำแนะนำสำหรับพนักงาน</h4>
        <p>รายการนี้แสดงลำดับคิวการจัดเตรียมชุดสำหรับลูกค้า โดยเรียงตามวันที่ลูกค้าจะมารับ</p>
        <hr>
        <p class="mb-0">กรุณาจัดเตรียมชุดตามลำดับคิว <strong>คิวที่ 1 <span style="color: red;">&#9733;</span>
                มีความสำคัญสูงสุดและต้องจัดเตรียมก่อน</strong></p>
    </div>

    @if($reservations->count() > 0 )
    <table class="table shadow-sm" style="width: 100%; background-color: #ffffff; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                {{-- <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">ลำดับคิว</th> --}}
                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">ชุด</th>
                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">ชื่อลูกค้า</th>
                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">วันที่นัดรับ</th>
                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">สถานะชุด</th>
                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">ดูรายละเอียด</th>
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
                    
                        @if ($reservation->shirtitems_id)
                            @php
                                $find = App\Models\Reservation::where('status_completed', 0)
                                    ->where('status', 'ถูกจอง')
                                    ->where('shirtitems_id', $reservation->shirtitems_id)
                                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                                    ->get();
                                foreach ($find as $index_dress => $item) {
                                    if ($item->id == $reservation->id) {
                                        $number = $index_dress + 1;
                                        break;
                                    }
                                }
                            @endphp
                        @elseif($reservation->skirtitems_id)
                            @php
                                $find = App\Models\Reservation::where('status_completed', 0)
                                    ->where('status', 'ถูกจอง')
                                    ->where('skirtitems_id', $reservation->skirtitems_id)
                                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                                    ->get();
                                foreach ($find as $index_dress => $item) {
                                    if ($item->id == $reservation->id) {
                                        $number = $index_dress + 1;
                                        break;
                                    }
                                }
                            @endphp
                        @else
                            @php
                                $find = App\Models\Reservation::where('status_completed', 0)
                                    ->where('dress_id', $reservation->dress_id)
                                    ->where('status', 'ถูกจอง')
                                    ->whereNull('shirtitems_id')
                                    ->whereNull('skirtitems_id')
                                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                                    ->get();
                                foreach ($find as $index_dress => $item) {
                                    if ($item->id == $reservation->id) {
                                        $number = $index_dress + 1;
                                        break;
                                    }
                                }

                            @endphp
                        @endif

                        {{-- @if ($number == 1)
                            คิวที่ {{ $number }} <span style="color: red; margin-left: 5px;">&#9733;</span>
                        @else
                            คิวที่ {{ $number }}
                        @endif --}}

                 

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
                                <span style="color: #CC2828; font-size: 30px;">- รอการปรับแก้ขนาด</span>
                            @else
                                <span style="color: #28a745; font-size: 30px;">- ไม่ต้องปรับแก้ขนาด</span>
                            @endif
                        </span>
                    </td>

                    <td style="padding: 16px;">
                        คุณ {{ $customer->customer_fname }} {{ $customer->customer_lname }}
                    </td>

                    <td style="padding: 16px;">
                        {{ \Carbon\Carbon::parse($reservation->start_date)->locale('th')->isoFormat('D MMM') }}
                        {{ \Carbon\Carbon::parse($reservation->start_date)->year + 543 }}
                        <p id="showday{{ $reservation->id }}" class="mt-2" style="color: #C31717;"></p>

                        <script>
                            var now = new Date();
                            var start_date = new Date("{{ $reservation->start_date }}");
                            var day = start_date - now;
                            var totalday = Math.ceil(day / (1000 * 60 * 60 * 24));
                            document.getElementById('showday{{ $reservation->id }}').innerHTML = "เหลืออีก " + totalday + ' วัน ';
                        </script>
                    </td>
                    <td style="width: 200px;">

                        @if ($reservation->shirtitems_id)
                            {{-- หาสถานะปัจจุบันของเสื้อ --}}
                            @php
                                $list_for_status = [];
                                $check_status_shirt_now_for_shirt_id = App\Models\Reservation::where(
                                    'status_completed',
                                    0,
                                )
                                    ->where('shirtitems_id', $reservation->shirtitems_id)
                                    ->get();
                                foreach ($check_status_shirt_now_for_shirt_id as $item) {
                                    $list_for_status[] = $item->id;
                                }
                                $check_status_shirt_now_for_dress_id = App\Models\Reservation::where(
                                    'status_completed',
                                    0,
                                )
                                    ->where('dress_id', $reservation->dress_id)
                                    ->whereNull('shirtitems_id')
                                    ->whereNull('skirtitems_id')
                                    ->get();
                                foreach ($check_status_shirt_now_for_dress_id as $value) {
                                    $list_for_status[] = $value->id;
                                }

                                $check_status_total = App\Models\Reservation::orderByRaw(
                                    " STR_TO_DATE(start_date,'%Y-%m-%d') asc",
                                )
                                    ->whereIn('id', $list_for_status)
                                    ->first();
                            @endphp

                            @if ($number == 1)
                                @if ($check_status_total->status == 'กำลังเช่า')
                                    กำลังถูกเช่าโดยลูกค้าท่านก่อน(คืน{{ \Carbon\Carbon::parse($check_status_total->start_date)->locale('th')->isoFormat('D MMM') }})
                                @elseif($check_status_total->status == 'ถูกจอง')
                                    อยู่ในร้าน
                                @else
                                    {{ $check_status_total->status }}
                                @endif
                            @else
                                รอคิว
                            @endif
                        @elseif($reservation->skirtitems_id)
                            {{-- หาสถานะปัจจุบันของผ้าถุง --}}
                            @php
                                $list_for_status = [];
                                $check_status_skirt_now_for_skirt_id = App\Models\Reservation::where(
                                    'status_completed',
                                    0,
                                )
                                    ->where('skirtitems_id', $reservation->skirtitems_id)
                                    ->get();
                                foreach ($check_status_skirt_now_for_skirt_id as $item) {
                                    $list_for_status[] = $item->id;
                                }
                                $check_status_skirt_now_for_dress_id = App\Models\Reservation::where(
                                    'status_completed',
                                    0,
                                )
                                    ->where('dress_id', $reservation->dress_id)
                                    ->whereNull('shirtitems_id')
                                    ->whereNull('skirtitems_id')
                                    ->get();
                                foreach ($check_status_skirt_now_for_dress_id as $value) {
                                    $list_for_status[] = $value->id;
                                }

                                $check_status_total = App\Models\Reservation::orderByRaw(
                                    " STR_TO_DATE(start_date,'%Y-%m-%d') asc",
                                )
                                    ->whereIn('id', $list_for_status)
                                    ->first();
                            @endphp

                            @if ($number == 1)
                                @if ($check_status_total->status == 'กำลังเช่า')
                                    กำลังถูกเช่าโดยลูกค้าท่านก่อน(คืน{{ \Carbon\Carbon::parse($check_status_total->start_date)->locale('th')->isoFormat('D MMM') }})
                                @elseif($check_status_total->status == 'ถูกจอง')
                                    อยู่ในร้าน
                                @else
                                    {{ $check_status_total->status }}
                                @endif
                            @else
                                รอคิว
                            @endif


                        @else
                            @if ($number == 1)
                                @php
                                    $check_status_dress_now = App\Models\Reservation::where('status_completed', 0)
                                        ->where('dress_id', $reservation->dress_id)
                                        ->whereNull('shirtitems_id')
                                        ->whereNull('skirtitems_id')
                                        ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc")
                                        ->first();
                                @endphp

                                @if ($check_status_dress_now->status == 'กำลังเช่า')
                                    กำลังถูกเช่าโดยลูกค้าท่านก่อน(คืน{{ \Carbon\Carbon::parse($check_status_dress_now->start_date)->locale('th')->isoFormat('D MMM') }})
                                @elseif($check_status_dress_now->status == 'ถูกจอง')
                                    อยู่ในร้าน
                                @else
                                    {{ $check_status_dress_now->status }}
                                @endif
                            @else
                                รอคิว
                            @endif
                        @endif



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
