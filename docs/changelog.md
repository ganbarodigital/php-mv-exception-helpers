---
currentSection: overview
currentItem: changelog
pageflow_prev_url: multivariant.html
pageflow_prev_text: Multi-Variant
pageflow_next_url: contributing.html
pageflow_next_text: Contributing
---
# CHANGELOG

## develop branch

### New

* added helpers to standardise the structure of format strings and parameter lists that get fed into `ParameterisedException`
  - added `BuildThrownAndCalledBy`
  - added `BuildThrownBy`
  - updated `UnsupportedType` to use the new helpers
  - updated `UnsupportedValue` to use the new helpers

## v1.2016061201

### New

* added separate factory methods when throwing an exception about an input parameter
  - added `UnsupportedType::newFromInputParameter`
  - added `UnsupportedValue::newFromInputParameter`

### Fixes

* `$callerFilter` signature is now enforced as array
  - updated `UnsupportedType`
  - updated `UnsupportedValue`

## v1.2016052501

Released Wed 25th May 2016.

### Refactor

* Move the code caller functionality into `ganbarodigital/php-the-missing-bits`. This is the right place for the functionality to live.
  - `FilterBacktrace` now extends code provided by `php-the-missing-bits`
  - `CodeCaller` now extends code provided by `php-the-missing-bits`
  - `FilterBacktrace`, `FilterBacktraceForTwoCodeCallers` and `FilterCodeCaller` no longer support filtering partial namespaces
  - `FilterCodeCaller::$DEFAULT_PARTIALS` is gone

## v1.2016052401

Released Tue 24th May 2016.

### Fixes

* Skip the first frame of a debug stack trace - the information provided in there can never be complete
  - Updated `FilterBacktrace`
  - Updated `FilterBacktraceForTwoCodeCallers`
* Make sure our base exceptions do not appear in the caller details that we include in an exception
  - Updated `UnsupportedType`
  - Updated `UnsupportedValue`

## v1.2016050201

Released Mon 2nd May 2016.

### New

* Updated to be compatible with [`ganbarodigital/php-http-status` version 2](https://ganbarodigital.github.io/php-http-status)

## v1.2016042405

Released Sun 24th April 2016.

### New

* Added base class for when an input parameter has the right type, but an unsupported value
  - Added `UnsupportedType`

## v1.2016042404

Released Sun 24th April 2016.

### New

* Added `CodeCaller::__toString()` as an alias for `CodeCaller::getCaller()`

### Fixes

* Added tracking of caller type to our backtrace analysis
  - Added new parameter to `CodeCaller::__construct`
  - Added `CodeCaller::getCallerType()` method
  - Updated `CodeCaller::getCallerName()` to include the caller type (used to be `::` all the time)
  - Updated `FilterBacktrace` to return `type` in a stack frame
  - Updated `FilterBacktraceForTwoCodeCallers` and `FilterCodeCaller` to generate `CodeCaller` values that include the caller type

## v1.20160402403

### Fixes

* Fixed `FilterBacktrace` to deal with the way that the PHP call stack splits data across two stack frames
* Added documentation explaining the PHP call stack data structure

## v1.2016042402

Released Sun 24th April 2016.

### Fixes

* Fixed `FilterBacktraceForTwoCodeCallers` to look for the second caller after the stack frame that contains the first caller

## v1.2016042401

Released Sun 24th April 2016.

### New

* Added support for searching a stack trace multiple times
  - added `$index` parameter to `FilterBacktrace`
  - added `FilterBacktraceForTwoCodeCallers`

## v1.2016041901

Released Tue 19th April 2016.

### Docs

* Switched to downloading template from Github
* Switched to Stuart's fork of Couscous
* Made Couscous a dev dependency

## v1.2016041701

Released Sun 17th April 2016.

### New

* Added support for exceptions that can remember relevant data
  - added `V1\BaseExceptions\ParameterisedException`
  - added `V1\BaseExceptions\UnsupportedType`
* Added support for working out who called an exception
  - added `V1\Callers\Values\CodeCaller`
  - added `V1\Callers\Filters\FilterBacktrace`
  - added `V1\Callers\Filters\FilterCodeCaller`
