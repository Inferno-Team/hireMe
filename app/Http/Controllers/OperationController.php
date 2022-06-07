<?php

namespace App\Http\Controllers;

use App\Models\CompanyPositions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OperationController extends Controller
{
    public function search(Request $request)
    {
        // [ name , location ]
        $location = isset($request->location) ? $request->location : '%';
        $title = isset($request->name) ? "%$request->name%" : '%';
        $pos = CompanyPositions::where('postion_name', 'like', $title)
            ->where('location', $location)->get();
        return response()->json($pos, 200);
    }
    public function filter(Request $request)
    {
        // [ salary_low , location , job_role , postion_name , job_level ,
        // experience , salary_big , remote ]
        $salary_low = isset($request->salary_low) ? $request->salary_low : 0;
        $salary_high = isset($request->salary_high) ? $request->salary_high : PHP_INT_MAX;

        $location = isset($request->location) ? "%$request->location%" : '%';
        $postion_name = isset($request->postion_name) ? "%$request->postion_name%" : '%';
        $experience = isset($request->experience) ? $request->experience : '0';
        $job_level = isset($request->job_level) ? $request->job_level : '%';
        $remote = isset($request->remotly) ? $request->remotly : 'false';

        $pos = CompanyPositions::where('postion_name', 'like', $postion_name)
            ->whereBetween('salary', [$salary_low, $salary_high])
            ->where('location', 'like',  $location)
            ->where('experience', '>=',  $experience)
            ->where('job_level', 'like',  $job_level)
            ->where('remote', $remote)
            ->with('company')
            ->get();
        return response()->json($pos, 200);
    }
    public function getAllPositions()
    {
        $pos = CompanyPositions::with('company')->get();
        return response()->json($pos, 200);
    }
}
