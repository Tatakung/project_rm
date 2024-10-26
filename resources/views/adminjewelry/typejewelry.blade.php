@extends('layouts.adminlayout')
@section('content')

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .custom-card {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            background: linear-gradient(145deg, #ffffff, #ffffff);
        }

        .custom-card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .custom-img {
            width: 100%;
            /* Make sure the image fills the width of the card */
            height: 320px;
            /* You can adjust the height as needed */
            object-fit: cover;
            /* This ensures the image keeps its aspect ratio */
            border-bottom: 2px solid #f8f8f8;
        }


        .card-body h5,
        .card-body p {
            margin: 0;
        }

        .card-body p {
            font-size: 14px;
        }

        .card-body .text-success {
            color: #28a745;
            font-weight: bold;
        }

        .card-body .text-danger {
            color: #dc3545;
            font-weight: bold;
        }

        body {
            /* font-family: 'Barlow', sans-serif; */
            font-family: "Bai Jamjuree", sans-serif;
        }
    </style>
</head>

</html>


<body>
    <div class="container  mt-5">
        <div class="col">
            <h1 class="font-bold">ประเภท{{$typename}}</h1>
        </div>
    </div>

    <div class="container mt-5">
    <div class="row">
        @foreach ($datajewelry as $index => $item)
        <div class="col-md-3 mb-4">
            <div class="card text-left custom-card">
                <a href="{{route('admin.jewelrydetail',['id' => $item->id])}}" style="text-decoration: none;">
                    @php
                        $image = App\Models\Jewelryimage::where('jewelry_id',$item->id)->value('jewelry_image') ; 
                    @endphp
                    <img src="{{ asset('storage/' . $image) }}" alt="" class="card-img-top custom-img">
                    <div class="card-body text-center">
                        
                        <h6 style="color: black;">ราคาเช่า: {{ number_format($item->jewelry_price, 2) }} บาท</h6>
                    </div>
                </a>
            </div>
        </div>

        <!-- Add a new row after every 4 items -->
        @if (($index + 1) % 4 == 0)
    </div>
    <div class="row mt-4">
        @endif
        @endforeach
    </div>
</div>

</body>
@endsection