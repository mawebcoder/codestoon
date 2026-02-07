<?php

declare(strict_types=1);

namespace App\Filament\Resources\Tutorials\Pages;

use App\Filament\Resources\Tutorials\RelationManagers\VideosRelationManager;
use App\Filament\Resources\Tutorials\TutorialResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTutorial extends ViewRecord
{
    protected static string $resource = TutorialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function getRelationManagers(): array
    {
        return [
            VideosRelationManager::class,
        ];
    }
}
