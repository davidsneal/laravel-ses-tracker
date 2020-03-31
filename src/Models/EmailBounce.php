<?php

namespace andytan07\LaravelSesTracker\Models;

use Illuminate\Database\Eloquent\Model;

class EmailBounce extends Model
{
    protected $table = 'laravel_ses_email_bounces';

    protected $guarded = [];
}
