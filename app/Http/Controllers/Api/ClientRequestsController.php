<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequestsStoreRequest;
use App\Http\Requests\ClientRequestsUpdateRequest;
use App\Http\Resources\ClientRequestsCollection;
use App\Http\Resources\ClientRequestsResource;
use App\Mail\SendClientRequestsAnswerMail;
use App\Models\ClientRequests;
use Illuminate\Support\Facades\Mail;

class ClientRequestsController extends Controller
{
    /**
        *@OA\GET(
        *   path="api/requests",
        *   security={{ "AuthenticateApi": {} }},
        *   summary="Заявки от клиентов",
        *   description="",
        *   tags={"Requests"},
        *   @OA\Parameter(
        *       name="sort",
        *       in="query",
        *       description="Атрибут для сортировки, по умолчанию 'status'",
        *   ),
        *   @OA\Parameter(
        *       name="order",
        *       in="query",
        *       description="Метод сортировки, по умолчанию 'desc'",
        *   ),
        *   @OA\Parameter(
        *       name="per_page",
        *       in="query",
        *       description="Кол-во извлекаемых объектов, по умолчанию '5'",
        *   ),
        *   @OA\Parameter(
        *       name="page",
        *       in="query",
        *       description="Страница пагинации",
        *   ),
        *   @OA\Response(
        *      response=200,
        *      description="OK",
        *      @OA\MediaType(
        *           mediaType="application/json",
        *      )
        *   ),
        * ),
    */
    public function index()
    {
        $clientRequests = ClientRequests::with('user')
        ->orderBy(request('sort', 'status'), request('order', 'desc'))
        ->paginate(request('per_page', 5));

        return new ClientRequestsCollection($clientRequests);
    }

    /**
        *@OA\POST(
        *   path="api/requests",
        *   summary="Создание заявки",
        *   description="",
        *   tags={"Requests"},
        *   @OA\Response(
        *       response=200,
        *       description="OK",
        *       @OA\MediaType(
        *           mediaType="application/json",
        *       )
        *   ),
        *),
    */
    public function store(ClientRequestsStoreRequest $request)
    {
        $clientRequests = ClientRequests::create($request->validated());

        return  new ClientRequestsResource($clientRequests);
    }

    /**
        *@OA\GET(
        *   path="api/requests/{clientRequests}",
        *   security={{ "AuthenticateApi": {} }},
        *   summary="Информация о заявке",
        *   description="",
        *   tags={"Requests"},
        *   @OA\Response(
        *       response=200,
        *       description="OK",
        *       @OA\MediaType(
        *           mediaType="application/json",
        *       )
        *   ),
        *),
    */
    public function show(ClientRequests $clientRequests)
    {
        return new ClientRequestsResource($clientRequests);
    }

    /**
        *@OA\PUT(
        *   path="api/requests/{clientRequests}",
        *   security={{ "AuthenticateApi": {} }},
        *   summary="Ответ на заявку",
        *   description="",
        *   tags={"Requests"},
        *   @OA\Response(
        *       response=200,
        *       description="OK",
        *       @OA\MediaType(
        *           mediaType="application/json",
        *       )
        *   ),
        *),
    */
    public function update(ClientRequestsUpdateRequest $request, ClientRequests $clientRequests)
    {
        $clientRequests->setAnswer($request->validated());
        Mail::to($clientRequests->mail)->send(new SendClientRequestsAnswerMail($clientRequests->comment));
        return new ClientRequestsResource($clientRequests);
    }

    /**
        *@OA\DELETE(
        *   path="api/requests/{clientRequests}",
        *   security={{ "AuthenticateApi": {} }},
        *   summary="Удаление заявки",
        *   description="",
        *   tags={"Requests"},
        *   @OA\Response(
        *       response=200,
        *       description="OK",
        *       @OA\MediaType(
        *           mediaType="application/json",
        *       )
        *   ),
        *),
    */
    public function destroy(ClientRequests $clientRequests)
    {
        $clientRequests->delete();

        return response(null, 204);
    }
}
