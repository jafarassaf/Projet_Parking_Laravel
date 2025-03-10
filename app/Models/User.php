<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Détermine si l'utilisateur est un administrateur.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    /**
     * Obtient les réservations de parking de l'utilisateur.
     */
    public function parkingReservations(): HasMany
    {
        return $this->hasMany(ParkingReservation::class);
    }

    /**
     * Obtient la réservation de parking active de l'utilisateur.
     */
    public function activeReservation(): HasOne
    {
        return $this->hasOne(ParkingReservation::class)->where('is_active', true);
    }

    /**
     * Obtient l'entrée de liste d'attente de l'utilisateur.
     */
    public function waitingListEntry(): HasOne
    {
        return $this->hasOne(ParkingWaitingList::class)->where('is_active', true);
    }
}
