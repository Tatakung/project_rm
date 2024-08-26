@extends('layouts.adminlayout')
@section('content')

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .card-custom {
            border: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .card-custom:hover {
            transform: translateY(-10px);

        }

        .card-title {
            text-align: center;
            color: black;
            margin-bottom: 15px;
        }

        .card-img {
            max-height: 350px;
            width: 200px;
            display: block;
            margin-left: auto;
            margin-right: auto;
            margin: auto;
        }

        body {
            /* font-family: 'Barlow', sans-serif; */
            font-family: "Prompt", sans-serif;
        }

        .breadcrumb {
            background-color: white;
            color: black;
        }
    </style>
</head>

</html>

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="">หน้าแรก</a></li>
    <li class="breadcrumb-item"><a href="{{route('employee.selectdate')}}">เลือกวันที่นัดรับ-คืนชุด</a></li>
    <li class="breadcrumb-item active">เลือกหมวดหมู่ชุด</li>
</ol>

<div class="row">
    <div class="col">
        <h2 class="py-2" style="text-align: center">ประเภทชุด</h2>
    </div>
</div>
<div class="container">

    {{-- {{$startDate}}
    {{$endDate}}
    {{$totalDay}} --}}
    {{-- {{$}} --}}


    <div class="row">
        @foreach ($typedress as $index => $item)
        <div class="card card-custom mt-5 col-3">
            <a href="{{ route('admin.dressdetail', ['id' => $item->id , 'separable' => $item->separable]) }}">
                <div class="card-body">
                    <div class="card-body">
                        <a href="{{ route('employee.typerentdressshow', ['id' => $item->id, 'startDate' => $startDate, 'endDate' => $endDate, 'totalDay' => $totalDay]) }}">
                            <h5 class="card-title">{{ $item->type_dress_name }}</h5>
                            {{-- {{$item->id}} --}}
                            @php
                            $type_id = $item->id;
                            $dress_id = App\Models\Dress::where('type_dress_id', $item->id)->value('id');
                            $image = App\Models\Dressimage::where('dress_id', $dress_id)->value('dress_image');
                            @endphp
                            <img src="{{ asset('storage/' . $image) }}" alt="" class="card-img mb-3">
                    </div>
                </div>
                {{-- </a> --}}
        </div>
        @if (($index + 1) % 4 == 0)
    </div>
    <div class="row mt-4">
        @endif
        @endforeach
    </div>
</div>
</body>
@endsection