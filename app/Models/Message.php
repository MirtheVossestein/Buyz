<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'sender_id',
        'receiver_id',
        'ad_id',
        'content',
    ];

    // Relatie: een bericht hoort bij een conversatie
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    // Relatie: het bericht heeft een afzender
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Relatie: het bericht heeft een ontvanger
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Optioneel: bericht gekoppeld aan advertentie
    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }
}
