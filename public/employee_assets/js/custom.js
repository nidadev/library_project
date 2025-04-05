const data = {
    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5'], // Time intervals
    datasets: [
        {
            label: 'Applications Submitted',
            data: [10, 20, 15, 25, 30], // Example application data
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            yAxisID: 'y'
        },
        {
            label: 'Interviews Scheduled',
            data: [2, 4, 3, 6, 8], // Example interview data
            borderColor: 'rgb(153, 102, 255)',
            backgroundColor: 'rgba(153, 102, 255, 0.2)',
            yAxisID: 'y1'
        }
    ]
}

const config = {
    type: 'line',
    data: data,
    options: {
        responsive: true,
        interaction: {
            mode: 'index',
            intersect: false
        },
        stacked: false,
        plugins: {
            title: {
                display: true,
                text: 'Employee Dashboard: Weekly Job Activity'
            }
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: {
                    display: true,
                    text: 'Applications Submitted'
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                grid: {
                    drawOnChartArea: false // Avoid grid overlap
                },
                title: {
                    display: true,
                    text: 'Interviews Scheduled'
                }
            }
        }
    }
}

// Render the chart
const ctx = document.getElementById('job_chart').getContext('2d')
new Chart(ctx, config)

const statusData = {
    labels: [
        'Applied',
        'In Review',
        'Interview Scheduled',
        'Hired',
        'Rejected'
    ], // Application statuses
    datasets: [
        {
            label: 'Number of Applications',
            data: [15, 8, 5, 2, 4], // Example counts for each status
            backgroundColor: [
                'rgba(75, 192, 192, 0.8)', // Applied
                'rgba(255, 206, 86, 0.8)', // In Review
                'rgba(54, 162, 235, 0.8)', // Interview Scheduled
                'rgba(75, 192, 75, 0.8)', // Hired
                'rgba(255, 99, 132, 0.8)' // Rejected
            ],
            borderColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(75, 192, 75, 1)',
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1
        }
    ]
}

const statusConfig = {
    type: 'bar',
    data: statusData,
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Job Application Status Distribution'
            },
            legend: {
                display: false // No need for a legend since data is self-explanatory
            }
        },
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Status'
                }
            },
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Number of Applications'
                }
            }
        }
    }
}

// Render the chart
const ctxStatus = document.getElementById('status_chart').getContext('2d')
new Chart(ctxStatus, statusConfig)

var ctx2 = document.getElementById('application_progress_chart').getContext('2d');
var applicationProgressChart = new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: ['Submitted', 'Under Review', 'Interview Scheduled', 'Rejected'],
        datasets: [{
            data: [10, 5, 2, 1], // Sample data
            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'],
            hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#f4b619'],
            hoverBorderColor: '#fff',
        }],
    },
    options: {
        maintainAspectRatio: false,
        responsive: true,
        
    },
});
var ctx3 = document.getElementById('skill_progress_chart').getContext('2d');
var skillProgressChart = new Chart(ctx3, {
    type: 'radar',
    data: {
        labels: ['UI Design', 'JavaScript', 'Team Collaboration', 'Communication', 'Problem Solving'],
        datasets: [{
            label: 'Skill Progress',
            data: [80, 60, 70, 90, 75], // Sample data
            backgroundColor: 'rgba(2, 117, 216, 0.2)',
            borderColor: 'rgba(2, 117, 216, 1)',
            pointBackgroundColor: 'rgba(2, 117, 216, 1)',
            pointBorderColor: '#fff',
        }],
    },
    options: {
        scales: {
            r: {
                ticks: {
                    beginAtZero: true,
                    max: 100,
                },
            },
        },
    },
});

 