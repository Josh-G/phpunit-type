<?php declare(strict_types=1);

namespace SebastianBergmann\Type;

/**
 * Nasty backport of UnionType to support union types under PHPUnit 8.5
 */
final class UnionType extends Type
{
    /**
     * @param array<Type> $types
     */
    public function __construct(
        private array $types,
    ) {}

    public function isAssignable(Type $other): bool
    {
        foreach ($this->types as $type) {
            if ($type->isAssignable($other)) {
                return true;
            }
        }
        return false;
    }

    public function getReturnTypeDeclaration(): string
    {
        $typeNames = array_map(
            fn(Type $type) => $type instanceof NullType ? 'null' : ltrim($type->getReturnTypeDeclaration(), ': '),
            $this->types
        );

        return ': ' . implode('|', $typeNames);
    }

    public function allowsNull(): bool
    {
        foreach ($this->types as $type) {
            if ($type instanceof NullType) {
                return true;
            }
        }
        return false;
    }
}
