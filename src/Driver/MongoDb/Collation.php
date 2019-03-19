<?php declare(strict_types=1);

namespace FatCode\Storage\Driver\MongoDb;

class Collation
{
    private $locale;
    private $strength;
    private $caseLevel;
    private $caseFirst;

    public function __construct(string $locale, CollationStrength $strength = null)
    {
        $this->locale = $locale;
        $this->strength = $strength;
    }

    /**
     * Flag that determines sort order of case differences during tertiary level comparisons.
     * @param CollationCaseFirst $caseFirst
     * @return Collation
     */
    public function withCaseFirst(CollationCaseFirst $caseFirst): self
    {
        $new = clone $this;
        $new->caseFirst = $caseFirst->getValue();

        return $new;
    }

    /**
     * Flag that determines whether to include case comparison at strength level 1 or 2.
     * @param bool $level
     * @return Collation
     */
    public function withCaseLevel(bool $level): self
    {
        $new = clone $this;
        $new->caseLevel = $level;

        return $new;
    }

    public function apply(): array
    {
        $collation = [
            'locale' => $this->locale,
        ];

        if ($this->strength !== null) {
            $collation['strength'] = $this->strength->getValue();
        }

        if ($this->caseLevel !== null) {
            $collation['caseLevel'] = $this->caseLevel;
        }

        if ($this->caseFirst !== null) {
            $collation['caseFirst'] = $this->caseFirst;
        }


        return ['collation' => $collation];
    }
}
