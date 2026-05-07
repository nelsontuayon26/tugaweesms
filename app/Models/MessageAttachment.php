<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
    ];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;

        while ($bytes >= 1024 && $unitIndex < count($units) - 1) {
            $bytes /= 1024;
            $unitIndex++;
        }

        return round($bytes, 2) . ' ' . $units[$unitIndex];
    }

    public function getIconAttribute()
    {
        $icons = [
            'image' => 'photo',
            'application/pdf' => 'document-text',
            'application/msword' => 'document',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'document',
            'application/vnd.ms-excel' => 'table',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'table',
            'text/plain' => 'document-text',
        ];

        foreach ($icons as $type => $icon) {
            if (str_starts_with($this->file_type, $type)) {
                return $icon;
            }
        }

        return 'paper-clip';
    }
}
