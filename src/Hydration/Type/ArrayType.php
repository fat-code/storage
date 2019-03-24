<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration\Type;

class ArrayType implements Type
{
    private $serialization;

    public function __construct(SerializationMethod $serialization = null)
    {
        $this->serialization = $serialization ? $serialization->getValue() : SerializationMethod::NONE;
    }

    public function hydrate($value) : array
    {
        switch ($this->serialization) {
            case SerializationMethod::JSON:
                return json_decode($value, true);
            case SerializationMethod::SERIALIZE:
                return unserialize($value, ['allowed_classes' => false]);
            case SerializationMethod::NONE:
            default:
                return $value;
        }
    }

    public function extract($value)
    {
        switch ($this->serialization) {
            case SerializationMethod::JSON:
                return json_encode($value);
            case SerializationMethod::SERIALIZE:
                return serialize($value);
            case SerializationMethod::NONE:
            default:
                return $value;
        }
    }
}
