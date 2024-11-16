<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->columnSpanFull()
                    ->minLength(3)
                    ->maxLength(255)
                    ->placeholder('Enter the Title')
                    ->unique(table: 'categories', column: 'title', ignoreRecord: true),

                Forms\Components\Toggle::make('is_active')
                    ->columnSpanFull()
                    ->default(true),
                Forms\Components\Select::make('parent_id')
                    ->relationship(
                        'parent',
                        'title',
                        modifyQueryUsing: function (Builder $query, $record) {
                            if (is_null($record)) {
                                return $query;
                            }
                            $query->whereNot('id', $record->id);
                        }
                    )
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->searchable()
                    ->label('ID'),
                TextColumn::make('title')
                    ->searchable()
                    ->label('Title'),
                TextColumn::make('is_active')
                    ->label('Activation')
                    ->badge()
                    ->color(function ($record) {
                        return $record->is_active ? Color::Green : Color::Red;
                    })
                    ->getStateUsing(function ($record) {
                        return $record->is_active ? "Active" : "Inactive";
                    }),
                TextColumn::make('parent.title')
                    ->badge(function ($record) {
                        return is_null($record->parent);
                    })
                    ->color(function ($record) {
                        if (!$record->parent) {
                            return Color::Red;
                        }
                    })
                    ->getStateUsing(function ($record) {
                        if (!$record->parent) {
                            return 'Not Parent';
                        } else {
                            return $record->parent->title;
                        }
                    })->label("Parent")
            ])
            ->filters([
                Tables\Filters\Filter::make('is_active')
                    ->label('Activation')
                    ->toggle()
                    ->query(fn(Builder $query) => $query->where('is_active', true)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
