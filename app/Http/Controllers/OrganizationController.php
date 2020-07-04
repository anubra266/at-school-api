<?php

namespace App\Http\Controllers;

use App\Role;
use App\Organization;
use Illuminate\Http\Request;
use Faker\Generator as Faker;

/**
 * @return \App\Models\User|null;
 */
class OrganizationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $organizations = Organization::where('user_id',$user->id)->get();
        $organizations->load('environs');
        return response()->json($organizations);
    }
    public function store(Request $request, Faker $faker)
    {
        $first_time = $request->first_time;
        $organization  = $request->validate([
            'name' => 'required',
            'address' => 'required'
        ]);
        //*create organization code
        $organization['code'] = 'ORG-' . ($faker->numberBetween(10000, 90000) . '-' . $faker->numberBetween(60000, 99000));
        //*get authenticated user
        $user = auth()->user();
        //*add organization to database
        $new_organization = $user->organizations()->create($organization);
        //*get old and new roles
        $orgadmin_role = Role::where('role', 'orgadmin')->first();
        $orgadmin_role_id = $orgadmin_role->id;
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
            $user->roles()->attach([$orgadmin_role_id, $dephead_role_id,$educator_role_id,$student_role_id]);
            //*remove newbie role
            $user->roles()->detach($new_role_id);
        }
        event(new \App\Events\UpdateOrganizations()); 
        return response()->json($new_organization);
    }
}
