<?php

namespace ToxyTech\SimpleSlider\Tables;

use ToxyTech\Base\Facades\Html;
use ToxyTech\SimpleSlider\Models\SimpleSlider;
use ToxyTech\Table\Abstracts\TableAbstract;
use ToxyTech\Table\Actions\DeleteAction;
use ToxyTech\Table\Actions\EditAction;
use ToxyTech\Table\BulkActions\DeleteBulkAction;
use ToxyTech\Table\BulkChanges\CreatedAtBulkChange;
use ToxyTech\Table\BulkChanges\NameBulkChange;
use ToxyTech\Table\BulkChanges\StatusBulkChange;
use ToxyTech\Table\BulkChanges\TextBulkChange;
use ToxyTech\Table\Columns\CreatedAtColumn;
use ToxyTech\Table\Columns\FormattedColumn;
use ToxyTech\Table\Columns\IdColumn;
use ToxyTech\Table\Columns\NameColumn;
use ToxyTech\Table\Columns\StatusColumn;
use ToxyTech\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class SimpleSliderTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(SimpleSlider::class)
            ->addHeaderAction(CreateHeaderAction::make()->route('simple-slider.create'))
            ->addColumns([
                IdColumn::make(),
                NameColumn::make()->route('simple-slider.edit'),
                FormattedColumn::make('key')
                    ->title(trans('plugins/simple-slider::simple-slider.shortcode'))
                    ->alignStart()
                    ->getValueUsing(function (FormattedColumn $column) {
                        $value = $column->getItem()->key;

                        if (! function_exists('shortcode')) {
                            return $value;
                        }

                        return shortcode()->generateShortcode('simple-slider', ['alias' => $value]);
                    })
                    ->renderUsing(fn (FormattedColumn $column) => Html::tag('code', $column->getValue()))
                    ->copyable(),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addActions([
                EditAction::make()->route('simple-slider.edit'),
                DeleteAction::make()->route('simple-slider.destroy'),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('simple-slider.destroy'),
            ])
            ->addBulkChanges([
                NameBulkChange::make(),
                TextBulkChange::make()
                    ->name('key')
                    ->title(trans('plugins/simple-slider::simple-slider.key')),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(function (Builder $query) {
                return $query
                    ->select([
                        'id',
                        'name',
                        'key',
                        'status',
                        'created_at',
                    ]);
            });
    }
}
