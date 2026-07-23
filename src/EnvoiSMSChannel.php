<?php

namespace EnvoiSMS\Laravel;

use EnvoiSMS\Client;
use EnvoiSMS\Laravel\Messages\EnvoiSMSMessage;
use Illuminate\Notifications\Notification;
use RuntimeException;

class EnvoiSMSChannel
{
    public function __construct(protected Client $client) {}

    public function send($notifiable, Notification $notification): ?array
    {
        if (! method_exists($notification, 'toEnvoiSMS')) {
            throw new RuntimeException('Notification missing toEnvoiSMS method');
        }

        /** @var EnvoiSMSMessage|string $message */
        $message = $notification->toEnvoiSMS($notifiable);

        $to = $notifiable->routeNotificationFor('envoisms', $notification)
            ?? $notifiable->routeNotificationFor('sms', $notification)
            ?? $notifiable->phone
            ?? $notifiable->mobile;

        if (! $to) {
            return null;
        }

        if (is_string($message)) {
            $message = (new EnvoiSMSMessage())->content($message);
        }

        if ($message->isOtp) {
            return $this->client->sendOtp([
                'to' => $to,
                'brand' => $message->brand ?? config('app.name'),
                'code_length' => $message->codeLength,
                'expiry' => $message->expiry,
                'channel' => $message->channel,
            ]);
        }

        return $this->client->send([
            'to' => $to,
            'message' => $message->content,
            'from' => $message->from ?? config('services.envoisms.from'),
            'channel' => $message->channel,
        ]);
    }
}
