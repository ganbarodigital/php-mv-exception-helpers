---
currentSection: v1
currentItem: home
pageflow_next_url: BaseExceptions.html
pageflow_next_text: Base Exceptions
---

# Version 1.x

## Introduction

Version 1 was written to replace `ganbarodigital/php-exceptions`. That's a library based on an approach to exception handling that Stuart first introduced on the __php|cruise__ back in 2004. It had a good run, but after 12 years we can now do better :)

## Key Ideas

The key ideas in Version 1 are:

* Use [HttpStatus value objects](http://ganbarodigital.github.io/php-http-status/HttpStatus.html) to map exceptions to HTTP status codes.  Previously, Stuart was using the exception's `code` property for this.
* Provide base exceptions for other libraries to use. Previously, Stuart provided traits. PHP doesn't see traits as a type hint. This limits what you can do in `try/catch` blocks.
* Use `newFromXXX()` style factory methods on exceptions. They allow us to create the same exception from different circumstances. Some people also find them more friendly to use.

## Components

Version 1 ships with the following components:

* [Type Inspectors](TypeInspectors.html) - reusable ways for an exception to understand the parameters that have been passed to it.
