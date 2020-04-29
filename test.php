<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;

$filesystem = new Filesystem();
$container = new Container();
$eventDispatcher = new Dispatcher($container);
$viewResolver = new EngineResolver();
$bladeCompiler = new BladeCompiler($filesystem, __DIR__ . '/compiled_views');

$viewResolver->register('blade', function () use ($bladeCompiler) {
  return new CompilerEngine($bladeCompiler);
});

$viewResolver->register('php', function () {
  return new PhpEngine();
});

$viewFinder = new FileViewFinder($filesystem, [__DIR__  . '/test_views']);
$viewFactory = new Factory($viewResolver, $viewFinder, $eventDispatcher);

echo $viewFactory->make('test')->render();
