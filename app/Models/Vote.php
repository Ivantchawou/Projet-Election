<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Vote extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'start_hour',
        'end_hour',
        'statut',
        'user_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_hour' => 'datetime:H:i:s',
        'end_hour' => 'datetime:H:i:s'
    ];

    protected $dates = [
        'start_date',
        'end_date',
        'start_hour',
        'end_hour',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The candidats that belong to the Vote
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function candidats(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'vote_candidats', 'vote_id', 'candidat_id');
    }

    public function electeurs(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'vote_electeurs', 'vote_id', 'electeur_id');
    }


}
