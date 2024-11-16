<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstructorResource\Pages;
use App\Filament\Resources\InstructorResource\RelationManagers;
use App\Models\Instructor;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rules\Password;

class InstructorResource extends Resource
{
    protected static ?string $model = Instructor::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->placeholder('First Name...')
                    ->minLength(3)
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->placeholder('Last Name...')
                    ->minLength(3)
                    ->maxLength(255),
                TextInput::make('email')
                    ->placeholder('Email...')
                    ->email()
                    ->required()
                    ->unique(table: Instructor::class, column: 'email', ignoreRecord: true),
                Forms\Components\TextInput::make('password')
                    ->placeholder('Password...')
                    ->password()
                    ->rule([Password::min(8)])
                    ->required()
                    ->confirmed(),
                TextInput::make('password_confirmation')
                    ->placeholder('Confirm Password...')
                    ->required()
                    ->password(),
                TextInput::make('cellphone')
                    ->placeholder('Cellphone...'),
                Forms\Components\SpatieMediaLibraryFileUpload::make('file')
                    ->columnSpanFull()
                    ->label('Profile Picture')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('first_name'),
                Tables\Columns\TextColumn::make('last_name'),
                Tables\Columns\TextColumn::make('email'),
                SpatieMediaLibraryImageColumn::make('file')
                ->label('Profile Picture')
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->requiresConfirmation()
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
            'index' => Pages\ListInstructors::route('/'),
            'create' => Pages\CreateInstructor::route('/create'),
            'edit' => Pages\EditInstructor::route('/{record}/edit'),
        ];
    }
}
