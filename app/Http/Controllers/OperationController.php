<?php

namespace App\Http\Controllers;

use App\Models\CompanyApplications;
use App\Models\CompanyPositions;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OperationController extends Controller
{
    public function search(Request $request)
    {
        // [ name , location ]
        $location = isset($request->location) ? $request->location : '%';
        $title = isset($request->title) ? "%$request->title%" : '%';
        $pos = CompanyPositions::where('postion_name', 'like', $title)
            ->where('location', 'like', $location)->with("company")->get();
        info($pos);
        return response()->json($pos, 200);
    }
    public function filter(Request $request)
    {
        // [ salary_low , location , job_role , postion_name , job_level ,
        // experience , salary_big , remote ]
        info($request);
        $salary_low = isset($request->salary_low) ? $request->salary_low : 0;
        $salary_high = isset($request->salary_high) ? $request->salary_high : PHP_INT_MAX;

        $location = isset($request->location) ? "%$request->location%" : '%';
        $postion_name = isset($request->postion_name) ? "%$request->postion_name%" : '%';
        $experience = isset($request->experience) ? $request->experience : '0';
        $job_level = isset($request->job_level) ? $request->job_level : '%';
        $pos = [];

        if (isset($request->remotly)) {
            $pos = CompanyPositions::where('postion_name', 'like', $postion_name)
                ->whereBetween('salary', [$salary_low, $salary_high])
                ->where('location', 'like',  $location)
                ->where('experience', '>=',  $experience)
                ->where('job_level', 'like',  $job_level)
                ->where('remote', $request->remotly)
                ->with('company', 'applications.user')
                ->get();
        } else {
            $pos = CompanyPositions::where('postion_name', 'like', $postion_name)
                ->whereBetween('salary', [$salary_low, $salary_high])
                ->where('location', 'like',  $location)
                ->where('experience', '>=',  $experience)
                ->where('job_level', 'like',  $job_level)
                ->with('company', 'applications.user')
                ->get();
        }
        $user = Auth::user();
        info($pos);
        $pos = $pos->filter(function ($p) use ($user) {
            $apps = $p->applications;
            foreach ($apps as $app) {
                if ($app->user->id == $user->id) {
                    $app->isMine = true;
                }
            }
            return true;
        });
        return response()->json($pos, 200);
    }
    public function getAllPositions()
    {
        $pos = CompanyPositions::with('company', 'applications.user')->get();
        $user = Auth::user();
        $pos = $pos->filter(function ($p)  use ($user) {
            $apps = $p->applications;
            foreach ($apps as $app) {
                if ($app->user->id == $user->id) {
                    $app->isMine = true;
                }
            }
            return true;
        });
        return response()->json($pos, 200);
    }
    public function applyJob(Request $request)
    {
        $user = Auth::user();
        $pos = CompanyPositions::where('id', $request->id)->with('company')->first();
        $application = CompanyApplications::create([
            'user_id' => $user->id,
            'position_id' => $request->id,
            'cv_file' => ''
        ]);
        if ($request->hasFile('cv')) {
            $cv = $request->file('cv');
            $ex = $cv->getClientOriginalExtension();
            $time = time();
            $name = $time . '.' . $ex;

            $compName = $pos->company->name;
            $cv->move(public_path("cv/$compName"), $name);
            // $cv->storeAs("public/cv/$compName", $name);
            $application->cv_file = "$compName/" . $name;
            $application->save();
        }
        info($application);
        return response()->json([
            'code' => 200,
            'msg' => 'applyed successfully'
        ], 200);
    }
    public function myAccount()
    {
        return response()->json(Auth::user(), 200);
    }
    public function updateAccount(Request $request)
    {
        $user = User::find($request->id);
        $user->fill($request->all())->save();
        return response()->json($user, 200);
    }
    public function loadMyJobs(Request $request)
    {
        $user = Auth::user();
        $user = User::where('id', $user->id)->with('applications.position.company')->first();

        $pos = [];
        foreach ($user->applications as $app) {
            info($app);
            array_push($pos, $app->position);
        }
        return response()->json($pos, 200);
    }
}
