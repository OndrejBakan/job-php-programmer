<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    public function customerGroups()
    {
        return $this->belongsToMany(CustomerGroup::class);
    }

    public function scopeInclude($query, $relations)
    {
        if (in_array('customer-groups', $relations)) {
            $query->with('customerGroups');
        }
        
        return $query;
    }
}
