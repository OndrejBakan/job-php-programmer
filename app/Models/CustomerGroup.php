<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CustomerGroup extends Model
{
    use HasFactory;

    /**
     * @return BelongsToMany<Customer>
     */
    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class);
    }
}
