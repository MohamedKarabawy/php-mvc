<?php

namespace App\Routes;

use Core\Router\Route;
use App\Controllers\ContactController;
use App\Controllers\AboutController;
use App\Controllers\ServicesController;
use App\Controllers\HomeController;
use App\Controllers\DocsController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/about', [AboutController::class, 'index']);

Route::get('/services', [ServicesController::class, 'index']);

Route::get('/contact', [ContactController::class, 'index']);

Route::get('/docs', [DocsController::class, 'introduction']);

Route::get('/docs/installation', [DocsController::class, 'installation']);

Route::get('/docs/architecture', [DocsController::class, 'architecture']);

Route::get('/docs/container', [DocsController::class, 'container']);

Route::get('/docs/environment', [DocsController::class, 'environment']);

Route::get('/docs/helpers', [DocsController::class, 'helpersDoc']);

Route::get('/docs/routing', [DocsController::class, 'routing']);

Route::get('/docs/requests', [DocsController::class, 'requests']);

Route::get('/docs/controllers', [DocsController::class, 'controllers']);

Route::get('/docs/database', [DocsController::class, 'database']);

Route::get('/docs/models', [DocsController::class, 'models']);

Route::get('/docs/migrations', [DocsController::class, 'migrations']);

Route::get('/docs/views', [DocsController::class, 'views']);
