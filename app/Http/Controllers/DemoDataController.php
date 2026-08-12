<?php

namespace App\Http\Controllers;

use App\Support\DemoData;
use Illuminate\Http\Request;

class DemoDataController extends Controller
{
    public function import(Request $request)
    {
        $result = DemoData::import();

        return back()->with([
            'message' => $result['message'],
            'alert-type' => $result['status'] === 'empty' ? 'warning' : 'success',
        ]);
    }

    public function clear(Request $request)
    {
        $result = DemoData::clearFromUi();

        $type = match ($result['status']) {
            'hidden' => 'success',
            'already_hidden' => 'info',
            default => 'warning',
        };

        return back()->with([
            'message' => $result['message'],
            'alert-type' => $type,
        ]);
    }
}
