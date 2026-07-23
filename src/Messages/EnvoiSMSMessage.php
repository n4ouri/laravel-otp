<?php

namespace EnvoiSMS\Laravel\Messages;

class EnvoiSMSMessage
{
    public string $content = '';
    public ?string $from = null;
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
