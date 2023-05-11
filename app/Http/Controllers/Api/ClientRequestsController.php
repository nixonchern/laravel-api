<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequestsStoreRequest;
use App\Http\Requests\ClientRequestsUpdateRequest;
use App\Http\Resources\ClientRequestsCollection;
use App\Http\Resources\ClientRequestsResource;
use App\Services\ClientRequestService;


class ClientRequestsController extends Controller
{
    private ClientRequestService $service;
    public function __construct(ClientRequestService $service)
    {
        $this->service = $service;
    }
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
        $clientRequestPaginate = $this->service->getAllWithPaginate(
            request('sort', 'status'), 
            request('order', 'desc'), 
            request('per_page', 5), 
            request('page', 1)
        );
        
        return new ClientRequestsCollection($clientRequestPaginate);
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
        $requestValidated = $request->validated();
        try {
            $newClientRequest = $this->service->create($requestValidated['name'], $requestValidated['email'], $requestValidated['message']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }

        return  new ClientRequestsResource( $newClientRequest );
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
    public function show(int $id)
    {
        $clientRequest = $this->service->getById($id);
        if(!$clientRequest){
            return response()->json(['message' => "Не найден 'запрос' с идентификатором: $id"], 404);
        }
        
        return new ClientRequestsResource( $clientRequest );
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
    public function update(ClientRequestsUpdateRequest $request, int $id)
    {
        $requestValidated = $request->validated();
        try {
            $clientRequest = $this->service->setAnswerById($id, $requestValidated['user_id'], $requestValidated['comment']);
        } catch (\Exception $e) {
            
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
        
        return new ClientRequestsResource($clientRequest);
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
    public function destroy(int $id)
    {
        try {
            $this->service->deleteById($id);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }

        return response(null, 204);
    }
}
