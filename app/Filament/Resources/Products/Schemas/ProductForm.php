<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Routine;
use App\Models\Category;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Tabs\Tab;

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
                ->columns(3),

            // Featured + In Stock
            Section::make()
                ->schema( [
                    Toggle::make('featured')->label('Featured'),
                    Toggle::make('in_stock')->label('In Stock'),
                ])
                ->columns(2),
        ]);
    }
}