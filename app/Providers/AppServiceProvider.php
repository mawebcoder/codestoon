<?php

declare(strict_types=1);

namespace App\Providers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Colors\Color;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureFilament();
    }

    private function configureFilament(): void
    {
        ViewAction::configureUsing(static function (ViewAction $viewAction) {
            $viewAction->color(Color::Cyan);
        });

        CreateAction::configureUsing(static function (CreateAction $createAction) {
            $createAction->color(Color::Green);
        });

        EditAction::configureUsing(static function (EditAction $editAction) {
            $editAction->color(Color::Orange);
        });

        DeleteAction::configureUsing(static function (DeleteAction $deleteAction) {
            $deleteAction->requiresConfirmation();
        });
    }
}
