@extends('layouts.adminlayout')
@section('content')
    <style>
        .hover-shadow {
            transition: all 0.3s ease;
        }

        .hover-shadow:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .transition-all {
            transition: all 0.3s ease;
        }
        .breadcrumb {
            background-color: transparent;
            font-size: 1rem;
        }

        .breadcrumb a {
            text-decoration: none;
            color: #333;
        }

        .breadcrumb-item.active {
            color: #6c757d;
        }
    </style>

    <div class="container mt-2">
        <!-- Search Form -->


        <div class="container-fluid py-1">
            <form action="{{ route('dashboardfilter') }}" method="GET" class="form-inline">
                @csrf
                <div class="form-group mb-2">
                    <div class="d-flex gap-2">
                        <select class="form-control" name="year" id="year" style="margin-right:0.2cm">
                            <option value="0">ทุกปี</option>
                            @for ($i = 2024; $i <= now()->year; $i++)
                                <option value="{{ $i }}" @if ($value_year == $i) selected @endif>
                                    {{ $i + 543 }}
                                </option>
                            @endfor
                        </select>
                        <select class="form-control" name="month" id="month" style="margin-right:0.2cm">
                            <option value="0" {{ $value_month == 0 ? 'selected' : '' }}>ทุกเดือน</option>
                            <option value="1" {{ $value_month == 1 ? 'selected' : '' }}>มกราคม</option>
                            <option value="2" {{ $value_month == 2 ? 'selected' : '' }}>กุมภาพันธ์</option>
                            <option value="3" {{ $value_month == 3 ? 'selected' : '' }}>มีนาคม</option>
                            <option value="4" {{ $value_month == 4 ? 'selected' : '' }}>เมษายน</option>
                            <option value="5" {{ $value_month == 5 ? 'selected' : '' }}>พฤษภาคม</option>
                            <option value="6" {{ $value_month == 6 ? 'selected' : '' }}>มิถุนายน</option>
                            <option value="7" {{ $value_month == 7 ? 'selected' : '' }}>กรกฎาคม</option>
                            <option value="8" {{ $value_month == 8 ? 'selected' : '' }}>สิงหาคม</option>
                            <option value="9" {{ $value_month == 9 ? 'selected' : '' }}>กันยายน</option>
                            <option value="10" {{ $value_month == 10 ? 'selected' : '' }}>ตุลาคม</option>
                            <option value="11" {{ $value_month == 11 ? 'selected' : '' }}>พฤศจิกายน</option>
                            <option value="12" {{ $value_month == 12 ? 'selected' : '' }}>ธันวาคม</option>
                        </select>

                        <button type="submit" class="btn" style="background-color:#BACEE6;">
                            <i class="bi bi-search"></i> ค้นหา
                        </button>
                    </div>
                </div>
            </form>
            <div class="row g-4 mt-2">
                <!-- Total Income Card -->
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card border-0 shadow h-100 hover-shadow transition-all">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle p-3 bg-opacity-10 me-3">
                                    {{-- <i class="bi bi-wallet-fill"></i> --}}
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1 fw-light">รายได้รวม</h6>
                                    <h4 class="mb-0 fw-bold">{{ number_format($income_success, 2) }} บาท</h4>
                                </div>
                            </div>
                            <div class="mt-3 text-success small">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Expenses Card -->
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card border-0 shadow h-40 hover-shadow transition-all">
                        <div class="card-body  d-flex flex-column h-100">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle p-3 bg-opacity-10 me-3">
                                    {{-- <i class="bi bi-credit-card-fill fs-3"></i> --}}
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1 fw-light">รายจ่ายรวม</h6>
                                    <h4 class="mb-0 fw-bold">{{ number_format($expense_success, 2) }} บาท</h4>
                                </div>
                            </div>
                            <div class="mt-3 text-danger small">
                                {{-- <i class="bi bi-clock-history me-1"></i> --}}
                                {{-- <span>อัพเดทล่าสุด</span> --}}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Transactions Card -->
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card border-0 shadow h-100 hover-shadow transition-all">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle p-3 bg-opacity-10 me-3">
                                    {{-- <i class="bi bi-file-earmark-text-fill fs-3"></i> --}}
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1 fw-light">จำนวนรายการ</h6>
                                    <h4 class="mb-0 fw-bold">{{ number_format($amount_success) }} รายการ</h4>
                                </div>
                            </div>
                            <div class="mt-3 text-success small">
                                {{-- <i class="bi bi-check-circle me-1"></i> --}}
                                {{-- <span>รายการทั้งหมด</span> --}}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Insurance Deposit Card -->
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card border-0 shadow h-100 hover-shadow transition-all">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle p-3 bg-opacity-10 me-3">
                                    {{-- <i class="bi bi-bank2 fs-3"></i> --}}
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1 fw-light">เงินประกันที่ยังไม่ได้คืน</h6>
                                    <h4 class="mb-0 fw-bold">{{ number_format($damage_insurance_success, 2) }} บาท</h4>
                                </div>
                            </div>
                            <div class="mt-3 text-warning small">
                                {{-- <i class="bi bi-exclamation-circle me-1"></i> --}}
                                {{-- <span>รอดำเนินการ</span>  --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>





            <!-- Chart Section -->
            <div class="row mb-1">
                {{-- <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">สัดส่วนรายได้แต่ละบริการ</h5>
                        <div class="row">
                            <div class="col-md-8">
                                <canvas id="serviceChart" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>ตัดชุด</span>
                            <span class="fw-bold">{{ $cut_dress_pie_count }} รายการ</span>
                        </div>


                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>เช่าชุด</span>
                            <span class="fw-bold">{{ $rent_dress_pie_count }} รายการ</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">


                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>เช่าเครื่องประดับ</span>
                            <span class="fw-bold">{{ $rent_jew_pie_count }} รายการ</span>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between py-2">
                            <span>เช่าตัดชุด</span>
                            <span class="fw-bold">{{ $rent_cut_dress_pie_count }} รายการ</span>
                        </div>
                    </div>
                </div>

            </div> --}}

                <!-- Revenue & Expense Chart Section -->
                {{-- <div class="row mt-4"> --}}
                <div class="col-12 mt-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">รายรับ - รายจ่าย(ทั้งหมด)ของร้าน</h5>
                            <div class="chart-container" style="position: relative; height:400px;">
                                <canvas id="revenueExpenseChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">รายรับ-แยกตามประเภทชุดเช่า</h5>
                            <div class="chart-container" style="position: relative; height:400px;">
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">รายรับ-แยกตามประเภทเครื่องประดับ</h5>
                            <div class="chart-container" style="position: relative; height:400px;">
                                <canvas id="jewelryRevenueChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">รายรับ-แยกตามประเภทเซตเครื่องประดับ</h5>
                            <div class="chart-container" style="position: relative; height:400px;">
                                <canvas id="jewelrySetRevenueChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">รายรับ-แยกตามประเภทงานที่สั่งตัด</h5>
                            <div class="chart-container" style="position: relative; height:400px;">
                                <canvas id="tailoringRevenueChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">รายรับ-แยกตามประเภทชุดที่เช่าตัด</h5>
                            <div class="chart-container" style="position: relative; height:400px;">
                                <canvas id="rentalTailoringRevenueChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>



                {{-- </div> --}}

            </div>

        </div>




        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // ดึงข้อมูลจาก Controller
            const monthsData = @json($monthsData);
            const revenueData = @json($revenueData);

            // สร้าง datasets จาก Object revenueData
            const datasets = Object.keys(revenueData).map(type => ({
                label: type,
                data: revenueData[type],
                backgroundColor: `rgba(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255}, 0.7)`
            }));

            // วาดกราฟ
            new Chart(document.getElementById('revenueChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: monthsData,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'รายรับ (บาท)',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                padding: {
                                    bottom: 10
                                },
                                color: '#333'
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
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'รายรับจากการเช่าชุด'
                        },
                        tooltip: {
                            callbacks: {
                                label: tooltipItem => ` ${tooltipItem.raw.toLocaleString()} บาท`
                            }
                        }
                    },
                    barPercentage: 0.8,
                    categoryPercentage: 0.9
                }
            });
        </script>


        <script>
            // ดึงข้อมูลจาก Controller
            const monthsJewelry = @json($monthsDataJewelry);
            const revenueJewelry = @json($revenueDataJewelry);

            // สร้าง datasets จาก Object revenueJewelry
            const jewelryDatasets = Object.keys(revenueJewelry).map(type => ({
                label: type,
                data: revenueJewelry[type],
                backgroundColor: `rgba(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255}, 0.7)`
            }));

            // วาดกราฟ
            new Chart(document.getElementById('jewelryRevenueChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: monthsJewelry,
                    datasets: jewelryDatasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'รายรับ (บาท)',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                padding: {
                                    bottom: 10
                                },
                                color: '#333'
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
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'รายรับจากการเช่าเครื่องประดับ'
                        },
                        tooltip: {
                            callbacks: {
                                label: tooltipItem => ` ${tooltipItem.raw.toLocaleString()} บาท`
                            }
                        }
                    },
                    barPercentage: 0.8,
                    categoryPercentage: 0.9
                }
            });
        </script>


        <script>
            // ดึงข้อมูลจาก Controller
            const monthsTailoring = @json($monthsDataTailoring);
            const revenueTailoring = @json($revenueDataTailoring);

            // สร้าง datasets จาก Object revenueTailoring
            const tailoringDatasets = Object.keys(revenueTailoring).map(type => ({
                label: type,
                data: revenueTailoring[type],
                backgroundColor: `rgba(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255}, 0.7)`
            }));

            // วาดกราฟ
            new Chart(document.getElementById('tailoringRevenueChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: monthsTailoring,
                    datasets: tailoringDatasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'รายรับ (บาท)',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                padding: {
                                    bottom: 10
                                },
                                color: '#333'
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
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'รายรับจากการตัดชุด'
                        },
                        tooltip: {
                            callbacks: {
                                label: tooltipItem => ` ${tooltipItem.raw.toLocaleString()} บาท`
                            }
                        }
                    },
                    barPercentage: 0.8,
                    categoryPercentage: 0.9
                }
            });
        </script>












        <script>
            // ดึงข้อมูลจาก Controller
            const monthsJewelrySet = @json($monthsDataJewelrySet);
            const revenueJewelrySet = @json($revenueDataJewelrySet);

            // สร้าง datasets จาก Object revenueJewelrySet
            const jewelrySetDatasets = Object.keys(revenueJewelrySet).map(type => ({
                label: type,
                data: revenueJewelrySet[type],
                backgroundColor: `rgba(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255}, 0.7)`
            }));

            // วาดกราฟ
            new Chart(document.getElementById('jewelrySetRevenueChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: monthsJewelrySet,
                    datasets: jewelrySetDatasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'รายรับ (บาท)',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                padding: {
                                    bottom: 10
                                },
                                color: '#333'
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
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'รายรับจากการเช่าเซตเครื่องประดับ'
                        },
                        tooltip: {
                            callbacks: {
                                label: tooltipItem => ` ${tooltipItem.raw.toLocaleString()} บาท`
                            }
                        }
                    },
                    barPercentage: 0.8,
                    categoryPercentage: 0.9
                }
            });
        </script>





















        <script>
            // โค้ด Chart กราฟวงกลมก่อนหน้า...
            // กราฟแท่งรายได้-รายจ่าย
            const revenueExpenseCtx = document.getElementById('revenueExpenseChart').getContext('2d');
            const label_bar = @json($label_bar); //เดือน
            const income_bar = @json($income_bar); //รายรับ
            const expense_bar = @json($expense_bar); //รายจ่าย
            new Chart(revenueExpenseCtx, {
                type: 'bar',
                data: {
                    labels: label_bar,
                    datasets: [{
                            label: 'รายรับ',
                            data: income_bar,
                            backgroundColor: '#36A2EB',
                            borderColor: '#36A2EB',
                            borderWidth: 1
                        },
                        {
                            label: 'รายจ่าย',
                            data: expense_bar,
                            backgroundColor: '#FF6384',
                            borderColor: '#FF6384',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f0f0f0'
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
                            position: 'top',
                            labels: {
                                boxWidth: 20,
                                padding: 20
                            }
                        },
                        title: {
                            display: true,
                            text: 'รายรับ - รายจ่าย',
                            padding: {
                                top: 10,
                                bottom: 30
                            }
                        },
                        subtitle: {
                            display: true,
                            text: 'แสดงผล',
                            padding: {
                                bottom: 10
                            }
                        }
                    },
                    barPercentage: 0.8,
                    categoryPercentage: 0.9
                }
            });
        </script>



        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('serviceChart').getContext('2d');
                const pie = @json($list_for_pie);
                const total = pie.reduce((sum, value) => sum + value, 0);
                const labels = ['เช่าชุด', 'เช่าเครื่องประดับ', 'ตัดชุด', 'เช่าตัดชุด'];

                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: pie,
                            backgroundColor: [
                                '#3B82F6', // สีน้ำเงิน
                                '#10B981', // สีเขียว
                                '#F59E0B', // สีเหลือง
                                '#EF4444' // สีแดง
                            ],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        let value = tooltipItem.raw;
                                        let percentage = ((value / total) * 100).toFixed(1); // คำนวณ %
                                        let formattedValue = value.toLocaleString(); // ใส่ , คั่นหลักพัน
                                        return `${labels[tooltipItem.dataIndex]}: ${formattedValue} บาท (${percentage}%)`;
                                    }
                                }
                            },
                            datalabels: {
                                color: '#fff',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                formatter: function(value) {
                                    let percentage = ((value / total) * 100).toFixed(1);
                                    return `${percentage}%`;
                                }
                            }
                        }
                    },
                    plugins: [ChartDataLabels]
                });
            });
        </script>




        <script>
            // ดึงข้อมูลจาก Controller
            const monthsRentalTailoring = @json($monthsDataRentalTailoring);
            const revenueRentalTailoring = @json($revenueDataRentalTailoring);

            // สร้าง datasets จาก Object revenueRentalTailoring
            const rentalTailoringDatasets = Object.keys(revenueRentalTailoring).map(type => ({
                label: type,
                data: revenueRentalTailoring[type],
                backgroundColor: `rgba(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255}, 0.7)`
            }));

            // วาดกราฟ
            new Chart(document.getElementById('rentalTailoringRevenueChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: monthsRentalTailoring,
                    datasets: rentalTailoringDatasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'รายรับ (บาท)',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                padding: {
                                    bottom: 10
                                },
                                color: '#333'
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
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'รายรับจากการเช่าตัดชุด'
                        },
                        tooltip: {
                            callbacks: {
                                label: tooltipItem => ` ${tooltipItem.raw.toLocaleString()} บาท`
                            }
                        }
                    },
                    barPercentage: 0.8,
                    categoryPercentage: 0.9
                }
            });
        </script>
    </div>
@endsection
