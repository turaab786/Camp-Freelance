<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

// Models
use App\User;

// Resources
use App\Http\Resources\User\UserProfileResource;

class ApiAuthController extends Model
{

    public function loginApi(Request $request)
    {
        $request->validate([
            'email' => 'required',
            // 'phone_number' => 'required',
            'password' => 'required|min:6'
        ]);

        $user = User::where('email', $request->email)->first();
        // $user = User::where('phone_number', $request->phone_number)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.']
                // 'phone_number' => ['The provided credentials are incorrect.']
            ]);
        }

        \session(['isVerified' => true]);

        $new_user_data = User::where('id', $user->id)->first();

        return response()->json(['data' => new UserProfileResource($new_user_data)], 200);
    }

    public function registerApi(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string'],
            'phone_number' => ['string', 'unique:users'],
            'role' => ['required', 'string']
        ]);

        $new_user_role = 'seller';
        if ($request->role) {
            $new_user_role = $request->role;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'role' => $new_user_role,
            'seller_plan_id' => 1,
            'buyer_plan_id' => 2
        ]);
        
        UserDetails::create([
            'user_id' => $user->id
        ]);
        UserAccount::create([
            'user_id' => $user->id
        ]);

        $user->assignRole($new_user_role);

        \session(['isVerified' => true]);

        $new_user_data = User::where('id', $user->id)->with('account')->with('details')->first();

        return response()->json(['data' => new UserProfileResource($new_user_data)], 201);
    }

    public function logoutApi(Request $request)
    {
        if (!!$request->user()->is_2fa_enabled) {
            $request->user()->update([
                'is_2fa_verified' => false
            ]);
        }
        $request->user()->tokens()->delete();
        return response()->json(['data' => 'User Tokkens Deleted'], 200);
    }
}
