<?php

namespace DavidNeal\LaravelSesTracker\Services;

use Illuminate\Support\Facades\DB;

use DavidNeal\LaravelSesTracker\Models\SentEmail;
use DavidNeal\LaravelSesTracker\Models\EmailLink;
use DavidNeal\LaravelSesTracker\Models\EmailBounce;
use DavidNeal\LaravelSesTracker\Models\EmailComplaint;
use DavidNeal\LaravelSesTracker\Models\EmailOpen;

class Stats
{
    public static function statsForSentEmail($email)
    {
        return [
            'counts' => [
                'sent_emails' => SentEmail::whereEmail($email)->count(),
                'deliveries' => SentEmail::whereEmail($email)->whereNotNull('delivered_at')->count(),
                'opens' => EmailOpen::whereEmail($email)->whereNotNull('opened_at')->count(),
                'bounces' => EmailBounce::whereEmail($email)->whereNotNull('bounced_at')->count(),
                'complaints' => EmailComplaint::whereEmail($email)->whereNotNull('complained_at')->count(),
                'click_throughs' => EmailLink::join(
                        'sent_emails',
                        'sent_emails.id',
                        'sent_email_links.sent_email_id'
                    )
                    ->where('sent_emails.email', '=', $email)
                    ->whereClicked(true)
                    ->count(DB::raw('DISTINCT(sent_emails.id)')) // if a user clicks two different links on one campaign, only one is counted
            ],
            'data' => [
                'sent_emails' => SentEmail::whereEmail($email)->get(),
                'deliveries' => SentEmail::whereEmail($email)->whereNotNull('delivered_at')->get(),
                'opens' => EmailOpen::whereEmail($email)->whereNotNull('opened_at')->get(),
                'bounces' => EmailComplaint::whereEmail($email)->whereNotNull('bounced_at')->get(),
                'complaints' => EmailComplaint::whereEmail($email)->whereNotNull('complained_at')->get(),
                'click_throughs' => EmailLink::join(
                    'sent_emails',
                    'sent_emails.id',
                    'sent_email_links.sent_email_id'
                )
                ->where('sent_emails.email', '=', $email)
                ->whereClicked(true)
                ->get()
            ]
        ];
    }

    public static function statsForBatch($emailId)
    {
        return [
            'send_count' => SentEmail::numberSentForBatch($emailId),
            'deliveries' => SentEmail::deliveriesForBatch($emailId),
            'opens' => SentEmail::opensForBatch($emailId),
            'bounces' => SentEmail::bouncesForBatch($emailId),
            'complaints' => SentEmail::complaintsForBatch($emailId),
            'click_throughs' => SentEmail::getNumberOfUsersThatClickedAtLeastOneLink($emailId),
            'link_popularity' => SentEmail::getLinkPopularityOrder($emailId)
        ];
    }
}
