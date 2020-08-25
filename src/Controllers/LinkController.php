<?php

namespace DavidNeal\LaravelSesTracker\Controllers;

use DavidNeal\LaravelSesTracker\Models\EmailLink;

class LinkController extends BaseController
{
    public function click($linkIdentifier)
    {
        $link = EmailLink::whereLinkIdentifier($linkIdentifier)->firstOrFail();
        $link->setClicked(true)->incrementClickCount();
        return redirect($link->original_url);
    }
}
