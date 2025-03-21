<?php



namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Gate;







class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        // 'deleted_at',
        'email',
        'password',
        'phone',
        'province_id',
        'district_id',
        'ward_id',
        'birthday',
        'image',
        'address',
        'decription',
        'user_catalogue_id',
        'deleted_at',
        'publish'
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function UserCatalogue()
    {
        return $this->belongsTo(UserCatalogue::class, 'user_catalogue_id', 'id');
    }
    public function hasPermission($permissionCanonical)
    {
        return $this->UserCatalogue->permissions->contains('canonical', $permissionCanonical);
    }
}
