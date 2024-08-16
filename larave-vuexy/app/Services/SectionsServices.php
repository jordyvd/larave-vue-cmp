<?php

namespace App\Services;

use App\Models\Section;
use App\DTO\Sections\StoreAccessDTO;
use App\Models\RoleSection;

class SectionsServices
{
    public function get()
    {
        $sections = Section::select('id', 'name', 'title', 'order', 'parent_id')->with('actions')->get();
        return $sections;
    }

    public function storeAccess($sections, $role_id)
    {
        foreach ($sections as $section) {
            if (count($section->actions) == 0 || !in_array(1, $section->actions)) {
                continue;
            }
            $sectionAction = new RoleSection();
            $sectionAction->section_id = $section->section_id;
            $sectionAction->role_id = $role_id;
            $sectionAction->actions = json_encode($section->actions);
            $sectionAction->save();
        }
    }

    public function getRoleSections($role_id)
    {
        $roleSections = RoleSection::select('role_sections.section_id', 'role_sections.role_id', 'role_sections.actions', 'sections.parent_id')
            ->join('sections', 'role_sections.section_id', '=', 'sections.id')
            ->where('role_id', $role_id)
            ->get();
        foreach ($roleSections as $roleSection) {
            $roleSection->actions = json_decode($roleSection->actions);
        }
        return $roleSections;
    }

    public function deleteRoleSections($role_id)
    {
        RoleSection::where('role_id', $role_id)->delete();
    }
}
