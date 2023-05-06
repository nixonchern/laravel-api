<?php

namespace App\Models;

use App\Enums\ClientRequestsStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientRequests extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'message', 'comment', 'user_id'];

    public function setAnswer($request){
        $this->attributes['comment'] = $request['comment'];
        $this->attributes['status'] = ClientRequestsStatusEnum::Resolved;
        
    }

}
