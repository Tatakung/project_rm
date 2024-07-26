@extends('layouts.adminlayout')
@section('content')
    <div class="modal fade" id="showsuccess" role="dialog" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog" role="document">
            <div class="modal-content custom-modal-content"
                style="max-width: 300px; height: 50px; width: 100%; margin: auto; background-color: #39d628; border: 2px solid #4fe227; ">
                <div class="modal-body" style="padding: 10px; display: flex; align-items: center; justify-content: center;">
                    <p style="margin: 0; color: #ffffff;">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    </div>
    <script>
        @if (session('success'))
            setTimeout(function() {
                $('#showsuccess').modal('show');
            }, 500);
        @endif
    </script>
    <div class="container">


        <form action="{{ route('employee.filtermea') }}" method="GET" class="p-3 border rounded shadow-sm bg-light">
            @csrf
            <div class="row g-3">
                {{-- <div class="col-md-2">
                    <label for="search_separable" class="form-label">ชุด</label>
                    <select name="search_separable" id="search_separable" class="form-select">
                        <option value="">ทั้งหมด</option>
                        <option value="1">ไม่สามารถเช่าแยกได้</option>
                        <option value="2">สามารถเช่าแยกได้</option>
                    </select>
                </div> --}}
                <div class="col-md-2">
                    <label for="bust" class="form-label">ขนาดอก(นิ้ว)</label>
                    <input type="number" name="chest" class="form-control">

                </div>
                <div class="col-md-2">
                    <label for="waist" class="form-label">ขนาดเอว(นิ้ว)</label>
                    <input type="number" name="waist" class="form-control">
                </div>
                <div class="col-md-2">
                    <label for="hip" class="form-label">ขนาดสะโพก(นิ้ว)</label>
                    <input type="number" name="hip" class="form-control">

                </div>
                <input type="hidden" name="type_dress_id" value="{{ $type_dress_id }}">


                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i>ค้นหา
                    </button>
                </div>
            </div>
        </form>

        {{-- {{$selectstartDate}}
        {{$selectendDate}}

        {{$selecttotalDay}} --}}


        <div class="row" style="margin-top: 15px;">
            @foreach ($dress as $index => $item)
                {{-- @if ($item->dress_status == 'พร้อมให้เช่า') --}}
                @php
                    $shirtStatus = App\Models\Shirtitem::where('dress_id', $item->id)->value('shirtitem_status'); //สถานะเสื้อ
                    $skirtStatus = App\Models\Skirtitem::where('dress_id', $item->id)->value('skirtitem_status'); //สถานะกระโปรง
                @endphp
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <button data-toggle="modal" data-target="#showmodal{{ $item->id }}">
                            <img src="{{ asset('storage/' . $item->dressimages->first()->dress_image) }}"
                                class="card-img-top img-fluid" alt="Dress Image"
                                style="max-height: 300px; object-fit: contain;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $item->dress_title_name }}</h5>
                                <h6>รหัสชุด: {{ $item->dress_code_new }}{{ $item->dress_code }}</h6>
                                <h6>จำนวนชุด: {{ $item->dress_count }} ชุด</h6>
                                <h6>ราคาเช่า:{{ number_format($item->dress_price, 2) }}&#3647;</h6>
                                <h6>
                                    @if ($item->separable == 1)
                                        <p><i class="bi bi-x-circle-fill text-danger"></i> ไม่สามารถเช่าแยกได้</p>
                                    @elseif($item->separable == 2)
                                        <p><i class="bi bi-check-circle-fill text-success"></i> สามารถเช่าแยกได้</p>
                                    @endif
                                </h6>
                                {{-- <h6>
                                    @if ($item->dress_status == 'พร้อมให้เช่า')
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    @elseif($item->dress_status == 'ไม่พร้อมใช้งาน')
                                        <i class="bi bi-x-circle-fill text-danger"></i>
                                    @else
                                        <i class="bi bi-info-circle-fill text-warning"></i>
                                    @endif
                                    {{ $item->dress_status }}
                                </h6> --}}
                                <!--ถ้ามันแยกได้ค่อยแสดงผลนะ-->
                                @if ($item->separable == 2)
                                    <h6>สถานะเสื้อ:<i
                                            @if ($shirtStatus == 'พร้อมให้เช่า') style="color: #39d628"
                                            @else
                                            style="color: red" @endif>{{ $shirtStatus }}</i>
                                    </h6>
                                    <h6>สถานะกระโปรง/กางเกง:<i
                                            @if ($skirtStatus == 'พร้อมให้เช่า') style="color: #39d628"
                                            @else
                                            style="color: red" @endif>{{ $skirtStatus }}</i>
                                    </h6>
                                @endif

                            </div>
                        </button>
                        {{-- <button class="btn btn-primary" data-toggle="modal" data-target="#showmodal{{ $item->id }}">
                            ดูรายละเอียด
                        </button> --}}

                        <div class="modal fade" role="dialog" aria-hidden="true" id="showmodal{{ $item->id }}">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">

                                        {{-- @foreach ($item->shirtitems as $shirtitem)
                                        {{ $item->dress_code_new }}{{ $item->dress_code }}
                                        {{ number_format($item->dress_price, 2) }}
                                        {{ number_format($item->dress_deposit, 2) }}
                                        {{ number_format($item->damage_insurance, 2) }}
                                        @endforeach --}}





                                    </div>
                                    <div class="modal-body">



                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="dress-tab" data-toggle="tab"
                                                    href="#dress{{ $item->id }}" role="tab" aria-controls="dress"
                                                    aria-selected="true">ข้อมูลชุด</a>
                                            </li>
                                            <li class="nav-item"
                                                @if ($item->separable == 1) style="display:none;" @endif>
                                                <a class="nav-link" id="shirt-tab" data-toggle="tab"
                                                    href="#shirt{{ $item->id }}" role="tab" aria-controls="shirt"
                                                    aria-selected="false">ข้อมูลเสื้อ</a>
                                            </li>
                                            <li class="nav-item"
                                                @if ($item->separable == 1) style="display:none;" @endif>
                                                <a class="nav-link" id="pants-tab" data-toggle="tab"
                                                    href="#pants{{ $item->id }}" role="tab" aria-controls="pants"
                                                    aria-selected="false">ข้อมูลกางเกง</a>
                                            </li>
                                        </ul>

                                        <div class="tab-content" id="myTabContent">
                                            <!-- ข้อมูลชุด -->
                                            <div class="tab-pane fade show active" id="dress{{ $item->id }}"
                                                role="tabpanel" aria-labelledby="dress-tab">

                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            {{-- <p><strong>ประเภทชุด:</strong> {{ $type_dress_name }}</p> --}}
                                                            <p><strong>รหัสชุด:</strong>
                                                                {{ $item->dress_code_new }}{{ $item->dress_code }}
                                                            </p>
                                                            <p><strong>ราคา:</strong>
                                                                {{ number_format($item->dress_price, 2) }}
                                                                บาท</p>
                                                            <p><strong>ราคามัดจำ:</strong>
                                                                {{ number_format($item->dress_deposit, 2) }} บาท</p>
                                                            <p><strong>ราคาประกันค่าเสียหาย:</strong>
                                                                {{ number_format($item->damage_insurance, 2) }} บาท</p>

                                                        </div>
                                                        <div class="col-md-6">
                                                            <p><strong>จำนวนชุด:</strong> {{ $item->dress_count }} ชุด</p>

                                                            <p>
                                                                <strong>สถานะชุด:</strong>
                                                                <span
                                                                    @if ($item->dress_status == 'พร้อมให้เช่า') style="color: #39d628" @else style="color: red" @endif>
                                                                    {{ $item->dress_status }}
                                                                </span>
                                                            </p>




                                                            <p><strong>จำนวนครั้งที่ถูกเช่า:</strong>
                                                                {{ $item->dress_rental }}
                                                                ครั้ง</p>
                                                            <p><strong>ชุด:</strong>
                                                                @if ($item->separable == 1)
                                                                    <i class="bi bi-x-circle-fill text-danger"></i>
                                                                    ไม่สามารถเช่าแยกได้
                                                                @elseif($item->separable == 2)
                                                                    <i class="bi bi-check-circle-fill text-success"></i>
                                                                    สามารถเช่าแยกได้
                                                                @endif
                                                            </p>
                                                            <p><strong>คำอธิบายชุด:</strong> {{ $item->dress_description }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <h5 class="mt-4">ขนาดของชุดเริ่มต้น<i>(ปรับแก้ ขยาย ลด ไม่เกิน 3
                                                            นิ้ว)</i></h5>
                                                    @foreach (App\Models\Dressmeasurement::where('dress_id', $item->id)->get() as $measument_no_separate)
                                                        {{ $measument_no_separate->measurement_dress_name }}&nbsp;{{ $measument_no_separate->measurement_dress_number }}&nbsp;{{ $measument_no_separate->measurement_dress_unit }}
                                                    @endforeach
                                                    <h5 class="mt-4">ขนาดของเสื้อล่าสุด</h5>
                                                    @foreach (App\Models\Dressmeasurementnow::where('dress_id', $item->id)->get() as $measument_no_separate_now)
                                                        {{ $measument_no_separate_now->measurementnow_dress_name }}&nbsp;{{ $measument_no_separate_now->measurementnow_dress_number }}&nbsp;{{ $measument_no_separate_now->measurementnow_dress_unit }}
                                                    @endforeach
                                                </div>
                                                <div class="d-flex justify-content-end">




                                                    <form action="{{ route('employee.addrentdresscart') }}"
                                                        method="POST">
                                                        @csrf
                                                        <input type="hidden" name="dress_id"
                                                            value="{{ $item->id }}">
                                                        <input type="hidden" name="price_dress"
                                                            value="{{ $item->dress_price }}">
                                                        <input type="hidden" name="deposit_dress"
                                                            value="{{ $item->dress_deposit }}">
                                                        <input type="hidden" name="damage_insurance_dress"
                                                            value="{{ $item->damage_insurance }}">
                                                        <input type="hidden" name="type_dress_name"
                                                            value="{{ $type_dress_name->type_dress_name }}">
                                                        <input type="hidden" name="separable"
                                                            value="{{ $item->separable }}">
                                                        <input type="hidden" name="dress_code"
                                                            value="{{ $item->dress_code_new }}{{ $item->dress_code }}">

                                                        <input type="hidden" name="selectstartDate"
                                                            value="{{ $selectstartDate }}">
                                                        <input type="hidden" name="selectendDate"
                                                            value="{{ $selectendDate }}">
                                                        <input type="hidden" name="selecttotalDay"
                                                            value="{{ $selecttotalDay }}">

                                                        <button type="submit" class="btn btn-success"
                                                            @if ($item->separable == 1) @if ($item->dress_status != 'พร้อมให้เช่า')
                                                            disabled @endif
                                                        @elseif($item->separable == 2)
                                                            @if ($shirtStatus != 'พร้อมให้เช่า' || $skirtStatus != 'พร้อมให้เช่า') disabled @endif
                                                            @endif>
                                                            เพิ่มลงตะกร้า</button>
                                                    </form>






                                                </div>
                                            </div>

                                            <!-- ข้อมูลเสื้อ -->
                                            <div class="tab-pane fade" id="shirt{{ $item->id }}" role="tabpanel"
                                                aria-labelledby="shirt-tab">
                                                <div class="card-body">
                                                    <!-- เพิ่มข้อมูลเสื้อที่นี่ -->
                                                    <div class="row">
                                                        @foreach ($item->shirtitems as $shirtitem)
                                                            <div class="col-md-6">
                                                                <p><strong>ราคา:</strong>
                                                                    {{ number_format($shirtitem->shirtitem_price, 2) }} บาท
                                                                </p>
                                                                <p><strong>ราคามัดจำ:</strong>
                                                                    {{ number_format($shirtitem->shirtitem_deposit, 2) }}
                                                                    บาท</p>
                                                                <p><strong>ราคาประกันค่าเสียหาย:</strong>
                                                                    {{ number_format($shirtitem->shirt_damage_insurance, 2) }}
                                                                    บาท</p>

                                                            </div>
                                                            <div class="col-md-6">
                                                                <p><strong>จำนวนเสื้อ:</strong> 1 ตัว</p>
                                                                <p><strong>สถานะเสื้อ:</strong><span
                                                                        @if ($shirtitem->shirtitem_status == 'พร้อมให้เช่า') style="color: #39d628" 
                                                                    @else
                                                                    style="color: red" @endif>{{ $shirtitem->shirtitem_status }}</span>
                                                                </p>
                                                                <p><strong>จำนวนครั้งที่ถูกเช่า:</strong>
                                                                    {{ $shirtitem->shirtitem_rental }} ครั้ง</p>
                                                            </div>
                                                            @php
                                                                $shirtitem_id = $shirtitem->id;
                                                                $shirtitem_price = $shirtitem->shirtitem_price;
                                                                $shirtitem_deposit = $shirtitem->shirtitem_deposit;
                                                                $shirt_damage_insurance =
                                                                    $shirtitem->shirt_damage_insurance;
                                                            @endphp
                                                        @endforeach
                                                    </div>




                                                    <h5 class="mt-4">ขนาดของเสื้อเริ่มต้น<i>(ปรับแก้ ขยาย ลด ไม่เกิน 3
                                                            นิ้ว)</i></h5>
                                                    @foreach (App\Models\Dressmeasurement::where('shirtitems_id', App\Models\Shirtitem::where('dress_id', $item->id)->value('id'))->get() as $measument_yes_separate_shirt)
                                                        {{ $measument_yes_separate_shirt->measurement_dress_name }}&nbsp;{{ $measument_yes_separate_shirt->measurement_dress_number }}&nbsp;{{ $measument_yes_separate_shirt->measurement_dress_unit }}
                                                    @endforeach
                                                    <h5 class="mt-4">ขนาดของเสื้อล่าสุด</h5>
                                                    @foreach (App\Models\Dressmeasurementnow::where('shirtitems_id', App\Models\Shirtitem::where('dress_id', $item->id)->value('id'))->get() as $measument_yes_separate_now_shirt)
                                                        {{ $measument_yes_separate_now_shirt->measurementnow_dress_name }}&nbsp;{{ $measument_yes_separate_now_shirt->measurementnow_dress_number }}&nbsp;{{ $measument_yes_separate_now_shirt->measurementnow_dress_unit }}
                                                    @endforeach
                                                </div>

                                                <div class="d-flex justify-content-end">
                                                    @if ($item->separable == 2)
                                                        <form action="{{ route('employee.addrentdresscart') }}"
                                                            method="POST">
                                                            @csrf
                                                            <input type="hidden" name="dress_id"
                                                                value="{{ $item->id }}">
                                                            <input type="hidden" name="shirtitem_id"
                                                                value="{{ $shirtitem_id }}">
                                                            <input type="hidden" name="shirtitem_price"
                                                                value="{{ $shirtitem_price }}">
                                                            <input type="hidden" name="shirtitem_deposit"
                                                                value="{{ $shirtitem_deposit }}">
                                                            <input type="hidden" name="shirt_damage_insurance"
                                                                value="{{ $shirt_damage_insurance }}">
                                                            <input type="hidden" name="type_dress_name"
                                                                value="{{ $type_dress_name->type_dress_name }}">
                                                            <input type="hidden" name="separable"
                                                                value="{{ $item->separable }}">
                                                            <input type="hidden" name="dress_code"
                                                                value="{{ $item->dress_code_new }}{{ $item->dress_code }}">
                                                            <input type="hidden" name="selectstartDate"
                                                                value="{{ $selectstartDate }}">
                                                            <input type="hidden" name="selectendDate"
                                                                value="{{ $selectendDate }}">
                                                            <input type="hidden" name="selecttotalDay"
                                                                value="{{ $selecttotalDay }}">


                                                            <button type="submit" class="btn btn-success"
                                                                @if ($shirtStatus != 'พร้อมให้เช่า') disabled @endif>เพิ่มลงตะกร้า</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>


                                            <!-- ข้อมูลกางเกง -->
                                            <div class="tab-pane fade" id="pants{{ $item->id }}" role="tabpanel"
                                                aria-labelledby="pants-tab">

                                                <div class="card-body">
                                                    <!-- เพิ่มข้อมูลกางเกงที่นี่ -->
                                                    <div class="row">
                                                        @foreach ($item->skirtitems as $skirtitem)
                                                            <div class="col-md-6">
                                                                <p><strong>ราคา:</strong>
                                                                    {{ number_format($skirtitem->skirtitem_price, 2) }} บาท
                                                                </p>
                                                                <p><strong>ราคามัดจำ:</strong>
                                                                    {{ number_format($skirtitem->skirtitem_deposit, 2) }}
                                                                    บาท
                                                                </p>
                                                                <p><strong>ราคาประกันค่าเสียหาย:</strong>
                                                                    {{ number_format($skirtitem->skirt_damage_insurance, 2) }}
                                                                    บาท
                                                                </p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <p><strong>จำนวนกระโปรง/กางเกง:</strong> 1 ตัว</p>
                                                                <p><strong>สถานะกระโปรง/กางเกง:</strong> <span
                                                                        @if ($skirtitem->skirtitem_status == 'พร้อมให้เช่า') style="color: #39d628" 
                                                                    @else
                                                                    style="color: red" @endif>{{ $skirtitem->skirtitem_status }}</span>
                                                                </p>
                                                                <p><strong>จำนวนครั้งที่ถูกเช่า:</strong>
                                                                    {{ $skirtitem->skirtitem_rental }} ครั้ง</p>
                                                            </div>
                                                            @php
                                                                $skirtitem_id = $skirtitem->id;
                                                                $skirtitem_price = $skirtitem->skirtitem_price;
                                                                $skirtitem_deposit = $skirtitem->skirtitem_deposit;
                                                                $skirt_damage_insurance =
                                                                    $skirtitem->skirt_damage_insurance;
                                                            @endphp
                                                        @endforeach
                                                    </div>
                                                    <h5 class="mt-4">ขนาดของกระโปรง/กางเกงเริ่มต้น<i>(ปรับแก้ ขยาย ลด
                                                            ไม่เกิน 3 นิ้ว)</i></h5>
                                                    @foreach (App\Models\Dressmeasurement::where('skirtitems_id', App\Models\Skirtitem::where('dress_id', $item->id)->value('id'))->get() as $measument_yes_separate_skirt)
                                                        {{ $measument_yes_separate_skirt->measurement_dress_name }}&nbsp;{{ $measument_yes_separate_skirt->measurement_dress_number }}&nbsp;{{ $measument_yes_separate_skirt->measurement_dress_unit }}
                                                    @endforeach
                                                    <h5 class="mt-4">ขนาดของกระโปรง/กางเกงล่าสุด</h5>
                                                    @foreach (App\Models\Dressmeasurementnow::where('skirtitems_id', App\Models\Skirtitem::where('dress_id', $item->id)->value('id'))->get() as $measument_yes_separate_now_skirt)
                                                        {{ $measument_yes_separate_now_skirt->measurementnow_dress_name }}&nbsp;{{ $measument_yes_separate_now_skirt->measurementnow_dress_number }}&nbsp;{{ $measument_yes_separate_now_skirt->measurementnow_dress_unit }}
                                                    @endforeach
                                                </div>
                                                <div class="d-flex justify-content-end">
                                                    @if ($item->separable == 2)
                                                        <form action="{{ route('employee.addrentdresscart') }}"
                                                            method="POST">
                                                            @csrf
                                                            <input type="hidden" name="dress_id"
                                                                value="{{ $item->id }}">
                                                            <input type="hidden" name="skirtitem_id"
                                                                value="{{ $skirtitem_id }}">
                                                            <input type="hidden" name="skirtitem_price"
                                                                value="{{ $skirtitem_price }}">
                                                            <input type="hidden" name="skirtitem_deposit"
                                                                value="{{ $skirtitem_deposit }}">
                                                            <input type="hidden" name="skirt_damage_insurance"
                                                                value="{{ $skirt_damage_insurance }}">
                                                            <input type="hidden" name="type_dress_name"
                                                                value="{{ $type_dress_name->type_dress_name }}">
                                                            <input type="hidden" name="separable"
                                                                value="{{ $item->separable }}">
                                                            <input type="hidden" name="dress_code"
                                                                value="{{ $item->dress_code_new }}{{ $item->dress_code }}">
                                                            <input type="hidden" name="selectstartDate"
                                                                value="{{ $selectstartDate }}">
                                                            <input type="hidden" name="selectendDate"
                                                                value="{{ $selectendDate }}">
                                                            <input type="hidden" name="selecttotalDay"
                                                                value="{{ $selecttotalDay }}">


                                                            <button type="submit" class="btn btn-success"
                                                                @if ($skirtStatus != 'พร้อมให้เช่า') disabled @endif>เพิ่มลงตะกร้า</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>


                        {{-- <div class="card-footer text-center">
                                <form action="{{ route('employee.addrentdresscart') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="dress_id" value="{{ $item->id }}">
                                    <input type="hidden" name="price_dress" value="{{ $item->dress_price }}">
                                    <input type="hidden" name="deposit_dress" value="{{ $item->dress_deposit }}">
                                    <input type="hidden" name="type_dress_name"
                                        value="{{ $type_dress_name->type_dress_name }}">
                                    <input type="hidden" name="dress_code"
                                        value="{{ $item->dress_code_new }}{{ $item->dress_code }}">
                                    <input type="hidden" name="dress_color" value="{{ $item->dress_color }}">

                                    <button class="btn btn-primary" type="submit">เพิ่มลงในตะกร้า</button>
                                </form>
                            </div> --}}
                    </div>
                </div>
                {{-- @endif --}}
            @endforeach
        </div>
    </div>
@endsection
