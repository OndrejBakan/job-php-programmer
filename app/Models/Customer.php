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

    /**
     * @return BelongsToMany<CustomerGroup>
     */
    public function customerGroups(): BelongsToMany
    {
        return $this->belongsToMany(CustomerGroup::class);
    }

    /**
     * @param Builder<Customer> $query
     * @param string|array<int, string> $relations
     */
    public function scopeInclude(Builder $query, array|string $relations): void
    {
        $allowedRelations = [
            'customerGroups',
        ];

        if (is_array($relations)) {
            foreach ($relations as $relation) {
                if (in_array($relation, $allowedRelations)) {
                    $query->with($relation);
                }
            }
        } else {
            if (in_array($relations, $allowedRelations)) {
                $query->with($relations);
            }
        }
    }
}
