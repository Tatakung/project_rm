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
    <ol class="breadcrumb" style="background-color: transparent;">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dresstotal') }}" style="color: black ; ">รายการชุด</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.typedress', ['id' => $typedressname->id]) }}"
                style="color: black ;">ประเภท{{ $typedressname->type_dress_name }}</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dressdetail', ['id' => $dress->id, 'separable' => $dress->separable]) }}"
                style="color: black ;">รายละเอียด{{ $typedressname->type_dress_name }}
                {{ $dress->dress_code_new }}{{ $dress->dress_code }}</a>
        </li>


        <li class="breadcrumb-item active">
            ประวัติการซ่อม{{ $typedressname->type_dress_name }} {{ $dress->dress_code_new }}{{ $dress->dress_code }}
        </li>
    </ol>
    <div class="container mt-5">
        <h2>ประวัติการซ่อม{{ $typedressname->type_dress_name }} {{ $dress->dress_code_new }}{{ $dress->dress_code }}
        </h2>


        @if ($history->count() > 0)
            <div class="table-responsive mt-4">
                <table class="table table-striped text-start">
                    <thead>
                        <tr>
                            <th scope="col">วันที่</th>
                            <th scope="col">รายละเอียด</th>
                            <th scope="col">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($history as $item)
                            <tr>
                                <td>
                                    {{ \Carbon\Carbon::parse($item->created_at)->locale('th')->isoFormat('D MMM') }}
                                    {{ \Carbon\Carbon::parse($item->created_at)->year + 543 }}
                                </td>
                                <td>{{ $item->repair_description }}</td>
                                <td>{{ $item->repair_status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="centered">ไม่มีรายการประวัติการซ่อม</p>
        @endif
    </div>
@endsection
