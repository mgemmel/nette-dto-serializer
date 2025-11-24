<?php
declare(strict_types=1);

namespace NetteDtoSerializer\Serializer;


final class Collection
{
    /** @var array<object> */
    private array $items;

    /**
     * @param Item[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @return Item[]
     */
    public function all(): array
    {
        return $this->items;
    }
}
