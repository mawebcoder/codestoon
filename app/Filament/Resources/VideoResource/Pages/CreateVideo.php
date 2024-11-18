<?php

namespace App\Filament\Resources\VideoResource\Pages;

use App\Filament\Resources\VideoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateVideo extends CreateRecord
{
    protected static string $resource = VideoResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['duration'] = $data['minute'];

        if (isset($data['second'])) {
            $data['duration'].=':'.$data['second'];
        }

        unset($data['minute'], $data['second']);

        return  $data;
    }


    protected function getRedirectUrl(): string
    {

        return  self::$resource::getUrl();
    }
}
