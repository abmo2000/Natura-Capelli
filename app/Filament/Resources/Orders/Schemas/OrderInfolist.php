<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Group::make([
                    Section::make('Order Information')
                        ->schema([
                            TextEntry::make('status')
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'pending' => 'warning',
                                    'processing' => 'info',
                                    'completed' => 'success',
                                    'cancelled' => 'danger',
                                    default => 'gray',
                                }),
                            TextEntry::make('payment_method')
                                ->badge()
                                ->icon(fn (string $state): string => match ($state) {
                                    'cash_on_delivery' => 'heroicon-m-banknotes',
                                    'instapay' => 'heroicon-m-credit-card',
                                    default => 'heroicon-m-currency-dollar',
                                }),
                            TextEntry::make('customer_type')
                                ->badge()
                                ->color('gray'),
                            TextEntry::make('delivery_option')
                                ->label('Delivery Discuss Option')
                                ->badge()
                                ->color('info')
                                ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state)))
                                ->placeholder('N/A'),
                        ])
                        ->columns(4)
                        ->icon('heroicon-o-shopping-bag'),

                    Section::make('Order Items')
                        ->schema([
                            RepeatableEntry::make('items')
                                ->label('')
                                ->schema([
                                    TextEntry::make('typeable.name')
                                        ->label('Product')
                                        ->weight('semibold')
                                        ->icon('heroicon-m-cube'),
                                    TextEntry::make('quantity')
                                        ->label('Qty')
                                        ->badge()
                                        ->color('info'),
                                    TextEntry::make('typeable.price')
                                        ->label('Unit Price')
                                        ->money('EGP')
                                        ->color('gray'),
                                    TextEntry::make('typeable.sale.sale_price')
                                        ->label('Sale Price')
                                        ->money('EGP')
                                        ->color('success')
                                        ->weight('semibold')
                                        ->placeholder('N/A'),
                                    TextEntry::make('amount')
                                        ->label('Subtotal')
                                        ->money('EGP')
                                        ->weight('bold')
                                        ->color('warning'),
                                ])
                                ->columns(5)
                                ->columnSpanFull(),
                        ])
                        ->icon('heroicon-o-shopping-cart'),
                ])
                    ->columnSpan(1),

                Group::make([
                    Section::make('Customer Details')
                        ->schema([
                            TextEntry::make('customer.name')
                                ->label('Name')
                                ->icon('heroicon-m-user')
                                ->weight('medium')
                                ->placeholder('N/A'),
                            TextEntry::make('customer.email')
                                ->label('Email')
                                ->icon('heroicon-m-envelope')
                                ->copyable()
                                ->copyMessage('Email copied!')
                                ->copyMessageDuration(1500)
                                ->placeholder('N/A'),
                            TextEntry::make('customer.phone')
                                ->label('Phone')
                                ->icon('heroicon-m-phone')
                                ->copyable()
                                ->copyMessage('Phone copied!')
                                ->copyMessageDuration(1500)
                                ->placeholder('N/A'),
                            TextEntry::make('customer_address')
                                ->label('Delivery Address')
                                ->icon('heroicon-m-map-pin')
                                ->columnSpanFull()
                                ->placeholder('N/A'),
                            TextEntry::make('customer.insta_account')
                                ->label('Customer Instapay Account')
                                ->placeholder('N/A')
                                ->icon('heroicon-m-credit-card')
                                ->copyable()
                                ->copyMessage('Instapay account copied!')
                                ->copyMessageDuration(1500),
                        ])
                        ->columns(3)
                        ->icon('heroicon-o-user-circle')
                        ->collapsible(),

                    Section::make('Order Summary')
                        ->schema([
                            TextEntry::make('items')
                                ->label('Items Total')
                                ->money('EGP')
                                ->state(function ($record) {
                                    return $record->items->sum('amount');
                                })
                                ->weight('semibold')
                                ->size('lg')
                                ->color('success'),
                            TextEntry::make('amount')
                                ->label('Grand Total')
                                ->money('EGP')
                                ->weight('bold')
                                ->size('lg')
                                ->color('success')
                                ->icon('heroicon-m-currency-dollar'),
                        ])
                        ->columns(2)
                        ->icon('heroicon-o-calculator'),
                ])
                    ->columnSpan(1),

                Section::make('Timeline')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Order Placed')
                            ->dateTime('M d, Y - h:i A')
                            ->icon('heroicon-m-calendar')
                            ->color('gray')
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime('M d, Y - h:i A')
                            ->icon('heroicon-m-clock')
                            ->color('gray')
                            ->placeholder('-')
                            ->since(),
                    ])
                    ->columns(2)
                    ->icon('heroicon-o-clock')
                    ->collapsed()
                    ->columnSpanFull(),
            ]);
    }
}
