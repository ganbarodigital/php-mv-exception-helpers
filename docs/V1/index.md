---
currentSection: v1
currentItem: home
pageflow_next_url: BaseExceptions/index.html
pageflow_next_text: Base Exceptions
---

# Version 1.x

## Introduction

Version 1 was written to replace `ganbarodigital/php-exceptions`. That's a library based on an approach to exception handling that Stuart first introduced on the __php|cruise__ back in 2004. It had a good run, but after 12 years we can now do better :)

## Key Ideas

The key ideas in Version 1 are:

* Provide base exceptions for other libraries to use. Previously, Stuart provided traits. PHP doesn't see traits as a type hint. This limits what you can do in `try/catch` blocks.
* Use `newFromXXX()` style factory methods on exceptions. They allow us to create the same exception from different circumstances. Some people also find them more friendly to use.
* Use [HttpStatus value objects](http://ganbarodigital.github.io/php-http-status/HttpStatus.html) to map exceptions to HTTP status codes.  Previously, Stuart was using the exception's `code` property for this. That's fine in most cases, as most exceptions typically do not use the `code` property. This way makes it crystal clear which HTTP status an exception maps onto, and it frees up the `code` property just in case anyone does need to use it for their own error codes.

## Components

Version 1 ships with the following components:

Namespace | Purpose
----------|--------
[`GanbaroDigital\ExceptionHelpers\V1\BaseExceptions`](BaseExceptions/index.html) | base classes to build your own exceptions from
[`GanbaroDigital\ExceptionHelpers\V1\Callers`](Callers/index.html) | work out who has thrown an exception
[`GanbaroDigital\ExceptionHelpers\V1\ParameterBuilders`](ParameterBuilders/index.html) | helpers for building [`ParameterisedException`](BaseExceptions/ParameterisedException.html)s with a standard data structure

Click on the namespace to learn more about the classes in that component.
