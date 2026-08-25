<?php

namespace App\Controllers;

class DocsController extends Controller
{
    public function __consturctor()
    {
        
    }

    private function nav(): array
    {
        return
        [
            [
                'label' => 'Getting Started',
                'items' =>
                [
                    ['key' => 'introduction', 'label' => 'Introduction', 'href' => '/'.basename(BASE_PATH).'/docs'],
                    ['key' => 'installation', 'label' => 'Installation', 'href' => '/'.basename(BASE_PATH).'/docs/installation'],
                    ['key' => 'architecture', 'label' => 'Architecture', 'href' => '/'.basename(BASE_PATH).'/docs/architecture'],
                ],
            ],
            [
                'label' => 'The Foundation',
                'items' =>
                [
                    ['key' => 'container', 'label' => 'Service Container', 'href' => '/'.basename(BASE_PATH).'/docs/container'],
                    ['key' => 'environment', 'label' => 'Environment', 'href' => '/'.basename(BASE_PATH).'/docs/environment'],
                    ['key' => 'helpers', 'label' => 'Helpers', 'href' => '/'.basename(BASE_PATH).'/docs/helpers'],
                ],
            ],
            [
                'label' => 'HTTP',
                'items' =>
                [
                    ['key' => 'routing', 'label' => 'Routing', 'href' => '/'.basename(BASE_PATH).'/docs/routing'],
                    ['key' => 'requests', 'label' => 'Requests', 'href' => '/'.basename(BASE_PATH).'/docs/requests'],
                    ['key' => 'controllers', 'label' => 'Controllers', 'href' => '/'.basename(BASE_PATH).'/docs/controllers'],
                ],
            ],
            [
                'label' => 'Database',
                'items' =>
                [
                    ['key' => 'database', 'label' => 'Database', 'href' => '/'.basename(BASE_PATH).'/docs/database'],
                    ['key' => 'models', 'label' => 'Models', 'href' => '/'.basename(BASE_PATH).'/docs/models'],
                    ['key' => 'migrations', 'label' => 'Migrations', 'href' => '/'.basename(BASE_PATH).'/docs/migrations'],
                ],
            ],
            [
                'label' => 'Views',
                'items' =>
                [
                    ['key' => 'views', 'label' => 'Views &amp; Templating', 'href' => '/'.basename(BASE_PATH).'/docs/views'],
                ],
            ],
        ];
    }

    private function page(string $view, string $active, string $chapter, string $title, ?array $prev = null, ?array $next = null)
    {
        $rendered = view('docs/' . $view)
            ->with('navGroups', $this->nav())
            ->with('active', $active)
            ->with('chapter', $chapter)
            ->with('title', $title);

        if ($prev)
        {
            $rendered->with('prev', $prev);
        }

        if ($next)
        {
            $rendered->with('next', $next);
        }

        return $rendered;
    }

    public function introduction()
    {
        return $this->page(
            'introduction',
            'introduction',
            'Getting Started',
            'Introduction',
            null,
            ['href' => '/'.basename(BASE_PATH).'/docs/installation', 'label' => 'Installation']
        );
    }

    public function installation()
    {
        return $this->page(
            'installation',
            'installation',
            'Getting Started',
            'Installation',
            ['href' => '/'.basename(BASE_PATH).'/docs', 'label' => 'Introduction'],
            ['href' => '/'.basename(BASE_PATH).'/docs/architecture', 'label' => 'Architecture']
        );
    }

    public function architecture()
    {
        return $this->page(
            'architecture',
            'architecture',
            'Getting Started',
            'Architecture',
            ['href' => '/'.basename(BASE_PATH).'/docs/installation', 'label' => 'Installation'],
            ['href' => '/'.basename(BASE_PATH).'/docs/container', 'label' => 'Service Container']
        );
    }

    public function container()
    {
        return $this->page(
            'container',
            'container',
            'The Foundation',
            'Service Container',
            ['href' => '/'.basename(BASE_PATH).'/docs/architecture', 'label' => 'Architecture'],
            ['href' => '/'.basename(BASE_PATH).'/docs/environment', 'label' => 'Environment']
        );
    }

    public function environment()
    {
        return $this->page(
            'environment',
            'environment',
            'The Foundation',
            'Environment',
            ['href' => '/'.basename(BASE_PATH).'/docs/container', 'label' => 'Service Container'],
            ['href' => '/'.basename(BASE_PATH).'/docs/helpers', 'label' => 'Helpers']
        );
    }

    public function helpersDoc()
    {
        return $this->page(
            'helpers',
            'helpers',
            'The Foundation',
            'Helpers',
            ['href' => '/'.basename(BASE_PATH).'/docs/environment', 'label' => 'Environment'],
            ['href' => '/'.basename(BASE_PATH).'/docs/routing', 'label' => 'Routing']
        );
    }

    public function routing()
    {
        return $this->page(
            'routing',
            'routing',
            'HTTP',
            'Routing',
            ['href' => '/'.basename(BASE_PATH).'/docs/helpers', 'label' => 'Helpers'],
            ['href' => '/'.basename(BASE_PATH).'/docs/requests', 'label' => 'Requests']
        );
    }

    public function requests()
    {
        return $this->page(
            'requests',
            'requests',
            'HTTP',
            'Requests',
            ['href' => '/'.basename(BASE_PATH).'/docs/routing', 'label' => 'Routing'],
            ['href' => '/'.basename(BASE_PATH).'/docs/controllers', 'label' => 'Controllers']
        );
    }

    public function controllers()
    {
        return $this->page(
            'controllers',
            'controllers',
            'HTTP',
            'Controllers',
            ['href' => '/'.basename(BASE_PATH).'/docs/requests', 'label' => 'Requests'],
            ['href' => '/'.basename(BASE_PATH).'/docs/database', 'label' => 'Database']
        );
    }

    public function database()
    {
        return $this->page(
            'database',
            'database',
            'Database',
            'Database',
            ['href' => '/'.basename(BASE_PATH).'/docs/controllers', 'label' => 'Controllers'],
            ['href' => '/'.basename(BASE_PATH).'/docs/models', 'label' => 'Models']
        );
    }

    public function models()
    {
        return $this->page(
            'models',
            'models',
            'Database',
            'Models',
            ['href' => '/'.basename(BASE_PATH).'/docs/database', 'label' => 'Database'],
            ['href' => '/'.basename(BASE_PATH).'/docs/migrations', 'label' => 'Migrations']
        );
    }

    public function migrations()
    {
        return $this->page(
            'migrations',
            'migrations',
            'Database',
            'Migrations',
            ['href' => '/'.basename(BASE_PATH).'/docs/models', 'label' => 'Models'],
            ['href' => '/'.basename(BASE_PATH).'/docs/views', 'label' => 'Views & Templating']
        );
    }

    public function views()
    {
        return $this->page(
            'views',
            'views',
            'Views',
            'Views & Templating',
            ['href' => '/'.basename(BASE_PATH).'/docs/migrations', 'label' => 'Migrations'],
            null
        );
    }
}
