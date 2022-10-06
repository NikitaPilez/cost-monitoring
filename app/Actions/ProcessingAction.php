<?php

namespace App\Actions;

use App\Events\NewPurchase;
use App\Models\Purchase;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

class ProcessingAction
{
    public function execute($data)
    {
        /** @var User $user */
        $user = auth()->user();
        $userSmsIds = Purchase::where('user_id', $user->id)->pluck('sms_id')->toArray();
        $haveNewPurchase = false;
        foreach ($data['sms'] as $sms) {
            if (!in_array($sms['id'], $userSmsIds)) {
                $haveNewPurchase = true;
                $transformSms = $this->getTransformSms($sms['body']);
                Purchase::create([
                    'user_id' => $user->id,
                    'sms_id' => $sms['id'],
                    'body' => $sms['body'],
                    'amount' => $transformSms['amount'],
                    'place' => $transformSms['place'],
                    'balance' => $transformSms['balance'],
                    'buy_at' => $sms['time']
                ]);
            }
        }

        $haveNewPurchase !== true ?: NewPurchase::dispatch($user->id);
    }

    public function getTransformSms($body)
    {
        $splitBody = explode(PHP_EOL, $body);
        preg_match('(\d+[.]\d+)', $splitBody[3], $matchAmount);
        preg_match('(\d+[.]\d+)', $splitBody[4], $matchBalance);
        $amount = $matchAmount[0];
        $place = $splitBody[5];
        $buyAt = $splitBody[6];
        $balance = $matchBalance[0];

        return [
            'amount' => $amount,
            'place' => $place,
            'balance' => $balance,
            'buyAt' => $buyAt
        ];
    }
}
