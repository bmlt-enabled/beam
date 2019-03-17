<?php

namespace App;

use App\ExternalApi\BeamApi;
use App\ExternalApi\BmltApi;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $service_body;
    public $beam;

    const ADMIN_TYPE = 'admin';
    const DEFAULT_TYPE = 'default';

    public function isAdmin()    {
        return $this->type === self::ADMIN_TYPE;
    }

    public static function getBeamedUsers() {
        $beamed_users = BeamApi::GetUsers();
        $beamed_users_with_service_bodes = [];

        foreach ($beamed_users[0]->users as $beamed_user) {
            $beamed_user->service_body = self::getBeamedServiceBodyForId($beamed_users[0]->service_bodies, $beamed_user->service_body_id);
            array_push($beamed_users_with_service_bodes, $beamed_user);
        }

        return $beamed_users_with_service_bodes;
    }

    private static function getBeamedServiceBodyForId($service_bodies, $id) {
        foreach ($service_bodies as $service_body) {
            if ($service_body->id == $id) {
                return $service_body;
            }
        }
    }
}
