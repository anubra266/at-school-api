<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Exception;
use App\Classroom;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function update_profile(Request $request)
    {

        try {


            $data = $request->validate([
                'firstName' => 'required',
                'middleName' => 'required',
                'lastName' => 'required',
                'gender' => 'required',
                'email' => 'required|email',
                'telephone' => 'required',
                'dateOfBirth' => 'required',
                'school' => 'required',
                'school_town' => 'required'
            ]);


            $user = Auth::user()->update($data);
            event(new \App\Events\UpdateUser());

            return response()->json('Profile updated successfully', 200);
        }

        //catch exception
        catch (Exception $e) {
            if ($e->getcode() == 23000) {
                return response()->json(['message' => 'Email already exists!'], 403);
            }
            return response()->json(["message" => "All fields are required!"], 500);
        }
    }
    public function update_password(Request $request)
    {
        try {

            $passwords = $request->validate(["oldpassword" => 'password', "password" => 'required']);
            if ($passwords['oldpassword'] === $passwords['password']) {
                return response()->json(["message" => "The new password can't be same as old"], 400);
            }
            Auth::user()->update(["password" => bcrypt($passwords['password'])]);
            event(new \App\Events\UpdateUser());
            return response()->json("Password Changed Successfully");
        } catch (Exception $e) {
            if ($e->getcode() == 0) {
                return response()->json(["message" => 'Incorrect Old password'], 400);
            } else {
                return response()->json(["message" => 'Sorry. Something went wrong. Try again later'], 400);
            }
        }
    }
    public function update_profile_image(Request $request)
    {

        $request->validate(["profile_image" => "required"]);
        //*delete previous image
        Storage::disk('images')->delete(auth()->user()->profile_image);

        // Get image file

        $image_64 = $request->profile_image;
        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];
        $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
        // find substring fro replace here eg: data:image/png;base64,
        $image = str_replace($replace, '', $image_64);
        $image = str_replace(' ', '+', $image);
        $imageName = time() . Str::random(10) . '.' . $extension;
        //*store new image
        Storage::disk('images')->put($imageName, base64_decode($image));

        Auth::user()->update(['profile_image' => $imageName]);
        event(new \App\Events\UpdateUser());
        return response()->json("Profile Picture Updated Successfully");
    }
    public function authed(Request $request)
    {
        $user =  $request->user();
        $user->load('roles');
        return response()->json($user);
    }
    public function show(Request $request, $userId)
    {
        $user = User::find($userId);

        if ($user) {
            return response()->json($user);
        }

        return response()->json(['message' => 'User not found!'], 404);
    }
    public function info()
    {
        $user = auth()->user();

        $students = 0;
        $testscreated = 0;
        $classroomsj = $user->classrooms()->get();
        $theorytestsdone = $user->theoryresults()->get();
        $theorytestsanswered = $user->theoryanswers()->get();
        $objectivetestsdone = $user->cbts()->get();
        $classroomsc = Classroom::where('user_id', $user->id)->get();
        //*for educators
        for ($i = 0; $i < count($classroomsc); $i++) {
            $classroom = $classroomsc[$i];
            $members = $classroom->users()->get();
            $objectivetests = $classroom->objectivetests()->get();
            $theorytests = $classroom->theorytests()->get();
            $students += count($members);
            $testscreated += count($objectivetests);
            $testscreated += count($theorytests);
        }
        //*for students
        $availabletheory = 0;
        $availableobj = 0;
        foreach ($classroomsj as $class) {
            $joinTime = $class->pivot->created_at;
            $objtestsavailable = $class->objectivetests()->where('created_at', ">", $joinTime)->get();
            $theorytestsavailable = $class->theorytests()->where('created_at', ">", $joinTime)->get();
            //*theory must have question
            $theorytestsavailable = $theorytestsavailable->filter(function ($test) {
                $question = $test->theoryquestions()->first();
                if ($question === null) {
                    return false;
                } else {
                    return true;
                }
            })->values()->all();
            $availableobj += count($objtestsavailable);
            $availabletheory += count($theorytestsavailable);
        }
        $environs = $user->environs()->get();
        $environEducators = 0;
        $environStudents = 0;
        for ($e = 0; $e < count($environs); $e++) {
            $classrooms = ($environs[$e])->classrooms()->get();
            foreach ($classrooms as $envclassroom) {
                if ($envclassroom->user_id !== $user->id) {
                    $environEducators += 1;
                }
                $environMembers = $envclassroom->users()->get();
                $environStudents += count($environMembers);
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
                    if ($orgClassroom->user_id !== $user->id) {
                        $organizationEducators += 1;
                    }
                    $orgMembers = $orgClassroom->users()->get();
                    $organizationStudents += count($orgMembers);
                }
            }
        }

        $user->classroomsJoined = [count($classroomsj)];
        $user->theoryTests = [count($theorytestsdone), $theorytestsdone];
        $user->objectiveTests = [count($objectivetestsdone), $objectivetestsdone];
        $user->missedtheory = $availabletheory - count($theorytestsanswered);
        $user->missedobjective = $availableobj - count($objectivetestsdone);
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
