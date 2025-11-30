<?php

namespace ToxyTech\SimpleSlider\Tables;

use ToxyTech\Base\Facades\BaseHelper;
use ToxyTech\Base\Facades\Html;
use ToxyTech\SimpleSlider\Models\SimpleSliderItem;
use ToxyTech\Table\Abstracts\TableAbstract;
use ToxyTech\Table\Actions\DeleteAction;
use ToxyTech\Table\Actions\EditAction;
use ToxyTech\Table\Columns\Column;
use ToxyTech\Table\Columns\CreatedAtColumn;
use ToxyTech\Table\Columns\FormattedColumn;
use ToxyTech\Table\Columns\IdColumn;
use ToxyTech\Table\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;

class SimpleSliderItemTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(SimpleSliderItem::class)
            ->setView('plugins/simple-slider::items')
            ->setDom($this->simpleDom())
            ->addColumns([
                IdColumn::make(),
                ImageColumn::make(),
                FormattedColumn::make('title')
                    ->title(trans('core/base::tables.title'))
                    ->alignStart()
                    ->getValueUsing(function (FormattedColumn $column) {
                        $item = $column->getItem();

                        $name = BaseHelper::clean($item->title);

                        if (! $this->hasPermission('simple-slider-item.edit')) {
                            return $name;
                        }

                        return $name ? Html::link(route('simple-slider-item.edit', $item->getKey()), $name, [
                            'data-bs-toggle' => 'modal',
                            'data-bs-target' => '#simple-slider-item-modal',
                        ]) : '&mdash;';
                    }),
                Column::make('order')
                    ->title(trans('core/base::tables.order'))
                    ->className('text-start order-column'),
                CreatedAtColumn::make(),
            ])
            ->addActions([
                EditAction::make()
                    ->route('simple-slider-item.edit')
                    ->attributes([
                        'data-bs-toggle' => 'modal',
                        'data-bs-target' => '#simple-slider-item-modal',
                    ])
                    ->permission('simple-slider-item.edit'),
                DeleteAction::make()
                    ->route('simple-slider-item.destroy')
                    ->permission('simple-slider-item.destroy'),
            ])
            ->queryUsing(function (Builder $query) {
                return $query
                    ->select([
                        'id',
                        'title',
                        'image',
                        'order',
                        'created_at',
                    ])
                    ->orderBy('order')
                    ->where('simple_slider_id', request()->route()->parameter('id'));
            });
    }
}
