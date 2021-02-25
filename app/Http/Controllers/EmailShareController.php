<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Requests\EmailShareRequest;
use App\Repositories\EmailShareRepository;

class EmailShareController extends Controller
{
    private $emailShareObj;

    public function __construct()
    {
        $this->emailShareObj = new EmailShareRepository();
    }

    public function index(EmailShareRequest $request)
    {
        try {
            $urlToken = $this->emailShareObj->storeQueryAndToken($request);
            $this->emailShareObj->sendEmail(
                $request->input('email'),
                $urlToken,
                'Access to activities'
            );
        } catch (Exception $exception) {
            return redirect()
            ->back()
            ->withErrors([
                'error' => 'The email couldn\'t be sent. Please try again',
            ]);
        }

        return redirect()
        ->route('activities')
        ->withSuccess([
            'success' => 'Email sent successfully',
        ]);
    }
}
