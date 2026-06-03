<?php

namespace Jumpix\Core\ORM\Native;

use Jumpix\Core\ORM\RepositoryInterface;
use Jumpix\Models\Model;

final class NativeRepository implements RepositoryInterface
{
    private string $modelClass;

    public function __construct(string $modelClass)
    {
        if (!is_subclass_of($modelClass, Model::class)) {
            throw new \InvalidArgumentException(
                "{$modelClass} must extend " . Model::class
            );
        }

        $this->modelClass = $modelClass;
    }

    public function find($id)
    {
        return $this->modelClass::find($id);
    }

    public function all(): array
    {
        return $this->modelClass::all();
    }

    public function save($entity): void
    {
        $this->assertNativeModel($entity);
        $entity->save();
    }

    public function delete($entity): void
    {
        $this->assertNativeModel($entity);
        $entity->delete();
    }

    private function assertNativeModel($entity): void
    {
        if (!$entity instanceof $this->modelClass) {
            throw new \InvalidArgumentException(
                'Repository expects instance of ' . $this->modelClass
            );
        }
    }
}


