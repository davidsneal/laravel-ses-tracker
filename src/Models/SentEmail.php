<?php

namespace DavidNeal\LaravelSesTracker\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SentEmail extends Model
{
    protected $table = 'sent_emails';

    protected $guarded = [];

    public function setDeliveredAt($time)
    {
        $this->delivered_at = $time;
        $this->save();
    }

    public function emailOpen()
    {
        return $this->hasOne(EmailOpen::class);
    }

    public function emailLinks()
    {
        return $this->hasMany(EmailLink::class);
    }

    public function emailBounce()
    {
        return $this->hasOne(EmailBounce::class);
    }

    public function emailComplaint()
    {
        return $this->hasOne(EmailComplaint::class);
    }

    public static function numberSentForBatch($emailId)
    {
        return self::where('email_id', $emailId)
            ->count();
    }

    public static function opensForBatch($emailId)
    {
        return self::select('sent_emails.email', 'opened_at as date')
            ->join(
                'sent_email_opens',
                'sent_emails.id',
                'sent_email_opens.sent_email_id'
            )
            ->where('sent_emails.email_id', $emailId)
            ->whereNotNull('sent_email_opens.opened_at')
            ->get();
    }

    public static function bouncesForBatch($emailId)
    {
        return self::select('sent_emails.email', 'bounced_at as date')
            ->join(
                'sent_email_bounces',
                'sent_emails.id',
                'sent_email_bounces.sent_email_id'
            )
            ->where('sent_emails.email_id', $emailId)
            ->whereNotNull('sent_email_bounces.bounced_at')
            ->get();
    }

    public static function complaintsForBatch($emailId)
    {
        return self::select('sent_emails.email', 'complained_at as date')
            ->join(
                'sent_email_complaints',
                'sent_emails.id',
                'sent_email_complaints.sent_email_id'
            )
            ->where('sent_emails.email_id', $emailId)
            ->whereNotNull('sent_email_complaints.complained_at')
            ->get();
    }

    public static function deliveriesForBatch($emailId)
    {
        return self::select('email', 'delivered_at as date')
            ->where('email_id', $emailId)
            ->whereNotNull('delivered_at')
            ->get();
    }

    public static function getNumberOfUsersThatClickedAtLeastOneLink($emailId)
    {
        return self::where('sent_emails.email_id', $emailId)
            ->join('sent_email_links', function ($join) {
                $join
                    ->on('sent_emails.id', '=', 'sent_email_id')
                    ->where('sent_email_links.clicked', '=', true);
            })
            ->select('email')
            ->count(DB::raw('DISTINCT(email)'));
    }

    public static function getLinkPopularityOrder($emailId)
    {
        return self::where('sent_emails.email_id', $emailId)
            ->join('sent_email_links', function ($join) {
                $join
                    ->on('sent_emails.id', '=', 'sent_email_id')
                    ->where('sent_email_links.clicked', '=', true);
            })
            ->get()
            ->groupBy('original_url')
            ->map(function ($linkClicks) {
                return ['clicks' => $linkClicks->count()];
            })
            ->sortByDesc('clicks');
    }
}
