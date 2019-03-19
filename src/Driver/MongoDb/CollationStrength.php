<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb;

use FatCode\Storage\Enum;

/**
 * @see http://userguide.icu-project.org/collation/concepts#TOC-Comparison-Levels
 */
class CollationStrength extends Enum
{
    /**
     * Primary level of comparison.
     * Collation performs comparisons of the base characters only,
     * ignoring other differences such as diacritics and case.
     */
    public const LEVEL_1 = 1;

    /**
     * Secondary level of comparison.
     * Collation performs comparisons up to secondary differences, such as diacritics.
     * That is, collation performs comparisons of base characters (primary differences)
     * and diacritics (secondary differences). Differences between base characters takes
     * precedence over secondary differences.
     */
    public const LEVEL_2 = 2;

    /**
     * Tertiary level of comparison. (This is the default level)
     * Collation performs comparisons up to tertiary differences, such as case and letter variants.
     * That is, collation performs comparisons of base characters (primary differences),
     * diacritics (secondary differences), and case and variants (tertiary differences).
     * Differences between base characters takes precedence over secondary differences,
     * which takes precedence over tertiary differences.
     */
    public const LEVEL_3 = 3;

    /**
     * Quaternary Level. Limited for specific use case to consider punctuation when levels 1-3 ignore punctuation
     * or for processing Japanese text.
     */
    public const LEVEL_4 = 4;

    /**
     * Identical Level. Limited for specific use case of tie breaker.
     */
    public const LEVEL_5 = 5;
}
