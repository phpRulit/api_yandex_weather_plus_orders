<?php

namespace App\Services\AppMessenger;

interface MessengerInterface
{
    /** Отправитель
     * @param string $value
     * @return mixed
     */
    public function setSender(string $value): MessengerInterface;

    /** Наименование отправителя
     * @param string $value
     * @return mixed
     */
    public function setFrom(string $value): MessengerInterface;


    /** Получатель
     * @param string $value
     * @return mixed
     */
    public function setRecipient(string $value): MessengerInterface;


    /** Наименование получателя
     * @param string $value
     * @return mixed
     */
    public function setToWhom(string $value): MessengerInterface;


    /** Тема сообщения
     * @param string $value
     * @return mixed
     */
    public function setSubject(string $value): MessengerInterface;

    /** Сообщение mail
     * @param array $value
     * @return mixed
     */
    public function setMessageMail(array $value): MessengerInterface;


    /** Сообщение sms
     * @param string $value
     * @return mixed
     */
    public function setMessageSms(string $value): MessengerInterface;


    /** Отправить сообщение
     * @return bool
     */
    public function send(): bool;
}
