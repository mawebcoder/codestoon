<?php

declare(strict_types=1);

namespace App\Filament\Resources\Tutorials\Schemas;

use App\Enums\TutorialStatusEnum;
use App\Models\Category;
use App\Models\Tutorial;
use App\Models\User;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class TutorialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(6)
                    ->columnSpanFull()
                    ->schema([
                        Section::make()
                            ->columns()
                            ->columnSpan(4)
                            ->schema([
                                static::getBasicInformation(),
                                static::getPricing(),
                            ]),
                        static::getAssociations(),
                    ]),

            ]);
    }

    private static function getAssociations(): Section
    {
        return Section::make()
            ->columns()
            ->columnSpan(2)
            ->schema([
                Fieldset::make('Association')
                    ->columnSpanFull()
                    ->schema([
                        Select::make(Tutorial::COLUMN_CATEGORY_ID)
                            ->label('Category')
                            ->options(function ($record, $operation) {
                                $options = Category::query()->take(10)
                                    ->pluck(Category::COLUMN_NAME, Category::COLUMN_ID);

                                if ($operation === 'edit') {
                                    $options->put($record->category->id, $record->category->{Category::COLUMN_NAME});
                                }

                                return $options;
                            })
                            ->searchable()
                            ->getSearchResultsUsing(function (?string $search) {
                                return Category::query()->take(10)
                                    ->where(Category::COLUMN_NAME, 'like', "%{$search}%")
                                    ->pluck(Category::COLUMN_NAME, Category::COLUMN_ID);
                            })
                            ->native(false)
                            ->required()
                            ->columnSpanFull(),
                        Select::make(Tutorial::COLUMN_USER_ID)
                            ->label('Published by')
                            ->options(
                                fn () => User::query()->take(10)
                                    ->pluck('email', 'id'),
                            )
                            ->searchable()
                            ->getSearchResultsUsing(function (?string $search) {
                                return User::query()->take(10)
                                    ->where('email', 'like', "%{$search}%")
                                    ->orWhere('name', 'like', "%{$search}%")
                                    ->pluck('email', 'id');
                            })
                            ->native(false)
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    private static function getBasicInformation(): Fieldset
    {
        return Fieldset::make('Basic Information')
            ->columnSpanFull()
            ->schema([
                TextInput::make('title')
                    ->live(onBlur: true)
                    ->placeholder('Enter your title...')
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->required(),
                TextInput::make('slug')
                    ->placeholder('Enter your slug...')
                    ->required(),
                RichEditor::make('description')
                    ->resizableImages()
                    ->placeholder('Enter your description...')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('description_short')
                    ->placeholder('Enter your description...')
                    ->required()
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(TutorialStatusEnum::class)
                    ->required(),
            ]);
    }

    private static function getPricing(): Fieldset
    {
        return Fieldset::make('Pricing')
            ->columnSpanFull()
            ->relationship('price')
            ->schema([
                TextInput::make('price')
                    ->lt('compare_at_price')
                    ->suffixIcon(Heroicon::CurrencyDollar)
                    ->suffixIconColor(Color::Green)
                    ->numeric()
                    ->required(),
                TextInput::make('compare_at_price')
                    ->gt('price')
                    ->suffixIconColor(Color::Green)
                    ->suffixIcon(Heroicon::CurrencyDollar),
            ]);
    }
}
