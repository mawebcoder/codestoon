<?php

namespace App\Filament\Resources;

use App\Enum\OrderStatusEnum;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'email')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\DateTimePicker::make('paid_at')->timezone('asia/tehran'),
                Forms\Components\Select::make('subscription_id')
                    ->required()
                    ->relationship('subscription', 'title')
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->integer()
                    ->required(),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options(OrderStatusEnum::class)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('User Email')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('subscription.title')
                    ->label('Subscription')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->getStateUsing(fn($record) => number_format($record->amount)),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(function ($record) {
                        return $record->status->getColor();
                    })->getStateUsing(function ($record) {
                        return $record->status->getLabel();
                    }),
                TextColumn::make('paid_at')
                    ->label('Paid At')
                    ->badge()
                    ->color(function ($record) {
                        if ($record->paid_at) {
                            return Color::Green;
                        }
                        return Color::Red;
                    })
                    ->getStateUsing(function ($record) {
                        if (!$record->paid_at) {
                            return "Not Paid";
                        }
                        return $record->paid_at->format('Y-m-d H:i:s');
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            RelationManagers\PaymentsRelationManager::class
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
