<?php

namespace App\Http\Controllers;

use App\Helpers\ProxyHelper;
use App\Http\Requests\ProxyRequest;
use App\Models\Proxy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProxyController extends Controller
{
    public function __construct(private ProxyHelper $helper) {

    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'type', 'status']);

        $data = $this->helper->paginate(Auth::user(), $filters);

        return response()->json(
            $data
        );
    }

    public function store(ProxyRequest $request) {

        $this->helper->create(Auth::user(), $request->validated());

        return response()->json([
            'message' => 'Прокси добавлен'
        ]);
    }

    public function update(ProxyRequest $request, $id)
    {
        $proxy = Proxy::findOrFail($id);
        $this->authorize('update', $proxy);
        
        $this->helper->update(Auth::user(), $proxy, $request->validated());

        return response()->json([
            'message' => 'Прокси обновлён'
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $proxy = Proxy::findOrFail($id);
        $this->authorize('delete', $proxy);

        $this->helper->delete($proxy);

        return response()->json([
            'message' => 'Прокси удалён'
        ]);
    }

    public function checkNow(Request $request, $id)
    {
        $proxy = Proxy::findOrFail($id);
        $this->authorize('view', $proxy);

        $this->helper->dispatchCheck($proxy);

        return response()->json([
            'message' => 'Проверка запущена'
        ]);
    }
}
