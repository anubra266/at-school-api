<?php

namespace App\Http\Controllers;

use App\Role;
use App\Environ;
use App\Classroom;
use Illuminate\Http\Request;
use Faker\Generator as Faker;


class ClassroomController extends Controller
{

    public function index() 
    {
        $user = auth()->user();
        $classrooms = Classroom::where('user_id',$user->id)->get();
        $classrooms->load('users');
        return response()->json($classrooms);
    }
    public function studindex()
    {
        $user = auth()->user();
        $classrooms = $user->classrooms()->get();
        $classrooms->load('users');
        return response()->json($classrooms);
    }
    public function store(Request $request, Faker $faker)
    {

        //?get all the inputs
        $first_time = $request->first_time;
        $environ_code = $request->code;

        $classroom = $request->validate([
            'name' => 'required'
        ]);

        //?check environ code
        $environ = Environ::where('code', $environ_code)->first();
        if ($environ === null) {
            return response()->json(['message' => 'Invalid Environ Code!'], 403);
        }
        //?create classroom code
        $classroom['code'] = 'CRM-' . ($faker->numberBetween(10000, 90000) . '-' . $faker->numberBetween(60000, 99000));
        //?create classroom slugurl
        $classroom['slug'] = 'CRM-' . $environ->id . '-' . ($faker->slug);
        //?signify classroom creator
        $user = auth()->user();
        $classroom['user_id'] = $user->id;
        //*add classroom to database
        $classroom = $environ->classrooms()->create($classroom);
        //*get old and new roles
        $educator_role = Role::where('role', 'educator')->first();
        $educator_role_id = $educator_role->id;
        $student_role = Role::where('role', 'student')->first();
        $student_role_id = $student_role->id;

        $new_role = Role::where('role', 'new')->first();
        $new_role_id = $new_role->id;
        if ($first_time) {
            //*assign orgadmin role
            $user->roles()->attach([$educator_role_id, $student_role_id]);
            //*remove newbie role
            $user->roles()->detach($new_role_id);
        }
        event(new \App\Events\UpdateClassrooms()); 
        event(new \App\Events\UpdateEnvirons()); 
        return response()->json($classroom);
    }

    public function join(Request $request)
    {

        //?get all the inputs
        $first_time = $request->first_time;
        $classroom_code = $request->validate([
            'code' => 'required'
        ]);
        //?get the classroom with given code
        $classroom = Classroom::where('code', $classroom_code)->first();
        if ($classroom === null) {
            return response()->json(['message' => 'Invalid Classroom Code!'], 403);
        }
        //?Check if user is already a member of the class
        $user_id = auth()->user()->id;

        $classroom_users = $classroom->users()->pluck('user_id');
        if ($classroom_users->contains($user_id)) {
            return response()->json(['message' => 'You are a member of this Class!'], 403);
        }
        //?Check if user created the class
        if ($classroom->user_id === $user_id) {
            return response()->json("You created this class, So you're in!", 200);
        }
        //* Add user to Classroom
        $user = auth()->user();
        $classroom->users()->attach($user->id);

        //*get old and new roles
        $student_role = Role::where('role', 'student')->first();
        $student_role_id = $student_role->id;

        $new_role = Role::where('role', 'new')->first();
        $new_role_id = $new_role->id;
        if ($first_time) {
            //*assign student role
            $user->roles()->attach($student_role_id);
            //*remove newbie role
            $user->roles()->detach($new_role_id);
        }
        event(new \App\Events\UpdateClasses()); 
        event(new \App\Events\UpdateClassrooms()); 
        $success_message = "You've been added to " . $classroom->name . " Classroom successfully!";
        return response()->json($success_message, 200);
    }

    public function role(Request $request){
        $slug = $request->slug;
        $classroom = Classroom::where('slug',$slug)->first();
        $classroom_educator = $classroom->user_id;
        $user_id = auth()->user()->id;
        if($classroom_educator==$user_id){
            return response()->json(true);
        }
        return response()->json(false);

    }
    public function check(Request $request)
    {

        $slug = $request->slug;

        $classroom = Classroom::where('slug',$slug)->first();
        $user_id = auth()->user()->id;
        $classroom_users = $classroom->users()->pluck('user_id');

        if ($classroom_users->contains($user_id)) {
            return response()->json('member');
        } elseif ($classroom->user_id == auth()->user()->id) {
            return response()->json('creator');
        } else {
            return response()->json('alien');
        }
    }
}
