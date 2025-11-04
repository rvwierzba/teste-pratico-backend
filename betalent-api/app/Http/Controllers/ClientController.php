<?php

namespace App\Http\Controllers;

use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        return Client::all();
    }

    public function show($id)
    {
        $client = Client::with('transactions.products')->findOrFail($id);
        return response()->json($client);
    }
}
