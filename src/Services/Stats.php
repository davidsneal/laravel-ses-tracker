<?php

namespace DavidNeal\LaravelSesTracker\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    private static function formatDates($stat)
    {
        $stat['delivered_at'] = $stat['delivered_at'] ? Carbon::parse($stat['delivered_at'])->toIso8601String() : null;
        $stat['bounced_at'] = $stat['bounced_at'] ? Carbon::parse($stat['bounced_at'])->toIso8601String() : null;
        $stat['complained_at'] = $stat['complained_at'] ? Carbon::parse($stat['complained_at'])->toIso8601String() : null;
        $stat['opened_at'] = $stat['opened_at'] ? Carbon::parse($stat['opened_at'])->toIso8601String() : null;

        return $stat;
    }

    public static function statsForBatch($emailId, $total)
    {
        $stats = SentEmail::getStats($emailId);

        $stats = $stats->map(function($stat) {
            if ($stat['complained']) $stat['status'] = 'complained';
            elseif ($stat['bounced']) $stat['status'] = 'bounced';
            elseif ($stat['opened']) $stat['status'] = 'opened';
            elseif ($stat['delivered']) $stat['status'] = 'delivered';
            else $stat['status'] = 'sending';

            return self::formatDates($stat);
        });

        try {
            $percentage = (int) number_format(($stats->count() / $total) * 100, 0);
        } catch (\Exception $e) {
            $percentage = 0;
        }

        $deliveredCount = $stats->where('delivered', true)->count();
        $openedCount = $stats->where('opened', true)->count();

        return [
            'data' => $stats,
            'percentage_sent' => $percentage,
            'counts' => [
                'sent' => $stats->count(),
                'delivered' => $deliveredCount,
                'opened' => $openedCount,
                'bounced' => $stats->where('bounced', true)->count(),
                'complained' => $stats->where('complained', true)->count(),
                'sending' => $stats->where('status', 'sending')->count(),
                'unopened' => $deliveredCount - $openedCount,
            ],
            'clicks' => SentEmail::getNumberOfUsersThatClickedAtLeastOneLink($emailId),
            'link_popularity' => SentEmail::getLinkPopularityOrder($emailId)
        ];
    }
}
