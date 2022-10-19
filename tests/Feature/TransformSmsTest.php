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
     * @dataProvider dataProvider
     *
     * @return void
     */
    public function test_example($body, $amount, $place, $balance, $buyAt, $isAccrual)
    {
        $action = new ProcessingAction();
        $transformSms = $action->getTransformSms($body);
        $this->assertEquals($amount, $transformSms['amount']);
        $this->assertEquals($place, $transformSms['place']);
        $this->assertEquals($balance, $transformSms['balance']);
        $this->assertEquals($buyAt, $transformSms['buyAt']);
        $this->assertEquals($isAccrual, $transformSms['isAccrual']);
    }

    public function dataProvider()
    {
        return [
            [
                'Blabla 4.5241' . PHP_EOL . 'Oplata' . PHP_EOL . 'Uspeshno' . PHP_EOL . 'Summa:5.08 BYN' . PHP_EOL . 'Ostatok:604.59 BYN' . PHP_EOL . 'STOLOVAYA BAPB' . PHP_EOL . '20.09.2022 13:03:29',
                '5.08',
                'STOLOVAYA BAPB',
                '604.59',
                '20.09.2022 13:03:29',
                0
            ],
            [
                'Karta 4.5241' . PHP_EOL . 'Spisanie' . PHP_EOL . 'Uspeshno' . PHP_EOL . 'Summa:32.09 BYN' . PHP_EOL . 'Ostatok:412.45 BYN' . PHP_EOL . 'POPOLNENIE SCHETA: 91KBYN-C745D8' . PHP_EOL . '18.10.2022 14:52:11',
                '32.09',
                'POPOLNENIE SCHETA: 91KBYN-C745D8',
                '412.45',
                '18.10.2022 14:52:11',
                0
            ],
            [
            'Karta 4.5241' . PHP_EOL . 'Postuplenie' . PHP_EOL . 'Uspeshno' . PHP_EOL . 'Summa:45.32 BYN' . PHP_EOL . 'Ostatok:412.45 BYN' . PHP_EOL . '18.10.2022 14:52:11',
                '45.32',
                'Postuplenie',
                '412.45',
                '18.10.2022 14:52:11',
                1
            ]
        ];
    }
}
