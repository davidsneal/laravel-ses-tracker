<?php

namespace andytan07\LaravelSesTracker\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use andytan07\LaravelSesTracker\Models\EmailLink;

class LinkController extends BaseController
{
    public function click($linkIdentifier)
    {
        $link = EmailLink::whereLinkIdentifier($linkIdentifier)->firstOrFail();
        $link->setClicked(true)->incrementClickCount();
        return redirect($link->original_url);
    }
}
