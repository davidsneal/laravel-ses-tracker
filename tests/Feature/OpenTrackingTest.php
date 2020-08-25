<?php

namespace DavidNeal\LaravelSesTracker\Tests\Feature;

use SesMail;
use DavidNeal\LaravelSesTracker\Models\SentEmail;
use DavidNeal\LaravelSesTracker\Models\EmailOpen;
use DavidNeal\LaravelSesTracker\Tests\Feature\FeatureTestCase;
use DavidNeal\LaravelSesTracker\Mocking\TestMailable;

class OpenTrackingTest extends FeatureTestCase
{
    public function testOpenTracking()
    {
        SesMail::fake();
        SesMail::enableOpenTracking();
        SesMail::to('harrykane9@gmail.com')->send(new TestMailable());

        //send a junk uuid and check error is thrown
        $this->get('laravel-ses/beacon/thisisjunk')
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'errors' => [
                    'Invalid Beacon'
                ]
            ]);

        $res = $this->get('laravel-ses/beacon/' . EmailOpen::first()->beacon_identifier)
            ->assertStatus(302)
            ->assertHeader('location', 'https://laravel-ses.com/laravel-ses/to.png');

        //check email open has been saved
        $this->assertNotNull(EmailOpen::first()->opened_at);
    }
}
