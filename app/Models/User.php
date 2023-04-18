<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'complete_name',
        'email',
        'identification_number',
        'password',
        'age',
        'citoyennete',
        'telephone',
        'residence',
        'language',
        'photo',
        'poste_presente_cdt',
        'nom_parti_politique_cdt',
        'exp_politique_cdt',
        'exp_pro_cdt',
        'niveau_etude_cdt',
        'domaine_etude_cdt',
        'realisations',
        'reseaux_sociaux',
        'isConfirm',
        'isActivated',
        'role',
        'pieces_jointes',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'reseaux_sociaux' => 'array',
        'pieces_jointes' => 'json',
        'isConfirm' => 'boolean',
        'isActivated' => 'boolean'
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCandidat()
    {
        return $this->role === 'candidat';
    }

    public function isOrganisateur()
    {
        return $this->role === 'organisateur';
    }

    public function isElecteur()
    {
        return $this->role === 'electeur';
    }
}
