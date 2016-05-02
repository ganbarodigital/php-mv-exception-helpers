---
currentSection: v1
currentItem: exceptions
pageflow_prev_url: index.html
pageflow_prev_text: Exceptions List
---

# NoBuilderForException

<div class="callout warning" markdown="1">
Not yet in a tagged release
</div>

## Description

`NoBuilderForException` is an exception. It is thrown when we do not know how to build an exception.

## Public Interface

`NoBuilderForException` has the following public interface:

```php
// NoBuilderForException lives in this namespace
namespace GanbaroDigital\ExceptionHelpers\V1\Exceptions;

// our base classes and interfaces
use GanbaroDigital\ExceptionHelpers\V1\BaseExceptions\ParameterisedException;
use GanbaroDigital\ExceptionHelpers\V1\Exceptions\ExceptionHelpersException;
use GanbaroDigital\HttpStatus\Interfaces\HttpRuntimeErrorException;

// return type(s) for our methods
use GanbaroDigital\HttpStatus\StatusValues\RuntimeError\UnexpectedErrorStatus;

class NoBuilderForException
  extends ParameterisedException
  implements ExceptionHelpersException, HttpRuntimeErrorException
{
    // adds 'getHttpStatus()' that returns a HTTP 500 status value object
    use UnexpectedErrorStatusProvider;

    /**
     * create a new exception
     *
     * @param  mixed $exceptionName
     *         the name of the exception that we could not find
     * @param  array|null $callerFilter
     *         are there any namespaces we want to filter out of the call stack?
     * @return NoBuilderForException
     *         an fully-built exception for you to throw
     */
    public static function newFromExceptionName($exceptionName, $callerFilter = null);

    /**
     * what was the data that we used to create the printable message?
     *
     * @return array
     */
    public function getMessageData();

    /**
     * what was the format string we used to create the printable message?
     *
     * @return string
     */
    public function getMessageFormat();

    /**
     * which HTTP status code do we map onto?
     *
     * @return UnexpectedErrorStatus
     */
    public function getHttpStatus();
}
```

## How To Use

### Creating Exceptions To Throw

Call `NoBuilderForException::newFromExceptionName()` to create a new exception that you can throw:

```php
use GanbaroDigital\ExceptionHelpers\V1\Exceptions\NoBuilderForException;

throw NoBuilderForException::newFromExceptionName("trout");
```

### Catching The Exception

`NoBuilderForException` implements a rich set of classes and interfaces. You can use any of these to catch this exception.

```php
// example 1: we catch only NoBuilderForException exceptions
use GanbaroDigital\ExceptionHelpers\V1\Exceptions\NoBuilderForException

try {
    throw NoBuilderForException::newFromExceptionName("trout");
}
catch(NoBuilderForException $e) {
    // ...
}
```

```php
// example 2: catch all exceptions thrown by the Exception Helpers Library
use GanbaroDigital\ExceptionHelpers\V1\Exceptions\NoBuilderForException
use GanbaroDigital\ExceptionHelpers\V1\Exceptions\ExceptionHelpersException;

try {
    throw NoBuilderForException::newFromExceptionName("trout");
}
catch(ExceptionHelpersException $e) {
    // ...
}
```

```php
// example 3: catch all exceptions where something went wrong that
// should never happen
use GanbaroDigital\ExceptionHelpers\V1\Exceptions\NoBuilderForException
use GanbaroDigital\HttpStatus\Interfaces\HttpRuntimeErrorException;

try {
    throw NoBuilderForException::newFromExceptionName("trout");
}
catch(HttpRuntimeErrorException $e) {
    $httpStatus = $e->getHttpStatus();
    // ...
}
```

```php
// example 4: catch all exceptions that map onto a HTTP status
use GanbaroDigital\ExceptionHelpers\V1\Exceptions\NoBuilderForException
use GanbaroDigital\HttpStatus\Interfaces\HttpException;

try {
    throw NoBuilderForException::newFromExceptionName("trout");
}
catch(HttpException $e) {
    $httpStatus = $e->getHttpStatus();
    // ...
}
```

```php
// example 5: catch all runtime exceptions
use GanbaroDigital\ExceptionHelpers\V1\Exceptions\NoBuilderForException
use RuntimeException;

try {
    throw NoBuilderForException::newFromExceptionName("trout");
}
catch(RuntimeException $e) {
    // ...
}
```

## Class Contract

Here is the contract for this class:

    GanbaroDigital\ExceptionHelpers\V1\Exceptions\NoBuilderForException
     [x] Can instantiate
     [x] Is parameterised exception
     [x] Is runtime exception
     [x] Is http runtime error exception
     [x] Maps to unexpected error status
     [x] Can build from exception name
     [x] Can pass caller filter into build from exception name
     [x] Build from exception name will use default caller filter if no filter provided

 Class contracts are built from this class's unit tests.

 <div class="callout success">
 Future releases of this class will not break this contract.
 </div>

 <div class="callout info" markdown="1">
 Future releases of this class may add to this contract. New additions may include:

 * clarifying existing behaviour (e.g. stricter contract around input or return types)
 * add new behaviours (e.g. extra class methods)
 </div>

 <div class="callout warning" markdown="1">
 When you use this class, you can only rely on the behaviours documented by this contract.

 If you:

 * find other ways to use this class,
 * or depend on behaviours that are not covered by a unit test,
 * or depend on undocumented internal states of this class,

 ... your code may not work in the future.
 </div>

## Notes

None at this time.

## See Also

* [`ExceptionBuilders`](../ExceptionBuilders/ExceptionBuilders.html) - dependency injection container for exceptions
