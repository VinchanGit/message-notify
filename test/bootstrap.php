<?php

declare(strict_types=1);
/**
 * Copyright (c) The Vinchan , Distributed under the software license
 */
use Hyperf\Context\ApplicationContext;
use Hyperf\Di\Container;
use Hyperf\Di\Definition\DefinitionSourceFactory;

! defined('BASE_PATH') && define('BASE_PATH', dirname(__DIR__, 1));

$container = new Container((new DefinitionSourceFactory(true))());

ApplicationContext::setContainer($container);
