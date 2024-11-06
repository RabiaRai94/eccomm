<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attachment extends Model
{
    use HasFactory;
    protected $fillable = [
        'file_path',
        'file_type',
        'attachable_type',
        'attachable_id'
    ];

    public function attachable()
    {
        return $this->morphTo(Attachment::class, 'attachable');;
    }
}
