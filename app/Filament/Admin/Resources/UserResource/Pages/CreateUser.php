<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Models\Role;
use App\Services\Users\UserCreationService;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected static bool $canCreateAnother = false;

    private UserCreationService $service;

    public function boot(UserCreationService $service): void
    {
        $this->service = $service;
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(['default' => 1, 'lg' => 3])
            ->schema([
                TextInput::make('username')
                    ->label(trans('admin/user.username'))
                    ->alphaNum()
                    ->required()
                    ->unique()
                    ->minLength(3)
                    ->maxLength(255),
                TextInput::make('email')
                    ->label(trans('admin/user.email'))
                    ->email()
                    ->required()
                    ->unique()
                    ->maxLength(255),
                TextInput::make('password')
                    ->label(trans('admin/user.password'))
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip(trans('admin/user.password_help'))
                    ->password(),
                CheckboxList::make('roles')
                    ->disableOptionWhen(fn (string $value): bool => $value == Role::getRootAdmin()->id)
                    ->relationship('roles', 'name')
                    ->dehydrated()
                    ->label(trans('admin/user.admin_roles'))
                    ->columnSpanFull()
                    ->bulkToggleable(false),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->getCreateFormAction()->formId('form'),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function handleRecordCreation(array $data): Model
    {
        $data['root_admin'] = false;

        $roles = $data['roles'];
        $roles = collect($roles)->map(fn ($role) => Role::findById($role));
        unset($data['roles']);

        $user = $this->service->handle($data);

        $user->syncRoles($roles);

        return $user;
    }
}
