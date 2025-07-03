<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'user_one_id',
        'user_two_id',
        'ad_id',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }


    public function participants()
    {
        $senderIds = $this->messages()->pluck('sender_id')->unique();
        $receiverIds = $this->messages()->pluck('receiver_id')->unique();

        $participantIds = $senderIds->merge($receiverIds)->unique();

        return User::whereIn('id', $participantIds)->get();
    }



}