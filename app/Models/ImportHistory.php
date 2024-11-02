<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'imported_at',
        'source_file',
        'imported_count',
        'status',
        'error_message',
    ];
}
