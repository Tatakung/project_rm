@extends('layouts.adminlayout')
@section('content')
    <div class="container mt-5">

    <div class="row">
        <div class="col">
            <h2 class="py-4" style="text-align: center">ตะกร้าสินค้า</h2>
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
                    @endif
                    <div class="media-left">
                        {{-- <p style="font-size: 15px;">เลขorder_detail_id ที่:{{ $detail->id }}</p> --}}
                        <p style="font-size: 15px;">{{ $detail->title_name }}</p>
                        <p style="font-size: 15px;">ราคา: {{ number_format($detail->price, 2) }} บาท</p>
                        <p style="font-size: 15px;">ราคามัดจำ: {{ number_format($detail->deposit, 2) }} บาท</p>
                    </div>
                    <div class="media-body text-center">
                        <p style="font-size: 15px;">วันที่นัดรับ:
                            {{ \Carbon\Carbon::parse($detail->pickup_date)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($detail->pickup_date)->year + 543 }}
                        </p>
                        <p style="font-size: 15px;">
                            @if ($detail->return_date != null)
                                วันที่นัดคืน:{{ \Carbon\Carbon::parse($detail->return_date)->locale('th')->isoFormat('D MMM') }}
                                {{ \Carbon\Carbon::parse($detail->return_date)->year + 543 }}
                            @endif
                        </p>

                        <p style="font-size: 15px;">
                            {{-- dress_idคือ:{{$detail->dress_id}} --}}
                        </p>
                        {{-- <p style="font-size: 15px;" >separable :{{App\Models\Dress::where('id',$detail->dress_id)->value('separable')}}</p> --}}

                    </div>
                    <div class="media-right">
                        <p style="font-size: 15px;">
                        <form action="{{ route('employee.manageitem', ['id' => $detail->id]) }}" method="GET"
                            style="display:inline;">
                            @csrf
                            <input type="hidden" name="type_order" value="{{ $detail->type_order }}">
                            <button type="submit" class="btn btn-warning btn-sm">จัดการ</button>
                        </form>
                        </p>
                        <p style="font-size: 15px;">
                        <form action="{{ route('employee.deletelist', ['id' => $detail->id]) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('แน่ใจใช่ไหมว่าคุณต้องการนำออก?')">ยกเลิกรายการ</button>
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




            {{-- <div class="media">
            <div class="media-right">
                <a href="{{ route('employee.confirmorder', ['id' => $order->id]) }}" class="btn btn-dark">
                    ชำระเงิน
                </a>
            </div>
        </div> --}}

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