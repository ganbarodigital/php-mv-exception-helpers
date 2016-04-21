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

* Added support for searching a stack trace multiple times
  - added `$index` parameter to `FilterBacktrace`

## v1.2016041901

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
