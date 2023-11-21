<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Novidade;
use Illuminate\Http\Request;

class NovidadeController extends Controller
{
    public function verify(Request $request)
    {
        $input = $request->all();
        if(isset($input['version']) && !empty($input['version']))
        {
            $updates = Novidade::where('version', '>', $input['version'])->first();
        } else {
            $updates = 0;
        }
        return response()->json($updates, 200);
    }
}
