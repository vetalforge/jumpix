<?php

namespace Jumpix\Core\Database;

class QueryBuilder
{
    private string $table;
    private string $columns = '*';
    protected array $where = [];
    protected array $bindings = [];
    protected ?string $orderBy = null;
    protected ?int $limit = null;
    protected ?int $offset = null;

    public function __construct(
        private \PDO $connection
    ) {
    }

    public function setTable(string $table): void
    {
        $this->table = $table;
    }

    public function select(string $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    public function where(string $column, string $operator, mixed $value): self
    {
        $allowedOperators = ['=', '!=', '>', '<', '>=', '<=', 'LIKE'];

        $operator = strtoupper($operator);

        if (!in_array($operator, $allowedOperators, true)) {
            throw new \InvalidArgumentException(
                "Invalid operator: {$operator}"
            );
        }

        $this->where[] = "$column $operator ?";
        $this->bindings[] = $value;

        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orderBy = "$column $direction";

        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    public function get(): array
    {
        $sql = $this->buildSelect();

        $result = $this->fetch($sql, $this->bindings);

        $this->reset();

        return $result;
    }

    public function first(): ?array
    {
        $this->limit(1);

        $result = $this->get();

        return $result[0] ?? null;
    }

    public function insert(array $data): bool
    {
        if (empty($data)) {
            throw new \InvalidArgumentException('Insert data cannot be empty.');
        }

        $columns = implode(',', array_keys($data));
        $placeholders = implode(',', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";

        $result = $this->execute($sql, array_values($data));

        $this->reset();

        return $result;
    }

    public function update(array $data): bool
    {
        if (empty($data)) {
            throw new \InvalidArgumentException('Update data cannot be empty.');
        }

        if (empty($this->where)) {
            throw new \LogicException('Update queries require at least one where condition.');
        }

        $set = implode('=?, ', array_keys($data)) . '=?';

        $sql = "UPDATE {$this->table} SET $set";

        $sql .= " WHERE " . implode(' AND ', $this->where);

        $bindings = array_merge(array_values($data), $this->bindings);

        $result = $this->execute($sql, $bindings);

        $this->reset();

        return $result;
    }

    public function delete(): bool
    {
        if (empty($this->where)) {
            throw new \LogicException('Delete queries require at least one where condition.');
        }

        $sql = "DELETE FROM {$this->table}";

        $sql .= " WHERE " . implode(' AND ', $this->where);

        $result = $this->execute($sql, $this->bindings);

        $this->reset();

        return $result;
    }

    protected function buildSelect(): string
    {
        $sql = "SELECT {$this->columns} FROM {$this->table}";

        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(' AND ', $this->where);
        }

        if ($this->orderBy) {
            $sql .= " ORDER BY {$this->orderBy}";
        }

        if ($this->limit !== null) {
            $sql .= " LIMIT {$this->limit}";
        }

        if ($this->offset !== null) {
            $sql .= " OFFSET {$this->offset}";
        }

        return $sql;
    }

    public function executeRaw(string $sql, array $bindings = []): array
    {
        return $this->fetch($sql, $bindings);
    }

    private function fetch(string $query, array $bindings = []): array
    {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($bindings);

            return $stmt->fetchAll($this->connection::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }

    private function execute(string $query, array $bindings = []): bool
    {
        try {
            $stmt = $this->connection->prepare($query);

            return $stmt->execute($bindings);
        } catch (\PDOException $e) {
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }

    public function reset(): self
    {
        $this->columns = '*';
        $this->where = [];
        $this->bindings = [];
        $this->orderBy = null;
        $this->limit = null;
        $this->offset = null;

        return $this;
    }
}


