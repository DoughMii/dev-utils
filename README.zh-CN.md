# dev-utils

一个用于日常开发的小型 PHP 工具集。模块化设计，暂时包含 Base62 编码器、 时间人类可读化工具等，适合在各类 PHP 项目中复用。

## 安装

使用 Composer 安装：

```sh
composer require doughmii/dev-utils
```

## 用法示例

### 🔢 Base62 编码器

以 [`Base62Encoder`](src/Id/Base62Encoder.php) 为例：

```php
use DoughMii\DevUtils\Id\Base62Encoder;

$encoder = new Base62Encoder();

// 编码和解码单个整数
$encoded = $encoder->encode(123456);
$decoded = $encoder->decode($encoded);

// 使用 salt 进行编码和解码
$encodedSalted = $encoder->encode(123456, 'my-salt');
$decodedSalted = $encoder->decode($encodedSalted, 'my-salt');

// 编码和解码多个整数
$data = [1, 2, 3];
$encodedMultiple = $encoder->encodeMultiple($data, 'salt123');
$decodedMultiple = $encoder->decodeMultiple($encodedMultiple, count($data), 'salt123');
```

### 🕒 时间人类可读化工具

使用 [`TimeHumanizer`](src/Time/TimeHumanizer.php) 将时间差转换为更易读的格式：

```php
use DoughMii\DevUtils\Time\TimeHumanizer;

$past = new DateTimeImmutable('-3 hours');
echo TimeHumanizer::diffForHumans($past); 
// 输出示例: "3 hours ago"

$future = new DateTimeImmutable('+2 days');
echo TimeHumanizer::diffForHumans($future, null, 'zh');
// 输出示例: "2 天后"
```

## 测试

运行 PHPUnit 测试：

```sh
vendor/bin/phpunit
```

## 许可证

MIT
