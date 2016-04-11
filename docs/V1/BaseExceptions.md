---
currentMenu: v1exceptions
---

# Base Exceptions (v1)

## Purpose

Your exceptions can extend classes defined in the `BaseExceptions` namespace.

## ParameterisedException

`ParameterisedException` provides a base class that builds the exception's message from a supplied format string and data set. The data set is stored in the exception. This can be logged and/or inspected to help with debugging and troubleshooting.

    // import
    use GanbaroDigital\ExceptionHelpers\BaseExceptions\ParameterisedException;

    // inherit from ParameterisedException
    class MyException extends ParameterisedException
    {
        // we recommend using static factory methods for creating exceptions
        public static function newFromData($data)
        {
            return new static($formatString, $data);
        }
    }
