<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KPI Data</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="m-5">
        <button class="btn btn-primary" onclick="window.location.href='/'">หน้าแรก</button>
        <button class="btn btn-primary" onclick="window.location.href='/question2'">ข้อที่ 2</button>
    </div>
    <div class="container mt-5">
        <h2 class="mb-4">รายงาน Departmental KPI และ Individual KPI</h2>

        <form method="GET" action="" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="unit_name" class="form-label">เลือกหน่วยงาน</label>
                    <select name="unit_name" id="unit_name" class="form-select" onchange="this.form.submit()">
                        <option value="">ศูนย์ทั้งหมด</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->unit_name }}"
                                {{ $selectedUnit == $unit->unit_name ? 'selected' : '' }}>
                                {{ $unit->unit_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr class="align-top">
                    <th class="bg-info text-start my-auto">No</th>
                    <th class="bg-info text-start">ชื่อ</th>
                    <th class="bg-info text-start">ตำแหน่ง</th>
                    <th class="bg-info text-start">สรุปผลการดำเนินงาน KPI</th>
                    <th class="bg-info text-start">คะแนน</th>
                    <th class="bg-info text-start">น้ำหนัก</th>
                    <th class="bg-info text-start">คะแนนถ่วงน้ำหนัก</th>
                </tr>
            </thead>
            <tbody>
                @php

                @endphp
                @foreach ($kpi as $index => $data)
                    <tr>
                        <td rowspan="3">{{ $index + 1 }}</td>
                        <td rowspan="3">{{ $data->employee_name }}</td>
                        <td rowspan="3">{{ $data->position }}</td>
                        <td>ผลการดำเนินงาน Departmental KPI</td>
                        <td>{{ $data->departmental_kpi_score }}</td>
                        <td>{{ $data->departmental_kpi_weight }}%</td>
                        <td>{{ ($data->departmental_kpi_score * $data->departmental_kpi_weight) / 100 }}</td>
                    </tr>
                    <tr>
                        <td>ผลการดำเนินงาน Individual KPI</td>
                        <td>{{ $data->individual_kpi_score }}</td>
                        <td>{{ $data->individual_kpi_weight }}%</td>
                        <td>{{ ($data->individual_kpi_score * $data->individual_kpi_weight) / 100 }}</td>
                    </tr>
                    <tr>
                        <td>ผลการดำเนินงาน Total Weighted Score</td>
                        <td></td>
                        <td>100%</td>
                        <td>{{ $data->total_weighted_score }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
