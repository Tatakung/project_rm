@extends('layouts.adminlayout')
@section('content')
    <div class="container mt-5">
        <div style="max-width: 1000px; margin: 0 auto;">
            <h2 style="text-align: center ; ">ประวัติการปรับแก้ขนาดชุด</h2>



            <div class="container">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a href="#one" class="nav-link active" data-toggle="tab">เสื้อ</a>
                    </li>
                    <li class="nav-item">
                        <a href="#two" class="nav-link" data-toggle="tab">ผ้าถุง</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="one">
                        

                        @if ($history_shirt->count() > 0)
                        <h3>{{ $typedress->type_dress_name }} รหัสชุด: {{ $dress->dress_code_new }}{{ $dress->dress_code }}</h3>
                        <div style="margin-bottom: 20px;">
                            <h4>ประวัติการปรับแก้:</h4>
                            <div style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px;">
                                @php
                                    $count_dress = 0;
                                @endphp
                                <table style="width: 100%; border-collapse: collapse;">
                                    @foreach ($history_shirt as $item)
                                        @if ($item->adjustment_number != $count_dress)
                                            {{-- <h5 style="margin-top: 0;">ครั้งที่ {{ $item->adjustment_number }} </h5> --}}
                                            <tr>
                                                @php
                                                    $orderid = App\Models\Orderdetail::where(
                                                        'id',
                                                        $item->order_detail_id,
                                                    )->value('order_id');
                                                    $customer_id = App\Models\Order::where('id', $orderid)->value(
                                                        'customer_id',
                                                    );
                                                    $customer = App\Models\Customer::find($customer_id);
                                                @endphp
                                                <th colspan="3">ครั้งที่ {{ $item->adjustment_number }} </th>
                                            </tr>
                                            <tr style="background-color: #f2f2f2;">
                                                <th style="border: 1px solid #ddd; padding: 8px;">ส่วนที่ปรับ</th>
                                                <th style="border: 1px solid #ddd; padding: 8px;">ขนาดเดิม</th>
                                                <th style="border: 1px solid #ddd; padding: 8px;">ขนาดที่ปรับ</th>
                                            </tr>
                                        @endif
                                        @php
                                            $count_dress = $item->adjustment_number;
                                        @endphp
                                        <tr>
                                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $item->name }}</td>
                                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $item->old_size }}</td>
                                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $item->edit_new_size }}</td>
                                        </tr>
                                    @endforeach
        
                                </table>
                            </div>
                        </div>
                    @else
                        <p style="text-align: center ; ">ไม่มีรายการประปรับแก้ชุด</p>
                    @endif

















                    </div>
                    <div class="tab-pane" id="two">
                        @if ($history_skirt->count() > 0)
                        <h3>{{ $typedress->type_dress_name }} รหัสชุด: {{ $dress->dress_code_new }}{{ $dress->dress_code }}</h3>
                        <div style="margin-bottom: 20px;">
                            <h4>ประวัติการปรับแก้:</h4>
                            <div style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px;">
                                @php
                                    $count_dress = 0;
                                @endphp
                                <table style="width: 100%; border-collapse: collapse;">
                                    @foreach ($history_skirt as $item)
                                        @if ($item->adjustment_number != $count_dress)
                                            {{-- <h5 style="margin-top: 0;">ครั้งที่ {{ $item->adjustment_number }} </h5> --}}
                                            <tr>
                                                @php
                                                    $orderid = App\Models\Orderdetail::where(
                                                        'id',
                                                        $item->order_detail_id,
                                                    )->value('order_id');
                                                    $customer_id = App\Models\Order::where('id', $orderid)->value(
                                                        'customer_id',
                                                    );
                                                    $customer = App\Models\Customer::find($customer_id);
                                                @endphp
                                                <th colspan="3">ครั้งที่ {{ $item->adjustment_number }} </th>
                                            </tr>
                                            <tr style="background-color: #f2f2f2;">
                                                <th style="border: 1px solid #ddd; padding: 8px;">ส่วนที่ปรับ</th>
                                                <th style="border: 1px solid #ddd; padding: 8px;">ขนาดเดิม</th>
                                                <th style="border: 1px solid #ddd; padding: 8px;">ขนาดที่ปรับ</th>
                                            </tr>
                                        @endif
                                        @php
                                            $count_dress = $item->adjustment_number;
                                        @endphp
                                        <tr>
                                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $item->name }}</td>
                                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $item->old_size }}</td>
                                            <td style="border: 1px solid #ddd; padding: 8px;">{{ $item->edit_new_size }}</td>
                                        </tr>
                                    @endforeach
        
                                </table>
                            </div>
                        </div>
                    @else
                        <p style="text-align: center ; ">ไม่มีรายการประปรับแก้ชุด</p>
                    @endif
                    </div>

                </div>

                





            </div>

        </div>


    </div>
@endsection
