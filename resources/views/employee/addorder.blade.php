@extends('layouts.adminlayout')

@section('content')
<div class="container">
    <div class="row mt-4">
        <div class="col-12">
            <div class="row">
                <!-- Card 1 -->
                <div class="col-md-3">
                    <div class="card h-100">
                        <div class="card-body text-center d-flex flex-column justify-content-between">
                            <div>
                                <i class="bi bi-scissors" style="font-size: 2rem;"></i>
                                <h5 class="card-title mt-3">ตัดชุด</h5>
                            </div>
                            <button class="btn btn-primary mt-3">
                                <a href="{{route('employee.addcutdress')}}" style="color: rgb(255, 255, 255)">เพิ่มออเดอร์</a>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="col-md-3">
                    <div class="card h-100">
                        <div class="card-body text-center d-flex flex-column justify-content-between">
                            <div>
                                <i class="bi bi-shop" style="font-size: 2rem;"></i>
                                <h5 class="card-title mt-3">เช่าชุด</h5>
                            </div>
                            <button class="btn btn-primary mt-3">
                                <a href="{{route('employee.typerentdress')}}" style="color: rgb(255, 255, 255)">เพิ่มออเดอร์</a>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Card 3 -->
                {{-- <div class="col-md-3">
                    <div class="card h-100">
                        <div class="card-body text-center d-flex flex-column justify-content-between">
                            <div>
                                <i class="bi bi-gem" style="font-size: 2rem;"></i>
                                <h5 class="card-title mt-3">เช่าเครื่องประดับ</h5>
                            </div>
                            <button class="btn btn-primary mt-3">
                                <a href="{{route('employee.typerentjewelry')}}" style="color: rgb(255, 255, 255)">เพิ่มออเดอร์</a>
                            </button>
                        </div>
                    </div>
                </div> --}}
                <!-- Card 4 -->
                <div class="col-md-3">
                    <div class="card h-100">
                        <div class="card-body text-center d-flex flex-column justify-content-between">
                            <div>
                                <i class="bi bi-scissors" style="font-size: 2rem;"></i>
                                <h5 class="card-title mt-3">เช่าตัด</h5>
                            </div>
                            {{-- <button class="btn btn-primary mt-3">เพิ่มออเดอร์</button> --}}
                            <button class="btn btn-primary mt-3">
                                <a href="{{route('employee.addcutrent')}}" style="color: rgb(255, 255, 255)">เพิ่มออเดอร์</a>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
