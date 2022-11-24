<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
	// Create User Method
	public function createUser(Request $request)
	{
		try {
			$validateUser = Validator::make($request->all(), [
				'name' => 'required',
				'email' => 'required|email|unique:users',
				'password' => 'required'
			]);

			if ($validateUser->fails()) {
				return response()->json([
					'status' => false,
					'message' => 'validation error',
					'errors' => $validateUser->errors()
				], 401);
			}

			$user = User::create([
				'name' => $request->name,
				'email' =>	$request->email,
				'password' => Hash::make($request->password)
			]);

			return response()->json([
				'status' => false,
				'message' => 'Usre created successfully',
				'token' => $user->createToken("API TOKEN")->plainTextToken
			], 200);
		} catch (\Throwable $th) {
			return response()->json([
				'status' => false,
				'message' => $th->getMessage(),
			], 500);
		}
	}
}
