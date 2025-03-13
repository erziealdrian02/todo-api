<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'checklist_id',
        'item_name',
        'is_completed',
    ];
}
