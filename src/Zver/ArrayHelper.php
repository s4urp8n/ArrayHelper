<?php

namespace Zver {

    use Zver\Exceptions\ArrayHelper\EmptyArrayException;
    use Zver\Exceptions\ArrayHelper\UndefinedOffsetException;

    /**
     * To help you manipulate with arrays
     *
     * @package Zver
     */
    class ArrayHelper implements \ArrayAccess, \IteratorAggregate
    {

        /**
         * Protected variable to store loaded array
         *
         * @var array
         */
        protected $array = [];

        /**
         * Private constructor
         *
         * @param array $array
         */
        private function __construct(array $array)
        {
            $this->array = $array;
        }

        /**
         * Get instance of class with loaded array of range values
         *
         * @param     $min
         * @param     $max
         * @param int $step
         *
         * @example
         *
         * <code>
         * <?php
         *
         * $to10=Arr::range(0,10);
         *
         * //$to10 contains array [0,1,2,3,4,5,6,7,8,9,10]
         *
         * $to10step2=Arr::range(0,10,2);
         *
         * //$to10step2 contains array [0,2,4,6,8,10]
         *
         * ?>
         * </code>
         *
         * @return \Zver\ArrayHelper
         */
        public static function range($min, $max, $step = 1)
        {
            return static::load(range($min, $max, $step));
        }

        /**
         * Get instance of ArrayHelper class and load array
         *
         * @example
         *
         * <code>
         * <?php
         * $arr=Arr::load([1,2,3]);
         * ?>
         * </code>
         *
         * @param array $array
         *
         * @return \Zver\ArrayHelper
         */
        public static function load(array $array = [])
        {
            return new static($array);
        }

        /**
         * Get instance of ArrayHelper class with exploded string loaded
         *
         * @param string $delimeter
         * @param string $string
         * @param null   $limit
         *
         * @return \Zver\ArrayHelper
         */
        public static function explode($delimeter, $string)
        {
            return static::load(explode($delimeter, $string));
        }

        /**
         * Combined two array, keys and values by common associative key
         *
         * @example
         *
         * Keys = Array
         * (
         *      [1] => XL
         *      [2] => L
         *      [3] => S
         *      [4] => M
         *      [6] => XS
         * )
         *
         * Values = Array
         * (
         *      [1] => 1200
         *      [2] => 700
         *      [3] => 670
         *      [4] => 450
         *      [6] => 300
         *      [7] =>
         * )
         *
         * Combined = Array
         * (
         *      [XL] => 1200
         *      [L] => 700
         *      [S] => 670
         *      [M] => 450
         *      [XS] => 300
         * )
         *
         * @param array $keys
         * @param array $values
         *
         * @return \Zver\ArrayHelper
         */
        public static function combine(array $keys, array $values)
        {
            $combined = [];

            foreach ($keys as $keysKey => $keysValue) {
                foreach ($values as $valuesKey => $valuesValue) {
                    if ($valuesKey == $keysKey) {
                        $combined[$keysValue] = $valuesValue;
                    }
                }
            }

            return static::load($combined);
        }

        /**
         * Get clone of instance
         *
         * @example
         *
         * <code>
         * <?php
         *
         * $array=Arr::load([1,2,3]);
         *
         * $clone=$array->getClone();
         *
         * //$clone and $array - two indentical instances of ArrayHelper class
         *
         * ?>
         * </code>
         *
         * @return \Zver\ArrayHelper
         */
        public function getClone()
        {
            return static::load($this->array);
        }

        /**
         * Alias for implode()
         *
         * @see implode()
         *
         * @param string $glue
         *
         * @return string
         */
        public function join($glue = '', $maxDepth = 0)
        {
            return $this->implode($glue, $maxDepth);
        }

        /**
         * Concatenates all elements in array recursively in one string.
         * $glue will placed between elements
         *
         * @example
         *
         * <code>
         * <?php
         *
         * $imploded=Arr::load(1,2,3,4,[5,6])->implode();
         *
         * //$imploded='123456'
         *
         * $imploded=Arr::load(1,2,3,4,[5,6])->implode('--');
         *
         * //$imploded='1--2--3--4--5--6'
         *
         * ?>
         * </code>
         *
         * @param string $glue String between elements
         *
         * @return string
         */
        public function implode($glue = '', $maxDepth = 0)
        {
            $imploded = '';
            $this->walk(
                function ($key, $value, $path) use (&$imploded, $glue, $maxDepth) {
                    if (!is_array($value)) {
                        if ($maxDepth <= 0 || ($maxDepth > 0 && count($path) <= $maxDepth)) {
                            $imploded .= (($imploded === '')
                                    ? ''
                                    : $glue) . $value;
                        }
                    }
                }
            );

            return $imploded;
        }

