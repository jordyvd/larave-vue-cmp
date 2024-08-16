<?php

namespace App\Handlers;

class SectionsHandlers
{
    public function recursiveActionsBySection($sections)
    {
        $sectionsNews = json_decode(json_encode($sections));
        foreach ($sectionsNews as $key => $section) {
            $sectionsNews[$key]->actions = $this->recursiveActions($section->actions);
        }
        return $sectionsNews;
    }

    public function recursiveActions($actions, $parentId = null)
    {
        $recursive = [];
        foreach ($actions as $action) {
            if ($action->parent_id == $parentId) {
                $children = $this->recursiveActions($actions, intval($action->id));
                if ($children) {
                    $action->children = $children;
                } else {
                    $action->children = [];
                }
                $recursive[] = $action;
            }
        }
        return $recursive;
    }

    public function recursiveSections($sections, $parentId = null)
    {
        $recursive = [];
        foreach ($sections as $section) {
            if ($section->parent_id == $parentId) {
                $children = $this->recursiveSections($sections, $section->id);
                if ($children) {
                    $section->children = $children;
                } else {
                    $section->children = [];
                }
                $recursive[] = $section;
            }
        }
        return $recursive;
    }

    public function setAccess($sections)
    {
        foreach ($sections as $section) {
            $section->access = false;
            foreach ($section->actions as $action) {
                $action->access = false;
            }
        }
        return $sections;
    }

    public function groupActionsBySections($sections)
    {
        $grouped = [];

        $uniques = array_unique(array_column($sections, 'section_id'));

        foreach ($uniques as $unique) {
            $grouped[] = [
                'section_id' => $unique,
                'parent_id' => null,
                'actions_add' => [],
                'actions_remove' => [],
                'actions' => []
            ];

            foreach ($sections as $section) {
                if ($section['section_id'] == $unique) {
                    if ($section['access']) {
                        $grouped[count($grouped) - 1]['actions_add'][] = $section['action_id'];
                        $grouped[count($grouped) - 1]['actions'][] = $section['action_id'];
                    } else {
                        $grouped[count($grouped) - 1]['actions_remove'][] = $section['action_id'];
                    }
                }
            }
        }

        return $grouped;
    }

    public function replaceCurrentAccessWithNewsAccess($newsAccess, $currentAccess)
    {
        foreach ($currentAccess as $access) {
            foreach ($newsAccess as $news) {
                if ($access->section_id == $news['section_id']) {
                    $access->actions = array_diff($access->actions, $news['actions_remove']);
                    $access->actions = array_merge($access->actions, $news['actions_add']);
                }
            }
        }
        return $currentAccess;
    }

    public function unionAccess($currentAccess, $sections)
    {
        foreach ($sections as $section) {
            $section->access = false;
            foreach ($currentAccess as $access) {
                if ($section->id == $access->section_id) {
                    $section->access = true;
                    foreach ($section->actions as $action) {
                        if (in_array($action->id, $access->actions)) {
                            $action->access = true;
                        }
                    }
                }
            }
        }
        return $sections;
    }

    public function searchSectionsWithAccess($sections)
    {
        $sectionsWithAccess = [];
        foreach ($sections as $section) {
            foreach ($section->actions as $action) {
                if ($action->access) {
                    $sectionsWithAccess[] = $section;
                    break;
                }
            }
        }
        return $sectionsWithAccess;
    }

    public function setAccessInParents($sectionsWithAccess, $sections)
    {
        $i = 0;
        while ($i < count($sectionsWithAccess)) {
            foreach ($sections as $section) {
                if ($sectionsWithAccess[$i]->parent_id == $section->id) {
                    $section->access = true;
                    $sectionsWithAccess[] = $section;
                }
            }
            $i++;
        }
        return $sections;
    }
}
