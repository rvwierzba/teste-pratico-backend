<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Client;
use App\Models\Product;
use App\Models\Gateway;
use Illuminate\Http\Request;
use App\Services\PaymentService;

class TransactionController extends Controller
{
    public function index()
    {
        return Transaction::with('products')->get();
    }

    public function show($id)
    {
        return Transaction::with('products')->findOrFail($id);
    }

    public function purchase(Request $request, PaymentService $paymentService)
    {
        $request->validate([
            'client_name' => 'required|string',
            'client_email' => 'required|email',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'card_number' => 'required|string|size:16',
            'cvv' => 'required|string|size:3'
        ]);

        // Criar cliente se não existir
        $client = Client::firstOrCreate(['email' => $request->client_email], ['name' => $request->client_name]);

        // Calcular valor total
        $amount = 0;
        foreach ($request->products as $item) {
            $product = Product::find($item['id']);
            $amount += $product->amount * $item['quantity'];
        }

        // Chamar serviço de pagamento com fallback
        $result = $paymentService->processPayment([
            'amount' => $amount,
            'name' => $client->name,
            'email' => $client->email,
            'cardNumber' => $request->card_number,
            'cvv' => $request->cvv
        ]);

        if ($result['status'] === 'FAILED') {
            return response()->json(['error' => 'Payment failed'], 400);
        }

        // Criar transação
        $transaction = Transaction::create([
            'client_id' => $client->id,
            'gateway_id' => $result['gateway_id'],
            'external_id' => $result['external_id'],
            'status' => $result['status'],
            'amount' => $amount,
            'card_last_numbers' => substr($request->card_number, -4)
        ]);

        foreach ($request->products as $item) {
            $transaction->products()->attach($item['id'], ['quantity' => $item['quantity']]);
        }

        return response()->json($transaction, 201);
    }

    public function refund($id, PaymentService $paymentService)
    {
        $transaction = Transaction::findOrFail($id);

        $result = $paymentService->refund($transaction);

        return response()->json($result);
    }
}