        /**
         * Run callback function with every element of loaded array as an argument recursively
         *
         * @param callable $callback
         *
         * @return \Zver\ArrayHelper
         */
        public function walk(callable $callback, $path = [], $that = null)
        {
            foreach ($this->array as $key => $element) {

                if (is_array($element)) {
                    call_user_func_array($callback, [$key, $element, $path, $this]);
                    $path[] = $key;
                    static::load($element)
                          ->walk($callback, $path, $this);
                } else {
                    call_user_func_array($callback, [$key, $element, $path, $this]);
                }
            }

            return $this;
        }

        /**
         * Alias for set()
         *
         * @see set()
         *
         * @param array $array
         *
         * @return \Zver\ArrayHelper
         */
        public function replaceArray(array $array)
        {
            return $this->set($array);
        }

        /**
         * Replace loaded array with $array
         *
         * @example
         *
         * <code>
         * <?php
         * $arr=Arr::load([1,2,3]);
         * $arr->set([2,3]);
         *
         * ?>
         * </code>
         *
         * @param array $array
         *
         * @return $this
         */
        public function set(array $array)
        {
            $this->array = $array;

            return $this;
        }

        /**
         * Reverse order of loaded array keys and values
         *
         * @return \Zver\ArrayHelper
         */
        public function reverse()
        {
            return $this->reverseKeys()
                        ->reverseValues();
        }

        /**
         * Reverse order of loaded array values
         *
         * @return \Zver\ArrayHelper
         */
        public function reverseValues()
        {
            $keys = $this->getKeys();
            $values = array_reverse($this->getValues());

            $reversed = [];

            foreach ($keys as $index => $key) {
                $reversed[$key] = $values[$index];
            }

            $this->array = $reversed;

            return $this;
        }

        /**
         * Reverse order of loaded array keys
         *
         * @return \Zver\ArrayHelper
         */
        public function reverseKeys()
        {
            $keys = array_reverse($this->getKeys());
            $values = $this->getValues();

            $reversed = [];

            foreach ($keys as $index => $key) {
                $reversed[$key] = $values[$index];
            }

            $this->array = $reversed;

            return $this;
        }

        /**
         * Return indexed array of keys of loaded array
         *
         * @return array
         */
        public function getKeys()
        {
            return array_keys($this->array);
        }

        /**
         * Return indexed array of values of loaded array
         *
         * @return array
         */
        public function getValues()
        {
            return array_values($this->array);
        }

        /**
         * Return last value from array and removes it from array
         *
         * @return mixed
         * @throws \Zver\Exceptions\ArrayHelper\EmptyArrayException
         */
        public function getLastValueUnset()
        {
            if ($this->isEmpty()) {
                throw new EmptyArrayException();
            }

            $value = $this->getLastValue();
            unset($this[$this->getLastKey()]);

            return $value;
        }

        /**
         * Returns true if loaded array have no elements, false otherwise
         *
         * @return bool
         */
        public function isEmpty()
        {
            return (count($this->getValues()) == 0);
        }

        /**
         * Get last value of array
         * If called on empty array - exception will be thrown
         *
         * @return mixed
         */
        public function getLastValue()
        {
            if ($this->isEmpty()) {
                throw new EmptyArrayException();
            }

            $values = $this->getValues();
            $valuesCount = count($values);

            return $values[$valuesCount - 1];
        }

        /**
         * Get last key of array
         * If called on empty array - exception will be thrown
         *
         * @return mixed
         */
        public function getLastKey()
        {
            if ($this->isEmpty()) {
                throw new EmptyArrayException();
            }

            return static::load($this->getKeys())
                         ->getLastValue();
        }

        /**
         * Return first value of array and removes it from array
         *
         * @return mixed
         * @throws \Zver\Exceptions\ArrayHelper\EmptyArrayException
         */
        public function getFirstValueUnset()
        {
            if ($this->isEmpty()) {
                throw new EmptyArrayException();
            }

            $value = $this->getFirstValue();
            unset($this[$this->getFirstKey()]);

            return $value;
        }

        /**
         * Get first value of array
         * If called on empty array - exception will be thrown
         *
         * @return mixed
         */
        public function getFirstValue()
        {
            if ($this->isEmpty()) {
                throw new EmptyArrayException();
            }

            $values = $this->getValues();

            return $values[0];

        }

