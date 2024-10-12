@extends('layouts.adminlayout')
@section('content')



    <div class="container mt-5">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a href="#dress_total" class="nav-link active" data-toggle="tab">ทั้งชุด</a>
            </li>
            <li class="nav-item">
                <a href="#dress_shirt" class="nav-link" data-toggle="tab">เสื้อ</a>
            </li>
            <li class="nav-item">
                <a href="#dress_skirt" class="nav-link" data-toggle="tab">ผ้าถุง</a>
            </li>
        </ul>


        <div class="tab-content">
            <div class="tab-pane active" id="dress_total">
                @if ($history_renrdress->count() > 0)
                    <div class="card">
                        <div class="card-body">
                            <h5>ข้อมูลสรุป</h5>
                            <p>จำนวนครั้งที่ถูกเช่า {{ $history_renrdress->count() }} ครั้ง</p>
                            <p>รายได้รวม {{ number_format($history_renrdress->sum('price'), 2) }} บาท</p>
                            <p>อัตรราการเช่าต่อเดือน </p>
                        </div>
                    </div>

                    <div class="table-responsive mt-4">
                        <table class="table table-bordered">
                            <thead">
                                <tr>
                                    <th scope="col">วันที่เช่า</th>
                                    <th scope="col">ชื่อลูกค้า</th>
                                    <th scope="col">รวมระยะเวลา (วัน)</th>
                                    <th scope="col">วันที่คืน</th>
                                    <th scope="col">สถานะ</th>
                                    <th scope="col">ดูรายละเอียด</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($history_renrdress as $item)
                                        <tr>
                                            <td>
                                                @php
                                                    $date = App\Models\Date::where('order_detail_id', $item->id)
                                                        ->orderBy('created_at', 'desc')
                                                        ->first();
                                                @endphp
                                                {{ \Carbon\Carbon::parse($date->actua_pickup_date)->locale('th')->isoFormat('D MMM') }}
                                                {{ \Carbon\Carbon::parse($date->actua_pickup_date)->year + 543 }}
                                            </td>
                                            <td>

                                                @php
                                                    $order_id = App\Models\Orderdetail::where('id', $item->id)->value(
                                                        'order_id',
                                                    );
                                                    $customer_id = App\Models\Order::where('id', $order_id)->value(
                                                        'customer_id',
                                                    );
                                                    $customer = App\Models\Customer::find($customer_id);
                                                @endphp
                                                คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }}
                                            </td>
                                            <td>
                                                <span id="show_day{{ $item->id }}">
                                                    <script>
                                                        var actua_return_date = new Date('{{ $date->actua_return_date }}');
                                                        var actua_pickup_date = new Date('{{ $date->actua_pickup_date }}');
                                                        actua_return_date.setHours(0, 0, 0, 0);
                                                        actua_pickup_date.setHours(0, 0, 0, 0);
                                                        var total_day = Math.ceil((actua_return_date - actua_pickup_date) / (1000 * 60 * 60 * 24)) + 1;
                                                        document.getElementById('show_day{{ $item->id }}').innerHTML = total_day;
                                                    </script>
                                                </span>
                                            </td>
                                            <td>
                                                {{ \carbon\carbon::parse($date->actua_return_date)->locale('th')->isoFormat('D MMM') }}
                                                {{ \Carbon\Carbon::parse($date->actua_return_date)->year + 543 }}
                                            </td>
                                            <td>
                                                <span id="status_show{{ $item->id }}"></span>

                                                <script>
                                                    var date_return = new Date('{{ $date->return_date }}');
                                                    var date_pickup = new Date('{{ $date->pickup_date }}');
                                                    date_return.setHours(0, 0, 0, 0);
                                                    date_pickup.setHours(0, 0, 0, 0);
                                                    var day_contract = Math.ceil((date_return - date_pickup) / (1000 * 60 * 60 * 24) + 1);
                                                    document.getElementById('status_show{{ $item->id }}').innerHTML = day_contract;

                                                    if (total_day > day_contract) {
                                                        document.getElementById('status_show{{ $item->id }}').innerHTML = 'คืนล่าช้า';
                                                    } else if (total_day == day_contract) {
                                                        document.getElementById('status_show{{ $item->id }}').innerHTML = 'คืนตามกำหนด';
                                                    } else if (total_day < day_contract) {
                                                        document.getElementById('status_show{{ $item->id }}').innerHTML = 'คืนก่อนกำหนด';
                                                    }
                                                </script>



                                            </td>
                                            <td>
                                                <a href="{{ route('employee.ordertotaldetailshow', ['id' => $item->id]) }}"
                                                    class="btn btn-primary btn-sm">ดูรายละเอียด</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                        </table>
                    </div>
                @else
                    <p style="text-align: center ; ">ไม่มีรายการประวัติการเช่าชุดนี้</p>
                @endif
            </div>
            <div class="tab-pane" id="dress_shirt">
                @if ($history_rentshirt->count() > 0)
                    <div class="card">
                        <div class="card-body">
                            <h5>ข้อมูลสรุป</h5>
                            <p>จำนวนครั้งที่ถูกเช่า {{ $history_rentshirt->count() }} ครั้ง</p>
                            <p>รายได้รวม {{ number_format($history_rentshirt->sum('price'), 2) }} บาท</p>
                            <p>อัตรราการเช่าต่อเดือน </p>
                        </div>
                    </div>

                    <div class="table-responsive mt-4">
                        <table class="table table-bordered">
                            <thead">
                                <tr>
                                    <th scope="col">วันที่เช่า</th>
                                    <th scope="col">ชื่อลูกค้า</th>
                                    <th scope="col">รวมระยะเวลา (วัน)</th>
                                    <th scope="col">วันที่คืน</th>
                                    <th scope="col">สถานะ</th>
                                    <th scope="col">ดูรายละเอียด</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($history_rentshirt as $item)
                                        <tr>
                                            <td>
                                                @php
                                                    $date = App\Models\Date::where('order_detail_id', $item->id)
                                                        ->orderBy('created_at', 'desc')
                                                        ->first();
                                                @endphp
                                                {{ \Carbon\Carbon::parse($date->actua_pickup_date)->locale('th')->isoFormat('D MMM') }}
                                                {{ \Carbon\Carbon::parse($date->actua_pickup_date)->year + 543 }}
                                            </td>
                                            <td>

                                                @php
                                                    $order_id = App\Models\Orderdetail::where('id', $item->id)->value(
                                                        'order_id',
                                                    );
                                                    $customer_id = App\Models\Order::where('id', $order_id)->value(
                                                        'customer_id',
                                                    );
                                                    $customer = App\Models\Customer::find($customer_id);
                                                @endphp
                                                คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }}
                                            </td>
                                            <td>
                                                <span id="show_day{{ $item->id }}">
                                                    <script>
                                                        var actua_return_date = new Date('{{ $date->actua_return_date }}');
                                                        var actua_pickup_date = new Date('{{ $date->actua_pickup_date }}');
                                                        actua_return_date.setHours(0, 0, 0, 0);
                                                        actua_pickup_date.setHours(0, 0, 0, 0);
                                                        var total_day = Math.ceil((actua_return_date - actua_pickup_date) / (1000 * 60 * 60 * 24)) + 1;
                                                        document.getElementById('show_day{{ $item->id }}').innerHTML = total_day;
                                                    </script>
                                                </span>
                                            </td>
                                            <td>
                                                {{ \carbon\carbon::parse($date->actua_return_date)->locale('th')->isoFormat('D MMM') }}
                                                {{ \Carbon\Carbon::parse($date->actua_return_date)->year + 543 }}
                                            </td>
                                            <td>
                                                <span id="status_show{{ $item->id }}"></span>

                                                <script>
                                                    var date_return = new Date('{{ $date->return_date }}');
                                                    var date_pickup = new Date('{{ $date->pickup_date }}');
                                                    date_return.setHours(0, 0, 0, 0);
                                                    date_pickup.setHours(0, 0, 0, 0);
                                                    var day_contract = Math.ceil((date_return - date_pickup) / (1000 * 60 * 60 * 24) + 1);
                                                    document.getElementById('status_show{{ $item->id }}').innerHTML = day_contract;

                                                    if (total_day > day_contract) {
                                                        document.getElementById('status_show{{ $item->id }}').innerHTML = 'คืนล่าช้า';
                                                    } else if (total_day == day_contract) {
                                                        document.getElementById('status_show{{ $item->id }}').innerHTML = 'คืนตามกำหนด';
                                                    } else if (total_day < day_contract) {
                                                        document.getElementById('status_show{{ $item->id }}').innerHTML = 'คืนก่อนกำหนด';
                                                    }
                                                </script>



                                            </td>
                                            <td>
                                                <a href="{{ route('employee.ordertotaldetailshow', ['id' => $item->id]) }}"
                                                    class="btn btn-primary btn-sm">ดูรายละเอียด</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                        </table>
                    </div>
                @else
                    <p style="text-align: center ; ">ไม่มีรายการประวัติการเช่าชุดนี้</p>
                @endif
            </div>
            <div class="tab-pane" id="dress_skirt">
                @if ($history_rentskirt->count() > 0)
                    <div class="card">
                        <div class="card-body">
                            <h5>ข้อมูลสรุป</h5>
                            <p>จำนวนครั้งที่ถูกเช่า {{ $history_rentskirt->count() }} ครั้ง</p>
                            <p>รายได้รวม {{ number_format($history_rentskirt->sum('price'), 2) }} บาท</p>
                            <p>อัตรราการเช่าต่อเดือน </p>
                        </div>
                    </div>

                    <div class="table-responsive mt-4">
                        <table class="table table-bordered">
                            <thead">
                                <tr>
                                    <th scope="col">วันที่เช่า</th>
                                    <th scope="col">ชื่อลูกค้า</th>
                                    <th scope="col">รวมระยะเวลา (วัน)</th>
                                    <th scope="col">วันที่คืน</th>
                                    <th scope="col">สถานะ</th>
                                    <th scope="col">ดูรายละเอียด</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($history_rentskirt as $item)
                                        <tr>
                                            <td>
                                                @php
                                                    $date = App\Models\Date::where('order_detail_id', $item->id)
                                                        ->orderBy('created_at', 'desc')
                                                        ->first();
                                                @endphp
                                                {{ \Carbon\Carbon::parse($date->actua_pickup_date)->locale('th')->isoFormat('D MMM') }}
                                                {{ \Carbon\Carbon::parse($date->actua_pickup_date)->year + 543 }}
                                            </td>
                                            <td>

                                                @php
                                                    $order_id = App\Models\Orderdetail::where('id', $item->id)->value(
                                                        'order_id',
                                                    );
                                                    $customer_id = App\Models\Order::where('id', $order_id)->value(
                                                        'customer_id',
                                                    );
                                                    $customer = App\Models\Customer::find($customer_id);
                                                @endphp
                                                คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }}
                                            </td>
                                            <td>
                                                <span id="show_day{{ $item->id }}">
                                                    <script>
                                                        var actua_return_date = new Date('{{ $date->actua_return_date }}');
                                                        var actua_pickup_date = new Date('{{ $date->actua_pickup_date }}');
                                                        actua_return_date.setHours(0, 0, 0, 0);
                                                        actua_pickup_date.setHours(0, 0, 0, 0);
                                                        var total_day = Math.ceil((actua_return_date - actua_pickup_date) / (1000 * 60 * 60 * 24)) + 1;
                                                        document.getElementById('show_day{{ $item->id }}').innerHTML = total_day;
                                                    </script>
                                                </span>
                                            </td>
                                            <td>
                                                {{ \carbon\carbon::parse($date->actua_return_date)->locale('th')->isoFormat('D MMM') }}
                                                {{ \Carbon\Carbon::parse($date->actua_return_date)->year + 543 }}
                                            </td>
                                            <td>
                                                <span id="status_show{{ $item->id }}"></span>

                                                <script>
                                                    var date_return = new Date('{{ $date->return_date }}');
                                                    var date_pickup = new Date('{{ $date->pickup_date }}');
                                                    date_return.setHours(0, 0, 0, 0);
                                                    date_pickup.setHours(0, 0, 0, 0);
                                                    var day_contract = Math.ceil((date_return - date_pickup) / (1000 * 60 * 60 * 24) + 1);
                                                    document.getElementById('status_show{{ $item->id }}').innerHTML = day_contract;

                                                    if (total_day > day_contract) {
                                                        document.getElementById('status_show{{ $item->id }}').innerHTML = 'คืนล่าช้า';
                                                    } else if (total_day == day_contract) {
                                                        document.getElementById('status_show{{ $item->id }}').innerHTML = 'คืนตามกำหนด';
                                                    } else if (total_day < day_contract) {
                                                        document.getElementById('status_show{{ $item->id }}').innerHTML = 'คืนก่อนกำหนด';
                                                    }
                                                </script>



                                            </td>
                                            <td>
                                                <a href="{{ route('employee.ordertotaldetailshow', ['id' => $item->id]) }}"
                                                    class="btn btn-primary btn-sm">ดูรายละเอียด</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                        </table>
                    </div>
                @else
                    <p style="text-align: center ; ">ไม่มีรายการประวัติการเช่าชุดนี้</p>
                @endif
            </div>
        </div>


    </div>

@endsection
