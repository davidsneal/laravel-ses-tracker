<?php

namespace DavidNeal\LaravelSesTracker\Models;

use Illuminate\Database\Eloquent\Model;

class EmailBounce extends Model
{
    protected $table = 'sent_email_bounces';

    protected $guarded = [];
}
