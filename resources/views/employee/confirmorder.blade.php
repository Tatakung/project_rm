@extends('layouts.adminlayout')
@section('content')
    @php
        $list_price = [];
        $list_deposit = [];
        $list_damage_insurance = [];
        $late_charge_late_charge = [];
    @endphp

    <div class="container mt-5">

        <form action="{{ route('employee.confirmordersave', ['id' => $order_id]) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-5" style="background-color: #F7F7F7">
                    <h5 style="margin-top: 10px;">ข้อมูลลูกค้า</h5>
                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <label for=""><span>ชื่อ</span></label>
                            <input type="text" name="customer_fname" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for=""><span>นามสกุล</span></label>
                            <input type="text" name="customer_lname" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="phone"><span>เบอร์ติดต่อ</span></label>
                            <input type="text" name="customer_phone" class="form-control" maxlength="10">
                        </div>
                    </div>

                    <hr>
                    <h5 style="margin-top: 15px;">ข้อมูลการจ่ายชำระเงิน</h5>
                    <br>
                    <div class="row">




                        <div class="col-md-6"
                            @if ($check_pickip_today == '1') style="display: none ; "
                        @elseif($check_pickip_today == '2')
                                style="display: block ; " @endif>
                            <div class="card h-100">
                                <div class="card-body text-center d-flex flex-column justify-content-center">
                                    <p>ลูกค้าชำระเงินมัดจำ ({{ number_format($total_deposit, 2) }} บาท )</p>
                                    <input type="radio" name="payment_status" id="payment_status_one" value="1"
                                        {{ $check_pickip_today == '0' ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body text-center d-flex flex-column justify-content-center">
                                    @if ($order->type_order == 1)
                                        <p>ลูกค้าชำระเงินเต็มจำนวน (ราคาตัด)
                                            ({{ number_format($total_price_and_damage_insurance, 2) }} บาท)</p>
                                    @elseif($order->type_order == 2)
                                        <p>ลูกค้าชำระเงินเต็มจำนวน (ราคาเช่า+เงินประกัน)
                                            ({{ number_format($total_price_and_damage_insurance, 2) }} บาท)</p>
                                    @elseif($order->type_order == 3)
                                        <p>ลูกค้าชำระเงินเต็มจำนวน (ราคาเช่าตัด+เงินประกัน)
                                            ({{ number_format($total_price_and_damage_insurance, 2) }} บาท)</p>
                                    @endif

                                    <input type="radio" name="payment_status" id="payment_status_two" value="2"
                                        {{ $check_pickip_today == '1' ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>



                    </div>


                </div>



                <div class="col-md-7">
                    <h5 style="margin-top: 10px;">
                        @if ($order->type_order == 1)
                            สรุปรายการตัดชุด ({{ $order->total_quantity }})
                        @elseif($order->type_order == 2)
                            สรุปรายการเช่า ({{ $order->total_quantity }})
                        @elseif($order->type_order == 3)
                            สรุปรายการเช่าตัดชุด ({{ $order->total_quantity }})
                        @endif
                    </h5>
                    <hr>
                    @foreach ($orderdetail as $detail)
                        @php
                            if ($detail->type_order == 3) {
                                $reserv = App\Models\Reservation::find($detail->reservation_id);
                                if ($reserv->jewelry_id) {
                                    $jewelry = App\Models\Jewelry::find($reserv->jewelry_id);
                                    $typejewelry = App\Models\Typejewelry::where(
                                        'id',
                                        $jewelry->type_jewelry_id,
                                    )->first();
                                    $imagejewelry = App\Models\Jewelryimage::where(
                                        'jewelry_id',
                                        $reserv->jewelry_id,
                                    )->first();
                                } elseif ($reserv->jewelry_set_id) {
                                    $setjewelry = App\Models\Jewelryset::find($reserv->jewelry_set_id);
                                }
                            } else {
                                $jewelry = null;
                                $typejewelry = null;
                                $setjewelry = null;
                            }
                        @endphp

                        <div class="media">
                            @if ($detail->type_order == 2)
                                <img src="{{ asset($detail->orderdetailmanytoonedress->dressimages->first()->dress_image) }}"
                                    class="mr-5" alt="..." style="width: 146px; height: 195px; border-radius: 8px;">
                            @elseif($detail->type_order == 1)
                                <div class="mr-5"
                                    style="width: 146px; height: 195px; border-radius: 8px; border-radius: 2px; display: flex; justify-content: center; align-items: center; background-color: #f8f9fa;">
                                    <i class="bi bi-scissors" style="font-size: 48px;"></i>
                                </div>
                            @elseif($detail->type_order == 3)
                                @if ($reserv->jewelry_id)
                                    <img src="{{ asset($imagejewelry->jewelry_image) }}" class="mr-5"
                                        alt="..." style="width: 146px; height: 195px; border-radius: 8px;">
                                @elseif($reserv->jewelry_set_id)
                                    <img src="{{ asset('images/setjewelry.jpg') }}" class="mr-5" alt="..."
                                        style="width: 146px; height: 195px; border-radius: 8px;">
                                @endif
                            @elseif($detail->type_order == 4)
                                <div class="mr-5"
                                    style="width: 146px; height: 195px; border-radius: 8px; border-radius: 2px; display: flex; justify-content: center; align-items: center; background-color: #f8f9fa;">
                                    <i class="bi bi-scissors" style="font-size: 48px;"></i>
                                </div>
                            @endif


                            <div class="media-left">
                                @php
                                    if ($detail->type_order == 2) {
                                        $dress = App\Models\Dress::find($detail->dress_id);
                                        $type_name = App\Models\Typedress::where('id', $dress->type_dress_id)->value(
                                            'type_dress_name',
                                        );
                                    }
                                @endphp
                                <strong>
                                    @if ($detail->type_order == 1)
                                        รายการตัดชุด
                                    @elseif($detail->type_order == 2)
                                        @if ($detail->shirtitems_id)
                                            เช่า{{ $type_name }} {{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                            (เสื้อ)
                                        @elseif($detail->skirtitems_id)
                                            เช่า{{ $type_name }} {{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                            (ผ้าถุง)
                                        @else
                                            เช่า{{ $type_name }} {{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                            (ทั้งชุด)
                                        @endif
                                    @elseif($detail->type_order == 3)
                                        @if ($detail->detail_many_one_re->jewelry_id)
                                            เช่า{{ $typejewelry->type_jewelry_name }}
                                            {{ $typejewelry->specific_letter }}{{ $jewelry->jewelry_code }}
                                        @elseif($detail->detail_many_one_re->jewelry_set_id)
                                            เช่าเซตเครื่องประดับ{{ $setjewelry->set_name }}
                                        @endif
                                    @elseif($detail->type_order == 4)
                                        เช่าตัด{{$detail->type_dress}}
                                    @endif

                                </strong>
                                <p style="font-size: 15px;  margin-bottom: 5px; ">
                                    จำนวน&nbsp;{{ $detail->amount }}&nbsp;รายการ
                                </p>
                                <p style="font-size: 15px;  margin-bottom: 5px;">
                                    @if ($detail->type_order == 1)
                                        ราคาตัด:&nbsp;{{ $detail->price }}&nbsp;บาท
                                    @elseif($detail->type_order == 2)
                                        ราคาเช่า:&nbsp;{{ $detail->price }}&nbsp;บาท
                                    @elseif($detail->type_order == 3)
                                        ราคาเช่า:&nbsp;{{ $detail->price }}&nbsp;บาท
                                    @elseif($detail->type_order == 4)
                                        ราคาเช่าตัด:&nbsp;{{ $detail->price }}&nbsp;บาท
                                    @endif
                                </p>
                                <p style="font-size: 15px;  margin-bottom: 5px;">
                                    เงินมัดจำ:&nbsp;{{ $detail->deposit }}&nbsp;บาท
                                </p>
                                @if ($detail->type_order != 1)
                                    <p style="font-size: 15px;  margin-bottom: 5px;">
                                        เงินประกัน:&nbsp;{{ $detail->damage_insurance }}&nbsp;บาท</p>
                                @endif

                                @php
                                    $Date = App\Models\Date::where('order_detail_id', $detail->id)
                                        ->orderBy('created_at', 'desc')
                                        ->first();
                                @endphp

                                @if ($detail->type_order == 1)
                                    <p style="font-size: 15px;  margin-bottom: 5px;">วันที่นัดรับ:
                                        {{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($Date->pickup_date)->year + 543 }}
                                    </p>
                                @elseif($detail->type_order == 2)
                                    <p style="font-size: 15px;  margin-bottom: 5px;">วันที่นัดรับ:
                                        {{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($Date->pickup_date)->year + 543 }}
                                    </p>
                                    <p style="font-size: 15px;  margin-bottom: 5px;">วันที่นัดคืน:
                                        {{ \Carbon\Carbon::parse($Date->return_date)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($Date->return_date)->year + 543 }}
                                    </p>
                                @elseif($detail->type_order == 3)
                                    <p style="font-size: 15px;  margin-bottom: 5px;">วันที่นัดรับ:
                                        {{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($Date->pickup_date)->year + 543 }}
                                    </p>
                                    <p style="font-size: 15px;  margin-bottom: 5px;">วันที่นัดคืน:
                                        {{ \Carbon\Carbon::parse($Date->return_date)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($Date->return_date)->year + 543 }}
                                    </p>
                                @elseif($detail->type_order == 4)
                                    <p style="font-size: 15px;  margin-bottom: 5px;">วันที่นัดรับ:
                                        {{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($Date->pickup_date)->year + 543 }}
                                    </p>
                                    <p style="font-size: 15px;  margin-bottom: 5px;">วันที่นัดคืน:
                                        {{ \Carbon\Carbon::parse($Date->return_date)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($Date->return_date)->year + 543 }}
                                    </p>
                                @endif


                                


                            </div>
                            <div class="media-body">
                                <p>&nbsp;</p>
                            </div>
                            
                        </div>
                        <hr>
                        @php
                            $list_price[] = $detail->price * $detail->amount;
                            $list_deposit[] = $detail->deposit * $detail->amount;
                            $list_damage_insurance[] = $detail->damage_insurance * $detail->amount;
                            $late_charge_late_charge[] = $detail->late_charge;
                        @endphp
                    @endforeach
                    {{ $orderdetail->onEachSide(2)->links() }}




                    <p style="margin-top: 10px; ">
                    <h5>สรุปยอดเงินรวม</h5>
                    </p>
                    <div class="media">
                        <div class="media-left">
                            <p style="font-size: 15px;  margin-bottom: 5px;"><strong>จำนวนเงินที่ต้องชำระสุทธิ</strong></p>
                        </div>
                        <div class="media-body">
                            <p>&nbsp;</p>
                        </div>
                        <div class="media-right">

                            <p style="font-size: 15px;  margin-bottom: 5px; text-align: right">
                            <p id="show_total_deposit"
                                @if ($check_pickip_today == '1') style="display: none ; "
                            @elseif($check_pickip_today == '0')
                            style="display: block ; " @endif>
                                {{ number_format($total_deposit, 2) }} บาท</p>
                            <p id="show_total_price_and_damage_insurance"
                                @if ($check_pickip_today == '0') style="display: none ;"
                                @elseif($check_pickip_today == '1')
                                    style="display: block ; " @endif>
                                {{ number_format($total_price_and_damage_insurance, 2) }} บาท</p>
                        </div>
                    </div>






                    <hr>
                    <div class="row" style="margin-top: 15px;">
                        <div class="col-md-12">
                            <div class="text-center">
                                <button class="btn btn-dark" type="submit">ยืนยันคำสั่ง</button>
                            </div>
                        </div>
                    </div>



                    <script>
                        var payment_status_one = document.getElementById('payment_status_one');
                        var payment_status_two = document.getElementById('payment_status_two');

                        var show_total_deposit = document.getElementById('show_total_deposit');
                        var show_total_price_and_damage_insurance = document.getElementById('show_total_price_and_damage_insurance');

                        payment_status_one.addEventListener('change', function() {
                            if (this.checked) {
                                show_total_deposit.style.display = 'block';
                                show_total_price_and_damage_insurance.style.display = 'none';
                            }
                        });
                        payment_status_two.addEventListener('change', function() {
                            if (this.checked) {
                                show_total_deposit.style.display = 'none';
                                show_total_price_and_damage_insurance.style.display = ' block';
                            }
                        });
                    </script>


                </div>
            </div>
        </form>
    </div>
@endsection
