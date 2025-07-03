<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\GenericEmail;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{

    public function sendSimpleEmail(Request $request)
    {
        $data = $request->validate([
            'to' => 'required|email',
            'subject' => 'required|string',
            'body' => 'required|string',
        ]);

        $email = Email::create([
            'to' => $data['to'],
            'subject' => $data['subject'],
            'body' => $data['body'],
            'status' => 'pending',
        ]);

        try {
            Mail::to($data['to'])->send(new GenericEmail($data['subject'], $data['body']));
            $email->update(['status' => 'sent']);
        } catch (\Exception $e) {
            $email->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);
        }

        return response()->json(['message' => 'Email processed', 'id' => $email->id]);
    }

    private function validateRequest(Request $request): void
    {
        $allowedMimeTypes = config('laravel-mail-api.attachmentsAllowedMimetypes');

        $rules = [
            'from' => 'required|email',
            'sender' => 'string|nullable',
            'to' => 'required|email',
            'receiver' => 'nullable|string',
            'subject' => 'nullable|string',
            'attachments' => 'nullable|array',
            'language' => 'nullable|string|min:2|max:2',
            'template' => 'nullable|string',
        ];

        if ($allowedMimeTypes !== '*') {
            $rules['attachments.*'] = 'mimetypes:' . implode(',', $allowedMimeTypes);
        }

        $request->validate($rules);
    }
}
