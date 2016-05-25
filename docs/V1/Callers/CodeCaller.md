---
currentSection: v1
currentItem: callers
pageflow_prev_url: HowThePhpStackFrameWorks.html
pageflow_prev_text: How The PHP Stack Frame Works
pageflow_next_url: FilterBacktrace.html
pageflow_next_text: FilterBacktrace class
---

# CodeCaller

<div class="callout info" markdown="1">
Since v1.2016041701
</div>

## Description

`CodeCaller` is a value object. It represents a stack frame on a `debug_backtrace()` stack.

[`FilterCodeCaller`](FilterCodeCaller.html) will build a `CodeCaller` object for you.

## Public Interface

`CodeCaller` has the following public interface:

```php
namespace GanbaroDigital\ExceptionHelpers\V1\Callers\Values\CodeCaller;

use GanbaroDigital\MissingBits\TraceInspectors\StackFrame;

class CodeCaller extends StackFrame
{
    /**
     * constructor
     *
     * @param string|null $class
     *        which class called us?
     * @param string|null $function
     *        which function or method called us?
     * @param string|null $file
     *        which file was the calling code in?
     * @param int|null $line
     *        which line in $file was the calling code on?
     * @param array $stack
     *        what was the call stack at the time?
     * @inheritedFrom StackFrame
     */
    public function __construct($class, $function, $file, $line, $stack = []);

    /**
     * which class called us?
     *
     * @return string|null
     * @inheritedFrom StackFrame
     */
    public function getClass();

    /**
     * which function or method called us?
     *
     * @return string|null
     * @inheritedFrom StackFrame
     */
    public function getFunction();

    /**
     * which method called us?
     *
     * @return string|null
     * @inheritedFrom StackFrame
     */
    public function getMethod();

    /**
     * which file was the calling code defined in?
     *
     * @return string|null
     * @inheritedFrom StackFrame
     */
    public function getFilename();

    /**
     * which line in $this->getFile() was the calling code defined on?
     *
     * @return int|null
     * @inheritedFrom StackFrame
     */
    public function getLine();

    /**
     * what were the contents of the call stack at the time?
     *
     * an empty array means that we weren't asked to save the call stack
     * (probably to save memory - the call stack can be large)
     *
     * @return array
     * @inheritedFrom StackFrame
     */
    public function getStack();

    /**
     * return our contents as a sensible, printable string
     *
     * @return string
     */
    public function getCaller();

    /**
     * return our contents as a sensible, printable string
     *
     * @return string
     * @inheritedFrom StackFrame
     */
    public function __toString();
}
```

## Notes

None at this time.

## Changelog

### v1.2016052501

* `CodeCaller` now extends the [`StackFrame`](http://ganbarodigital.github.io/php-the-missing-bits/traces/StackFrame.html) from [PHP: The Missing Bits](http://ganbarodigital.github.io/php-the-missing-bits/).

## See Also

* [`FilterCodeCaller` class](FilterCodeCaller.html)
* [`StackFrame` class](http://ganbarodigital.github.io/php-the-missing-bits/traces/StackFrame.html)
