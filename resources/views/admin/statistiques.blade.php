@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <div class="grid gap-6 md:grid-cols-2">
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:border-blue-500 transition-all">
                <h3 class="text-sm font-bold text-gray-950 uppercase mb-4">New User Registrations</h3>
                <div class="relative h-[250px]"><canvas id="userChart"></canvas></div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:border-blue-500 transition-all">
                <h3 class="text-sm font-bold text-gray-950 uppercase mb-4">Monthly Expenses Logged</h3>
                <div class="relative h-[250px]"><canvas id="activityChart"></canvas></div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:border-green-500 transition-all">
                <h3 class="text-sm font-bold text-gray-950 uppercase mb-4">Colocation Health</h3>
                <div class="relative h-[250px]"><canvas id="statusDistributionChart"></canvas></div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:border-red-500 transition-all">
                <h3 class="text-sm font-bold text-gray-950 uppercase mb-4">Banned Users Trend</h3>
                <div class="relative h-[250px] w-full">
                    <canvas id="bannedChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false
        };

        // User Chart
        new Chart(document.getElementById('userChart'), {
            type: 'line',
            data: {
                labels: @json($chartData['labels']),
                datasets: [{
                    label: 'Users',
                    data: @json($chartData['data']),
                    borderColor: '#4f46e5',
                    tension: 0.3,
                    fill: true,
                    backgroundColor: 'rgba(79, 70, 229, 0.05)'
                }]
            },
            options: chartOptions
        });

        // Activity Chart
        new Chart(document.getElementById('activityChart'), {
            type: 'bar',
            data: {
                labels: @json($extraChartData['activity_labels']),
                datasets: [{
                    label: 'Expenses',
                    data: @json($extraChartData['activity_data']),
                    backgroundColor: '#6366f1',
                    borderRadius: 4
                }]
            },
            options: chartOptions
        });

        // Status Distribution (Pie Chart)
        new Chart(document.getElementById('statusDistributionChart'), {
            type: 'pie',
            data: {
                labels: @json($statusChartData['labels']),
                datasets: [{
                    data: @json($statusChartData['data']),
                    backgroundColor: ['#10b981', '#f59e0b', '#6b7280']
                }]
            },
            options: chartOptions
        });

        new Chart(document.getElementById('bannedChart'), {
            type: 'line',
            data: {
                labels: @json($bannedChartData['labels']),
                datasets: [{
                    label: 'Banned Accounts',
                    data: @json($bannedChartData['data']),
                    borderColor: '#ef4444', // Red-500
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#ef4444'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
@endsection
