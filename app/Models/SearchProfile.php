<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SearchProfile extends Model
{
    use HasFactory;



     /**
     * ==========================================================
     * Eloquent Relationships
     * ==========================================================
     */

    /**
     * @return BelongsTo
     */
    final public function propertyType(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class, 'property_type_id');
    }
}
