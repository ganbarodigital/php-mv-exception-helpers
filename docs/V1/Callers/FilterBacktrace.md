---
currentSection: v1
currentItem: callers
pageflow_prev_url: CodeCaller.html
pageflow_prev_text: CodeCaller class
pageflow_next_url: FilterBacktraceForTwoCodeCallers.html
pageflow_next_text: FilterBacktraceForTwoCodeCallers class
---

# FilterBacktrace

<div class="callout info" markdown="1">
Since v1.2016041701
</div>

## Description

`FilterBacktrace` is a data filter. You give it the output from `debug_backtrace()`, and a list of PHP classnames or partial namespaces to filter, and it will return the first entry in the debug trace that passes the filter.

`FilterBacktrace` was introduced to separate out the low-level filtering code from `FilterCodeCaller`.

## Public Interface

`FilterBacktrace` has the following public interface:

```php
use GanbaroDigital\ExceptionHelpers\V1\Callers\Filters\FilterBacktrace;

class FilterBacktrace
{
    /**
     * work out who has called a piece of code
     *
     * @param  array $backtrace
     *         the debug_backtrace() return value
     * @param  array $partialsToFilterOut
     *         a list of partial namespaces to skip over
     * @param  int $index
     *         how far down the stack do we want to start looking from?
     * @return array
     */
    public function __invoke($backtrace, $partialsToFilterOut = [], $index = 0);

    /**
     * work out who has called a piece of code
     *
     * @param  array $backtrace
     *         the debug_backtrace() return value
     * @param  array $partialsToFilterOut
     *         a list of partial namespaces to skip over
     * @param  int $index
     *         how far down the stack do we want to start looking from?
     * @return array
     */
    public static function from($backtrace, $partialsToFilterOut = [], $index = 0);
}
```

## How To Use

### Finding A Single Stack Frame

`FilterBacktrace` can be used as an object, or as a static class method.

```php
// import first
use GanbaroDigital\ExceptionHelpers\V1\Callers\Filters\FilterBacktrace;

// we need a stack trace
// use DEBUG_BACKTRACE_IGNORE_ARGS to keep the trace very small
$trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

// we need a list of namespaces to filter out
//
// this can be individual parts of namespaces, or whole classnames
$partials = [
    'Checks',
    'Requirements',
    FilterBacktrace::class,
];

// to use as an object
$filter = new FilterBacktrace;
$caller = $filter($trace, $partials);

// to call statically
$caller = FilterBacktrace::from($trace, $partials);
```

### Finding Multiple Stack Frames

`debug_backtrace()` returns the stack trace as an array. You can start the search from anywhere in this array by passing a third parameter into this filter. This is especially useful if your exception wants to know who called the code that is trying to throw an exception:

```php
use GanbaroDigital\ExceptionHelpers\V1\Callers\Filters\FilterBacktrace;

// we need a stack trace
// use DEBUG_BACKTRACE_IGNORE_ARGS to keep the trace very small
$trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

// starts from the beginning of the stack trace
$caller1 = FilterBacktrace::from($trace);
// starts from just after where we found $caller1
$caller2 = FilterBacktrace::from($trace, [], $caller1['stackIndex'] + 1);
```

If you attempt to search beyond the end of the stack trace, `FilterBacktrace` will return the last stack frame.

## Return Value

`FilterBacktrace` returns an array:

Key | Description
----|------------
class | A valid PHP class name, or NULL
function | The method on the class that called us, or NULL
file | The file were the PHP code was defined, or NULL
line | The line number in the file where the PHP code was defined, or NULL
stackIndex | Where did we find these details in the backtrace array?

You should expect some of the fields in the return array to be `NULL`:

* Any entry in the returned array can be `NULL`.
* One of `class`, `class` and `function`, or `file` will have a value.

## Notes

None at this time.

## See Also

* [`FilterCodeCaller` class](FilterCodeCaller.html)
