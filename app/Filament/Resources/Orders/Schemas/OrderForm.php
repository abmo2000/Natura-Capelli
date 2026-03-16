<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Customer Details')
                    ->description('Edit the customer\'s personal information.')
                    ->icon('heroicon-o-user-circle')
                    ->columns(2)
                    ->schema([
                        TextInput::make('customer_name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('customer_email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('customer_phone')
                            ->label('Phone Number')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('customer_insta_account')
                            ->label('Instapay Account')
                            ->maxLength(255),
                        Textarea::make('customer_address')
                            ->label('Delivery Address')
                            ->columnSpanFull()
                            ->rows(2),
                    ]),

                Section::make('Order Status')
                    ->description('Update order status and payment details.')
                    ->icon('heroicon-o-shopping-bag')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->options(OrderStatus::toAssociativeArray())
                            ->required(),
                        Select::make('payment_method')
                            ->options([
                                'cash_on_delivery' => 'Cash on Delivery',
                                'instapay' => 'InstaPay',
                            ])
                            ->required(),
                        TextInput::make('amount')
                            ->label('Grand Total (EGP)')
                            ->numeric()
                            ->required()
                            ->prefix('EGP'),
                        TextInput::make('delivery_price')
                            ->label('Delivery Fee (EGP)')
                            ->numeric()
                            ->default(0)
                            ->prefix('EGP'),
                    ]),

                Section::make('Order Notes')
                    ->description('Customer notes or admin remarks.')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->schema([
                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->columnSpanFull(),
            ]);
    }
}
