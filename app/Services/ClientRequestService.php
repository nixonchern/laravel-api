<?php

namespace App\Services;

use App\Mail\SendClientRequestsAnswerMail;
use App\Repositories\ClientRequestRepository;
use Illuminate\Support\Facades\Mail;

class ClientRequestService
{
    private ClientRequestRepository $repository;
    public function __construct(ClientRequestRepository $clientRequestRepository)
    {
        $this->repository = $clientRequestRepository;
    }

    public function getById(int $id){
        return $this->repository->getById($id);
    }

    public function getAllWithPaginate($sort, $order, $per_page, $page){
        return $this->repository->getAllWithPaginate($sort, $order, $per_page, $page);
    }

    public function deleteById(int $id){
        $clientRequest = $this->getById($id);
        if(!$clientRequest){
            throw new \Exception("Не найден 'запрос' с идентификатором: $id", 404);
        }

        $this->repository->deleteById($id);
    }

    public function create(string $name, string $email, string $message){
        return $this->repository->create($name, $email, $message);
    }

    public function setAnswerById(int $id, int $idUser, string $comment){
        $clientRequest = $this->getById($id);
        if(!$clientRequest){
            throw new \Exception("Не найден 'запрос' с идентификатором: $id", 404);
        }

        $clientRequest = $this->repository->setAnswer($id, $idUser, $comment);
        Mail::to($clientRequest->mail)->send(new SendClientRequestsAnswerMail($clientRequest->comment));
        
        return $clientRequest;
    }
}