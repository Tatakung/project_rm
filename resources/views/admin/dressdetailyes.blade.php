@extends('layouts.adminlayout')
@section('content')
    <style>
        .modal-body {
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-header {
            background-color: #ffffff;
            font-weight: bold;
        }
    </style>

    <ol class="breadcrumb" style="background: white; ">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dresstotal') }}" style="color: black ; ">จัดการชุด</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.typedress', ['id' => $datadress->type_dress_id]) }}"
                style="color: black ;">ประเภท{{ $name_type }}</a>
        </li>
        <li class="breadcrumb-item active">
            รายละเอียดของหมายเลขชุด{{ $datadress->dress_code_new }}{{ $datadress->dress_code }}</li>

    </ol>

    <div class="row">
        <div class="col">
            <h2 class="py-4" style="text-align: center">รายละเอียดของหมายเลขชุด
                {{ $datadress->dress_code_new }}{{ $datadress->dress_code }}</h2>
        </div>
    </div>
    <div class="container">



        <div class="card mb-4 shadow-sm bg-body rounded">
            {{-- <div class="card-header">
                <i class="bi bi-info-circle"></i> รายละเอียดชุด
                <button class="btn btn-link p-0 ml-2 float-right" data-toggle="modal" data-target="#edittotal">
                    <i class="bi bi-pencil-square text-dark"></i>
                </button>
            </div> --}}

            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="dress-tab" data-toggle="tab" href="#dress" role="tab"
                        aria-controls="dress" aria-selected="true">ข้อมูลชุด</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="shirt-tab" data-toggle="tab" href="#shirt" role="tab" aria-controls="shirt"
                        aria-selected="false">ข้อมูลเสื้อ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pants-tab" data-toggle="tab" href="#pants" role="tab" aria-controls="pants"
                        aria-selected="false">ข้อมูลกระโปรง</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">

                <!-- ข้อมูลชุด -->
                <div class="tab-pane fade show active" id="dress" role="tabpanel" aria-labelledby="dress-tab">
                    <div class="card-header">
                        <i class="bi bi-info-circle"></i> รายละเอียดชุด
                        <button class="btn btn-link p-0 ml-2 float-right" data-toggle="modal" data-target="#edittotal">
                            <i class="bi bi-pencil-square text-dark"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="d-flex">
                                @foreach ($imagedata as $image)
                                    <div class="col-md-4">
                                        <img src="{{ asset('storage/' . $image->dress_image) }}" alt=""
                                            style="max-height: 350px; width: auto;">
                                    </div>
                                @endforeach
                            </div>

                            <div class="col-md-4">
                                <p><strong>ประเภทชุด:</strong> {{ $name_type }}</p>
                                <!-- <p><strong>หมายเลขชุด:</strong> {{ $datadress->dress_code_new }}{{ $datadress->dress_code }}
                                                                        </p> -->
                                <p><strong>สถานะชุด:</strong> <span
                                        @if ($datadress->dress_status == 'พร้อมให้เช่า') style="color: green;" @else style="color: red;" @endif>
                                        {{ $datadress->dress_status }}</span></p>


                                <p><strong>สถานะปัจจุบันของชุด</strong></p>
                                <ul>
                                    <li>เสื้อ : {{ $text_check_status_shirt }}</li>
                                    <li>ผ้าถุง : {{ $text_check_status_skirt }}</li>
                                </ul>

                                <p><strong>จำนวนชุด:</strong> {{ $datadress->dress_count }} ชุด</p>
                                <p><strong>ราคา:</strong> {{ number_format($datadress->dress_price, 2) }} บาท</p>
                                <p><strong>ราคามัดจำ:</strong> {{ number_format($datadress->dress_deposit, 2) }} บาท</p>
                                <p><strong>ราคาประกันค่าเสียหาย:</strong>
                                    {{ number_format($datadress->damage_insurance, 2) }} บาท</p>


                                <p><strong>จำนวนครั้งที่ถูกเช่า:</strong> {{ $datadress->dress_rental }} ครั้ง</p>
                                <p><strong>คำอธิบายชุด:</strong> {{ $datadress->dress_description }}</p>
                            </div>
                            <div class="col-md-5">
                                <p>
                                    <strong>ขนาดของชุด</strong> (ปรับแก้ ขยาย/ลด ไม่เกิน 4 นิ้ว):
                                <div class=" ">
                                    @php
                                        $list_check_name_shirt = [];
                                    @endphp
                                    <table class="table table-bordered-0">
                                        @foreach ($dress_mea_totaldress as $dress_mea_totaldress)
                                            <tr>
                                                <td>{{ $dress_mea_totaldress->mea_dress_name }}<span
                                                        style="font-size: 12px; color: rgb(197, 21, 21)">(ปรับได้
                                                        {{ $dress_mea_totaldress->initial_mea - 4 }}-{{ $dress_mea_totaldress->initial_mea + 4 }})</span>
                                                </td>
                                                <td col-1>{{ $dress_mea_totaldress->current_mea }} </td>
                                                <td col-1>นิ้ว</td>
                                            </tr>
                                            {{-- @php
                                                $list_check_name_shirt[] = $item->measurementnow_dress_name;
                                            @endphp --}}
                                        @endforeach
                                    </table>

                                </div>
                                </p>
                            </div>
                        </div>
                    </div>


                    <div class="container">
                        <h3>คิวการเช่า</h3>
                        @if ($reservation_dress->count() > 0)
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ลำดับคิว</th>
                                        <th>ชื่อลูกค้า</th>
                                        <th>วันที่นัดรับ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reservation_dress as $index => $reservation_dress)
                                        <tr>
                                            <td>คิวที่ {{ $index + 1 }} </td>
                                            <td>
                                                @php
                                                    $order_id = App\Models\Orderdetail::where(
                                                        'reservation_id',
                                                        $reservation_dress->id,
                                                    )->value('order_id');
                                                    $customer_id = App\Models\Order::where('id', $order_id)->value(
                                                        'customer_id',
                                                    );
                                                    $customer = App\Models\Customer::find($customer_id);
                                                @endphp
                                                คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($reservation_dress->start_date)->locale('th')->isoFormat('D MMM') }}
                                                {{ \Carbon\Carbon::parse($reservation_dress->start_date)->year + 543 }}
                                                <span style="color: red ; "
                                                    id="showday{{ $reservation_dress->id }}"></span>
                                            </td>


                                            <script>
                                                var now = new Date();
                                                var start_date = new Date("{{ $reservation_dress->start_date }}");
                                                var day = start_date - now;

                                                var totalday = Math.ceil(day / (1000 * 60 * 60 * 24));

                                                document.getElementById('showday{{ $reservation_dress->id }}').innerHTML = 'เหลืออีก ' + totalday + ' วัน !';
                                            </script>




                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <h6 style="text-align: center ; ">ไม่มีคิวการเช่าชุดนี้ !</h6>
                        @endif
                    </div>




                </div>

                <!-- ข้อมูลเสื้อ -->
                <div class="tab-pane fade" id="shirt" role="tabpanel" aria-labelledby="shirt-tab">
                    <div class="card-header">
                        <i class="bi bi-info-circle"></i> รายละเอียดเสื้อ
                        <button class="btn btn-link p-0 ml-2 float-right" data-toggle="modal" data-target="#edittotalshirt">
                            <i class="bi bi-pencil-square text-dark"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- เพิ่มข้อมูลเสื้อที่นี่ -->
                        <div class="row">
                            <div class="d-flex">
                                @foreach ($imagedata as $image)
                                    <div class="col-md-4">
                                        <img src="{{ asset('storage/' . $image->dress_image) }}" alt=""
                                            style="max-height: 350px; width: auto;">
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-md-4">
                                <p>
                                    <strong>สถานะเสื้อปัจุบัน:</strong>
                                    <span>
                                        {{ $text_check_status_shirt }}
                                    </span>
                                </p>
                                <p><strong>จำนวนเสื้อ:</strong> 1 ตัว</p>
                                <p><strong>ราคา:</strong> {{ number_format($shirtitem->shirtitem_price, 2) }} บาท</p>
                                <p><strong>ราคามัดจำ:</strong> {{ number_format($shirtitem->shirtitem_deposit, 2) }} บาท
                                </p>
                                <p><strong>ราคาประกันค่าเสียหาย:</strong>
                                    {{ number_format($shirtitem->shirt_damage_insurance, 2) }} บาท</p>
                                <p><strong>จำนวนครั้งที่ถูกเช่า:</strong> {{ $shirtitem->shirtitem_rental }} ครั้ง</p>
                                @php
                                    $shirt_id = App\Models\Shirtitem::where('dress_id', $datadress->id)->value('id');

                                    $list_one = [];
                                    $list_success = [];
                                    $reservation_find_shirt_id = App\Models\Reservation::where(
                                        'shirtitems_id',
                                        $shirt_id,
                                    )->get();
                                    $reservation_find_dress_id = App\Models\Reservation::where(
                                        'dress_id',
                                        $datadress->id,
                                    )->get();
                                    foreach ($reservation_find_shirt_id as $key => $re) {
                                        $list_one[] = $re->id;
                                    }
                                    foreach ($reservation_find_dress_id as $key => $dr) {
                                        $list_one[] = $dr->id;
                                    }
                                    $list_one_unique = array_unique($list_one);

                                    foreach ($list_one_unique as $reservation_id) {
                                        $repair = App\Models\Repair::where('reservation_id', $reservation_id)
                                            ->whereIn('repair_type', ['10', '20'])
                                            ->get();
                                        if ($repair->isNotEmpty()) {
                                            foreach ($repair as $key => $re_success) {
                                                $list_success[] = $re_success->id;
                                            }
                                        }
                                    }
                                    $historyrepair_shirt = App\Models\Repair::whereIn('id', $list_success)->get();
                                @endphp
                                <p><strong>จำนวนครั้งที่ซ่อม:</strong> {{$historyrepair_shirt->count()}} ครั้ง
                                    <button class="btn btn-secondary" type="button" data-toggle="modal"
                                        data-target="#showhistory_repair_shirt">ประวัติการซ่อม</button>
                                </p>


                                {{-- modalประวัติการซ่อมชุด --}}
                                <div class="modal fade" id="showhistory_repair_shirt" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">ประวัติการซ่อม</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                @if ($historyrepair_shirt->count() > 0)
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>วันที่</th>
                                                                <th>รายการ</th>
                                                                <th>สถานะ</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($historyrepair_shirt as $repair)
                                                                <tr>
                                                                    <td>{{ $repair->created_at }}</td>
                                                                    <td>{{ $repair->repair_description }}</td>
                                                                    <td>{{ $repair->repair_status }}</td>
                                                                </tr>
                                                            @endforeach


                                                        </tbody>
                                                    </table>
                                                @else
                                                    <p style="text-align: center ; ">ไม่มีรายการประวัติการซ่อม</p>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">ปิด</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

















                            </div>
                            <div class="col-md-5">
                                <p>
                                    <strong>ขนาดของเสื้อ</strong> (ปรับแก้ ขยาย/ลด ไม่เกิน 4 นิ้ว):
                                    <button class="btn btn-link p-0 ml-2" data-toggle="modal"
                                        data-target="#add_mea_shirt">
                                        <i class="bi bi-plus-square text-dark"></i>
                                    </button>
                                <div class=" ">
                                    @php
                                        $list_check_name_shirt = [];
                                    @endphp
                                    <table class="table table-bordered-0">
                                        @foreach ($dress_mea_shirt as $dress_mea_shirt)
                                            <tr>
                                                <td>{{ $dress_mea_shirt->mea_dress_name }}<span
                                                        style="font-size: 12px; color: rgb(197, 21, 21)">(ปรับได้
                                                        {{ $dress_mea_shirt->initial_mea - 4 }}-{{ $dress_mea_shirt->initial_mea + 4 }})</span>
                                                </td>
                                                <td col-1>{{ $dress_mea_shirt->current_mea }} </td>
                                                <td col-1>นิ้ว</td>
                                            </tr>
                                            {{-- @php
                                                $list_check_name_shirt[] = $item->measurementnow_dress_name;
                                            @endphp --}}
                                        @endforeach
                                    </table>

                                </div>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="container">
                        <h3>คิวการเช่า</h3>
                        @if ($reservation_shirt->count() > 0)
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ลำดับคิว</th>
                                        <th>ชื่อลูกค้า</th>
                                        <th>วันที่นัดรับ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reservation_shirt as $index => $reservation_shirt)
                                        <tr>
                                            <td>คิวที่ {{ $index + 1 }} </td>
                                            <td>
                                                @php
                                                    $order_id = App\Models\Orderdetail::where(
                                                        'reservation_id',
                                                        $reservation_shirt->id,
                                                    )->value('order_id');
                                                    $customer_id = App\Models\Order::where('id', $order_id)->value(
                                                        'customer_id',
                                                    );
                                                    $customer = App\Models\Customer::find($customer_id);
                                                @endphp
                                                คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($reservation_shirt->start_date)->locale('th')->isoFormat('D MMM') }}
                                                {{ \Carbon\Carbon::parse($reservation_shirt->start_date)->year + 543 }}
                                                <span style="color: red ; "
                                                    id="showday{{ $reservation_shirt->id }}"></span>
                                            </td>


                                            <script>
                                                var now = new Date();
                                                var start_date = new Date("{{ $reservation_shirt->start_date }}");
                                                var day = start_date - now;

                                                var totalday = Math.ceil(day / (1000 * 60 * 60 * 24));

                                                document.getElementById('showday{{ $reservation_shirt->id }}').innerHTML = 'เหลืออีก ' + totalday + ' วัน !';
                                            </script>




                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <h6 style="text-align: center ; ">ไม่มีคิวการเช่าชุดนี้ !</h6>
                        @endif
                    </div>
                </div>

                <!-- ข้อมูลกางเกง -->
                <div class="tab-pane fade" id="pants" role="tabpanel" aria-labelledby="pants-tab">
                    <div class="card-header">
                        <i class="bi bi-info-circle"></i> รายละเอียดกระโปรง/ผ้าถุง
                        <button class="btn btn-link p-0 ml-2 float-right" data-toggle="modal"
                            data-target="#edittotalskirt">
                            <i class="bi bi-pencil-square text-dark
                            "></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- เพิ่มข้อมูลกางเกงที่นี่ -->
                        <div class="row">

                            <div class="d-flex">
                                @foreach ($imagedata as $image)
                                    <div class="col-md-4">
                                        <img src="{{ asset('storage/' . $image->dress_image) }}" alt=""
                                            style="max-height: 350px; width: auto;">
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-md-4">
                                <p><strong>สถานะผ้าถุงตอนนี้:</strong> <span>{{ $text_check_status_skirt }}</span>
                                </p>
                                <p><strong>จำนวนกระโปรง/กางเกง:</strong> 1 ตัว</p>
                                <p><strong>ราคา:</strong> {{ number_format($skirtitem->skirtitem_price, 2) }} บาท</p>
                                <p><strong>ราคามัดจำ:</strong> {{ number_format($skirtitem->skirtitem_deposit, 2) }} บาท
                                </p>
                                <p><strong>ราคาประกันค่าเสียหาย:</strong>
                                    {{ number_format($skirtitem->skirt_damage_insurance, 2) }} บาท</p>
                                <p><strong>จำนวนครั้งที่ถูกเช่า:</strong> {{ $skirtitem->skirtitem_rental }} ครั้ง</p>
                                @php
                                    $skirt_id = App\Models\Skirtitem::where('dress_id', $datadress->id)->value('id');

                                    $list_one = [];
                                    $list_success = [];
                                    $reservation_find_skirt_id = App\Models\Reservation::where(
                                        'skirtitems_id',
                                        $skirt_id,
                                    )->get();
                                    $reservation_find_dress_id = App\Models\Reservation::where(
                                        'dress_id',
                                        $datadress->id,
                                    )->get();
                                    foreach ($reservation_find_skirt_id as $key => $re) {
                                        $list_one[] = $re->id;
                                    }
                                    foreach ($reservation_find_dress_id as $key => $dr) {
                                        $list_one[] = $dr->id;
                                    }
                                    $list_one_unique = array_unique($list_one);

                                    foreach ($list_one_unique as $reservation_id) {
                                        $repair = App\Models\Repair::where('reservation_id', $reservation_id)
                                            ->whereIn('repair_type', ['10', '30'])
                                            ->get();
                                        if ($repair->isNotEmpty()) {
                                            foreach ($repair as $key => $re_success) {
                                                $list_success[] = $re_success->id;
                                            }
                                        }
                                    }
                                    $historyrepair_skirt = App\Models\Repair::whereIn('id', $list_success)->get();
                                @endphp
                                <p><strong>จำนวนครั้งที่ซ่อม:</strong> {{$historyrepair_skirt->count()}} ครั้ง
                                    <button class="btn btn-secondary" type="button" data-toggle="modal"
                                        data-target="#showhistory_repair_skirt">ประวัติการซ่อม</button>
                                </p>

                                    {{-- modalประวัติการซ่อมชุด --}}
                                    <div class="modal fade" id="showhistory_repair_skirt" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">ประวัติการซ่อม</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                @if ($historyrepair_skirt->count() > 0)
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>วันที่</th>
                                                                <th>รายการ</th>
                                                                <th>สถานะ</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($historyrepair_skirt as $repair)
                                                                <tr>
                                                                    <td>{{ $repair->created_at }}</td>
                                                                    <td>{{ $repair->repair_description }}</td>
                                                                    <td>{{ $repair->repair_status }}</td>
                                                                </tr>
                                                            @endforeach


                                                        </tbody>
                                                    </table>
                                                @else
                                                    <p style="text-align: center ; ">ไม่มีรายการประวัติการซ่อม</p>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">ปิด</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                













                            </div>
                            <div class="col-md-5">
                                <p>
                                    <strong>ขนาดของกระโปรง/กางเกง:</strong>
                                    <button class="btn btn-link p-0 ml-2" data-toggle="modal"
                                        data-target="#add_mea_skirt">
                                        <i class="bi bi-plus-square text-dark"></i>
                                    </button>
                                <div class=" ">
                                    @php
                                        $list_check_name_skirt = [];
                                    @endphp
                                    <table class="table table-bordered-0">
                                        @foreach ($dress_mea_skirt as $dress_mea_skirt)
                                            <tr>
                                                <td>{{ $dress_mea_skirt->mea_dress_name }}<span
                                                        style="font-size: 12px; color: rgb(197, 21, 21)">(ปรับได้
                                                        {{ $dress_mea_skirt->initial_mea - 4 }}-{{ $dress_mea_skirt->initial_mea + 4 }})</span>
                                                </td>

                                                </td>
                                                <td>{{ $dress_mea_skirt->current_mea }}
                                                </td>
                                                <td>นิ้ว</td>
                                            </tr>
                                            {{-- @php
                                                $list_check_name_skirt[] =
                                                    $measument_yes_separate_now_skirt->measurementnow_dress_name;
                                            @endphp --}}
                                        @endforeach
                                    </table>



                                </div>
                                </p>
                            </div>


                        </div>
                    </div>
                    <div class="container">
                        <h3>คิวการเช่า</h3>
                        @if ($reservation_skirt->count() > 0)
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ลำดับคิว</th>
                                        <th>ชื่อลูกค้า</th>
                                        <th>วันที่นัดรับ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reservation_skirt as $index => $reservation_skirt)
                                        <tr>
                                            <td>คิวที่ {{ $index + 1 }} </td>
                                            <td>
                                                @php
                                                    $order_id = App\Models\Orderdetail::where(
                                                        'reservation_id',
                                                        $reservation_skirt->id,
                                                    )->value('order_id');
                                                    $customer_id = App\Models\Order::where('id', $order_id)->value(
                                                        'customer_id',
                                                    );
                                                    $customer = App\Models\Customer::find($customer_id);
                                                @endphp
                                                คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($reservation_skirt->start_date)->locale('th')->isoFormat('D MMM') }}
                                                {{ \Carbon\Carbon::parse($reservation_skirt->start_date)->year + 543 }}
                                                <span style="color: red ; "
                                                    id="showday{{ $reservation_skirt->id }}"></span>
                                            </td>


                                            <script>
                                                var now = new Date();
                                                var start_date = new Date("{{ $reservation_skirt->start_date }}");
                                                var day = start_date - now;

                                                var totalday = Math.ceil(day / (1000 * 60 * 60 * 24));

                                                document.getElementById('showday{{ $reservation_skirt->id }}').innerHTML = 'เหลืออีก ' + totalday + ' วัน !';
                                            </script>




                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <h6 style="text-align: center ; ">ไม่มีคิวการเช่าชุดนี้ !</h6>
                        @endif
                    </div>
                </div>
            </div>

            {{-- modalแก้ไขชุด --}}
            <div class="modal fade" id="edittotal" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header text-dark" style="background-color: #EAD8C0;">
                            <h5 class="modal-title">แก้ไขข้อมูลชุด</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <!-- ข้อมูลชุด -->
                                <h5 class="mb-4">ข้อมูลชุด</h5>

                                <form action="{{ route('admin.updatedressnoyes', ['id' => $datadress->id]) }}"
                                    method="POST">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_dress_price">ราคา</label>
                                            <input type="number" class="form-control" name="update_dress_price"
                                                id="update_dress_price" value="{{ $datadress->dress_price }}"
                                                placeholder="กรุณากรอกราคา">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_dress_deposit">ราคามัดจำ</label>
                                            <input type="number" class="form-control" name="update_dress_deposit"
                                                id="update_dress_deposit" value="{{ $datadress->dress_deposit }}"
                                                placeholder="กรุณากรอกราคามัดจำ">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_dress_deposit">ราคาประกันค่าเสียหาย</label>
                                            <input type="number" class="form-control"
                                                name="update_dress_damage_insurance" id="update_dress_damage_insurance"
                                                value="{{ $datadress->damage_insurance }}"
                                                placeholder="กรุณากรอกราคาประกันค่าเสียหาย">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="dress_status">สถานะชุด</label>
                                            <select name="update_dress_status" id="update_dress_status"
                                                class="form-control">
                                                <option value="พร้อมให้เช่า"
                                                    {{ $datadress->dress_status == 'พร้อมให้เช่า' ? 'selected' : '' }}>
                                                    พร้อมให้เช่า</option>
                                                <option value="ถูกจองแล้ว"
                                                    {{ $datadress->dress_status == 'ถูกจองแล้ว' ? 'selected' : '' }}>
                                                    ถูกจองแล้ว
                                                </option>
                                                <option value="กำลังเช่า"
                                                    {{ $datadress->dress_status == 'กำลังเช่า' ? 'selected' : '' }}>
                                                    กำลังเช่า
                                                </option>
                                                <option value="ส่งทำความสะอาด"
                                                    {{ $datadress->dress_status == 'ส่งทำความสะอาด' ? 'selected' : '' }}>
                                                    ส่งทำความสะอาด</option>
                                                <option value="ซ่อมแซม"
                                                    {{ $datadress->dress_status == 'ซ่อมแซม' ? 'selected' : '' }}>ซ่อมแซม
                                                </option>
                                                <option value="เลิกให้เช่า"
                                                    {{ $datadress->dress_status == 'เลิกให้เช่า' ? 'selected' : '' }}>
                                                    เลิกให้เช่า</option>
                                                <option value="สูญหาย"
                                                    {{ $datadress->dress_status == 'สูญหาย' ? 'selected' : '' }}>สูญหาย
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_dress_description">คำอธิบายชุด</label>
                                            <textarea name="update_dress_description" id="update_dress_description" class="form-control" rows="3">{{ $datadress->dress_description }}</textarea>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success">บันทึก</button>
                                </form>

                                <hr>


                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- modalแก้ไขเสื้อ+ข้อมูลการวัด --}}
            <div class="modal fade" id="edittotalshirt" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header text-dark" style="background-color: #EAD8C0;">
                            <h5 class="modal-title">แก้ไขข้อมูลเสื้อ</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <!-- ข้อมูลชุด -->
                                <h5 class="mb-4">ข้อมูลเสื้อ</h5>

                                <form action="{{ route('admin.updatedressyesshirt', ['id' => $shirtitem->id]) }}"
                                    method="POST">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_shirt_price">ราคา</label>
                                            <input type="number" class="form-control" name="update_shirt_price"
                                                id="update_shirt_price" value="{{ $shirtitem->shirtitem_price }}"
                                                placeholder="กรุณากรอกราคา" min="1" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_shirt_deposit">ราคามัดจำ</label>
                                            <input type="number" class="form-control" name="update_shirt_deposit"
                                                id="update_shirt_deposit" value="{{ $shirtitem->shirtitem_deposit }}"
                                                placeholder="กรุณากรอกราคามัดจำ" min="1" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_shirt_deposit">ราคาประกันค่าเสียหาย</label>
                                            <input type="number" class="form-control"
                                                name="update_shirt_damage_insurance" id="update_shirt_damage_insurance"
                                                value="{{ $shirtitem->shirt_damage_insurance }}"
                                                placeholder="กรุณากรอกราคาประกันค่าเสียหาย" min="0" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_shirt_status">สถานะชุด</label>
                                            <select name="update_shirt_status" id="update_shirt_status"
                                                class="form-control">
                                                <option value="พร้อมให้เช่า"
                                                    {{ $shirtitem->shirtitem_status == 'พร้อมให้เช่า' ? 'selected' : '' }}>
                                                    พร้อมให้เช่า</option>
                                                <option value="ถูกจองแล้ว"
                                                    {{ $shirtitem->shirtitem_status == 'ถูกจองแล้ว' ? 'selected' : '' }}>
                                                    ถูกจองแล้ว
                                                </option>
                                                <option value="กำลังเช่า"
                                                    {{ $shirtitem->shirtitem_status == 'กำลังเช่า' ? 'selected' : '' }}>
                                                    กำลังเช่า
                                                </option>
                                                <option value="ส่งทำความสะอาด"
                                                    {{ $shirtitem->shirtitem_status == 'ส่งทำความสะอาด' ? 'selected' : '' }}>
                                                    ส่งทำความสะอาด</option>
                                                <option value="ซ่อมแซม"
                                                    {{ $shirtitem->shirtitem_status == 'ซ่อมแซม' ? 'selected' : '' }}>
                                                    ซ่อมแซม
                                                </option>
                                                <option value="เลิกให้เช่า"
                                                    {{ $shirtitem->shirtitem_status == 'เลิกให้เช่า' ? 'selected' : '' }}>
                                                    เลิกให้เช่า</option>
                                                <option value="สูญหาย"
                                                    {{ $shirtitem->shirtitem_status == 'สูญหาย' ? 'selected' : '' }}>สูญหาย
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- ข้อมูลการวัด -->
                                    <h5 class="mb-4">ขนาดของชุด</h5>

                                    @foreach ($measument_yes_separate_now_shirt_modal as $measument_yes_separate_now_shirt_modal)
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <input type="hidden" name="mea_now_id_[]"
                                                    value="{{ $measument_yes_separate_now_shirt_modal->id }}">
                                                <input type="text" class="form-control" name="mea_now_name_[]"
                                                    value="{{ $measument_yes_separate_now_shirt_modal->measurementnow_dress_name }}"
                                                    placeholder="ชื่อการวัด" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="number" class="form-control" name="mea_now_number_[]"
                                                    value="{{ $measument_yes_separate_now_shirt_modal->measurementnow_dress_number }}"
                                                    placeholder="ค่าการวัด" required step="0.01"
                                                    min="{{ $measument_yes_separate_now_shirt_modal->measurementnow_dress_number_start - 4 }}"
                                                    max="{{ $measument_yes_separate_now_shirt_modal->measurementnow_dress_number_start + 4 }}">
                                            </div>
                                            <div class="col-md-4">
                                                <p>นิ้ว</p>
                                            </div>
                                        </div>
                                    @endforeach


                                    <button type="submit" class="btn btn-success ">บันทึก</button>
                                </form>

                                <hr>


                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{-- modalแก้ไขกระโปรง+ข้อมูลการวัด --}}
            <div class="modal fade" id="edittotalskirt" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header text-dark" style="background-color: #EAD8C0;">
                            <h5 class="modal-title">แก้ไขข้อมูลกระโปรง/กางเกง</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <!-- ข้อมูลชุด -->
                                <h5 class="mb-4">ข้อมูลกระโปรง/กางเกง</h5>

                                <form action="{{ route('admin.updatedressyesskirt', ['id' => $skirtitem->id]) }}"
                                    method="POST">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_skirt_price">ราคา</label>
                                            <input type="number" class="form-control" name="update_skirt_price"
                                                id="update_skirt_price" value="{{ $skirtitem->skirtitem_price }}"
                                                placeholder="กรุณากรอกราคา">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_skirt_deposit">ราคามัดจำ</label>
                                            <input type="number" class="form-control" name="update_skirt_deposit"
                                                id="update_skirt_deposit" value="{{ $skirtitem->skirtitem_deposit }}"
                                                placeholder="กรุณากรอกราคามัดจำ">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_skirt_deposit">ราคาประกันค่าเสียหาย</label>
                                            <input type="number" class="form-control"
                                                name="update_skirt_damage_insurance" id="update_skirt_damage_insurance"
                                                value="{{ $skirtitem->skirt_damage_insurance }}"
                                                placeholder="กรุณากรอกราคามัดจำ">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label for="update_skirt_status">สถานะชุด</label>
                                            <select name="update_skirt_status" id="update_skirt_status"
                                                class="form-control">
                                                <option value="พร้อมให้เช่า"
                                                    {{ $skirtitem->skirtitem_status == 'พร้อมให้เช่า' ? 'selected' : '' }}>
                                                    พร้อมให้เช่า</option>
                                                <option value="ถูกจองแล้ว"
                                                    {{ $skirtitem->skirtitem_status == 'ถูกจองแล้ว' ? 'selected' : '' }}>
                                                    ถูกจองแล้ว
                                                </option>
                                                <option value="กำลังเช่า"
                                                    {{ $skirtitem->skirtitem_status == 'กำลังเช่า' ? 'selected' : '' }}>
                                                    กำลังเช่า
                                                </option>
                                                <option value="ส่งทำความสะอาด"
                                                    {{ $skirtitem->skirtitem_status == 'ส่งทำความสะอาด' ? 'selected' : '' }}>
                                                    ส่งทำความสะอาด</option>
                                                <option value="ซ่อมแซม"
                                                    {{ $skirtitem->skirtitem_status == 'ซ่อมแซม' ? 'selected' : '' }}>
                                                    ซ่อมแซม
                                                </option>
                                                <option value="เลิกให้เช่า"
                                                    {{ $skirtitem->skirtitem_status == 'เลิกให้เช่า' ? 'selected' : '' }}>
                                                    เลิกให้เช่า</option>
                                                <option value="สูญหาย"
                                                    {{ $skirtitem->skirtitem_status == 'สูญหาย' ? 'selected' : '' }}>
                                                    สูญหาย
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- ข้อมูลการวัด -->
                                    <h5 class="mb-4">ขนาดของกระโปรง/กางเกง</h5>

                                    @foreach ($measument_yes_separate_now_skirt_modal as $measument_yes_separate_now_skirt_modal)
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <input type="hidden" name="mea_now_id_[]"
                                                    value="{{ $measument_yes_separate_now_skirt_modal->id }}">
                                                <input type="text" class="form-control" name="mea_now_name_[]"
                                                    value="{{ $measument_yes_separate_now_skirt_modal->measurementnow_dress_name }}"
                                                    placeholder="ชื่อการวัด" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="number" class="form-control" name="mea_now_number_[]"
                                                    value="{{ $measument_yes_separate_now_skirt_modal->measurementnow_dress_number }}"
                                                    placeholder="ค่าการวัด" step="0.01" required
                                                    min="{{ $measument_yes_separate_now_skirt_modal->measurementnow_dress_number_start - 4 }}"
                                                    max="{{ $measument_yes_separate_now_skirt_modal->measurementnow_dress_number_start + 4 }}">
                                            </div>
                                            <div class="col-md-4">
                                                <p>นิ้ว</p>
                                            </div>
                                        </div>
                                    @endforeach


                                    <button type="submit" class="btn btn-success">บันทึก</button>
                                </form>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>






        </div>

        <!--modal เพิ่มรูปภาพ-->
        <div class="modal fade" id="modaladdimage" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">เพิ่มรูปภาพ</div>
                    <form action="{{ route('admin.addimage', ['id' => $datadress->id]) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="addimage">รูปภาพ:</label>
                                <input type="file" class="form-control" name="addimage" id="addimage" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                            <button type="submit" class="btn btn-success">ยืนยัน</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- modalเพิ่มข้อมูลการวัด -->
        <div class="modal fade" id="add_mea_shirt" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form action="{{ route('admin.addmeasumentyesshirt', ['id' => $shirtitem->id]) }}" method="POST">
                        @csrf
                        <div class="modal-header text-dark" style="background-color:#EAD8C0 ;">
                            <h5 class="modal-title">เพิ่มข้อมูลการวัดเสื้อ</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class=" "><i class="bi bi-pencil-square text-dark"></i>ข้อมูลการวัด</h5>

                                    <button class="btn btn-success" type="button" id="add_measurement_shirt">
                                        <i class="bi bi-plus"></i> เพิ่มการวัด
                                    </button>
                                </div>
                                {{-- list_check_name_shirt --}}
                                <input type="hidden" name="dress_id" value="{{ $datadress->id }}">
                                <div id="aria_show_add_mea_input">

                                    <div class="row mb-3" id>
                                        <div class="col-md-3">
                                            {{-- <input type="text" class="form-control" name="add_mea_now_name_[1]"
                                                placeholder="ชื่อการวัด" > --}}
                                            <select class="form-control" name="add_mea_now_name_[1]" required>
                                                <option value="" disabled selected>เลือกรายการ</option>
                                                <option value="ยาวหน้า"
                                                    @if (in_array('ยาวหน้า', $list_check_name_shirt)) style="display: none;" @endif>ยาวหน้า
                                                </option>
                                                <option value="ยาวหลัง"
                                                    @if (in_array('ยาวหลัง', $list_check_name_shirt)) style="display: none;" @endif>ยาวหลัง
                                                </option>
                                                <option value="ไหล่กว้าง"
                                                    @if (in_array('ไหล่กว้าง', $list_check_name_shirt)) style="display: none;" @endif>
                                                    ไหล่กว้าง</option>
                                                <option value="บ่าหน้า"
                                                    @if (in_array('บ่าหน้า', $list_check_name_shirt)) style="display: none;" @endif>บ่าหน้า
                                                </option>
                                                <option value="บ่าหลัง"
                                                    @if (in_array('บ่าหลัง', $list_check_name_shirt)) style="display: none;" @endif>บ่าหลัง
                                                </option>
                                                <option value="รอบคอ"
                                                    @if (in_array('รอบคอ', $list_check_name_shirt)) style="display: none;" @endif>รอบคอ
                                                </option>
                                                <option value="รักแร้"
                                                    @if (in_array('รักแร้', $list_check_name_shirt)) style="display: none;" @endif>รักแท้
                                                </option>
                                                <option value="รอบอก"
                                                    @if (in_array('รอบอก', $list_check_name_shirt)) style="display: none;" @endif>รอบอก
                                                </option>
                                                <option value="อกห่าง"
                                                    @if (in_array('อกห่าง', $list_check_name_shirt)) style="display: none;" @endif>อกห่าง
                                                </option>
                                                <option value="อกสูง"
                                                    @if (in_array('อกสูง', $list_check_name_shirt)) style="display: none;" @endif>อกสูง
                                                </option>
                                                <option value="แขนยาว"
                                                    @if (in_array('แขนยาว', $list_check_name_shirt)) style="display: none;" @endif>แขนยาว
                                                </option>
                                                <option value="แขนกว้าง"
                                                    @if (in_array('แขนกว้าง', $list_check_name_shirt)) style="display: none;" @endif>
                                                    แขนกว้าง</option>
                                                <option value="เสื้อยาว"
                                                    @if (in_array('เสื้อยาว', $list_check_name_shirt)) style="display: none;" @endif>
                                                    เสื้อยาว</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="form-control" name="add_mea_now_number_[1]"
                                                placeholder="ค่าการวัด" min="0" max="100" step="0.01"
                                                required>
                                        </div>
                                        <div class="col-md-3">
                                            <p>นิ้ว</p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                            <button type="submit" class="btn btn-success">บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            var add_measurement = document.getElementById('add_measurement_shirt');
            var aria_show_add_mea_input = document.getElementById('aria_show_add_mea_input');
            var count_add_mea = 1;
            add_measurement.addEventListener('click', function() {
                count_add_mea++;

                var div = document.createElement('div');
                div.className = 'row mb-3';
                div.id = 'row_add_measurement' + count_add_mea;


                input =


                    '<div class="col-md-3">' +
                    '<select class="form-control" name="add_mea_now_name_[' + count_add_mea + ']">' +
                    '<option value="" disabled selected>เลือกรายการ</option>' +
                    '<option value="ยาวหน้า" @if (in_array('ยาวหน้า', $list_check_name_shirt)) style="display: none;" @endif>ยาวหน้า</option>' +
                    '<option value="ยาวหลัง" @if (in_array('ยาวหลัง', $list_check_name_shirt)) style="display: none;" @endif>ยาวหลัง</option>' +
                    '<option value="ไหล่กว้าง" @if (in_array('ไหล่กว้าง', $list_check_name_shirt)) style="display: none;" @endif>ไหล่กว้าง</option>' +
                    '<option value="บ่าหน้า" @if (in_array('บ่าหน้า', $list_check_name_shirt)) style="display: none;" @endif>บ่าหน้า</option>' +
                    '<option value="บ่าหลัง" @if (in_array('บ่าหลัง', $list_check_name_shirt)) style="display: none;" @endif>บ่าหลัง</option>' +
                    '<option value="รอบคอ" @if (in_array('รอบคอ', $list_check_name_shirt)) style="display: none;" @endif>รอบคอ</option>' +
                    '<option value="รักแท้" @if (in_array('รักแท้', $list_check_name_shirt)) style="display: none;" @endif>รักแท้</option>' +
                    '<option value="รอบอก" @if (in_array('รอบอก', $list_check_name_shirt)) style="display: none;" @endif>รอบอก</option>' +
                    '<option value="อกห่าง" @if (in_array('อกห่าง', $list_check_name_shirt)) style="display: none;" @endif>อกห่าง</option>' +
                    '<option value="อกสูง" @if (in_array('อกสูง', $list_check_name_shirt)) style="display: none;" @endif>อกสูง</option>' +
                    '<option value="แขนยาว" @if (in_array('แขนยาว', $list_check_name_shirt)) style="display: none;" @endif>แขนยาว</option>' +
                    '<option value="แขนกว้าง" @if (in_array('แขนกว้าง', $list_check_name_shirt)) style="display: none;" @endif>แขนกว้าง</option>' +
                    '<option value="เสื้อยาว" @if (in_array('เสื้อยาว', $list_check_name_shirt)) style="display: none;" @endif>เสื้อยาว</option>' +
                    '</select>' +
                    '</div>' +
                    '<div class="col-md-3">' +
                    '<input type="number" class="form-control" name="add_mea_now_number_[' + count_add_mea +
                    ']" placeholder="ค่าการวัด" min="0" max="100" step="0.01" required>' +
                    '</div>' +
                    '<div class="col-md-1">' +
                    '<p>นิ้ว</p>' +
                    '</div>' +
                    '<div class="input-group-append">' +
                    '<button class="btn btn-danger remove-measurement" onclick="remove_add_mea_now(' + count_add_mea +
                    ')" type="button">ลบ</button>' +
                    '</div>';
                div.innerHTML = input;
                aria_show_add_mea_input.appendChild(div);
            });

            function remove_add_mea_now(count_add_mea) {
                var delete_add_mea_now = document.getElementById('row_add_measurement' + count_add_mea);
                delete_add_mea_now.remove();
            }
        </script>





        <!-- modalเพิ่มข้อมูลการวัด -->
        <div class="modal fade" id="add_mea_skirt" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form action="{{ route('admin.addmeasumentyesskirt', ['id' => $skirtitem->id]) }}" method="POST">
                        @csrf
                        <div class="modal-header text-dark" style="background-color: #EAD8C0;">
                            <h5 class="modal-title">เพิ่มข้อมูลการวัดกระโปรง</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="mb-0"><i class="bi bi-pencil-square text-dark"></i>ข้อมูลการวัด</h5>
                                    {{-- <button class="btn btn-success" type="button" id="add_measurement">
                                        <i class="bi bi-plus"></i> เพิ่มการวัด
                                    </button> --}}
                                </div>
                                <input type="hidden" name="dress_id" value="{{ $datadress->id }}">

                                <div id="aria_show_add_mea_input">

                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <select class="form-control" name="add_mea_now_name_[1]" required>
                                                <option value="" selected disabled>เลือกรายการ</option>
                                                <option value="รอบเอว"
                                                    @if (in_array('รอบเอว', $list_check_name_skirt)) style="display: none;" @endif>รอบเอว
                                                </option>
                                                <option value="รอบสะโพก"
                                                    @if (in_array('รอบสะโพก', $list_check_name_skirt)) style="display: none;" @endif>
                                                    รอบสะโพก
                                                </option>
                                                <option value="กระโปรงยาว"
                                                    @if (in_array('กระโปรงยาว', $list_check_name_skirt)) style="display: none;" @endif>
                                                    กระโปรงยาว</option>
                                                <option value="ต้นขา"
                                                    @if (in_array('ต้นขา', $list_check_name_skirt)) style="display: none;" @endif>ต้นขา
                                                </option>
                                                <option value="ปลายขา"
                                                    @if (in_array('ปลายขา', $list_check_name_skirt)) style="display: none;" @endif>ปลายขา
                                                </option>
                                                <option value="เป้า"
                                                    @if (in_array('เป้า', $list_check_name_skirt)) style="display: none;" @endif>เป้า
                                                </option>
                                                <option value="กางเกงยาว"
                                                    @if (in_array('กางเกงยาว', $list_check_name_skirt)) style="display: none;" @endif>
                                                    กางเกงยาว</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" class="form-control" name="add_mea_now_number_[1]"
                                                placeholder="ค่าการวัด" min="1" max="100" step="0.01"
                                                required>
                                        </div>
                                        <div class="col-md-3">
                                            <p>นิ้ว</p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                            <button type="submit" class="btn btn-success">บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            var add_measurement_skirt = document.getElementById('add_measurement');
            var aria_show_add_mea_input = document.getElementById('aria_show_add_mea_input');
            var count_add_mea = 1;
            add_measurement_skirt.addEventListener('click', function() {
                count_add_mea++;
                var div = document.createElement('div');
                div.className = 'row mb-3';
                div.id = 'row_add_measurement' + count_add_mea;

                input =

                    '<div class="col-md-3">' +
                    '<input type="text" class="form-control" name="add_mea_now_name_[' + count_add_mea +
                    ']" placeholder="ชื่อการวัด">' +
                    '</div>' +
                    '<div class="col-md-3">' +
                    '<input type="number" class="form-control" name="add_mea_now_number_[' + count_add_mea +
                    ']" placeholder="หมายเลขการวัด">' +
                    '</div>' +
                    '<div class="col-md-3">' +
                    '<select class="form-control" name="add_mea_now_unit_[' + count_add_mea + ']">' +
                    '<option value="นิ้ว" selected>นิ้ว</option>' +
                    '<option value="เซนติเมตร">เซนติเมตร</option>' +
                    '<option value="มิลลิเมตร">มิลลิเมตร</option>' +
                    '</select>' +
                    '</div>' +
                    '<div class="input-group-append">' +
                    '<button class="btn btn-danger remove-measurement" onclick="remove_add_mea_now(' + count_add_mea +
                    ')" type="button"><i class="bi bi-trash"></i>ลบ</button>' +
                    '</div>';
                div.innerHTML = input;
                aria_show_add_mea_input.appendChild(div);
            });

            function remove_add_mea_now(count_add_mea) {
                var delete_add_mea_now = document.getElementById('row_add_measurement' + count_add_mea);
                delete_add_mea_now.remove();
            }
        </script>



        <!-- ข้อความแจ้งเตือน -->
        <div class="modal fade" id="showsuccessss" role="dialog" aria-hidden="true">
            <div class="modal-dialog custom-modal-dialog">
                <div class="modal-content custom-modal-content">
                    <div class="modal-body">{{ session('success') }}</div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="showfail" role="dialog" aria-hidden="true">
            <div class="modal-dialog custom-modal-dialog">
                <div class="modal-content custom-modal-content fail">
                    <div class="modal-body">{{ session('fail') }}</div>
                </div>
            </div>
        </div>

        <script>
            @if (session('success'))
                setTimeout(function() {
                    $('#showsuccessss').modal('show');
                    setTimeout(function() {
                        $('#showsuccessss').modal('hide');
                    }, 6000);
                }, 500);
            @endif
            @if (session('fail'))
                setTimeout(function() {
                    $('#showfail').modal('show');
                    setTimeout(function() {
                        $('#showfail').modal('hide');
                    }, 6000);
                }, 500);
            @endif
        </script>
    @endsection
