
<canvas id="myChart" height="500"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    function getMonthName(monthNumber) {
        const date = new Date();
        date.setMonth(monthNumber - 1);

        return date.toLocaleString('id-ID', { month: 'long' });
    }
    var orderByMonth = @json($orderByMonth);

    var color  = [
        "#007baf",
        "#007bbf",
        "#007bcf",
        "#007bdf",
        "#007bef",
        "#007bff",
        "#007caf",
        "#007cbf",
        "#007ccf",
        "#007cdf",
        "#007cef",
        "#007cff",
    ];

    
    $(function () {
        
            var dataSet = [];
        
            for (let i = 0; i < 12; i++) {
                month = getMonthName(i+1);
                dataSet[i] = {
                    label: month,
                    data: orderByMonth[i+1] === undefined ? [0] : [orderByMonth[i+1].price] ,
                    borderWidth: 0,
                    backgroundColor: color[i]
                }
                
            }
        
        config = {
            type: 'bar',
            data: {
                labels: ['Pendapatan'],
                datasets: dataSet
            },
            options: {
                // responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
                // barPercentage: 0.9, // set the width of the bars
                // categoryPercentage:0.3, // set the width of the bars
            }
        }

        console.log(config);

        var ctx = document.getElementById("myChart").getContext('2d');
        new Chart(ctx, config);
    });
    </script>