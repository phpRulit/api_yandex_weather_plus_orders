<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppShipped extends Mailable
{
    use Queueable, SerializesModels;

    private $params;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->params['subject'])
            ->from($this->params['sender'], $this->params['from'])
            ->to($this->params['recipient'], $this->params['whom'])
            ->view($this->params['template'])
            ->with($this->params['message']);
    }
}
