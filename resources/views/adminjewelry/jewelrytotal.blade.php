@extends('layouts.adminlayout')
@section('content')
    <div class="container mt-5">
        <a href="{{route('admin.formaddjewelry')}}">เพิ่มเครื่องประดับ</a>
        <div class="row">
            @foreach ($showtype as $index => $item)
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{route('admin.typejewelry',['id' => $item->id])}}">
                                <h5 class="card-title">{{ $item->specific_letter }}-{{ $item->type_jewelry_name }}</h5>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
            @if (($index + 1) % 4 == 0)
        </div>&nbsp;<div class="row">
        @endif
    </div>
@endsection