        /**
         * Get first key of array
         * If called on empty array - exception will be thrown
         *
         * @return mixed
         */
        public function getFirstKey()
        {
            if ($this->isEmpty()) {
                throw new EmptyArrayException();
            }

            $keys = $this->getKeys();

            return $keys[0];
        }

        /**
         * Flip array - keys will be values, values will be keys
         * Swap keys and values
         *
         * @return \Zver\ArrayHelper
         */
        public function flip()
        {
            $this->array = array_flip($this->array);

            return $this;
        }

        /**
         * Alias for get()
         *
         * @see get()
         * @return array
         */
        public function getArray()
        {
            return $this->get();
        }

        /**
         * Alias for set()
         *
         * @see set()
         *
         * @param array $array
         *
         * @return \Zver\ArrayHelper
         */
        public function setArray(array $array)
        {
            return $this->set($array);
        }

        /**
         * Return loaded array
         *
         * @return array
         */
        public function get()
        {
            return $this->array;
        }

        /**
         * Alias for count()
         *
         * @see count()
         * @return int
         */
        public function getLength()
        {
            return $this->count();
        }

        /**
         * Get count of elements in loaded array
         *
         * @return int
         */
        public function count()
        {
            return count($this->array);
        }

        /**
         * Alias for count()
         *
         * @see count()
         * @return int
         */
        public function length()
        {
            return $this->count();
        }

        /**
         * Alias for count()
         *
         * @see count()
         * @return int
         */
        public function getCount()
        {
            return $this->count();
        }

        /**
         * Get value at $index
         * Also works fine at associative arrays
         *
         * @param int  $index
         * @param null $default
         *
         * @return mixed|null
         */
        public function getAt($index)
        {

            if ($this->isEmpty()) {
                throw new EmptyArrayException();
            }

            $values = $this->getValues();
            if (isset($values[$index])) {
                return $values[$index];
            } else {
                throw new UndefinedOffsetException();
            }
        }

        /**
         * Return array of paths (array of keys) to element of array which key is equals to $key, false otherwise
         * Searching processed all elements recursively
         * If $strict is true it compares in strict mode === instead of ==
         *
         * @param $key
         *
         * @return bool
         */
        public function isKeyExists($key, $strict = true, $maxDepth = 0)
        {
            $exists = false;
            $this->walk(
                function ($keyElement, $element, $path) use (&$exists, &$key, $strict) {
                    if ($strict && $keyElement === $key || !$strict && $keyElement == $key) {
                        if (!is_array($exists)) {
                            $exists = [];
                        }

                        $path[] = $key;

                        $exists[] = $path;
                    }
                }
            );

            if ($maxDepth > 0 && $exists !== false) {
                foreach ($exists as $existKey => $exist) {
                    if (count($exist) > $maxDepth) {
                        unset($exists[$existKey]);
                    }
                }
                $exists = array_values($exists);
            }

            return $exists;
        }

        /**
         * Return array of paths (array of keys) to element of array which value is equals to $value, false otherwise
         * Searching processed all elements recursively
         * If $strict is true it compares in strict mode === instead of ==
         *
         * @param      $value
         * @param bool $strict
         *
         * @return bool
         */
        public function isValueExists($value, $strict = true, $maxDepth = 0)
        {
            $exists = false;
            $this->walk(
                function ($key, $element, $path) use (&$exists, &$value, $strict) {
                    if ($strict && $element === $value || !$strict && $element == $value) {
                        if (!is_array($exists)) {
                            $exists = [];
                        }

                        $path[] = $key;

                        $exists[] = $path;
                    }
                }
            );

            if ($maxDepth > 0 && $exists !== false) {
                foreach ($exists as $existKey => $exist) {
                    if (count($exist) > $maxDepth) {
                        unset($exists[$existKey]);
                    }
                }
                $exists = array_values($exists);
            }

            return $exists;
        }

        /**
         * Replaced all keys in single-demension array with indexes starting from $startIndex
         *
         * @param int $startIndex
         *
         * @return $this
         */
        public function convertToIndexed($startIndex = 0)
        {
            $indexed = [];
            foreach ($this->array as $item) {
                if (empty($indexed)) {
                    $indexed[$startIndex] = $item;
                } else {
                    $indexed[] = $item;
                }
            }

            $this->array = $indexed;

            return $this;
        }

        /**
         * Split array to $numberOfParts parts
         *
         * @param $numberOfParts
         *
         * @return \Zver\ArrayHelper
         */
        public function splitParts($numberOfParts)
        {

            $partition = [];
            $offset = $sliceLength = 0;
            $partsLength = floor(count($this->array) / $numberOfParts);
            $partsRest = count($this->array) % $numberOfParts;

            for ($countIndex = 0; $countIndex < $numberOfParts; $countIndex++) {
                $sliceLength = ($countIndex < $partsRest)
                    ? $partsLength + 1
                    : $partsLength;
                $partition[$countIndex] = array_slice($this->array, $offset, $sliceLength);
                $offset += $sliceLength;
            }

            return $this->set($partition);
        }

