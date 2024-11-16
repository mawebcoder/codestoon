<?php

namespace App\Filament\Resources\BlogResource\Pages;

use App\Filament\Resources\BlogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBlog extends EditRecord
{
    protected static string $resource = BlogResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['publish'] && $this->record->published_at) {
            unset($data['publish']);

            $data['published_at'] = $this->record->published_at;

            return $data;
        }

        if ($data['publish']) {
            unset($data['publish']);
            $data['published_at'] = now();
            return $data;
        }


        $data['published_at'] = null;

        unset($data['publish']);


        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($data['published_at']) {
            $data['publish'] = true;
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return self::$resource::getUrl();
    }
}
