@extends('layouts.adminlayout')
@section('content')
<div class="container">


    <div class="row">
    <div class="col-12">
        <h2 class="py-4 text-center">เพิ่มออเดอร์ใหม่</h2>
        <div class="row">
            <!-- Card 1 -->
            <div class="col-md-4 mb-4">
                <div class="card custom-card">
                    <div class="card-body text-center d-flex flex-column justify-content-between">
                        <div>
                            <i class="bi bi-scissors card-icon"></i>
                            <h5 class="card-title mt-3">ตัดเย็บชุด</h5>
                        </div>
                        <button class="btn custom-btn mt-3">
                            <a href="{{route('employee.addcutdress')}}" class="custom-link">เพิ่มออเดอร์</a>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Card 2 -->
            {{-- <div class="col-md-4 mb-4">
                <div class="card custom-card">
                    <div class="card-body text-center d-flex flex-column justify-content-between">
                        <div>
                            <i class="bi bi-shop card-icon"></i>
                            <h5 class="card-title mt-3">เช่าชุด</h5>
                        </div>
                        <button class="btn custom-btn mt-3">
                            <a href="{{route('employee.selectdate')}}" class="custom-link">เพิ่มออเดอร์</a>
                        </button>
                    </div>
                </div>
            </div> --}}
            <div class="col-md-4 mb-4">
                <div class="card custom-card">
                    <div class="card-body text-center d-flex flex-column justify-content-between">
                        <div>
                            <i class="bi bi-shop card-icon"></i>
                            <h5 class="card-title mt-3">เช่าชุด</h5>
                        </div>
                        <button class="btn custom-btn mt-3">
                            {{-- <a href="{{route('adddresstocart')}}" class="custom-link">เพิ่มออเดอร์</a> --}}
                            <a href="{{route('employee.addrentdresstocard')}}" class="custom-link">เพิ่มออเดอร์</a>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="col-md-4 mb-4">
                <div class="card custom-card">
                    <div class="card-body text-center d-flex flex-column justify-content-between">
                        <div>
                            <i class="bi bi-bag-heart card-icon"></i>
                            <h5 class="card-title mt-3">เช่าตัด</h5>
                        </div>
                        <button class="btn custom-btn mt-3">
                            <a href="" class="custom-link">เพิ่มออเดอร์</a>
                        </button>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Additional Information -->
</div>

<style>
    .btn:hover {
        transform: translateY(-5px);
    }

    .card {
        transition: all 0.3s;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .custom-card {
        background-color: #f5f5f5;
        border: none;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Card icon styles */
    .card-icon {
        font-size: 3rem;
        color: #A67B5B;
    }

    /* Card title styles */
    .card-title {
        color: #000000;
    }

    /* Button styles */
    .custom-btn {
        background: #A67B5B;
        border: none;
        transition: all 0.3s;
    }

    /* Link styles */
    .custom-link {
        color: #ffffff;
        text-decoration: none;
    }
    a:hover {
        color: #ffffff !important; 
    }
</style>
@endsection