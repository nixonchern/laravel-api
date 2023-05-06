<?php

namespace App\Http\Controllers\Api;

use App\Enums\ClientRequestsStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequestsStoreRequest;
use App\Http\Requests\ClientRequestsUpdateRequest;
use App\Http\Resources\ClientRequestsResource;
use App\Mail\SendClientRequestsAnswerMail;
use App\Models\ClientRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
/**
 * @OA\GET(
 *     path="api/requests",
 *     security={{ "AuthenticateApi": {} }},
 *     summary="Заявки от клиентов",
 *     description="",
 *     tags={"Requests"},
 *     @OA\Parameter(
 *          name="sort",
 *          in="query",
 *          description="Атрибут для сортировки, по умолчанию 'status'",
 *     ),
 *     @OA\Parameter(
 *          name="order",
 *          in="query",
 *          description="Метод сортировки, по умолчанию 'desc'",
 *     ),
 *     @OA\Parameter(
 *          name="limit",
 *          in="query",
 *          description="Кол-во извлекаемых объектов, по умолчанию '5'",
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\MediaType(
 *              mediaType="application/json",
 *         )
 *     ),
 * ),
 * @OA\GET(
 *      path="api/requests/{clientRequests}",
 *      security={{ "AuthenticateApi": {} }},
 *      summary="Информация о заявке",
 *      description="",
 *      tags={"Requests"},
 *      @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *          )
 *      ),
 * ),
 * @OA\POST(
 *      path="api/requests",
 *      summary="Создание заявки",
 *      description="",
 *      tags={"Requests"},
 *      @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *          )
 *      ),
 * ),
 * @OA\PUT(
 *      path="api/requests/{clientRequests}",
 *      security={{ "AuthenticateApi": {} }},
 *      summary="Ответ на заявку",
 *      description="",
 *      tags={"Requests"},
 *      @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *          )
 *      ),
 * ),
 * @OA\DELETE(
 *      path="api/requests/{clientRequests}",
 *      security={{ "AuthenticateApi": {} }},
 *      summary="Удаление заявки",
 *      description="",
 *      tags={"Requests"},
 *      @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *          )
 *      ),
 * ),
 */
class ClientRequestsController extends Controller
{
    public function index()
    {
        $clientRequests = ClientRequests::query();
        $clientRequests->orderBy(request('sort', 'status'), request('order', 'desc'));
        $clientRequests->limit(request('limit', '5'));

        return ClientRequestsResource::collection($clientRequests->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientRequestsStoreRequest $request)
    {
        $clientRequests = ClientRequests::create($request->validated());

        return  new ClientRequestsResource($clientRequests);
    }

    /**
     * Display the specified resource.
     */
    public function show(ClientRequests $clientRequests)
    {
        // var_dump($clientRequests);
        return new ClientRequestsResource($clientRequests);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientRequestsUpdateRequest $request, ClientRequests $clientRequests)
    {
        $clientRequests->setAnswer($request->validated());
        Mail::to('nixonchern@mail.ru')->send(new SendClientRequestsAnswerMail($clientRequests->comment));
        return new ClientRequestsResource($clientRequests);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClientRequests $clientRequests)
    {
        $clientRequests->delete();

        return response(null, 204);
    }
}
