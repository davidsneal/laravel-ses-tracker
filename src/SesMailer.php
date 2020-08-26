<?php

namespace DavidNeal\LaravelSesTracker;

use Illuminate\Mail\Mailer;
use DavidNeal\LaravelSesTracker\Models\SentEmail;
use DavidNeal\LaravelSesTracker\SesMailerInterface;
use DavidNeal\LaravelSesTracker\TrackingTrait;
use DavidNeal\LaravelSesTracker\Services\Stats;
use DavidNeal\LaravelSesTracker\Exceptions\TooManyEmails;

class SesMailer extends Mailer implements SesMailerInterface
{
    use TrackingTrait;

    protected function sendSwiftMessage($message)
    {
        $sentEmail = $this->initMessage($message); //adds database record for the email
        $newBody = $this->setupTracking($message->getBody(), $sentEmail); //parses email body and adds tracking functionality
        $message->setBody($newBody); //sets the new parsed body as email body

        parent::sendSwiftMessage($message);
    }

    //this will be called every time
    public function initMessage($message)
    {
        //open tracking etc won't work if emails are sent to more than one recepient at a time
        if (sizeOf($message->getTo()) > 1) {
            throw new TooManyEmails("Tried to send to too many emails only one email may be set");
        }

        $sentEmail = SentEmail::create([
            'message_id' => $message->getId(),
            'email' => key($message->getTo()),
            'email_id' => $this->getEmailId(),
            'sent_at' => now(),
            'delivery_tracking' => $this->deliveryTracking,
            'complaint_tracking' => $this->complaintTracking,
            'bounce_tracking' => $this->bounceTracking
        ]);

        return $sentEmail;
    }

    public function statsForBatch($emailId)
    {
        return Stats::statsForBatch($emailId);
    }

    public function statsForEmail($email)
    {
        return Stats::statsForEmail($email);
    }
}
