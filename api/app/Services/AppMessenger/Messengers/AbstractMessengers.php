<?php

namespace App\Services\AppMessenger\Messengers;

use App\Services\AppMessenger\MessengerInterface;

abstract class AbstractMessengers implements MessengerInterface
{
    /**
     * @var string
     */
    protected $sender;

    /**
     * @var string
     */
    protected $from;

    /**
     * @var string
     */
    protected $recipient;

    /**
     * @var string
     */
    protected $whom;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $message;


    /** Отправитель
     * @param string $value
     * @return MessengerInterface
     */
    public function setSender(string $value): MessengerInterface
    {
        $this->sender = $value;

        return $this;
    }

    /** Наименование отправителя
     * @param string $value
     * @return MessengerInterface
     */
    public function setFrom(string $value): MessengerInterface
    {
        $this->from = $value;

        return $this;
    }


    /** Получатель
     * @param string $value
     * @return MessengerInterface
     */
    public function setRecipient(string $value): MessengerInterface
    {
        $this->recipient = $value;

        return $this;
    }

    /** Наименование получателя
     * @param string $value
     * @return MessengerInterface
     */
    public function setToWhom(string $value): MessengerInterface
    {
        $this->whom = $value;

        return $this;
    }

    /** Тема сообщения
     * @param string $value
     * @return MessengerInterface
     */
    public function setSubject(string $value): MessengerInterface
    {
        $this->subject = $value;

        return $this;
    }


    /** Сообщение
     * @param array $value
     * @return MessengerInterface
     */
    public function setMessageMail(array $value): MessengerInterface
    {
        $this->message = $value;

        return $this;
    }

    /** Сообщение
     * @param string $value
     * @return MessengerInterface
     */
    public function setMessageSms(string $value): MessengerInterface
    {
        $this->message = $value;

        return $this;
    }


    /** Отправить сообщение
     * @return bool
     */
    public function send(): bool
    {
        return true;
    }

}
