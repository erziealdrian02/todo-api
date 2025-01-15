<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TodoCategory extends Model
{
    use HasFactory;
    
    /**
     * fillable
     *
    * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'due_date',
    ];

    protected $table = 'todo_category';
}
