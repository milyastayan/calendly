<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use function auth;

class AuthController extends BaseController
{

    /**
     * Handle a registration request for the application.
     *
     * @param UserRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        if ($user) {
            $token = $this->createToken($user);

            return $this->setData([
                'token' => $token->accessToken,
                'tokenType' => 'Bearer',
                'expiresAt' => Carbon::parse($token->token->expires_at)->toISOString(),
                'user' => new UserResource($user),
            ])->respondWithSuccess('Registered Successfully!');
        }

        return $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->respondWithError();
    }

    /**
     * Handle a login request to the application.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $login = str_replace(' ', '', $request->login);
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $request->merge([$field => $login]);

        $credentials = $request->only([$field, 'password']);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $this->createToken();
            $token->token->expires_at = Carbon::now()->addYears(2);
            $token->token->save();


            return $this->setData([
                'token' => $token->accessToken,
                'tokenType' => 'Bearer',
                'expiresAt' => Carbon::parse($token->token->expires_at)->toISOString(),
                'user' => new UserResource($user),
            ])->respondWithSuccess('Logged in Successfully!');
        }


        return $this->setStatusCode(Response::HTTP_BAD_REQUEST)
            ->respondWithError('auth_failed');
    }

    /**
     * Handle a logout request for the application.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $user = auth()->user();
        if ($user && $user->token()->delete()) {
            return $this->respondWithSuccess('You\'re logged out!');
        }

        return $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->respondWithError();
    }

    /**
     * @param User $user
     *
     * @return \Laravel\Passport\PersonalAccessTokenResult
     */
    protected function createToken($user = null)
    {
        $user = $user ?? auth()->user();

        return $user->createToken('Personal Access Token', []);
    }


}
