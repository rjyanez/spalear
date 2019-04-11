<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Functions;
use App\Transformers\Json;

class FunctionsController extends Controller
{
    public function primaryMenu($roles)
    {
        $roles = explode(',', $roles);
        $funtions = Functions::select('code', 'title', 'icon', 'url', 'parent_name')
                    ->whereNull('parent_name')
                    ->where('menu', 1)
                    ->orderBy('order', 'asc')
                    ->with('childs')
                    ->where(function ($query) use ($roles) {
                        return $query->doesntHave('roles')->orWhereHas('roles', function ($q) use ($roles) {
                            $q->whereIn('rol_code', $roles);
                        });
                    })
                    ->where(function ($query) use ($roles) {
                        return $query->doesntHave('childs')
                            ->orWhereHas('childs', function ($query) use ($roles) {
                                return $query->select('code', 'title', 'icon', 'url', 'parent_name')
                                    ->where('menu', 1)
                                    ->orderBy('order', 'asc')
                                    ->where(function ($query) use ($roles) {
                                        return $query->doesntHave('roles')->orWhereHas('roles', function ($q) use ($roles) {
                                            $q->whereIn('rol_code', $roles);
                                        });
                                    });
                            });
                    })
                    ->get()
                    ->toJson();
        return response()->json(Json::response(compact('funtions')), 200);
    }

    // public function secondaryMenu($role)
    // {
    //     $funtions = Functions::select('code', 'title', 'icon', 'url', 'parent_name')
    //         ->whereNotNull('parent_name')
    //         ->where('menu', true)
    //         ->orderBy('order', 'asc')
    //         ->whereHas('roles', function ($q) use ($role) {
    //             $q->where('rol_code', $role);
    //         })
    //         ->get()
    //         ->groupBy('parent_name')
    //         ->toJson();
    //     return response()->json(Json::response(compact('funtions')), 200);
    // }
}
