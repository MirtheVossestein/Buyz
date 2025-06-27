<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description', 'price', 'category_id', 'location', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
{
    return $this->hasMany(AdImage::class);
}

public function category()
{
    return $this->belongsTo(Category::class);
}


}
