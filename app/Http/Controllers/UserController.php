<?php

namespace App\Http\Controllers;

use App\User;
use App\Classroom;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function authed(Request $request){
        $user =  $request->user();
        $user->load('roles');
        return response()->json($user);
    }
    public function show(Request $request, $userId)
    {
        $user = User::find($userId);

        if($user) {
            return response()->json($user);
        }

        return response()->json(['message' => 'User not found!'], 404);
    }
    public function info(){
        $user = auth()->user();

        $students = 0;
        $testscreated = 0;
        $classroomsj = $user->classrooms()->get();
        $theorytestsdone = $user->theoryresults()->get();
        $objectivetestsdone = $user->cbts()->get();
        $classroomsc = Classroom::where('user_id',$user->id)->get();
        for($i=0;$i<count($classroomsc);$i++){
            $classroom = $classroomsc[$i];
            $members = $classroom->users()->get();
            $objectivetests = $classroom->objectivetests()->get();
            $theorytests = $classroom->theorytests()->get();
            $students+=count($members);
            $testscreated+=count($objectivetests);
            $testscreated+=count($theorytests);
        }
        $environs = $user->environs()->get();
        $environEducators = 0;
        $environStudents = 0;
        for($e=0;$e<count($environs);$e++){
            $classrooms = ($environs[$e])->classrooms()->get();
            foreach ($classrooms as $envclassroom) {
                if($envclassroom->user_id!==$user->id){
                    $environEducators+=1;
                }
                $environMembers = $envclassroom->users()->get();
                $environStudents+=count($environMembers);
            }
        }
        $organizations = $user->organizations()->get();
        $organizationEducators = 0;
        $organizationStudents = 0;
        foreach ($organizations as $organization) {
            $orgEnvirons = $organization->environs()->get();
            foreach ($orgEnvirons as $orgEnviron) {
                $orgClassrooms = $orgEnviron->classrooms()->get();
                foreach ($orgClassrooms as $orgClassroom) {
                    if($orgClassroom->user_id!==$user->id){
                        $organizationEducators+=1;
                    }
                    $orgMembers = $orgClassroom->users()->get();
                    $organizationStudents+=count($orgMembers);
                }
            }
        }

        $user->classroomsJoined = [count($classroomsj)];
        $user->theoryTests = [count($theorytestsdone),$theorytestsdone];
        $user->objectiveTests = [count($objectivetestsdone),$objectivetestsdone];
        $user->classroomsCreated = [count($classroomsc)];
        $user->classroomStudents = $students;
        $user->classroomTests = $testscreated;
        $user->environs = [count($environs)];
        $user->environEducators = $environEducators;
        $user->environStudents = $environStudents;
        $user->organizations = [count($organizations)];
        $user->organizationEducators = $organizationEducators;
        $user->organizationStudents = $organizationStudents;

        return response()->json($user);
    }
}
