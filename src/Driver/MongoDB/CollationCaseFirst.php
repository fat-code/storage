<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDB;

use FatCode\Storage\Enum;

class CollationCaseFirst extends Enum
{
    /**
     * Uppercase sorts before lowercase.
     */
    public const UPPER = 'upper';

    /**
     * Lowercase sorts before uppercase.
     */
    public const LOWER = 'lower';

    /**
     * Default value. Similar to "lower" with slight differences.
     * @see http://userguide.icu-project.org/collation/customization
     */
    public const OFF = 'off';
}
