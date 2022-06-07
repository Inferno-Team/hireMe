<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyApplications;
use App\Models\CompanyPositions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function addCompany(Request $request)
    {
        // [ name , description , file(logo) ]
        $user = Auth::user();
        $company = Company::where('user_id', $user->id)->first();
        if (isset($company))
            return response([
                'code' => 401,
                'msg' => "you can'tcreate multiple companies"
            ], 200);
        $company = Company::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'description' => $request->description,
        ]);
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $extension = $logo->getClientOriginalExtension();
            $name = time() . '.' . $extension;
            $logo->storeAs('public/logo', $name);
            $company->logo_url = "/storage/logo/$name";
            $company->save();
        }
        return response()->json([
            'msg' => 'added Successfully',
            'code' => 200
        ], 200);
    }
    public function addPosition(Request $request)
    {
        // [ 'location','job_role','postion_name','job_level',
        // 'experience','salary','remote']
        $user = Auth::user();
        if (!isset($user->company))
            return response()->json([
                'code' => 401,
                'msg' => 'you don\'t have a company'
            ], 200);
        $position = CompanyPositions::create([
            'company_id' => $user->company->id,
            'postion_name' => $request->name,
            'location' => $request->location,
            'job_role' => $request->job_role,
            'job_level' => $request->job_level,
            'experience' => $request->experience,
            'salary' => $request->salary,
            'remote' => $request->remote
        ]);
        return response()->json([
            'code' => 200,
            'msg' => 'added Successfully',
            'position' => $position
        ], 200);
    }
    public function positionApplications(int $id)
    {
        // check if this user has company
        $user = Auth::user();
        if (!isset($user->company))
            return response()->json([
                'code' => 404,
                'msg' => 'you have no company'
            ], 200);
        // check if this position is not null and if it is belongs to this user company
        $pos = CompanyPositions::find($id);
        if (!isset($pos))
            return response()->json([
                'code' => 404,
                'msg' => 'position not found',
            ], 200);
        if ($pos->company_id != $user->company->id)
            return response()->json([
                'code' => 404,
                'msg' => "this position dosn't belongs to your company"
            ], 200);
        $applications = CompanyApplications::where('position_id', $pos->id)
            ->with('user')->get();
        return response()->json([
            'code' => 200,
            'msg' => 'positions',
            'applications' => []
        ], 200);
    }
    public function applicationUser(int $id)
    {
        $application = CompanyApplications::where('id', $id)->with('user')->first();
        return response()->json($application->user, 200);
    }
    public function getAllCompanies()
    {
        $companies = Company::get()->map->reshape();
        return response()->json($companies, 200);
    }
    public function getCompanyPositions(int $id)
    {
        $positions = CompanyPositions::where('company_id', $id)->with('company')->get();
        return response()->json($positions, 200);
    }
}
