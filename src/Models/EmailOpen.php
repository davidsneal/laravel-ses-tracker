<?php

namespace DavidNeal\LaravelSesTracker\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOpen extends Model
{
    protected $table = 'sent_email_opens';

    protected $guarded = [];
}
