<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionResource\Pages;
use App\Filament\Resources\SubscriptionResource\RelationManagers;
use App\Models\Subscription;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->placeholder('Please enter a title...')
                    ->minLength(3)
                    ->maxLength(255)
                    ->unique(table: Subscription::class, column: 'title', ignoreRecord: true),
                TextInput::make('month_length')
                    ->required()
                    ->placeholder('Month length')
                    ->numeric()
                    ->unique(table: Subscription::class, column: 'month_length', ignoreRecord: true),
                TextArea::make('description')->placeholder('Please enter a description...'),
                TextInput::make('price')
                    ->placeholder('Please enter a price...')
                    ->required()
                    ->integer()
                    ->numeric(),

                Toggle::make('active')
                    ->default(true)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID'),
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('month_length'),
                Tables\Columns\ToggleColumn::make('active'),
                Tables\Columns\TextColumn::make('price')->getStateUsing(function ($record) {
                    return number_format($record->price);
                }),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->requiresConfirmation(),
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
            'index' => Pages\ListSubscriptions::route('/'),
            'create' => Pages\CreateSubscription::route('/create'),
            'edit' => Pages\EditSubscription::route('/{record}/edit'),
        ];
    }
}
