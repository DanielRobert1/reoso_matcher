<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropertyType extends Model
{
    use HasFactory;




    /**
     * ==========================================================
     * Eloquent Relationships
     * ==========================================================
     */

    /**
     * @return HasMany
     */
    final public function properties(): HasMany
    {
        return $this->hasMany(Properties::class, 'property_type_id');
    }

    /**
     * @return HasMany
     */
    final public function searchProfiles(): HasMany
    {
        return $this->hasMany(SearchProfile::class, 'property_type_id');
    }
}
