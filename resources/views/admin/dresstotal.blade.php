@extends('layouts.adminlayout')
@section('content')
    <div class="container mt-5">
        <a href="{{ route('admin.formadddress') }}">เพิ่มชุด</a>
        <div class="row">
            @foreach ($showtype as $index => $item)
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">

                            <a href="{{ route('admin.typedress', ['id' => $item->id]) }}">
                                <h5 class="card-title">{{ $item->type_dress_name }}</h5>
                                @if ($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="" style="max-height: 300px; width: auto; margin: auto; display: block;">
                                @else
                                    <p>No image available</p>
                                @endif


                            </a>
                        </div>
                    </div>
                </div>
                @if (($index + 1) % 4 == 0)
        </div>&nbsp;<div class="row">
            @endif
            @endforeach
        </div>
    </div>
@endsection
