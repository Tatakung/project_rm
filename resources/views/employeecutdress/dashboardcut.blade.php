@extends('layouts.adminlayout')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <p>Pie Charts</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div style="width: 100%; margin: auto;">
                    <canvas id="pieChart"></canvas>
                </div>

            </div>
        </div>
    </div>

    <script>
        var ctx = document.getElementById('pieChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: @json($labels),
                datasets: [{
                    data: @json($data),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                    ],
                    borderWidth: 1
                }]
            },
        });
    </script>
@endsection
