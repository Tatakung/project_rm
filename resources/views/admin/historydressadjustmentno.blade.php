@extends('layouts.adminlayout')
@section('content')
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em;
        }

        .adjustment-record {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            padding: 20px;
        }

        h2 {
            color: #3498db;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            margin-top: 0;
        }

        .customer-name {
            font-weight: bold;
            color: #e74c3c;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .note {
            font-style: italic;
            color: #7f8c8d;
            margin-top: 15px;
        }
    </style>
    <div class="container">
        <h1>📏 ประวัติการปรับขนาด</h1>
        @php
            $list_one = [];
            $list_two = [];
            $list_edit = [];
            $dress_mea = App\Models\Dressmea::where('dress_id', $dress_id)->get();
            foreach ($dress_mea as $item) {
                $list_one[] = $item->id;
            }

            $dress_adjust = App\Models\Dressmeaadjustment::whereIn('dressmea_id', $list_one)->get();

            foreach ($dress_adjust as $item) {
                $list_two[] = $item->id;
            }

            $mea_edit = App\Models\Dressmeasurementcutedit::whereIn('adjustment_id', $list_two)->get();

            foreach ($mea_edit as $key => $value) {
                $list_edit[] = $value->id;
            }
            $show_mea_edit = App\Models\Dressmeasurementcutedit::whereIn('id', $list_edit)->get();
        @endphp

        <div class="adjustment-record">
            {{-- <h2>🗓 ครั้งที่ 1 - วันที่: 15/09/2567</h2>
            <p>ลูกค้า: <span class="customer-name">คุณเธอศรี โจดี</span></p> --}}


            @php
                $Counter = 0;
            @endphp

            @if ($show_mea_edit->count() > 0)
                <table>
                    <table>
                        <tr>
                            <th>ส่วนที่ปรับ</th>
                            <th>ขนาดเดิม</th>
                            <th>ขนาดที่ปรีบ</th>
                        </tr>
                        @foreach ($show_mea_edit as $item)
                            @if ($item->adjustment_number != $Counter)
                                <tr>
                                    @php
                                        $order_id = App\Models\Orderdetail::where('id', $item->order_detail_id)->value(
                                            'order_id',
                                        );
                                        $customer_id = App\Models\Order::where('id', $order_id)->value('customer_id');
                                        $customer = App\Models\customer::find($customer_id);
                                    @endphp
                                    <th colspan="3">ครั้งที่ {{ $item->adjustment_number }} <span>ลูกค้า :
                                            คุณ{{ $customer->customer_fname }} {{ $customer->customer_lname }}</span></th>
                                </tr>
                            @endif
                            @php
                                $Counter = $item->adjustment_number;
                            @endphp
                            <tr>
                                <td>
                                    @php
                                        $dressmea_id = App\Models\Dressmeaadjustment::where(
                                            'id',
                                            $item->adjustment_id,
                                        )->value('dressmea_id');
                                        $dress_mea_name = App\Models\Dressmea::where('id', $dressmea_id)->value(
                                            'mea_dress_name',
                                        );
                                    @endphp
                                    {{ $dress_mea_name }}
                                </td>
                                <td>{{ $item->old_size }}</td>
                                <td>{{ $item->edit_new_size }}</td>
                            </tr>
                        @endforeach
                    </table>
                </table>
                @else
                <p style="text-align: center ; ">ไม่มีประวัติการปรับแก้</p>
                @endif








                {{-- <p class="note">📝 หมายเหตุ: ปรับขยายตามความต้องการของลูกค้า</p>
            <p class="note">📝 หมายเหตุ: ปรับขยายตามความต้องการของลูกค้า</p> --}}
        </div>


    </div>
@endsection
