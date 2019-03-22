<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration\Type;

class IdType implements NamedType
{
    /**
     * @var string
     */
    private $localName;

    /**
     * @var string
     */
    private $externalName;

    public function __construct(string $localName = 'id', string $externalName = '_id')
    {
        $this->localName = $localName;
        $this->externalName = $externalName;
    }

    public function hydrate($value)
    {
        return $value;
    }

    public function extract($value)
    {
        return $value;
    }

    public function getLocalName(): string
    {
        return $this->localName;
    }

    public function getExternalName(): string
    {
        return $this->externalName;
    }
}
