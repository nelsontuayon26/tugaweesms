<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Admin\ActivityLogController as BaseController;
use App\Http\Controllers\Principal\Concerns\SwapsToPrincipalView;
use Illuminate\Http\Request;

class ActivityLogController extends BaseController
{
    use SwapsToPrincipalView;

    public function index(Request $request)
    {
        return $this->swapView(parent::index($request));
    }
}
