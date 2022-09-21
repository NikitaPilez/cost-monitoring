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
//                $transformSms = $this->getTransformSms($sms['body']);
                Purchase::create([
                    'user_id' => $userId,
                    'body' => $sms['body'],
                    'sms_id' => $sms['id'],
                    'buy_at' => $sms['time']
                ]);
            }
        }
    }

    public function getTransformSms($body)
    {
        $splitBody = explode(PHP_EOL, $body);
        $amount = $splitBody[3]; // TODO
        $place = $splitBody[5];
        $buyAt = $splitBody[6];
        $balance = $splitBody[4]; // TODO

        return [
            'amount' => $amount,
            'place' => $place,
            'balance' => $balance,
            'buyAt' => $buyAt
        ];
    }
}
