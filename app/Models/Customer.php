<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Customer extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
    ];

    public function customerGroups(): BelongsToMany
    {
        return $this->belongsToMany(CustomerGroup::class);
    }

    public function scopeInclude(Builder $query, $relations)
    {
        $allowedRelations = [
            'customerGroups',
        ];

        foreach ($relations as $relation) {
            if (in_array($relation, $allowedRelations)) {
                $query->with($relation);
            }
        }
    }
}
