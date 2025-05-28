# dev-utils

> English | [简体中文](README.zh-CN.md)

A lightweight PHP utility toolkit for daily development. Modular design — currently includes a **Base62 encoder** and a **human-friendly time formatter**, and more tools can be added over time.

## Installation

Install via Composer：

```sh
composer require doughmii/dev-utils
```

## Usage

### 🔢 Base62Encoder

A simple Base62 encoder/decoder that supports optional salt and batch encoding.

```php
use DoughMii\DevUtils\Id\Base62Encoder;

$encoder = new Base62Encoder();

// Encode and decode a single integer
$encoded = $encoder->encode(123456);
$decoded = $encoder->decode($encoded);

// Encode and decode with salt
$encodedSalted = $encoder->encode(123456, 'my-salt');
$decodedSalted = $encoder->decode($encodedSalted, 'my-salt');

// Encode and decode multiple integers
$data = [1, 2, 3];
$encodedMultiple = $encoder->encodeMultiple($data, 'salt123');
$decodedMultiple = $encoder->decodeMultiple($encodedMultiple, count($data), 'salt123');
```

### 🕒 TimeHumanizer

Converts time differences into human-friendly text, supporting multiple languages (English, Chinese) and precise month/year logic.

```php
use DoughMii\DevUtils\Time\TimeHumanizer;

$now = new DateTimeImmutable();
$past = $now->modify('-3 days');

echo TimeHumanizer::diffForHumans($past); // e.g. "3 days ago"
echo TimeHumanizer::fromNow($past);       // same as above

// With custom locale (zh)
echo TimeHumanizer::diffForHumans($past, null, 'zh'); // "3 天前"
```

## 测试

运行 PHPUnit 测试：

```sh
vendor/bin/phpunit
```

## 许可证

MIT
