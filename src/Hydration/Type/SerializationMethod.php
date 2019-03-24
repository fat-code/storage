<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration\Type;

use FatCode\Enum;

/**
 * @method static SerializationMethod SERIALIZE()
 * @method static SerializationMethod JSON()
 * @method static SerializationMethod NONE()
 */
class SerializationMethod extends Enum
{
    public const JSON = 'json';
    public const NONE = 'none';
    public const SERIALIZE = 'serialize';
}
