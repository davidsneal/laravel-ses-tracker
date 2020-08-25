<?php

namespace DavidNeal\LaravelSesTracker\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOpen extends Model
{
    protected $table = 'laravel_ses_tracker_email_opens';

    protected $guarded = [];
}
