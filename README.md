<div align="center">

<img src=".github/banner.svg" alt="PHP MVC — v1.0.0" width="100%">

<br>

![PHP](https://img.shields.io/badge/PHP-8.0%2B-8d94f4?style=flat-square&labelColor=0d0f16)
![Dependencies](https://img.shields.io/badge/dependencies-none-e6b877?style=flat-square&labelColor=0d0f16)
![Status](https://img.shields.io/badge/status-educational-6b73ec?style=flat-square&labelColor=0d0f16)
![License](https://img.shields.io/badge/license-MIT-8d94f4?style=flat-square&labelColor=0d0f16)

**A minimal Model–View–Controller framework, written in plain PHP so you can read every line of the container, the router and the ORM — and actually understand what a framework does underneath.**

[Read the full docs](#documentation) · [Quick start](#quick-start) · [Project structure](#project-structure)

</div>

<br>

> [!NOTE]
> This framework is for learning/teaching purposes only, to make the developer be able to know how MVC works and the underlying of MVC frameworks.

<br>

## Why this exists

Most frameworks hide their machinery behind convenience. **PHP MVC** does the opposite — every piece is a single, short, readable file: a reflection-based container, a flat router, a PDO-backed model, and a Blade-inspired view engine. Nothing is compiled, cached, or generated. If you can read PHP, you can read the whole framework in an afternoon.

<br>

## Features

| | |
|---|---|
| **Service Container** | Reflection-based dependency injection. Type-hint a constructor parameter and it's auto-wired — no config, no service providers. |
| **Router** | A flat, explicit `Route::get()` / `Route::post()` facade. No hidden matching, no compiled route cache. |
| **Model** | A small active-record base class over PDO — `where()`, `get()`, `find()`, and `hasOne` / `hasMany` / `belongsTo` / `belongsToMany` relationships. |
| **View** | A single-file templating layer supporting `@extends` and `@section` / `@endsection`, resolved with path-traversal protection. |
| **Migrations** | Plain `.sql` files run through a small CLI (`Core/migrate.php migrate` / `drop`) — no ORM-specific migration DSL to learn. |
| **Request** | A thin wrapper around `$_GET` / `$_POST` with basic input sanitisation. |

<br>

## Quick start

```bash
git clone https://github.com/your-username/php-mvc.git
cd php-mvc
```

Create your `.env` file:

```env
DB_HOST=localhost
DB_NAME=phpmvc
DB_USER=root
DB_PASS=
```

Run the migrations:

```bash
php Core/migrate.php migrate
```

Serve the app:

```bash
php -S localhost:8000 -t public
```

Then open `http://localhost:8000` for the landing page, or `http://localhost:8000/docs` for the full in-app documentation.

<br>

## Project structure

```text
project-root/
├─ Core/                  framework internals — container, router, model, view engine
│  ├─ Http/Request.php
│  ├─ Kernel/App.php
│  ├─ Router/Route.php
│  ├─ Container.php
│  ├─ Database.php
│  ├─ Env.php
│  ├─ Model.php
│  ├─ helpers.php
│  └─ migrate.php
├─ app/Routes/routes.php  your route definitions
├─ App/
│  ├─ Controllers/        your controllers
│  ├─ Models/              your models
│  └─ Views/               your .view.php templates + docs/
├─ App/Migrations/
│  ├─ up/                  CREATE TABLE ... .sql files
│  └─ down/                 DROP TABLE ... .sql files
├─ public/
│  ├─ index.php            single front controller
│  └─ css/                  docs.css, home.css
└─ .env
```

<br>

## Documentation

Full, in-app documentation covering every piece of `Core/` — the service container, routing, requests, the database layer, models & relationships, migrations, and the view/templating engine — ships with the app itself and renders in the same dark theme as this README:

| | |
|---|---|
| [Introduction](App/Views/docs/introduction.view.php) | What's included and how the pieces fit together |
| [Installation](App/Views/docs/installation.view.php) | Requirements, project layout, first boot |
| [Architecture](App/Views/docs/architecture.view.php) | The full request lifecycle, end to end |
| [Service Container](App/Views/docs/container.view.php) | Binding, singletons, reflection-based auto-wiring |
| [Environment](App/Views/docs/environment.view.php) | `.env` loading and the `env()` helper |
| [Helpers](App/Views/docs/helpers.view.php) | The global `env()` and `view()` functions |
| [Routing](App/Views/docs/routing.view.php) | `Route::get()` / `Route::post()` and how matches are decided |
| [Requests](App/Views/docs/requests.view.php) | Reading query/body input safely |
| [Controllers](App/Views/docs/controllers.view.php) | Structure and constructor injection |
| [Database](App/Views/docs/database.view.php) | The PDO singleton and raw queries |
| [Models](App/Views/docs/models.view.php) | Active-record queries and relationships |
| [Migrations](App/Views/docs/migrations.view.php) | Running and authoring `.sql` migrations |
| [Views & Templating](App/Views/docs/views.view.php) | `@extends`, `@section`, layouts and `yield()` |

Once the app is running, browse the same content live starting at **`/docs`**.

<br>

## License

Released under the MIT License. Built for learning — fork it, break it, read it end to end.
