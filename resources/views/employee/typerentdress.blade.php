@extends('layouts.adminlayout')
@section('content')
    <div class="container mt-5">
        <div class="row">
            @foreach ($typedress as $index => $item)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">

                            <a href="{{route('employee.typerentdressshow',['id' => $item->id])}}">
                                <h5>{{$item->id}}</h5>
                                <h5 class="card-title">{{ $item->specific_letter }}-{{ $item->type_dress_name }}</h5>
                            </a>
                        </div>
                    </div>
                </div>
                @if (($index + 1) % 3 == 0)
        </div>&nbsp;<div class="row">
            @endif
            @endforeach
        </div>
    </div>
@endsection

