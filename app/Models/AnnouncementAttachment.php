<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnouncementAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'announcement_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
    ];

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    public function url()
    {
        return asset('storage/' . $this->file_path);
    }

    public function isImage()
    {
        if (!$this->file_name) return false;
        $ext = strtolower(pathinfo($this->file_name, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp']);
    }
}
