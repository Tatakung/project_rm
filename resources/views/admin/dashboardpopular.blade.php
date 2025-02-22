@extends('layouts.adminlayout')
@section('content')
    <div class="container mt-2">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('dashboardpopularfiltershop') }}" method="GET" class="form-inline">
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
    </div>




    <div class="container mt-4">
        <!-- หัวข้อหลัก -->
        <h1 class="text-center mb-2" style="font-size: 24px;">สถิติ4รายการแรกที่นิยมมากที่สุด</h1>
        {{-- <p class="text-center text-muted mb-5">สินค้าขายดี สินค้าใหม่จาก
            OWNDAYS<br>กรอบแว่นพร้อมเลนส์มัลติโค้ทย์อบางมีค่าสายตา</p> --}}

        <!-- Navigation Tabs -->
        <div class="row">
            <div class="col-12">
                <div class="position-relative">
                    <!-- Main Tabs -->
                    <div class="d-flex justify-content-center border-0">
                        <div class="position-relative">
                            <div class="nav-tabs-wrapper">
                                <ul class="nav custom-tabs" role="tablist">

                                    <li class="nav-item">
                                        <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all"
                                            role="tab" aria-controls="all" aria-selected="true">เครื่องประดับ</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" id="men-tab" href="#men" role="tab"
                                            aria-controls="men" aria-selected="true">เซตเครื่องประดับ</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#women" id="women-tab"
                                            aria-controls="women" aria-selected="true" role="tab">ชุดเช่า</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#accessories" role="tab"
                                            id="accessories-tab" aria-controls="accessories" aria-selected="true">ชุดตัด</a>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Underline -->
                    <div class="tab-underline"></div>
                </div>

                <!-- Tab Content -->
                <div class="tab-content mt-4">
                    <!-- ตัดชุด -->
                    <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                        <div class="row">
                            @if (!empty($list_popular_jew))
                                @foreach ($list_popular_jew as $index => $item)
                                    @php
                                        $jewelry = App\Models\Jewelry::find($index);
                                        $typejewelry = App\Models\Typejewelry::find($jewelry->type_jewelry_id);
                                        $image_jew = App\Models\Jewelryimage::where(
                                            'jewelry_id',
                                            $jewelry->id,
                                        )->first();
                                    @endphp
                                    <div class="col-md-3 mb-4">
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
                                            <div class="product-image mb-3">
                                                <img src="{{ asset('storage/' . $image_jew->jewelry_image) }}"
                                                    alt="SR1007B-4A C1" class="img-fluid">
                                            </div>
                                            <div class="product-name mb-2">
                                                {{ $typejewelry->type_jewelry_name }}{{ $typejewelry->specific_letter }}{{ $jewelry->jewelry_code }}
                                            </div>
                                            <div class="product-code text-muted small mb-2">ถูกเช่า {{ $item }}
                                                ครั้ง</div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p style="text-align: center ; ">ไม่มีรายการแสดงผล</p>
                            @endif
                        </div>
                    </div>



                    <!-- เช่าชุด -->
                    <div class="tab-pane fade" id="men" role="tabpanel" aria-labelledby="men-tab">
                        <div class="row">
                            @if (!empty($list_popular_jew_set))
                                @foreach ($list_popular_jew_set as $index => $item)
                                    @php
                                        $jewelryset = App\Models\Jewelryset::find($index);
                                    @endphp
                                    <div class="col-md-3 mb-4">
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
                                            <div class="product-image mb-3">
                                                <img src="{{ asset('images/setjewelry.jpg') }}" alt="SR1007B-4A C1"
                                                    class="img-fluid">
                                            </div>
                                            <div class="product-name mb-2">
                                                {{ $jewelryset->set_name }}
                                            </div>
                                            <div class="product-code text-muted small mb-2">ถูกเช่า {{ $item }}
                                                ครั้ง</div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p style="text-align: center ; ">ไม่มีรายการแสดงผล</p>
                            @endif
                        </div>
                    </div>


                    <div class="tab-pane fade" id="women" role="tabpanel" aria-labelledby="women-tab">
                        <div class="row">
                            @if (!empty($list_popular_dress))
                                @foreach ($list_popular_dress as $item)
                                    @php
                                        $dress = App\Models\Dress::find($item[0]);
                                        $typedress = App\Models\Typedress::find($dress->type_dress_id);
                                        $imagedress = App\Models\Dressimage::where('dress_id', $dress->id)->first();
                                    @endphp
                                    <div class="col-md-3 mb-4">
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
                                            <div class="product-image mb-3">
                                                <img src="{{ asset('storage/' . $imagedress->dress_image) }}"
                                                    alt="SR1007B-4A C1" class="img-fluid">
                                            </div>
                                            <div class="product-name mb-2">
                                                {{ $typedress->type_dress_name }}{{ $typedress->specific_letter }}{{ $dress->dress_code }}
                                                @if ($item[2] == 30)
                                                    (ทั้งชุด)
                                                @elseif($item[2] == 20)
                                                    (ผ้าถุง)
                                                @elseif($item[2] == 10)
                                                    (เสื้อ)
                                                @endif
                                            </div>
                                            <div class="product-code text-muted small mb-2">ถูกเช่า {{ $item[1] }}
                                                ครั้ง</div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p style="text-align: center ; ">ไม่มีรายการแสดงผล</p>
                            @endif
                        </div>
                    </div>

                    <!-- เช่าตัดชุด -->
                    <div class="tab-pane fade" id="accessories" role="tabpanel" aria-labelledby="accessories-tab">
                        <div class="row">
                            @if (!empty($list_popular_cut_dress))
                                @foreach ($list_popular_cut_dress as $index => $item)
                                    
                                    <div class="col-md-3 mb-4">
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
                                            <div class="product-image mb-3">
                                                <img src="" alt="ไม่มี" class="img-fluid">
                                            </div>
                                            <div class="product-name mb-2">
                                                {{ $index }}
                                            </div>
                                            <div class="product-code text-muted small mb-2">สั่งตัด {{ $item }}
                                                ครั้ง</div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p style="text-align: center ; ">ไม่มีรายการแสดงผล</p>
                            @endif
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
@endsection
