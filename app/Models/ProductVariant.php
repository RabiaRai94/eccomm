<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}