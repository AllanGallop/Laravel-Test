<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *      title="Assessment API Documentation",
 *      version="1.0.0",
 *      description="This is the API documentation for the WatchTwr assessment",
 *      @OA\Contact(
 *          email="allangallop@gmail.com"
 *      ),
 *      @OA\License(
 *          name="MIT",
 *          url="https://opensource.org/licenses/MIT"
 *      )
 * )
 * 
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="API Server"
 * )
 * 
 * @OA\SecurityScheme(
 *      securityScheme="BearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT"
 * )
 */
abstract class Controller
{
    //
}
