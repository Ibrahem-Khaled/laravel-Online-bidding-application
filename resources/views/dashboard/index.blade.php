@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center my-4">لوحة التحكم</h1>

        <!-- الإحصائيات -->
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">إجمالي المستخدمين</h5>
                        <p class="card-text">{{ $totalUsers }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">إجمالي المزادات</h5>
                        <p class="card-text">{{ $totalAuctions }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">إجمالي البثوث المباشرة</h5>
                        <p class="card-text">{{ $totalLiveStreams }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">إجمالي الأقسام</h5>
                        <p class="card-text">{{ $totalCategories }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- الرسوم البيانية -->
        <div class="row my-5">
            <div class="col-md-6">
                <canvas id="auctionsChart"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="liveStreamsChart"></canvas>
            </div>
        </div>

        <!-- أحدث المزادات والبثوث -->
        <div class="row">
            <div class="col-md-6">
                <h3>أحدث المزادات</h3>
                <ul class="list-group">
                    @foreach ($recentAuctions as $auction)
                        <li class="list-group-item">{{ $auction->title }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-6">
                <h3>أحدث البثوث المباشرة</h3>
                <ul class="list-group">
                    @foreach ($recentLiveStreams as $stream)
                        <li class="list-group-item">{{ $stream->title }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // رسم بياني للمزادات
        const auctionsCtx = document.getElementById('auctionsChart').getContext('2d');
        const auctionsChart = new Chart(auctionsCtx, {
            type: 'bar',
            data: {
                labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
                datasets: [{
                    label: 'عدد المزادات',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // رسم بياني للبثوث المباشرة
        const liveStreamsCtx = document.getElementById('liveStreamsChart').getContext('2d');
        const liveStreamsChart = new Chart(liveStreamsCtx, {
            type: 'line',
            data: {
                labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
                datasets: [{
                    label: 'عدد البثوث المباشرة',
                    data: [5, 10, 15, 7, 12, 9],
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
