<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Routine;
use App\Models\Category;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // Translations & Image side-by-side
            Section::make()
                ->schema([
                    Tabs::make('Translations')
                        ->tabs([
                            Tab::make('English')
                                ->schema([
                                    TextInput::make('en.name')
                                        ->label('Name (EN)')
                                        ->required()
                                        ->maxLength(255),

                                    RichEditor::make('en.description')
                                        ->label('Description (EN)')
                                        ->required()
                                        ->toolbarButtons([
                                            'blockquote', 'bold', 'bulletList', 'codeBlock',
                                            'h2', 'h3', 'italic', 'link', 'orderedList',
                                            'redo', 'strike', 'underline', 'undo',
                                        ]),
                                ]),

                            Tab::make('Arabic')
                                ->schema([
                                    TextInput::make('ar.name')
                                        ->label('Name (AR)')
                                        ->required()
                                        ->maxLength(255)
                                        ->extraAttributes(['dir' => 'rtl', 'style' => 'text-align:right;']),

                                    RichEditor::make('ar.description')
                                        ->label('Description (AR)')
                                        ->required()
                                        ->extraAttributes(['dir' => 'rtl'])
                                        ->toolbarButtons([
                                            'blockquote', 'bold', 'bulletList', 'codeBlock',
                                            'h2', 'h3', 'italic', 'link', 'orderedList',
                                            'redo', 'strike', 'underline', 'undo',
                                        ]),
                                ]),
                            ])->columnSpanFull(),
                         ]) ->columnSpanFull(),

                     FileUpload::make('image')
                        ->label('Image')
                        ->required()
                        ->image()
                        ->imageEditor()
                        ->directory('products')
                        ->visibility('public')
                        ->columnSpanFull(),

            
                        Section::make()
                            ->schema([
                                TextInput::make('price')
                                    ->label('Price')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->inputMode('decimal'),

                                    TextInput::make('capacity')
                                    ->label('Capacity')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1),

                                Select::make('routines')
                                    ->label('Routines')
                                    ->required()
                                    ->multiple()
                                    ->relationship('routines', 'id')
                                    ->options(function () {
                                        return Routine::with('translations')
                                            ->get()
                                            ->mapWithKeys(function ($routine) {
                                                $title = $routine->translations->first()?->title ?? 'No title';
                                                return [$routine->id => $title];
                                            });
                                    })
                                    ->searchable(),

                                Select::make('category_id')
                                    ->label('Category')
                                    ->required()
                                    ->options(
                                        Category::with('translations')
                                            ->get()
                                            ->pluck('title', 'id')
                                    )
                                    ->searchable(),
                            ])
                            ->columns(2),

                        // Featured + In Stock
                        Section::make()
                            ->schema( [
                                Toggle::make('featured')->label('Featured'),
                                Toggle::make('in_stock')->label('In Stock'),
                            ])
                            ->columns(2),

                        //Trials   
                        Section::make('Trial')
                        ->schema([
                            Toggle::make('has_trial')
                            ->label('Has Trial')
                            ->reactive()
                            ->afterStateHydrated(function ($state, callable $set, $record) {
                                $set('has_trial', $record?->trial()->exists());
                            }),

                            Group::make([

                                TextInput::make('trial_price')
                                    ->label('Trial Price')
                                    ->numeric()
                                    ->required(fn (Get $get) => $get('has_trial'))
                                    ->afterStateHydrated(function ($state, callable $set, $record) {
                                            $set('trial_price', $record?->trial?->price);
                                    })
                                    ->minValue(1),
                
                                    TextInput::make('trial_capacity')
                                        ->label('Trial Capacity')
                                        ->required(fn (Get $get) => $get('has_trial'))
                                        ->afterStateHydrated(function ($state, callable $set, $record) {
                                            $set('trial_capacity', $record?->trial?->capacity);
                                        })
                                        ->numeric()
                                        ->minValue(1),
                
                                        FileUpload::make('trial_image')
                                        ->label('Trial Image')
                                        ->required(fn (Get $get) => $get('has_trial'))
                                        ->image()
                                        ->imageEditor()
                                        ->directory('trials')
                                        ->afterStateHydrated(function ($state, callable $set, $record) {
                                            $set('trial_image', $record?->trial?->image);
                                        })
                                        ->visibility('public'),
                            ])->columns(1)
                            ->visible(fn (Get $get) => $get('has_trial')),


                        ]),    
                        
                       //sale  
                        Section::make('Sale')
                        ->schema([
                            Toggle::make('has_sale')
                                ->label('Has Sale')
                                ->reactive()
                                ->afterStateHydrated(function ($state, callable $set, $record) {
                                    $set('has_sale', $record?->sale()->exists());
                                }),

                                Group::make([
                                    TextInput::make('sale_price')
                                        ->label('Sale Price')
                                        ->numeric()
                                        ->required(fn (Get $get) => $get('has_sale'))
                                        ->afterStateHydrated(function ($state, callable $set, $record) {
                                                $set('sale_price', $record?->sale?->sale_price);
                                        })
                                        ->minValue(1),
                                ])
                                ->columns(1)
                                ->visible(fn (Get $get) => $get('has_sale')),

                            ]) ->columns(2), 
        ]);
    }
}