<?php

namespace App\Http\Controllers;

use App\Models\Gateway;
use Illuminate\Http\Request;

class GatewayController extends Controller
{
    public function toggle($id)
    {
        $gateway = Gateway::findOrFail($id);
        $gateway->is_active = !$gateway->is_active;
        $gateway->save();

        return response()->json($gateway);
    }

    public function updatePriority(Request $request, $id)
    {
        $request->validate(['priority' => 'required|integer|min:1']);
        $gateway = Gateway::findOrFail($id);
        $gateway->priority = $request->priority;
        $gateway->save();

        return response()->json($gateway);
    }
}
