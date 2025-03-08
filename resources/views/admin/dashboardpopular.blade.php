@extends('layouts.adminlayout')
@section('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>



    <div class="container mt-4">
        <!-- หัวข้อหลัก -->
        {{-- <h1 class="text-center mb-2" style="font-size: 24px;">สถิติ4รายการแรกที่นิยมมากที่สุด</h1> --}}
        {{-- <p class="text-center text-muted mb-5">สินค้าขายดี สินค้าใหม่จาก
            OWNDAYS<br>กรอบแว่นพร้อมเลนส์มัลติโค้ทย์อบางมีค่าสายตา</p> --}}

        <!-- Navigation Tabs -->
        <div class="row">
            <div class="col-12">
                <div class="position-relative">
                    <form action="{{ route('dashboardpopularfiltershop') }}" method="GET" class="form-inline">
                        @csrf
                        <div class="form-group mb-2">
                            <div class="d-flex gap-2">
                                <select class="form-control" name="year" id="year">
                                    <option value="0">ทุกปี</option>
                                    @for ($i = 2020; $i <= now()->year; $i++)
                                        <option value="{{ $i }}"
                                            @if ($value_year == $i) selected @endif>
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
                    <div class="d-flex justify-content-center border-0">
                        <div class="position-relative">
                            <div class="nav-tabs-wrapper">
                                <ul class="nav custom-tabs" role="tablist">

                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#jewelry_aria" role="tab"
                                            aria-controls="jewelry_aria"
                                            aria-selected="true"><strong>เครื่องประดับ</strong></a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#setjew" role="tab"
                                            aria-controls="setjew"
                                            aria-selected="true"><strong>เซตเครื่องประดับ</strong></a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#dresser" aria-controls="dresser"
                                            aria-selected="true" role="tab"><strong>ชุดเช่า</strong></a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#cut_dresstab" role="tab"
                                            id="cut_dresstab-tab" aria-controls="cut_dresstab"
                                            aria-selected="true"><strong>ชุดตัด</strong></a>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Underline -->
                    <div class="tab-underline"></div>
                </div>


                <div class="tab-content mt-4">

                    <div class="tab-pane fade show active" id="jewelry_aria" role="tabpanel"
                        aria-labelledby="jewelry_aria-tab">
                        <div class="container">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <h5 class="card-title">จำนวนครั้งการถูกเช่า-แยกตามประเภทเครื่องประดับ</h5>
                                    <div class="chart-container" style="position: relative; height:350px;">
                                        <canvas id="jewelryRevenueChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                @if (!empty($list_popular_jew))
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="alll-tab" data-bs-toggle="tab"
                                            data-bs-target="#alll" type="button" role="tab">ทั้งหมด</button>
                                    </li>
                                @else
                                    <p style="text-align: center ; ">ไม่มีรายการแสดงผล</p>
                                @endif
                                @foreach ($l_for_type_jew as $type_jew)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" data-bs-toggle="tab"
                                            data-bs-target="#jew-{{ $type_jew }}" type="button" role="tab">
                                            @php
                                                $nametypejew = App\Models\Typejewelry::where('id', $type_jew)->value(
                                                    'type_jewelry_name',
                                                );
                                            @endphp
                                            {{ $nametypejew }}
                                        </button>
                                    </li>
                                @endforeach

                            </ul>

                            <div class="tab-content mt-3" id="myTabContent">
                                <div class="tab-pane fade show active" id="alll" role="tabpanel">
                                    <div class="row">
                                        @if (!empty($list_popular_jew))
                                            @foreach ($list_popular_jew as $index => $item)
                                                @php
                                                    $jewelry = App\Models\Jewelry::find($index);
                                                    $typejewelry = App\Models\Typejewelry::find(
                                                        $jewelry->type_jewelry_id,
                                                    );
                                                    $image_jew = App\Models\Jewelryimage::where(
                                                        'jewelry_id',
                                                        $jewelry->id,
                                                    )->first();
                                                @endphp


                                                <div class="col-md-4 mb-4">
                                                    <div class="product-card text-center">
                                                        <div class="crown-icon mb-3">

                                                            @if ($loop->iteration == 1 || $loop->iteration == 2 || $loop->iteration == 3)
                                                                <svg width="60" height="60" viewBox="0 0 24 24">
                                                                    <path
                                                                        d="M2.5 4.5l3.75 8.5h11.5l3.75-8.5L17 11.5l-5-4.5-5 4.5z"
                                                                        fill="#FFD700" />

                                                                    <text x="12" y="11" font-size="6" font-weight="bold"
                                                                        text-anchor="middle"
                                                                        fill="black">{{ $loop->iteration }}</text>
                                                                </svg>
                                                            @endif

                                                        </div>
                                                        <div class="product-image mb-3">
                                                            <img src="{{ asset('storage/' . $image_jew->jewelry_image) }}"
                                                                alt="SR1007B-4A C1" class="img-fluid">
                                                        </div>
                                                        <div class="product-name mb-2">
                                                            {{ $typejewelry->type_jewelry_name }}{{ $typejewelry->specific_letter }}{{ $jewelry->jewelry_code }}
                                                        </div>
                                                        <div class="product-code text-muted small mb-2">ถูกเช่า
                                                            {{ $item }}
                                                            ครั้ง</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                @foreach ($l_for_type_jew as $type_jew)
                                    <div class="tab-pane fade" id="jew-{{ $type_jew }}" role="tabpanel">
                                        <div class="row">
                                            @php
                                                $count_jew = 0;
                                            @endphp
                                            @if (!empty($list_popular_jew))
                                                @foreach ($list_popular_jew as $index => $item)
                                                    @php
                                                        $jewelry = App\Models\Jewelry::find($index);
                                                        $typejewelry = App\Models\Typejewelry::find(
                                                            $jewelry->type_jewelry_id,
                                                        );
                                                        $image_jew = App\Models\Jewelryimage::where(
                                                            'jewelry_id',
                                                            $jewelry->id,
                                                        )->first();
                                                    @endphp

                                                    @if ($type_jew == $typejewelry->id)
                                                        <div class="col-md-4 mb-4">
                                                            <div class="product-card text-center">
                                                                <div class="crown-icon mb-3">
                                                                    @php
                                                                        $count_jew++;
                                                                    @endphp
                                                                    @if ($count_jew == 1 || $count_jew == 2 || $count_jew == 3)
                                                                        <svg width="60" height="60"
                                                                            viewBox="0 0 24 24">
                                                                            <path
                                                                                d="M2.5 4.5l3.75 8.5h11.5l3.75-8.5L17 11.5l-5-4.5-5 4.5z"
                                                                                fill="#FFD700" />
                                                                            <text x="12" y="11" font-size="6"
                                                                                font-weight="bold" text-anchor="middle"
                                                                                fill="black">{{ $count_jew }}</text>
                                                                        </svg>
                                                                    @endif
                                                                </div>
                                                                <div class="product-image mb-3">
                                                                    <img src="{{ asset('storage/' . $image_jew->jewelry_image) }}"
                                                                        alt="SR1007B-4A C1" class="img-fluid">
                                                                </div>
                                                                <div class="product-name mb-2">
                                                                    {{ $typejewelry->type_jewelry_name }}{{ $typejewelry->specific_letter }}{{ $jewelry->jewelry_code }}
                                                                </div>
                                                                <div class="product-code text-muted small mb-2">ถูกเช่า
                                                                    {{ $item }}
                                                                    ครั้ง</div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>


                    <div class="tab-pane fade" id="setjew" role="tabpanel2" aria-labelledby="setjew-tab">
                        <div class="container">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <h5 class="card-title">รายรับ-แยกตามประเภทเซตเครื่องประดับ</h5>
                                    <div class="chart-container" style="position: relative; height:350px;">
                                        <canvas id="jewelrySetRevenueChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <ul class="nav nav-tabs" role="tablist">
                                @if (!empty($list_popular_jew_set))
                                    <li class="nav-item" role="presentation2">
                                        <button class="nav-link active" data-bs-toggle="tab"
                                            data-bs-target="#alll_set_jew" type="button" role="tab">ทั้งหมด</button>
                                    </li>
                                @else
                                    <p style="text-align: center ; ">ไม่มีรายการแสดงผล</p>
                                @endif
                            </ul>

                            <div class="tab-content mt-3" id="myTabContent">
                                <div class="tab-pane fade show active" id="alll_set_jew" role="tabpanel2">
                                    <div class="row">
                                        @if (!empty($list_popular_jew_set))
                                            @foreach ($list_popular_jew_set as $index => $item)
                                                @php
                                                    $jewelryset = App\Models\Jewelryset::find($index);
                                                @endphp
                                                <div class="col-md-4 mb-4">
                                                    <div class="product-card text-center">
                                                        <div class="crown-icon mb-3">
                                                            @if ($loop->iteration == 1 || $loop->iteration == 2 || $loop->iteration == 3)
                                                                <svg width="60" height="60" viewBox="0 0 24 24">
                                                                    <!-- มงกุฎ -->
                                                                    <path
                                                                        d="M2.5 4.5l3.75 8.5h11.5l3.75-8.5L17 11.5l-5-4.5-5 4.5z"
                                                                        fill="#FFD700" />

                                                                    <!-- เลข 1 -->
                                                                    <text x="12" y="11" font-size="6" font-weight="bold"
                                                                        text-anchor="middle"
                                                                        fill="black">{{ $loop->iteration }}</text>
                                                                </svg>
                                                            @endif
                                                        </div>
                                                        <div class="product-image mb-3">
                                                            <img src="{{ asset('images/setjewelry.jpg') }}"
                                                                alt="SR1007B-4A C1" class="img-fluid">
                                                        </div>
                                                        <div class="product-name mb-2">
                                                            เซต{{ $jewelryset->set_name }}
                                                        </div>
                                                        <div class="product-code text-muted small mb-2">ถูกเช่า
                                                            {{ $item }}
                                                            ครั้ง</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>


                            </div>

                        </div>
                    </div>


                    <div class="tab-pane fade" id="dresser" role="tabpanel3" aria-labelledby="dresser-tab">
                        <div class="container">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <h5 class="card-title">รายรับ-แยกตามประเภทชุดเช่า</h5>
                                    <div class="chart-container" style="position: relative; height:350px;">
                                        <canvas id="revenueChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <ul class="nav nav-tabs" role="tablist">
                                @if (!empty($list_popular_dress))
                                    <li class="nav-item" role="presentation3">
                                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#alll_dress"
                                            type="button" role="tab">ทั้งหมด</button>
                                    </li>
                                @else
                                    <p style="text-align: center ; ">ไม่มีรายการแสดงผล</p>
                                @endif
                                @foreach ($list_for_tab_type_dress as $typedress)
                                    <li class="nav-item" role="presentation3">
                                        <button class="nav-link" data-bs-toggle="tab"
                                            data-bs-target="#dresser-{{ $typedress }}" type="button" role="tab">
                                            @php
                                                $nametypedress = App\Models\Typedress::where('id', $typedress)->value(
                                                    'type_dress_name',
                                                );
                                            @endphp
                                            {{ $nametypedress }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content mt-3" id="myTabContent">
                                <div class="tab-pane fade show active" id="alll_dress" role="tabpanel3">
                                    <div class="row">
                                        @if (!empty($list_popular_dress))
                                            @foreach ($list_popular_dress as $item)
                                                @php
                                                    $dress = App\Models\Dress::find($item[0]);
                                                    $datatypedress = App\Models\Typedress::find($dress->type_dress_id);
                                                    $imagedress = App\Models\Dressimage::where(
                                                        'dress_id',
                                                        $dress->id,
                                                    )->first();
                                                @endphp
                                                <div class="col-md-4 mb-4">
                                                    <div class="product-card text-center">
                                                        <div class="crown-icon mb-3">

                                                            @if ($loop->iteration == 1 || $loop->iteration == 2 || $loop->iteration == 3)
                                                                <svg width="60" height="60" viewBox="0 0 24 24">
                                                                    <!-- มงกุฎ -->
                                                                    <path
                                                                        d="M2.5 4.5l3.75 8.5h11.5l3.75-8.5L17 11.5l-5-4.5-5 4.5z"
                                                                        fill="#FFD700" />

                                                                    <!-- เลข 1 -->
                                                                    <text x="12" y="11" font-size="6" font-weight="bold"
                                                                        text-anchor="middle"
                                                                        fill="black">{{ $loop->iteration }}</text>
                                                                </svg>
                                                            @endif
                                                        </div>
                                                        <div class="product-image mb-3">
                                                            <img src="{{ asset('storage/' . $imagedress->dress_image) }}"
                                                                alt="SR1007B-4A C1" class="img-fluid">
                                                        </div>
                                                        <div class="product-name mb-2">
                                                            {{ $datatypedress->type_dress_name }}{{ $datatypedress->specific_letter }}{{ $dress->dress_code }}
                                                            @if ($item[2] == 30)
                                                                (ทั้งชุด)
                                                            @elseif($item[2] == 20)
                                                                (ผ้าถุง)
                                                            @elseif($item[2] == 10)
                                                                (เสื้อ)
                                                            @endif
                                                        </div>
                                                        <div class="product-code text-muted small mb-2">ถูกเช่า
                                                            {{ $item[1] }}
                                                            ครั้ง</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                @foreach ($list_for_tab_type_dress as $typedress)
                                    <div class="tab-pane fade show" id="dresser-{{ $typedress }}" role="tabpanel3">



                                        <div class="row">
                                            @if (!empty($list_popular_dress))
                                                @php
                                                    $dress_count = 0;
                                                @endphp
                                                @foreach ($list_popular_dress as $item)
                                                    @php
                                                        $dress = App\Models\Dress::find($item[0]);
                                                        $datatypedress = App\Models\Typedress::find(
                                                            $dress->type_dress_id,
                                                        );
                                                        $imagedress = App\Models\Dressimage::where(
                                                            'dress_id',
                                                            $dress->id,
                                                        )->first();
                                                    @endphp

                                                    @if ($typedress == $datatypedress->id)
                                                        <div class="col-md-4 mb-4">
                                                            <div class="product-card text-center">
                                                                <div class="crown-icon mb-3">
                                                                    @php
                                                                        $dress_count++;
                                                                    @endphp

                                                                    @if ($dress_count == 1 || $dress_count == 2 || $dress_count == 3)
                                                                        <svg width="60" height="60"
                                                                            viewBox="0 0 24 24">
                                                                            <!-- มงกุฎ -->
                                                                            <path
                                                                                d="M2.5 4.5l3.75 8.5h11.5l3.75-8.5L17 11.5l-5-4.5-5 4.5z"
                                                                                fill="#FFD700" />

                                                                            <!-- เลข 1 -->
                                                                            <text x="12" y="11" font-size="6"
                                                                                font-weight="bold" text-anchor="middle"
                                                                                fill="black">{{ $dress_count }}</text>
                                                                        </svg>
                                                                    @endif
                                                                </div>
                                                                <div class="product-image mb-3">
                                                                    <img src="{{ asset('storage/' . $imagedress->dress_image) }}"
                                                                        alt="SR1007B-4A C1" class="img-fluid">
                                                                </div>
                                                                <div class="product-name mb-2">
                                                                    {{ $datatypedress->type_dress_name }}{{ $datatypedress->specific_letter }}{{ $dress->dress_code }}
                                                                    @if ($item[2] == 30)
                                                                        (ทั้งชุด)
                                                                    @elseif($item[2] == 20)
                                                                        (ผ้าถุง)
                                                                    @elseif($item[2] == 10)
                                                                        (เสื้อ)
                                                                    @endif
                                                                </div>
                                                                <div class="product-code text-muted small mb-2">ถูกเช่า
                                                                    {{ $item[1] }}
                                                                    ครั้ง</div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>




                    <!-- ชุดตัด -->
                    <div class="tab-pane fade" id="cut_dresstab" role="tabpanel4" aria-labelledby="cut_dresstab-tab">


                        <div class="container">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <h5 class="card-title">รายรับ-แยกตามประเภทชุดที่สั่งตัด</h5>
                                    <div class="chart-container" style="position: relative; height:350px;">
                                        <canvas id="tailoringRevenueChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <ul class="nav nav-tabs" role="tablist">
                                @if (!empty($list_popular_cut_dress))
                                    <li class="nav-item" role="presentation4">
                                        <button class="nav-link active" data-bs-toggle="tab"
                                            data-bs-target="#alll_dress_cut" type="button"
                                            role="tab">ทั้งหมด</button>
                                    </li>
                                @else
                                    <p style="text-align: center ; ">ไม่มีรายการแสดงผล</p>
                                @endif
                                @foreach ($list_popular_cut_dress as $typedresscut => $rent_count)
                                    <li class="nav-item" role="presentation4">
                                        <button class="nav-link" data-bs-toggle="tab"
                                            data-bs-target="#dress_cut-{{ $loop->iteration }}" type="button"
                                            role="tab">
                                            {{ $typedresscut }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content mt-3" id="myTabContent">
                                <div class="tab-pane fade show active" id="alll_dress_cut" role="tabpanel4">

                                    <div class="row">
                                        @if (!empty($list_popular_cut_dress))
                                            @foreach ($list_popular_cut_dress as $index => $item)
                                                <div class="col-md-4 mb-4">
                                                    <div class="product-card text-center">
                                                        <div class="crown-icon mb-3">

                                                            {{-- <svg width="60" height="60" viewBox="0 0 24 24">
                                                                <!-- มงกุฎ -->
                                                                <path d="M2.5 4.5l3.75 8.5h11.5l3.75-8.5L17 11.5l-5-4.5-5 4.5z"
                                                                    fill="#FFD700" />
            
                                                                <!-- เลข 1 -->
                                                                <text x="12" y="11" font-size="6" font-weight="bold"
                                                                    text-anchor="middle" fill="black">1</text>
                                                            </svg> --}}
                                                        </div>
                                                        <div class="product-image mb-3"
                                                            style="width: 100%; max-width: 300px; height: auto; display: flex; justify-content: center; align-items: center;">
                                                            <div
                                                                style="width: 100%; aspect-ratio: 3/4; border-radius: 2px; display: flex; justify-content: center; align-items: center; background-color: #f8f9fa;">
                                                                <i class="bi bi-scissors" style="font-size: 48px;"></i>
                                                            </div>
                                                        </div>

                                                        <div class="product-name mb-2">
                                                            {{ $index }}
                                                        </div>
                                                        <div class="product-code text-muted small mb-2">สั่งตัด
                                                            {{ $item }}
                                                            ครั้ง</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                </div>
                                @foreach ($list_popular_cut_dress as $typedresscut => $rent_count)
                                    <div class="tab-pane fade show " id="dress_cut-{{ $loop->iteration }}"
                                        role="tabpanel4">

                                        <div class="row">
                                            @if (!empty($list_popular_cut_dress))
                                                @foreach ($list_popular_cut_dress as $index => $item)
                                                    @if ($typedresscut == $index)
                                                        <div class="col-md-4 mb-4">
                                                            <div class="product-card text-center">
                                                                <div class="crown-icon mb-3">

                                                                    {{-- <svg width="60" height="60" viewBox="0 0 24 24">
                                                                <!-- มงกุฎ -->
                                                                <path d="M2.5 4.5l3.75 8.5h11.5l3.75-8.5L17 11.5l-5-4.5-5 4.5z"
                                                                    fill="#FFD700" />
            
                                                                <!-- เลข 1 -->
                                                                <text x="12" y="11" font-size="6" font-weight="bold"
                                                                    text-anchor="middle" fill="black">1</text>
                                                            </svg> --}}
                                                                </div>
                                                                <div class="product-image mb-3"
                                                                    style="width: 100%; max-width: 300px; height: auto; display: flex; justify-content: center; align-items: center;">
                                                                    <div
                                                                        style="width: 100%; aspect-ratio: 3/4; border-radius: 2px; display: flex; justify-content: center; align-items: center; background-color: #f8f9fa;">
                                                                        <i class="bi bi-scissors"
                                                                            style="font-size: 48px;"></i>
                                                                    </div>
                                                                </div>

                                                                <div class="product-name mb-2">
                                                                    {{ $index }}
                                                                </div>
                                                                <div class="product-code text-muted small mb-2">สั่งตัด
                                                                    {{ $item }}
                                                                    ครั้ง</div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <p style="text-align: center ; ">ไม่มีรายการแสดงผล</p>
                                            @endif
                                        </div>

                                    </div>
                                @endforeach









                            </div>

                        </div>


                    </div>



                </div>
            </div>
        </div>
    </div>

    <!-- ใส่ CSS ทั้งหมดจากทั้งสองส่วนที่เราเขียนไว้ก่อนหน้านี้ -->
    <style>
        /* CSS สำหรับ Tabs */
        .custom-tabs {
            border: none;
            margin-bottom: -1px;
            position: relative;
            z-index: 2;
        }

        .custom-tabs .nav-link {
            border: none;
            color: #999;
            font-size: 16px;
            padding: 10px 30px;
            margin: 0 10px;
            transition: all 0.3s;
            position: relative;
        }

        .custom-tabs .nav-link.active {
            color: #000;
            background: none;
        }

        .custom-tabs .nav-link:hover {
            color: #000;
        }

        .tab-underline {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: #e0e0e0;
            z-index: 1;
        }

        /* CSS สำหรับ Product Cards */
        .product-card {
            position: relative;
            padding: 20px;
        }

        .product-image img {
            width: 200px;
            /* ปรับขนาดตามต้องการ */
            height: 200px;
            /* ปรับขนาดตามต้องการ */
            object-fit: cover;
            /* ครอบรูปภาพให้เต็มขนาดที่กำหนด */
            border-radius: 8px;
            /* ถ้าต้องการให้ขอบโค้ง */
        }


        .favorite-btn {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .crown-icon {
            position: absolute;
            top: 10px;
            left: 10px;
        }

        .product-name {
            font-weight: 500;
        }

        .product-price {
            color: #666;
        }

        .product-code {
            font-size: 0.9em;
            color: #999;
        }

        .pagination {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .page-number {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: #f0f0f0;
            cursor: pointer;
        }

        .page-number.active {
            background-color: #666;
            color: white;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // ดึงข้อมูลจาก Controller
        const monthsJewelry = @json($monthsDataJewelry_chart);
        const revenueJewelry = @json($revenueDataJewelry_chart);

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
                            text: 'จำนวนครั้งในการเช่า (ครั้ง)',
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
                        text: 'จำนวนครั้งจากการเช่าเครื่องประดับ'
                    },
                    tooltip: {
                        callbacks: {
                            label: tooltipItem => ` ${tooltipItem.raw.toLocaleString()} ครั้ง`
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
        const monthsJewelrySet = @json($monthsDataJewelryset_chart);
        const revenueJewelrySet = @json($revenueDataJewelryset_chart);

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
                            text: 'จำนวนครั้งในการเช่า (ครั้ง)',
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
                        text: 'จำนวนครั้งจากการเช่าเซตเครื่องประดับ'
                    },
                    tooltip: {
                        callbacks: {
                            label: tooltipItem => ` ${tooltipItem.raw.toLocaleString()} ครั้ง`
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
        const monthsData = @json($monthsDatadress);
        const revenueData = @json($revenueDatadress);

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
                            text: 'จำนวนครั้งในการเช่า (ครั้ง)',
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
                        text: 'จำนวนครั้งจากการเช่าชุด'
                    },
                    tooltip: {
                        callbacks: {
                            label: tooltipItem => ` ${tooltipItem.raw.toLocaleString()} ครั้ง`
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
                            text: 'จำนวนครั้งในการเข่า่ (ครั้ง)',
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
                        text: 'จำนวนครั้งในการตัดชุด'
                    },
                    tooltip: {
                        callbacks: {
                            label: tooltipItem => ` ${tooltipItem.raw.toLocaleString()} ครั้ง`
                        }
                    }
                },
                barPercentage: 0.8,
                categoryPercentage: 0.9
            }
        });
    </script>













@endsection
