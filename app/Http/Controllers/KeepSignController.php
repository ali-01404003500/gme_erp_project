<?php

namespace App\Http\Controllers;

use App\Models\KeepSign;
use Illuminate\Http\Request;

class KeepSignController extends Controller
{
    //

     public function saveSignature(Request $request)
    {
        $request->validate([
            'signature' => 'required',
            'keep_signatureable_type' => 'required',
            'keep_signatureable_id' => 'required',
        ]);

        $entry = KeepSign::updateOrCreate([
            'keep_signatureable_type' => $request->keep_signatureable_type,
            'keep_signatureable_id' => $request->keep_signatureable_id,
        ], [
            'signature' => $request->signature,
        ]);

        return response()->json(['success' => true, 'path' => $entry->signature]);
    }
}
