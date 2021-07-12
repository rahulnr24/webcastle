<?php

namespace App\Http\Middleware;

use App\Models\Credential;
use Closure;
use Illuminate\Http\Request;

class BearerToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if ($token) {

            $is_valid_token = Credential::where(['is_active' => true, 'access_token' => $token])->first();

            if ($is_valid_token) {

                $request->merge([
                    'user_id' => $is_valid_token->user_id,
                    'credential_id' => $is_valid_token->id
                ]);

                return $next($request);
            } else {
                $response = [
                    'status' => 401,
                    'message' => 'Invalid access token',
                ];
            }
        } else {
            $response = [
                'status' => 400,
                'message' => 'Bad request',
            ];
        }

        return response()->json($response, $response['status']);
    }
}
