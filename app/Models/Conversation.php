<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'user_one_id',
        'user_two_id',
        'ad_id', // als je die koppeling hebt
    ];

    // Relatie: een conversatie heeft veel berichten
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Optioneel: relaties naar de gebruikers
    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    // Optioneel: relatie naar de advertentie
    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }
}