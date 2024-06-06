<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkRule extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function getEnableWorkRules()
    {
        return WorkRule::where('id_enable',1)->get();
    }
    public function getWorkId($id)
    {
        return WorkRule::where('id',$id)->pluck('work_id')->first();
    }

}
