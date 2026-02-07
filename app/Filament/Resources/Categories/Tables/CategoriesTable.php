<?php

declare(strict_types=1);

namespace App\Filament\Resources\Categories\Tables;

use App\Helpers\FilamentHelper;
use App\Models\Category;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make(Category::COLUMN_ID)->label('ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make(Category::COLUMN_NAME)->label('Name')
                    ->searchable(),
                SpatieMediaLibraryImageColumn::make('image')->circular(),
                TextColumn::make(Category::COLUMN_ENABLED)
                    ->color(fn (int $state) => FilamentHelper::getEnabledColor($state))
                    ->formatStateUsing(fn (int $state) => FilamentHelper::getEnabledLabel($state)),
                TextColumn::make(Category::COLUMN_SLUG)->label('Slug'),
            ])
            ->filters([
                Filter::make('is_enabled')
                    ->label('Is Enabled')
                    ->query(fn (Builder $query): Builder => $query->where(Category::COLUMN_ENABLED, true)),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                ]),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
