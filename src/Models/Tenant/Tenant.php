<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $connection = 'tenants';
    protected $table      = 'tenants';
    protected $primaryKey = 'tenant_id';
}
