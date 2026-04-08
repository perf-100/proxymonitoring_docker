<?php

namespace App\Http\Controllers;

use App\Helpers\ProxyCheckHelper;
use App\Http\Resources\ProxyCheckResource;
use App\Models\Proxy;
use Illuminate\Http\Request;

class ProxyCheckController extends Controller
{
    public function __construct(private ProxyCheckHelper $helper) {

    }

    /**
     * @OA\Get(
     *     path="/api/proxies/checks/{id}",
     *     summary="Данные о проверках прокси",
     *     tags={"Proxies"},
     *
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function index(Request $request, Proxy $proxy)
    {
        $this->authorize('view', $proxy);

        $data = $this->helper->paginate($proxy);

        return ProxyCheckResource::collection($data);
    }
}
