<?php

namespace andytan07\LaravelSesTracker\Tests\Feature;

use andytan07\LaravelSesTracker\Models\SentEmail;
use andytan07\LaravelSesTracker\Models\EmailLink;
use andytan07\LaravelSesTracker\Tests\Feature\FeatureTestCase;
use Ramsey\Uuid\Uuid;
use DMS\PHPUnitExtensions\ArraySubset\Assert;

class ClickTrackingTest extends FeatureTestCase
{
    public function testEmailLinksCanBeTracked(): void
    {
        $linkId = Uuid::uuid4()->toString();

        EmailLink::create([
            'sent_email_id' => 11,
            'original_url' => 'https://redirect.com',
            'link_identifier' => $linkId
        ]);

        $res = $this->get("https://laravel-ses.com/laravel-ses/link/$linkId")
            ->assertStatus(302);

        $this->assertEquals('https://redirect.com', $res->getTargetUrl());

        Assert::assertArraySubset([
            'clicked' => true,
            'click_count' => 1
        ], EmailLink::first()->toArray());

        $this->get("https://laravel-ses.com/laravel-ses/link/$linkId");

        Assert::assertArraySubset([
            'clicked' => true,
            'click_count' => 2
        ], EmailLink::first()->toArray());
    }
}
