<?php

namespace App\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class FailedJobs extends Model
{
    use Notifiable;

    protected $table = 'failed_jobs';
    protected $primaryKey = 'id';
}
