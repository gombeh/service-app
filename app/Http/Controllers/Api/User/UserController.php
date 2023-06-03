<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\UserResource;
use App\Services\Abiliy;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        $user =  auth()->user();
        return $this->json(200, null, UserResource::make($user));
    }

    /**
     * @param ProfileRequest $request
     * @return JsonResponse
     */
    public function updateProfile(ProfileRequest $request): JsonResponse
    {
        $user =  auth()->user();
        $user->update($request->only(['name']));
        return $this->json(200, null, UserResource::make($user));
    }
}
