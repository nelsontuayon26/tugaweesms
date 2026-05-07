<?php

namespace App\Http\Controllers\Principal\Concerns;

use Illuminate\View\View;

trait SwapsToPrincipalView
{
    /**
     * If a principal-themed view exists for the given admin view result,
     * return the principal view with the same data instead.
     */
    protected function swapView($result)
    {
        if ($result instanceof View) {
            $principalView = str_replace('admin.', 'principal.', $result->getName());

            if (view()->exists($principalView)) {
                return view($principalView, $result->getData());
            }
        }

        return $result;
    }
}
