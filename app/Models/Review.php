<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'reviewer_id',
        'reviewee_id',
        'ad_id',
        'rating',
        'comment',
    ];

      public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function reviewee()
    {
        return $this->belongsTo(User::class, 'reviewee_id');
    }

    public function ad()
    {
        return $this->belongsTo(Ad::class, 'ad_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    

  
}
