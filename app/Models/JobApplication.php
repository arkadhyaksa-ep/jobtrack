<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['company_name', 'job_title', 'job_url', 'status', 'applied_at', 'notes'])]
class JobApplication extends Model
{
}