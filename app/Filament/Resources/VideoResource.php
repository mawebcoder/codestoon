<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoResource\Pages;
use App\Filament\Resources\VideoResource\RelationManagers;
use App\Models\Video;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
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

class VideoResource extends Resource
{
    protected static ?string $model = Video::class;

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
                    ->live(onBlur: true)
                    ->unique(table: Video::class, column: 'title', ignoreRecord: true)
                    ->afterStateUpdated(function ($state, $set) {
                        {
                            $set('slug', Str::slug($state));
                        }
                    }),
                TextInput::make('slug')->required()->placeholder('Please enter a slug...')->maxLength(255)->minLength(
                    3
                ),
                Select::make('tutorial_id')
                    ->label('Tutorial')
                    ->required()
                    ->relationship('tutorial', 'title'),
                RichEditor::make('description')->columnSpanFull(),
                TextArea::make('meta')
                    ->placeholder('Please enter meta description...')
                    ->columnSpanFull(),
                TextInput::make('minute')
                    ->placeholder('Minute...')
                    ->label('Video Duration in Minutes')
                    ->integer()
                    ->maxValue(59),
                TextInput::make('second')
                    ->placeholder('Second...')
                    ->label('Video Duration in seconds')
                    ->integer()
                    ->maxValue(59),
                Forms\Components\Toggle::make('published')
                    ->label('publish')
                    ->default(false),
                Forms\Components\SpatieMediaLibraryFileUpload::make('video')
                    ->maxSize(512 * 1024)
                    ->columnSpanFull()
                    ->acceptedFileTypes(['video/mp4'])
                    ->disk('public')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id','desc')
            ->columns([
                TextColumn::make('id')->sortable()
                    ->searchable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('duration'),
                TextColumn::make('tutorial.title')
                    ->searchable()
                    ->label('Tutorial'),
                TextColumn::make('published')
                    ->badge()
                    ->color(function ($record) {
                        return $record->published ? Color::Green : Color::Red;
                    })
                    ->getStateUsing(function ($record) {
                        return (int)$record->published ? 'Published' : 'Unpublished';
                    })
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->requiresConfirmation()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->groups(['tutorial.title']);
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
            'index' => Pages\ListVideos::route('/'),
            'create' => Pages\CreateVideo::route('/create'),
            'edit' => Pages\EditVideo::route('/{record}/edit'),
        ];
    }
}
