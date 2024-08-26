@extends('layouts.adminlayout')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="display-4">จัดการการส่งซ่อม</h1>
            <p class="lead">ดูและอัพเดตสถานะการซ่อมของชุด</p>
        </div>
    </div>

    <!-- Status Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">รอดำเนินการ</h5>
                    <p class="card-text display-4">8 รายการ</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">กำลังซ่อม</h5>
                    <p class="card-text display-4">12 รายการ</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">ซ่อมเสร็จแล้ว</h5>
                    <p class="card-text display-4">7 รายการ</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <h5 class="card-title">ไม่สามารถซ่อมได้</h5>
                    <p class="card-text display-4">3 รายการ</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Repair List -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-tools me-1"></i>
            รายการชุดที่ต้องซ่อม
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>หมายเลขชุด</th>
                            <th>ชื่อชุด</th>
                            <th>สถานะการซ่อม</th>
                            <th>การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>RP001</td>
                            <td>ชุดเดรสสั้น</td>
                            <td><span class="badge bg-danger">ต้องซ่อม</span></td>
                            <td>
                                <a href="#" class="btn btn-info btn-sm">
                                    อัพเดตสถานะ
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>RP002</td>
                            <td>ชุดสูท</td>
                            <td><span class="badge bg-warning">กำลังซ่อม</span></td>
                            <td>
                                <a href="#" class="btn btn-info btn-sm">
                                    อัพเดตสถานะ
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
