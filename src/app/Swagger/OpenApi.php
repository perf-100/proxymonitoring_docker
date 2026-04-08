<?php

namespace App\Swagger;

/**
 * @OA\Info(
 *     title="ProxyMonitoring API",
 *     version="1.0.0",
 *     description="API для мониторинга прокси"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="Token"
 * )
 * 
 * @OA\OpenApi(
 *     security={{"sanctum":{}}}
 * )
 */
class OpenApi
{

}