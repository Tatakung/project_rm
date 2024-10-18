@extends('layouts.adminlayout')
@section('content')

    <style>
        .container h3 {
            font-weight: bold;
            color: #333;
            margin-bottom: 1rem;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 8px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: none;
        }

        .card-body h5 {
            font-weight: bold;
            color: #4A4A4A;
        }

        .card-body p {
            color: #6c757d;
        }

        .table-responsive {
            margin-top: 2rem;
        }

        .table thead {
            background-color: #f8f9fa;
        }

        .table-bordered th,
        .table-bordered td {
            border-color: #dee2e6;
            vertical-align: middle;
            text-align: center;
        }

        .table th {
            color: #6c757d;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .status-late {
            color: #dc3545;
            font-weight: bold;
        }

        .status-on-time {
            color: #28a745;
            font-weight: bold;
        }

        .status-early {
            color: #ffc107;
            font-weight: bold;
        }

        p.centered {
            text-align: center;
            color: #6c757d;
            font-size: 1.1rem;
            margin-top: 20px;
        }
    </style>
    <div class="container mt-5">
        <h3>ประวัติการเช่าชุด </h3>


        <div class="card mb-5">
            <div class="card-body">
                <form action="{{ route('admin.historydressrentnofilter', ['id' => $dress->id]) }}" method="GET"
                    class="form-inline">
                    @csrf
                    <div class="form-group mb-2">
                        <label for="month" class="mr-2">ฟิลเตอร์:</label>
                        <select class="form-control mr-2" name="month" id="month">
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

                        <select class="form-control mr-2" name="year" id="year">
                            <option value="">ปี</option>
                            @for ($i = 2020; $i <= now()->year; $i++)
                                <option value="{{ $i }}" @if ($value_year == $i) selected @endif>
                                    {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">ใช้ฟิลเตอร์</button>
                </form>
            </div>
        </div>


        
        @if ($history_renrdress->count() > 0)
            <div class="card">
                <div class="card-body">
                    <h5>ข้อมูลสรุปของ{{ $typedress->type_dress_name }} {{ $dress->dress_code_new }}
                        {{ $dress->dress_code }}
                    </h5>
                    <p>จำนวนครั้งที่ถูกเช่า: <strong>{{ $history_renrdress->count() }} ครั้ง</strong></p>
                    <p>รายได้รวม: <strong>{{ number_format($history_renrdress->sum('price'), 2) }} บาท</strong></p>
                    <p>อัตราการเช่าต่อเดือน: <strong>ข้อมูลเพิ่มเติม (หากมี)</strong></p>
                </div>
            </div>
            <div class="table-responsive mt-4">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">วันที่เช่า</th>
                            <th scope="col">วันที่คืน</th>
                            <th scope="col">รวมระยะเวลา (วัน)</th>
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
                                    {{ \carbon\carbon::parse($date->actua_return_date)->locale('th')->isoFormat('D MMM') }}
                                    {{ \Carbon\Carbon::parse($date->actua_return_date)->year + 543 }}
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
                                    <span id="status_show{{ $item->id }}" class=""></span>

                                    <script>
                                        var date_return = new Date('{{ $date->return_date }}');
                                        var date_pickup = new Date('{{ $date->pickup_date }}');
                                        date_return.setHours(0, 0, 0, 0);
                                        date_pickup.setHours(0, 0, 0, 0);
                                        var day_contract = Math.ceil((date_return - date_pickup) / (1000 * 60 * 60 * 24) + 1);
                                        var statusElement = document.getElementById('status_show{{ $item->id }}');

                                        if (total_day > day_contract) {
                                            statusElement.innerHTML = 'คืนล่าช้า';
                                            statusElement.classList.add('status-late');
                                        } else if (total_day == day_contract) {
                                            statusElement.innerHTML = 'คืนตามกำหนด';
                                            statusElement.classList.add('status-on-time');
                                        } else if (total_day < day_contract) {
                                            statusElement.innerHTML = 'คืนก่อนกำหนด';
                                            statusElement.classList.add('status-early');
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
            <p class="centered">ไม่มีรายการประวัติการเช่าชุดนี้</p>
        @endif
    </div>
@endsection
