<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class AuthController extends Controller
{
    //Registers new users into the system with a token 
    public function register(Request $request)
    {
        $fields = $request->validate(
            ['name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'       //Needs a 4th parameter password_confirmation
            ]
        );
        

        $role = Role::where('name','customer')->first();
        
        //Creates new user instance
        $user = new User();
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = bcrypt($request['password']);
        $user->role_id = $role->id;
        $user->save();

        //Assign designated roles depending on role
        if ($user->role->name == 'admin')
        {
            $token = $user->createToken('3d13d',['view:sales','crud:book'])->plainTextToken;
        }
        else if ($user->role->name == 'customer')
        {
            $token = $user->createToken('3d13d',['purchase:book'])->plainTextToken;
        }

        $response =  ['name' => $user,
        'token' => $token];

        return response($response,201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return ['message' => 'Logged out'];
    }

    public function login(Request $request)
    {
        $fields = $request->validate(
            [
            'email' => 'required|string',
            'password' => 'required|string'
            ]
        );

        $user = User::where('email',$request->email)->first();

        //Checking if user exists and if password is correct
        logger('User Found Login:');
        logger($user);
        logger('User Email Seen:');
        logger($request->email);
        logger('User Password Seen:');
        logger($request->password);
        if (!$user || !Hash::check($request['password'],$user->password))
        {
            return response(['message' => 'Bad Credentials'],401);
        }

        //Assign designated roles depending on role
        if ($user->role->name == 'admin')
        {
            logger('Admin Logged in');
            $token = $user->createToken('3d13d',['view:sales','crud:book','purchase:book'])->plainTextToken;
        }
        else if ($user->role->name == 'customer')
        {
            logger('Customer Logged in');
            $token = $user->createToken('3d13d',['purchase:book'])->plainTextToken;
        }
            

        $response =  ['name' => $user,
        'token' => $token];

        return response($response,201);
    }
}
