@extends('layouts.adminlayout')
@section('content')
<div class="container">
    <!-- Header Banner -->
    <!-- <div class="row bg-gradient" style="background: linear-gradient(45deg, #A7567F, #1B365D); padding: 40px 0; margin-bottom: 40px;">
        <div class="col-12 text-center">
            <h1 style="color: #EBE5AE;">ระบบจัดการร้านเช่าชุดและตัดชุด</h1>
            <p style="color: #F5F5F5;">ยินดีต้อนรับสู่แดชบอร์ดการจัดการ</p>
        </div>
    </div> -->

    <!-- <div class="row"> -->
    <!-- Summary Cards -->
    <!-- <div class="col-md-4 mb-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">ออเดอร์วันนี้</h5>
                    <p class="card-text" style="font-size: 2rem;">15</p>
                </div>
            </div>
        </div>
        {{-- <div class="col-md-3 mb-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">รายได้วันนี้</h5>
                    <p class="card-text" style="font-size: 2rem;">฿25,000</p>
                </div>
            </div>
        </div> --}}
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">ชุดที่ต้องส่งคืน</h5>
                    <p class="card-text" style="font-size: 2rem;">8</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">ชุดที่ต้องซ่อม</h5>
                    <p class="card-text" style="font-size: 2rem;">3</p>
                </div>
            </div>
        </div>
    </div> -->

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
                            <h5 class="card-title mt-3">ตัดชุด</h5>
                        </div>
                        <button class="btn custom-btn mt-3">
                            <a href="{{route('employee.addcutdress')}}" class="custom-link">เพิ่มออเดอร์</a>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="col-md-4 mb-4">
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