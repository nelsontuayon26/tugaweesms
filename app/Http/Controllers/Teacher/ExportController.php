<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\SF1Export;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    //

    public function sf1(Request $request)
    {
        $sectionId = $request->section_id;

        return Excel::download(new SF1Export($sectionId), 'SF1_Section_'.$sectionId.'.xlsx');
    }
    public function sf9(Request $request)
{
    $sectionId = $request->section_id;

    return response()->json([
        'message' => 'SF9 export not yet implemented',
        'section_id' => $sectionId
    ]);
}
}
