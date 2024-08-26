@extends('layouts.adminlayout')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="display-4">หน้าหลัก</h1>
            <p class="lead">ข้อมูลสำคัญเกี่ยวกับการจองชุด</p>
        </div>
    </div>

    <!-- Alert Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-warning" role="alert">
                <h5 class="alert-heading">การแจ้งเตือน</h5>
                <p>มีชุดที่ต้องปรับขนาดให้ลูกค้า</p>
                <hr>
                <p class="mb-0">โปรดตรวจสอบและแก้ไขขนาดชุดที่ลูกค้าจองไว้ด้านล่าง:</p>
            </div>
        </div>
    </div>

    <!-- Reservations Section -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-calendar-check me-1"></i>
            รายการชุดที่ต้องแก้ไขและวันที่ลูกค้าจะมารับ
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ชื่อชุด</th>
                            <th>ประเภทชุด</th>
                            <th>สถานะแก้ไข</th>
                            <th>วันที่จอง</th>
                            <th>วันที่นัดรับ</th>
                            <th>อีก ... วัน</th>
                            <th>การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- สมมุติข้อมูล -->
                        <tr>
                            <td>ชุดไทย A06 (กระโปรง)</td>
                            <td>ชุดไทย</td>
                            <td><span class="badge bg-warning">ยังไม่ได้แก้ไข</span></td>
                            <td>1 มกราคม 2567</td>
                            <td>10 มกราคม 2567</td>
                            <td>อีก 5 วัน</td>
                            <td>
                                <a href="#" class="btn btn-info btn-sm">
                                    แก้ไขขนาด
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>ชุดราตรี (ทั้งชุด)</td>
                            <td>ชุดราตรี</td>
                            <td><span class="badge bg-success">แก้ไขแล้ว</span></td>
                            <td>5 มกราคม 2567</td>
                            <td>15 มกราคม 2567</td>
                            <td>อีก 10 วัน</td>
                            <td>
                                <a href="#" class="btn btn-info btn-sm">
                                    แก้ไขขนาด
                                </a>
                            </td>
                        </tr>
                        <!-- เพิ่มเติมข้อมูลตามต้องการ -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
