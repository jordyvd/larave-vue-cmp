<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SectionsServices;
use App\Handlers\SectionsHandlers;
use App\DTO\Sections\StoreAccessDTO;
use Illuminate\Support\Facades\DB;

class SectionsController extends Controller
{
    private $sectionsServices;
    private $sectionsHandlers;
    public function __construct()
    {
        $this->sectionsServices = new SectionsServices();
        $this->sectionsHandlers = new SectionsHandlers();
    }
    public function get(Request $request)
    {
        $sections = $this->sectionsServices->get();
        $sections = $this->sectionsHandlers->setAccess($sections);
        $roleSections = $this->sectionsServices->getRoleSections($request->role_id);
        $sections = $this->sectionsHandlers->unionAccess($roleSections, $sections);
        $sectionsWithAccess = $this->sectionsHandlers->searchSectionsWithAccess($sections);
        $sections = $this->sectionsHandlers->setAccessInParents($sectionsWithAccess, $sections);
        return $this->response($sections, 'Sections retrieved successfully', 200);
    }

    public function getRecursive(Request $request)
    {
        $sections = $this->sectionsServices->get();
        $sections = $this->sectionsHandlers->setAccess($sections);
        $roleSections = $this->sectionsServices->getRoleSections($request->role_id);
        $sections = $this->sectionsHandlers->unionAccess($roleSections, $sections);
        $sectionsWithAccess = $this->sectionsHandlers->searchSectionsWithAccess($sections);
        $sections = $this->sectionsHandlers->setAccessInParents($sectionsWithAccess, $sections);
        /***/
        $recursiveActions = $this->sectionsHandlers->recursiveActionsBySection($sections);
        $recursive = $this->sectionsHandlers->recursiveSections($recursiveActions);
        return $this->response($recursive, 'Sections retrieved successfully', 200);
    }

    public function storeAccess(Request $request)
    {
        DB::beginTransaction();
        try {
            $storeAccessDTO = new StoreAccessDTO($request->all()); // request
            $newsAccess = $this->sectionsHandlers->groupActionsBySections($storeAccessDTO->access);
            $currentAccess = $this->sectionsServices->getRoleSections($request->role_id);
            $currentAccess = $this->sectionsHandlers->replaceCurrentAccessWithNewsAccess($newsAccess, $currentAccess);
            $sectionsAll = array_merge(json_decode(json_encode($currentAccess)), json_decode(json_encode($newsAccess)));
            /*** */
            $this->sectionsServices->deleteRoleSections($request->role_id);
            $this->sectionsServices->storeAccess($sectionsAll, $request->role_id);
            DB::commit();
            return $this->response([], 'Access stored successfully', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->response([], $e->getMessage(), 500);
        }
    }
}
