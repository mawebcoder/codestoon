<?php

namespace App\Filament\Resources;

use App\Enum\TutorialLevelEnum;
use App\Filament\Resources\TutorialResource\Pages;
use App\Filament\Resources\TutorialResource\RelationManagers;
use App\Models\Tutorial;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

use function PHPUnit\Framework\matches;

class TutorialResource extends Resource
{
    protected static ?string $model = Tutorial::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->unique(table: Tutorial::class, column: 'title', ignoreRecord: true)
                    ->maxLength(255)
                    ->afterStateUpdated(function ($state, $set) {
                        $set('slug', Str::slug($state));
                    })
                    ->placeholder('Enter Title...')
                    ->minLength(3),

                TextInput::make('slug')
                    ->placeholder('Enter Slug...')
                    ->required(),

                Select::make('category_id')
                    ->label('Category')
                    ->preload()
                    ->multiple()
                    ->searchable()
                    ->columnSpanFull()
                    ->relationship('categories', 'title'),

                Forms\Components\SpatieMediaLibraryFileUpload::make('file')
                    ->columnSpanFull(),

                Forms\Components\Select::make('instructor_id')
                    ->label("Instructor")
                    ->relationship('instructor', 'email'),
                Forms\Components\RichEditor::make('description')->columnSpanFull()->placeholder('Enter description...'),

                Forms\Components\RichEditor::make('prerequisites')->columnSpanFull()->placeholder(
                    'Enter Prerequisites...'
                ),

                TextArea::make('meta')->placeholder('Enter Meta Description...'),
                Select::make('level')
                    ->options(TutorialLevelEnum::class),
                Forms\Components\Toggle::make('publish')
                    ->default(false)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),

                TextColumn::make('categories.title')
                    ->badge()
                    ->label('Categories'),

                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('file'),
                TextColumn::make('level')
                    ->badge()
                    ->color(function ($record) {
                        return $record->level->getTableColor();
                    })
                    ->getStateUsing(function ($record) {
                        return $record->level->getName();
                    }),

                Tables\Columns\TextColumn::make('published_at')
                    ->badge()
                    ->color(function ($record) {
                        if ($record->published_at) {
                            return Color::Yellow;
                        }
                        return Color::Red;
                    })
                    ->getStateUsing(function ($record) {
                        if ($record->published_at) {
                            return $record->published_at;
                        }
                        return '-';
                    }),
            ])
            ->filters([
                //
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
            'index' => Pages\ListTutorials::route('/'),
            'create' => Pages\CreateTutorial::route('/create'),
            'edit' => Pages\EditTutorial::route('/{record}/edit'),
        ];
    }

}
