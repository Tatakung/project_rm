@extends('layouts.adminlayout')
@section('content')
<html>
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
            font-size: 34px ; /* น้าแอ๊ด */
        }
        .card-img {
            max-height: 350px;
            width: 200px;
            display: block;
            margin-left: auto;
            margin-right: auto;
            margin: auto;
            font-size: 34px ; /* น้าแอ๊ด */
        }
        body {
            /* font-family: 'Barlow', sans-serif; */
            font-family: "Prompt", sans-serif;
             font-size: 34px ; /* น้าแอ๊ด */
        }
    </style>
</head>
</html>
<body>

    
    

    <div class="row">
        <div class="col">
            <h2 class="py-4" style="text-align: center">ประเภทชุด</h2>
        </div>
    </div>

    <div class="container ">
        <div class="d-flex justify-content-end ">
          <button class="btn btn-success ">
            <a href="{{ route('admin.formadddress') }}" style="color: aliceblue ; font-size: 34px;">เพิ่มชุดใหม่เข้าไปในร้าน (สำหรับเช่า)</a>  
        </div>

        </button>
        <div class="container">
    <div class="row">
        @foreach ($showtype as $index => $item)

            <div class="card card-custom mt-5 col-3">
                <div class="card-body">
                    <a href="{{ route('admin.typedress', ['id' => $item->id]) }}">
                        <h5 class="card-title">{{ $item->type_dress_name }}</h5>
                        @php
                        $type_id = $item->id ;
                        $dress_id = App\Models\Dress::where('type_dress_id',$type_id)->value('id') ;
                        $image_show = App\Models\Dressimage::where('dress_id',$dress_id)->first() ;
                        @endphp
                        <img src="{{ asset('storage/' . $image_show->dress_image) }}" alt="" class="card-img">
                    </a>
                </div>
            </div>

        @if (($index + 1) % 4 == 0)
    </div>  
    <div class="row">
        @endif
        @endforeach
    </div>
</div>
</body>
@endsection