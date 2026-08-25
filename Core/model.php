<?php

namespace Core;

abstract class Model 
{
    protected ?string $table = null;
    protected string $primaryKey = 'id';
    protected array $attributes = [];
    protected array $whereClauses = [];
    protected array $whereBindings = [];

    public function __construct(array $attributes = []) 
    {
        // Store the model attributes retrieved from the database
        $this->attributes = $attributes;

        // Automatically determine the table name from the model class when no table is explicitly defined
        if (!$this->table) 
        {
            $this->table = strtolower(basename(str_replace('\\', '/', get_class($this))));
        }
    }

    public function __get(string $key) 
    {
        // Return the requested attribute when it exists in the model data
        if (array_key_exists($key, $this->attributes)) 
        {
            return $this->attributes[$key];
        }

        // Resolve the requested key as a model relationship method when a matching method exists
        if (method_exists($this, $key)) 
        {
            return $this->$key();
        }

        // Return null when the requested attribute or relationship does not exist
        return null;
    }

    public static function query(): static 
    {
        // Create a new model instance to start building a query
        return new static();
    }

    public function where(string $column, mixed $value): self 
    {
        // Add a parameterized condition to the current query
        $this->whereClauses[] = "{$column} = ?";

        // Store the value separately so it can be safely bound to the query
        $this->whereBindings[] = $value;

        // Return the current model instance to allow method chaining
        return $this;
    }

    public function get(): array 
    {
        // Get the shared database connection
        $db = Database::connect();

        // Build the base SELECT query using the model's table
        $sql = "SELECT * FROM {$this->table}";
        
        // Append the WHERE conditions when query constraints have been added
        if (!empty($this->whereClauses)) 
        {
            $sql .= " WHERE " . implode(' AND ', $this->whereClauses);
        }

        // Prepare the generated query before executing it
        $stmt = $db->prepare($sql);

        // Execute the query using the stored parameter bindings
        $stmt->execute($this->whereBindings);
        
        $results = [];

        // Fetch each database row and convert it into a model instance
        while ($row = $stmt->fetch()) 
        {
            $results[] = new static($row);
        }

        return $results;
    }

    public static function find(mixed $id): ?static 
    {
        // Create a model instance to determine the table and primary key
        $instance = new static();

        // Get the shared database connection
        $db = Database::connect();

        // Prepare a query to find a single record using the primary key
        $stmt = $db->prepare("SELECT * FROM {$instance->table} WHERE {$instance->primaryKey} = ? LIMIT 1");

        // Execute the query using the provided ID as a parameter
        $stmt->execute([$id]);

        // Fetch the matching database row
        $row = $stmt->fetch();

        // Return the matching model instance or null when no record is found
        return $row ? new static($row) : null;
    }

    protected function hasOne(string $relatedModel, string $foreignKey): ?Model 
    {
        // Query the related model using the current model's primary key
        return $relatedModel::query()->where($foreignKey, $this->attributes[$this->primaryKey])->get()[0] ?? null;
    }

    protected function hasMany(string $relatedModel, string $foreignKey): array 
    {
        // Query all related records using the current model's primary key
        return $relatedModel::query()->where($foreignKey, $this->attributes[$this->primaryKey])->get();
    }

    protected function belongsTo(string $relatedModel, string $foreignKey): ?Model 
    {
        // Create the related model instance before resolving the related record
        new $relatedModel();

        // Find the related model using the foreign key stored on the current model
        return $relatedModel::find($this->attributes[$foreignKey]);
    }

    protected function belongsToMany(string $relatedModel, string $pivotTable, string $foreignPivotKey, string $relatedPivotKey): array 
    {
        // Get the shared database connection
        $db = Database::connect();

        // Create an instance of the related model to determine its table and primary key
        $relatedInstance = new $relatedModel();
        
        // Build the query joining the related model with the pivot table
        $sql = "SELECT r.* FROM {$relatedInstance->table} r 
                JOIN {$pivotTable} p ON r.{$relatedInstance->primaryKey} = p.{$relatedPivotKey}
                WHERE p.{$foreignPivotKey} = ?";
        
        // Prepare the generated relationship query
        $stmt = $db->prepare($sql);

        // Execute the query using the current model's primary key
        $stmt->execute([$this->attributes[$this->primaryKey]]);
        
        $results = [];

        // Fetch each related record and convert it into a model instance
        while ($row = $stmt->fetch()) 
        {
            $results[] = new $relatedModel($row);
        }
        return $results;
    }
}
