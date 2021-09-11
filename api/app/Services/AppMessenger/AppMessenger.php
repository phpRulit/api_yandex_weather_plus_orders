<?php

namespace App\Services\AppMessenger;

use App\Services\AppMessenger\Messengers\EmailMessenger;
use App\Services\AppMessenger\Messengers\SmsMessenger;

class AppMessenger implements MessengerInterface
{
    //Это класс делегат, с которым будет работать сервис (pattern design)

    /**
     * @var MessengerInterface
     */
    private $messenger;


    /**
     * @return $this
     */
    public function toEmail(string $template)
    {
        $this->messenger = new EmailMessenger($template);

        return $this;
    }

    /**
     * @return $this
     */
    public function toSms()
    {
        $this->messenger = new SmsMessenger();

        return $this;
    }


    /**
     * @param string $value
     *
     * @return MessengerInterface
     */
    public function setSender(string $value): MessengerInterface
    {
        $this->messenger->setSender($value);

        return $this->messenger;
    }

    /**
     * @param string $value
     *
     * @return MessengerInterface
     */
    public function setFrom(string $value): MessengerInterface
    {
        $this->messenger->setFrom($value);

        return $this->messenger;
    }

    /**
     * @param string $value
     *
     * @return MessengerInterface
     */
    public function setRecipient(string $value): MessengerInterface
    {
        $this->messenger->setRecipient($value);

        return $this->messenger;
    }

    /**
     * @param string $value
     *
     * @return MessengerInterface
     */
    public function setToWhom(string $value): MessengerInterface
    {
        $this->messenger->setToWhom($value);

        return $this->messenger;
    }

    /**
     * @param string $value
     *
     * @return MessengerInterface
     */
    public function setSubject(string $value): MessengerInterface
    {
        $this->messenger->setSubject($value);

        return $this->messenger;
    }

    /**
     * @param array $value
     *
     * @return MessengerInterface
     */
    public function setMessageMail(array $value): MessengerInterface
    {
        $this->messenger->setMessageMail($value);

        return $this->messenger;
    }

    /**
     * @param string $value
     *
     * @return MessengerInterface
     */
    public function setMessageSms(string $value): MessengerInterface
    {
        $this->messenger->setMessageSms($value);

        return $this->messenger;
    }

    /** Отправить сообщение
     * @return bool
     */
    public function send(): bool
    {
        return $this->messenger->send();
    }

}
