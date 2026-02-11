<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Component;

class CustomLogin extends Login
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(), // Gunakan Name bukan Email
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getNameFormComponent(): Component
    {
        return TextInput::make('name') // Pastikan ini 'name'
            ->label('Username / Name')
            ->required()
            ->autocomplete()
            ->autofocus();
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'name' => $data['name'], // Memberitahu Laravel untuk cek kolom 'name'
            'password'  => $data['password'],
        ];
    }
}