<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParkingSpace extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'space_number',
        'is_available',
        'description',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_available' => 'boolean',
    ];

    /**
     * Obtient les réservations liées à cette place de parking.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(ParkingReservation::class);
    }
}
