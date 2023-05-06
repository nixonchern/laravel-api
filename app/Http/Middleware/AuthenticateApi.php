<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
/**
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Токен авторизации",
 *     name="Token",
 *     in="header",
 *     scheme="bearer",
 *     securityScheme="AuthenticateApi",
 * )
 */
class AuthenticateApi extends Middleware
{
    protected function authenticate($request, array $guards)
    {
        $token = $request->bearerToken();

        if($token === config('apitokens')[0]) return;

        $this->unauthenticated($request, $guards);
    }
}
