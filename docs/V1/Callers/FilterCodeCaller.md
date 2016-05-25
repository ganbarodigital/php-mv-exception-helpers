---
currentSection: v1
currentItem: callers
pageflow_prev_url: FilterBacktraceForTwoCodeCallers.html
pageflow_prev_text: FilterBacktraceForTwoCodeCallers class
---

# FilterCodeCaller

<div class="callout info" markdown="1">
Since v1.2016041701
</div>

## Description

`FilterCodeCaller` is a data filter. It is a wrapper around [`FilterBacktrace`](FilterBacktrace.html). It converts `FilterBacktrace`'s return value into a [`CodeCaller`](CodeCaller.html) value object.

`FilterCodeCaller` was introduced for use in the `UnsupportedType` exception.

## Public Interface

`FilterCodeCaller` has the following public interface:

```php
use GanbaroDigital\ExceptionHelpers\V1\Callers\Values\CodeCaller;

class FilterCodeCaller
{
    /**
     * work out who has called a piece of code
     *
     * @param  array $backtrace
     *         the debug_backtrace() return value
     * @param  array $$filterList
     *         a list of namespaces and classes to skip over
     * @return CodeCaller
     */
    public function __invoke($backtrace, $$filterList = []);

    /**
     * work out who has called a piece of code
     *
     * @param  array $backtrace
     *         the debug_backtrace() return value
     * @param  array $$filterList
     *         a list of namespaces and classes to skip over
     * @return CodeCaller
     */
    public static function from($backtrace, $$filterList = []);
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
// this can be individual namespaces, or classnames
$filterList = [
    'GanbaroDigital\Reflection\V1\Checks',
    'GanbaroDigital\Reflection\V1\Requirements',
    FilterCodeCaller::class,
];

// to use as an object
$filter = new FilterCodeCaller;
$caller = $filter($trace, $filterList);

// to call statically
$caller = FilterCodeCaller::from($trace, $filterList);
```

## Return Value

`FilterCodeCaller` returns a `CodeCaller` value object. This object will contain details about the first entry on the `debug_backtrace()` stack that passed filtering.

## Notes

None at this time.

## Changelog

### v1.2016052501

* `FilterCodeCaller` no longer filters out partial namespaces.

  This was a disaster waiting to happen. Filtering partial namespaces meant that we'd filter out your namespaces too - and that's a violation of the rule of no surprises. It's just too blunt an instrument. We've switched to filtering out whole namespaces only to make sure that we never filter out your namespaces when we meant to only filter out our namespaces.

* The public static property `FilterCodeCaller::$DEFAULT_PARTIALS` has been removed.

  It has been removed because we're no longer supporting filtering out partial namespaces.

  The truth is, it was always a bit of a risky hack. There was no way for us to prevent anyone from changing this list at runtime. It was a bug waiting to happen. So it's gone.

  This is a backwards-compatibility break - the kind which can cause your code to crash. The whole point of versioned APIs is that you can trust us not to break your code. But in this case it was simply too dangerous a feature to keep. We're sorry if you're using this library and this change causes you problems.

## See Also

* [`CodeCaller` value object](CodeCaller.html)
* [`FilterBacktrace` class](FilterBacktrace.html)
