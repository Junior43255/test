<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Kpi extends Controller
{
    public function index(Request $request)
    {
        // Fetch all unique unit names for the dropdown
        $units = DB::table('employee_kpis')->select('unit_name')->distinct()->get();

        // Get the selected unit from the request, if any
        $selectedUnit = $request->input('unit_name');

        // Fetch KPI data filtered by the selected unit, if chosen
        $query = DB::table('employee_kpis');
        if ($selectedUnit) {
            $query->where('unit_name', $selectedUnit);
        }
        $kpi = $query->orderBy('total_weighted_score', 'desc')->get();

        return view('kpi', compact('kpi', 'units', 'selectedUnit'));
    }

    public function normal_distribution(Request $request)
    {

        $units = DB::table('employee_kpis')->select('unit_name')->distinct()->get();

        $selectedUnit = $request->input('unit_name');

        $query = DB::table('employee_kpis');
        if ($selectedUnit) {
            $query->whereIn('unit_name', $selectedUnit);
        } else {
            $selectedUnit = $units->pluck('unit_name')->toArray();
        }
        $stddev = $query->selectRaw('STDDEV(total_weighted_score) as stddev')->get();

        $averageScore = $query->avg('total_weighted_score');
        $sdScore = $stddev[0]->stddev;


        $employees = $query
            ->select('*')
            ->get()
            ->map(function ($employee) use ($averageScore, $sdScore) {
                $score = $employee->total_weighted_score;

                if ($score >= $averageScore + 2 * $sdScore) {
                    $employee->grade = 'A+';
                } elseif ($score >= $averageScore + $sdScore) {
                    $employee->grade = 'A';
                } elseif ($score >= $averageScore) {
                    $employee->grade = 'B';
                } elseif ($score >= $averageScore - $sdScore) {
                    $employee->grade = 'C';
                } elseif ($score >= $averageScore - 2 * $sdScore) {
                    $employee->grade = 'D';
                } else {
                    $employee->grade = 'F';
                }

                return $employee;
            });

        $gradeCounts = [
            'A+' => $employees->where('grade', 'A+')->count(),
            'A' => $employees->where('grade', 'A')->count(),
            'B' => $employees->where('grade', 'B')->count(),
            'C' => $employees->where('grade', 'C')->count(),
            'D' => $employees->where('grade', 'D')->count(),
            'F' => $employees->where('grade', 'F')->count(),
        ];


    $xValues = [];
    $yValues = [];
    for ($x = $averageScore - 4 * round($sdScore, 2); $x <= $averageScore + 4 * round($sdScore, 2); $x += 0.01) {
        $xValues[] = round($x, 2);
        $yValues[] = (1 / (round($sdScore, 2) * sqrt(2 * M_PI))) * exp(-0.5 * pow(($x - $averageScore) / round($sdScore, 2), 2));
    }
        return view('normal_distribution', compact('averageScore', 'sdScore', 'employees', 'gradeCounts', 'units', 'selectedUnit', 'xValues', 'yValues'));
    }
}
