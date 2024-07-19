@extends('layouts.adminlayout')
@section('content')
    <div class="container mt-5">
        <div class="row">
            @foreach ($data as $index => $item)
                <div class="col-md-3 mb-4">
                    <a href="{{route('admin.dressdetail',['id'=>$item->id])}}">
                        <div class="card">
                            <div class="card-body text-center">
                                <!-- ใช้ text-center เพื่อจัดให้เนื้อหาภายในการ์ดอยู่ตรงกลาง -->
                                <p class="card-title">รหัสชุด&nbsp;{{ $item->dress_code_new }}{{ $item->dress_code }}</p>
                                
                                @if ($item->dressimages->isNotEmpty())
                                    <img src="{{ asset('storage/' . $item->dressimages->first()->dress_image) }}"
                                        alt="" style="max-height: 300px; width: auto; margin: auto; display: block;">
                                @endif
                            </div>
                        </div>
                    </a>

                </div>
                @if (($index + 1) % 4 == 0)
        </div>
        <div class="row mt-4">
            @endif
            @endforeach
        </div>
    </div>
@endsection
