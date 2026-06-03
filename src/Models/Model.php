<?php

namespace Jumpix\Models;

use Jumpix\Core\Database\QueryBuilder;

abstract class Model
{
    protected string $table;
    protected string $primaryKey = 'id';
    private static QueryBuilder $builder;

    public function __construct(
        private array $attributes = [],
        private bool $exists = false
    ) {
    }

    public static function setBuilder(QueryBuilder $builder): void
    {
        self::$builder = $builder;
    }

    protected function newQuery(): QueryBuilder
    {
        $builder = clone self::$builder;

        $builder->reset();
        $builder->setTable($this->table);

        return $builder;
    }

    public function __set(string $propertyName, mixed $value): void
    {
        $this->attributes[$propertyName] = $value;
    }

    public function __get(string $property): mixed
    {
        if (array_key_exists($property, $this->attributes)) {
            return $this->attributes[$property];
        }

        return null;
    }

    public function save(): bool
    {
        if ($this->exists) {
            if (!isset($this->attributes[$this->primaryKey])) {
                throw new \LogicException('Cannot update model without primary key.');
            }

            $data = $this->attributes;
            unset($data[$this->primaryKey]);

            return $this->newQuery()
                ->where($this->primaryKey, '=', $this->attributes[$this->primaryKey])
                ->update($data);
        }

        $result = $this->newQuery()->insert($this->attributes);

        if ($result) {
            $this->exists = true;
        }

        return $result;
    }

    public function delete(): bool
    {
        if (!isset($this->attributes[$this->primaryKey])) {
            throw new \LogicException('Cannot delete model without primary key.');
        }

        $result = $this->newQuery()
            ->where($this->primaryKey, '=', $this->attributes[$this->primaryKey])
            ->delete();

        if ($result) {
            $this->exists = false;
        }

        return $result;
    }

    public static function __callStatic(string $method, array $arguments)
    {
        if (in_array($method, ['find', 'query', 'all', 'create'])) {
            return (new static)->$method(...$arguments);
        }

        throw new \BadMethodCallException(
            "Method [$method] does not exist."
        );
    }

    protected function find(mixed $id): ?static
    {
        $data = $this->newQuery()
            ->where($this->primaryKey, '=', $id)
            ->first();

        return $data ? new static($data, true) : null;
    }

    protected function query(): QueryBuilder
    {
        return $this->newQuery();
    }

    protected function all(): array
    {
        return array_map(function ($item) {
            return new static($item, true);
        }, $this->newQuery()->get());
    }

    protected function create(array $attributes): static
    {
        $model = new static($attributes);

        $model->save();

        return $model;
    }
}


