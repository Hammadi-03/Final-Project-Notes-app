<?php

namespace App\Providers;

use App\Models\Note;
use App\Policies\NotePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Note::class, NotePolicy::class);

        view()->composer('*', function ($view) {
            if (auth()->check()) {
                $user = auth()->user();
                $view->with('globalLabels', $user->labels()->orderBy('name')->get());
                $view->with('globalSettings', array_merge([
                    'add_to_bottom' => true,
                    'move_checked_to_bottom' => true,
                    'dark_theme' => false,
                ], $user->settings ?? []));
            } else {
                $view->with('globalLabels', collect());
                $view->with('globalSettings', [
                    'add_to_bottom' => true,
                    'move_checked_to_bottom' => true,
                    'dark_theme' => false,
                ]);
            }
        });
    }
}

