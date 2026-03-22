<?php

namespace App\Filament\Resources\AdminUsers;

use App\Enums\AdminRole;
use App\Models\User;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\AdminUsers\Pages\ListAdminUsers;
use App\Filament\Resources\AdminUsers\Pages\EditAdminUser;

class AdminUserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $navigationLabel = 'Admin Users';

    protected static ?string $modelLabel = 'Admin User';

    protected static string|UnitEnum|null $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user && $user->isSuperAdmin();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereIn('role_name', AdminRole::toArray());
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),

                Select::make('role_name')
                    ->label('Role')
                    ->options(
                        collect(AdminRole::cases())
                            ->mapWithKeys(fn(AdminRole $role) => [$role->value => $role->label()])
                            ->toArray()
                    )
                    ->required(),

                Select::make('is_approved')
                    ->label('Approval Status')
                    ->options([
                        1 => 'Approved',
                        0 => 'Pending',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('role_name')
                    ->label('Role')
                    ->badge()
                    ->formatStateUsing(fn(AdminRole $state): string => $state->label())
                    ->color(fn(AdminRole $state): string => match ($state) {
                        AdminRole::SUPER_ADMIN => 'danger',
                        AdminRole::SALES_ADMIN => 'info',
                        AdminRole::ACCOUNTING_ADMIN => 'warning',
                        AdminRole::ASSET_ADMIN => 'success',
                    }),

                IconColumn::make('is_approved')
                    ->label('Approved')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role_name')
                    ->label('Role')
                    ->options(
                        collect(AdminRole::cases())
                            ->mapWithKeys(fn(AdminRole $role) => [$role->value => $role->label()])
                            ->toArray()
                    ),

                TernaryFilter::make('is_approved')
                    ->label('Approval Status')
                    ->placeholder('All')
                    ->trueLabel('Approved')
                    ->falseLabel('Pending'),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Admin')
                    ->modalDescription('Are you sure you want to approve this admin? They will be able to access the admin panel.')
                    ->visible(fn(User $record): bool => !$record->is_approved && !$record->isSuperAdmin())
                    ->action(function (User $record): void {
                        $record->update(['is_approved' => true]);
                        Notification::make()
                            ->title('Admin approved successfully')
                            ->success()
                            ->send();
                    }),

                Action::make('revoke')
                    ->label('Revoke')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Revoke Admin Access')
                    ->modalDescription('Are you sure you want to revoke this admin\'s access? They will no longer be able to access the admin panel.')
                    ->visible(fn(User $record): bool => $record->is_approved && !$record->isSuperAdmin())
                    ->action(function (User $record): void {
                        $record->update(['is_approved' => false]);
                        Notification::make()
                            ->title('Admin access revoked')
                            ->warning()
                            ->send();
                    }),
            ])
            ->bulkActions([
                BulkAction::make('approve_selected')
                    ->label('Approve Selected')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Collection $records): void {
                        $records->each(fn(User $record) => $record->update(['is_approved' => true]));
                        Notification::make()
                            ->title('Selected admins approved')
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAdminUsers::route('/'),
            'edit' => EditAdminUser::route('/{record}/edit'),
        ];
    }
}
