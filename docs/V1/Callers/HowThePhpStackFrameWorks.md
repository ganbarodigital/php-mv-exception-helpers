---
currentSection: v1
currentItem: callers
pageflow_prev_url: index.html
pageflow_prev_text: Callers
pageflow_next_url: CodeCaller.html
pageflow_next_text: CodeCaller class
---

# How The PHP Stack Frame Works

## Introduction

The PHP stack frame isn't as straight-forward as you might think. This caught us out when building [`FilterBacktrace`](FilterBacktrace.html).

## What's In A Stack Frame

Here's a stack dump generated by calling `debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)`:

    array(11) {
      [0] =>
      array(5) {
        'file' =>
        string(98) "/Users/stuart/Devel/ganbarodigital/php-mv-defensive/tests/V1/Exceptions/BadRequirementDataTest.php"
        'line' =>
        int(142)
        'function' =>
        string(22) "newFromRequirementData"
        'class' =>
        string(57) "GanbaroDigital\Defensive\V1\Exceptions\BadRequirementData"
        'type' =>
        string(2) "::"
      }
      [1] =>
      array(3) {
        'function' =>
        string(35) "testCanCreateFromBadRequirementData"
        'class' =>
        string(65) "GanbaroDigitalTest\Defensive\V1\Exceptions\BadRequirementDataTest"
        'type' =>
        string(2) "->"
      }
      [2] =>
      array(5) {
        'file' =>
        string(101) "/Users/stuart/Devel/ganbarodigital/php-mv-defensive/vendor/phpunit/phpunit/src/Framework/TestCase.php"
        'line' =>
        int(909)
        'function' =>
        string(10) "invokeArgs"
        'class' =>
        string(16) "ReflectionMethod"
        'type' =>
        string(2) "->"
      }
      [3] =>
      array(5) {
        'file' =>
        string(101) "/Users/stuart/Devel/ganbarodigital/php-mv-defensive/vendor/phpunit/phpunit/src/Framework/TestCase.php"
        'line' =>
        int(768)
        'function' =>
        string(7) "runTest"
        'class' =>
        string(26) "PHPUnit_Framework_TestCase"
        'type' =>
        string(2) "->"
      }
      [4] =>
      array(5) {
        'file' =>
        string(103) "/Users/stuart/Devel/ganbarodigital/php-mv-defensive/vendor/phpunit/phpunit/src/Framework/TestResult.php"
        'line' =>
        int(612)
        'function' =>
        string(7) "runBare"
        'class' =>
        string(26) "PHPUnit_Framework_TestCase"
        'type' =>
        string(2) "->"
      }
      [5] =>
      array(5) {
        'file' =>
        string(101) "/Users/stuart/Devel/ganbarodigital/php-mv-defensive/vendor/phpunit/phpunit/src/Framework/TestCase.php"
        'line' =>
        int(724)
        'function' =>
        string(3) "run"
        'class' =>
        string(28) "PHPUnit_Framework_TestResult"
        'type' =>
        string(2) "->"
      }
      [6] =>
      array(5) {
        'file' =>
        string(102) "/Users/stuart/Devel/ganbarodigital/php-mv-defensive/vendor/phpunit/phpunit/src/Framework/TestSuite.php"
        'line' =>
        int(747)
        'function' =>
        string(3) "run"
        'class' =>
        string(26) "PHPUnit_Framework_TestCase"
        'type' =>
        string(2) "->"
      }
      [7] =>
      array(5) {
        'file' =>
        string(100) "/Users/stuart/Devel/ganbarodigital/php-mv-defensive/vendor/phpunit/phpunit/src/TextUI/TestRunner.php"
        'line' =>
        int(440)
        'function' =>
        string(3) "run"
        'class' =>
        string(27) "PHPUnit_Framework_TestSuite"
        'type' =>
        string(2) "->"
      }
      [8] =>
      array(5) {
        'file' =>
        string(97) "/Users/stuart/Devel/ganbarodigital/php-mv-defensive/vendor/phpunit/phpunit/src/TextUI/Command.php"
        'line' =>
        int(149)
        'function' =>
        string(5) "doRun"
        'class' =>
        string(25) "PHPUnit_TextUI_TestRunner"
        'type' =>
        string(2) "->"
      }
      [9] =>
      array(5) {
        'file' =>
        string(97) "/Users/stuart/Devel/ganbarodigital/php-mv-defensive/vendor/phpunit/phpunit/src/TextUI/Command.php"
        'line' =>
        int(100)
        'function' =>
        string(3) "run"
        'class' =>
        string(22) "PHPUnit_TextUI_Command"
        'type' =>
        string(2) "->"
      }
      [10] =>
      array(5) {
        'file' =>
        string(82) "/Users/stuart/Devel/ganbarodigital/php-mv-defensive/vendor/phpunit/phpunit/phpunit"
        'line' =>
        int(47)
        'function' =>
        string(4) "main"
        'class' =>
        string(22) "PHPUnit_TextUI_Command"
        'type' =>
        string(2) "::"
      }
    }

