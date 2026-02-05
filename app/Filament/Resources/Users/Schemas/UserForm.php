<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Operation;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns()
                    ->schema([
                        TextInput::make('name')
                            ->autocomplete(false)
                            ->required(),
                        TextInput::make('email')
                            ->email()
                            ->autocomplete(false)
                            ->unique(table: User::getTableName())
                            ->required(),
                        TextInput::make('password')
                            ->password()
                            ->live(onBlur: true)
                            ->required(fn (string $operation) => $operation === Operation::Create->value)
                            ->revealable()
                            ->rules(['confirmed', Password::min(8)]),
                        TextInput::make('password_confirmation')
                            ->password()
                            ->required(
                                fn (string $operation, Get $get) => $operation === Operation::Create->value,
                            )
                            ->revealable(),
                        SpatieMediaLibraryFileUpload::make('profile')
                            ->image()
                            ->imageEditor()
                            ->required()
                            ->maxSize(1024)
                            ->helperText('Max Size:1MB,Max width:200px,Max height:200px')
                            ->rules([Rule::dimensions()->maxHeight(200)->maxWidth(200)]),

                    ]),

            ]);
    }
}
