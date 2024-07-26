@extends('layouts.adminlayout')
@section('content')
    @php
        $list_price = [];
        $list_deposit = [];
        $list_damage_insurance = [];
        $late_charge_late_charge = [];
    @endphp

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-5" style="background-color: #F7F7F7">
                <p style="margin-top: 10px;">ข้อมูลลูกค้า</p>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <label for=""><span>ชื่อ</span></label>
                        <input type="text" name="" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for=""><span>นามสกุล</span></label>
                        <input type="text" name="" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for=""><span>เบอร์ติดต่อ</span></label>
                        <input type="text" name="" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col">

                    </div>
                </div>
                <hr>
            </div>
            
            <div class="col-md-7">
                <p style="margin-top: 10px;">สรุปรายการ</p>
                <hr>

                @foreach ($orderdetail as $detail)
                    <div class="media">
                        <img src="{{ asset('storage/' . App\Models\Dressimage::where('dress_id', $detail->dress_id)->first()->dress_image) }}"
                            class="mr-5" alt="..." style="width: 96px; height: 145px; border-radius: 8px;">

                        <div class="media-left">
                            <strong>{{ $detail->title_name }}<span>&nbsp(&nbsp;{{ $detail->amount }}&nbsp;ชุด)</span></strong>
                            <p style="font-size: 15px;  margin-bottom: 5px; ">จำนวน&nbsp;{{ $detail->amount }}&nbsp;ชุด</p>
                            <p style="font-size: 15px;  margin-bottom: 5px;">ราคาเช่า:&nbsp;{{ $detail->price }}&nbsp;บาท
                            </p>
                            <p style="font-size: 15px;  margin-bottom: 5px;">ราคามัดจำ:&nbsp;{{ $detail->deposit }}&nbsp;บาท
                            </p>
                            <p style="font-size: 15px;  margin-bottom: 5px;">
                                ประกันค่าเสียหาย:&nbsp;{{ $detail->damage_insurance }}&nbsp;บาท</p>
                            <p style="font-size: 15px;  margin-bottom: 5px;">วันที่นัดรับ:
                                {{ \Carbon\Carbon::parse($detail->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                {{ \Carbon\Carbon::parse($detail->pickup_date)->year + 543 }}
                            </p>
                            <p style="font-size: 15px;  margin-bottom: 5px;">วันที่นัดคืน:
                                {{ \Carbon\Carbon::parse($detail->return_date)->locale('th')->isoFormat('D MMM') }}
                                {{ \Carbon\Carbon::parse($detail->return_date)->year + 543 }}
                            </p>
                            <p style="font-size: 15px;  margin-bottom: 5px; ">
                                ค่าบริการขยายเวลาเช่าชุด&nbsp;{{ $detail->late_charge }}&nbsp;บาท</p>
                        </div>
                        <div class="media-body">
                            <p>&nbsp;</p>
                        </div>
                        <div class="media-right">
                            <p style="font-size: 15px;  margin-bottom: 5px;">รวมทั้งสิ้น
                                {{ number_format($detail->price + $detail->deposit + $detail->damage_insurance +$detail->late_charge) }}
                            </p>
                        </div>
                    </div>
                    <hr>
                    @php
                        $list_price[] = $detail->price;
                        $list_deposit[] = $detail->deposit;
                        $list_damage_insurance[] = $detail->damage_insurance;
                        $late_charge_late_charge[] = $detail->late_charge;

                    @endphp
                @endforeach

                <p style="margin-top: 10px;">สรุปยอดเงินรวม</p>
                <div class="media">
                    <div class="media-left">
                        <p style="font-size: 15px;  margin-bottom: 5px; ">ราคาค่าเช่า</p>
                        <p style="font-size: 15px;  margin-bottom: 5px;">ค่ามัดจำ</p>
                        <p style="font-size: 15px;  margin-bottom: 5px;">ประกันค่าเสียหาย</p>
                        <p style="font-size: 15px;  margin-bottom: 5px;">ค่าบริการขยายเวลาเช่าชุด</p>
                        {{-- <p style="font-size: 15px;  margin-bottom: 5px; color: crimson">จำนวนเงินที่ต้องชำระทั้งหมด</p> --}}
                    </div>
                    <div class="media-body">
                        <p>&nbsp;</p>
                    </div>
                    <div class="media-right">
                        <p style="font-size: 15px;  margin-bottom: 5px; ">{{ number_format(array_sum($list_price), 2) }}</p>
                        <p style="font-size: 15px;  margin-bottom: 5px;">{{ number_format(array_sum($list_deposit), 2) }}
                        </p>
                        <p style="font-size: 15px;  margin-bottom: 5px;">
                            {{ number_format(array_sum($list_damage_insurance), 2) }}</p>
                        <p style="font-size: 15px;  margin-bottom: 5px;">
                            {{ number_format(array_sum($late_charge_late_charge), 2) }}</p>
                    </div>
                </div>
                <hr>





            </div>
        </div>
    </div>
@endsection
