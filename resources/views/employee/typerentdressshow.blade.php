@extends('layouts.adminlayout')
@section('content')
    <div class="container">
        <h2 class="text-center my-4" style="font-weight: bold; color: #5a5a5a;">
            {{ $type_dress_name->specific_letter }}-{{ $type_dress_name->type_dress_name }}</h2>
        <div class="row" style="margin-top: 15px;">
            @foreach ($dress as $index => $item)
                @if ($item->dress_status == 'พร้อมให้เช่า')
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            <img src="{{ asset('storage/' . $item->dressimages->first()->dress_image) }}"
                                class="card-img-top img-fluid" alt="Dress Image"
                                style="max-height: 200px; object-fit: contain;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $item->dress_title_name }}</h5>
                                <h6>รหัสชุด: {{ $item->dress_code_new }}{{ $item->dress_code }}</h6>
                                <h6 style="color: red ; ">สถานะชุด: {{ $item->dress_status }}</h6>
                                <h6 style="color: red ; ">จำนวนชุด: {{ $item->dress_count }} ชุด</h6>
                                {{-- <p>ไอดีของชุด:{{ $item->id }}</p> --}}
                                <p>ราคาชุด:{{ $item->dress_price }}</p>
                                <p>ราคามัดจำ:{{ $item->dress_deposit }}</p>
                                {{-- <p>ประเภทชุด:{{ $type_dress_name->type_dress_name }}</p> --}}
                                {{-- <p>หมายเลขชุด:{{$item->dress_code_new}}{{ $item->dress_code }}</p> --}}

                            </div>
                            <div class="card-footer text-center">
                                <form action="{{ route('employee.addrentdresscart') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="dress_id" value="{{ $item->id }}">
                                    <input type="hidden" name="price_dress" value="{{ $item->dress_price }}">
                                    <input type="hidden" name="deposit_dress" value="{{ $item->dress_deposit }}">
                                    <input type="hidden" name="type_dress_name"
                                        value="{{ $type_dress_name->type_dress_name }}">
                                    <input type="hidden" name="dress_code" value="{{$item->dress_code_new}}{{ $item->dress_code }}">
                                    <input type="hidden" name="dress_color" value="{{$item->dress_color}}">

                                    <button class="btn btn-primary" type="submit">เพิ่มลงในตะกร้า</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endsection
