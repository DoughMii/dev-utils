<?php

use PHPUnit\Framework\TestCase;
use Doughmii\DevUtils\Id\Base62Encoder;

class Base62EncoderTest extends TestCase
{
    public function testEncodeAndDecode()
    {
        $encoder = new Base62Encoder();
        $numbers = [0, 1, 10, 99999999, PHP_INT_MAX];

        foreach ($numbers as $num) {
            $encoded = $encoder->encode($num);
            $decoded = $encoder->decode($encoded);
            $this->assertEquals($num, $decoded);
        }
    }

    public function testSaltedEncodeDecode()
    {
        $encoder = new Base62Encoder();
        $num = 12345678;
        $salt = 'my-secret';

        $encoded = $encoder->encode($num, $salt);
        $decoded = $encoder->decode($encoded, $salt);
        $this->assertEquals($num, $decoded);

        // 使用不同 salt 解码应失败
        $this->assertNotEquals($num, $encoder->decode($encoded, 'wrong-salt'));
    }

    public function testEncodeMultipleAndDecode()
    {
        $encoder = new Base62Encoder();
        $data = [1, 2, 3];

        $encoded = $encoder->encodeMultiple($data, 'salt123');
        $decoded = $encoder->decodeMultiple($encoded, count($data), 'salt123');

        $this->assertEquals($data, $decoded);
    }

    public function testInvalidCharacterInDecode()
    {
        $this->expectException(\InvalidArgumentException::class);

        $encoder = new Base62Encoder();
        $encoder->decode('@#$');
    }

    public function testInvalidChunkRange()
    {
        $this->expectException(\InvalidArgumentException::class);

        $encoder = new Base62Encoder();
        $encoder->encodeMultiple([9999999, 1048576]); // 超过 0xFFFFF
    }

    public function testTooManyChunks()
    {
        $this->expectException(\InvalidArgumentException::class);

        $encoder = new Base62Encoder();
        $chunks = array_fill(0, 100, 1); // 太多
        $encoder->encodeMultiple($chunks);
    }

    public function testNegativeNumberEncoding()
    {
        $this->expectException(\InvalidArgumentException::class);

        $encoder = new Base62Encoder();
        $encoder->encode(-1);
    }
}
