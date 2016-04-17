---
currentSection: v1
currentItem: callers
pageflow_prev_url: BaseExceptions.html
pageflow_prev_text: BaseExceptions
pageflow_next_url: CodeCaller.html
pageflow_next_text: CodeCaller class

---

# Callers

## Purpose

This is a collection of values and helpers for working with a `debug_backtrace()` stack.

## Available Classes

Class | Description
------|------------
[`CodeCaller`](CodeCaller.html) | value object representing the code that has called our code
[`FilterBacktrace`](FilterBacktrace.html) | find the first item in a backtrace that isn't in the filter list
[`FilterCodeCaller`](FilterCodeCaller.html) | wrapper around `FilterBacktrace` that returns a `CodeCaller` value object.

Click on the name of a class to see full details.
