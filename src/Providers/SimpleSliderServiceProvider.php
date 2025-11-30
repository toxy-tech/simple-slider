<?php

namespace ToxyTech\SimpleSlider\Providers;

use ToxyTech\Base\Facades\DashboardMenu;
use ToxyTech\Base\Facades\PanelSectionManager;
use ToxyTech\Base\PanelSections\PanelSectionItem;
use ToxyTech\Base\Supports\ServiceProvider;
use ToxyTech\Base\Traits\LoadAndPublishDataTrait;
use ToxyTech\Language\Facades\Language;
use ToxyTech\Setting\PanelSections\SettingOthersPanelSection;
use ToxyTech\SimpleSlider\Models\SimpleSlider;
use ToxyTech\SimpleSlider\Models\SimpleSliderItem;
use ToxyTech\SimpleSlider\Repositories\Eloquent\SimpleSliderItemRepository;
use ToxyTech\SimpleSlider\Repositories\Eloquent\SimpleSliderRepository;
use ToxyTech\SimpleSlider\Repositories\Interfaces\SimpleSliderInterface;
use ToxyTech\SimpleSlider\Repositories\Interfaces\SimpleSliderItemInterface;

class SimpleSliderServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(SimpleSliderInterface::class, function () {
            return new SimpleSliderRepository(new SimpleSlider());
        });

        $this->app->bind(SimpleSliderItemInterface::class, function () {
            return new SimpleSliderItemRepository(new SimpleSliderItem());
        });
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/simple-slider')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadMigrations()
            ->publishAssets();

        DashboardMenu::default()->beforeRetrieving(function () {
            DashboardMenu::make()
                ->registerItem([
                    'id' => 'cms-plugins-simple-slider',
                    'priority' => 390,
                    'name' => 'plugins/simple-slider::simple-slider.menu',
                    'icon' => 'ti ti-slideshow',
                    'route' => 'simple-slider.index',
                ]);
        });

        PanelSectionManager::default()->beforeRendering(function () {
            PanelSectionManager::registerItem(
                SettingOthersPanelSection::class,
                fn () => PanelSectionItem::make('simple_sliders')
                    ->setTitle(trans('plugins/simple-slider::simple-slider.settings.title'))
                    ->withIcon('ti ti-slideshow')
                    ->withPriority(430)
                    ->withDescription(trans('plugins/simple-slider::simple-slider.settings.description'))
                    ->withRoute('simple-slider.settings')
            );
        });

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            Language::registerModule(SimpleSlider::class);
        }

        $this->app->booted(function () {
            $this->app->register(HookServiceProvider::class);
        });
    }
}
