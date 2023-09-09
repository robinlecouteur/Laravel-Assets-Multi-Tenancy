<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;

    public static function booted()
    {
        static::saved(function (self $model) {
            if ($model->is_primary) {
                $model->tenant->domains()
                    ->where('id', '!=', $model->id)
                    ->update(['is_primary' => false]);
            }
        });
    }

    public function makePrimary(): static
    {
        $this->update([
            'is_primary' => true,
        ]);

        $this->tenant->setRelation('primary_domain', $this);

        return $this;
    }


}
