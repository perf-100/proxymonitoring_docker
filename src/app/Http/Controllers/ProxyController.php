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

    public function index(ProxyFilterRequest $request)
    {
        $data = $this->helper->paginate(Auth::user(), $request->validated());

        return ProxyResource::collection($data);
    }

    public function store(ProxyRequest $request) {

        $this->helper->create(Auth::user(), $request->validated());

        return response()->json([
            'message' => 'Прокси добавлен'
        ]);
    }

    public function update(ProxyRequest $request, Proxy $proxy)
    {
        $this->authorize('update', $proxy);
        
        $this->helper->update($proxy, $request->validated());

        return response()->json([
            'message' => 'Прокси обновлён'
        ]);
    }

    public function destroy(Request $request, Proxy $proxy)
    {
        $this->authorize('delete', $proxy);

        $this->helper->delete($proxy);

        return response()->json([
            'message' => 'Прокси удалён'
        ]);
    }

    public function checkNow(Request $request, Proxy $proxy)
    {
        $this->authorize('view', $proxy);

        $this->helper->dispatchCheck($proxy);

        return response()->json([
            'message' => 'Проверка запущена'
        ]);
    }
}
