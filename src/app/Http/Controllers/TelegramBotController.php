<?php

namespace App\Http\Controllers;

use App\Helpers\TelegramBotHelper;
use App\Http\Requests\TelegramBotFilterRequest;
use App\Http\Requests\TelegramBotRequest;
use App\Http\Resources\TelegramBotResource;
use App\Models\TelegramBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TelegramBotController extends Controller
{
    public function __construct(private TelegramBotHelper $helper) {

    }

    /**
     * @OA\Get(
     *     path="/api/bots",
     *     summary="Список Telegram ботов",
     *     tags={"TelegramBots"},
     *
     *     @OA\Parameter(name="search", in="query", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="status", in="query", required=false, @OA\Schema(type="string", enum={"active","disabled"})),
     * 
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function index(TelegramBotFilterRequest $request)
    {
        $this->authorize('viewAny', TelegramBot::class);

        $data = $this->helper->paginate(Auth::user(), $request->validated());

        return TelegramBotResource::collection($data);
    }

    /**
     * @OA\Post(
     *     path="/api/bots",
     *     summary="Создать Telegram бота",
     *     tags={"TelegramBots"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"bot_token","chat_id"},
     *             @OA\Property(property="bot_token", type="string", example="123456:ABCDEF1234567890abcdef1234567890abcd"),
     *             @OA\Property(property="chat_id", type="string", example="123456789")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Создано",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Бот добавлен")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Ошибка валидации")
     * )
     */
    public function store(TelegramBotRequest $request)
    {
        $this->authorize('create', TelegramBot::class);

        $this->helper->create(Auth::user(), $request->validated());

        return response()->json([
            'message' => 'Бот добавлен'
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/bots/{id}/toggle",
     *     summary="Переключить статус бота",
     *     tags={"TelegramBots"},
     *
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=204, description="Статус изменён"),
     *     @OA\Response(response=403, description="Нет доступа")
     * )
     */
    public function toggle(Request $request, TelegramBot $bot)
    {
        $this->authorize('update', $bot);

        $this->helper->toggle($bot);

        return response()->noContent();
    }

    /**
     * @OA\Delete(
     *     path="/api/bots/{id}",
     *     summary="Удалить бота",
     *     tags={"TelegramBots"},
     *
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=204, description="Удалено"),
     *     @OA\Response(response=403, description="Нет доступа")
     * )
     */
    public function destroy(Request $request, TelegramBot $bot)
    {
        $this->authorize('delete', $bot);

        $this->helper->delete($bot);

        return response()->noContent();
    }
}