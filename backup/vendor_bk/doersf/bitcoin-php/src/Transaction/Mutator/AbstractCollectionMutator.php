<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Transaction\Mutator;

abstract class AbstractCollectionMutator implements \Iterator, \ArrayAccess, \Countable
{
    /**
     * @var \SplFixedArray
     */
    protected $set;
    protected $myIterator;
    
    /**
     * @return array
     */
    public function all(): array
    {
        return $this->set->toArray();
    }

    /**
     * @return bool
     */
    public function isNull(): bool
    {
        return count($this->set) === 0;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->set->count();
    }

    /**
     *
     */
    public function rewind(): void
    {
        $this->myIterator->rewind();
    }

    /**
     * @return mixed
     */
    public function current(): mixed
    {
        return $this->myIterator->current();
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->myIterator->key();
    }

    /**
     *
     */
    public function next(): void
    {
        $this->myIterator->next();
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return $this->myIterator->valid();
    }

    /**
     * @param int $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $this->set->offsetExists($offset);
    }

    /**
     * @param int $offset
     */
    public function offsetUnset($offset): void
    {
        if (!$this->offsetExists($offset)) {
            throw new \InvalidArgumentException('Offset does not exist');
        }

        $this->set->offsetUnset($offset);
    }

    /**
     * @param int $offset
     * @return mixed
     */
    public function offsetGet($offset): mixed
    {
        if (!$this->set->offsetExists($offset)) {
            throw new \OutOfRangeException('Nothing found at this offset');
        }
        return $this->set->offsetGet($offset);
    }

    /**
     * @param int $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        $this->set->offsetSet($offset, $value);
    }
}
