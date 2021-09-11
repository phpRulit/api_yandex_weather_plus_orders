<?php

namespace App\Services\AppMessenger\Messengers;

class SmsMessenger extends AbstractMessengers
{

    public function __construct()
    {
        //принять сюда какие-либо параметры...
    }

    /**
     * @return bool
     */
    public function send(): bool
    {
        $this->sentSms();
        return parent::send();
    }

    private function sentSms()
    {
        //прописать отправку
    }

}
