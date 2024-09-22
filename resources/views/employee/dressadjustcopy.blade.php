@extends('layouts.adminlayout')

@section('content')
    <h1 class="text-center my-4" style="color: #3d3d3d; font-family: 'Prompt', sans-serif; font-weight: 600;">
        รายการเช่าชุดที่ลูกค้าต้องมารับในเร็วๆนี้ !
    </h1>

    <table class="table shadow-sm" style="width: 100%; background-color: #ffffff; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">ลำดับคิว</th>
                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">ชุด</th>
                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">ชื่อลูกค้า</th>
                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">วันที่นัดรับ</th>
                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">ตำแหน่งชุด</th>
                <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">ดูรายละเอียด</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reservation as $index => $reservation)
                @php
                    $orderdetail = App\Models\Orderdetail::where('reservation_id', $reservation->id)->first();
                    $customer_id = App\Models\Order::where('id', $orderdetail->order_id)->value('customer_id');
                    $customer = App\Models\Customer::find($customer_id);
                    $dress_mea_adjust = App\Models\Dressmeaadjustment::where('order_detail_id', $orderdetail->id)->get();

                    $validate = false;
                    foreach ($dress_mea_adjust as $index => $dressmeaadjust) {
                        $dressmea = App\Models\Dressmea::where('id', $dressmeaadjust->dressmea_id)->value('current_mea');
                        if ($dressmea != $dressmeaadjust->new_size) {
                            $validate = true;
                        }
                    }

                    $dress = App\Models\Dress::where('id', $reservation->dress_id)->first();
                    $type_dress = App\Models\Typedress::where('id', $dress->type_dress_id)->first();
                @endphp

                <tr style="border-bottom: 1px solid #e6e6e6;">
                    <td>1</td>
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
                                <span style="color: #CC2828;">- ต้องปรับแก้ขนาด</span>
                            @else
                                <span style="color: #28a745;">- ไม่ต้องปรับแก้ขนาด</span>
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
                            document.getElementById('showday{{ $reservation->id }}').innerHTML = "เหลืออีก " + totalday + ' วัน !';
                        </script>
                    </td>
                    <td>อยู่ในร้าน(รอปรับแก้)</td>

                    <td style="padding: 16px;">
                        <a href="{{ route('employee.ordertotaldetailshow', ['id' => $orderdetail->id]) }}" class="btn btn-primary" style="padding: 6px 12px;">
                            ดูรายละเอียด
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
