<?php

namespace App\Providers\Filament;

//use Pboivin\FilamentPeek\Tables\Actions\ListPreviewAction;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Widgets\UserStatsWidget;
use App\Filament\Widgets\EquipmentStatusChart;
use App\Filament\Widgets\EquipmentsPerCategory;
use App\Filament\Widgets\EquipmentsPerFacility;
use App\Filament\Widgets\FacilityPerFacilityType;


class AdminPanelProvider extends PanelProvider
{

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login( )
            ->brandName('CCIS ComLab System')
            ->favicon(asset('images/ccis.png'))
            ->sidebarFullyCollapsibleOnDesktop()
            //->brandLogo(asset('images/ccis.png'))
            // ->breadcrumbs(false)
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->colors([
                'primary' => Color::Amber,
            ])

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([])
                //Pages\Dashboard::class,
            //])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                //FilamentPeekPlugin::make()
                // \EightyNine\Approvals\ApprovalPlugin::make(),

            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([

                //\App\Filament\Widgets\TotalUserWidget::class,
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,

                //App\Filament\Widgets\EquipmentPerFacility::class,
                //Widgets\EquipmentPerCategory::class,
                //UserStatsWidget::class,
                //BorrowStatsWidget::class,
                //EquipmentsPerCategory::class,

                //EquipmentStatusChart::class,
                //FacilityPerFacilityType::class,
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
