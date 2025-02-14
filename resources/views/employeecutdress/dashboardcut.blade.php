@extends('layouts.adminlayout')

@section('content')
    <!-- โหลด Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- <canvas id="myChart" width="400" height="200"></canvas>

    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var list_type = @json($list_type);
        var list_count_type = @json($list_count_type_values);
        var myChart = new Chart(ctx, {
            // type: 'bar',
            // type: 'doughnut',
            type: 'pie',


            data: {
                labels: list_type,


                // datasets: [{
                //     label: 'จำนวนครั้ง (ครั้ง)',
                //     data: list_count_type,
                //     backgroundColor: 'rgba(54, 162, 235, 0.5)',
                //     borderColor: 'rgba(54, 162, 235, 1)',
                //     borderWidth: 1
                // }]

                datasets: [{
                    label: 'My First Dataset',
                    data: list_count_type,
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)'
                    ],
                    hoverOffset: 4
                }]

            }


        });
    </script> --}}

    <div class="container">
        <h2>รายรับ - รายจ่าย รายเดือน</h2>
        <canvas id="barChart"></canvas>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('barChart').getContext('2d');

            const labels = @json($list_one) ; 
            const list_two = @json($list_two) ; 
            const list_three = @json($list_three) ; 




            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'รายรับ',
                            data: list_two,
                            backgroundColor: 'rgba(75, 192, 192, 0.6)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'รายจ่าย',
                            data: list_three,
                            backgroundColor: 'rgba(255, 99, 132, 0.6)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>


    
@endsection
