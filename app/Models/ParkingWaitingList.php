<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParkingWaitingList extends Model
{
    use HasFactory;

    /**
     * Le nom de la table associée au modèle.
     *
     * @var string
     */
    protected $table = 'parking_waiting_list';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'position',
        'requested_at',
        'is_active',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'requested_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Obtient l'utilisateur associé à cette entrée de liste d'attente.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
