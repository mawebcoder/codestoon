<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagResource\Pages;
use App\Filament\Resources\TagResource\RelationManagers;
use App\Models\Tag;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rule;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->columnSpanFull()
                    ->label('Title')
                    ->minLength(3)
                    ->maxLength(255)
                    ->placeholder('Enter The Title')
                    ->unique(ignoreRecord: true),
                Forms\Components\Toggle::make('is_active')
                    ->label('Activation')
                    ->columnSpanFull()
                    ->default(true)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
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
                    })
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
            'index' => Pages\ListTags::route('/'),
            'create' => Pages\CreateTag::route('/create'),
            'edit' => Pages\EditTag::route('/{record}/edit'),
        ];
    }
}
