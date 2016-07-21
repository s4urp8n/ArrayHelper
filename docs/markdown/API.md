# Zver\ArrayHelper

## Table of Contents

* [ArrayHelper](#arrayhelper)
    * [range](#range)
    * [load](#load)
    * [explode](#explode)
    * [combine](#combine)
    * [getClone](#getclone)
    * [join](#join)
    * [implode](#implode)
    * [walk](#walk)
    * [replaceArray](#replacearray)
    * [set](#set)
    * [reverse](#reverse)
    * [reverseValues](#reversevalues)
    * [reverseKeys](#reversekeys)
    * [getKeys](#getkeys)
    * [getValues](#getvalues)
    * [getLastValueUnset](#getlastvalueunset)
    * [isEmpty](#isempty)
    * [getLastValue](#getlastvalue)
    * [getLastKey](#getlastkey)
    * [getFirstValueUnset](#getfirstvalueunset)
    * [getFirstValue](#getfirstvalue)
    * [getFirstKey](#getfirstkey)
    * [flip](#flip)
    * [getArray](#getarray)
    * [setArray](#setarray)
    * [get](#get)
    * [getLength](#getlength)
    * [count](#count)
    * [length](#length)
    * [getCount](#getcount)
    * [getAt](#getat)
    * [isKeyExists](#iskeyexists)
    * [isValueExists](#isvalueexists)
    * [convertToIndexed](#converttoindexed)
    * [splitParts](#splitparts)
    * [getIterator](#getiterator)
    * [offsetExists](#offsetexists)
    * [offsetGet](#offsetget)
    * [offsetSet](#offsetset)
    * [offsetUnset](#offsetunset)
    * [map](#map)
    * [filter](#filter)
    * [column](#column)
* [EmptyArrayException](#emptyarrayexception)
* [UndefinedOffsetException](#undefinedoffsetexception)

## ArrayHelper

To help you manipulate with arrays



* Full name: \Zver\ArrayHelper
* This class implements: \ArrayAccess, \IteratorAggregate


### range

Get instance of class with loaded array of range values

```php
ArrayHelper::range(  $min,  $max, integer $step = 1 ): \Zver\ArrayHelper
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$min` | **** |  |
| `$max` | **** |  |
| `$step` | **integer** |  |




---

### load

Get instance of ArrayHelper class and load array

```php
ArrayHelper::load( array $array = array() ): \Zver\ArrayHelper
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$array` | **array** |  |




---

### explode

Get instance of ArrayHelper class with exploded string loaded

```php
ArrayHelper::explode( string $delimeter, string $string ): \Zver\ArrayHelper
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$delimeter` | **string** |  |
| `$string` | **string** |  |




---

### combine

Combined two array, keys and values by common associative key

```php
ArrayHelper::combine( array $keys, array $values ): \Zver\ArrayHelper
```



* This method is **static**.
**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$keys` | **array** |  |
| `$values` | **array** |  |




---

### getClone

Get clone of instance

```php
ArrayHelper::getClone(  ): \Zver\ArrayHelper
```







---

### join

Alias for implode()

```php
ArrayHelper::join( string $glue = &#039;&#039;,  $maxDepth ): string
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$glue` | **string** |  |
| `$maxDepth` | **** |  |



**See Also:**

* \Zver\ArrayHelper::implode() 

---

### implode

Concatenates all elements in array recursively in one string.

```php
ArrayHelper::implode( string $glue = &#039;&#039;,  $maxDepth ): string
```

$glue will placed between elements


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$glue` | **string** | String between elements |
| `$maxDepth` | **** |  |




---

### walk

Run callback function with every element of loaded array as an argument recursively

```php
ArrayHelper::walk( callable $callback,  $path = array(),  $that = null ): \Zver\ArrayHelper
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$callback` | **callable** |  |
| `$path` | **** |  |
| `$that` | **** |  |




---

### replaceArray

Alias for set()

```php
ArrayHelper::replaceArray( array $array ): \Zver\ArrayHelper
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$array` | **array** |  |



**See Also:**

* \Zver\ArrayHelper::set() 

---

### set

Replace loaded array with $array

```php
ArrayHelper::set( array $array ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$array` | **array** |  |




---

### reverse

Reverse order of loaded array keys and values

```php
ArrayHelper::reverse(  ): \Zver\ArrayHelper
```







---

### reverseValues

Reverse order of loaded array values

```php
ArrayHelper::reverseValues(  ): \Zver\ArrayHelper
```







---

### reverseKeys

Reverse order of loaded array keys

```php
ArrayHelper::reverseKeys(  ): \Zver\ArrayHelper
```







---

### getKeys

Return indexed array of keys of loaded array

```php
ArrayHelper::getKeys(  ): array
```







---

### getValues

Return indexed array of values of loaded array

```php
ArrayHelper::getValues(  ): array
```







---

### getLastValueUnset

Return last value from array and removes it from array

```php
ArrayHelper::getLastValueUnset(  ): mixed
```







---

### isEmpty

Returns true if loaded array have no elements, false otherwise

```php
ArrayHelper::isEmpty(  ): boolean
```







---

### getLastValue

Get last value of array
If called on empty array - exception will be thrown

```php
ArrayHelper::getLastValue(  ): mixed
```







---

### getLastKey

Get last key of array
If called on empty array - exception will be thrown

```php
ArrayHelper::getLastKey(  ): mixed
```







---

### getFirstValueUnset

Return first value of array and removes it from array

```php
ArrayHelper::getFirstValueUnset(  ): mixed
```







---

### getFirstValue

Get first value of array
If called on empty array - exception will be thrown

```php
ArrayHelper::getFirstValue(  ): mixed
```







---

### getFirstKey

Get first key of array
If called on empty array - exception will be thrown

```php
ArrayHelper::getFirstKey(  ): mixed
```







---

### flip

Flip array - keys will be values, values will be keys
Swap keys and values

```php
ArrayHelper::flip(  ): \Zver\ArrayHelper
```







---

### getArray

Alias for get()

```php
ArrayHelper::getArray(  ): array
```






**See Also:**

* \Zver\ArrayHelper::get() 

---

### setArray

Alias for set()

```php
ArrayHelper::setArray( array $array ): \Zver\ArrayHelper
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$array` | **array** |  |



**See Also:**

* \Zver\ArrayHelper::set() 

---

### get

Return loaded array

```php
ArrayHelper::get(  ): array
```







---

### getLength

Alias for count()

```php
ArrayHelper::getLength(  ): integer
```






**See Also:**

* \Zver\ArrayHelper::count() 

---

### count

Get count of elements in loaded array

```php
ArrayHelper::count(  ): integer
```







---

### length

Alias for count()

```php
ArrayHelper::length(  ): integer
```






**See Also:**

* \Zver\ArrayHelper::count() 

---

### getCount

Alias for count()

```php
ArrayHelper::getCount(  ): integer
```






**See Also:**

* \Zver\ArrayHelper::count() 

---

### getAt

Get value at $index
Also works fine at associative arrays

```php
ArrayHelper::getAt( integer $index ): mixed|null
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$index` | **integer** |  |




---

### isKeyExists

Return array of paths (array of keys) to element of array which key is equals to $key, false otherwise
Searching processed all elements recursively
If $strict is true it compares in strict mode === instead of ==

```php
ArrayHelper::isKeyExists(  $key,  $strict = true,  $maxDepth ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$key` | **** |  |
| `$strict` | **** |  |
| `$maxDepth` | **** |  |




---

### isValueExists

Return array of paths (array of keys) to element of array which value is equals to $value, false otherwise
Searching processed all elements recursively
If $strict is true it compares in strict mode === instead of ==

```php
ArrayHelper::isValueExists(  $value, boolean $strict = true,  $maxDepth ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$value` | **** |  |
| `$strict` | **boolean** |  |
| `$maxDepth` | **** |  |




---

### convertToIndexed

Replaced all keys in single-demension array with indexes starting from $startIndex

```php
ArrayHelper::convertToIndexed( integer $startIndex ): $this
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$startIndex` | **integer** |  |




---

### splitParts

Split array to $numberOfParts parts

```php
ArrayHelper::splitParts(  $numberOfParts ): \Zver\ArrayHelper
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$numberOfParts` | **** |  |




---

### getIterator

Implementation of IteratorAggregade

```php
ArrayHelper::getIterator(  ): \ArrayIterator
```







---

### offsetExists

Implementation of ArrayAccess

```php
ArrayHelper::offsetExists( mixed $offset ): boolean
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$offset` | **mixed** |  |




---

### offsetGet



```php
ArrayHelper::offsetGet( mixed $offset ): mixed|null
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$offset` | **mixed** | Implementation of ArrayAccess |




---

### offsetSet

Implementation of ArrayAccess

```php
ArrayHelper::offsetSet( mixed $offset, mixed $value )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$offset` | **mixed** |  |
| `$value` | **mixed** |  |




---

### offsetUnset

Implementation of ArrayAccess

```php
ArrayHelper::offsetUnset( mixed $offset )
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$offset` | **mixed** |  |




---

### map

Apply callback to every element of loaded array.

```php
ArrayHelper::map(  $callback ): $this
```

Keys preserved.
No recursion.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$callback` | **** |  |




---

### filter

Apply callback to every element of loaded array, if callback returns false - element with key will be unset.

```php
ArrayHelper::filter(  $callback ): $this
```

Keys preserved.
No recursion.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$callback` | **** |  |




---

### column

Get only one key column from multi-dimensional array

```php
ArrayHelper::column( string $column ): \Zver\ArrayHelper
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$column` | **string** |  |




---

## EmptyArrayException





* Full name: \Zver\Exceptions\ArrayHelper\EmptyArrayException
* Parent class: 


## UndefinedOffsetException





* Full name: \Zver\Exceptions\ArrayHelper\UndefinedOffsetException
* Parent class: 




--------
> This document was automatically generated from source code comments on 2016-07-22 using [phpDocumentor](http://www.phpdoc.org/) and [cvuorinen/phpdoc-markdown-public](https://github.com/cvuorinen/phpdoc-markdown-public)
