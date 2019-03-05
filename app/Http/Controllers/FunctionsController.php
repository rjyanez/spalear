<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Functions;
use App\Transformers\Json;

class FunctionsController extends Controller
{
    public function primaryMenu($role){
        $funtions = Functions::select('code', 'title', 'icon', 'url')
                            ->whereNull('parent_name')
                            ->where('menu', 1)
                            ->orderBy('order', 'asc')
                            ->whereHas('roles',function ($q) use ($role) {
                                $q->where('rol_code', $role);
                            })
                            ->get()
                            ->toJson();
        return response()->json(Json::response(compact('funtions')), 200);
    }

    public function secondaryMenu($role){
        $funtions = Functions::select('code', 'title', 'icon', 'url','parent_name')
                                ->whereNotNull('parent_name')
                                ->where('menu', true)
                                ->orderBy('order', 'asc')
                                ->whereHas('roles',function ($q) use ($role) {
                                    $q->where('rol_code', $role);
                                })
                                ->get()
                                ->groupBy('parent_name')
                                ->toJson();
        return response()->json(Json::response(compact('funtions')), 200);
    }
}
