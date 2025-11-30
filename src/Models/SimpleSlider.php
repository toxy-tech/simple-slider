<?php

namespace ToxyTech\SimpleSlider\Models;

use ToxyTech\Base\Casts\SafeContent;
use ToxyTech\Base\Enums\BaseStatusEnum;
use ToxyTech\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SimpleSlider extends BaseModel
{
    protected $table = 'simple_sliders';

    protected $fillable = [
        'name',
        'key',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
        'key' => SafeContent::class,
        'description' => SafeContent::class,
    ];

    protected static function booted(): void
    {
        static::deleted(function (SimpleSlider $slider) {
            $slider->sliderItems()->each(fn (SimpleSliderItem $item) => $item->delete());
        });
    }

    public function sliderItems(): HasMany
    {
        return $this->hasMany(SimpleSliderItem::class)->orderBy('simple_slider_items.order');
    }
}
