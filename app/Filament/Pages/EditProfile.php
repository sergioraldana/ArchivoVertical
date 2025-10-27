<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EditProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected string $view = 'filament.pages.edit-profile';

    protected static ?string $navigationLabel = 'Mi Perfil';

    protected static ?string $title = 'Mi Perfil';

    protected static ?int $navigationSort = 100;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'email' => Auth::user()->email,
        ]);
    }

    public function form($form)
    {
        return $form
            ->schema([
                TextInput::make('email')
                    ->label('Correo electrónico')
                    ->email()
                    ->required()
                    ->rules([
                        'required',
                        'email',
                        'max:255',
                        Rule::unique('users', 'email')->ignore(Auth::id()),
                    ]),
                TextInput::make('current_password')
                    ->label('Contraseña actual')
                    ->password()
                    ->dehydrated(false)
                    ->required()
                    ->currentPassword(),
                TextInput::make('password')
                    ->label('Nueva contraseña')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state))
                    ->minLength(5)
                    ->same('password_confirmation')
                    ->helperText('Dejar en blanco para mantener la contraseña actual (mínimo 5 caracteres)'),
                TextInput::make('password_confirmation')
                    ->label('Confirmar nueva contraseña')
                    ->password()
                    ->dehydrated(false),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $user = Auth::user();

        // Actualizar email
        $user->email = $data['email'];

        // Actualizar contraseña si se proporcionó una nueva
        if (filled($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        // Limpiar campos de contraseña
        $this->form->fill([
            'email' => $user->email,
            'current_password' => null,
            'password' => null,
            'password_confirmation' => null,
        ]);

        Notification::make()
            ->title('Perfil actualizado')
            ->success()
            ->send();
    }
}
