<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="My Todo API",
 *     version="0.1",
 *     description="This is a sample API",
 *     @OA\Contact(
 *         email="developer@example.com"
 *     )
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter 'Bearer <token>' to authenticate."
 * )
 */
abstract class Controller
{
    //
}
