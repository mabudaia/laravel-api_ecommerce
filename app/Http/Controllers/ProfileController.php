<?php

namespace App\Http\Controllers;
use App\Models\Profile;
use App\Http\Requests\storeProfileRequest;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
        public function store(storeProfileRequest $request)
    {
                $user_id=Auth::user()->id;
        $validatedData=$request->validated();
        $validatedData['user_id']=$user_id;

  
                    $profile =Profile::create($validatedData);

        return response()->json([
            'messege'=>'Profile create successfully',
            'profile'=>$profile
        ], 201);
    }}
