<?php

declare(strict_types=1);

namespace App\Filament\Resources\Tutorials\RelationManagers;

use App\Filament\Resources\Videos\Tables\VideosTable;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class VideosRelationManager extends RelationManager
{
    protected static string $relationship = 'videos';

    public function table(Table $table): Table
    {
        return VideosTable::configure($table);
    }
}
