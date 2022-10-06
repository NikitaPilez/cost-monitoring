<?php

namespace App\Http\Controllers;

use App\Actions\ProcessingAction;
use App\Http\Requests\GetPurchasesRequest;
use App\Http\Requests\ProcessingRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Http\Resources\PurchaseCollection;
use App\Http\Resources\PurchasesResource;
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

    public function userPurchasesIndex(GetPurchasesRequest $request, User $user): PurchaseCollection
    {
        $purchases = Purchase::filter($user->id, $request->search)->get();
        return new PurchaseCollection($purchases);
    }

    public function userPurchasesShow(Purchase $purchase): PurchasesResource
    {
        return new PurchasesResource($purchase);
    }

    public function userPurchasesUpdate(Purchase $purchase, UpdatePurchaseRequest $request): PurchasesResource
    {
        $purchase->update($request->validated());
        return new PurchasesResource($purchase);
    }
}
