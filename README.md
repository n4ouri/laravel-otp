# EnvoiSMS Laravel OTP & Notification Channel

Official Laravel Notification Channel & OTP Package for [EnvoiSMS.ma](https://envoisms.ma) — [Passerelle SMS & WhatsApp Business](https://envoisms.ma/fr/docs) and [API SMS Maroc](https://envoisms.ma/fr/tarifs).

For full API documentation, visit the [Passerelle SMS & WhatsApp Business](https://envoisms.ma/fr/docs). For pricing plans and credit packs, visit [API SMS Maroc](https://envoisms.ma/fr/tarifs).

## Installation

Install via Composer:

```bash
composer require envoisms/laravel-otp
```

## Configuration

Add your API credentials to `.env`:

```env
ENVOISMS_API_KEY=your_api_key_here
ENVOISMS_FROM=MonBusiness
```

Optionally publish or add to `config/services.php`:

```php
'envoisms' => [
    'api_key' => env('ENVOISMS_API_KEY'),
    'from' => env('ENVOISMS_FROM', 'EnvoiSMS'),
],
```

## Usage in Laravel Notifications

Create a notification class:

```php
namespace App\Notifications;

use EnvoiSMS\Laravel\EnvoiSMSChannel;
use EnvoiSMS\Laravel\Messages\EnvoiSMSMessage;
use Illuminate\Notifications\Notification;

class SendOtpNotification extends Notification
{
    public function via($notifiable): array
    {
        return [EnvoiSMSChannel::class];
    }

    public function toEnvoiSMS($notifiable): EnvoiSMSMessage
    {
        return (new EnvoiSMSMessage())
            ->asOtp(brand: 'MonApp', codeLength: 6, expiry: 600);
    }
}
```

Send the notification to any model with a `phone` or `routeNotificationForEnvoiSMS()` method:

```php
$user->notify(new SendOtpNotification());
```

## Plain-text notifications (order status, reminders, alerts)

```php
public function toEnvoiSMS($notifiable): EnvoiSMSMessage
{
    return (new EnvoiSMSMessage())
        ->content("Bonjour {$notifiable->first_name}, votre commande #{$this->order->id} est expédiée.")
        ->from('MonBusiness'); // validated Sender ID, or omit for the account default
}
```

The channel defaults to `sms`, and that is the right choice for any ordinary text — even to a customer who uses WhatsApp. `->channel('whatsapp')` sends from **your own connected WhatsApp Business number**: without one the API answers `403 WHATSAPP_NOT_CONNECTED`, and free text outside the 24-hour customer window answers `400 OUT_OF_24H_WINDOW`. Nothing is charged in either case. A one-time code on WhatsApp is `->asOtp(...)->channel('whatsapp')`: it goes through EnvoiSMS's shared sender and needs no connection.

Sends carry an `Idempotency-Key` (from the underlying PHP SDK), so a queued notification that is retried never bills twice.

## Using the Facade Directly

```php
use EnvoiSMS\Laravel\Facades\EnvoiSMS;

// Send OTP
$response = EnvoiSMS::sendOtp([
    'to' => '+212600000000',
    'brand' => 'MonApp',
]);

// Check OTP Code
$result = EnvoiSMS::checkOtp(
    sessionId: $response['session_id'],
    code: '123456'
);

if ($result['verified']) {
    // Code valid
}
```

## Documentation & Tarifs

- Guides et référence API : [Passerelle SMS & WhatsApp Business](https://envoisms.ma/fr/docs)
- Tarifs et packs SMS Maroc : [API SMS Maroc](https://envoisms.ma/fr/tarifs)

## License

MIT
