<?php

namespace App\Filament\Resources\TutorialResource\Pages;

use App\Enum\TutorialLevelEnum;
use App\Filament\Resources\TutorialResource;
use App\Models\Tutorial;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTutorials extends ListRecords
{
    protected static string $resource = TutorialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


    public function getTabs(): array
    {
        return [
            'All' => Tab::make(),
            'Beginner' => Tab::make()->modifyQueryUsing(
                fn(Builder $query) => $query->where('level', TutorialLevelEnum::BEGINNER)
            ),
            'Advanced' => Tab::make()->modifyQueryUsing(
                fn(Builder $query) => $query->where('level', TutorialLevelEnum::ADVANCED)
            ),
            'Medium' => Tab::make()->modifyQueryUsing(
                fn(Builder $query) => $query->where('level', TutorialLevelEnum::MEDIUM)
            ),
        ];
    }
}
