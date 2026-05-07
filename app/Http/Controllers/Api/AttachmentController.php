<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MessageAttachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttachmentController extends Controller
{
    /**
     * View/download an attachment (returns file content for inline viewing)
     */
    public function view(MessageAttachment $attachment)
    {
        $user = Auth::user();
        $message = $attachment->message;

        if ($message->recipient_id !== $user->id && $message->sender_id !== $user->id) {
            abort(403);
        }

        if (!Storage::disk('public')->exists($attachment->file_path)) {
            abort(404, 'File not found');
        }

        $path = Storage::disk('public')->path($attachment->file_path);
        $mimeType = $attachment->file_type ?: mime_content_type($path) ?: 'application/octet-stream';

        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $attachment->file_name . '"',
        ]);
    }

    /**
     * Download an attachment (forces download)
     */
    public function download(MessageAttachment $attachment)
    {
        $user = Auth::user();
        $message = $attachment->message;

        if ($message->recipient_id !== $user->id && $message->sender_id !== $user->id) {
            abort(403);
        }

        if (!Storage::disk('public')->exists($attachment->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    }
}
