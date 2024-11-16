<?php

namespace App\Filament\Resources\TutorialResource\Pages;

use App\Filament\Resources\TutorialResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTutorial extends CreateRecord
{
    protected static string $resource = TutorialResource::class;

    protected function getRedirectUrl(): string
    {
        return  self::$resource::getUrl();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($data['publish']) {
            $data['published_at'] = now();
        }

        unset($data['publish']);

        return $data;
    }
}
