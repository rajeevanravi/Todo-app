<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'message',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
