<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property integer $id
 * @property string uuid
 * @property string $name
 * @property string $description
 * @property string $affiliations
 * @property string $globus_user
 * @property string $password
 * @property mixed projects
 * @property string $api_token
 * @property boolean is_admin
 * @property string email
 * @property mixed communities
 *
 * @mixin Builder
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use HasUUID;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'globus_user', 'description',
        'api_token', 'affiliations', 'uuid', 'is_admin', 'slug'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project2user', 'user_id', 'project_id');
    }

    public function labs()
    {
        return $this->belongsToMany(Lab::class, 'lab2user', 'user_id', 'lab_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'owner_id');
    }

    public function communities()
    {
        return $this->hasMany(Community::class, 'owner_id');
    }

    public function adminTeams()
    {
        return $this->belongsToMany(Team::class, 'team2admin', 'user_id', 'team_id');
    }

    public function memberOfTeams()
    {
        return $this->belongsToMany(Team::class, 'team2member', 'user_id', 'team_id');
    }

    public function globusUploads()
    {
        return $this->hasMany(GlobusUploadDownload::class, 'owner_id');
    }

    public function hasCommunities()
    {
        return $this->communities->count() > 0;
    }

    public function datasets()
    {
        return $this->morphToMany(Dataset::class, 'item', 'item2dataset');
    }

    public function sendEmailVerificationNotification()
    {
        if (!config('app.email_verification')) {
            return;
        }

        $this->notify(new VerifyEmail);
    }
}
