<?php

namespace App\Actions;

use App\Events\NewPurchase;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PHPUnit\Exception;

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
                if ($this->isValidSms($sms['body'])) {
                    $haveNewPurchase = true;
                    try {
                        $transformSms = $this->getTransformSms($sms['body']);
                        Purchase::create([
                            'user_id' => $user->id,
                            'sms_id' => $sms['id'],
                            'body' => $sms['body'],
                            'amount' => $transformSms['amount'],
                            'place' => $transformSms['place'],
                            'balance' => $transformSms['balance'],
                            'buy_at' => $transformSms['buyAt'],
                            'is_accrual' => $transformSms['isAccrual']
                        ]);
                    } catch (Exception $exception) {
                        Log::error('Processing error: ' . $exception->getMessage(), $data);
                    }

                }
            }
        }

        $haveNewPurchase !== true ?: NewPurchase::dispatch($user->id);
    }

    public function isValidSms(string $body): bool
    {
        $splitBody = explode(PHP_EOL, $body);
        return Str::contains($splitBody[0], 'Karta');
    }

    public function getTransformSms($body)
    {
        $splitBody = explode(PHP_EOL, $body);
        preg_match('(\d+[.]\d+)', $splitBody[3], $matchAmount);
        preg_match('(\d+[.]\d+)', $splitBody[4], $matchBalance);
        $isAccrual = $splitBody[1] === 'Postuplenie';
        $amount = $matchAmount[0];
        $place = $isAccrual ? 'Postuplenie' : $splitBody[5];
        $buyAt = $isAccrual ? $splitBody[5] : $splitBody[6];
        $balance = $matchBalance[0];

        return [
            'isAccrual' => $isAccrual,
            'amount' => $amount,
            'place' => $place,
            'balance' => $balance,
            'buyAt' => $buyAt
        ];
    }
}
