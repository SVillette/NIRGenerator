<?php

declare(strict_types=1);

namespace App\Domain\Nir\Validation\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class Nir extends Constraint
{
    final public const INVALID_ERROR = '4e81f0b0-18f6-49c1-9811-634ed36fa9a3';
    final public const LENGTH_ERROR = 'c2445638-3287-4978-8411-695e2b0cd55c';
    final public const INVALID_KEY_ERROR = 'eaf8ec96-1284-435c-90b8-86e89c76dc0d';

    public string $message = 'The NIR is not valid';

    public function __construct(array $options = null, string $message = null, array $groups = null, mixed $payload = null)
    {
        parent::__construct($options ?? [], $groups, $payload);

        $this->message = $message ?? $this->message;
    }
}
