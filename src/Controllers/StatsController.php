<?php

namespace DavidNeal\LaravelSesTracker\Controllers;

use Illuminate\Routing\Controller;
use SesMail;

class StatsController extends BaseController
{
    public function statsForBatch($emailId)
    {
        return ['success' => true, 'data' => SesMail::statsForBatch($emailId)];
    }

    public function statsForEmail($email)
    {
        return ['success' => true, 'data' => SesMail::statsForEmail($email)];
    }
}