        /**
         * Implementation of IteratorAggregade
         *
         * @return \ArrayIterator
         */
        public function getIterator()
        {
            return new \ArrayIterator($this->array);
        }

        /**
         * Implementation of ArrayAccess
         *
         * @param mixed $offset
         *
         * @return bool
         */
        public function offsetExists($offset)
        {
            return isset($this->array[$offset]);
        }

        /**
         * @param mixed $offset
         *
         * Implementation of ArrayAccess
         *
         * @return mixed|null
         */
        public function offsetGet($offset)
        {
            return isset($this->array[$offset])
                ? $this->array[$offset]
                : null;
        }

        /**
         * Implementation of ArrayAccess
         *
         * @param mixed $offset
         * @param mixed $value
         */
        public function offsetSet($offset, $value)
        {
            if (is_null($offset)) {
                $this->array[] = $value;
            } else {
                $this->array[$offset] = $value;
            }
        }

        /**
         * Implementation of ArrayAccess
         *
         * @param mixed $offset
         */
        public function offsetUnset($offset)
        {
            unset($this->array[$offset]);
        }

        /**
         * Apply callback to every element of loaded array.
         * Keys preserved.
         * No recursion.
         *
         * @param $callback
         *
         * @return $this
         */
        public function map($callback)
        {
            foreach ($this->array as $key => $value) {
                $this->array[$key] = $callback($key, $value);
            }

            return $this;
        }

        /**
         * Apply callback to every element of loaded array, if callback returns false - element with key will be unset.
         * Keys preserved.
         * No recursion.
         *
         * @param $callback
         *
         * @return $this
         */
        public function filter($callback)
        {
            foreach ($this->array as $key => $value) {
                if ($callback($key, $value) === false) {
                    unset($this->array[$key]);
                }
            }

            return $this;
        }

        /**
         * Get only one key column from multi-dimensional array
         *
         * @param string $column
         *
         * @return \Zver\ArrayHelper
         */
        public function column($column)
        {
            $columnArray = [];

            foreach ($this->array as $values) {
                if (is_array($values)) {

                    foreach ($values as $key => $value) {
                        if ($key == $column) {
                            $columnArray[] = $value;
                        }
                    }
                }
            }

            return $this->setArray($columnArray)
                        ->setArray($this->getValues());
        }

        /**
         * Insert element into beginning of array
         *
         * @param $element
         *
         * @return \Zver\ArrayHelper
         */
        public function insertFirst($element)
        {
            return $this->setArray(array_merge([$element], $this->array));
        }

        /**
         * Insert element into end of array
         *
         * @param $element
         *
         * @return $this
         */
        public function insertLast($element)
        {
            $this->array[] = $element;

            return $this;
        }

        /**
         * Get $length number of elements started from $offset
         *
         * @param $offset
         * @param $length
         *
         * @return $this
         */
        public function slice($offset, $length)
        {
            $this->array = array_slice($this->array, $offset, $length, true);

            return $this;
        }

        public function sliceFromCenter($centerIndex, $itemsPerSide)
        {
            $itemsPerSide = intval($itemsPerSide);

            /**
             * Check items per side is correct
             */
            if ($itemsPerSide < 1) {
                throw new \Exception('Items per side must be >=1 ');
            }

            /**
             * Check center index is exists
             */
            if (!$this->isKeyExists($centerIndex)) {
                throw new \Exception('Center index="' . $centerIndex . '" is not exists');
            }

            /**
             * Check slice is needed
             */
            if ($this->length() > $itemsPerSide + $itemsPerSide + 1) {

                $sliceStart = $centerIndex;
                $sliceEnd = $centerIndex;

                $availableSpace = $itemsPerSide + $itemsPerSide;
                $currentInc = 1;

                while ($availableSpace > 0) {
                    if ($this->isKeyExists($centerIndex + $currentInc)) {
                        $availableSpace--;
                        $sliceEnd++;
                    }

                    if ($this->isKeyExists($centerIndex - $currentInc)) {
                        $availableSpace--;
                        $sliceStart--;
                    }

                    $currentInc++;
                }

                return $this->slice($sliceStart, $sliceEnd - $sliceStart + 1)
                            ->convertToIndexed();

            }

            return $this;
        }
    }
}
