<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Product;
use App\Models\Routine;
use App\Models\Category;
use Filament\Tables\Table;
use Filament\Actions\BulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Collection;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                   ImageColumn::make('image')
                    ->circular(),
                
                TextColumn::make('name')
                    ->searchable(  true,function ($query, $search) {
                        return $query->whereHas('translations', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                    }),

                   TextColumn::make('price')
                   ->sortable(),
                   
                   ToggleColumn::make('in_stock'),

            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Category')
                    ->options(Category::query()->with(['translations'])->get()->pluck('title' , 'id'))
                    ->attribute('category_id'),

                 SelectFilter::make('Routine')
                    ->label('Rotine')
                    ->options(Routine::query()->with(['translations'])->get()->pluck('title' , 'id'))
                    ->attribute('routine_id'),

                 TernaryFilter::make('in_stock'),

                 TernaryFilter::make('featured'),
              TernaryFilter::make('has_sale')
                ->label('Has Sale')
                ->queries(
                    true: fn (Builder $query) => $query->whereHas('sale'),
                    false: fn (Builder $query) => $query->whereDoesntHave('sale'),
                    blank: fn (Builder $query) => $query,
                ),
            ], layout: FiltersLayout::AboveContent)
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
            ])
            
            ->toolbarActions([
                BulkActionGroup::make([

            BulkAction::make('add_sale')
            ->label('Add Sale')
            ->icon('heroicon-o-tag')
            ->color('success')
            ->modalHeading('Add Sale to Selected Products')
            ->modalSubmitActionLabel('Apply Sale')
            ->schema([
                TextInput::make('sale_price')
                    ->label('Sale Price')
                    ->numeric()
                    ->required()
                    ->minValue(1),
            ])
            ->action(function (Collection $records, array $data) {
                foreach ($records as $product) {
                    $product->sale()->updateOrCreate(
                        [],
                        [
                            'sale_price' => $data['sale_price'],
                        ]
                    );
                }

                Notification::make()
                    ->title('Sale applied successfully')
                    ->success()
                    ->send();
            })
            ->deselectRecordsAfterCompletion(),

            BulkAction::make('remove_sale')
              ->label('Remove Sale')
              ->icon('heroicon-o-x-mark')
              ->color('danger')
              ->action(function(Collection $records , array $data){
                foreach ($records as $product) {
                    $product->sale()->delete();
                }

                Notification::make()
                    ->title('Sale removed successfully')
                    ->success()
                    ->send();
              })->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
