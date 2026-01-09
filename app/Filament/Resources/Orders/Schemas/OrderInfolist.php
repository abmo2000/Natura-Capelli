<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('customer_type'),
                TextEntry::make('payment_method'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('amount')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),

                    TextEntry::make('customer.email')
                            ->label('Customer Email')
                            ->icon('heroicon-m-envelope')
                            ->copyable()
                            ->placeholder('N/A'),
                        TextEntry::make('customer.name')
                            ->label('Customer Name')
                            ->placeholder('N/A'),
                            TextEntry::make('customer.phone')
                            ->label('Customer Phone')
                            ->placeholder('N/A'),
                        TextEntry::make('customer_address')
                            ->label('Delivery Address')
                            ->columnSpanFull(), 
                        
                RepeatableEntry::make('items')
                    ->label('Order Items')
                    ->schema([
                        TextEntry::make('typeable.name')
                            ->label('Product'),
                        TextEntry::make('quantity')
                            ->label('Qty'),
                        TextEntry::make('amount')
                        ->money('EGP')
                            ->label('amount'),    
                        TextEntry::make('typeable.price')
                            ->label('Price')
                            ->money('EGP'),
                       TextEntry::make('typeable.sale.sale_price')
                        ->label('On Sale')
                        ->money('EGP')
                        ->placeholder('N/A')

                      
                    ])
                    ->columns(5)
                    ->columnSpanFull(),

                    TextEntry::make('items')
                    ->label('Items Total')
                    ->money("EGP")
                    ->state(function ($record) {
                        return $record->items->sum('amount');
                    })
                    ->weight('bold'),
                            

                           
            ]);
    }
}
