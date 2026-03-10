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

    public function index(Request $request, Proxy $proxy)
    {
        $this->authorize('view', $proxy);

        $data = $this->helper->paginate($proxy);

        return ProxyCheckResource::collection($data);
    }
}
