<?php

namespace App\Http\Controllers;

use App\Actions\ProcessingAction;
use App\Http\Requests\ProcessingRequest;
use App\Http\Resources\UsersResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class MonitoringController extends Controller
{
    public function processing(ProcessingRequest $request, ProcessingAction $processingAction): JsonResponse
    {
        $processingAction->execute($request->validated());
        return response()->json(['result' => 'true']);
    }

    public function purchases(User $user)
    {
        return new UsersResource($user);
    }
}
