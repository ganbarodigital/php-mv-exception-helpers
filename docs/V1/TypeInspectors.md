---
currentMenu: v1typeinspectors
---

# Type Inspectors

## Purpose

Use __type inspectors__ to work out what type of data you are examining.

They were originally added to help Exception classes generate a suitable error message.

## GetPrintableType

`GetPrintableType` returns the PHP type of the data that you pass to it.

    // how to import
    use GanbaroDigital\ExceptionHelpers\Types\Inspectors\GetPrintableType;

    // call directly
    //
    // returns a string
    echo GetPrintableType::of($data);

    // use as an object
    //
    // returns a string
    $inspector = new GetPrintableType;
    echo $inspector($data);

### Signature

`GetPrintableType` can be used as an object, or called directly:

    // as an object
    $inspector = new GetPrintableType;
    string $inspector(mixed $data, int $flags = self::FLAG_NONE);

    // called directly
    string GetPrintableType::from(mixed $data, int $flags = self::FLAG_NONE);

The input parameters are:

* `$data` - the PHP variable to examine
* `$flags` - bit-mask of options to add extra information to the return value

The following flags are supported:

* `GetPrintableType::FLAG_NONE` - no extra information required (default behaviour)
* `GetPrintableType::FLAG_CLASSNAME` - add the class|interface|trait name if `$data` is a PHP object

`GetPrintableType` returns a `string`, which contains the PHP type of `$data`:

* `NULL`
* `array`
* `boolean`
* `callable`
* `double`
* `integer`
* `object`
* `resource`
* `string`
