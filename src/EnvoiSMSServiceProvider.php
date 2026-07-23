<?php

namespace EnvoiSMS\Laravel;

use EnvoiSMS\Client;
use Illuminate\Support\ServiceProvider;

class EnvoiSMSServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Client::class, function ($app) {
            $apiKey = config('services.envoisms.api_key', env('ENVOISMS_API_KEY'));
            $baseUrl = config('services.envoisms.base_url', env('ENVOISMS_BASE_URL', 'https://api.envoisms.ma'));

            return new Client($apiKey, $baseUrl);
        });

        $this->app->alias(Client::class, 'envoisms');
    }

    public function boot(): void
    {
        // Service registration complete
    }
}
