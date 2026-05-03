<?php

declare(strict_types=1);

namespace App\Core\Exceptions;

use Psr\Container\NotFoundExceptionInterface;

final class NotFoundException extends \RuntimeException implements NotFoundExceptionInterface
{
}
