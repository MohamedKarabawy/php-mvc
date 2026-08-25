<?php

// Get env values
if (!function_exists('env'))
{
    function env(string $key, mixed $default = null): mixed
    {
        $value = getenv($key);

        if ($value === false) 
        {
            return $default;
        }

        return $value;
    }
}

class View
{
    private string $viewName;
    private array $data = [];
    private string $viewsPath;
    private ?string $layout = null;
    private array $sections = [];

    public function __construct(string $viewName)
    {
        // Get and store view name
        $this->viewName = $viewName;
        /// Get and store view path
        $this->viewsPath = dirname(__DIR__) . '/App/Views/';
    }

    // Static factory method to create and return a new class instance to enable method chaining
    public static function make(string $viewName): self
    {
        return new self($viewName);
    }

    // Assign a variable to pass into the view and returns the object to enable continuous method chaining
    public function with(string $key, mixed $value): self
    {
        $this->data[$key] = $value;
        return $this;
    }

    private function resolvePath(string $name): string
    {
        $path = realpath($this->viewsPath . $name . '.view.php');

        if (!$path || !str_starts_with($path, realpath($this->viewsPath))) 
        {
            throw new Exception("View file [{$name}.view.php] not found or unauthorized.");
        }
        return $path;
    }

    public function render(): string
    {
        $viewPath = $this->resolvePath($this->viewName);

        extract($this->data);

        ob_start();
        include $viewPath;
        $childContent = ob_get_clean();

        $this->parseDirectives($childContent);

        if ($this->layout) 
        {
            $layoutPath = $this->resolvePath($this->layout);
            ob_start();
            include $layoutPath;
            return ob_get_clean();
        }

        return $childContent;
    }

    // Extract layout and section tags from the template using regular expression and store them in the class properties
    private function parseDirectives(string $content): void
    {
        if (preg_match('/@extends\([\'"](.+?)[\'"]\)/', $content, $matches)) 
        {
            $this->layout = $matches[1];
        }

        $pattern = '/@section\([\'"](.+?)[\'"]\)(.*?)@endsection/s';

        if (preg_match_all($pattern, $content, $matches, PREG_SET_ORDER)) 
        {
            foreach ($matches as $match) 
            {
                $this->sections[$match[1]] = trim($match[2]);
            }
        }
    }

    public function yield(string $sectionName): void
    {
        echo $this->sections[$sectionName] ?? '';
    }

    public function __toString(): string
    {
        try 
        {
            return $this->render();
        } 
        catch (Exception $e) 
        {
            return $e->getMessage();
        }
    }
}

function view(string $viewName): View
{
    return View::make($viewName);
}