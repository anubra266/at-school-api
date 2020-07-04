<?php

namespace App\Http\Controllers;

use App\Role;
use App\Environ;
use App\Organization;
use Illuminate\Http\Request;
use Faker\Generator as Faker;

class EnvironController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        $environs = Environ::where('user_id',$user->id)->get();
        $environs->load('classrooms');
        return response()->json($environs);
    }
    public function store(Request $request, Faker $faker)
    {
        //?get all the inputs
        $first_time = $request->first_time;
        $organization_code = $request->code;

        $environ = $request->validate([
            'name' => 'required'
        ]);

        //?check organization code
        $organization = Organization::where('code', $organization_code)->first();
        if ($organization === null) {
            return response()->json(['message' => 'Invalid Organization Code!'], 403);
        }
        //?create environ code
        $environ['code'] = 'ENV-'.($faker->numberBetween(10000, 90000) . '-' . $faker->numberBetween(60000, 99000));
        //?signify environ creator
        $user= auth()->user();
        $environ['user_id'] = $user->id;
        //*add environ to database
        $environ = $organization->environs()->create($environ);
        //*get old and new roles
        $dephead_role = Role::where('role', 'dephead')->first();
        $dephead_role_id = $dephead_role->id;
        $educator_role = Role::where('role', 'educator')->first();
        $educator_role_id = $educator_role->id;
        $student_role = Role::where('role', 'student')->first();
        $student_role_id = $student_role->id;

        $new_role = Role::where('role', 'new')->first();
        $new_role_id = $new_role->id;
        if ($first_time) {
            //*assign orgadmin role
            $user->roles()->attach([$dephead_role_id,$educator_role_id,$student_role_id]);
            //*remove newbie role
            $user->roles()->detach($new_role_id);
        }
        event(new \App\Events\UpdateEnvirons()); 
        event(new \App\Events\UpdateOrganizations()); 
        return response()->json($environ);
    }
}
