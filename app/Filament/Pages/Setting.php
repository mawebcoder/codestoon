<?php

namespace App\Filament\Pages;

use App\Models\Category;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\HasState;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\EditRecord;
use Hamcrest\Core\Set;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;
use Livewire\Attributes\Validate;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

/**
 * @property Form $form
 */
class Setting extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';

    protected static ?string $title = 'Settings';

    public array $file = [];

    public ?string $youtube = null;

    public ?string $email = null;

    public ?string $phone = null;

    public ?string $aboutUs = null;
    public ?string $instagram = null;

    public ?string $aparat = null;

    public function mount()
    {
        $setting = \App\Models\Setting::query()->first();

        if (!$setting) {
            return;
        }
        $this->youtube = $setting->youtube;
        $this->email = $setting->email;
        $this->phone = $setting->phone;
        $this->aparat = $setting->aparat;
        $this->aboutUs = $setting->about_us;
        $this->instagram = $setting->instagram;


        $logo=$setting->media()->latest('id')->first();

        $this->file = [
            $logo->uuid => $logo->uuid
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Settings';
    }


    public function form(Form $form): Form
    {
        return $form
            ->model(\App\Models\Setting::query()->first())->schema([
                TextInput::make('youtube')
                    ->placeholder(
                        'Youtube URL'
                    )->statePath('youtube'),


                SpatieMediaLibraryFileUpload::make('file')
                    ->image()
                    ->statePath('file')
                    ->collection('default'),

                TextInput::make('email')->placeholder('Email')->statePath('email'),
                TextInput::make('phone')->placeholder('phone')->statePath('phone'),
                TextInput::make('aparat')->placeholder('aparat')->statePath('aparat'),
                TextInput::make('instagram')->placeholder('instagram')->statePath('instagram'),
                Textarea::make('about_us')->statePath('aboutUs')->placeholder('About Us'),
                Actions::make([
                    Action::make('Submit')
                        ->action(function () {
                            $this->store();
                        })
                ])
            ]);
    }


    protected static string $view = 'filament.pages.setting';

    private function store(): void
    {
        $this->validate([
            'aboutUs' => 'nullable|string',
            'instagram' => 'nullable|url',
            'aparat' => 'nullable|url',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'youtube' => 'nullable|url',
        ]);

        $setting = \App\Models\Setting::query()->first();

        if (!$setting) {
            $setting = new \App\Models\Setting();
        }

        $setting->youtube = $this->youtube;
        $setting->email = $this->email;
        $setting->phone = $this->phone;
        $setting->about_us = $this->aboutUs;
        $setting->instagram = $this->instagram;
        $setting->aparat = $this->aparat;

        $setting->save();

        $this->uploadFile($setting);


        Notification::make('happy birthdate')
            ->title('Success')
            ->success()
            ->send();
    }


    function uploadFile(\App\Models\Setting $setting): void
    {
        if (!$this->file) {
            return;
        }

        if (array_keys($this->file)[0] === $setting->media()->latest('id')->first()?->uuid) {
            return;
        }

        $setting->media()->delete();
        /**
         * @type TemporaryUploadedFile $logo
         */
        $logo = array_values($this->file)[0];

        $fileName = $logo->hashName();

        $media = $setting->media()->create([
            'disk' => 'public',
            'uuid' => Str::uuid(),
            'collection_name' => 'default',
            'name' => $logo->getClientOriginalName(),
            'file_name' => $fileName,
            'conversions_disk' => 'public',
            'size' => $logo->getSize(),
            'mime_type'=>$logo->getMimeType(),
            'manipulations' => [],
            'custom_properties' => [],
            'generated_conversions' => [],
            'responsive_images' => [],
            'order_column' => 1
        ]);

        $logo->storeAs($media->id, $fileName, ['disk' => 'public', 'visibility' => 'public']);
    }
}
