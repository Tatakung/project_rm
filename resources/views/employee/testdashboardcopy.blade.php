@extends('layouts.adminlayout')
@section('content')
    <div class="container">

        <div class="row mt-5">
            <h5 >แดชบอร์ดแสดงผลสำหรับเช่าชุด</h5>
        </div>

        <div class="card mt-5 mb-5">
            <div class="card-body">
                <form action="" method="GET" class="form-inline">
                    @csrf
                    <div class="form-group mb-2">
                        <label for="month" class="mr-2"></label>
                        <select class="form-control mr-2" name="month" id="month">
                            <option value="0">ทุกเดือน</option>
                            <option value="1">มกราคม</option>
                            <option value="2">กุมภาพันธ์</option>
                            <option value="3">มีนาคม</option>
                            <option value="4">เมษายน</option>
                            <option value="5">พฤษภาคม</option>
                            <option value="6">มิถุนายน</option>
                            <option value="7">กรกฎาคม</option>
                            <option value="8">สิงหาคม</option>
                            <option value="9">กันยายน</option>
                            <option value="10">ตุลาคม</option>
                            <option value="11">พฤศจิกายน</option>
                            <option value="12">ธันวาคม</option>
                        </select>

                        <select class="form-control mr-2" name="year" id="year">
                            <option value="0">ทุกปี</option>
                            @for ($i = 2020; $i <= now()->year; $i++)
                                <option value="{{ $i }}">
                                    {{ $i + 543 }}</option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit" class="btn btn-s mb-2" style="background-color:#BACEE6 ;"><i
                            class="bi bi-search"></i> ฟิลเตอร์</button>
                </form>
            </div>
        </div>



        <div class="row mb-5">
            <div class="col-md-6">
                <div class="card" style="max-width: 2300px; margin: auto;"> <!-- จำกัดความกว้าง -->
                    <div class="card-header text-center">
                        <h5>2024 Sales</h5>
                        <p class="text-muted">All products including Taxes</p>
                    </div>
                    <div class="card-body">
                        <div style="width: 100%; max-width: 2300px; margin: auto ; height: 500px;">
                            <canvas id="monthlySalesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                ใส่แผนภูมิวงกลมประเภทชุดที่ยอดนิยม
            </div>
        </div>

        






        {{-- <div class="container mt-4"> --}}
            <div class="row">
                <!-- กรอบที่ 1: สถานะชุด (col-md-6) -->
                <div class="col-md-6">
                    <div class="row">
                        <!-- แถวที่ 1 -->
                        <div class="col-md-6">
                            <div class="card text-center bg-primary text-white">
                                <div class="card-body">
                                    <h5>ชุดที่กำลังถูกเช่า</h5>
                                    <h2>2 ชุด</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card text-center bg-warning text-white">
                                <div class="card-body">
                                    <h5>ชุดที่ถูกจองล่วงหน้า</h5>
                                    <h2>6 ชุด</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <!-- แถวที่ 2 -->
                        <div class="col-md-6">
                            <div class="card text-center bg-success text-white">
                                <div class="card-body">
                                    <h5>ชุดที่รอส่งคืน</h5>
                                    <h2>12 ชุด</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card text-center bg-danger text-white">
                                <div class="card-body">
                                    <h5>ชุดที่เสียหาย</h5>
                                    <h2>3 ชุด</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        
                <!-- กรอบที่ 2: การเงิน (col-md-6) -->
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-12">
                            <div class="card text-center bg-info text-white">
                                <div class="card-body">
                                    <h5>เงินประกันชุดที่ยังไม่ได้คืน</h5>
                                    <h2>xxxx บาท</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card text-center bg-dark text-white">
                                <div class="card-body">
                                    <h5>รายได้จากการเช่าชุดทั้งหมด</h5>
                                    <h2>xxxx บาท</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        {{-- </div> --}}
        


        







        










    </div>

    <!-- เพิ่ม Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('monthlySalesChart').getContext('2d');

            // ข้อมูลจาก Controller (ต้องแน่ใจว่า $monthlySales มีค่าถูกต้อง)
            const monthlySales = @json($monthlySales);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: Object.keys(monthlySales), // เดือนต่างๆ
                    datasets: [{
                            label: 'Product 1',
                            data: Object.values(monthlySales).map(month => month.product1),
                            backgroundColor: 'rgba(0, 192, 239, 0.8)',
                            borderWidth: 1
                        },
                        {
                            label: 'Product 2',
                            data: Object.values(monthlySales).map(month => month.product2),
                            backgroundColor: 'rgba(236, 61, 61, 0.8)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // ปิดอัตราส่วนเดิม
                    aspectRatio: 2, // ปรับให้กราฟเล็กลง
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [2],
                                drawBorder: false,
                                color: '#e9ecef'
                            },
                            ticks: {
                                stepSize: 100
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    barPercentage: 0.6, // ลดขนาดความกว้างของแท่ง
                    categoryPercentage: 0.7 // ลดระยะห่างของแต่ละแท่ง
                }
            });
        });
    </script>
@endsection
