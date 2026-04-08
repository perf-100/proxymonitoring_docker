<?php

namespace App\Http\Controllers;

use App\Helpers\ProxyHelper;
use App\Http\Requests\ProxyFilterRequest;
use App\Http\Requests\ProxyRequest;
use App\Http\Resources\ProxyResource;
use App\Models\Proxy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProxyController extends Controller
{
    public function __construct(private ProxyHelper $helper) {

    }
    
    /**
     * @OA\Get(
     *     path="/api/proxies",
     *     summary="Список прокси",
     *     tags={"Proxies"},
     *
     *     @OA\Parameter(name="search", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="type", in="query", required=false, @OA\Schema(type="string", enum={"http","https","socks4","socks5"})),
     *     @OA\Parameter(name="status", in="query", required=false, @OA\Schema(type="string", enum={"unknown","working","failed"})),
     *
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function index(ProxyFilterRequest $request)
    {
        $this->authorize('viewAny', Proxy::class);

        $data = $this->helper->paginate(Auth::user(), $request->validated());

        return ProxyResource::collection($data);
    }

    /**
     * @OA\Post(
     *     path="/api/proxies",
     *     summary="Создать прокси",
     *     tags={"Proxies"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"proxy_string","check_interval"},
     *             @OA\Property(property="proxy_string", type="string", example="login:pass@127.0.0.1:8080"),
     *             @OA\Property(property="check_interval", type="integer", example=300),
     *             @OA\Property(property="comment", type="string", example="test proxy")
     *         )
     *     ),
     *
     *     @OA\Response(response=201, description="Создано"),
     *     @OA\Response(response=422, description="Ошибка валидации")
     * )
     */
    public function store(ProxyRequest $request) {

        $this->authorize('create', Proxy::class);

        $this->helper->create(Auth::user(), $request->validated());

        return response()->json([
            'message' => 'Прокси добавлен'
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/proxies/{id}",
     *     summary="Обновить прокси",
     *     tags={"Proxies"},
     *
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"proxy_string","check_interval"},
     *             @OA\Property(property="proxy_string", type="string", example="login:pass@127.0.0.1:8080"),
     *             @OA\Property(property="check_interval", type="integer", example=60),
     *             @OA\Property(property="comment", type="string", example="test proxy")
     *         )
     *     ),
     *
     *     @OA\Response(response=204, description="Обновлено"),
     *     @OA\Response(response=403, description="Нет доступа")
     * )
     */
    public function update(ProxyRequest $request, Proxy $proxy)
    {
        $this->authorize('update', $proxy);
        
        $this->helper->update($proxy, $request->validated());

        return response()->noContent();
    }

    /**
     * @OA\Delete(
     *     path="/api/proxies/{id}",
     *     summary="Удалить прокси",
     *     tags={"Proxies"},
     *
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=204, description="Удалено"),
     *     @OA\Response(response=403, description="Нет доступа")
     * )
     */
    public function destroy(Request $request, Proxy $proxy)
    {
        $this->authorize('delete', $proxy);

        $this->helper->delete($proxy);

        return response()->noContent();
    }

    /**
     * @OA\Post(
     *     path="/api/proxies/{id}/check",
     *     summary="Запустить проверку прокси",
     *     tags={"Proxies"},
     *
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=202, description="Проверка запущена"),
     *     @OA\Response(response=403, description="Нет доступа")
     * )
     */
    public function check(Request $request, Proxy $proxy)
    {
        $this->authorize('view', $proxy);

        $this->helper->dispatchCheck($proxy);

        return response()->json([
            'message' => 'Проверка запущена'
        ], 202);
    }
}
