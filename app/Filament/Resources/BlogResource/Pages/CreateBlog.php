<?php

namespace App\Filament\Resources\BlogResource\Pages;

use App\Filament\Resources\BlogResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBlog extends CreateRecord
{
    protected static string $resource = BlogResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($data['publish']) {
            $data['published_at'] = now();
        }

        unset($data['publish']);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return  self::$resource::getUrl();
    }
}
