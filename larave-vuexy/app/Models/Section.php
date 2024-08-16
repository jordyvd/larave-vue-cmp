<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    public function actions()
    {
        return $this->belongsToMany(Action::class, 'section_actions')
            ->select('actions.id', 'actions.name', 'actions.order', 'actions.parent_id', 'actions.level');
    }
}
