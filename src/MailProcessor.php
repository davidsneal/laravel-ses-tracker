<?php

namespace andytan07\LaravelSesTracker;

use Ramsey\Uuid\Uuid;
use andytan07\LaravelSesTracker\Models\EmailOpen;
use andytan07\LaravelSesTracker\Models\SentEmail;
use andytan07\LaravelSesTracker\Models\EmailLink;
use PHPHtmlParser\Dom;

class MailProcessor
{
    protected $emailBody;
    protected $batch;
    protected $sentEmail;

    public function __construct(SentEmail $sentEmail, $emailBody)
    {
        $this->setEmailBody($emailBody);
        $this->setSentEmail($sentEmail);
    }

    public function getEmailBody()
    {
        return $this->emailBody;
    }

    private function setEmailBody($body)
    {
        $this->emailBody = $body;
    }

    private function setSentEmail(SentEmail $email)
    {
        $this->sentEmail = $email;
    }

    public function openTracking()
    {
        $beaconIdentifier = Uuid::uuid4()->toString();
        $beaconUrl = config('app.url') . "/laravel-ses/beacon/$beaconIdentifier";

        EmailOpen::create
        ([
            'sent_email_id' => $this->sentEmail->id,
            'email' => $this->sentEmail->email,
            'batch' => $this->sentEmail->batch,
            'beacon_identifier' => $beaconIdentifier,
            'url' => $beaconUrl,
        ]);

        $this->setEmailBody($this->getEmailBody() . "<img src=\"$beaconUrl\"" . " alt=\"\" style=\"width:1px;height:1px;\"/>");
        return $this;
    }

    public function linkTracking()
    {
        $url_expression = '/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i';
        preg_match_all($url_expression, $this->getEmailBody(), $result);

        foreach($result[2] as $originalUrl)
        {
            $this->createAppLink($originalUrl);
        }

        return $this;
    }

    private function createAppLink(string $originalUrl)
    {
        $linkIdentifier = Uuid::uuid4()->toString();
        $linkUrl = "https://rapportstar.ml/laravel-ses/link/$linkIdentifier";

        $link = EmailLink::create
        ([
            'sent_email_id' => $this->sentEmail->id,
            'batch' => $this->sentEmail->batch,
            'link_identifier' => $linkIdentifier,
            'original_url' => $originalUrl
        ]);

        $replaceUrl = str_replace($originalUrl, $linkUrl, $this->getEmailBody());
        $this->setEmailBody($replaceUrl);

        return $this;
    }
}