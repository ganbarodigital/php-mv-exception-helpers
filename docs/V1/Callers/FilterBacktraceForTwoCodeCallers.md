---
currentSection: v1
currentItem: callers
pageflow_prev_url: FilterBacktrace.html
pageflow_prev_text: FilterBacktrace class
pageflow_next_url: FilterCodeCaller.html
pageflow_next_text: FilterCodeCaller class
---

# FilterBacktraceForTwoCodeCallers

<div class="callout info" markdown="1">
Since v1.2016042401
</div>

## Description

`FilterBacktraceForTwoCodeCallers` is a data filter. It is a wrapper around [`FilterBacktrace`](FilterBacktrace.html). It converts `FilterBacktrace`'s return value into an array of two [`CodeCaller`](CodeCaller.html) value objects.

`FilterBacktraceForTwoCodeCallers` was introduced for use in exception error messages in the `ganbarodigital/php-mv-defensive` package.

## Public Interface

`FilterBacktraceForTwoCodeCallers` has the following public interface:

```php
use GanbaroDigital\ExceptionHelpers\V1\Callers\Filters\FilterBacktraceForTwoCodeCallers;
use GanbaroDigital\ExceptionHelpers\V1\Callers\Values\CodeCaller;

class FilterBacktraceForTwoCodeCallers
{
    /**
     * work out who has called a piece of code, and who (in turn) called that
     * piece of code too
     *
     * @param  array $backtrace
     *         the debug_backtrace() return value
     * @param  array $filterList
     *         a list of namespaces and classes to skip over
     * @return array
     */
    public function __invoke($backtrace, $filterList = []);

    /**
     * work out who has called a piece of code, and who (in turn) called that
     * piece of code too
     *
     * @param  array $backtrace
     *         the debug_backtrace() return value
     * @param  array $filterList
     *         a list of namespaces and classes to skip over
     * @return array
     */
    public static function from($backtrace, $filterList = []);
}
```

## How To Use

`FilterBacktraceForTwoCodeCallers` can be used as an object, or as a static class method.

```php
// import first
use GanbaroDigital\ExceptionHelpers\V1\Callers\Filters\FilterBacktraceForTwoCodeCallers;

// we need a stack trace
// use DEBUG_BACKTRACE_IGNORE_ARGS to keep the trace very small
$trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

// we need a list of namespaces to filter out
//
// this can be individual parts of namespaces, or whole classnames
$filterList = [
    'GanbaroDigital\Reflection\V1\Checks',
    'GanbaroDigital\Reflection\V1\Requirements',
    FilterBacktraceForTwoCodeCallers::class,
];

// to use as an object
$filter = new FilterBacktraceForTwoCodeCallers;
$callers = $filter($trace, $filterList);

// to call statically
$callers = FilterBacktraceForTwoCodeCallers::from($trace, $filterList);
```

## Return Value

`FilterBacktraceForTwoCodeCallers` returns an array of `CodeCaller` value objects.

1. The first value object contains details of the code that called you.
2. The second value object contains details of the code that called that code.

This is very useful in exception messages. It allows your exception to state which bit of code is throwing the exception, and which bit of code caused the exception.

## Notes

None at this time.

## Changelog

### v1.2016052501

* `FilterBacktraceForTwoCodeCallers` no longer filters out partial namespaces.

  This was a disaster waiting to happen. Filtering partial namespaces meant that we'd filter out your namespaces too - and that's a violation of the rule of no surprises. It's just too blunt an instrument. We've switched to filtering out whole namespaces only to make sure that we never filter out your namespaces when we meant to only filter out our namespaces.

## See Also

* [`CodeCaller` value object](CodeCaller.html)
* [`FilterBacktrace` class](FilterBacktrace.html)
