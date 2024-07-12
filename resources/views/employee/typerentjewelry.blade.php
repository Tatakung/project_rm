@extends('layouts.adminlayout')

@section('content')
<div class="container mt-4">
    <div class="row">
        @foreach ($typejewelry as $item)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex align-items-center justify-content-center">
                    <a href="{{route('employee.typerentjewelryshow',['id' => $item->id])}}" class="text-decoration-none text-dark">
                        <h3 class="card-title text-primary font-weight-bold text-center">
                            {{$item->specific_letter}} - {{$item->type_jewelry_name}}
                            
                        </h3>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
