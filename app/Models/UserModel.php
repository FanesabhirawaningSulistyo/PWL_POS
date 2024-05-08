<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

use App\Models\LevelModel; // Add this import statement

class UserModel extends Authenticatable implements JWTSubject
{
    use HasFactory;
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $table = 'm_user';
    public $timestamps = true;
    protected $primaryKey = 'user_id';

    protected $fillable = ['user_id','level_id','username','nama','password'];

    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class,'level_id','level_id');
    }
}