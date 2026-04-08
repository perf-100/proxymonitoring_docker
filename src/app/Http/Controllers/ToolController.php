<?php

namespace App\Http\Controllers;

use App\Helpers\ToolHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function __construct(private ToolHelper $helper) {

    }

    /**
     * @OA\Get(
     *     path="/api/tools",
     *     summary="Информация о ip",
     *     tags={"Tools"},
     * 
     *     @OA\Response(response=200, description="Данные получены"),
     *     @OA\Response(response=503, description="Сервис недоступен")
     * )
     */
    public function index(Request $request)
    {
        try {
            $data = $this->helper->lookup(Auth::user());

            return response()->json($data, 200);

        } catch (\Throwable $e) {

            return response()->json([
                'error' => 'Сервис недоступен'
            ], 503);
        }
    }
}