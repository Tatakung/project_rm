@extends('layouts.adminlayout')
@section('content')
    <div class="container mt-5">
        <div class="row">
            @foreach ($datajewelry as $index => $item)
                <div class="col-md-3">
                    <div class="card">
                        <a href="{{route('admin.jewelrydetail',['id' => $item->id])}}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $item->jewelry_title_name }}</h5>
                                <p class="card-title">รหัสชุด&nbsp;{{ $item->jewelry_code_new }}{{ $item->jewelry_code }}</p>
                                @if ($item->jewelryimages->isNotEmpty())
                                    <img src="{{ asset('storage/' . $item->jewelryimages->first()->jewelry_image) }}"
                                        alt="" style="max-height: 300px; width: auto; margin: auto; display: block;">
                                @endif
                            </div>
                        </a>
                    </div>
                </div>
                @if (($index + 1) % 4 == 0)
        </div>
        <div class="row">
            @endif
            @endforeach
        </div>
    </div>
@endsection
