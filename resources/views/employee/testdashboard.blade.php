@extends('layouts.adminlayout')

@section('content')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <div class="container-fluid p-4">
        <div class="card shadow-lg">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">แดชบอร์ดสำหรับเช่าชุด</h3>
                <div class="d-flex">
                    <select class="form-control form-select-sm me-2" style="width: 120px;">
                        <option value="all">ทุกปี</option>
                        <option value="2024">ปี 2024</option>
                        <option value="2023">ปี 2023</option>
                    </select>
                    <select class="form-control form-select-sm" style="width: 120px;">
                        <option value="all">ทุกเดือน</option>
                        <option value="01">มกราคม</option>
                        <option value="02">กุมภาพันธ์</option>
                        <option value="03">มีนาคม</option>
                        <option value="04">เมษายน</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <div class="me-3 text-primary"><i class="fas fa-chart-line fa-2x"></i></div>
                                <div>
                                    <h5 class="card-title text-muted mb-1">รายได้</h5>
                                    <div class="h4 mb-0">฿90,000</div>
                                    {{-- <small class="text-success">+15% จากเดือนที่แล้ว</small> --}}
                                    <small class="text-success">ทั้งหมด</small>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <div class="me-3 text-success"><i class="fas fa-calendar fa-2x"></i></div>
                                <div>
                                    <h5 class="card-title text-muted mb-1">การเช่าทั้งหมด</h5>
                                    <div class="h4 mb-0">60</div>
                                    <small class="text-muted">จำนวนครั้งการเช่า</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <div class="me-3 text-purple"><i class="fas fa-users fa-2x"></i></div>
                                <div>
                                    <h5 class="card-title text-muted mb-1">ลูกค้าทั้งหมด</h5>
                                    <div class="h4 mb-0">50</div>
                                    <small class="text-muted">จำนวนลูกค้า</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <div class="me-3 text-danger"><i class="fas fa-clock fa-2x"></i></div>
                                <div>
                                    <h5 class="card-title text-muted mb-1">การคืนล่าช้า</h5>
                                    <div class="h4 mb-0">4</div>
                                    <small class="text-danger">จำนวนครั้งการคืนช้า</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">รายได้แยกตามประเภทชุด</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="revenueByTypeChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>





                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">อันดับชุดยอดนิยม</h5>
                            </div>
                            <div class="card-body">
                                <div class="list-group">
                                    <div
                                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">ชุดราตรี</h6>
                                            <small class="text-muted">25 ครั้ง</small>
                                        </div>
                                        <span class="badge bg-success rounded-pill">฿37,500</span>
                                    </div>
                                    <div
                                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">ชุดไทยจิตลดา</h6>
                                            <small class="text-muted">18 ครั้ง</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">฿27,000</span>
                                    </div>
                                    <div
                                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">ชุดราตรี</h6>
                                            <small class="text-muted">15 ครั้ง</small>
                                        </div>
                                        <span class="badge bg-warning rounded-pill">฿22,500</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">กราฟรายได้และการเช่ารายเดือน</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="barChart" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function () {
    // Horizontal Bar Chart for Revenue by Costume Type
    var revenueByTypeChartCanvas = $('#revenueByTypeChart').get(0).getContext('2d')
    var revenueByTypeData = {
        labels: ['ชุดไทยจิตลดา', 'ชุดราตรี', 'ชุดไทย', 'ชุดราตรี'],
        datasets: [{
            data: [37500, 27000, 22500, 15000],
            backgroundColor: [
                'rgba(255, 99, 132, 0.7)',   // ชมพู
                'rgba(54, 162, 235, 0.7)',   // น้ำเงิน
                'rgba(255, 206, 86, 0.7)',   // เหลือง
                'rgba(75, 192, 192, 0.7)'    // เขียว
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1
        }]
    }

    new Chart(revenueByTypeChartCanvas, {
        type: 'bar',
        data: {
            labels: revenueByTypeData.labels,
            datasets: revenueByTypeData.datasets
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'รายได้ (บาท)'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    })

    // Bar Chart
    var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartData = {
        labels: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.'],
        datasets: [
            {
                label: 'รายได้ (บาท)',
                data: [67500, 78000, 90000, 82500],
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            },
            {
                label: 'จำนวนการเช่า',
                data: [45, 52, 60, 55],
                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }
        ]
    }

    var barChartOptions = {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                position: 'top',
            }
        }
    }

    new Chart(barChartCanvas, {
        type: 'bar',
        data: barChartData,
        options: barChartOptions
    })
})
    </script>
@endsection
