@extends('layouts.adminlayout')
@section('content')
    <div class="container mt-5">
        {{-- <a href="{{ route('admin.formadddress') }}">เพิ่มชุด</a> --}}

        <button class="btn btn-success">
            <a href="{{ route('admin.formadddress') }}" style="color: aliceblue">เพิ่มชุด</a>

        </button>

        <div class="row">
            <div class="col-md-12">
                    <h3 style="text-align: center ; ">หมวดหมู่ชุด</h3>
            </div>
        </div>
        <div class="row">
            @foreach ($showtype as $index => $item)
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">

                            <a href="{{ route('admin.typedress', ['id' => $item->id]) }}">
                                <h5 class="card-title" style="text-align: center ;">{{ $item->type_dress_name }}</h5>
                                @if ($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="" style="max-height: 400px; width: auto; margin: auto; display: block;">
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
