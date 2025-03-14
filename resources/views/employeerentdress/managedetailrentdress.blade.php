@extends('layouts.adminlayout')
@section('content')
    <style>
        .status-timeline {
            padding: 20px 0;
        }

        .status-step {
            z-index: 1;
            position: relative;
        }

        .status-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            color: #6c757d;
        }

        .status-icon.active {
            background-color: #6B5949;
            color: #000;
            /* เปลี่ยนสีตัวอักษรเป็นสีดำเพื่อให้เห็นชัดบนพื้นสีเหลือง */
        }

        .status-line {
            flex-grow: 1;
            height: 3px;
            background-color: #EBC591;
            position: relative;
            top: 25px;
            z-index: 0;
        }

        .status-line::after {
            content: '';
            position: absolute;
            right: -10px;
            /* Adjust this value to align the arrow */
            top: 50%;
            transform: translateY(-50%);
            border-left: 10px solid #EBC591;
            /* Arrow color */
            border-top: 5px solid transparent;
            border-bottom: 5px solid transparent;
        }


        .status-step p {
            margin-bottom: 0;
        }

        .status-step small {
            color: #6c757d;
        }
    </style>


    <div class="modal fade" id="showfail" role="dialog" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog" role="document">
            <div class="modal-content custom-modal-content"
                style="max-width: 300px; height: 50px; width: 100%; margin: auto; background-color: #EE4E4E; border: 2px solid #EE4E4E; ">
                <div class="modal-body" style="padding: 5px; display: flex; align-items: center; justify-content: center;">
                    <p style="margin: 0; color: #ffffff;">{{ session('fail') }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="showsuccess" role="dialog" aria-hidden="true">
        <div class="modal-dialog custom-modal-dialog" role="document">
            <div class="modal-content custom-modal-content"
                style="max-width: 400px; height: 50px; width: 100%; margin: auto; background-color: #EAD8C0; border: 2px solid #EAD8C0; ">
                <div class="modal-body shadow"
                    style="padding: 10px; display: flex; align-items: center; justify-content: center;">
                    <p style="margin: 0; color: #000000;">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    </div>





    <script>
        @if (session('fail'))
            setTimeout(function() {
                $('#showfail').modal('show');
            }, 500);
        @endif
    </script>










    <script>
        @if (session('success'))
            setTimeout(function() {
                $('#showsuccess').modal('show');
            }, 500);
        @endif
    </script>


















    


    <ol class="breadcrumb" style="background-color: transparent;">
        <li class="breadcrumb-item">
            <a href="{{ route('employee.ordertotal') }}" style="color: black ; ">รายการออเดอร์ทั้งหมด</a>
        </li>

        <li class="breadcrumb-item">
            <a href="{{ route('employee.ordertotaldetail', ['id' => $orderdetail->order_id]) }}" style="color: black ; ">รายการออเดอร์ที่ {{ $orderdetail->order_id }} </a>
        </li>
        
        <li class="breadcrumb-item active">
            รายละเอียดที่ {{ $orderdetail->id }}
        </li>
    </ol>




    <div class="container mt-4">
        @php
            $dress = App\Models\Dress::find($orderdetail->dress_id);
            $typename = App\Models\Typedress::where('id', $dress->type_dress_id)->value('type_dress_name');
        @endphp


        {{-- เอาไว้นับเงื่อนไขของปุ่มว่ามีชุดที่ปรับแก้ไขไหมนะ  --}}
        @php
            $check_button_updatestatusadjust = false;
            foreach ($dress_mea_adjust_button as $dress_mea_adjust_button) {
                $dress_mea = App\Models\Dressmea::where('id', $dress_mea_adjust_button->dressmea_id)->first();
                if ($dress_mea_adjust_button->new_size != $dress_mea->current_mea) {
                    $check_button_updatestatusadjust = true; //แปลว่าต้องแก้
                }
            }
        @endphp


        {{-- เช็คว่ามันอยู่คิวไหน --}}
        @php
            $reservation_now = App\Models\Reservation::where('status_completed', 0)
                ->where('dress_id', $orderdetail->dress_id)
                ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                ->first();

            // เช็คว่าเช่าทั้งชุด หรือ เช่าแค่เสื้อ หรือเช่าแค่ผ้าถุง
            if ($orderdetail->skirtitems_id) {
                //  ตรวจสอบเฉพาะผ้าถุงก่อน
                $status_skirt = App\Models\Reservation::where('status_completed', 0)
                    ->where('dress_id', $orderdetail->dress_id)
                    ->where('skirtitems_id', $orderdetail->skirtitems_id)
                    ->whereNull('shirtitems_id')
                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                    ->get();

                // ตรวจอสอบเช่าเฉพาะทั้งชุด แต่ห้ามเอาเช่าเฉพาะเสื้อและผ้าถุงมาเกี่ยวข้อง เพราะอย่าไปนับคิวด้วย
                $status_total_dress = App\Models\Reservation::where('status_completed', 0)
                    ->where('dress_id', $orderdetail->dress_id)
                    ->whereNull('shirtitems_id')
                    ->whereNull('skirtitems_id')
                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                    ->get();
                $list__for__one = [];

                foreach ($status_skirt as $item) {
                    $list__for__one[] = $item->id;
                }
                foreach ($status_total_dress as $item) {
                    $list__for__one[] = $item->id;
                }

                $reservation_now = App\Models\reservation::whereIn('id', $list__for__one)
                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                    ->first();

                $check_open_button = true;
                if ($reservation_now) {
                    if ($reservation_now->id == $orderdetail->reservation_id) {
                        // คิวแรก
                        // คิวแรกก็จริงแต่มันก็ต้องไปเช็คว่าพร้อมให้เช่าไหม
                        if (
                            $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status ==
                            'พร้อมให้เช่า'
                        ) {
                            $check_open_button = true;
                        } else {
                            $check_open_button = false;
                        }
                    } elseif ($reservation_now->id != $orderdetail->reservation_id) {
                        // ไม่ใช่คิวแรก
                        $check_open_button = false;
                    }
                } else {
                    $check_open_button = true;
                }
            } elseif ($orderdetail->shirtitems_id) {
                //  ตรวจสอบเฉพาะเสื้อก่อน
                $status_shirt = App\Models\Reservation::where('status_completed', 0)
                    ->where('dress_id', $orderdetail->dress_id)
                    ->where('shirtitems_id', $orderdetail->shirtitems_id)
                    ->whereNull('skirtitems_id')
                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                    ->get();
                // ตรวจอสอบเช่าเฉพาะทั้งชุด แต่ห้ามเอาเช่าเฉพาะผ้าถุง/เสื้อ มาเกี่ยวข้อง เพราะอย่าไปนับคิวด้วย
                $status_total_dress = App\Models\Reservation::where('status_completed', 0)
                    ->where('dress_id', $orderdetail->dress_id)
                    ->whereNull('shirtitems_id')
                    ->whereNull('skirtitems_id')
                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                    ->get();
                $list__for__one = [];

                foreach ($status_shirt as $item) {
                    $list__for__one[] = $item->id;
                }
                foreach ($status_total_dress as $item) {
                    $list__for__one[] = $item->id;
                }
                $reservation_now = App\Models\reservation::whereIn('id', $list__for__one)
                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                    ->first();

                $check_open_button = true;
                if ($reservation_now) {
                    if ($reservation_now->id == $orderdetail->reservation_id) {
                        // คิวแรก
                        // คิวแรกก็จริงแต่มันก็ต้องไปเช็คว่าพร้อมให้เช่าไหม
                        if (
                            $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status ==
                            'พร้อมให้เช่า'
                        ) {
                            $check_open_button = true;
                        } else {
                            $check_open_button = false;
                        }
                    } elseif ($reservation_now->id != $orderdetail->reservation_id) {
                        // ไม่ใช่คิวแรก
                        $check_open_button = false;
                    }
                } else {
                    $check_open_button = true;
                }
            } else {
                $reservation_now = App\Models\Reservation::where('status_completed', 0)
                    ->where('dress_id', $orderdetail->dress_id)
                    ->orderByRaw(" STR_TO_DATE(start_date,'%Y-%m-%d') asc ")
                    ->first();
                $check_open_button = true;
                if ($reservation_now) {
                    if ($reservation_now->id == $orderdetail->reservation_id) {
                        // คิวแรก
                        if ($orderdetail->orderdetailmanytoonedress->separable == 1) {
                            if ($orderdetail->orderdetailmanytoonedress->dress_status == 'พร้อมให้เช่า') {
                                $check_open_button = true;
                            } else {
                                $check_open_button = false;
                            }
                        } elseif ($orderdetail->orderdetailmanytoonedress->separable == 2) {
                            if (
                                $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status ==
                                    'พร้อมให้เช่า' &&
                                $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status ==
                                    'พร้อมให้เช่า'
                            ) {
                                $check_open_button = true;
                            } else {
                                $check_open_button = false;
                            }
                        }
                    } elseif ($reservation_now->id != $orderdetail->reservation_id) {
                        // ไม่ใช่คิวแรก
                        $check_open_button = false;
                    }
                } else {
                    $check_open_button = true;
                }
            }

        @endphp

        {{-- <p>คิวแรก {{ $reservation_now->id }}</p> --}}


        {{-- เอาไว้เช็คว่่าถ้า reservation_id มีคอลัมน์ status_completed เป็น 1 อะ ไอ้พวกแจ้งเตือนต่างๆไม่ต้องให้มันแสดงผลขึ้นมา เพราะมันเป็นประวัติไปแล้ว ไม่จำเป็นต้องแจ้งเตือน --}}
        @php
            $check_reser_status_for_his = App\Models\Reservation::where('id', $orderdetail->reservation_id)->value(
                'status_completed',
            );
        @endphp

        @if ($check_reser_status_for_his != 1)
            @if ($check_open_button == false)
                @if ($reservation_now->id == $orderdetail->reservation_id)

                    {{-- คิวแรก --}}
                    @if ($orderdetail->shirtitems_id)

                        @if ($orderdetail->status_detail == 'ยกเลิกโดยลูกค้า' || $orderdetail->status_detail == 'ยกเลิกโดยทางร้าน')
                            @if ($orderdetail->status_detail == 'ยกเลิกโดยทางร้าน')
                                @if (
                                    $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'สูญหาย' ||
                                        $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'ยุติการให้เช่า')
                                    <div class="alert alert-danger" role="alert">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <div>
                                                {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                                <p class="mb-0">
                                                    <strong>รายการนี้ถูกยกเลิกการจองโดยทางร้านเนื่องจาก{{ $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status }}
                                                    </strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-danger" role="alert">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <div>
                                                {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                                <p class="mb-0">
                                                    <strong>รายการนี้ถูกยกเลิกการจองโดยทางร้าน
                                                    </strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @elseif($orderdetail->status_detail == 'ยกเลิกโดยลูกค้า')
                                <div class="alert alert-danger" role="alert">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <div>
                                            {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                            <p class="mb-0">
                                                <strong>รายการนี้ถูกยกเลิกการจองโดยลูกค้า
                                                </strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @elseif(
                            $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'สูญหาย' ||
                                $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'ยุติการให้เช่า')
                            <div class="alert alert-danger" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <div>
                                        {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                        <p class="mb-0">
                                            <strong>{{ $typename }}{{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                                (เสื้อ)
                                                {{ $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status }}
                                                กรุณาติดต่อลูกค้าเพื่อแจ้งยกเลิกการจองและคืนเงินมัดจำ เบอร์ติดต่อลูกค้า :
                                                {{ $customer->customer_phone }}</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            @if ($orderdetail->status_detail == 'ถูกจอง')
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger" role="alert">
                                            <strong>แจ้งเตือน:</strong>
                                            {{ $typename }}{{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                            (เสื้อ)<span>
                                                {{ $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status }}
                                            </span>
                                            กรุณารอจนกว่าจะพร้อมใช้งาน
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @elseif($orderdetail->skirtitems_id)
                        @if ($orderdetail->status_detail == 'ยกเลิกโดยลูกค้า' || $orderdetail->status_detail == 'ยกเลิกโดยทางร้าน')
                            @if ($orderdetail->status_detail == 'ยกเลิกโดยทางร้าน')
                                @if (
                                    $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'สูญหาย' ||
                                        $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'ยุติการให้เช่า')
                                    <div class="alert alert-danger" role="alert">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <div>
                                                {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                                <p class="mb-0">
                                                    <strong>รายการนี้ถูกยกเลิกการจองโดยทางร้านเนื่องจาก{{ $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status }}
                                                    </strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-danger" role="alert">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <div>
                                                {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                                <p class="mb-0">
                                                    <strong>รายการนี้ถูกยกเลิกการจองโดยทางร้าน
                                                    </strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @elseif($orderdetail->status_detail == 'ยกเลิกโดยลูกค้า')
                                <div class="alert alert-danger" role="alert">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <div>
                                            {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                            <p class="mb-0">
                                                <strong>รายการนี้ถูกยกเลิกการจองโดยลูกค้า
                                                </strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @elseif(
                            $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'สูญหาย' ||
                                $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'ยุติการให้เช่า')
                            <div class="alert alert-danger" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <div>
                                        {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                        <p class="mb-0"><strong>{{ $typename }}
                                                {{ $dress->dress_code_new }}{{ $dress->dress_code }} (ผ้าถุง)
                                                {{ $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status }}
                                                กรุณาติดต่อลูกค้าเพื่อแจ้งยกเลิกการจองและคืนเงินมัดจำ เบอร์ติดต่อลูกค้า :
                                                {{ $customer->customer_phone }}</strong></p>
                                    </div>
                                </div>
                            </div>
                        @else
                            @if ($orderdetail->status_detail == 'ถูกจอง')
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger" role="alert">
                                            <strong>แจ้งเตือน:</strong>
                                            {{ $typename }}{{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                            (ผ้าถุง)<span>
                                                {{ $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status }}
                                            </span>
                                            กรุณารอจนกว่าจะพร้อมใช้งาน
                                        </div>
                                    </div>
                                </div>
                            @endif



                        @endif
                    @else
                        {{-- ส่วนนี้ --}}
                        @if ($orderdetail->orderdetailmanytoonedress->separable == 1)
                            @if ($orderdetail->status_detail == 'ยกเลิกโดยลูกค้า' || $orderdetail->status_detail == 'ยกเลิกโดยทางร้าน')
                                @if ($orderdetail->status_detail == 'ยกเลิกโดยทางร้าน')
                                    @if (
                                        $orderdetail->orderdetailmanytoonedress->dress_status == 'สูญหาย' ||
                                            $orderdetail->orderdetailmanytoonedress->dress_status == 'ยุติการให้เช่า')
                                        <div class="alert alert-danger" role="alert">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <div>
                                                    {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                                    <p class="mb-0">
                                                        <strong>รายการนี้ถูกยกเลิกการจองโดยทางร้านเนื่องจาก{{ $orderdetail->orderdetailmanytoonedress->dress_status }}
                                                        </strong>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-danger" role="alert">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <div>
                                                    {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                                    <p class="mb-0">
                                                        <strong>รายการนี้ถูกยกเลิกการจองโดยทางร้าน
                                                        </strong>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @elseif($orderdetail->status_detail == 'ยกเลิกโดยลูกค้า')
                                    <div class="alert alert-danger" role="alert">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <div>
                                                {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                                <p class="mb-0">
                                                    <strong>รายการนี้ถูกยกเลิกการจองโดยลูกค้า
                                                    </strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @elseif(
                                $orderdetail->orderdetailmanytoonedress->dress_status == 'สูญหาย' ||
                                    $orderdetail->orderdetailmanytoonedress->dress_status == 'ยุติการให้เช่า')
                                <div class="alert alert-danger" role="alert">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <div>
                                            {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                            <p class="mb-0"><strong>{{ $typename }}
                                                    {{ $dress->dress_code_new }}{{ $dress->dress_code }} (ทั้งชุด)
                                                    {{ $orderdetail->orderdetailmanytoonedress->dress_status }}
                                                    กรุณาติดต่อลูกค้าเพื่อแจ้งยกเลิกการจองและคืนเงินมัดจำ เบอร์ติดต่อลูกค้า
                                                    :
                                                    {{ $customer->customer_phone }}</strong></p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                @if ($orderdetail->status_detail == 'ถูกจอง')
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <div class="alert alert-danger" role="alert">
                                                <strong>แจ้งเตือน:</strong>
                                                {{ $typename }}{{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                                (ทั้งชุด)<span>
                                                    {{ $orderdetail->orderdetailmanytoonedress->dress_status }}
                                                </span>
                                                กรุณารอจนกว่าจะพร้อมใช้งาน
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            @endif
                        @elseif($orderdetail->orderdetailmanytoonedress->separable == 2)
                            @if ($orderdetail->status_detail == 'ยกเลิกโดยลูกค้า' || $orderdetail->status_detail == 'ยกเลิกโดยทางร้าน')
                                @if ($orderdetail->status_detail == 'ยกเลิกโดยทางร้าน')
                                    @if (
                                        $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'สูญหาย' ||
                                            $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'ยุติการให้เช่า' ||
                                            $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'สูญหาย' ||
                                            $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'ยุติการให้เช่า')
                                        <div class="alert alert-danger" role="alert">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <div>
                                                    {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                                    <p class="mb-0">
                                                        <strong>รายการนี้ถูกยกเลิกการจองโดยทางร้านเนื่องจาก
                                                            @if (
                                                                $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'สูญหาย' ||
                                                                    $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'ยุติการให้เช่า')
                                                                {{ $typename }}{{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                                                (เสื้อ) :
                                                                {{ $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status }}
                                                            @endif

                                                            @if (
                                                                $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'สูญหาย' ||
                                                                    $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'ยุติการให้เช่า')
                                                                {{ $typename }}{{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                                                (ผ้าถุง) :
                                                                {{ $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status }}
                                                            @endif




                                                        </strong>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-danger" role="alert">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <div>
                                                    {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                                    <p class="mb-0">
                                                        <strong>รายการนี้ถูกยกเลิกการจองโดยทางร้าน
                                                        </strong>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @elseif($orderdetail->status_detail == 'ยกเลิกโดยลูกค้า')
                                    <div class="alert alert-danger" role="alert">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <div>
                                                {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                                <p class="mb-0">
                                                    <strong>รายการนี้ถูกยกเลิกการจองโดยลูกค้า
                                                    </strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @elseif(
                                $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'สูญหาย' ||
                                    $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'ยุติการให้เช่า' ||
                                    $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'สูญหาย' ||
                                    $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'ยุติการให้เช่า')
                                <div class="alert alert-danger" role="alert">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <div>
                                            {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                            <p class="mb-0"><strong>

                                                    @if (
                                                        $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'สูญหาย' ||
                                                            $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'ยุติการให้เช่า')
                                                        {{ $typename }}{{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                                        (เสื้อ) :
                                                        {{ $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status }}
                                                    @endif

                                                    @if (
                                                        $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'สูญหาย' ||
                                                            $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'ยุติการให้เช่า')
                                                        {{ $typename }}{{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                                        (ผ้าถุง) :
                                                        {{ $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status }}
                                                    @endif
                                                    ส่งผลให้ไม่ครบชุด
                                                    กรุณาติดต่อลูกค้าเพื่อแจ้งยกเลิกการจองและคืนเงินมัดจำ เบอร์ติดต่อลูกค้า
                                                    :
                                                    {{ $customer->customer_phone }}
                                                </strong></p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                {{-- ... --}}
                                @if ($orderdetail->status_detail == 'ถูกจอง')
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <div class="alert alert-danger" role="alert">
                                                <strong>แจ้งเตือน:</strong>



                                                @if ($orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status != 'พร้อมให้เช่า')
                                                    {{ $typename }}{{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                                    (เสื้อ)<span>
                                                        :
                                                        {{ $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status }}
                                                    </span>
                                                @endif

                                                @if ($orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status != 'พร้อมให้เช่า')
                                                    {{ $typename }}{{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                                    (ผ้าถุง)<span>
                                                        :
                                                        {{ $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status }}
                                                    </span>
                                                @endif
                                                กรุณารอจนกว่าจะพร้อมใช้งาน
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endif



                    @endif
                @elseif($reservation_now->id != $orderdetail->reservation_id)
                    {{-- ไม่ใช่คิวแรก --}}
                    @if ($orderdetail->shirtitems_id)
                        @if ($orderdetail->status_detail == 'ยกเลิกโดยลูกค้า' || $orderdetail->status_detail == 'ยกเลิกโดยทางร้าน')
                            @if ($orderdetail->status_detail == 'ยกเลิกโดยทางร้าน')
                                @if (
                                    $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'สูญหาย' ||
                                        $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'ยุติการให้เช่า')
                                    <div class="alert alert-danger" role="alert">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <div>
                                                {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                                <p class="mb-0">
                                                    <strong>รายการนี้ถูกยกเลิกการจองโดยทางร้านเนื่องจาก{{ $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status }}
                                                    </strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-danger" role="alert">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <div>
                                                {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                                <p class="mb-0">
                                                    <strong>รายการนี้ถูกยกเลิกการจองโดยทางร้าน
                                                    </strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @elseif($orderdetail->status_detail == 'ยกเลิกโดยลูกค้า')
                                <div class="alert alert-danger" role="alert">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <div>
                                            {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                            <p class="mb-0">
                                                <strong>รายการนี้ถูกยกเลิกการจองโดยลูกค้า
                                                </strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @elseif(
                            $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'สูญหาย' ||
                                $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'ยุติการให้เช่า')
                            <div class="alert alert-danger" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <div>
                                        {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                        <p class="mb-0">
                                            <strong>{{ $typename }}{{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                                (เสื้อ)
                                                {{ $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status }}
                                                กรุณาติดต่อลูกค้าเพื่อแจ้งยกเลิกการจองและคืนเงินมัดจำ เบอร์ติดต่อลูกค้า :
                                                {{ $customer->customer_phone }}</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="alert alert-danger" role="alert">


                                        <p class="mb-0">
                                            @php
                                                $find_order_detail_now = App\Models\Orderdetail::where(
                                                    'reservation_id',
                                                    $reservation_now->id,
                                                )->first();
                                                $find_order_detail_id = App\Models\Orderdetail::find(
                                                    $find_order_detail_now->id,
                                                );
                                                $customer_id_re = App\Models\order::where(
                                                    'id',
                                                    $find_order_detail_id->order_id,
                                                )->value('customer_id');
                                                $customer_fname_re = App\Models\Customer::where(
                                                    'id',
                                                    $customer_id_re,
                                                )->value('customer_fname');
                                                $customer_lname_re = App\Models\Customer::where(
                                                    'id',
                                                    $customer_id_re,
                                                )->value('customer_lname');

                                            @endphp
                                            <strong>แจ้งเตือน:</strong>
                                            {{ $typename }}{{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                            (เสื้อ) นี้{{ $reservation_now->status }}โดยลูกค้าท่านอื่น
                                            ไม่สามารถดำเนินการในรายการนี้ได้<br>

                                            <strong>รายละเอียดการเช่าปัจจุบัน:</strong> <a
                                                href="{{ route('employee.ordertotaldetailshow', ['id' => $find_order_detail_now->id]) }}">ดูรายละเอียด</a><br>
                                            &bull; ลูกค้า: คุณ{{ $customer_fname_re }} {{ $customer_lname_re }}<br>
                                            &bull; วันที่เช่า:
                                            {{ \Carbon\carbon::parse($reservation_now->start_date)->locale('th')->isoFormat('D MMM') }}
                                            {{ \Carbon\carbon::parse($reservation_now->start_date)->year + 543 }}
                                            <br>
                                            &bull; กำหนดคืน:
                                            {{ \Carbon\carbon::parse($reservation_now->end_date)->locale('th')->isoFormat('D MMM') }}
                                            {{ \Carbon\carbon::parse($reservation_now->end_date)->year + 543 }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @elseif($orderdetail->skirtitems_id)
                        @if ($orderdetail->status_detail == 'ยกเลิกโดยลูกค้า' || $orderdetail->status_detail == 'ยกเลิกโดยทางร้าน')
                            @if ($orderdetail->status_detail == 'ยกเลิกโดยทางร้าน')
                                @if (
                                    $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'สูญหาย' ||
                                        $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'ยุติการให้เช่า')
                                    <div class="alert alert-danger" role="alert">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <div>
                                                {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                                <p class="mb-0">
                                                    <strong>รายการนี้ถูกยกเลิกการจองโดยทางร้านเนื่องจาก{{ $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status }}
                                                    </strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-danger" role="alert">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <div>
                                                {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                                <p class="mb-0">
                                                    <strong>รายการนี้ถูกยกเลิกการจองโดยทางร้าน
                                                    </strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @elseif($orderdetail->status_detail == 'ยกเลิกโดยลูกค้า')
                                <div class="alert alert-danger" role="alert">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <div>
                                            {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                            <p class="mb-0">
                                                <strong>รายการนี้ถูกยกเลิกการจองโดยลูกค้า
                                                </strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @elseif(
                            $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'สูญหาย' ||
                                $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'ยุติการให้เช่า')
                            <div class="alert alert-danger" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <div>
                                        {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                        <p class="mb-0"><strong>{{ $typename }}
                                                {{ $dress->dress_code_new }}{{ $dress->dress_code }} (ผ้าถุง)
                                                {{ $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status }}
                                                กรุณาติดต่อลูกค้าเพื่อแจ้งยกเลิกการจองและคืนเงินมัดจำ เบอร์ติดต่อลูกค้า :
                                                {{ $customer->customer_phone }}</strong></p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="alert alert-danger" role="alert">
                                        <p class="mb-0">
                                            @php
                                                $find_order_detail_now = App\Models\Orderdetail::where(
                                                    'reservation_id',
                                                    $reservation_now->id,
                                                )->first();
                                                $find_order_detail_id = App\Models\Orderdetail::find(
                                                    $find_order_detail_now->id,
                                                );
                                                $customer_id_re = App\Models\order::where(
                                                    'id',
                                                    $find_order_detail_id->order_id,
                                                )->value('customer_id');
                                                $customer_fname_re = App\Models\Customer::where(
                                                    'id',
                                                    $customer_id_re,
                                                )->value('customer_fname');
                                                $customer_lname_re = App\Models\Customer::where(
                                                    'id',
                                                    $customer_id_re,
                                                )->value('customer_lname');

                                            @endphp
                                            <strong>แจ้งเตือน:</strong>
                                            {{ $typename }}{{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                            (ผ้าถุง) นี้{{ $reservation_now->status }}โดยลูกค้าท่านอื่น
                                            ไม่สามารถดำเนินการในรายการนี้ได้<br>
                                            <strong>รายละเอียดการเช่าปัจจุบัน:</strong> <a
                                                href="{{ route('employee.ordertotaldetailshow', ['id' => $find_order_detail_now->id]) }}">ดูรายละเอียด</a><br>
                                            &bull; ลูกค้า: คุณ{{ $customer_fname_re }} {{ $customer_lname_re }}<br>
                                            &bull; วันที่เช่า:
                                            {{ \Carbon\carbon::parse($reservation_now->start_date)->locale('th')->isoFormat('D MMM') }}
                                            {{ \Carbon\carbon::parse($reservation_now->start_date)->year + 543 }}
                                            <br>
                                            &bull; กำหนดคืน:
                                            {{ \Carbon\carbon::parse($reservation_now->end_date)->locale('th')->isoFormat('D MMM') }}
                                            {{ \Carbon\carbon::parse($reservation_now->end_date)->year + 543 }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        @if ($orderdetail->orderdetailmanytoonedress->separable == 1)
                            @if ($orderdetail->status_detail == 'ยกเลิกโดยลูกค้า' || $orderdetail->status_detail == 'ยกเลิกโดยทางร้าน')
                                @if ($orderdetail->status_detail == 'ยกเลิกโดยทางร้าน')
                                    @if (
                                        $orderdetail->orderdetailmanytoonedress->dress_status == 'สูญหาย' ||
                                            $orderdetail->orderdetailmanytoonedress->dress_status == 'ยุติการให้เช่า')
                                        <div class="alert alert-danger" role="alert">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <div>
                                                    {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                                    <p class="mb-0">
                                                        <strong>รายการนี้ถูกยกเลิกการจองโดยทางร้านเนื่องจาก{{ $orderdetail->orderdetailmanytoonedress->dress_status }}
                                                        </strong>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-danger" role="alert">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <div>
                                                    {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                                    <p class="mb-0">
                                                        <strong>รายการนี้ถูกยกเลิกการจองโดยทางร้าน
                                                        </strong>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @elseif($orderdetail->status_detail == 'ยกเลิกโดยลูกค้า')
                                    <div class="alert alert-danger" role="alert">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <div>
                                                {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                                <p class="mb-0">
                                                    <strong>รายการนี้ถูกยกเลิกการจองโดยลูกค้า
                                                    </strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @elseif(
                                $orderdetail->orderdetailmanytoonedress->dress_status == 'สูญหาย' ||
                                    $orderdetail->orderdetailmanytoonedress->dress_status == 'ยุติการให้เช่า')
                                <div class="alert alert-danger" role="alert">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <div>
                                            {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                            <p class="mb-0"><strong>{{ $typename }}
                                                    {{ $dress->dress_code_new }}{{ $dress->dress_code }} (ทั้งชุด)
                                                    {{ $orderdetail->orderdetailmanytoonedress->dress_status }}
                                                    กรุณาติดต่อลูกค้าเพื่อแจ้งยกเลิกการจองและคืนเงินมัดจำ เบอร์ติดต่อลูกค้า
                                                    :
                                                    {{ $customer->customer_phone }}</strong></p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger" role="alert">
                                            <p class="mb-0">
                                                @php
                                                    $find_order_detail_now = App\Models\Orderdetail::where(
                                                        'reservation_id',
                                                        $reservation_now->id,
                                                    )->first();
                                                    $find_order_detail_id = App\Models\Orderdetail::find(
                                                        $find_order_detail_now->id,
                                                    );
                                                    $customer_id_re = App\Models\order::where(
                                                        'id',
                                                        $find_order_detail_id->order_id,
                                                    )->value('customer_id');
                                                    $customer_fname_re = App\Models\Customer::where(
                                                        'id',
                                                        $customer_id_re,
                                                    )->value('customer_fname');
                                                    $customer_lname_re = App\Models\Customer::where(
                                                        'id',
                                                        $customer_id_re,
                                                    )->value('customer_lname');

                                                @endphp
                                                <strong>แจ้งเตือน:</strong>
                                                {{ $typename }}{{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                                (ทั้งชุด) นี้{{ $reservation_now->status }}โดยลูกค้าท่านอื่น
                                                ไม่สามารถดำเนินการในรายการนี้ได้<br>
                                                <strong>รายละเอียดการเช่าปัจจุบัน:</strong> <a
                                                    href="{{ route('employee.ordertotaldetailshow', ['id' => $find_order_detail_now->id]) }}">ดูรายละเอียด</a><br>
                                                &bull; ลูกค้า: คุณ{{ $customer_fname_re }} {{ $customer_lname_re }}<br>
                                                &bull; วันที่เช่า:
                                                {{ \Carbon\carbon::parse($reservation_now->start_date)->locale('th')->isoFormat('D MMM') }}
                                                {{ \Carbon\carbon::parse($reservation_now->start_date)->year + 543 }}
                                                <br>
                                                &bull; กำหนดคืน:
                                                {{ \Carbon\carbon::parse($reservation_now->end_date)->locale('th')->isoFormat('D MMM') }}
                                                {{ \Carbon\carbon::parse($reservation_now->end_date)->year + 543 }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @elseif($orderdetail->orderdetailmanytoonedress->separable == 2)
                            @if ($orderdetail->status_detail == 'ยกเลิกโดยลูกค้า' || $orderdetail->status_detail == 'ยกเลิกโดยทางร้าน')
                                @if ($orderdetail->status_detail == 'ยกเลิกโดยทางร้าน')
                                    @if (
                                        $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'สูญหาย' ||
                                            $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'ยุติการให้เช่า' ||
                                            $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'สูญหาย' ||
                                            $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'ยุติการให้เช่า')
                                        <div class="alert alert-danger" role="alert">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <div>
                                                    {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                                    <p class="mb-0">
                                                        <strong>รายการนี้ถูกยกเลิกการจองโดยทางร้านเนื่องจาก
                                                            @if (
                                                                $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'สูญหาย' ||
                                                                    $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'ยุติการให้เช่า')
                                                                {{ $typename }}{{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                                                (เสื้อ) :
                                                                {{ $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status }}
                                                            @endif

                                                            @if (
                                                                $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'สูญหาย' ||
                                                                    $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'ยุติการให้เช่า')
                                                                {{ $typename }}{{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                                                (ผ้าถุง) :
                                                                {{ $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status }}
                                                            @endif




                                                        </strong>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-danger" role="alert">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <div>
                                                    {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                                    <p class="mb-0">
                                                        <strong>รายการนี้ถูกยกเลิกการจองโดยทางร้าน
                                                        </strong>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @elseif($orderdetail->status_detail == 'ยกเลิกโดยลูกค้า')
                                    <div class="alert alert-danger" role="alert">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <div>
                                                {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                                <p class="mb-0">
                                                    <strong>รายการนี้ถูกยกเลิกการจองโดยลูกค้า
                                                    </strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @elseif(
                                $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'สูญหาย' ||
                                    $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'ยุติการให้เช่า' ||
                                    $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'สูญหาย' ||
                                    $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'ยุติการให้เช่า')
                                <div class="alert alert-danger" role="alert">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <div>
                                            {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                            <p class="mb-0"><strong>

                                                    @if (
                                                        $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'สูญหาย' ||
                                                            $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'ยุติการให้เช่า')
                                                        {{ $typename }}{{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                                        (เสื้อ) :
                                                        {{ $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status }}
                                                    @endif

                                                    @if (
                                                        $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'สูญหาย' ||
                                                            $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'ยุติการให้เช่า')
                                                        {{ $typename }}{{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                                        (ผ้าถุง) :
                                                        {{ $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status }}
                                                    @endif
                                                    ส่งผลให้ไม่ครบชุด
                                                    กรุณาติดต่อลูกค้าเพื่อแจ้งยกเลิกการจองและคืนเงินมัดจำ เบอร์ติดต่อลูกค้า
                                                    :
                                                    {{ $customer->customer_phone }}
                                                </strong></p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger" role="alert">
                                            <p class="mb-0">
                                                @php
                                                    $find_order_detail_now = App\Models\Orderdetail::where(
                                                        'reservation_id',
                                                        $reservation_now->id,
                                                    )->first();
                                                    $find_order_detail_id = App\Models\Orderdetail::find(
                                                        $find_order_detail_now->id,
                                                    );
                                                    $customer_id_re = App\Models\order::where(
                                                        'id',
                                                        $find_order_detail_id->order_id,
                                                    )->value('customer_id');
                                                    $customer_fname_re = App\Models\Customer::where(
                                                        'id',
                                                        $customer_id_re,
                                                    )->value('customer_fname');
                                                    $customer_lname_re = App\Models\Customer::where(
                                                        'id',
                                                        $customer_id_re,
                                                    )->value('customer_lname');

                                                @endphp
                                                <strong>แจ้งเตือน:</strong>
                                                {{ $typename }}{{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                                (ทั้งชุด) นี้{{ $reservation_now->status }}โดยลูกค้าท่านอื่น
                                                ไม่สามารถดำเนินการในรายการนี้ได้<br>
                                                <strong>รายละเอียดการเช่าปัจจุบัน:</strong> <a
                                                    href="{{ route('employee.ordertotaldetailshow', ['id' => $find_order_detail_now->id]) }}">ดูรายละเอียด</a><br>
                                                &bull; ลูกค้า: คุณ{{ $customer_fname_re }} {{ $customer_lname_re }}<br>
                                                &bull; วันที่เช่า:
                                                {{ \Carbon\carbon::parse($reservation_now->start_date)->locale('th')->isoFormat('D MMM') }}
                                                {{ \Carbon\carbon::parse($reservation_now->start_date)->year + 543 }}
                                                <br>
                                                &bull; กำหนดคืน:
                                                {{ \Carbon\carbon::parse($reservation_now->end_date)->locale('th')->isoFormat('D MMM') }}
                                                {{ \Carbon\carbon::parse($reservation_now->end_date)->year + 543 }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                        {{-- ทำไร --}}
                    @endif
                @endif
            @endif
        @elseif ($check_reser_status_for_his == 1)
            {{-- ถ้าเงื่อนไขนี้ จะมีอยู่ 1.คืนชุดแล้ว 2.ยกเลิกออเดอร์ --}}
            @if ($orderdetail->status_detail == 'ยกเลิกโดยลูกค้า' || $orderdetail->status_detail == 'ยกเลิกโดยทางร้าน')
                @if ($orderdetail->shirtitems_id)
                    @if ($orderdetail->status_detail == 'ยกเลิกโดยทางร้าน')
                        @if (
                            $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'สูญหาย' ||
                                $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'ยุติการให้เช่า')
                            <div class="alert alert-danger" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <div>
                                        {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                        <p class="mb-0">
                                            <strong>รายการนี้ถูกยกเลิกการจองโดยทางร้านเนื่องจาก{{ $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status }}
                                            </strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-danger" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <div>
                                        {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                        <p class="mb-0">
                                            <strong>รายการนี้ถูกยกเลิกการจองโดยทางร้าน
                                            </strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @elseif($orderdetail->status_detail == 'ยกเลิกโดยลูกค้า')
                        <div class="alert alert-danger" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <div>
                                    {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                    <p class="mb-0">
                                        <strong>รายการนี้ถูกยกเลิกการจองโดยลูกค้า
                                        </strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                @elseif ($orderdetail->skirtitems_id)
                    @if ($orderdetail->status_detail == 'ยกเลิกโดยทางร้าน')
                        @if (
                            $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'สูญหาย' ||
                                $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'ยุติการให้เช่า')
                            <div class="alert alert-danger" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <div>
                                        {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                        <p class="mb-0">
                                            <strong>รายการนี้ถูกยกเลิกการจองโดยทางร้านเนื่องจาก{{ $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status }}
                                            </strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-danger" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <div>
                                        {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                        <p class="mb-0">
                                            <strong>รายการนี้ถูกยกเลิกการจองโดยทางร้าน
                                            </strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @elseif($orderdetail->status_detail == 'ยกเลิกโดยลูกค้า')
                        <div class="alert alert-danger" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <div>
                                    {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                    <p class="mb-0">
                                        <strong>รายการนี้ถูกยกเลิกการจองโดยลูกค้า
                                        </strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    @if ($orderdetail->orderdetailmanytoonedress->separable == 1)
                        @if ($orderdetail->status_detail == 'ยกเลิกโดยลูกค้า' || $orderdetail->status_detail == 'ยกเลิกโดยทางร้าน')
                            @if ($orderdetail->status_detail == 'ยกเลิกโดยทางร้าน')
                                @if (
                                    $orderdetail->orderdetailmanytoonedress->dress_status == 'สูญหาย' ||
                                        $orderdetail->orderdetailmanytoonedress->dress_status == 'ยุติการให้เช่า')
                                    <div class="alert alert-danger" role="alert">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <div>
                                                {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                                <p class="mb-0">
                                                    <strong>รายการนี้ถูกยกเลิกการจองโดยทางร้านเนื่องจาก{{ $orderdetail->orderdetailmanytoonedress->dress_status }}
                                                    </strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-danger" role="alert">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <div>
                                                {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                                <p class="mb-0">
                                                    <strong>รายการนี้ถูกยกเลิกการจองโดยทางร้าน
                                                    </strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @elseif($orderdetail->status_detail == 'ยกเลิกโดยลูกค้า')
                                <div class="alert alert-danger" role="alert">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <div>
                                            {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                            <p class="mb-0">
                                                <strong>รายการนี้ถูกยกเลิกการจองโดยลูกค้า
                                                </strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @elseif(
                            $orderdetail->orderdetailmanytoonedress->dress_status == 'สูญหาย' ||
                                $orderdetail->orderdetailmanytoonedress->dress_status == 'ยุติการให้เช่า')
                            <div class="alert alert-danger" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <div>
                                        {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                        <p class="mb-0"><strong>{{ $typename }}
                                                {{ $dress->dress_code_new }}{{ $dress->dress_code }} (ทั้งชุด)
                                                {{ $orderdetail->orderdetailmanytoonedress->dress_status }}
                                                กรุณาติดต่อลูกค้าเพื่อแจ้งยกเลิกการจองและคืนเงินมัดจำ เบอร์ติดต่อลูกค้า
                                                :
                                                {{ $customer->customer_phone }}</strong></p>
                                    </div>
                                </div>
                            </div>
                        @else
                            @if ($orderdetail->status_detail == 'ถูกจอง')
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger" role="alert">
                                            <strong>แจ้งเตือน:</strong>
                                            {{ $typename }}{{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                            (ทั้งชุด)<span>
                                                {{ $orderdetail->orderdetailmanytoonedress->dress_status }}
                                            </span>
                                            กรุณารอจนกว่าจะพร้อมใช้งาน
                                        </div>
                                    </div>
                                </div>
                            @endif

                        @endif
                    @elseif($orderdetail->orderdetailmanytoonedress->separable == 2)
                        @if ($orderdetail->status_detail == 'ยกเลิกโดยลูกค้า' || $orderdetail->status_detail == 'ยกเลิกโดยทางร้าน')
                            @if ($orderdetail->status_detail == 'ยกเลิกโดยทางร้าน')
                                @if (
                                    $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'สูญหาย' ||
                                        $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'ยุติการให้เช่า' ||
                                        $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'สูญหาย' ||
                                        $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'ยุติการให้เช่า')
                                    <div class="alert alert-danger" role="alert">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <div>
                                                {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                                <p class="mb-0">
                                                    <strong>รายการนี้ถูกยกเลิกการจองโดยทางร้านเนื่องจาก
                                                        @if (
                                                            $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'สูญหาย' ||
                                                                $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'ยุติการให้เช่า')
                                                            {{ $typename }}{{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                                            (เสื้อ) :
                                                            {{ $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status }}
                                                        @endif

                                                        @if (
                                                            $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'สูญหาย' ||
                                                                $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'ยุติการให้เช่า')
                                                            {{ $typename }}{{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                                            (ผ้าถุง) :
                                                            {{ $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status }}
                                                        @endif




                                                    </strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-danger" role="alert">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <div>
                                                {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                                <p class="mb-0">
                                                    <strong>รายการนี้ถูกยกเลิกการจองโดยทางร้าน
                                                    </strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @elseif($orderdetail->status_detail == 'ยกเลิกโดยลูกค้า')
                                <div class="alert alert-danger" role="alert">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <div>
                                            {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                            <p class="mb-0">
                                                <strong>รายการนี้ถูกยกเลิกการจองโดยลูกค้า
                                                </strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @elseif(
                            $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'สูญหาย' ||
                                $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'ยุติการให้เช่า' ||
                                $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'สูญหาย' ||
                                $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'ยุติการให้เช่า')
                            <div class="alert alert-danger" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <div>
                                        {{-- <h4 class="alert-heading">แจ้งเตือนสินค้าสูญหาย!</h4> --}}
                                        <p class="mb-0"><strong>

                                                @if (
                                                    $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'สูญหาย' ||
                                                        $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status == 'ยุติการให้เช่า')
                                                    {{ $typename }}{{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                                    (เสื้อ) :
                                                    {{ $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status }}
                                                @endif

                                                @if (
                                                    $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'สูญหาย' ||
                                                        $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status == 'ยุติการให้เช่า')
                                                    {{ $typename }}{{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                                    (ผ้าถุง) :
                                                    {{ $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status }}
                                                @endif
                                                ส่งผลให้ไม่ครบชุด
                                                กรุณาติดต่อลูกค้าเพื่อแจ้งยกเลิกการจองและคืนเงินมัดจำ เบอร์ติดต่อลูกค้า
                                                :
                                                {{ $customer->customer_phone }}
                                            </strong></p>
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- ... --}}
                            @if ($orderdetail->status_detail == 'ถูกจอง')
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger" role="alert">
                                            <strong>แจ้งเตือน:</strong>



                                            @if ($orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status != 'พร้อมให้เช่า')
                                                {{ $typename }}{{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                                (เสื้อ)<span>
                                                    :
                                                    {{ $orderdetail->orderdetailmanytoonedress->shirtitems->first()->shirtitem_status }}
                                                </span>
                                            @endif

                                            @if ($orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status != 'พร้อมให้เช่า')
                                                {{ $typename }}{{ $dress->dress_code_new }}{{ $dress->dress_code }}
                                                (ผ้าถุง)<span>
                                                    :
                                                    {{ $orderdetail->orderdetailmanytoonedress->skirtitems->first()->skirtitem_status }}
                                                </span>
                                            @endif
                                            กรุณารอจนกว่าจะพร้อมใช้งาน
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endif





                @endif
            @endif

        @endif


        @php
        $customer_id = App\Models\Order::where('id', $orderdetail->order_id)->value('customer_id');
        $customer = App\Models\Customer::find($customer_id);
        $Date = App\Models\Date::where('order_detail_id', $orderdetail->id)->orderBy('created_at', 'desc')->first();
    @endphp



        <h4 class="mt-2"><strong>รายการ :
                @if ($orderdetail->shirtitems_id)
                    เช่า{{ $typename }} {{ $dress->dress_code_new }}{{ $dress->dress_code }} (เสื้อ)
                @elseif($orderdetail->skirtitems_id)
                    เช่า{{ $typename }} {{ $dress->dress_code_new }}{{ $dress->dress_code }}
                    (ผ้าถุง)
                @else
                    เช่า{{ $typename }} {{ $dress->dress_code_new }}{{ $dress->dress_code }}
                    (ทั้งชุด)
                @endif
            </strong>
        </h4>




        <div class="row">
            <div class="col-md-12">




            </div>
        </div>



        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="card-title">สถานะการเช่า</h5>
                            </div>




                            @php
                                // $now_today = now()->setTime(0, 0)->format('Y-m-d');
                                $now_today = now()->format('Y-m-d');

                                // dd($now_today) ;

                            @endphp

                            {{-- && $now_today == $dateeee->pickup_date --}}




                            
                            <div class="col-md-6 text-right"
                                @if ($orderdetail->status_detail == 'ถูกจอง' && $check_button_updatestatusadjust == false && $check_open_button == true  ) 
                                    style="display: block ; "
                                @else
                                style="display: none ; " 
                                @endif
                                >
                                <button class="btn" style="background: #C28041; color: #ffffff;" data-toggle="modal"
                                    data-target="#updatestatus">อัปเดตสถานะการเช่าอยู่นี่</button>
                            </div>




                            <div class="col-md-6 text-right"
                                @if ($orderdetail->status_detail == 'กำลังเช่า') style="display: block ; "@else style="display: none ; " @endif>
                                <button class="btn" style="background: #C28041; color: #ffffff;" data-toggle="modal"
                                    data-target="#updatestatus_return">อัปเดตการรับชุดคืน</button>
                            </div>





                        </div>
                        <div class="status-timeline d-flex justify-content-between position-relative">



                            @php
                                $list_status = [];
                                foreach ($orderdetailstatus as $index => $status) {
                                    $list_status[] = $status->status;
                                }
                            @endphp



                            <div class="status-step text-center">
                                <div class="status-icon @if (in_array('ถูกจอง', $list_status)) active @endif">
                                    {{-- <i class="fas fa-check"></i> --}}
                                </div>
                                <p>ถูกจอง</p>
                                <small>
                                    <p>
                                        @php
                                            $created_at = App\Models\Orderdetailstatus::where(
                                                'order_detail_id',
                                                $orderdetail->id,
                                            )
                                                ->where('status', 'ถูกจอง')
                                                ->first();
                                            if ($created_at) {
                                                $text_date = Carbon\Carbon::parse($created_at->created_at)
                                                    ->addHours(7)
                                                    ->format('d/m/Y H:i');
                                            } else {
                                                $text_date = 'รอดำเนินการ';
                                            }
                                        @endphp
                                        {{ $text_date }}
                                    </p>
                                </small>
                            </div>


                            <div class="status-line "></div>



                            @if (in_array('ยกเลิกโดยทางร้าน', $list_status) || in_array('ยกเลิกโดยลูกค้า', $list_status))
                                <div class="status-step text-center">
                                    <div class="status-icon @if (in_array('ถูกจอง', $list_status)) active @endif"
                                        style="background: rgb(166, 32, 32) ; ">
                                        {{-- <i class="fas fa-check"></i> --}}
                                    </div>
                                    <p class="text-danger">ยกเลิกการจอง</p>
                                    <small>
                                        <p>
                                            @php
                                                $created_at = App\Models\Orderdetailstatus::where(
                                                    'order_detail_id',
                                                    $orderdetail->id,
                                                )
                                                    ->whereIn('status', ['ยกเลิกโดยทางร้าน', 'ยกเลิกโดยลูกค้า'])
                                                    ->first();
                                                if ($created_at) {
                                                    $text_date = Carbon\Carbon::parse($created_at->created_at)
                                                        ->addHours(7)
                                                        ->format('d/m/Y H:i');
                                                } else {
                                                    $text_date = 'รอดำเนินการ';
                                                }
                                            @endphp
                                        <p class="text-danger">{{ $text_date }}</p>
                                        </p>
                                    </small>
                                </div>
                                <div class="status-line "></div>
                            @endif













                            <div class="status-step text-center">
                                <div class="status-icon @if (in_array('กำลังเช่า', $list_status)) active @endif">
                                    {{-- <i class="fas fa-check"></i> --}}
                                </div>
                                <p>กำลังเช่า</p>
                                <small>
                                    <p>
                                        @php
                                            $created_at = App\Models\Orderdetailstatus::where(
                                                'order_detail_id',
                                                $orderdetail->id,
                                            )
                                                ->where('status', 'กำลังเช่า')
                                                ->first();
                                            if ($created_at) {
                                                $text_date = Carbon\Carbon::parse($created_at->created_at)
                                                    ->addHours(7)
                                                    ->format('d/m/Y H:i');
                                            } else {
                                                $text_date = 'รอดำเนินการ';
                                            }
                                        @endphp
                                        {{ $text_date }}
                                    </p>

                                    {{-- <a href="{{ route('receiptpickup', ['id' => $orderdetail->id]) }}" target="_blank"
                                        class="btn btn-sm btn-primary"@if ($receipt_bill_pickup) style="display: block ; "
                                    @else
                                    style="display: none ; " @endif>ใบเสร็จรับชุด</a> --}}
                                </small>
                            </div>


                            <div class="status-line "></div>



                            <div class="status-step text-center">
                                <div class="status-icon @if (in_array('คืนชุดแล้ว', $list_status)) active @endif">
                                    {{-- <i class="fas fa-check"></i> --}}
                                </div>
                                <p>คืนชุดแล้ว</p>
                                <small>
                                    <p>
                                        @php
                                            $created_at = App\Models\Orderdetailstatus::where(
                                                'order_detail_id',
                                                $orderdetail->id,
                                            )
                                                ->where('status', 'คืนชุดแล้ว')
                                                ->first();
                                            if ($created_at) {
                                                $text_date = Carbon\Carbon::parse($created_at->created_at)
                                                    ->addHours(7)
                                                    ->format('d/m/Y H:i');
                                            } else {
                                                $text_date = 'รอดำเนินการ';
                                            }
                                        @endphp
                                        {{ $text_date }}
                                    </p>
                                    {{-- <a href="{{ route('receiptreturn', ['id' => $orderdetail->id]) }}" target="_blank"
                                        class="btn btn-sm btn-primary"@if ($receipt_bill_return) style="display: block ; "
                                    @else
                                    style="display: none ; " @endif>ใบเสร็จคืนชุด</a> --}}

                                </small>
                            </div>

                            {{-- <div class="status-line "></div>

                            <div class="status-step text-center">
                                <i class="fas fa-file-alt" style="font-size: 2rem; color: #4caf50;"></i> 
                                <p>สรุปผลการเช่า</p>
                                <small>
                                    <p>รายละเอียดเพิ่มเติม</p>
                                </small>
                            </div> --}}











                        </div>
                    </div>
                </div>
            </div>
        </div>




        
        <div class="row mt-3 d-flex align-items-stretch" id="div_show_net">
            <div class="col-md-12"
                @if ($orderdetail->status_detail == 'คืนชุดแล้ว') style="display: block;" 
                @else 
                    style="display: none;" @endif>
                <div class="card shadow-sm">
                    <!-- หัวข้อการ์ด -->
                    <div class="card-header bg-light border-bottom d-flex align-items-center">
                        <div class="border-4 border-primary rounded me-2" style="width: 4px; height: 20px;"></div>
                        <h5 class="card-title mb-0">
                            <i class="bi bi-file-earmark-text"></i> สรุปข้อมูลการเช่าชุด
                        </h5>
                    </div>

                    <!-- เนื้อหาการ์ด -->
                    <div class="card-body p-4">
                        <div class="row">
                            <!-- ข้อมูลระยะเวลา -->
                            <div class="col-md-6 mb-4">
                                <div class="d-flex align-items-center text-secondary mb-3">
                                    <i class="bi bi-calendar3 me-2"></i>
                                    <span class="fw-medium">ข้อมูลระยะเวลา</span>
                                </div>
                                <div class="ms-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-secondary">วันที่รับชุดจริง</span>
                                        <span
                                            class="fw-medium">{{ \Carbon\Carbon::parse($Date->actua_pickup_date)->locale('th')->isoFormat('D MMM') }}
                                            {{ \Carbon\Carbon::parse($Date->actua_pickup_date)->year + 543 }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-secondary">วันที่คืนชุดจริง</span>
                                        <span
                                            class="fw-medium">{{ \Carbon\Carbon::parse($Date->actua_return_date)->locale('th')->isoFormat('D MMM') }}
                                            {{ \Carbon\Carbon::parse($Date->actua_return_date)->year + 543 }}</span>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-secondary"><strong>จำนวนวันที่เช่าทั้งหมด</strong></span>
                                        <strong><span class="fw-medium" id="total_day_reall"></span></strong>
                                        <script>
                                            var total_day_real = document.getElementById('total_day_reall');
                                            var day_actua_pickup_date = new Date('{{ $Date->actua_pickup_date }}');
                                            day_actua_pickup_date.setHours(0, 0, 0, 0);

                                            var day_actua_return_date = new Date('{{ $Date->actua_return_date }}');
                                            day_actua_return_date.setHours(0, 0, 0, 0);

                                            var total_actua_pickup_date_return_date = Math.ceil((day_actua_return_date - day_actua_pickup_date) / (1000 * 60 *
                                                60 * 24));
                                            total_day_real.innerHTML = ' ' + total_actua_pickup_date_return_date + ' วัน';
                                        </script>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top"></div>
                                </div>

                                <div class="d-flex align-items-center text-secondary mb-3">
                                    <i class="bi bi-calendar3 me-2"></i>
                                    <span class="fw-medium">สภาพชุดหลังคืน</span>
                                </div>



                                <div class="ms-4">
                                    @if ($orderdetail->status_detail == 'คืนชุดแล้ว')
                                        @if ($orderdetail->shirtitems_id)
                                            @foreach ($reservationfilterdress as $item)
                                                @if ($item->filterdress_one_to_one_afterreturndress->type == 1)
                                                    <p>{{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}{{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}(เสื้อ)
                                                        : สภาพปกติ
                                                        @if ($item->filterdress_one_to_one_afterreturndress->price != 0)
                                                            <span
                                                                style="color: red ; font-size: 12px; ">(หักเงินประกันลูกค้า
                                                                {{ number_format($item->filterdress_one_to_one_afterreturndress->price, 2) }}
                                                                บาท)</span>
                                                        @endif
                                                    </p>
                                                @elseif($item->filterdress_one_to_one_afterreturndress->type == 2)
                                                    <p>{{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}{{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}(เสื้อ)
                                                        : ต้องซ่อม
                                                        เนื่องจาก{{ $item->filterdress_one_to_many_repair->first()->repair_description }}
                                                        @if ($item->filterdress_one_to_one_afterreturndress->price != 0)
                                                            <span
                                                                style="color: red ; font-size: 12px; ">(หักเงินประกันลูกค้า
                                                                {{ number_format($item->filterdress_one_to_one_afterreturndress->price, 2) }}
                                                                บาท)</span>
                                                        @endif
                                                    </p>
                                                @elseif($item->filterdress_one_to_one_afterreturndress->type == 3)
                                                    <p>{{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}{{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}(เสื้อ)
                                                        : ลูกค้าแจ้งสูญหาย
                                                        @if ($item->filterdress_one_to_one_afterreturndress->price != 0)
                                                            <span
                                                                style="color: red ; font-size: 12px; ">(หักเงินประกันลูกค้า
                                                                {{ number_format($item->filterdress_one_to_one_afterreturndress->price, 2) }}
                                                                บาท)</span>
                                                        @endif
                                                    </p>
                                                @elseif($item->filterdress_one_to_one_afterreturndress->type == 4)
                                                    <p>{{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}{{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}(เสื้อ)
                                                        : สูญหาย ลูกค้าไม่ส่งคืน
                                                        @if ($item->filterdress_one_to_one_afterreturndress->price != 0)
                                                            <span
                                                                style="color: red ; font-size: 12px; ">(หักเงินประกันลูกค้า
                                                                {{ number_format($item->filterdress_one_to_one_afterreturndress->price, 2) }}
                                                                บาท)</span>
                                                        @endif
                                                    </p>
                                                @elseif($item->filterdress_one_to_one_afterreturndress->type == 5)
                                                    <p>{{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}{{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}(เสื้อ)
                                                        : สภาพเสียหายหนัก ให้เช่าต่อไม่ได้
                                                        @if ($item->filterdress_one_to_one_afterreturndress->price != 0)
                                                            <span
                                                                style="color: red ; font-size: 12px; ">(หักเงินประกันลูกค้า
                                                                {{ number_format($item->filterdress_one_to_one_afterreturndress->price, 2) }}
                                                                บาท)</span>
                                                        @endif
                                                    </p>
                                                @endif
                                            @endforeach
                                        @elseif($orderdetail->skirtitems_id)
                                            @foreach ($reservationfilterdress as $item)
                                                @if ($item->filterdress_one_to_one_afterreturndress->type == 1)
                                                    <p>{{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}{{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}(ผ้าถุง)
                                                        : สภาพปกติ
                                                        @if ($item->filterdress_one_to_one_afterreturndress->price != 0)
                                                            <span
                                                                style="color: red ; font-size: 12px; ">(หักเงินประกันลูกค้า
                                                                {{ number_format($item->filterdress_one_to_one_afterreturndress->price, 2) }}
                                                                บาท)</span>
                                                        @endif
                                                    </p>
                                                @elseif($item->filterdress_one_to_one_afterreturndress->type == 2)
                                                    <p>{{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}{{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}(ผ้าถุง)
                                                        : ต้องซ่อม
                                                        เนื่องจาก{{ $item->filterdress_one_to_many_repair->first()->repair_description }}
                                                        @if ($item->filterdress_one_to_one_afterreturndress->price != 0)
                                                            <span
                                                                style="color: red ; font-size: 12px; ">(หักเงินประกันลูกค้า
                                                                {{ number_format($item->filterdress_one_to_one_afterreturndress->price, 2) }}
                                                                บาท)</span>
                                                        @endif
                                                    </p>
                                                @elseif($item->filterdress_one_to_one_afterreturndress->type == 3)
                                                    <p>{{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}{{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}(ผ้าถุง)
                                                        : ลูกค้าแจ้งสูญหาย
                                                        @if ($item->filterdress_one_to_one_afterreturndress->price != 0)
                                                            <span
                                                                style="color: red ; font-size: 12px; ">(หักเงินประกันลูกค้า
                                                                {{ number_format($item->filterdress_one_to_one_afterreturndress->price, 2) }}
                                                                บาท)</span>
                                                        @endif
                                                    </p>
                                                @elseif($item->filterdress_one_to_one_afterreturndress->type == 4)
                                                    <p>{{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}{{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}(ผ้าถุง)
                                                        : สูญหาย ลูกค้าไม่ส่งคืน
                                                        @if ($item->filterdress_one_to_one_afterreturndress->price != 0)
                                                            <span
                                                                style="color: red ; font-size: 12px; ">(หักเงินประกันลูกค้า
                                                                {{ number_format($item->filterdress_one_to_one_afterreturndress->price, 2) }}
                                                                บาท)</span>
                                                        @endif
                                                    </p>
                                                @elseif($item->filterdress_one_to_one_afterreturndress->type == 5)
                                                    <p>{{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}{{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}(ผ้าถุง)
                                                        : สภาพเสียหายหนัก ให้เช่าต่อไม่ได้
                                                        @if ($item->filterdress_one_to_one_afterreturndress->price != 0)
                                                            <span
                                                                style="color: red ; font-size: 12px; ">(หักเงินประกันลูกค้า
                                                                {{ number_format($item->filterdress_one_to_one_afterreturndress->price, 2) }}
                                                                บาท)</span>
                                                        @endif
                                                    </p>
                                                @endif
                                            @endforeach
                                        @else
                                            @if ($datadress->separable == 1)
                                                @foreach ($reservationfilterdress as $item)
                                                    @if ($item->filterdress_one_to_one_afterreturndress->type == 1)
                                                        <p>{{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}{{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}(ทั้งชุด)
                                                            : สภาพปกติ
                                                            @if ($item->filterdress_one_to_one_afterreturndress->price != 0)
                                                                <span
                                                                    style="color: red ; font-size: 12px; ">(หักเงินประกันลูกค้า
                                                                    {{ number_format($item->filterdress_one_to_one_afterreturndress->price, 2) }}
                                                                    บาท)</span>
                                                            @endif
                                                        </p>
                                                    @elseif($item->filterdress_one_to_one_afterreturndress->type == 2)
                                                        <p>{{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}{{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}(ทั้งชุด)
                                                            : ต้องซ่อม
                                                            เนื่องจาก{{ $item->filterdress_one_to_many_repair->first()->repair_description }}
                                                            @if ($item->filterdress_one_to_one_afterreturndress->price != 0)
                                                                <span
                                                                    style="color: red ; font-size: 12px; ">(หักเงินประกันลูกค้า
                                                                    {{ number_format($item->filterdress_one_to_one_afterreturndress->price, 2) }}
                                                                    บาท)</span>
                                                            @endif
                                                        </p>
                                                    @elseif($item->filterdress_one_to_one_afterreturndress->type == 3)
                                                        <p>{{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}{{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}(ทั้งชุด)
                                                            : ลูกค้าแจ้งสูญหาย
                                                            @if ($item->filterdress_one_to_one_afterreturndress->price != 0)
                                                                <span
                                                                    style="color: red ; font-size: 12px; ">(หักเงินประกันลูกค้า
                                                                    {{ number_format($item->filterdress_one_to_one_afterreturndress->price, 2) }}
                                                                    บาท)</span>
                                                            @endif
                                                        </p>
                                                    @elseif($item->filterdress_one_to_one_afterreturndress->type == 4)
                                                        <p>{{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}{{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}(ทั้งชุด)
                                                            : สูญหาย ลูกค้าไม่ส่งคืน
                                                            @if ($item->filterdress_one_to_one_afterreturndress->price != 0)
                                                                <span
                                                                    style="color: red ; font-size: 12px; ">(หักเงินประกันลูกค้า
                                                                    {{ number_format($item->filterdress_one_to_one_afterreturndress->price, 2) }}
                                                                    บาท)</span>
                                                            @endif
                                                        </p>
                                                    @elseif($item->filterdress_one_to_one_afterreturndress->type == 5)
                                                        <p>{{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}{{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}(ทั้งชุด)
                                                            : สภาพเสียหายหนัก ให้เช่าต่อไม่ได้
                                                            @if ($item->filterdress_one_to_one_afterreturndress->price != 0)
                                                                <span
                                                                    style="color: red ; font-size: 12px; ">(หักเงินประกันลูกค้า
                                                                    {{ number_format($item->filterdress_one_to_one_afterreturndress->price, 2) }}
                                                                    บาท)</span>
                                                            @endif
                                                        </p>
                                                    @endif
                                                @endforeach
                                            @elseif ($datadress->separable == 2)
                                                @foreach ($reservationfilterdress as $item)
                                                    @if ($item->filterdress_one_to_one_afterreturndress->type == 1)
                                                        @if ($item->shirtitems_id)
                                                            <p>{{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}{{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}(เสื้อ)
                                                                : สภาพปกติ
                                                                @if ($item->filterdress_one_to_one_afterreturndress->price != 0)
                                                                    <span
                                                                        style="color: red ; font-size: 12px; ">(หักเงินประกันลูกค้า
                                                                        {{ number_format($item->filterdress_one_to_one_afterreturndress->price, 2) }}
                                                                        บาท)</span>
                                                                @endif
                                                            </p>
                                                        @elseif($item->skirtitems_id)
                                                            <p>{{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}{{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}(ผ้าถุง)
                                                                : สภาพปกติ
                                                                @if ($item->filterdress_one_to_one_afterreturndress->price != 0)
                                                                    <span
                                                                        style="color: red ; font-size: 12px; ">(หักเงินประกันลูกค้า
                                                                        {{ number_format($item->filterdress_one_to_one_afterreturndress->price, 2) }}
                                                                        บาท)</span>
                                                                @endif
                                                            </p>
                                                        @endif
                                                    @elseif($item->filterdress_one_to_one_afterreturndress->type == 2)
                                                        @if ($item->shirtitems_id)
                                                            <p>{{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}{{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}(เสื้อ)
                                                                : ต้องซ่อม
                                                                เนื่องจาก{{ $item->filterdress_one_to_many_repair->first()->repair_description }}
                                                                @if ($item->filterdress_one_to_one_afterreturndress->price != 0)
                                                                    <span
                                                                        style="color: red ; font-size: 12px; ">(หักเงินประกันลูกค้า
                                                                        {{ number_format($item->filterdress_one_to_one_afterreturndress->price, 2) }}
                                                                        บาท)</span>
                                                                @endif
                                                            </p>
                                                        @elseif ($item->skirtitems_id)
                                                            <p>{{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}{{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}(ผ้าถุง)
                                                                : ต้องซ่อม
                                                                เนื่องจาก{{ $item->filterdress_one_to_many_repair->first()->repair_description }}
                                                                @if ($item->filterdress_one_to_one_afterreturndress->price != 0)
                                                                    <span
                                                                        style="color: red ; font-size: 12px; ">(หักเงินประกันลูกค้า
                                                                        {{ number_format($item->filterdress_one_to_one_afterreturndress->price, 2) }}
                                                                        บาท)</span>
                                                                @endif
                                                            </p>
                                                        @endif
                                                    @elseif($item->filterdress_one_to_one_afterreturndress->type == 3)
                                                        @if ($item->shirtitems_id)
                                                            <p>{{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}{{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}(เสื้อ)
                                                                : ลูกค้าแจ้งสูญหาย
                                                                @if ($item->filterdress_one_to_one_afterreturndress->price != 0)
                                                                    <span
                                                                        style="color: red ; font-size: 12px; ">(หักเงินประกันลูกค้า
                                                                        {{ number_format($item->filterdress_one_to_one_afterreturndress->price, 2) }}
                                                                        บาท)</span>
                                                                @endif
                                                            </p>
                                                        @elseif ($item->skirtitems_id)
                                                            <p>{{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}{{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}(ผ้าถุง)
                                                                : ลูกค้าแจ้งสูญหาย
                                                                @if ($item->filterdress_one_to_one_afterreturndress->price != 0)
                                                                    <span
                                                                        style="color: red ; font-size: 12px; ">(หักเงินประกันลูกค้า
                                                                        {{ number_format($item->filterdress_one_to_one_afterreturndress->price, 2) }}
                                                                        บาท)</span>
                                                                @endif
                                                            </p>
                                                        @endif
                                                    @elseif($item->filterdress_one_to_one_afterreturndress->type == 4)
                                                        @if ($item->shirtitems_id)
                                                            <p>{{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}{{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}(เสื้อ)
                                                                : สูญหาย ลูกค้าไม่ส่งคืน
                                                                @if ($item->filterdress_one_to_one_afterreturndress->price != 0)
                                                                    <span
                                                                        style="color: red ; font-size: 12px; ">(หักเงินประกันลูกค้า
                                                                        {{ number_format($item->filterdress_one_to_one_afterreturndress->price, 2) }}
                                                                        บาท)</span>
                                                                @endif
                                                            </p>
                                                        @elseif ($item->skirtitems_id)
                                                            <p>{{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}{{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}(ผ้าถุง)
                                                                : สูญหาย ลูกค้าไม่ส่งคืน
                                                                @if ($item->filterdress_one_to_one_afterreturndress->price != 0)
                                                                    <span
                                                                        style="color: red ; font-size: 12px; ">(หักเงินประกันลูกค้า
                                                                        {{ number_format($item->filterdress_one_to_one_afterreturndress->price, 2) }}
                                                                        บาท)</span>
                                                                @endif
                                                            </p>
                                                        @endif
                                                    @elseif($item->filterdress_one_to_one_afterreturndress->type == 5)
                                                        @if ($item->shirtitems_id)
                                                            <p>{{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}{{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}(เสื้อ)
                                                                : สภาพเสียหายหนัก ให้เช่าต่อไม่ได้
                                                                @if ($item->filterdress_one_to_one_afterreturndress->price != 0)
                                                                    <span
                                                                        style="color: red ; font-size: 12px; ">(หักเงินประกันลูกค้า
                                                                        {{ number_format($item->filterdress_one_to_one_afterreturndress->price, 2) }}
                                                                        บาท)</span>
                                                                @endif
                                                            </p>
                                                        @elseif ($item->skirtitems_id)
                                                            <p>{{ $item->filterdress_many_to_one_dress->typedress->type_dress_name }}{{ $item->filterdress_many_to_one_dress->typedress->specific_letter }}{{ $item->filterdress_many_to_one_dress->dress_code }}(ผ้าถุง)
                                                                : สภาพเสียหายหนัก ให้เช่าต่อไม่ได้
                                                                @if ($item->filterdress_one_to_one_afterreturndress->price != 0)
                                                                    <span
                                                                        style="color: red ; font-size: 12px; ">(หักเงินประกันลูกค้า
                                                                        {{ number_format($item->filterdress_one_to_one_afterreturndress->price, 2) }}
                                                                        บาท)</span>
                                                                @endif
                                                            </p>
                                                        @endif
                                                    @endif
                                                @endforeach





                                            @endif
                                        @endif
                                    @endif
                                </div>




                            </div>

                            <!-- ข้อมูลการเงิน -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-center text-secondary mb-3">
                                    <i class="bi bi-coin me-2"></i>
                                    <span class="fw-medium">ข้อมูลการเงิน</span>
                                </div>
                                <div class="ms-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-secondary">รายได้ค่าเช่าชุด</span>
                                        <span
                                            class="fw-medium text-secondary">{{ number_format($orderdetail->price, 2) }}
                                            บาท</span>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-secondary">รายได้จากการหักเงินประกัน</span>

                                        @if ($additional->count() > 0)
                                            @foreach ($additional as $item)
                                                @if ($item->charge_type == 1)
                                                    <span class="fw-medium">{{ number_format($item->amount, 2) }}
                                                        บาท</span>
                                                @else
                                                    <span class="fw-medium">0.00 บาท</span>
                                                @endif
                                            @endforeach
                                        @else
                                            <span class="fw-medium">0.00 บาท</span>
                                        @endif


                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-secondary">รายได้จากการคืนชุดล่าช้า</span>
                                        @if ($additional->count() > 0)
                                            @foreach ($additional as $item)
                                                @if ($item->charge_type == 2)
                                                    <span class="fw-medium">{{ number_format($item->amount, 2) }}
                                                        บาท</span>
                                                @else
                                                    <span class="fw-medium">0.00 บาท</span>
                                                @endif
                                            @endforeach
                                        @else
                                            <span class="fw-medium">0.00 บาท</span>
                                        @endif
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-secondary">รายได้จากค่าธรรมเนียมขยายระยะเวลาเช่า </span>
                                        @if ($additional->count() > 0)
                                            @foreach ($additional as $item)
                                                @if ($item->charge_type == 3)
                                                    <span class="fw-medium">{{ number_format($item->amount, 2) }}
                                                        บาท</span>
                                                @else
                                                    <span class="fw-medium">0.00 บาท</span>
                                                @endif
                                            @endforeach
                                        @else
                                            <span class="fw-medium">0.00 บาท</span>
                                        @endif
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <span class="text-secondary fw-medium"><strong>รายได้รวมทั้งหมด</strong></span>
                                        @if ($additional->count() <= 0)
                                            <span
                                                class="fw-medium fs-5 text-primary">{{ number_format($orderdetail->price) }}
                                                บาท</span>
                                        @elseif($additional->count() > 0)
                                            <span
                                                class="fw-medium fs-5 text-primary">{{ number_format($orderdetail->price + $sum_additional, 2) }}
                                                บาท</span>
                                        @endif




                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




        <div class="row mt-3 d-flex align-items-stretch">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-4">
                                <img src="{{ asset($dressimage->dress_image) }}" alt=""
                                    width="154px;" height="auto">
                            </div>
                            <div class="col-md-8">
                                <h5>ข้อมูลชุด</h5>
                                <p>ประเภทชุด : {{ $typename }}</p>
                                <p>หมายเลขชุด : {{ $dress->dress_code_new }}{{ $dress->dress_code }}</p>
                                {{-- <p>รายละเอียด : {{ $dress->dress_description }}</p> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title">ข้อมูลการเช่า</h5>

                        <p><span class="bi bi-person"></span> ชื่อผู้เช่า : คุณ{{ $customer->customer_fname }}
                            {{ $customer->customer_lname }}</p>




                        <p><i class="bi bi-calendar"></i> วันที่นัดรับ - นัดคืน :
                            {{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($Date->pickup_date)->year + 543 }}
                            -
                            {{ \Carbon\Carbon::parse($Date->return_date)->locale('th')->isoFormat('D MMM') }}
                            {{ \Carbon\Carbon::parse($Date->return_date)->year + 543 }}

                        </p>



                        <p><i></i> ราคาเช่า : {{ number_format($orderdetail->price, 2) }} บาท
                        </p>
                        <p><i></i> เงินมัดจำ : {{ number_format($orderdetail->deposit, 2) }}
                            บาท</p>
                        <p><i class="bi bi-shield-check"></i> ประกันค่าเสียหาย :
                            {{ number_format($orderdetail->damage_insurance, 2) }} บาท</p>
                        <p><i class="bi bi-check-circle"></i> สถานะ : @if ($orderdetail->status_payment == 1)
                                ชำระเงินมัดจำแล้ว
                            @elseif($orderdetail->status_payment == 2)
                                ชำระเงินครบแล้ว
                            @endif
                        </p>
                        @php
                            $user_id = App\Models\Order::where('id', $orderdetail->order_id)->value('user_id');
                            $user = App\Models\User::find($user_id);
                        @endphp
                        <p><span class="bi bi-person"></span> พนักงานผู้รับออเดอร์ : คุณ{{ $user->name }}
                            {{ $user->lname }}</p>
                    </div>
                </div>
            </div>
        </div>



        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="card-title">รายการปรับแก้ไขชุด (หน่วยเป็นนิ้ว)</h5>
                            </div>
                        </div>

                        <table class="table mt-3">
                            <thead>
                                <tr>
                                    <th scope="col">รายการ</th>
                                    <th scope="col">
                                        @if ($check_button_updatestatusadjust == true)
                                            ขนาดเดิม
                                        @elseif($check_button_updatestatusadjust == false)
                                            ขนาด
                                        @endif
                                    </th>
                                    <th scope="col">
                                        @if ($check_button_updatestatusadjust == true)
                                            ขนาดที่ปรับแก้
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dress_mea_adjust as $item)
                                    <tr>
                                        @php
                                            $dress_mea = App\Models\Dressmea::where('id', $item->dressmea_id)->first();
                                        @endphp
                                        <td>{{ $dress_mea->mea_dress_name }}</td>
                                        <td>{{ $dress_mea->current_mea }}</td>

                                        @if ($check_button_updatestatusadjust == true)
                                            <td>
                                                @if ($dress_mea->current_mea != $item->new_size)
                                                    <span style="color: red;">ปรับแก้:จาก{{ $dress_mea->current_mea }}<i
                                                            class="bi bi-arrow-right"></i>{{ $item->new_size }}นิ้ว</span>
                                                @else
                                                    ไม่ต้องปรับแก้ขนาด
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="col-md-12"
                            @if ($check_reser_status_for_his != 1) @if ($check_button_updatestatusadjust == true && $check_open_button == true) 
                                style="display: block; text-align: right ;" 
                            @else
                                style="display: none ; text-align: right ; " @endif
                        @else style="display: none ; text-align: right ; " @endif
                            >
                            <button class="btn " data-toggle='modal' data-target="#updatestatusadjust"
                                style="background-color:#ACE6B7;" type="button">ปรับแก้ไขขนาดสำเร็จ</button>
                        </div>

                        @if ($his_dress_adjust->count() > 0)
                            <p><strong>ประวัติการปรับแก้ขนาด</strong></p>
                            <p style="margin-left: 10px; font-size: 14px;">พนักงานที่ทำการปรับแก้ : คุณผกาสินี ชัยเลิศ</p>
                            @foreach ($his_dress_adjust as $item)
                                <li>{{ $item->name }} ปรับจาก {{ $item->old_size }} เป็น {{ $item->edit_new_size }}
                                </li>
                            @endforeach
                        @endif
                    </div>

                </div>

            </div>




        </div>




















    </div>

















    </div>


    {{-- modalปรับแก้ไขชุดสำเร็จ   --}}
    <div class="modal fade" id="updatestatusadjust" tabindex="-1" role="dialog"
        aria-labelledby="updatestatusadjustLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('employee.actionupdatestatusadjustdress', ['id' => $orderdetail->id]) }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="dress_id" value="{{ $orderdetail->dress_id }}">
                    <input type="hidden" name="shirtitems_id" value="{{ $orderdetail->shirtitems_id }}">
                    <input type="hidden" name="skirtitems_id" value="{{ $orderdetail->skirtitems_id }}">
                    <input type="hidden" name="order_detail_id" value="{{ $orderdetail->id }}">
                    @foreach ($dress_mea_adjust_modal as $index => $dress_mea_adjust_modal)
                        <input type="hidden" name="dress_adjustment_[]" value="{{ $dress_mea_adjust_modal->id }}">
                        <input type="hidden" name="dressmea_id_[]" value="{{ $dress_mea_adjust_modal->dressmea_id }}">
                        <input type="hidden" name="new_size_[]" value="{{ $dress_mea_adjust_modal->new_size }}">
                    @endforeach

                    <div class="modal-header"style="background-color:#EAD8C0 ;">
                        <h5 class="modal-title" id="adjustmentModalLabel">การปรับแก้ไขขนาดชุด</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <p>รายละเอียดการปรับแก้:</p>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">รายการ</th>
                                    <th scope="col">ขนาดเดิม</th>
                                    <th scope="col">ขนาดที่ปรับแก้</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dress_mea_adjust_modal_show as $item)
                                    @php
                                        $dress_mea = App\Models\Dressmea::where('id', $item->dressmea_id)->first();
                                    @endphp
                                    @if ($dress_mea->current_mea != $item->new_size)
                                        <tr>
                                            <td>{{ $dress_mea->mea_dress_name }}</td>
                                            <td>{{ $dress_mea->current_mea }}</td>
                                            <td>{{ $item->new_size }}</td>
                                        </tr>
                                    @endif
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" type="button" class="btn " style="background-color:#DADAE3;"
                            data-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn "style="background-color:#ACE6B7;">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal สำหรับยืนยันการอัปเดตสถานะพร้อมรายละเอียดเพิ่มเติม -->
    <div class="modal fade" id="updatestatus" tabindex="-1" aria-labelledby="updatestatusLabel" aria-hidden="true"
        data-backdrop="static">
        <div class="modal-dialog" style="max-width: 40% ; ">
            <div class="modal-content">


                <form action="{{ route('employee.actionupdatestatusrentdress', ['id' => $orderdetail->id]) }}"
                    method="POST">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title w-100 text-center" id="updatestatusLabel">อัปเดตสถานะการเช่า</h5>
                    </div>

                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>ชื่อลูกค้า:</strong> คุณ{{ $customer->customer_fname }}
                                {{ $customer->customer_lname }}
                            </div>

                        </div>

                        <div class="row mb-3">

                            <div class="col-6">
                                <strong>วันที่นัดรับชุด:</strong>
                                {{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                {{ \Carbon\Carbon::parse($Date->pickup_date)->year + 543 }}
                            </div>
                        </div>


                        <h6 class="fw-bold mb-3">รายละเอียดการชำระเงิน</h6>
                        @if ($orderdetail->status_payment == 1)

                            <div class="p-3 bg-light rounded">
                                <div class="d-flex justify-content-between">
                                    <span>ค่าเช่าชุด:</span>
                                    <span>
                                        {{ number_format($orderdetail->price, 2) }} บาท
                                    </span>
                                </div>
                            </div>


                            <div class="p-3 bg-light rounded">
                                <div class="d-flex justify-content-between">
                                    <span>เงินประกัน:</span>
                                    <span>
                                        {{ number_format($orderdetail->damage_insurance, 2) }} บาท
                                    </span>
                                </div>
                            </div>

                            <div class="p-3 bg-light rounded mb-3">
                                <div class="d-flex justify-content-between">
                                    <span>เงินมัดจำ: <span style="font-size: 14px; color: rgb(133, 126, 126) ;">(ชำระเมื่อ
                                            {{ \Carbon\Carbon::parse($orderdetail->created_at)->locale('th')->isoFormat('D MMM') }}
                                            {{ \Carbon\Carbon::parse($orderdetail->created_at)->year + 543 }}
                                            )</span></span>
                                    <span>
                                        {{ number_format($orderdetail->deposit, 2) }} บาท
                                    </span>
                                </div>
                            </div>

                            <div class="p-4 bg-opacity-10 rounded mt-4" style="background-color: #F0FFFF	 ; ">
                                <div class="d-flex justify-content-between fw-bold text-info">
                                    <span style="color:#0000CD ; ">ยอดคงเหลือที่ต้องชำระ:</span>
                                    <span class="fs-5"
                                        style="color:#0000CD ; ">{{ number_format($orderdetail->price - $orderdetail->deposit + $orderdetail->damage_insurance, 2) }}
                                        บาท</span>
                                </div>
                                <small class="text-muted" style="color:#0000CD ; ">
                                    (หักเงินมัดจำ {{ number_format($orderdetail->price - $orderdetail->deposit, 2) }}
                                    บาท
                                    + เงินประกัน
                                    {{ number_format($orderdetail->damage_insurance, 2) }} บาท)
                                </small>
                            </div>
                        @elseif($orderdetail->status_payment == 2)
                            <div class="p-3 bg-light rounded">
                                <div class="d-flex justify-content-between">
                                    <span>ค่าเช่าชุด: <span style="font-size: 14px; color: rgb(133, 126, 126) ;">(ชำระเมื่อ
                                            {{ \Carbon\Carbon::parse($orderdetail->created_at)->locale('th')->isoFormat('D MMM') }}
                                            {{ \Carbon\Carbon::parse($orderdetail->created_at)->year + 543 }}
                                            )</span></span>
                                    <span>
                                        {{ number_format($orderdetail->price, 2) }} บาท
                                    </span>
                                </div>
                            </div>


                            <div class="p-3 bg-light rounded">
                                <div class="d-flex justify-content-between">
                                    <span>เงินประกัน: <span style="font-size: 14px; color: rgb(133, 126, 126) ;">(ชำระเมื่อ
                                            {{ \Carbon\Carbon::parse($orderdetail->created_at)->locale('th')->isoFormat('D MMM') }}
                                            {{ \Carbon\Carbon::parse($orderdetail->created_at)->year + 543 }}
                                            )</span></span>
                                    <span>
                                        {{ number_format($orderdetail->damage_insurance, 2) }} บาท
                                    </span>
                                </div>
                            </div>


                            <div class="p-4 bg-opacity-10 rounded mt-4" style="background-color: #F0FFFF	 ; ">
                                <div class="d-flex justify-content-between fw-bold text-info">
                                    <span style="color:#0000CD ; ">ยอดคงเหลือที่ต้องชำระ:</span>
                                    <span class="fs-5" style="color:#0000CD ; ">0.00
                                        บาท</span>
                                </div>
                                <small class="text-muted" style="color:#0000CD ; ">
                                    ชำระเงินครบเรียบร้อยแล้ว
                                    <i class="text-success bi bi-check-circle ms-2"></i>
                                </small>
                            </div>
                        @endif
                    </div>





                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-success">ยืนยันการอัปเดตสถานะ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <div class="modal fade" id="updatestatus_return" tabindex="-1" role="dialog"
        aria-labelledby="updatestatus_returnLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('employee.actionupdatestatusrentdress', ['id' => $orderdetail->id]) }}"
                method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header" style="background-color:#EAD8C0 ;">
                        <h5 class="modal-title" id="returnModalLabel">
                            ยืนยันการคืนชุด</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            style="color: white;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- แสดงรายละเอียดการเช่าและการคืน -->
                        <strong class="mb-3">รายละเอียดการเช่าและการคืน:</strong>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 50%; text-align: left; padding: 10px;">ชื่อลูกค้า:</th>
                                    <td style="padding: 10px;">คุณ{{ $customer->customer_fname }}
                                        {{ $customer->customer_lname }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%; text-align: left; padding: 10px;">วันที่รับชุด:</th>
                                    <td style="padding: 10px;">
                                        {{ \Carbon\Carbon::parse($Date->pickup_date)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($Date->pickup_date)->year + 543 }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 50%; text-align: left; padding: 10px;">วันที่มารับชุดจริง:</th>
                                    <td style="padding: 10px;">
                                        {{ \Carbon\Carbon::parse($Date->actua_pickup_date)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($Date->actua_pickup_date)->year + 543 }}</td>
                                </tr>

                                <tr>
                                    <th style="width: 50%; text-align: left; padding: 10px;">วันที่นัดคืน:</th>
                                    <td style="padding: 10px;">
                                        {{ \Carbon\Carbon::parse($Date->return_date)->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::parse($Date->return_date)->year + 543 }}</td>
                                </tr>

                                <tr>
                                    <th style="width: 50%; text-align: left; padding: 10px;">วันที่มาคืนจริง:</th>
                                    <td style="padding: 10px;">
                                        {{ \Carbon\Carbon::now()->locale('th')->isoFormat('D MMM') }}
                                        {{ \Carbon\Carbon::now()->year + 543 }}</td>
                                </tr>

                                <tr>
                                    <th style="width: 50%; text-align: left; padding: 10px;">ค่าปรับส่งคืนชุดล่าช้า:</th>
                                    <td style="padding: 10px;"><span id="mulct"></span>
                                    </td>
                                </tr>
                                <input type="hidden" name="late_return_fee" id="late_return_fee">
                                <input type="hidden" name="late_chart" id="late_chart">
                                <script>
                                    var now_day = new Date();
                                    var return_date = new Date('{{ $Date->return_date }}');
                                    now_day.setHours(0, 0, 0, 0);
                                    return_date.setHours(0, 0, 0, 0);
                                    // console.log(return_date) ; 
                                    var t = now_day - return_date;
                                    var s = Math.ceil(t / (1000 * 60 * 60 * 24));
                                    var p = s * 200; //ถ้าคืนช้า คิดวันละ 200 บาท  
                                    document.getElementById('late_return_fee').value = p;
                                    if (s == 0) {
                                        document.getElementById('mulct').innerHTML = '0 บาท';
                                    } else if (s > 0) {
                                        document.getElementById('mulct').innerHTML = p + ' บาท ' + 'เนื่องจากคืนล่าช้า ' + s + ' วัน';
                                    } else {
                                        document.getElementById('mulct').innerHTML = '0 บาท'
                                    }
                                </script>
                                <tr>
                                    <th style="width: 50%; text-align: left; padding: 10px;">ค่าธรรมเนียมขยายระยะเวลาเช่า:
                                    </th>
                                    <td style="padding: 10px;"><span id="rental_exte"></span></td>
                                </tr>
                                <script>
                                    var rr = new Date('{{ $Date->return_date }}');
                                    var pp = new Date('{{ $Date->pickup_date }}');
                                    rr.setHours(0, 0, 0, 0);
                                    pp.setHours(0, 0, 0, 0);
                                    var rr_pp = rr - pp;
                                    var late_chart_day = Math.ceil(rr_pp / (1000 * 60 * 60 * 24));

                                    if (late_chart_day > 3) {
                                        console.log('ในสัญญาเกิน 3 วัน ');
                                        var n = new Date('{{ $Date->actua_pickup_date }}'); //วันที่รับจริง
                                        var nn = new Date(); //วันปัจจุบัน
                                        n.setHours(0, 0, 0, 0);
                                        nn.setHours(0, 0, 0, 0);

                                        var nn_n = Math.ceil((nn - n) / (1000 * 60 * 60 * 24));

                                        if (nn_n > 3) {
                                            document.getElementById('rental_exte').innerHTML = (nn_n - 3) * 100 + ' บาท' + '   (ขยายเวลาเช่า ' + (nn_n -
                                                3) + ' วัน)';
                                        } else if (nn_n <= 3) {
                                            document.getElementById('rental_exte').innerHTML = '0 บาท';
                                        }

                                    } else if (late_chart_day <= 3) {
                                        document.getElementById('rental_exte').innerHTML = '0 บาท';
                                    }
                                </script>


                            </tbody>
                        </table>

                        <div class="form-group">
                            <p>เก็บประกันจากลูกค้า : <span>{{ $orderdetail->damage_insurance }} บาท</span></p>
                            {{-- <strong for="damageFee">ค่าธรรมเนียมความเสียหาย (หักจากประกัน):</strong> --}}
                            {{-- <input type="number" class="form-control" name="total_damage_insurance"
                                id="total_damage_insurance" placeholder="กรอกจำนวนเงิน" min="0" step="0.01"
                                required value="0"> --}}
                        </div>



                        <!-- ฟิลด์สำหรับเลือกสถานะการดำเนินการหลังคืนชุด -->
                        @if ($orderdetail->shirtitems_id)
                            <div class="form-group">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="bg-gray-100">รายการ</th>
                                            <th class="bg-gray-100">การดำเนินการ</th>
                                            <th class="bg-gray-100">ค่าธรรมเนียมความเสียหาย (บาท)</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <td>
                                                {{ $typename }}
                                                {{ $dress->dress_code_new }}{{ $dress->dress_code }} (เสื้อ)
                                            </td>
                                            <td>
                                                <select name="actionreturnitemshirt" id="actionreturnitemshirt"
                                                    class="form-control">
                                                    <option value="cleanitem" selected>สภาพปกติ ส่งทำความสะอาด</option>
                                                    <option value="repairitem">ต้องซ่อม</option>
                                                    <option value="lost">สูญหาย (ลูกค้าแจ้ง)</option>
                                                    <option value="lost_unreported">*สูญหาย (ลูกค้าไม่ส่งคืน)</option>
                                                    <option value="damaged_beyond_repair">*เสียหายหนัก (ให้เช่าต่อไม่ได้)
                                                    </option>
                                                </select>

                                                <div id="showrepair_detail_itemshirt" class="mt-2"
                                                    style="display: none;">
                                                    <textarea name="repair_detail_for_itemshirt" class="form-control" placeholder="กรุณาระบุรายละเอียดการซ่อม..."
                                                        rows="3"></textarea>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" value="0" class="form-control" min="0"
                                                    name="damage_insurance_shirt" required>
                                            </td>
                                        </tr>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                var actionreturnitemshirt = document.getElementById('actionreturnitemshirt');
                                                var showrepair_detail_itemshirt = document.getElementById('showrepair_detail_itemshirt');
                                                actionreturnitemshirt.addEventListener('change', function() {
                                                    if (actionreturnitemshirt.value == "repairitem") {
                                                        showrepair_detail_itemshirt.style.display = 'block';
                                                    } else {
                                                        showrepair_detail_itemshirt.style.display = 'none';
                                                    }
                                                });
                                            });
                                        </script>
                                    </tbody>
                                </table>
                            </div>
                        @elseif($orderdetail->skirtitems_id)
                            <div class="form-group">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="bg-gray-100">รายการ</th>
                                            <th class="bg-gray-100">การดำเนินการ</th>
                                            <th class="bg-gray-100">ค่าธรรมเนียมความเสียหาย (บาท)</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <td>
                                                {{ $typename }}
                                                {{ $dress->dress_code_new }}{{ $dress->dress_code }} (ผ้าถุง)
                                            </td>
                                            <td>
                                                <select name="actionreturnitemskirt" id="actionreturnitemskirt"
                                                    class="form-control">
                                                    <option value="cleanitem" selected>สภาพปกติ ส่งทำความสะอาด</option>
                                                    <option value="repairitem">ต้องซ่อม</option>
                                                    <option value="lost">สูญหาย (ลูกค้าแจ้ง)</option>
                                                    <option value="lost_unreported">*สูญหาย (ลูกค้าไม่ส่งคืน)</option>
                                                    <option value="damaged_beyond_repair">*เสียหายหนัก (ให้เช่าต่อไม่ได้)
                                                    </option>
                                                </select>

                                                <div id="showrepair_detail_itemskirt" class="mt-2"
                                                    style="display: none;">
                                                    <textarea name="repair_detail_for_itemskirt" class="form-control" placeholder="กรุณาระบุรายละเอียดการซ่อม..."
                                                        rows="3"></textarea>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" value="0" class="form-control" min="0"
                                                    name="damage_insurance_skirt" required>
                                            </td>
                                        </tr>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                var actionreturnitemskirt = document.getElementById('actionreturnitemskirt');
                                                var showrepair_detail_itemskirt = document.getElementById('showrepair_detail_itemskirt');
                                                actionreturnitemskirt.addEventListener('change', function() {
                                                    if (actionreturnitemskirt.value == "repairitem") {
                                                        showrepair_detail_itemskirt.style.display = 'block';
                                                    } else {
                                                        showrepair_detail_itemskirt.style.display = 'none';
                                                    }
                                                });
                                            });
                                        </script>
                                    </tbody>
                                </table>
                            </div>
                        @else
                            @if ($datadress->separable == 1)
                                <div class="form-group">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="bg-gray-100">รายการ</th>
                                                <th class="bg-gray-100">การดำเนินการ</th>
                                                <th class="bg-gray-100">ค่าธรรมเนียมความเสียหาย (บาท)</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr>
                                                <td>
                                                    {{ $typename }}
                                                    {{ $dress->dress_code_new }}{{ $dress->dress_code }} (ทั้งชุด)
                                                </td>
                                                <td>
                                                    <select name="actionreturnitemtotaldress"
                                                        id="actionreturnitemtotaldress" class="form-control">
                                                        <option value="cleanitem" selected>ส่งทำความสะอาด</option>
                                                        <option value="repairitem">ต้องซ่อม</option>
                                                        <option value="lost">สูญหาย (ลูกค้าแจ้ง)</option>
                                                        <option value="lost_unreported">*สูญหาย (ลูกค้าไม่ส่งคืน)</option>
                                                        <option value="damaged_beyond_repair">*เสียหายหนัก
                                                            (ให้เช่าต่อไม่ได้)
                                                        </option>
                                                    </select>

                                                    <div id="showrepair_detail_itemtotaldress" class="mt-2"
                                                        style="display: none;">
                                                        <textarea name="repair_detail_for_itemtotaldress" class="form-control" placeholder="กรุณาระบุรายละเอียดการซ่อม..."
                                                            rows="3"></textarea>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="number" value="0" class="form-control"
                                                        min="0" name="damage_insurance_separable_one" required>
                                                </td>
                                            </tr>
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    var actionreturnitemtotaldress = document.getElementById('actionreturnitemtotaldress');
                                                    var showrepair_detail_itemtotaldress = document.getElementById('showrepair_detail_itemtotaldress');
                                                    actionreturnitemtotaldress.addEventListener('change', function() {
                                                        if (actionreturnitemtotaldress.value == "repairitem") {
                                                            showrepair_detail_itemtotaldress.style.display = 'block';
                                                        } else {
                                                            showrepair_detail_itemtotaldress.style.display = 'none';
                                                        }
                                                    });
                                                });
                                            </script>
                                        </tbody>
                                    </table>
                                </div>
                            @elseif($datadress->separable == 2)
                                <div class="form-group">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="bg-gray-100">รายการ</th>
                                                <th class="bg-gray-100">การดำเนินการ</th>
                                                <th class="bg-gray-100">ค่าธรรมเนียมความเสียหาย (บาท)</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr>
                                                <td>
                                                    {{ $typename }}
                                                    {{ $dress->dress_code_new }}{{ $dress->dress_code }} (เสื้อ)
                                                </td>
                                                <td>
                                                    <select name="actionreturnitemtotal1" id="actionreturnitemtotal1"
                                                        class="form-control">
                                                        <option value="cleanitem" selected>สภาพปกติ ส่งทำความสะอาด</option>
                                                        <option value="repairitem">ต้องซ่อม</option>
                                                        <option value="lost">สูญหาย (ลูกค้าแจ้ง)</option>
                                                        <option value="lost_unreported">*สูญหาย (ลูกค้าไม่ส่งคืน)</option>
                                                        <option value="damaged_beyond_repair">*เสียหายหนัก
                                                            (ให้เช่าต่อไม่ได้)
                                                        </option>
                                                    </select>
                                                    <input type="hidden" name="filtershirt_id"
                                                        value="{{ $filtershirt_id }}">

                                                    <div id="showrepair_detail_itemtotal1" class="mt-2"
                                                        style="display: none;">
                                                        <textarea name="repair_detail_for_item1" class="form-control" placeholder="กรุณาระบุรายละเอียดการซ่อม..."
                                                            rows="3"></textarea>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="number" value="0" class="form-control"
                                                        min="0" name="damage_insurance_shirt_two" required>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    {{ $typename }}
                                                    {{ $dress->dress_code_new }}{{ $dress->dress_code }} (ผ้าถุง)
                                                </td>
                                                <td>
                                                    <select name="actionreturnitemtotal2" id="actionreturnitemtotal2"
                                                        class="form-control">
                                                        <option value="cleanitem" selected>สภาพปกติ ส่งทำความสะอาด</option>
                                                        <option value="repairitem">ต้องซ่อม</option>
                                                        <option value="lost">*สูญหาย (ลูกค้าแจ้ง)</option>
                                                        <option value="lost_unreported">*สูญหาย (ลูกค้าไม่ส่งคืน)</option>
                                                        <option value="damaged_beyond_repair">*เสียหายหนัก
                                                            (ให้เช่าต่อไม่ได้)</option>
                                                    </select>
                                                    <input type="hidden" name="filterskirt_id"
                                                        value="{{ $filterskirt_id }}">

                                                    <div id="showrepair_detail_itemtotal2" class="mt-2"
                                                        style="display: none;">
                                                        <textarea name="repair_detail_for_item2" class="form-control" placeholder="กรุณาระบุรายละเอียดการซ่อม..."
                                                            rows="3"></textarea>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="number" value="0" class="form-control"
                                                        min="0" name="damage_insurance_skirt_two" required>
                                                </td>
                                            </tr>


                                            <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    var actionreturnitemtotal1 = document.getElementById('actionreturnitemtotal1');
                                                    var showrepair_detail_itemtotal1 = document.getElementById('showrepair_detail_itemtotal1');
                                                    actionreturnitemtotal1.addEventListener('change', function() {
                                                        if (actionreturnitemtotal1.value == "repairitem") {
                                                            showrepair_detail_itemtotal1.style.display = 'block';
                                                        } else {
                                                            showrepair_detail_itemtotal1.style.display = 'none';
                                                        }
                                                    });

                                                    var actionreturnitemtotal2 = document.getElementById('actionreturnitemtotal2');
                                                    var showrepair_detail_itemtotal2 = document.getElementById('showrepair_detail_itemtotal2');
                                                    actionreturnitemtotal2.addEventListener('change', function() {
                                                        if (actionreturnitemtotal2.value == "repairitem") {
                                                            showrepair_detail_itemtotal2.style.display = 'block';
                                                        } else {
                                                            showrepair_detail_itemtotal2.style.display = 'none';
                                                        }
                                                    });
                                                });
                                            </script>


                                        </tbody>
                                    </table>
                                </div>

                            @endif


                        @endif

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn " data-dismiss="modal"
                            style="background-color:#DADAE3;">ยกเลิก</button>
                        <button type="submit" class="btn " id="confirmReturnButton"
                            style="background-color:#ACE6B7;">ยืนยันการคืนชุด</button>
                    </div>
                </div>
            </form>
        </div>
    </div>






@endsection
