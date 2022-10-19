<?php

namespace Tests\Feature;

use App\Actions\ProcessingAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransformSmsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $action = new ProcessingAction();
        foreach ($this->mockData() as $data) {
            $transformSms = $action->getTransformSms($data['body']);
            $this->assertEquals($data['result']['amount'], $transformSms['amount']);
            $this->assertEquals($data['result']['place'], $transformSms['place']);
            $this->assertEquals($data['result']['balance'], $transformSms['balance']);
            $this->assertEquals($data['result']['buyAt'], $transformSms['buyAt']);
            $this->assertEquals($data['result']['isAccrual'], $transformSms['isAccrual']);
        }
    }

    public function mockData()
    {
        return [
            [
                'body' => 'Blabla 4.5241' . PHP_EOL . 'Oplata' . PHP_EOL . 'Uspeshno' . PHP_EOL . 'Summa:5.08 BYN' . PHP_EOL . 'Ostatok:604.59 BYN' . PHP_EOL . 'STOLOVAYA BAPB' . PHP_EOL . '20.09.2022 13:03:29',
                'result' => [
                    'amount' => '5.08',
                    'place' => 'STOLOVAYA BAPB',
                    'balance' => '604.59',
                    'buyAt' => '20.09.2022 13:03:29',
                    'isAccrual' => 0
                ]
            ],
            [
                'body' => 'Karta 4.5241' . PHP_EOL . 'Spisanie' . PHP_EOL . 'Uspeshno' . PHP_EOL . 'Summa:32.09 BYN' . PHP_EOL . 'Ostatok:412.45 BYN' . PHP_EOL . 'POPOLNENIE SCHETA: 91KBYN-C745D8' . PHP_EOL . '18.10.2022 14:52:11',
                'result' => [
                    'amount' => '32.09',
                    'place' => 'POPOLNENIE SCHETA: 91KBYN-C745D8',
                    'balance' => '412.45',
                    'buyAt' => '18.10.2022 14:52:11',
                    'isAccrual' => 0
                ]
            ],
            [
                'body' => 'Karta 4.5241' . PHP_EOL . 'Postuplenie' . PHP_EOL . 'Uspeshno' . PHP_EOL . 'Summa:45.32 BYN' . PHP_EOL . 'Ostatok:412.45 BYN' . PHP_EOL . '18.10.2022 14:52:11',
                'result' => [
                    'amount' => '45.32',
                    'place' => 'Postuplenie',
                    'balance' => '412.45',
                    'buyAt' => '18.10.2022 14:52:11',
                    'isAccrual' => 1
                ]
            ]
        ];
    }
}
