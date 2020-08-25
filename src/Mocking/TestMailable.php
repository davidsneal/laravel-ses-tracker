<?php

namespace DavidNeal\LaravelSesTracker\Mocking;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TestMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $test = 'Just some test text';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('test@example.com')
            ->view('LaravelSes::test');
    }
}
