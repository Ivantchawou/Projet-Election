<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoteElecteur extends Model
{
    use HasFactory;
    protected $fillable = [
        'vote_id',
        'electeur_id',
        'candidat_id',
        'isVoted'
    ];
}
