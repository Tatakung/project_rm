@extends('layouts.adminlayout')
@section('content')
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .card-custom {
            border: none;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
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
            font-family: "Prompt", sans-serif;
        }
    </style>
</head>
</html>
<body>
<div class="row">
        <div class="col">
            <h2 class="py-4" style="text-align: center">ประเภท{{$typedress->type_dress_name}}</h2>
        </div>
    </div>
    <div class="container">

        


       
        <div class="row">
            @foreach ($data as $index => $item)
                <div class="card card-custom mt-5 col-3">
                    <a href="{{ route('admin.dressdetail', ['id' => $item->id , 'separable' => $item->separable]) }}">
                        <div class="card-body">
                            
                            <div class="card-body">
                                    @if ($item->dressimages->isNotEmpty())
                                    <img src="{{ asset('storage/' . $item->dressimages->first()->dress_image) }}"
                                        alt=""  class="card-img mb-3">
                                @endif
                                
                                <h6 @if($item->dress_status == "พร้อมให้เช่า") style="color: green" 
                                    @else
                                    style="color: red" 
                                    @endif
                                >{{$item->dress_status}}</h6>
                                @if($item->separable == 1)
                                <h6 style="color: black;"> ทั้งชุด</h6>
                                @elseif($item->separable == 2)
                                <h6 style="color: black;"> ชุดแยก: เสื้อและกระโปรง</h6>
                                @endif
                                <h6 style="color: black;">ราคาเช่า: {{ number_format($item->dress_price, 2) }} บาท</h6>
                            </div>
                        </div>
                </div>
                @if (($index + 1) % 4 == 0)
        </div>
        <div class="row mt-4">
            @endif
            @endforeach
        </div>
    </div>

    <script>
        document.getElementById('search_status_of_dress').addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });
    </script>
    </body>
@endsection