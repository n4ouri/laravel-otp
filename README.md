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
