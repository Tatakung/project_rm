@extends('layouts.adminlayout')
@section('content')
    <div class="container mt-5">
        <h2>ประวัติการซ่อมชุด</h2>
        <p>{{ $typedressname->type_dress_name }} {{ $dress->dress_code_new }}{{ $dress->dress_code }}</p>

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a href="#one" class="nav-link active" data-toggle="tab">ประวัติการซ่อมเสื้อ</a>
            </li>
            <li class="nav-item">
                <a href="#two" class="nav-link" data-toggle="tab">ประวัติการซ่อมผ้าถุง</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="one">
                @if ($history_shirt->count() > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>วันที่</th>
                                <th>รายละเอียด</th>
                                <th>สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($history_shirt as $item)
                                <tr>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->repair_description }}</td>
                                    <td>{{ $item->repair_status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p style="text-align: center ; ">ไม่มีรายการประวัติการซ่อม</p>
                @endif
            </div>
            <div class="tab-pane" id="two">
                @if ($history_skirt->count() > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>วันที่</th>
                                <th>รายละเอียด</th>
                                <th>สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($history_skirt as $item)
                                <tr>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->repair_description }}</td>
                                    <td>{{ $item->repair_status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p style="text-align: center ; ">ไม่มีรายการประวัติการซ่อม</p>
                @endif
            </div>
        </div>
    </div>




@endsection
