<?php

namespace DavidNeal\LaravelSesTracker;

use DavidNeal\LaravelSesTracker\Models\SentEmail;

interface SesMailerInterface
{
    public function initMessage($message);
    public function statsForBatch($emailId, $total);
    public function statsForEmail($email);
    public function setupTracking($setupTracking, SentEmail $sentEmail);
    public function setEmailId($id);
    public function getEmailId();
    public function setContactId($id);
    public function getContactId();
    public function enableOpenTracking();
    public function enableLinkTracking();
    public function enableBounceTracking();
    public function enableComplaintTracking();
    public function enableDeliveryTracking();
    public function disableOpenTracking();
    public function disableLinkTracking();
    public function disableBounceTracking();
    public function disableComplaintTracking();
    public function disableDeliveryTracking();
    public function enableAllTracking();
    public function disableAllTracking();
    public function trackingSettings();
}
