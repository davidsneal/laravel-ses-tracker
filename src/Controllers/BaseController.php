<?php

namespace DavidNeal\LaravelSesTracker\Controllers;

use Illuminate\Routing\Controller;
use Psr\Http\Message\ServerRequestInterface;
use Aws\Sns\Message;
use Aws\Sns\MessageValidator;
use Aws\Sns\Exception\InvalidSnsMessageException;

class BaseController extends Controller
{
    public function validateSns(ServerRequestInterface $request)
    {
        if (config('laravel-ses-tracker.aws_sns_validator')) {
            $message = Message::fromPsrRequest($request);
            $validator = new MessageValidator();

            try {
                $validator->validate($message);
            } catch (InvalidSnsMessageException $e) {
                // pretend we're not here if the message is invalid
                abort(404, 'Not Found');
            }
        }
    }
}
