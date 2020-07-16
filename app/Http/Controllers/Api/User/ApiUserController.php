<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
// Models
use App\User;

// Resources
use App\Http\Resources\User\UserProfileResource;

class ApiUserController extends Model
{
    public function getUserPermissions(Request $request)
    {
        $permissions = $request->user()->getAllPermissions();
        return response()->json(['data' => $permissions], 404);
    }

    public function getUserProfileData(Request $request)
    {
        $user = User::where('id', $request->user()->id)->first();
        if ($user) {
            return response()->json(['data' => new UserProfileResource($user)], 200);
        } else {
            return response()->json(['message' => 'User Not Found!'], 500);
        }

    }
}