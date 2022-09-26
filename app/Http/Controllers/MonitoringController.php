<?php

namespace App\Http\Controllers;

use App\Actions\ProcessingAction;
use App\Http\Requests\GetPurchasesRequest;
use App\Http\Requests\ProcessingRequest;
use App\Http\Resources\PurchaseCollection;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class MonitoringController extends Controller
{
    public function processing(ProcessingRequest $request, ProcessingAction $processingAction): JsonResponse
    {
        $processingAction->execute($request->validated());
        return response()->json(['result' => 'true']);
    }

    public function purchases(GetPurchasesRequest $request, User $user)
    {
        $purchases = Purchase::filter($user->id, $request->search)->get();
        return new PurchaseCollection($purchases);
    }
}
