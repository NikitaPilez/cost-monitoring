<?php

namespace App\Actions;

use App\Models\User;
use App\Models\Purchase;
use Illuminate\Support\Facades\Hash;

class ProcessingAction
{
    public function execute($data)
    {
        $userId = $data['user_id'];
        $user = User::where('id', $userId)->first();
        if ($user === null) {
            User::create([
                'id' => $userId,
                'name' => 'user' . $userId,
                'email' => $userId . '@gmail.com',
                'password' => Hash::make($userId)
            ]);
        }
        $userSmsIds = Purchase::where('user_id', $userId)->pluck('sms_id')->toArray();
        foreach ($data['sms'] as $sms) {
            if (!in_array($sms['id'], $userSmsIds)) {
                $transformSms = $this->getTransformSms($sms['body']);
                Purchase::create([
                    'user_id' => $userId,
                    'sms_id' => $sms['id'],
                    'body' => $sms['body'],
                    'amount' => $transformSms['amount'],
                    'place' => $transformSms['place'],
                    'balance' => $transformSms['balance'],
                    'buy_at' => $sms['time']
                ]);
            }
        }
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
