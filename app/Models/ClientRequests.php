<?php

namespace App\Models;

use App\Enums\ClientRequestsStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User;

class ClientRequests extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'message', 'comment', 'user_id'];

    public function setAnswer($request){
        $this->comment = $request['comment'];
        $this->user_id = $request['user_id'];
        $this->status = ClientRequestsStatusEnum::Resolved;
        $this->save();
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
