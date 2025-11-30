<?php

namespace ToxyTech\SimpleSlider\Http\Requests\Settings;

use ToxyTech\Base\Rules\OnOffRule;
use ToxyTech\Support\Http\Requests\Request;

class SimpleSliderSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'simple_slider_using_assets' => new OnOffRule(),
        ];
    }
}
