<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use FilePathEnum;
class Attachment extends Model
{
    use HasFactory;
    protected $fillable = [
        'file_name', 'file_path', 'file_type', 'attachable_id', 'attachable_type',
    ];
    
    public static function getMessageContentAttribute($file,$filePath)
    {
        $filename = time().rand(1000,9999).'.'.$file->getClientOriginalExtension();
        Storage::putFileAs($filePath,$file,$filename);
        $attachment = new Attachment();
        $attachment->file_name =$filename;
        $attachment->file_type = $file->getMimeType();
        $attachment->save();

        return [
            'file_path' => $filePath,
            'attachment_id' => $attachment->id
        ];
    }
    public function getProfilePictureUrlAttribute()
    {
        if ($this->profile_picture_id) {
            $attachment = Attachment::find($this->profile_picture_id);
            return $attachment ? Storage::url(FilePathEnum::PROFILE_IMAGE . $this->id . '/' . $attachment->file_name) : null;
        }
        return null;
    }
    public function attachable()
    {
        return $this->morphTo(Attachment::class, 'attachable');;
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
