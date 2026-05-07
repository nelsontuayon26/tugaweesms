<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Admin\ReportingController as BaseController;
use App\Http\Controllers\Principal\Concerns\SwapsToPrincipalView;
use App\Models\SavedReport;
use Illuminate\Http\Request;

class ReportController extends BaseController
{
    use SwapsToPrincipalView;

    public function index()
    {
        return $this->swapView(parent::index());
    }

    public function runSavedReport(SavedReport $savedReport)
    {
        if ($savedReport->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $savedReport->update(['last_run_at' => now()]);

        return redirect()->route('principal.reports.builder', [
            $savedReport->template,
            'saved_report' => $savedReport->id,
        ]);
    }
}
