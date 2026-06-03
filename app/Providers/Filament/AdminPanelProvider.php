<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\HtmlString;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->globalSearch(false)
            ->brandName('Goal Shot Ball Association of Bihar')
            ->brandLogo('/assets/img/logo.png')
            ->brandLogoHeight('3.25rem')
            ->favicon('/assets/img/logo.png')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->renderHook(
                PanelsRenderHook::BODY_START,
                fn (): HtmlString => new HtmlString(<<<'HTML'
                    <style>
                        /* Sports background + amber brand overlay on the login screen */
                        .fi-simple-layout {
                            position: relative;
                            background-image:
                                linear-gradient(135deg, rgba(180, 83, 9, 0.86) 0%, rgba(245, 158, 11, 0.66) 45%, rgba(15, 23, 42, 0.84) 100%),
                                url('/assets/img/sliders/slider10.jpeg');
                            background-size: cover;
                            background-position: center;
                            background-attachment: fixed;
                        }
                        /* Glass card so the sports photo shows through */
                        .fi-simple-layout .fi-simple-main {
                            background-color: rgba(255, 255, 255, 0.72);
                            backdrop-filter: blur(10px);
                            -webkit-backdrop-filter: blur(10px);
                            border: 1px solid rgba(255, 255, 255, 0.5);
                            box-shadow: 0 24px 60px -12px rgba(15, 23, 42, 0.55);
                        }
                        .dark .fi-simple-layout .fi-simple-main {
                            background-color: rgba(17, 24, 39, 0.9);
                            border-color: rgba(255, 255, 255, 0.08);
                        }
                        /* Bigger, centered brand logo on login */
                        .fi-simple-layout .fi-logo {
                            margin-inline: auto;
                        }
                    </style>
                    HTML),
            )
            ->renderHook(
                PanelsRenderHook::FOOTER,
                fn (): HtmlString => new HtmlString(<<<'HTML'
                    <div style="text-align:center;padding:16px 0 8px;font-size:.8rem;color:rgb(100 116 139);">
                        Designed &amp; developed by
                        <a href="https://www.instagram.com/10xcart" target="_blank" rel="noopener"
                           style="font-weight:600;color:rgb(217 119 6);text-decoration:none;">10xCart</a>
                    </div>
                    HTML),
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
