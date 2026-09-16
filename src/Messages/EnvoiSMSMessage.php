<?php

namespace EnvoiSMS\Laravel\Messages;

class EnvoiSMSMessage
{
    public string $content = '';
    public ?string $from = null;
    /**
     * 'sms' (default) is what an ordinary notification needs — even when the
     * recipient uses WhatsApp. 'whatsapp' sends from YOUR OWN connected
     * WhatsApp Business number: without one the API answers
     * 403 WHATSAPP_NOT_CONNECTED, and free text outside the 24-hour customer
     * window answers 400 OUT_OF_24H_WINDOW. Nothing is charged either way.
     * A one-time code on WhatsApp is ->asOtp(...)->channel('whatsapp'): that
     * goes through EnvoiSMS's shared sender and needs no connection.
     */
    public string $channel = 'sms';
    public ?string $brand = null;
    public bool $isOtp = false;
    public int $codeLength = 6;
    public int $expiry = 600;

    public function content(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function from(string $from): self
    {
        $this->from = $from;
        return $this;
    }

    public function channel(string $channel): self
    {
        $this->channel = $channel;
        return $this;
    }

    public function asOtp(string $brand = 'EnvoiSMS', int $codeLength = 6, int $expiry = 600): self
    {
        $this->isOtp = true;
        $this->brand = $brand;
        $this->codeLength = $codeLength;
        $this->expiry = $expiry;
        return $this;
    }
}
