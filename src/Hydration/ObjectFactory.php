<?php declare(strict_types=1);

namespace FatCode\Storage\Hydration;

final class ObjectFactory implements Hydrator
{
    use GenericHydrator;

    private $class;

    public function __construct(string $class, IdentityMap $identityMap = null)
    {
        $this->class = $class;
        $this->setIdentityMap($identityMap ?? new IdentityMap());
    }

    public function hydrate(array $input, object $object = null): object
    {
        return $this->hydrateObject($input, Instantiator::instantiate($this->class));
    }
}
