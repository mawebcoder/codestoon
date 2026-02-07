<?php

declare(strict_types=1);

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Closure;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(static::getSchema());
    }

    public static function getSlug(): Closure
    {
        return static fn (?string $state, Set $set) => $set(Category::COLUMN_SLUG, Str::slug($state));
    }

    public static function getSchema(): array
    {
        return [
            Section::make()
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    TextInput::make(Category::COLUMN_NAME)
                        ->live(onBlur: true)
                        ->afterStateUpdated(static::getSlug())
                        ->required(),
                    TextInput::make(Category::COLUMN_SLUG)
                        ->required(),
                    Textarea::make('description'),

                    Toggle::make('enabled')
                        ->onColor('success')
                        ->offColor('danger'),

                    SpatieMediaLibraryFileUpload::make('image')
                        ->maxSize(1024)
                        ->image()
                        ->rule(Rule::dimensions()->maxWidth(500)->maxHeight(500))
                        ->helperText('Max size:1MB,max size:500*500')
                        ->imageEditor(),
                ]),
        ];
    }
}
