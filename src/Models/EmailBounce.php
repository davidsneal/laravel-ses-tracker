<?php

namespace DavidNeal\LaravelSesTracker\Models;

use Illuminate\Database\Eloquent\Model;

class EmailBounce extends Model
{
    protected $table = 'laravel_ses_tracker_email_bounces';

    protected $guarded = [];
}
