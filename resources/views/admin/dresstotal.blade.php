@extends('layouts.adminlayout')
@section('content')
    <html>

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

        
      

      
      
        


        

        



        <!-- ถ้าต้องการควบคุมสีมากขึ้น สามารถเพิ่ม CSS แบบนี้ -->
       







        <div class="container mt-4">
            <div class="col">
                <h1 class="font-bold">ประเภทชุด</h1>

                เลือกประเภทชุดเพื่อดูชุดทั้งหมด
            </div>
        </div>

        <div class="container mt-5">
            <div class="row">
                @foreach ($showtype as $index => $item)
                    @php
                        $dress = App\Models\Dress::where('type_dress_id', $item->id)->get();
                    @endphp

                    @if ($dress->count() > 0)
                        <div class="col-md-3 mb-4">
                            <div class="card text-left custom-card">
                                <button type="button" class="btn p-0" style="border: none; background: none;"
                                    onclick="window.location='{{ route('admin.typedress', ['id' => $item->id]) }}'">
                                    @php
                                        $type_id = $item->id;
                                        $dress_id = App\Models\Dress::where('type_dress_id', $type_id)->value('id');
                                        $image_show = App\Models\Dressimage::where('dress_id', $dress_id)->first();
                                    @endphp
                                        <img src="{{ asset($image_show->dress_image) }}" alt=""
                                        class="card-img-top custom-img">

                                </button>
                                <div class="card-body">
                                    <h5 class="card-title text-center">{{ $item->type_dress_name }}</h5>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (($index + 1) % 4 == 0)
            </div>
            <div class="row">
                @endif
                @endforeach
            </div>
        </div>
    </body>
@endsection
