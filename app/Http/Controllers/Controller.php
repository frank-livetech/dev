<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{/**
     * @OA\Info(
     *      version="1.0.0",
     *      title="Live-Tech System (v0.8)",
     *      description="Api's description",
     *      @OA\Contact(
     *          email="admin@admin.com"
     *      ),
     *      @OA\License(
     *          name="Copyright (c) Live-Tech System All rights reserved.",
     *          url="/"
     *      )
     * )
     *
     * @OA\Get(
     *     path="/",
     *     description="Home page",
    *     @OA\Response(response="default", description="Welcome page")
    * )
     * 
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description=" Live-Tech System APi"
     * )

     *
     * @OA\Tag(
     *     name="Projects",
     *     description="API Endpoints of Projects"
     * )
     * 
    * @OA\SecurityScheme(
    *     type="http",
    *     description="Login with email and password to get the authentication token",
    *     name="Token based Based",
    *     in="header",
    *     scheme="bearer",
    *     bearerFormat="JWT",
    *     securityScheme="apiAuth",
    * )
     */
   
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
