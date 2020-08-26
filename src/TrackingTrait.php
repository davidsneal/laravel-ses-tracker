<?php

namespace DavidNeal\LaravelSesTracker;

use DavidNeal\LaravelSesTracker\Models\SentEmail;
use DavidNeal\LaravelSesTracker\MailProcessor;

trait TrackingTrait
{
    private $openTracking = false;
    private $linkTracking = false;
    private $bounceTracking = false;
    private $complaintTracking = false;
    private $deliveryTracking = false;
    private $emailId;


    public function setupTracking($emailBody, SentEmail $sentEmail)
    {
        $mailProcessor = new MailProcessor($sentEmail, $emailBody);

        if ($this->openTracking) {
            $mailProcessor->openTracking();
        }
        if ($this->linkTracking) {
            $mailProcessor->linkTracking();
        }

        return $mailProcessor->getEmailBody();
    }

    public function setEmailId($id)
    {
        $this->email_id = $id;
        return $this;
    }

    public function getEmailId()
    {
        return $this->email_id;
    }

    public function setContactId($id)
    {
        $this->contact_id = $id;
        return $this;
    }

    public function getContactId()
    {
        return $this->contact_id;
    }

    public function enableOpenTracking()
    {
        $this->openTracking = true;
        return $this;
    }

    public function enableLinkTracking()
    {
        $this->linkTracking = true;
        return $this;
    }

    public function enableBounceTracking()
    {
        $this->bounceTracking = true;
        return $this;
    }

    public function enableComplaintTracking()
    {
        $this->complaintTracking = true;
        return $this;
    }

    public function enableDeliveryTracking()
    {
        $this->deliveryTracking = true;
        return $this;
    }

    public function disableOpenTracking()
    {
        $this->openTracking = false;
        return $this;
    }

    public function disableLinkTracking()
    {
        $this->linkTracking = false;
        return $this;
    }

    public function disableBounceTracking()
    {
        $this->bounceTracking = false;
        return $this;
    }

    public function disableComplaintTracking()
    {
        $this->complaintTracking = false;
        return $this;
    }

    public function disableDeliveryTracking()
    {
        $this->deliveryTracking = false;
        return $this;
    }

    public function enableAllTracking()
    {
        return $this->enableOpenTracking()
            ->enableLinkTracking()
            ->enableBounceTracking()
            ->enableComplaintTracking()
            ->enableDeliveryTracking();
    }

    public function disableAllTracking()
    {
        return $this->disableOpenTracking()
            ->disableLinkTracking()
            ->disableBounceTracking()
            ->disableComplaintTracking()
            ->disableDeliveryTracking();
    }



    public function trackingSettings()
    {
        return [
            'openTracking' => $this->openTracking,
            'linkTracking' => $this->linkTracking,
            'bounceTracking' => $this->bounceTracking,
            'complaintTracking' => $this->complaintTracking,
            'deliveryTracking' => $this->deliveryTracking
         ];
    }
}
