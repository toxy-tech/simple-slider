<?php

namespace ToxyTech\SimpleSlider\Http\Controllers\Settings;

use ToxyTech\Setting\Http\Controllers\SettingController;
use ToxyTech\SimpleSlider\Forms\Settings\SimpleSliderSettingForm;
use ToxyTech\SimpleSlider\Http\Requests\Settings\SimpleSliderSettingRequest;

class SimpleSliderSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/simple-slider::simple-slider.settings.title'));

        return SimpleSliderSettingForm::create()->renderForm();
    }

    public function update(SimpleSliderSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
