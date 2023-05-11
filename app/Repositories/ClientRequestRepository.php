<?php

namespace App\Repositories;

use App\Enums\ClientRequestsStatusEnum;
use App\Models\ClientRequests;

class ClientRequestRepository
{
    public function getById(int $id){
        return ClientRequests::find($id);
    }

    public function getAllWithPaginate($sort, $order, $per_page, $page){
        return ClientRequests::with('user')
            ->orderBy($sort, $order)
            ->paginate($per_page, ['*'], 'page', $page);
    }

    public function deleteById($id){
        ClientRequests::find($id)->delete();
    }

    public function create(string $name, string $email, string $message){
        $clientRequest = ClientRequests::create([
            'name' => $name,
            'email' => $email,
            'message' => $message,
        ]);

        return $clientRequest;
    }

    public function setAnswer(int $id, int $idUser, string $comment){
        $clientRequest = ClientRequests::find($id);
        $clientRequest->comment = $comment;
        $clientRequest->user_id = $idUser;
        $clientRequest->status = ClientRequestsStatusEnum::Resolved;
        $clientRequest->save();
        return $clientRequest;
    }
}