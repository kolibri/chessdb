<?php declare(strict_types = 1);

namespace Tests\AppBundle\Helper;

use AppBundle\Helper\MovesTransformHelper;
use PHPUnit\Framework\TestCase;

class MovesTransformHelperTest extends TestCase
{
    /** @dataProvider  moveStringArrayProvider */
    public function testTransformArrayToString(array $asArray, string $asString)
    {
        $transformHelper = new MovesTransformHelper();

        $this->assertEquals(
            $asString,
            $transformHelper->moveArrayToString($asArray)
        );
    }

    /** @dataProvider  moveStringArrayProvider */
    public function testTransformStringToArray(array $asArray, string $asString)
    {
        $transformHelper = new MovesTransformHelper();

        $this->assertEquals(
            $asArray,
            $transformHelper->moveStringToArray($asString)
        );
    }

    public function moveStringArrayProvider()
    {
        return [
            [
                [
                    "e4",
                    "d5",
                    "exd5",
                    "Qxd5",
                    "Nc3",
                    "Qa5",
                    "d4",
                    "c6",
                    "Nf3",
                    "Bg4",
                    "Bf4",
                    "e6",
                    "h3",
                    "Bxf3",
                    "Qxf3",
                    "Bb4",
                    "Be2",
                    "Nd7",
                    "a3",
                    "O-O-O",
                    "axb4",
                    "Qxa1+",
                    "Kd2",
                    "Qxh1",
                    "Qxc6+",
                    "bxc6",
                    "Ba6#",
                ],
                '1.e4 d5 2.exd5 Qxd5 3.Nc3 Qa5 4.d4 c6 5.Nf3 Bg4 6.Bf4 e6 7.h3 Bxf3 8.Qxf3 Bb4 9.Be2 Nd7 10.a3 O-O-O '.
                '11.axb4 Qxa1+ 12.Kd2 Qxh1 13.Qxc6+ bxc6 14.Ba6#',
            ],


        ];
    }
}
