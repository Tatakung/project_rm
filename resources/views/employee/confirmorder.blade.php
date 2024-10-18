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
                    <form action="{{ route('employee.confirmordersave', ['id' => $order_id]) }}" method="POST">
                        @csrf
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
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <p>ลูกค้าชำระเงินมัดจำ
                                            <br><br><br>
                                        </p>
                                        <input type="radio" name="payment_status" value="1" checked>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <p>ลูกค้าชำระเงินเต็มจำนวน(มัดจำ+ราคาเช่า+ประกันค่าเสียหาย)</p>
                                        <input type="radio" name="payment_status" value="2">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>





                <div class="col-md-7">
                    <h5 style="margin-top: 10px;">สรุปรายการ</h5>
                    <hr>

                    @foreach ($orderdetail as $detail)
                        <div class="media">
                            @if ($detail->type_order == 2)
                                <img src="{{ asset('storage/' . App\Models\Dressimage::where('dress_id', $detail->dress_id)->first()->dress_image) }}"
                                    class="mr-5" alt="..." style="width: 146px; height: 195px; border-radius: 8px;">
                            @elseif($detail->type_order == 1)
                                <div class="mr-5"
                                    style="width: 96px; height: 145px; border-radius: 2px; display: flex; justify-content: center; align-items: center; background-color: #f8f9fa;">
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
                                    @endif

                                </strong>
                                <p style="font-size: 15px;  margin-bottom: 5px; ">จำนวน&nbsp;{{ $detail->amount }}&nbsp;ชุด
                                </p>
                                <p style="font-size: 15px;  margin-bottom: 5px;">
                                    @if ($detail->type_order == 1)
                                        ราคาตัด:&nbsp;{{ $detail->price }}&nbsp;บาท
                                    @elseif($detail->type_order == 2)
                                        ราคาเช่า:&nbsp;{{ $detail->price }}&nbsp;บาท
                                    @endif
                                </p>
                                <p style="font-size: 15px;  margin-bottom: 5px;">
                                    เงินมัดจำ:&nbsp;{{ $detail->deposit }}&nbsp;บาท
                                </p>
                                @if ($detail->type_order == 2)
                                    <p style="font-size: 15px;  margin-bottom: 5px;">
                                        ประกันค่าเสียหาย:&nbsp;{{ $detail->damage_insurance }}&nbsp;บาท</p>
                                @endif

                                @php
                                    $Date = App\Models\Date::where('order_detail_id', $detail->id)
                                        ->orderBy('created_at', 'desc')
                                        ->first();
                                @endphp
                                <p style="font-size: 15px;  margin-bottom: 5px;">วันที่นัดรับ:
                                    {{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                    {{ \Carbon\Carbon::parse($Date->pickup_date)->year + 543 }}
                                </p>
                                @if ($detail->type_order == 2)
                                    <p style="font-size: 15px;  margin-bottom: 5px;">วันที่นัดคืน:
                                        {{ \Carbon\Carbon::parse($Date->return_date)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($Date->return_date)->year + 543 }}
                                    </p>
                                @endif


                            </div>
                            <div class="media-body">
                                <p>&nbsp;</p>
                            </div>
                            {{-- <div class="media-right">
                                <p style="font-size: 15px;  margin-bottom: 5px;">รวมทั้งสิ้น
                                    {{ number_format($detail->price * $detail->amount + $detail->deposit * $detail->amount + $detail->damage_insurance + $detail->late_charge) }}
                                </p>
                            </div> --}}
                        </div>
                        <hr>
                        @php
                            $list_price[] = $detail->price * $detail->amount;
                            $list_deposit[] = $detail->deposit * $detail->amount;
                            $list_damage_insurance[] = $detail->damage_insurance * $detail->amount;
                            $late_charge_late_charge[] = $detail->late_charge;
                        @endphp
                    @endforeach


                    <p style="margin-top: 10px; ">
                    <h5>สรุปยอดเงินรวม</h5>
                    </p>
                    <div class="media">
                        <div class="media-left">
                            <p style="font-size: 15px;  margin-bottom: 5px; ">ราคาค่าเช่า</p>
                            <p style="font-size: 15px;  margin-bottom: 5px;">ค่ามัดจำ</p>
                            <p style="font-size: 15px;  margin-bottom: 5px;">ประกันค่าเสียหาย</p>
                            {{-- <p style="font-size: 15px;  margin-bottom: 5px;">ค่าบริการขยายเวลาเช่าชุด</p> --}}
                            {{-- <p style="font-size: 15px;  margin-bottom: 5px; color: crimson">จำนวนเงินที่ต้องชำระทั้งหมด</p> --}}
                        </div>
                        <div class="media-body">
                            <p>&nbsp;</p>
                        </div>
                        <div class="media-right">
                            <p style="font-size: 15px;  margin-bottom: 5px; text-align: right">
                                {{ number_format(array_sum($list_price), 2) }}
                            </p>
                            <p style="font-size: 15px;  margin-bottom: 5px; text-align: right">
                                {{ number_format(array_sum($list_deposit), 2) }}
                            </p>
                            <p style="font-size: 15px;  margin-bottom: 5px; text-align: right">
                                {{ number_format(array_sum($list_damage_insurance), 2) }}</p>
                            {{-- <p style="font-size: 15px;  margin-bottom: 5px; text-align: right">
                                {{ number_format(array_sum($late_charge_late_charge), 2) }}</p> --}}
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

                </div>
            </div>
        </form>
    </div>
@endsection