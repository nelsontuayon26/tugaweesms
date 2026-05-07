<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Admin\SectionController as BaseController;
use App\Http\Controllers\Principal\Concerns\SwapsToPrincipalView;
use Illuminate\Http\Request;

class SectionController extends BaseController
{
    use SwapsToPrincipalView;

    public function index(Request $request)
    {
        return $this->swapView(parent::index($request));
    }

    public function show($id)
    {
        return $this->swapView(parent::show($id));
    }
}
