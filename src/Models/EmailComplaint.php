<?php

namespace DavidNeal\LaravelSesTracker\Models;

use Illuminate\Database\Eloquent\Model;

class EmailComplaint extends Model
{
    protected $table = 'laravel_ses_tracker_email_complaints';

    protected $guarded = [];
}
