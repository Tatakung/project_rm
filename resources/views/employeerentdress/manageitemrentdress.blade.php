@extends('layouts.adminlayout')
@section('content')
<ol class="breadcrumb" style="background-color: transparent; ">
    <li class="breadcrumb-item">
        <a href="{{ route('employee.cart') }}" style="color: black ; ">ตะกร้าสินค้า</a>
    </li>
    <li class="breadcrumb-item active">
        จัดการข้อมูลเช่าชุด
    </li>
</ol>

<style>
    p {
        font-size: 16px;
    }

    .card-header {
        background-color: #f8f9fa;
        font-weight: bold;
    }
</style>
<div class="modal fade" id="showfail" role="dialog" aria-hidden="true">
    <div class="modal-dialog custom-modal-dialog" role="document">
        <div class="modal-content custom-modal-content"
            style="max-width: 300px; height: 50px; width: 100%; margin: auto; background-color: #EE4E4E; border: 2px solid #EE4E4E; ">
            <div class="modal-body" style="padding: 10px; display: flex; align-items: center; justify-content: center;">
                <p style="margin: 0; color: #ffffff;">{{ session('fail') }}</p>
            </div>
        </div>
    </div>
</div>

<script>
    @if(session('fail'))
    setTimeout(function() {
        $('#showfail').modal('show');
    }, 500);
    @endif
</script>
<div class="modal fade" id="showsuccess" role="dialog" aria-hidden="true">
    <div class="modal-dialog custom-modal-dialog" role="document">
        <div class="modal-content custom-modal-content"
            style="max-width: 300px; height: 50px; width: 100%; margin: auto; background-color: #39d628; border: 2px solid #4fe227; ">
            <div class="modal-body" style="padding: 10px; display: flex; align-items: center; justify-content: center;">
                <p style="margin: 0; color: #ffffff;">{{ session('success') }}</p>
            </div>
        </div>
    </div>
</div>
<script>
    @if(session('success'))
    setTimeout(function() {
        $('#showsuccess').modal('show');
    }, 500);
    @endif
</script>


<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header"><i class="bi bi-info-circle"></i> ข้อมูลการเช่าของชุด
        </div>
        <div class="card-body">

            <form action="{{ route('employee.savemanageitemrentdress', ['id' => $orderdetail->id]) }}" method="POST">
                @csrf


                <div class="row">
                    <div class="col-md-12">
                        <div class="media">
                            <img src="{{ asset('storage/' . $imagedress->first()->dress_image) }}" class="mr-5" alt="..."
                                style="max-height: 350px; width: auto;">
                            <div class="media-left">
                                @php
                                $dress_id = App\Models\Orderdetail::where('id',$orderdetail->id)->value('dress_id') ;
                                $dress = App\Models\Dress::find($dress_id) ;
                                $type_name = App\Models\Typedress::where('id',$dress->type_dress_id)->value('type_dress_name') ;
                                @endphp
                                <p><strong>รายการ :</strong>
                                    @if($orderdetail->shirtitems_id)
                                    {{$type_name}} {{$dress->dress_code_new}}{{$dress->dress_code}} (เสื้อ)
                                    @elseif($orderdetail->skirtitems_id)
                                    {{$type_name}} {{$dress->dress_code_new}}{{$dress->dress_code}} (ผ้าถุง)
                                    @else
                                    {{$type_name}} {{$dress->dress_code_new}}{{$dress->dress_code}} (ทั้งชุด)

                                    @endif
                                </p>

                                <p><strong>ประเภทชุด :</strong> {{ $orderdetail->type_dress }}</p>
                                <p><strong>หมายเลขชุด :</strong> {{ $dress->dress_code_new }}{{ $dress->dress_code }}</p>
                                <p><strong>จำนวนชุด :</strong> {{ $orderdetail->amount }}&nbsp;ชุด</p>
                                <p><strong>ราคาเช่า :</strong> {{ number_format($orderdetail->price) }} บาท</p>


                                <p><strong>เงินมัดจำ :</strong> {{ number_format($orderdetail->deposit) }} บาท</p>

                                <p><strong>ประกันค่าเสียหาย :</strong> {{ number_format($orderdetail->damage_insurance) }} บาท</p>


                                @php
                                $Date_data = App\Models\Date::where('order_detail_id',$orderdetail->id)
                                ->orderBy('created_at','desc')
                                ->first() ;
                                @endphp


                                <p><strong>วันนัดรับ :</strong>
                                    {{ \Carbon\Carbon::parse($Date_data->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                    {{ \Carbon\Carbon::parse($Date_data->pickup_date)->year + 543 }}
                                </p>
                                <p><strong>วันนัดคืน :</strong>
                                    {{ \Carbon\Carbon::parse($Date_data->return_date)->locale('th')->isoFormat('D MMM') }}
                                    {{ \Carbon\Carbon::parse($Date_data->return_date)->year + 543 }}
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
        </div>
    </div>
    <div class="card shadow mt-3">
        <div class="card-header">
            @if ($orderdetail->shirtitems_id)
            <p>ปรับแก้ขนาดชุด(เสื้อ)</p>
            @elseif($orderdetail->skirtitems_id)
            <p>ปรับแก้ขนาดชุด(กระโปรง)</p>
            @else
            <p>ปรับแก้ขนาดชุด(ทั้งชุด)</p>
            @endif
        </div>
        <div class="card-body">
            <div id="aria_show_mea">
                {{-- พืน้ที่แสดงผล  --}}
                @foreach ($dress_mea_adjust as $dress_mea_adjust)
                <div class="row">
                    <div class="col-md-4">
                        {{-- <p>{{ $measurementorderdetail->measurement_name }}</p> --}}
                        <p for="" class="form-label" style="font-size: 16px;margin-top: 10px;">
                            @php
                            $dress_mea = App\Models\Dressmea::where('id',$dress_mea_adjust->dressmea_id)->first() ;
                            @endphp
                            {{ $dress_mea->mea_dress_name  }}
                            <span
                                style="color: #c40606 ; font-size: 13px;"> (ปรับได้
                                {{ $dress_mea->initial_min }}-{{ $dress_mea->initial_max }} นิ้ว)</span>

                        </p>
                    </div>

                    <div class="col-md-4" style="display: flex; align-items: center;">
                        <input type="hidden" value="{{ $dress_mea_adjust->id }}"
                            name="dress_mea_adjust_[]">
                        <input type="number" name="dress_mea_adjust_number_[]" class="form-control"
                            style="width: 50%; height: 60%; font-size: 15px; margin-right: 20px; margin-bottom: 1px;"
                            value="{{ $dress_mea_adjust->new_size }}" step="0.01"
                            min="{{ $dress_mea->initial_min}}"
                            max="{{ $dress_mea->initial_max}}">
                        <span style="margin-left: 5px; font-size: 15px;">นิ้ว</span>
                    </div>
                    <div class="col-md-4" style="padding-left: 1px; margin-top: 12px;">
                        @if ($dress_mea->current_mea != $dress_mea_adjust->new_size )
                        <p style="color: #eb3131">
                            ปรับเปลี่ยน: {{ $dress_mea->current_mea }} <i
                                class="bi bi-arrow-right"> </i>{{ $dress_mea_adjust->new_size }}นิ้ว
                        </p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="card-body">
            <div class="col-md-8">
                <p>รายละเอียดอื่นๆ</p><textarea name="note" id="" cols="1" rows="4" class="form-control">{{ $orderdetail->note }}</textarea>
                
            </div>
            <div class="row">
                <div class="col-md-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">ยืนยัน</button>
                </div>
            </div>
        </div>

    </div>



    </form>

</div>


@endsection