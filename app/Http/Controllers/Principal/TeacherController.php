<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Admin\TeacherController as BaseController;
use App\Http\Controllers\Principal\Concerns\SwapsToPrincipalView;
use Illuminate\Http\Request;

class TeacherController extends BaseController
{
    use SwapsToPrincipalView;

    public function index()
    {
        return $this->swapView(parent::index());
    }

    public function show(\App\Models\Teacher $teacher)
    {
        return $this->swapView(parent::show($teacher));
    }
}
