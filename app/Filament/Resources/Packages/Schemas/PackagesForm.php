<?php

namespace App\Filament\Resources\Packages\Schemas;

use App\Models\Product;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Tabs\Tab;

class PackagesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                 Section::make()
                ->schema([
                    Tabs::make('Translations')
                        ->tabs([
                            Tab::make('English')
                                ->schema([
                                    TextInput::make('en.title')
                                        ->label('Title (EN)')
                                        ->required()
                                        ->maxLength(255),

                                    RichEditor::make('en.description')
                                        ->label('Description (EN)')
                                        ->toolbarButtons([
                                            'blockquote', 'bold', 'bulletList', 'codeBlock',
                                            'h2', 'h3', 'italic', 'link', 'orderedList',
                                            'redo', 'strike', 'underline', 'undo',
                                        ]),
                                ]),

                            Tab::make('Arabic')
                                ->schema([
                                    TextInput::make('ar.title')
                                        ->label('Title (AR)')
                                        ->maxLength(255)
                                        ->required()
                                        ->extraAttributes(['dir' => 'rtl', 'style' => 'text-align:right;']),

                                    RichEditor::make('ar.description')
                                        ->label('Description (AR)')
                                        ->extraAttributes(['dir' => 'rtl'])
                                        ->toolbarButtons([
                                            'blockquote', 'bold', 'bulletList', 'codeBlock',
                                            'h2', 'h3', 'italic', 'link', 'orderedList',
                                            'redo', 'strike', 'underline', 'undo',
                                        ]),
                                ]),
                            ])->columnSpanFull(),
                         ]) ->columnSpanFull(),

                Section::make()
                ->schema([
                    TextInput::make('price')
                        ->label('Price')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->inputMode('decimal'),

                  
                        Toggle::make('is_active')->label('Is Active'),

                ])
                ->columns(2),

                Section::make()
                ->schema([
                     Select::make('products')
                        ->label('Products')
                        ->required()
                        ->multiple()
                        ->relationship('products', 'id')
                        ->options(function () {
                            return Product::with('translations')
                                ->get()
                                ->mapWithKeys(function ($product) {
                                    $name = $product->translations->first()?->name ?? 'No title';
                                    return [$product->id => $name];
                                });
                        })
                        ->searchable(),

                ])
                ->columns(1),
            ]);
    }
}
