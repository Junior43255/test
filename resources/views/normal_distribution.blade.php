<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Normal Distribution</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="m-5">
        <button class="btn btn-primary" onclick="window.location.href='/'">หน้าแรก</button>
        <button class="btn btn-primary" onclick="window.location.href='/question1'">ข้อที่ 1</button>
    </div>
    <div class="container mt-5">
        <h2>Normal Distribution</h2>
        <p>คะแนนเฉลี่ย (X): {{ $averageScore }}</p>
        <p>ส่วนเบี่ยงเบนมาตรฐาน (SD): {{ $sdScore }}</p>

        <h3>กลุ่ม</h3>

        <form method="GET" action="" class="mb-4">
            @foreach ($units as $unit)
                <div class="form-check mb-3">
                    <input class="form-check-input" type='checkbox' name='unit_name[]' value='{{ $unit->unit_name }}'
                        {{ in_array($unit->unit_name, $selectedUnit) ? 'checked' : '' }} />
                    <label class="form-check-label">{{ $unit->unit_name }}</label>
                </div>
            @endforeach
            <button type="submit" class="btn btn-primary">ตกลง</button>
        </form>

        <div style="width: 80%; margin: auto;" class="mt-5">
            <h4>กราฟแสดงแบบ Normal Curve Distribution</h4>
            <canvas id="normalDistributionChart"></canvas>
        </div>

        <div class="mt-5" style="width: 80%; margin: auto;">
            <h4>ตารางการแบ่งกลุ่ม</h4>
            <table class="table table-bordered border-black w-50 mt-3">
                <thead align="center">
                    <tr>
                        <th class="bg-warning">Grade</th>
                        <th class="bg-warning">ระดับคะแนน</th>
                        <th class="bg-warning">จำนวน</th>
                    </tr>
                </thead>
                <tbody align="center">
                    <tr>
                        <td>A+</td>
                        <td>{{ '≥ ' . number_format($averageScore + 2 * $sdScore, 2) }}</td>
                        <td>{{ $gradeCounts['A+'] }}</td>
                    </tr>
                    <tr>
                        <td>A</td>
                        <td>{{ number_format($averageScore + $sdScore, 2) . ' - < ' . number_format($averageScore + 2 * $sdScore, 2) }}
                        </td>
                        <td>{{ $gradeCounts['A'] }}</td>
                    </tr>
                    <tr>
                        <td>B</td>
                        <td>{{ number_format($averageScore, 2) . ' - < ' . number_format($averageScore + $sdScore, 2) }}
                        </td>
                        <td>{{ $gradeCounts['B'] }}</td>
                    </tr>
                    <tr>
                        <td>C</td>
                        <td>{{ number_format($averageScore - $sdScore, 2) . ' - < ' . number_format($averageScore, 2) }}
                        </td>
                        <td>{{ $gradeCounts['C'] }}</td>
                    </tr>
                    <tr>
                        <td>D</td>

                        <td>{{ number_format($averageScore - 2 * $sdScore, 2) . ' - < ' . number_format($averageScore - $sdScore, 2) }}
                        </td>
                        <td>{{ $gradeCounts['D'] }}</td>
                    </tr>
                    <tr>
                        <td>F</td>
                        <td>{{ ' < ' . number_format($averageScore - 2 * $sdScore, 2) }}</td>
                        <td>{{ $gradeCounts['F'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="width: 80%; margin: auto;" class="mt-5">
            <h4>กราฟแสดงจำนวนคนตาม Grade</h4>
            <canvas id="rankDistributionChart"></canvas>
        </div>
        <div class="my-5">
            <h4>ตารางข้อมูลพนักงาน</h4>
            <table class="table table-bordered border-black text-center">
                <thead>
                    <tr>
                        <th rowspan="2" class="bg-info">รายชื่อ</th>
                        <th rowspan="2" class="bg-info">ตําแหน่ง</th>
                        <th rowspan="2" class="bg-info">หน่วยงาน</th>
                        <th colspan="2" class="bg-info">คะแนน Departmental KPI</th>
                        <th colspan="2" class="bg-info">คะแนน Individual KPI</th>
                        <th rowspan="2" class="bg-info">คะแนน รวมตามน้ำหนัก</th>
                        <th rowspan="2" class="bg-info">grade ตาม bell curve</th>
                    </tr>
                    <tr>
                        <th class="bg-info">คะแนน</th>
                        <th class="bg-info">น้ำหนัก</th>
                        <th class="bg-info">คะแนน</th>
                        <th class="bg-info">น้ำหนัก</th>
                    </tr>
                </thead>
                <tbody align="center">
                    @foreach ($employees as $employee)
                        <tr>
                            <td>{{ $employee->employee_name }}</td>
                            <td>{{ $employee->position }}</td>
                            <td>{{ $employee->unit_name }}</td>
                            <td>{{ $employee->departmental_kpi_score }}</td>
                            <td>{{ $employee->departmental_kpi_weight }}%</td>
                            <td>{{ $employee->individual_kpi_score }}</td>
                            <td>{{ $employee->individual_kpi_weight }}%</td>
                            <td>{{ $employee->total_weighted_score }}</td>
                            <td>{{ $employee->grade }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
<script>
    const xValues = @json($xValues);
    const yValues = @json($yValues);
    const mean = {{ $averageScore }};

    const ctx = document.getElementById('normalDistributionChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: xValues,
            datasets: [{
                    label: 'Normal Distribution',
                    data: yValues,
                    borderColor: 'blue',
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    fill: true,
                    pointRadius: 0,
                    borderWidth: 2,
                    tension: 0.4
                },
                {
                    label: 'ค่าเฉลี่ย',
                    data: yValues.map((y, i) => (xValues[i]) == mean.toFixed(2) ? y : 0),
                    borderColor: 'red',
                    borderWidth: 2,
                    type: 'line',
                    pointRadius: 0,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Normal Distribution Curve'
                }
            },
            scales: {
                x: {
                    min: Math.min(...xValues),
                    max: Math.max(...xValues),
                },
                y: {
                    min: 0,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            elements: {
                line: {
                    fill: '-1'
                }
            }
        }
    });

    const labels = @json(array_keys($gradeCounts));
    const data = @json(array_values($gradeCounts));

    const rankDistributionChart = document.getElementById('rankDistributionChart').getContext('2d');
    new Chart(rankDistributionChart, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'จำนวนคน',
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'คน'
                    },
                    ticks: {
                        stepSize: 1
                    },
                    max: Math.max(...data) + 1
                },
                x: {
                    title: {
                        display: true,
                        text: 'Grade'
                    }
                }
            },
            plugins: {
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    color: 'white',
                    font: {
                        weight: 'bold'
                    },
                    formatter: Math.round
                }
            }
        }
    });
</script>

</html>
