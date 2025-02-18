<?php

namespace App\Extensions\OAuth\Providers;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use SocialiteProviders\Authelia\Provider;

final class AutheliaProvider extends OAuthProvider
{
    public function getId(): string
    {
        return 'authelia';
    }

    public function getProviderClass(): string
    {
        return Provider::class;
    }

    public function getServiceConfig(): array
    {
        return [
            'base_url' => env('AUTHELIA_BASE_URL'),
            'client_id' => env('AUTHELIA_CLIENT_ID'),
            'client_secret' => env('AUTHELIA_CLIENT_SECRET'),
            'redirect' => env('AUTHELIA_REDIRECT_URI'),
        ];
    }

    public function getSettingsForm(): array
    {
        return array_merge(parent::getSettingsForm(), [
            TextInput::make('AUTHELIA_BASE_URL')
                ->label('Base URL')
                ->placeholder('https://your-authelia-instance.com')
                ->columnSpan(2)
                ->required()
                ->url()
                ->autocomplete(false)
                ->default(env('AUTHELIA_BASE_URL')),
            TextInput::make('AUTHELIA_DISPLAY_NAME')
                ->label('Display Name')
                ->placeholder('Authelia')
                ->autocomplete(false)
                ->default(env('AUTHELIA_DISPLAY_NAME', 'Authelia')),
            ColorPicker::make('AUTHELIA_DISPLAY_COLOR')
                ->label('Display Color')
                ->placeholder('#009688')
                ->default(env('AUTHELIA_DISPLAY_COLOR', '#009688'))
                ->hex(),
        ]);
    }

    public function getName(): string
    {
        return env('AUTHELIA_DISPLAY_NAME', 'Authelia');
    }

    public function getHexColor(): string
    {
        return env('AUTHELIA_DISPLAY_COLOR', '#009688');
    }

    public static function register(): self
    {
        return new self();
    }
}
