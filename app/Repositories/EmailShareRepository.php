<?php

namespace App\Repositories;

use App\Models\ActivityAccessToken;
use App\Http\Requests\EmailShareRequest;
use App\Mail\EmailShare;
use Illuminate\Support\Facades\Mail;

class EmailShareRepository
{
    /**
     * Store the query and the access token in the activity_access_token table
     */
    public function storeQueryAndToken(EmailShareRequest $request): string
    {
        // current timestamp + 0-100 random number + email
        $randomString = time().rand(0, 100).$request->input('email');
        $urlToken = md5($randomString);

        try {
            ActivityAccessToken::create([
                'user_id' => auth()->user()->id,
                'started_at' => $request->input('email-started-at'),
                'finished_at' => $request->input('email-finished-at'),
                'email' => $request->input('email'),
                'url_token' => $urlToken,
            ]);
        } catch (Exception $exception) {
            throw $exception;
        }

        return $urlToken;
    }

    /**
     * Send email
     */
    public function sendEmail(string $email, string $token, string $subject): void
    {
        $url = route('activities', ['token' => $token]);
        $data = [
            'emailTo' => $email,
            'emailFrom' => config('app.email_sender'),
            'subject' => $subject,
            'sender' => auth()->user()->name,
            'url' => $url
        ];

        try {
            Mail::to($email)->send(new EmailShare((object)$data));
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
