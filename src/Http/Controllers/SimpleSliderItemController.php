<?php

namespace ToxyTech\SimpleSlider\Http\Controllers;

use ToxyTech\Base\Http\Actions\DeleteResourceAction;
use ToxyTech\Base\Http\Controllers\BaseController;
use ToxyTech\SimpleSlider\Forms\SimpleSliderItemForm;
use ToxyTech\SimpleSlider\Http\Requests\SimpleSliderItemRequest;
use ToxyTech\SimpleSlider\Models\SimpleSliderItem;
use ToxyTech\SimpleSlider\Tables\SimpleSliderItemTable;

class SimpleSliderItemController extends BaseController
{
    public function index(SimpleSliderItemTable $dataTable)
    {
        return $dataTable->renderTable();
    }

    public function create()
    {
        $form = SimpleSliderItemForm::create()
            ->setUseInlineJs(true)
            ->renderForm();

        return $this
            ->httpResponse()
            ->setData([
                'title' => trans('plugins/simple-slider::simple-slider.create_new_slide'),
                'content' => $form,
            ]);
    }

    public function store(SimpleSliderItemRequest $request)
    {
        SimpleSliderItemForm::create()->setRequest($request)->save();

        return $this
            ->httpResponse()
            ->withCreatedSuccessMessage();
    }

    public function edit(int|string $id)
    {
        $simpleSliderItem = SimpleSliderItem::query()->findOrFail($id);

        $form = SimpleSliderItemForm::createFromModel($simpleSliderItem)
            ->setUseInlineJs(true)
            ->renderForm();

        return $this
            ->httpResponse()
            ->setData([
                'title' => trans('plugins/simple-slider::simple-slider.edit_slide', ['id' => $simpleSliderItem->getKey()]),
                'content' => $form,
            ]);
    }

    public function update(int|string $id, SimpleSliderItemRequest $request)
    {
        $simpleSliderItem = SimpleSliderItem::query()->findOrFail($id);

        SimpleSliderItemForm::createFromModel($simpleSliderItem)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->withUpdatedSuccessMessage();
    }

    public function destroy(int|string $id)
    {
        $simpleSliderItem = SimpleSliderItem::query()->findOrFail($id);

        return DeleteResourceAction::make($simpleSliderItem);
    }
}
