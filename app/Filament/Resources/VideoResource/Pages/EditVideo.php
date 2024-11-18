<?php

namespace App\Filament\Resources\VideoResource\Pages;

use App\Filament\Resources\VideoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVideo extends EditRecord
{
    protected static string $resource = VideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['duration'] = $data['minute'];

        if (isset($data['second'])) {
            $data['duration'] .= ':' . $data['second'];
        }

        unset($data['minute'], $data['second']);

        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (!isset($data['duration'])) {
            return $data;
        }

        $explodedDuration = explode(':', $data['duration']);

        $data['minute'] = $explodedDuration[0];
        $data['second'] = $explodedDuration[1] ?? null;

        return  $data;
    }

    protected function getRedirectUrl(): ?string
    {
        return self::$resource::getUrl();
    }
}
