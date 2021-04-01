<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roles_permissions extends Model
{
    protected $table = "roles_permissions";

    protected $fillable = ['role_id', 'permission_id'];
    public $timestamps = false;
}
