---
currentSection: overview
currentItem: home
pageflow_next_url: license.html
pageflow_next_text: License
---

# Introduction

## What Is The Exception Helpers Library?

Ganbaro Digital's _Exception Helpers Library_ provides an easy-to-use collection of helpers and base classes for your own exception classes.

* Ships with a collection of useful base classes

## Goals

The _Exception Helpers Library_'s purpose is to provide a common `Exception` structure to all of Ganbaro Digital's PHP libraries and apps.

* Collect common base classes
* Collect commonly used exception features into reusable helpers

## Design Constraints

The library's design is guided by the following constraint(s):

* _Fundamental dependency of other libraries_: This library provides base classes for other libraries to use. Composer does not support multual dependencies (two or more packages depending on each other). As a result, this library needs to depend on very little (if anything at all).

## Questions?

This package was created by [Stuart Herbert](http://www.stuartherbert.com) for [Ganbaro Digital Ltd](http://ganbarodigital.com). Follow [@ganbarodigital](https://twitter.com/ganbarodigital) or [@stuherbert](https://twitter.com/stuherbert) for updates.
