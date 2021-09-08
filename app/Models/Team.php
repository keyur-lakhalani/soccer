<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $table = 'team';

    protected $fillable = [
        'name',
        'logo'
    ];
    
    /**
     * Get the player associated with the user.
     */
    public function player()
    {
        return $this->hasMany(TeamPlayer::class);
    }
}
