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
use GanbaroDigital\ExceptionHelpers\V1\Callers\Values\CodeCaller;

class CodeCaller
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
     */
    public function __construct($class, $function, $file, $line, $stack = []);

    /**
     * which class called us?
     *
     * @return string|null
     */
    public function getClass();

    /**
     * which function or method called us?
     *
     * @return string|null
     */
    public function getFunction();

    /**
     * which method called us?
     *
     * @return string|null
     */
    public function getMethod();

    /**
     * which file was the calling code defined in?
     *
     * @return string|null
     */
    public function getFilename();

    /**
     * which line in $this->getFile() was the calling code defined on?
     *
     * @return int|null
     */
    public function getLine();

    /**
     * what were the contents of the call stack at the time?
     *
     * an empty array means that we weren't asked to save the call stack
     * (probably to save memory - the call stack can be large)
     *
     * @return array
     */
    public function getStack();

    /**
     * return our contents as a sensible, printable string
     *
     * @return string
     */
    public function getCaller();
}
```

## Notes

None at this time.

## See Also

* [`FilterCodeCaller` class](FilterCodeCaller.html)
