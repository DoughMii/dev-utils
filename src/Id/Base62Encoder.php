<?php

namespace Doughmii\DevUtils\Id;

class Base62Encoder
{
    protected const DEFAULT_CHARSET = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    protected const MULTIPLE_CHUNK_BITS = 20;
    protected const MULTIPLE_CHUNK_MASK = 0xFFFFF;

    protected string $charset;
    protected int $base;
    protected array $charToValue = [];

    public function __construct(?string $charset = null)
    {
        $this->charset = $charset ?? self::DEFAULT_CHARSET;
        $this->base = strlen($this->charset);

        if ($this->base === 0) {
            throw new \InvalidArgumentException('字符集不能为空。');
        }

        $chars = str_split($this->charset);
        if (count(array_unique($chars)) !== $this->base) {
            throw new \InvalidArgumentException('字符集不能包含重复字符。');
        }

        foreach ($chars as $i => $char) {
            $this->charToValue[$char] = $i;
        }
    }

    public function encode(int $num, string $salt = ''): string
    {
        if ($num < 0) {
            throw new \InvalidArgumentException('不支持对负数进行编码。');
        }

        if ($num === 0) {
            return $this->charset[0];
        }

        $num = $this->applySalt($num, $salt);
        $encoded = '';

        do {
            $encoded = $this->charset[$num % $this->base] . $encoded;
            $num = intdiv($num, $this->base);
        } while ($num > 0);

        return $encoded;
    }

    public function decode(string $str, string $salt = ''): int
    {
        $decoded = 0;
        $len = strlen($str);

        for ($i = 0; $i < $len; $i++) {
            $char = $str[$i];
            if (!isset($this->charToValue[$char])) {
                throw new \InvalidArgumentException("无效字符 \"$char\" 不在字符集中。");
            }
            $decoded = $decoded * $this->base + $this->charToValue[$char];
        }

        return $this->revertSalt($decoded, $salt);
    }

    public function encodeMultiple(array $numbers, string $salt = ''): string
    {
        $maxChunks = intdiv(PHP_INT_SIZE * 8, self::MULTIPLE_CHUNK_BITS);
        if (count($numbers) > $maxChunks) {
            throw new \InvalidArgumentException("最多只能打包 {$maxChunks} 个整数。");
        }

        $combined = 0;
        foreach ($numbers as $i => $num) {
            if ($num < 0 || $num > self::MULTIPLE_CHUNK_MASK) {
                throw new \InvalidArgumentException("要打包的数字 #$i ($num) 超出范围 (0 ~ " . self::MULTIPLE_CHUNK_MASK . ")");
            }
            $combined |= ($num & self::MULTIPLE_CHUNK_MASK) << ($i * self::MULTIPLE_CHUNK_BITS);
        }

        return $this->encode($combined, $salt);
    }

    public function decodeMultiple(string $encoded, int $count, string $salt = ''): array
    {
        $maxChunks = intdiv(PHP_INT_SIZE * 8, self::MULTIPLE_CHUNK_BITS);
        if ($count > $maxChunks) {
            throw new \InvalidArgumentException("最多只能解码 {$maxChunks} 个整数。");
        }

        $combined = $this->decode($encoded, $salt);
        $results = [];
        for ($i = 0; $i < $count; $i++) {
            $results[] = ($combined >> ($i * self::MULTIPLE_CHUNK_BITS)) & self::MULTIPLE_CHUNK_MASK;
        }

        return $results;
    }

    protected function applySalt(int $num, string $salt): int
    {
        $saltHash = ($salt === '') ? 0 : sprintf('%u', crc32($salt));
        return $num ^ $saltHash;
    }

    protected function revertSalt(int $num, string $salt): int
    {
        $saltHash = ($salt === '') ? 0 : sprintf('%u', crc32($salt));
        return $num ^ $saltHash;
    }
}
