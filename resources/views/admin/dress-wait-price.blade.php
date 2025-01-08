@extends('layouts.adminlayout')

@section('content')
<div class="container mt-4">
    <div class="row">
        @foreach($dress_wait as $item)
        <div class="col-12 mb-3">
            <div class="card p-3 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-light d-flex justify-content-center align-items-center" 
                             style="width: 100px; height: 100px;">
                            <img src="{{ asset('storage/' .$item->dressimages->first()->dress_image) }}" alt="" width="60%">
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="mb-1">{{$item->typedress->type_dress_name}} {{$item->typedress->specific_letter}}{{$item->dress_code}}</h5>
                        <p class="text-muted mb-1">เพิ่มเข้าระบบเมื่อ: </p>
                        <p class="text-muted">ผู้เพิ่ม: ddd</p>
                    </div>
                    <div>
                        <span class="badge bg-warning text-dark">รอกำหนดราคา</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
