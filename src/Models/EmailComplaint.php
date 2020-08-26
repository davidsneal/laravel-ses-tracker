<?php

namespace DavidNeal\LaravelSesTracker\Models;

use Illuminate\Database\Eloquent\Model;

class EmailComplaint extends Model
{
    protected $table = 'sent_email_complaints';

    protected $guarded = [];
}
