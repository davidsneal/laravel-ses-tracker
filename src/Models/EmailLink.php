<?php

namespace DavidNeal\LaravelSesTracker\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLink extends Model
{
    protected $table = 'sent_email_links';

    protected $guarded = [];

    public function sentEmail()
    {
        return $this->belongsTo(SentEmail::class);
    }

    public function setClicked($clicked)
    {
        $this->clicked = $clicked;
        $this->save();
        return $this;
    }

    public function incrementClickCount()
    {
        $this->click_count++;
        $this->save();
        return $this;
    }
}
