---
currentSection: v1
currentItem: callers
pageflow_prev_url: FilterBacktrace.html
pageflow_prev_text: FilterBacktrace class
---

# FilterCodeCaller

<div class="callout warning" markdown="1">
Not yet in a tagged release.
</div>

## Description

`FilterCodeCaller` is a data filter. It is a wrapper around [`FilterBacktrace`](FilterBacktrace.html). It converts `FilterBacktrace`'s return value into a [`CodeCaller`](CodeCaller.html) value object.

`FilterCodeCaller` was introduced for use in the `UnsupportedType` exception.

## Public Interface

`FilterCodeCaller` has the following public interface:

```php
use GanbaroDigital\ExceptionHelpers\V1\Callers\Filters\FilterCodeCaller;
use GanbaroDigital\ExceptionHelpers\V1\Callers\Values\CodeCaller;

class FilterCodeCaller
{
    /**
     * recommended list of namespaces to filter from
     *
     * DO NOT CHANGE THIS ARRAY FROM YOUR CODE
     *
     * @var array
     */
    public static $DEFAULT_PARTIALS = [
        'Checks' => 'Checks',
        'Exceptions' => 'Exceptions',
        'Requirements' => 'Requirements',
    ];

    /**
     * work out who has called a piece of code
     *
     * @param  array $backtrace
     *         the debug_backtrace() return value
     * @param  array $partialsToFilterOut
     *         a list of partial namespaces to skip over
     * @return CodeCaller
     */
    public function __invoke($backtrace, $partialsToFilterOut = []);

    /**
     * work out who has called a piece of code
     *
     * @param  array $backtrace
     *         the debug_backtrace() return value
     * @param  array $partialsToFilterOut
     *         a list of partial namespaces to skip over
     * @return CodeCaller
     */
    public static function from($backtrace, $partialsToFilterOut = []);
}

```

## How To Use

`FilterCodeCaller` can be used as an object, or as a static class method.

```php
// import first
use GanbaroDigital\ExceptionHelpers\V1\Callers\Filters\FilterCodeCaller;

// we need a stack trace
// use DEBUG_BACKTRACE_IGNORE_ARGS to keep the trace very small
$trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

// we need a list of namespaces to filter out
//
// this can be individual parts of namespaces, or whole classnames
$partials = [
    'Checks',
    'Requirements',
    FilterCodeCaller::class,
];

// to use as an object
$filter = new FilterCodeCaller;
$caller = $filter($trace, $partials);

// to call statically
$caller = FilterCodeCaller::from($trace, $partials);
```

## Return Value

`FilterCodeCaller` returns a `CodeCaller` value object. This object will contain details about the first entry on the `debug_backtrace()` stack that passed filtering.

## Notes

None at this time.

## See Also

* [`CodeCaller` value object](CodeCaller.html)
* [`FilterBacktrace` class](FilterBacktrace.html)