Each stack frame can contain any of these details:

Detail | Description
-------|------------
file | A PHP file that has been executed
line | The line number in 'file' that is being executed
class | A class that is being called
function | The method on 'class' that is being called (if 'class' has a value)
function | A global function that is being called (if 'class' has no value or is not set)
type | How 'function' was called (`::` for a static call, `->` for a call on an object, not set if 'class' is not set)

At first glance, that looks very straight-forward. Let's isolate a single stack frame:

Detail | Value
-------|------
file | /Users/stuart/Devel/ganbarodigital/php-mv-defensive/vendor/phpunit/phpunit/src/TextUI/TestRunner.php
line | 440
class | PHPUnit_Framework_TestSuite
function | run

Hang on a moment. According to that, line 440 of TestRunner.php is part of the `run()` method of `PHPUnit_Framework_TestSuite`. Surely that's a typo?

No, it isn't. Here's the stack frame that I built that table from:

    [7] =>
    array(5) {
      'file' =>
      string(100) "/Users/stuart/Devel/ganbarodigital/php-mv-defensive/vendor/phpunit/phpunit/src/TextUI/TestRunner.php"
      'line' =>
      int(440)
      'function' =>
      string(3) "run"
      'class' =>
      string(27) "PHPUnit_Framework_TestSuite"
      'type' =>
      string(2) "->"
    }

What's going on?

## Stack Frames Contain Staggered Data

In any PHP stack frame, `file` and `line` correctly refer to the code that is executing. `class`, `function` and `type` (if present) refer to the code that `file` and `line` is __calling__ - and __not__ the code in `file` and line `line` as you might be expecting.

You can clearly see that in the following two stack frames:

    [7] =>
    array(5) {
      'file' =>
      string(100) "/Users/stuart/Devel/ganbarodigital/php-mv-defensive/vendor/phpunit/phpunit/src/TextUI/TestRunner.php"
      'line' =>
      int(440)
      'function' =>
      string(3) "run"
      'class' =>
      string(27) "PHPUnit_Framework_TestSuite"
      'type' =>
      string(2) "->"
    }
    [8] =>
    array(5) {
      'file' =>
      string(97) "/Users/stuart/Devel/ganbarodigital/php-mv-defensive/vendor/phpunit/phpunit/src/TextUI/Command.php"
      'line' =>
      int(149)
      'function' =>
      string(5) "doRun"
      'class' =>
      string(25) "PHPUnit_TextUI_TestRunner"
      'type' =>
      string(2) "->"
    }

## How To Work Out Actual Caller Details

To work out a full set of caller details, do the following:

1. take the `class`, `function` and `type` values from a stack frame
2. take the `file` and `line` values from the preceeding stack frame

In our example above, if you combine `class`, `function` and `type` from stack frame 8 with `file` and `line` from stack frame 7, you get this:

Detail | Value
-------|------
file | /Users/stuart/Devel/ganbarodigital/php-mv-defensive/vendor/phpunit/phpunit/src/TextUI/TestRunner.php
line | 440
class | PHPUnit_TextUI_TestRunner
function | doRun
type | ->

... which looks far more credible :)

## Automatically Handled For You

Our [`FilterBacktrace`](FilterBacktrace.html) class automatically handles this for you. All of our other classes in the `Callers` namespace use `FilterBacktrace` to understand the PHP stack.

As long as you use our classes, you won't have to worry about this PHP behaviour in your own code.
