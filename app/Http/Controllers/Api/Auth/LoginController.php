<?php

namespace App\Http\Controllers\Api\Auth;

use App\Exceptions\ErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Services\Abiliy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws ErrorException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw new ErrorException(__('validation.credentials-incorrect'), 400);
        }

        $token = $user->createToken('default', Abiliy::permissions($user));

        return $this->json(200, null, [
            'token' => $token->plainTextToken,
        ]);
    }

    public function logout() {
        auth()->user()->tokens()->delete();

        return $this->json(200, 'logout');
    }
}
