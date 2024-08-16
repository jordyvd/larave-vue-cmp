<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionAction extends Model
{
    use HasFactory;

    public function section()
    {
        return $this->belongsTo(Section::class)->select(['id', 'name']);
    }

    public function action()
    {
        return $this->belongsTo(Action::class)->select(['id', 'name', 'parent_id']);
    }

    public function selectFields()
    {
        return self::select('id', 'section_id', 'action_id');
    }
}
