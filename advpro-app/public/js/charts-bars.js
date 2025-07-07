document.addEventListener('DOMContentLoaded', function() {
    const barsCtx = document.getElementById('bars');
    
    if (barsCtx) {
        // Obtener datos del DOM
        const chartData = {
            labels: JSON.parse(barsCtx.dataset.labels || '["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]'),
            data: JSON.parse(barsCtx.dataset.data || '["2", "3", "5", "4", "6", "7", "8", "9", "10", "11", "12", "13"]')
        };

        const barConfig = {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Contratos por Mes',
                    backgroundColor: '#0694a2',
                    borderWidth: 1,
                    data: chartData.data,
                }]
            },
            options: {
                responsive: true,
                legend: {
                    display: false,
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        };

        window.myBar = new Chart(barsCtx, barConfig);
    }
});