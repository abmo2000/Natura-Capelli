<?php

namespace App\Providers\Filament;


use Filament\Panel;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use App\Filament\Auth\CustomLogin;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\NavigationGroup;
use Filament\Widgets\FilamentInfoWidget;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use App\Filament\Resources\BuisnessInfos\BuisnessInfoResource;
use App\Filament\Resources\ContentManagement\ContentManagementResource;
use App\Filament\Resources\OrderSettings\OrderSettingsResource;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(CustomLogin::class)
            ->colors([
                'primary' => Color::Amber,
            ])
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
            ])
            ->navigationItems([
            NavigationItem::make('Content Management')
                ->url(fn () => ContentManagementResource::getUrl('index'))
                ->icon('heroicon-o-document-text')
                ->group('Business Settings')
                ->sort(1)
                ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.content-management.*')),
            
            NavigationItem::make('Business Info')
                ->url(fn () => BuisnessInfoResource::getUrl('index'))
                ->icon('heroicon-o-building-office')
                ->group('Business Settings')
                ->sort(2)
                ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.business-info.*')),

                         NavigationItem::make('Order Settings')
                ->url(fn () => OrderSettingsResource::getUrl('index'))
                ->icon('heroicon-o-building-office')
                ->group('Business Settings')
                ->sort(2)
                ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.order_settings.*')),
        ])
        ->navigationGroups([
            NavigationGroup::make('Business Settings')
                // ->icon('heroicon-o-cog-6-tooth')
                ->collapsible()
                ->collapsed(false), // Start expanded
        ]);
    }
}
