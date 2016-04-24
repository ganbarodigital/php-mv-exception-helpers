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

Nothing yet.

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
