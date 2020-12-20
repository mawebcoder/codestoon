<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="codestoon",
 *      description="codestoon api documantion",
 * )
 */
/**
 * @OA\Post(
 * path="/login/{id}",
 * summary="Sign in",
 * description="Login by email, password",
 * tags={"auth"},
 * @OA\RequestBody(
 *    required=true,
 *    description="Pass user credentials",
 *    @OA\JsonContent(
 *       required={"email","password"},
 *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
 *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
 *       @OA\Property(property="persistent", type="boolean", example="true"),
 *    ),
 * ),
 *     @OA\parameter(
 *          name="id",
 *           in="path",
 *           required=true,
 *           description="the user id",
 *              @OA\schema(
type="integer",
 *
 * ),
 *     ),
 * @OA\Response(
 *    response=422,
 *    description="Wrong credentials response",
 *    @OA\JsonContent(
 *       @OA\Property(property="users", type="object", example="{name:'mohammad',family:'amiri'}"),
 *       @OA\Property(property="status", type="integer", example="200"),
 *        )
 *     ),
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
