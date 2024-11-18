<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Filament\Resources\BlogResource\RelationManagers;
use App\Models\Blog;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->columnSpanFull()
                    ->placeholder('Enter the blog title...')
                    ->minLength(3)
                    ->unique(table: 'blogs', column: 'title', ignoreRecord: true)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        $set('slug', Str::slug($state));
                    })
                    ->maxLength(255),

                Forms\Components\Select::make('category')
                    ->label('Category')
                    ->multiple()
                    ->preload(true)
                    ->relationship('categories', 'title'),

                SpatieMediaLibraryFileUpload::make('file')
                    ->required()
                    ->image()
                    ->maxSize(1024)
                    ->columnSpanFull()
                    ->label("Cover Image"),


                TextInput::make('slug')
                    ->columnSpanFull(),

                TextArea::make('short_content')
                    ->columnSpanFull()
                    ->required(),

                RichEditor::make('content')
                    ->columnSpanFull()
                    ->required()
                    ->minLength(10),

                Toggle::make('publish')
                    ->columnSpanFull()
                    ->default(true),

                Forms\Components\DateTimePicker::make('published_at')
                    ->hiddenOn('created')
                    ->disabled(),

                TextInput::make('time_to_read')
                    ->numeric(),
                Textarea::make('meta')
                    ->placeholder('Enter the blog meta description...')

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->date('d M,Y')
                    ->getStateUsing(function ($record) {
                        if ($record->published_at) {
                            return $record->published_at;
                        }
                        return '-';
                    })
                    ->label('Published At'),
                SpatieMediaLibraryImageColumn::make('file')

            ])
            ->filters([
                Tables\Filters\Filter::make('published')
                    ->label('Published')
                    ->query(fn(Builder $query) => $query->whereNotNull('published_at')),
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
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
