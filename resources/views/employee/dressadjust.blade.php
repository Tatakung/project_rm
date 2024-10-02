@extends('layouts.adminlayout')

@section('content')
    <h1 class="text-center my-4" style="color: #3d3d3d; font-family: 'Prompt', sans-serif; font-weight: 600;">
        "คิวการจัดเตรียมชุดสำหรับลูกค้า"
    </h1>

    <div class="alert alert-info" role="alert" style="font-family: 'Prompt', sans-serif;">
        <h4 class="alert-heading">คำแนะนำสำหรับพนักงาน</h4>
        <p>รายการนี้แสดงลำดับคิวการจัดเตรียมชุดสำหรับลูกค้า โดยเรียงตามวันที่ลูกค้าจะมารับ</p>
        <hr>
        {{-- <p class="mb-0">กรุณาจัดเตรียมชุดตามลำดับคิว <strong>คิวที่ 1 <span style="color: red;">&#9733;</span>
                มีความสำคัญสูงสุดและต้องจัดเตรียมก่อน</strong></p> --}}
    </div>

    @if ($reservations->count() > 0)
        <table class="table shadow-sm" style="width: 100%; background-color: #ffffff; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">วันที่นัดรับ</th>
                    <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">ชุด</th>
                    <th style="padding: 12px; border-bottom: 2px solid #e6e6e6;">ชื่อลูกค้า</th>
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
                        <td style="padding: 16px;">
                            {{ \Carbon\Carbon::parse($reservation->start_date)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($reservation->start_date)->year + 543 }}
                            <p id="showday{{ $reservation->id }}" class="mt-2" style="color: #8d1e1e;"></p>

                            <script>
                                var now = new Date();
                                var start_date = new Date("{{ $reservation->start_date }}");
                                var day = start_date - now;
                                var totalday = Math.ceil(day / (1000 * 60 * 60 * 24));
                                

                                if(totalday > 0){
                                    document.getElementById('showday{{ $reservation->id }}').innerHTML = "เหลืออีก " + totalday + ' วัน ';
                                }
                                else if(totalday == 0){
                                    document.getElementById('showday{{ $reservation->id }}').innerHTML = "มารับชุดวันนี้ ";                   
                                }
                                else{
                                    document.getElementById('showday{{ $reservation->id }}').innerHTML = "เหลืออีก " + totalday + ' วัน ';

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
                            คุณ {{ $customer->customer_fname }} {{ $customer->customer_lname }}
                        </td>

                        
                        <td style="width: 200px;">
                            @php
                                $status_now = App\Models\Reservation::where('status_completed', 0)
                                    ->where('dress_id', $reservation->dress_id)
                                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                                    ->first();
                            @endphp

                            @if ($number == 1)
                                @if($status_now->status == 'ถูกจอง')
                                   อยู่ในร้าน
                                @else
                                {{$status_now->status}}
                                @endif
                            
                            @else
                                รอคิว
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
