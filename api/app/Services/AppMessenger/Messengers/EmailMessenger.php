<?php

namespace App\Services\AppMessenger\Messengers;

use App\Mail\AppShipped;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;

class EmailMessenger extends AbstractMessengers implements ShouldQueue
{
    private $template;

    public function __construct(string $template)
    {
        $this->template = $template;
    }
    /**
     * @return bool
     */
    public function send(): bool
    {
        $this->sendMail();
        return parent::send();
    }

    private function sendMail()
    {
        /* //Отправка без очереди
         * Mail::send($this->template, ['message' => $this->message],
            function($mail) {
                $mail->from($this->sender, $this->from);
                $mail->to($this->recipient, $this->whom);
                $mail->subject($this->subject);
            });
        */
        $params = [
            'sender' => $this->sender,
            'from' => $this->from,
            'recipient' => $this->recipient,
            'whom' => $this->whom,
            'subject' => $this->subject,
            'template' => $this->template,
            'message' => ['data' => $this->message], //не использовать ключ message (зарезервирован Laravel), поэтому data
        ];
        Mail::queueOn('mail', new AppShipped($params));
    }

}
