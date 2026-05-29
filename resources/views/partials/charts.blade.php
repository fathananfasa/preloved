<script>

    /* ======================
       REVENUE CHART
    ====================== */

    const revenueDailyLabels   = @json($revenueDailyLabels);
    const revenueDailyData     = @json($revenueDailyData);

    const revenueWeeklyLabels  = @json($revenueWeeklyLabels);
    const revenueWeeklyData    = @json($revenueWeeklyData);

    const revenueMonthlyLabels = @json($revenueMonthlyLabels);
    const revenueMonthlyData   = @json($revenueMonthlyData);

    const revenueChart = new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: revenueDailyLabels,
            datasets: [{
                label: 'Pendapatan',
                data: revenueDailyData,
                borderColor: '#b45309',
                backgroundColor: 'rgba(180, 83, 9, 0.06)',
                borderWidth: 2,
                pointBackgroundColor: '#b45309',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    document.getElementById('revenueFilter').addEventListener('change', function () {
        const value = this.value;

        if (value === 'daily') {
            revenueChart.data.labels = revenueDailyLabels;
            revenueChart.data.datasets[0].data = revenueDailyData;
        } else if (value === 'weekly') {
            revenueChart.data.labels = revenueWeeklyLabels;
            revenueChart.data.datasets[0].data = revenueWeeklyData;
        } else if (value === 'monthly') {
            revenueChart.data.labels = revenueMonthlyLabels;
            revenueChart.data.datasets[0].data = revenueMonthlyData;
        }

        revenueChart.update();
    });

    /* ======================
       TRANSACTION CHART
    ====================== */

    const transaksiDailyLabels   = @json($transaksiDailyLabels);
    const transaksiDailyData     = @json($transaksiDailyData);

    const transaksiWeeklyLabels  = @json($transaksiWeeklyLabels);
    const transaksiWeeklyData    = @json($transaksiWeeklyData);

    const transaksiMonthlyLabels = @json($transaksiMonthlyLabels);
    const transaksiMonthlyData   = @json($transaksiMonthlyData);

    const transaksiChart = new Chart(document.getElementById('transaksiChart'), {
        type: 'bar',
        data: {
            labels: transaksiDailyLabels,
            datasets: [{
                label: 'Jumlah Transaksi',
                data: transaksiDailyData,
                backgroundColor: 'rgba(180, 83, 9, 0.12)',
                borderColor: '#b45309',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                }
            }
        }
    });

    document.getElementById('transaksiFilter').addEventListener('change', function () {
        const value = this.value;

        if (value === 'daily') {
            transaksiChart.data.labels = transaksiDailyLabels;
            transaksiChart.data.datasets[0].data = transaksiDailyData;
        } else if (value === 'weekly') {
            transaksiChart.data.labels = transaksiWeeklyLabels;
            transaksiChart.data.datasets[0].data = transaksiWeeklyData;
        } else if (value === 'monthly') {
            transaksiChart.data.labels = transaksiMonthlyLabels;
            transaksiChart.data.datasets[0].data = transaksiMonthlyData;
        }

        transaksiChart.update();
    });

    /* ======================
       VISITOR CHART
    ====================== */

    const visitorDailyLabels   = @json($visitorDailyLabels);
    const visitorDailyData     = @json($visitorDailyData);

    const visitorWeeklyLabels  = @json($visitorWeeklyLabels);
    const visitorWeeklyData    = @json($visitorWeeklyData);

    const visitorMonthlyLabels = @json($visitorMonthlyLabels);
    const visitorMonthlyData   = @json($visitorMonthlyData);

    const visitorChart = new Chart(document.getElementById('visitorChart'), {
        type: 'line',
        data: {
            labels: visitorDailyLabels,
            datasets: [{
                label: 'Jumlah Pengunjung',
                data: visitorDailyData,
                borderColor: '#b45309',
                backgroundColor: 'rgba(180, 83, 9, 0.06)',
                borderWidth: 2,
                pointBackgroundColor: '#b45309',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                }
            }
        }
    });

    document.getElementById('visitorFilter').addEventListener('change', function () {
        const value = this.value;

        if (value === 'daily') {
            visitorChart.data.labels = visitorDailyLabels;
            visitorChart.data.datasets[0].data = visitorDailyData;
        } else if (value === 'weekly') {
            visitorChart.data.labels = visitorWeeklyLabels;
            visitorChart.data.datasets[0].data = visitorWeeklyData;
        } else if (value === 'monthly') {
            visitorChart.data.labels = visitorMonthlyLabels;
            visitorChart.data.datasets[0].data = visitorMonthlyData;
        }

        visitorChart.update();
    });

</script>