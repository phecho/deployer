<?php

namespace REBELinBLUE\Deployer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use REBELinBLUE\Deployer\Traits\BroadcastChanges;

/**
 * Model for SSH keys.
 */
class Key extends Model
{
    use SoftDeletes, BroadcastChanges;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'private_key', 'public_key'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'private_key'];

    /**
     * Additional attributes to include in the JSON representation.
     *
     * @var array
     */
    protected $appends = ['fingerprint'];

    /**
     * Has many relationship.
     *
     * @return Project
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Generate the fingerprint for the SSH key.
     *
     * @return string
     * @see https://james-brooks.uk/fingerprint-an-ssh-key-using-php/
     */
    public function getFingerprintAttribute()
    {
        $key    = preg_replace('/^(ssh-[dr]s[as]\s+)|(\s+.+)|\n/', '', trim($this->private_key));
        $buffer = base64_decode($key);
        $hash   = md5($buffer);

        return preg_replace('/(.{2})(?=.)/', '$1:', $hash);
    }
}
