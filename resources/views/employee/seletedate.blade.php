{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จองชุด</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

</head>

<body>
    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <label for="" class="form-label">เลือกวันที่เช่าชุด</label>
                <input class="form-control" type="text" id="datepicker">
                <input type="text" id="startDate" name="startDate">
                <input type="text" id="endDate" name="endDate">

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded',function(){
            var valuestartdate = document.getElementById('startDate') ; 
            var valueenddate = document.getElementById('endDate') ; 
            flatpickr('#datepicker',{
                dateFormat : 'Y-m-d' ,
                locale : 'th' , 
                minDate : 'today' , 
                mode : 'range' , 
                maxDate : new Date().fp_incr(10),
                onChange : function(array,str,instance){

                    if(array.length === 2){
                        var stardate = array[0] ; 
                        var enddate = array[1] ; 
                        




                        // แปลงobjectเป็น date
                        var strstartdate = instance.formatDate(stardate,'Y-m-d') ; 
                        var strenddate = instance.formatDate(enddate,'Y-m-d') ; 
                        // กำหนด value ให้ช่อง hidden 
                        valuestartdate.value = strstartdate ; 
                        valueenddate.value = strenddate ; 
                    }

                }
            }) ; 
        }) ; 
    </script>
</body>

</html> --}}

@extends('layouts.adminlayout')
@section('content')
    <style>
        .container {
            margin-top: 50px;
        }

        .row {
            background-color: #A7545E;
            padding: 20px;
            border-radius: 8px;
            color: #fff;
        }

        .form-label {
            font-size: 30px;
            font-weight: bold;
        }

        .form-control {
            width: 300px;
            height: 40px;
            display: block;
            margin: auto;
            padding-right: 40px;
            /* Add space for the icon */
            position: relative;
        }

        .form-control-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: #A7545E;
        }

        .btn-custom {
            background-color: #fff;
            color: #A7545E;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
            cursor: pointer;
        }

        .btn-custom:hover {
            background-color: #f5f5f5;
        }
    </style>
    <div class="container">
        <form action="{{route('employee.selectdatesuccess')}}" method="GET">
            @csrf
        <div class="row">
            <div class="col-md-12 text-center">
                <p style="font-size: 20px;">เลือกวันเช่าชุด - วันคืนชุด</p>
            </div>
                <div class="col-md-12 d-flex justify-content-center align-items-center position-relative">
                    <input class="form-control" type="text" id="datepicker">
                    <i class="bi bi-calendar"></i>
                    <input type="hidden" id="startDate" name="startDate">
                    <input type="hidden" id="endDate" name="endDate">
                    <input type="hidden" id="totalDay" name="totalDay">

                </div>
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn-custom">เลือก</button>
                </div>
        </div>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var valuestartdate = document.getElementById('startDate');
            var valueenddate = document.getElementById('endDate');
            var totalDay = document.getElementById('totalDay') ; 
            flatpickr('#datepicker', {
                dateFormat: 'Y-m-d',
                locale: 'th',
                minDate: 'today',
                mode: 'range',
                maxDate: new Date().fp_incr(30),
                onChange: function(array, str, instance) {

                    if (array.length === 2) {
                        var stardate = array[0];
                        var enddate = array[1];

                        // แปลงobjectเป็น date
                        var strstartdate = instance.formatDate(stardate, 'Y-m-d');
                        var strenddate = instance.formatDate(enddate, 'Y-m-d');
                        // กำหนด value ให้ช่อง hidden 
                        valuestartdate.value = strstartdate;
                        valueenddate.value = strenddate;

                        //ตรวจสอบว่าเป็นกี่วัน 
                        var count_day = enddate-stardate  ;
                        var totalday = Math.ceil(count_day / (1000 * 60 * 60 *24)) ; 
                        totalDay.value = totalday ; 
                      

                        
                        



                    }

                }
            });
        });
    </script>

    {{-- <div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <label for="" class="form-label">เลือกวันที่เช่าชุด</label>
            <input class="form-control" type="text" id="datepicker">
            <input type="text" id="startDate" name="startDate">
            <input type="text" id="endDate" name="endDate">

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded',function(){
        var valuestartdate = document.getElementById('startDate') ; 
        var valueenddate = document.getElementById('endDate') ; 
        flatpickr('#datepicker',{
            dateFormat : 'Y-m-d' ,
            locale : 'th' , 
            minDate : 'today' , 
            mode : 'range' , 
            maxDate : new Date().fp_incr(10),
            onChange : function(array,str,instance){

                if(array.length === 2){
                    var stardate = array[0] ; 
                    var enddate = array[1] ; 
                    




                    // แปลงobjectเป็น date
                    var strstartdate = instance.formatDate(stardate,'Y-m-d') ; 
                    var strenddate = instance.formatDate(enddate,'Y-m-d') ; 
                    // กำหนด value ให้ช่อง hidden 
                    valuestartdate.value = strstartdate ; 
                    valueenddate.value = strenddate ; 
                }

            }
        }) ; 
    }) ; 
</script> --}}
@endsection
