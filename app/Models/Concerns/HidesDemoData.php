<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HidesDemoData
{
    protected static function bootHidesDemoData(): void
    {
        static::addGlobalScope('hide_demo_data', function (Builder $builder) {
            $table = $builder->getModel()->getTable();

            if (! schema_has_demo_column($table)) {
                return;
            }

            if (demo_data_visible()) {
                return;
            }

            $builder->where($table.'.is_demo', false);
        });
    }
}
