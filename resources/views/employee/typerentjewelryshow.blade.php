@extends('layouts.adminlayout')
@section('content')

<div class="container mt-4">
    <div class="row">
        @foreach ($jewelry as $item)
        @if($item->jewelry_status == "พร้อมให้เช่า")
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <img src="{{asset('storage/' .$item->jewelryimages->first()->jewelry_image)}}" alt="" width="50px;">
                    <h5 class="card-title text-primary font-weight-bold mt-2 mb-3">{{ $item->jewelry_title_name }}</h5>
                    <p class="card-text text-muted"><strong>รหัสชุด:</strong> {{ $item->jewelry_code_new }}{{ $item->jewelry_code }}</p>
                    <p class="card-text text-muted"><strong>สถานะชุด:</strong> {{ $item->jewelry_status }}</p>
                    <p class="card-text text-muted"><strong>ไอดีของชุด:</strong> {{ $item->id }}</p>
                    <p class="card-text text-muted"><strong>ราคา:</strong> {{ number_format($item->jewelry_price, 2) }} บาท</p>
                </div>
                <div class="card-footer text-center">
                    <form action="{{route('employee.addrentjewelrycart')}}" method="POST">
                        @csrf
                        <input type="hidden" name="jewelry_id" value="{{ $item->id }}">
                        <input type="hidden" name="jewelry_price" value="{{ $item->jewelry_price }}">
                        <input type="hidden" name="jewelry_deposit" value="{{ $item->jewelry_deposit }}">
                        <input type="hidden" name="type_jewelry_name" value="{{ $type_jewelry_name->type_jewelry_name }}">
                        <input type="hidden" name="jewelry_code" value="{{$item->jewelry_code_new}}{{ $item->jewelry_code }}">
                        <button class="btn btn-primary" type="submit">เพิ่มลงตะกร้า</button>
                    </form>
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>
</div>

@endsection
