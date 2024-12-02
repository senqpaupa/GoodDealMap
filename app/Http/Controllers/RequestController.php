<?php

namespace App\Http\Controllers;

use App\Models\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class RequestController extends Controller
{
    /**
     * Конструктор контроллера
     * Требует аутентификацию для всех методов
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Получение списка всех запросов
     * GET /api/requests
     */
    public function index(): JsonResponse
    {
        $requests = Request::with('user')->get();
        return response()->json(['requests' => $requests]);
    }

    /**
     * Создание нового запроса
     * POST /api/requests
     */
    public function store(HttpRequest $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'deadline' => 'nullable|date|after:now',
            'estimated_time' => 'nullable|integer|min:1',
            'contact_phone' => 'nullable|string',
            'urgency' => 'nullable|string',
            'files' => 'nullable|array'
        ]);

        $helpRequest = Request::create([
            ...$validated,
            'user_id' => Auth::id(),
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Запрос успешно создан',
            'request' => $helpRequest->load('user')
        ], 201);
    }

    /**
     * Получение информации о конкретном запросе
     * GET /api/requests/{id}
     */
    public function show(Request $request): JsonResponse
    {
        return response()->json(['request' => $request->load('user')]);
    }

    /**
     * Обновление существующего запроса
     * PUT /api/requests/{id}
     */
    public function update(HttpRequest $httpRequest, Request $request): JsonResponse
    {
        if ($request->user_id !== Auth::id()) {
            return response()->json(['message' => 'Нет прав для редактирования'], 403);
        }

        $validated = $httpRequest->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'address' => 'sometimes|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'deadline' => 'nullable|date|after:now',
            'estimated_time' => 'nullable|integer|min:1',
            'contact_phone' => 'nullable|string',
            'urgency' => 'nullable|string',
            'status' => 'sometimes|in:pending,in_progress,completed,cancelled'
        ]);

        $request->update($validated);

        return response()->json([
            'message' => 'Запрос успешно обновлен',
            'request' => $request->fresh('user')
        ]);
    }

    /**
     * Удаление запроса
     * DELETE /api/requests/{id}
     */
    public function destroy(Request $request): JsonResponse
    {
        if ($request->user_id !== Auth::id()) {
            return response()->json(['message' => 'Нет прав для удаления'], 403);
        }

        $request->delete();
        return response()->json(['message' => 'Запрос успешно удален']);
    }

    /**
     * Поиск ближайших запросов
     * GET /api/requests/nearby?latitude=55.7558&longitude=37.6173&radius=10
     */
    public function nearby(HttpRequest $request): JsonResponse
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:0|max:100'
        ]);

        $radius = $validated['radius'] ?? 10;
        $nearbyRequests = Request::findNearby(
            $validated['latitude'],
            $validated['longitude'],
            $radius
        );

        return response()->json([
            'requests' => $nearbyRequests,
            'count' => $nearbyRequests->count()
        ]);
    }
}
