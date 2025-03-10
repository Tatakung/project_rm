@extends('layouts.adminlayout')
@section('content')
    <style>
        .btn-c {
            background-color: #EBDE88;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);

        }

        .btn-d {
            background-color: #EB7E6D;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);

        }
    </style>
    <div class="container mt-5">

        <div class="row">
            <div class="col">
                <h2 class="py-4" style="text-align: center">
                    @if ($order != null)
                        @if ($order->type_order == 1)
                            ตะกร้าสินค้าสำหรับรายการตัดชุด
                        @elseif($order->type_order == 2)
                            ตะกร้าสินค้าสำหรับรายการเช่า
                        @elseif($order->type_order == 3)
                            ตะกร้าสินค้าสำหรับรายการเช่าตัดชุด
                        @endif
                    @endif
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <hr>
            </div>
        </div>

        @php
            $list_order_detail_id = [];
        @endphp



        @if ($order)
            @foreach ($order->order_one_many_orderdetails as $index => $detail)
                <div class="media">
                    @if ($detail->type_order == 2)
                        <img src="{{ asset('storage/' . App\Models\Dressimage::where('dress_id', $detail->dress_id)->first()->dress_image) }}"
                            class="mr-5" alt="..." style="width: 96px; height: 145px; border-radius: 8px;">
                    @elseif($detail->type_order == 1)
                        <div class="mr-5"
                            style="width: 96px; height: 145px; border-radius: 2px; display: flex; justify-content: center; align-items: center; background-color: #f8f9fa;">
                            <i class="bi bi-scissors" style="font-size: 48px;"></i>
                        </div>
                    @elseif($detail->type_order == 3)
                        @php
                            $reservation = App\Models\Reservation::find($detail->reservation_id);
                            if ($reservation->jewelry_id != null) {
                                $jewelryimagenoset = App\Models\Jewelryimage::where(
                                    'jewelry_id',
                                    $reservation->jewelry_id,
                                )->value('jewelry_image');
                            }
                        @endphp
                        @if ($reservation->jewelry_id != null)
                            <img src="{{ asset('storage/' . $jewelryimagenoset) }}" class="mr-5" alt="..."
                                style="width: 96px; height: 145px; border-radius: 8px;">
                        @else
                            <img src="{{ asset('images/setjewelry.jpg') }}" class="mr-5" alt="..."
                                style="width: 96px; height: 145px; border-radius: 8px;">
                        @endif
                    @elseif($detail->type_order == 4)
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
                        <p style="font-size: 15px;">

                            @if ($detail->type_order == 1)
                                รายการตัด{{$detail->type_dress}}
                            @elseif($detail->type_order == 2)
                                @if ($detail->shirtitems_id)
                                รายการเช่า{{ $type_name }} {{ $dress->dress_code_new }}{{ $dress->dress_code }} (เสื้อ)
                                @elseif($detail->skirtitems_id)
                                รายการเช่า{{ $type_name }} {{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                    (ผ้าถุง)
                                @else
                                รายการเช่า{{ $type_name }} {{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                    (ทั้งชุด)
                                @endif
                            @elseif($detail->type_order == 3)
                                @if ($reservation->jewelry_id)
                                    @php
                                        $datajewelry = App\Models\Jewelry::find($reservation->jewelry_id);
                                    @endphp
                                    รายการเช่า{{ $datajewelry->jewelry_m_o_typejew->type_jewelry_name }}
                                    {{ $datajewelry->jewelry_m_o_typejew->specific_letter }}{{ $datajewelry->jewelry_code }}
                                @elseif($reservation->jewelry_set_id)
                                    @php
                                        $datasetjewelry = App\Models\Jewelryset::find($reservation->jewelry_set_id);
                                    @endphp
                                    รายการเช่าเซต{{ $datasetjewelry->set_name }}
                                @endif
                            @elseif($detail->type_order == 4)
                            รายการเช่าตัด{{$detail->type_dress}}
                            @endif

                        </p>
                        <p style="font-size: 15px;">ราคาเช่า: {{ number_format($detail->price, 2) }} บาท</p>
                        <p style="font-size: 15px;">เงินมัดจำ: {{ number_format($detail->deposit, 2) }} บาท</p>
                    </div>
                    <div class="media-body text-center">
                        @php
                            $Date = App\Models\Date::where('order_detail_id', $detail->id)
                                ->orderBy('created_at', 'desc')
                                ->first();
                        @endphp
                        <p style="font-size: 15px;">วันนัดรับ:
                            {{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($Date->pickup_date)->year + 543 }}
                        </p>
                        <p style="font-size: 15px;">

                            @if ($detail->type_order == 2)
                                วันนัดคืน:
                                {{ \Carbon\Carbon::parse($Date->return_date)->locale('th')->isoFormat('D MMM') }}
                                {{ \Carbon\Carbon::parse($Date->return_date)->year + 543 }}
                            @elseif($detail->type_order == 3)
                                วันนัดคืน:
                                {{ \Carbon\Carbon::parse($Date->return_date)->locale('th')->isoFormat('D MMM') }}
                                {{ \Carbon\Carbon::parse($Date->return_date)->year + 543 }}
                            @elseif($detail->type_order == 4)
                                วันนัดคืน:
                                {{ \Carbon\Carbon::parse($Date->return_date)->locale('th')->isoFormat('D MMM') }}
                                {{ \Carbon\Carbon::parse($Date->return_date)->year + 543 }}
                            @endif
                        </p>

                        <p style="font-size: 15px;">
                            
                        </p>
                        

                    </div>
                    

                    
                    <div class="media-right d-flex justify-content-between align-items-center">
                        <p style="font-size: 15px; margin-right: 20px;"> <!-- เพิ่ม margin-right -->
                        <form action="{{ route('employee.manageitem', ['id' => $detail->id]) }}" method="GET"
                            style="display:inline;">
                            @csrf
                            <input type="hidden" name="type_order" value="{{ $detail->type_order }}">
                            <button type="submit" class="btn btn-c btn-sm">จัดการ</button>
                        </form>
                        </p>
                        <p style="font-size: 15px; margin-left: 20px;"> <!-- เพิ่ม margin-left -->
                        <form action="{{ route('employee.deletelist', ['id' => $detail->id]) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-d btn-sm"
                                onclick="return confirm('แน่ใจใช่ไหมว่าคุณต้องการนำออก?')">ยกเลิก</button>
                        </form>
                        </p>
                    </div>


                </div>
                <div class="row">
                    <div class="col-md-12">
                        <hr>
                    </div>
                </div>
                @php
                    $list_order_detail_id[] = $detail->id;
                @endphp
            @endforeach




            <div class="row">
                <div class="col-md-12">
                    <div class="text-right">
                        <a href="{{ route('employee.confirmorder', ['id' => $order->id]) }}" class="btn btn-dark">
                            ชำระเงิน
                        </a>
                    </div>
                </div>

            </div>
        @else
            <div class="row">
                <div class="col-md-12" style="background-color: #EEEEEE">
                    <div class="text-center">
                        <p>ไม่มีรายการในตะกร้าสินค้า</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
