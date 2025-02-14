@extends('layouts.adminlayout')
@section('content')
    <div class="container mt-2">
        <!-- Search Form -->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('dashboardfilter') }}" method="GET" class="form-inline">
                    @csrf
                    <div class="form-group mb-2">
                        <div class="d-flex gap-2">
                            <select class="form-control" name="year" id="year">
                                <option value="0">ทุกปี</option>
                                @for ($i = 2020; $i <= now()->year; $i++)
                                    <option value="{{ $i }}" @if ($value_year == $i) selected @endif>
                                        {{ $i + 543 }}
                                    </option>
                                @endfor
                            </select>
                            <select class="form-control" name="month" id="month">
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
                                <i class="bi bi-search"></i> ฟิลเตอร์
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="container-fluid py-4">
            <div class="row g-4">
                <!-- Total Income Card -->
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 hover-shadow transition-all">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle p-3 bg-opacity-10 me-3">
                                    <i class="bi bi-wallet-fill"></i>
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
                    <div class="card border-0 shadow-sm h-100 hover-shadow transition-all">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle p-3 bg-opacity-10 me-3">
                                    <i class="bi bi-credit-card-fill fs-3"></i>
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
                    <div class="card border-0 shadow-sm h-100 hover-shadow transition-all">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle p-3 bg-opacity-10 me-3">
                                    <i class="bi bi-file-earmark-text-fill fs-3"></i>
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
                    <div class="card border-0 shadow-sm h-100 hover-shadow transition-all">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle p-3 bg-opacity-10 me-3">
                                    <i class="bi bi-bank2 fs-3"></i>
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
        </div>

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
        </style>


        <!-- Chart Section -->
        <div class="row mb-4">
            <div class="col-6">
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








            </div>

            <!-- Revenue & Expense Chart Section -->
            {{-- <div class="row mt-4"> --}}
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">รายได้ - รายจ่าย</h5>
                        <div class="chart-container" style="position: relative; height:400px;">
                            <canvas id="revenueExpenseChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            {{-- </div> --}}

        </div>




        <h5 class="card-title mb-4">สถิติ4รายการแรกที่นิยมมากที่สุด</h5>
        <div class="row g-1">
            <!-- เครื่องประดับที่นิยมเช่า -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 hover-shadow transition-all">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle p-3 bg-opacity-10 me-3">
                                <i class="bi bi-gem fs-3"></i>
                            </div>
                            <h6 class="card-title mb-0">เครื่องประดับที่นิยมเช่ามากที่สุด</h6>
                        </div>
                        @if (!empty($list_popular_jew))
                            @foreach ($list_popular_jew as $index => $item)
                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <span>
                                        @php
                                            $jewelry = App\Models\Jewelry::find($index);
                                            $typejewelry = App\Models\Typejewelry::find($jewelry->type_jewelry_id);
                                        @endphp
                                        {{ $typejewelry->type_jewelry_name }}{{ $typejewelry->specific_letter }}{{ $jewelry->jewelry_code }}
                                    </span>
                                    <span class="fw-bold">{{ $item }} ครั้ง</span>
                                </div>
                            @endforeach
                        @else
                            <p style="text-align: center ; ">ไม่มีรายการแสดงผล</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- เซตเครื่องประดับ -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 hover-shadow transition-all">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle p-3 bg-opacity-10 me-3">
                                <i class="bi bi-stars fs-3"></i>
                            </div>
                            <h6 class="card-title mb-0">เซตเครื่องประดับที่นิยมเช่ามากที่สุด</h6>
                        </div>
                        @if (!empty($list_popular_jew_set))
                            @foreach ($list_popular_jew_set as $index => $item)
                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <span>
                                        @php
                                            $jewelryset = App\Models\Jewelryset::find($index);
                                        @endphp
                                        {{ $jewelryset->set_name }}
                                    </span>
                                    <span class="fw-bold">{{ $item }} ครั้ง</span>
                                </div>
                            @endforeach
                        @else
                            <p style="text-align: center ; ">ไม่มีรายการแสดงผล</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- ชุดที่นิยมเช่า -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 hover-shadow transition-all">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle p-3 bg-opacity-10 me-3">
                                <i class="bi bi-bag-heart fs-3"></i>
                            </div>
                            <h6 class="card-title mb-0">ชุดที่นิยมเช่ามากที่สุด</h6>
                        </div>
                        @foreach ($list_popular_dress as $item)
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span>
                                    @php
                                        $dress = App\Models\Dress::find($item[0]) ; 
                                        $typedress = App\Models\Typedress::find($dress->type_dress_id) ; 
                                    @endphp
                                    {{$typedress->type_dress_name}}{{$typedress->specific_letter}}{{$dress->dress_code}}
                                    @if($item[2] == 30)
                                    (ทั้งชุด)
                                    @elseif($item[2] == 20)
                                    (ผ้าถุง)
                                    @elseif($item[2] == 10)
                                    (เสื้อ)
                                    @endif
                                
                                </span>
                                <span class="fw-bold">{{$item[1]}} ครั้ง</span>
                            </div>
                        @endforeach


                    </div>
                </div>
            </div>

            <!-- ชุดที่นิยมตัด -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 hover-shadow transition-all">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle p-3 bg-opacity-10 me-3">
                                <i class="bi bi-scissors fs-3"></i>
                            </div>
                            <h6 class="card-title mb-0">ประเภทชุดที่นิยมตัดมากที่สุด</h6>
                        </div>
                        @if (!empty($list_popular_cut_dress))
                            @foreach ($list_popular_cut_dress as $index => $item)
                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <span>

                                        {{ $index }}
                                    </span>
                                    <span class="fw-bold">{{ $item }} ครั้ง</span>
                                </div>
                            @endforeach
                        @else
                            <p style="text-align: center ; ">ไม่มีรายการแสดงผล</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>










        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            // โค้ด Chart กราฟวงกลมก่อนหน้า...
            // กราฟแท่งรายได้-รายจ่าย
            const revenueExpenseCtx = document.getElementById('revenueExpenseChart').getContext('2d');
            const label_bar = @json($label_bar);
            const income_bar = @json($income_bar);
            const expense_bar = @json($expense_bar);
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















    </div>
@endsection
