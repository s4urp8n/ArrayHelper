<?php

use Zver\ArrayHelper;

class ArrayHelperTest extends PHPUnit\Framework\TestCase
{

    use Package\Test;

    public static $assocArray = [
        'zero'  => 0,
        'one'   => 1,
        'two'   => 2,
        'three' => 3,
    ];

    public static $autoArray = [
        'zero',
        'one',
        'two',
        'three',
    ];

    public static $multiArray = [
        0,
        1,
        4,
        5,
        'multi' => [
            [
                'zero'  => 0,
                'one'   => 1,
                'two'   => 2,
                'three' => [
                    [
                        'zero'  => 0,
                        'one'   => 1,
                        'two'   => 2,
                        'three' => 3,
                    ],
                ],
            ],
            [],
            [
                'zero',
                'one',
                'two',
                'three',
            ],
        ],
        'last',
    ];

    public $assoc = [];
    public $auto = [];
    public $empty = [];
    public $multi = [];

    public function setUp()
    {
        $this->assoc = static::$assocArray;
        $this->auto = static::$autoArray;
        $this->empty = [];
        $this->multi = static::$multiArray;
    }

    public function testRange()
    {
        $this->assertSame(
            ArrayHelper::range(0, 10)
                       ->getArray(), [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
        );

        $this->assertSame(
            ArrayHelper::range(0, 10, 2)
                       ->getArray(), [0, 2, 4, 6, 8, 10]
        );
    }

    public function testLoadReload()
    {
        //load
        $test = ArrayHelper::load($this->assoc);
        $this->assertSame($this->assoc, $test->getArray());

        //set
        $test->set($this->assoc);
        $this->assertSame($this->assoc, $test->getArray());

        //set array
        $test->setArray($this->assoc);
        $this->assertSame($this->assoc, $test->getArray());

        //set array
        $test->replaceArray($this->assoc);
        $this->assertSame($this->assoc, $test->getArray());

    }

    public function testReverse()
    {
        $this->assertNotSame(
            ArrayHelper::load($this->assoc)
                       ->reverse()
                       ->getArray(), ArrayHelper::load($this->assoc)
                                                ->getArray()
        );

        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->reverse()
                       ->getArray(), ArrayHelper::load($this->assoc)
                                                ->reverseKeys()
                                                ->reverseValues()
                                                ->getArray()
        );

        //twice
        $arrays = [$this->multi, $this->assoc, $this->empty, $this->auto];

        foreach ($arrays as $key => $array) {
            $arrayReversed = ArrayHelper::load($array)
                                        ->reverse()
                                        ->reverse()
                                        ->getArray();

            $this->assertSame($array, $arrayReversed);
        }

        //twice combo
        $arrays = [$this->multi, $this->assoc, $this->empty, $this->auto];

        foreach ($arrays as $key => $array) {
            $arrayReversed = ArrayHelper::load($array)
                                        ->reverseKeys()
                                        ->reverseValues()
                                        ->reverseKeys()
                                        ->reverseValues()
                                        ->getArray();

            $this->assertSame($array, $arrayReversed);
        }

        //twice combo order
        $arrays = [$this->multi, $this->assoc, $this->empty, $this->auto];

        foreach ($arrays as $key => $array) {
            $arrayReversed = ArrayHelper::load($array)
                                        ->reverseValues()
                                        ->reverseKeys()
                                        ->reverseKeys()
                                        ->reverseValues()
                                        ->getArray();

            $this->assertSame($array, $arrayReversed);
        }

        //precise test
        $array = $this->assoc;
        $arrayReversed = ArrayHelper::load($this->assoc)
                                    ->reverse()
                                    ->getArray();

        $this->assertSame(array_keys($array), array_reverse(array_keys($arrayReversed)));
        $this->assertSame(array_values($array), array_reverse(array_values($arrayReversed)));

    }

    public function testReverseValues()
    {
        $test = ArrayHelper::load($this->assoc)
                           ->reverseValues()
                           ->getArray();

        $this->assertSame(
            $test, [
                     'zero'  => 3,
                     'one'   => 2,
                     'two'   => 1,
                     'three' => 0,
                 ]
        );

        $test = ArrayHelper::load($this->assoc)
                           ->reverseValues()
                           ->reverseValues()
                           ->getArray();

        $this->assertSame(
            $test, $this->assoc
        );

    }

    public function testReverseKeys()
    {
        $test = ArrayHelper::load($this->assoc)
                           ->reverseKeys()
                           ->getArray();

        $this->assertSame(
            $test, [
                     'three' => 0,
                     'two'   => 1,
                     'one'   => 2,
                     'zero'  => 3,
                 ]
        );

        $test = ArrayHelper::load($this->assoc)
                           ->reverseKeys()
                           ->reverseKeys()
                           ->getArray();

        $this->assertSame(
            $test, $this->assoc
        );

        $test = ArrayHelper::load($this->auto)
                           ->reverseKeys()
                           ->reverseKeys()
                           ->getArray();

        $this->assertSame(
            $test, $this->auto
        );
    }

    public function testGetAt()
    {
        $test = ArrayHelper::load($this->assoc);
        $this->assertSame($test->getAt(0), 0);
        $this->assertSame($test->getAt(1), 1);
        $this->assertSame($test->getAt(2), 2);
        $this->assertSame($test->getAt(3), 3);

        $test = ArrayHelper::load($this->auto);
        $this->assertSame($test->getAt(0), 'zero');
        $this->assertSame($test->getAt(1), 'one');
        $this->assertSame($test->getAt(2), 'two');
        $this->assertSame($test->getAt(3), 'three');
    }

    public function testLoadAndGet()
    {
        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->get(), $this->assoc
        );

        $this->assertSame(
            ArrayHelper::load($this->auto)
                       ->get(), $this->auto
        );

        $this->assertSame(
            ArrayHelper::load($this->empty)
                       ->get(), $this->empty
        );

        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->getArray(), $this->assoc
        );

        $this->assertSame(
            ArrayHelper::load($this->auto)
                       ->getArray(), $this->auto
        );

        $this->assertSame(
            ArrayHelper::load($this->empty)
                       ->getArray(), $this->empty
        );
    }

    public function testKeys()
    {
        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->getKeys(), ['zero', 'one', 'two', 'three']
        );

        $this->assertSame(
            ArrayHelper::load($this->auto)
                       ->getKeys(), [0, 1, 2, 3]
        );

        $this->assertSame(
            ArrayHelper::load($this->empty)
                       ->getKeys(), []
        );
    }

    public function testValues()
    {
        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->getValues(), [0, 1, 2, 3]
        );

        $this->assertSame(
            ArrayHelper::load($this->auto)
                       ->getValues(), $this->auto
        );

        $this->assertSame(
            ArrayHelper::load($this->empty)
                       ->getValues(), []
        );
    }

    public function testEmpty()
    {
        $this->assertFalse(
            ArrayHelper::load($this->assoc)
                       ->isEmpty()
        );

        $this->assertFalse(
            ArrayHelper::load($this->auto)
                       ->isEmpty()
        );

        $this->assertTrue(
            ArrayHelper::load($this->empty)
                       ->isEmpty()
        );
    }

    public function testCount()
    {
        //length
        $this->assertSame(
            ArrayHelper::load($this->empty)
                       ->getLength(), 0
        );
        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->getLength(), 4
        );
        $this->assertSame(
            ArrayHelper::load($this->auto)
                       ->getLength(), 4
        );

        //length
        $this->assertSame(
            ArrayHelper::load($this->empty)
                       ->length(), 0
        );
        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->length(), 4
        );
        $this->assertSame(
            ArrayHelper::load($this->auto)
                       ->length(), 4
        );

        //count
        $this->assertSame(
            ArrayHelper::load($this->empty)
                       ->count(), 0
        );
        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->count(), 4
        );
        $this->assertSame(
            ArrayHelper::load($this->auto)
                       ->count(), 4
        );

        //getCount
        $this->assertSame(
            ArrayHelper::load($this->empty)
                       ->getCount(), 0
        );
        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->getCount(), 4
        );
        $this->assertSame(
            ArrayHelper::load($this->auto)
                       ->getCount(), 4
        );
    }

    public function testGetFirstKey()
    {
        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->getFirstKey(), 'zero'
        );
        $this->assertSame(
            ArrayHelper::load($this->auto)
                       ->getFirstKey(), 0
        );
        $this->assertSame(
            ArrayHelper::load($this->multi)
                       ->getFirstKey(), 0
        );
    }

    public function testGetFirstValue()
    {

        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->getFirstValue(), 0
        );
        $this->assertSame(
            ArrayHelper::load($this->auto)
                       ->getFirstValue(), 'zero'
        );
        $this->assertSame(
            ArrayHelper::load($this->multi)
                       ->getFirstValue(), 0
        );
    }

    public function testFlip()
    {
        $this->assertSame(
            ArrayHelper::load($this->empty)
                       ->flip()
                       ->get(), []
        );
        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->flip()
                       ->get(), ['zero', 'one', 'two', 'three']
        );
        $this->assertSame(
            ArrayHelper::load($this->auto)
                       ->flip()
                       ->get(), ['zero' => 0, 'one' => 1, 'two' => 2, 'three' => 3]
        );

    }

    public function testGetLastKey()
    {
        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->getLastKey(), 'three'
        );
        $this->assertSame(
            ArrayHelper::load($this->auto)
                       ->getLastKey(), 3
        );
        $this->assertSame(
            ArrayHelper::load($this->multi)
                       ->getLastKey(), 4
        );
    }

    public function testGetLastValue()
    {
        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->getLastValue(), 3
        );
        $this->assertSame(
            ArrayHelper::load($this->auto)
                       ->getLastValue(), 'three'
        );
        $this->assertSame(
            ArrayHelper::load($this->multi)
                       ->getLastValue(), 'last'
        );
    }

    public function testWalkEmpty()
    {
        $test = ArrayHelper::load($this->multi)
                           ->walk(
                               function () {
                               }
                           )
                           ->getArray();

        $this->assertSame($test, $this->multi);
    }

    public function getFlattenPreserveKeysMulti()
    {
        return [
            0,
            1,
            4,
            5,
            'zero'  => 0,
            'one'   => 1,
            'two'   => 2,
            'three' => 3,
            'zero',
            'one',
            'two',
            'three',
            'last',
        ];
    }

    public function testWalkFlatten()
    {
        $flatten = [];
        $test = ArrayHelper::load($this->multi)
                           ->walk(
                               function ($key, $element) use (&$flatten) {
                                   if (!is_array($element)) {
                                       $flatten[$key] = $element;
                                   }
                               }
                           )
                           ->getArray();

        $this->assertSame(
            $flatten, $this->getFlattenMulti()
        );
    }

    public function getFlattenMulti()
    {
        return [
            0       => 'zero',
            1       => 'one',
            2       => 'two',
            3       => 'three',
            'zero'  => 0,
            'one'   => 1,
            'two'   => 2,
            'three' => 3,
            4       => 'last',
        ];
    }

    public function testImplode()
    {
        //empty
        $imploded = '01450120123zeroonetwothreelast';

        $this->assertSame(
            ArrayHelper::load($this->multi)
                       ->implode(), $imploded
        );

        //space
        $imploded = '0 1 4 5 0 1 2 0 1 2 3 zero one two three last';

        $this->assertSame(
            ArrayHelper::load($this->multi)
                       ->implode(' '), $imploded
        );

        //double dash
        $imploded = '0--1--4--5--0--1--2--0--1--2--3--zero--one--two--three--last';

        $this->assertSame(
            ArrayHelper::load($this->multi)
                       ->implode('--'), $imploded
        );

        //join

        //empty
        $imploded = '01450120123zeroonetwothreelast';

        $this->assertSame(
            ArrayHelper::load($this->multi)
                       ->join(), $imploded
        );

        //space
        $imploded = '0 1 4 5 0 1 2 0 1 2 3 zero one two three last';

        $this->assertSame(
            ArrayHelper::load($this->multi)
                       ->join(' '), $imploded
        );

        //double dash
        $imploded = '0--1--4--5--0--1--2--0--1--2--3--zero--one--two--three--last';

        $this->assertSame(
            ArrayHelper::load($this->multi)
                       ->join('--'), $imploded
        );

        //with depth

        $imploded = '0--1--4--5--last';

        $this->assertSame(
            ArrayHelper::load($this->multi)
                       ->join('--', 1), $imploded
        );

        $imploded = '0--1--4--5--0--1--2--last';

        $this->assertSame(
            ArrayHelper::load($this->multi)
                       ->join('--', 2), $imploded
        );
    }

    public function testClone()
    {
        $test = ArrayHelper::load($this->multi);
        $clone = $test->getClone();

        $this->assertSame($test->get(), $clone->get());
        $this->assertSame($test->getKeys(), $clone->getKeys());
        $this->assertSame($test->getValues(), $clone->getValues());

        //change one
        //not same
        $clone->reverseKeys();
        $this->assertNotSame($test->get(), $clone->get());
        $this->assertNotSame($test->getKeys(), $clone->getKeys());
        //not same
        $clone->reverseValues();
        $this->assertNotSame($test->getValues(), $clone->getValues());

        //same again
        $clone->reverse();
        $this->assertSame($test->get(), $clone->get());
        $this->assertSame($test->getKeys(), $clone->getKeys());
        $this->assertSame($test->getValues(), $clone->getValues());
    }

    public function testIterator()
    {
        //auto
        $testForeach = '';
        $testArr = '';

        foreach ($this->auto as $element) {
            $testForeach .= $element;
        }

        foreach (ArrayHelper::load($this->auto) as $element) {
            $testArr .= $element;
        }

        $this->assertSame($testArr, $testForeach);

        //assoc
        $testForeach = '';
        $testArr = '';

        foreach ($this->assoc as $element) {
            $testForeach .= $element;
        }

        foreach (ArrayHelper::load($this->assoc) as $element) {
            $testArr .= $element;
        }

        $this->assertSame($testArr, $testForeach);
    }

    public function testArrayAccess()
    {
        $test = ArrayHelper::load($this->assoc);
        $this->assertSame($test->get(), $this->assoc);

        //change
        $test['zero'] = 1;

        $this->assertSame(
            $test->get(), [
                            'zero'  => 1,
                            'one'   => 1,
                            'two'   => 2,
                            'three' => 3,
                        ]
        );

        //isset
        $this->assertFalse(isset($test['five']));

        //unset
        unset($test['zero']);

        $this->assertSame(
            $test->get(), [
                            'one'   => 1,
                            'two'   => 2,
                            'three' => 3,
                        ]
        );

        //add
        $test[] = 5;
        $this->assertSame(
            $test->get(), [
                            'one'   => 1,
                            'two'   => 2,
                            'three' => 3,
                            0       => 5,
                        ]
        );

        //add assoc
        $test['five'] = 5;
        $this->assertSame(
            $test->get(), [
                            'one'   => 1,
                            'two'   => 2,
                            'three' => 3,
                            0       => 5,
                            'five'  => 5,
                        ]
        );

        //add to empty
        $test->set([]);
        $this->assertSame($test->get(), []);

        $test[] = 5;
        $this->assertSame($test->get(), [5]);

        //get key
        $this->assertSame($test[0], 5);

    }

    public function testExceptions()
    {

        $this->assertException(
            function () {
                ArrayHelper::load([1, 2, 3])
                           ->getAt(5);
            }
            , \Zver\Exceptions\ArrayHelper\UndefinedOffsetException::class
        );

        $this->assertException(
            function () {
                ArrayHelper::load()
                           ->getAt(5);
            }
            , \Zver\Exceptions\ArrayHelper\EmptyArrayException::class
        );

        $this->assertException(
            function () {
                ArrayHelper::load()
                           ->getFirstKey();
            }
            , \Zver\Exceptions\ArrayHelper\EmptyArrayException::class
        );

        $this->assertException(
            function () {
                ArrayHelper::load()
                           ->getFirstValue();
            }
            , \Zver\Exceptions\ArrayHelper\EmptyArrayException::class
        );

        $this->assertException(
            function () {
                ArrayHelper::load()
                           ->getLastKey();
            }
            , \Zver\Exceptions\ArrayHelper\EmptyArrayException::class
        );

        $this->assertException(
            function () {
                ArrayHelper::load()
                           ->getLastValue();
            }
            , \Zver\Exceptions\ArrayHelper\EmptyArrayException::class
        );

        $this->assertException(
            function () {
                ArrayHelper::load()
                           ->getFirstValueUnset();
            }
            , \Zver\Exceptions\ArrayHelper\EmptyArrayException::class
        );

        $this->assertException(
            function () {
                ArrayHelper::load()
                           ->getLastValueUnset();
            }
            , \Zver\Exceptions\ArrayHelper\EmptyArrayException::class
        );
    }

    public function testKeyExists()
    {
        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->isKeyExists('one'), [['one']]
        );

        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->isKeyExists('two'), [['two']]
        );

        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->isKeyExists('three'), [['three']]
        );

        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->isKeyExists('zero'), [['zero']]
        );

        $this->assertFalse(
            ArrayHelper::load($this->assoc)
                       ->isKeyExists('one1')
        );

        $this->assertFalse(
            ArrayHelper::load($this->assoc)
                       ->isKeyExists('two1')
        );

        $this->assertFalse(
            ArrayHelper::load($this->assoc)
                       ->isKeyExists('three1')
        );

        $this->assertFalse(
            ArrayHelper::load($this->assoc)
                       ->isKeyExists('zero1')
        );

        for ($i = -10; $i < 10; $i++) {
            $this->assertFalse(
                ArrayHelper::load($this->assoc)
                           ->isKeyExists($i)
            );
        }

        $this->assertSame(
            ArrayHelper::load($this->multi)
                       ->isKeyExists('zero'), [
                ['multi', 0, 'zero'],
                ['multi', 0, 'three', 0, 'zero'],
            ]
        );

        //with max depth
        $this->assertSame(
            ArrayHelper::load($this->multi)
                       ->isKeyExists('zero', true, 1), []
        );

        $this->assertSame(
            ArrayHelper::load($this->multi)
                       ->isKeyExists('zero', true, 2), []
        );

        $this->assertSame(
            ArrayHelper::load($this->multi)
                       ->isKeyExists('zero', true, 3), [
                ['multi', 0, 'zero'],
            ]
        );

        $this->assertSame(
            ArrayHelper::load($this->multi)
                       ->isKeyExists('zero', true, 4), [
                ['multi', 0, 'zero'],
            ]
        );

        $this->assertSame(
            ArrayHelper::load($this->multi)
                       ->isKeyExists('zero', true, 5), [
                ['multi', 0, 'zero'],
                ['multi', 0, 'three', 0, 'zero'],
            ]
        );
    }

    public function testValueExists()
    {
        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->isValueExists(3), [['three']]
        );

        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->isValueExists(3, true), [['three']]
        );

        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->isValueExists('3', false), [['three']]
        );

        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->isValueExists('3', true), false
        );

        $this->assertSame(
            ArrayHelper::load([0, 0, 0, 0])
                       ->isValueExists(0), [[0], [1], [2], [3]]
        );

        $this->assertSame(
            ArrayHelper::load($this->multi)
                       ->isValueExists(0), [
                [0],
                ['multi', 0, 'zero'],
                ['multi', 0, 'three', 0, 'zero'],
            ]
        );

        $this->assertSame(
            ArrayHelper::load([])
                       ->isValueExists(0), false
        );

        $this->assertSame(
            ArrayHelper::load([])
                       ->isValueExists(0, false), false
        );

        $this->assertSame(
            ArrayHelper::load(
                [
                    0,
                    [
                        0,
                        [
                            0,
                            [
                                [
                                    [
                                        [
                                            [
                                                [
                                                    [
                                                        [
                                                            0,
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]
            )
                       ->isValueExists(0), [
                [0],
                [1, 0],
                [1, 1, 0],
                [1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0],
            ]
        );

        //with max depth
        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->isValueExists(3, true, 2), [['three']]
        );

        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->isValueExists(3, true, 2), [['three']]
        );

        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->isValueExists('3', false, 2), [['three']]
        );

        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->isValueExists('3', true, 2), false
        );

        $this->assertSame(
            ArrayHelper::load([0, 0, 0, 0])
                       ->isValueExists(0, true, 2), [[0], [1], [2], [3]]
        );

        $this->assertSame(
            ArrayHelper::load($this->multi)
                       ->isValueExists(0, true, 1), [
                [0],
                //['multi', 0, 'zero'],
                //['multi', 0, 'three', 0, 'zero'],
            ]
        );

        $this->assertSame(
            ArrayHelper::load($this->multi)
                       ->isValueExists(0, true, 2), [
                [0],
            ]
        );

        $this->assertSame(
            ArrayHelper::load($this->multi)
                       ->isValueExists(0, true, 3), [
                [0],
                ['multi', 0, 'zero'],
                //['multi', 0, 'three', 0, 'zero'],
            ]
        );

        $this->assertSame(
            ArrayHelper::load($this->multi)
                       ->isValueExists(0, true, 4), [
                [0],
                ['multi', 0, 'zero'],
                //['multi', 0, 'three', 0, 'zero'],
            ]
        );

        $this->assertSame(
            ArrayHelper::load($this->multi)
                       ->isValueExists(0, true, 5), [
                [0],
                ['multi', 0, 'zero'],
                ['multi', 0, 'three', 0, 'zero'],
            ]
        );

        $this->assertSame(
            ArrayHelper::load($this->multi)
                       ->isValueExists(0, true, 6), [
                [0],
                ['multi', 0, 'zero'],
                ['multi', 0, 'three', 0, 'zero'],
            ]
        );

        $this->assertSame(
            ArrayHelper::load([])
                       ->isValueExists(0, false, 1), false
        );

    }

    public function testConvertToIndexed()
    {
        $this->assertSame(
            ArrayHelper::load([])
                       ->convertToIndexed(0)
                       ->getArray(), []
        );

        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->convertToIndexed(0)
                       ->getArray(), [0, 1, 2, 3]
        );

        //start index
        for ($i = -1; $i < 10; $i++) {
            $this->assertSame(
                ArrayHelper::load($this->assoc)
                           ->convertToIndexed($i)
                           ->getArray(), [
                    $i => 0,
                    1,
                    2,
                    3,
                ]
            );
        }
    }

    public function testFirstKeyUnset()
    {
        $arr = ArrayHelper::load($this->assoc);
        $this->assertSame(
            $arr->getCount(), ArrayHelper::load($this->assoc)
                                         ->count()
        );
        $this->assertSame($arr->getFirstValueUnset(), 0);
        $this->assertSame(
            $arr->getCount(), ArrayHelper::load($this->assoc)
                                         ->count() - 1
        );

        $arr->replaceArray($this->auto);
        $this->assertSame(
            $arr->getCount(), ArrayHelper::load($this->auto)
                                         ->count()
        );
        $this->assertSame($arr->getFirstValueUnset(), 'zero');
        $this->assertSame(
            $arr->getCount(), ArrayHelper::load($this->auto)
                                         ->count() - 1
        );

        $arr->replaceArray($this->multi);
        $this->assertSame(
            $arr->getCount(), ArrayHelper::load($this->multi)
                                         ->count()
        );
        $this->assertSame($arr->getFirstValueUnset(), 0);
        $this->assertSame(
            $arr->getCount(), ArrayHelper::load($this->multi)
                                         ->count() - 1
        );
    }

    public function testLastKeyUnset()
    {
        $arr = ArrayHelper::load($this->assoc);
        $this->assertSame(
            $arr->getCount(), ArrayHelper::load($this->assoc)
                                         ->count()
        );
        $this->assertSame($arr->getLastValueUnset(), 3);
        $this->assertSame(
            $arr->getCount(), ArrayHelper::load($this->assoc)
                                         ->count() - 1
        );

        $arr->replaceArray($this->auto);
        $this->assertSame(
            $arr->getCount(), ArrayHelper::load($this->auto)
                                         ->count()
        );
        $this->assertSame($arr->getLastValueUnset(), 'three');
        $this->assertSame(
            $arr->getCount(), ArrayHelper::load($this->auto)
                                         ->count() - 1
        );

        $arr->replaceArray($this->multi);
        $this->assertSame(
            $arr->getCount(), ArrayHelper::load($this->multi)
                                         ->count()
        );
        $this->assertSame($arr->getLastValueUnset(), 'last');
        $this->assertSame(
            $arr->getCount(), ArrayHelper::load($this->multi)
                                         ->count() - 1
        );
    }

    public function testSplitParts()
    {
        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->splitParts(2)
                       ->getArray(), [
                ['zero' => 0, 'one' => 1],
                ['two' => 2, 'three' => 3],
            ]
        );

        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->splitParts(1)
                       ->getArray(), [
                [
                    'zero'  => 0,
                    'one'   => 1,
                    'two'   => 2,
                    'three' => 3,
                ],
            ]
        );

        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->splitParts(3)
                       ->getArray(), [
                ['zero' => 0, 'one' => 1],
                ['two' => 2,],
                ['three' => 3],
            ]
        );

        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->splitParts(4)
                       ->getArray(), [
                ['zero' => 0],
                ['one' => 1],
                ['two' => 2,],
                ['three' => 3],
            ]
        );

        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->splitParts(5)
                       ->getArray(), [
                ['zero' => 0],
                ['one' => 1],
                ['two' => 2,],
                ['three' => 3],
                [],
            ]
        );

        $this->assertSame(
            ArrayHelper::load($this->assoc)
                       ->splitParts(6)
                       ->getArray(), [
                ['zero' => 0],
                ['one' => 1],
                ['two' => 2,],
                ['three' => 3],
                [],
                [],
            ]
        );

        $this->assertSame(
            ArrayHelper::load([])
                       ->splitParts(6)
                       ->getArray(), [
                [],
                [],
                [],
                [],
                [],
                [],
            ]
        );
    }

    public function combineTest()
    {
        $keys = [
            '1' => 'XL',
            '2' => 'L',
            '3' => 'S',
            '4' => 'M',
            '6' => 'XS',
        ];

        $values = [
            '1' => 1200,
            '2' => 700,
            '3' => 670,
            '4' => 450,
            '6' => 300,
            '7' => '',
        ];

        $combined = [
            'XL' => 1200,
            'L'  => 700,
            'S'  => 670,
            'M'  => 450,
            'XS' => 300,
        ];

        $this->assertSame(
            ArrayHelper::combine($keys, $values)
                       ->getArray(), $combined
        );
    }

    public function explodeTest()
    {
        $this->assertSame(
            explode('/', 'id/id/id/id'), ['id', 'id', 'id', 'id']
        );

        $this->assertSame(
            ArrayHelper::explode('/', 'id/id/id/id')
                       ->getArray(), ['id', 'id', 'id', 'id']
        );

        $this->assertSame(
            ArrayHelper::explode('/', '/id/id/id/id//')
                       ->getArray(), [
                '',
                'id',
                'id',
                'id',
                'id',
                '',
                '',
            ]
        );

        $this->assertSame(
            ArrayHelper::explode('.', '/id/id/id/id//')
                       ->getArray(), [
                '/id/id/id/id//',
            ]
        );
    }

    public function mapTest()
    {
        $data = [
            [
                'original' => [],
                'callback' => function ($key, $value) {
                    return $value;
                },
                'mapped'   => [],
            ],
            [
                'original' => [1, 2, 3, 4, 5],
                'callback' => function ($key, $value) {
                    return $value * 2;
                },
                'mapped'   => [2, 4, 6, 8, 10],
            ],
            [
                'original' => [1, 2, 3, 4, 5],
                'callback' => function ($key, $value) {
                    return $value + $key;
                },
                'mapped'   => [1, 3, 5, 7, 9],
            ],
        ];

        foreach ($data as $item) {
            $this->assertSame(
                ArrayHelper::load($item['original'])
                           ->map($item['callback'])
                           ->getArray(), $item['mapped']
            );
        }
    }

    public function testFilter()
    {
        $data = [
            [
                'original' => [],
                'callback' => function ($key, $value) {
                    return $value;
                },
                'filtered' => [],
            ],
            [
                'original' => [1, 2, 3, 4, 5],
                'callback' => function ($key, $value) {
                    return $value < 10;
                },
                'filtered' => [1, 2, 3, 4, 5],
            ],
            [
                'original' => [1, 2, 3, 4, 5],
                'callback' => function ($key, $value) {
                    return $value > 3;
                },
                'filtered' => [3 => 4, 5],
            ],
            [
                'original' => [1, 2, 3, 4, 5],
                'callback' => function ($key, $value) {
                    return false;
                },
                'filtered' => [],
            ],
        ];

        foreach ($data as $item) {
            $this->assertSame(
                ArrayHelper::load($item['original'])
                           ->filter($item['callback'])
                           ->getArray(), $item['filtered']
            );
        }
    }

    public function testColumn()
    {
        $original = [
            ['key' => 1, 'key2' => 25],
            ['key' => 2, 'key2' => 2435],
            ['key' => 3, 'key52' => 2123],
            ['key' => 4, 'key22' => 244],
            ['key' => 5, 'key42' => 233],
            ['key' => 6, 'key22' => 2333],
            ['key' => 7, 'key22' => 2667],
        ];

        $array = ArrayHelper::load($original);

        $this->assertSame(
            $array->get(), $original
        );

        $this->assertSame(
            $array->column('key')
                  ->get(), [1, 2, 3, 4, 5, 6, 7]
        );

        $this->assertNotSame(
            $array->get(), $original
        );

        $this->assertSame(
            ArrayHelper::load($original)
                       ->column('key2')
                       ->get(), [25, 2435]
        );

        $this->assertSame(
            ArrayHelper::load($original)
                       ->column('key888888')
                       ->get(), []
        );

        $this->assertSame(
            ArrayHelper::load($original)
                       ->column('key22')
                       ->get(), [244, 2333, 2667]
        );

        $this->assertSame(
            ArrayHelper::load($original)
                       ->column('key22')
                       ->column('key')
                       ->get(), []
        );

        $this->assertSame(
            ArrayHelper::load($original)
                       ->column('key22')
                       ->column('key')
                       ->column('key')
                       ->column('key')
                       ->column('key')
                       ->column('key')
                       ->column('key')
                       ->column('key')
                       ->column('key')
                       ->column('key')
                       ->column('key')
                       ->column('key')
                       ->column('key')
                       ->column('key')
                       ->column('key')
                       ->get(), []
        );
    }

    public function testMultiColumn()
    {
        $original = [
            [
                'key'  => [
                    'key' => [
                        'key' => 1,
                    ],
                ],
                'key2' => 25,
            ],
            [
                'key'  => [
                    'key' => [
                        'key' => 1,
                    ],
                ],
                'key2' => 2435,
            ],
            [
                'key'   => [
                    'key' => [
                        'key' => 1,
                    ],
                ],
                'key52' => 2123,
            ],
            [
                'key'   => [
                    'key' => [
                        'key' => 1,
                    ],
                ],
                'key22' => 244,
            ],
            [
                'key'   => [
                    'key' => [
                        'key' => 1,
                    ],
                ],
                'key42' => 233,
            ],
            [
                'key'   => [
                    'key' => [
                        'key2' => 2,
                    ],
                ],
                'key22' => 2333,
            ],
            [
                'key'   => [
                    'key' => [
                        'key' => 1,
                    ],
                ],
                'key22' => 2667,
            ],
        ];

        $this->assertSame(
            ArrayHelper::load($original)
                       ->column('key')
                       ->column('key')
                       ->column('key')
                       ->get(), [1, 1, 1, 1, 1, 1]
        );

        $this->assertSame(
            ArrayHelper::load($original)
                       ->column('key')
                       ->column('key')
                       ->column('key2')
                       ->get(), [2]
        );

        $this->assertSame(
            ArrayHelper::load($original)
                       ->column('key22')
                       ->get(), [244, 2333, 2667]
        );

        $this->assertSame(
            ArrayHelper::load($original)
                       ->column('key22')
                       ->column('ddwd')
                       ->get(), []
        );

    }

    public function testSlice()
    {
        $load = [
            'el1' => 0,
            'el2' => 2,
            'el3' => 3,
            'el4' => 4,
            'el5' => 5,
            'el6' => 6,
            'el7' => 7,
        ];

        $ah = ArrayHelper::load($load);

        $this->assertSame(
            $ah->slice(0, 100)
               ->get(), $load
        );

        $ah->setArray($load);

        $this->assertSame(
            $ah->slice(10, 100)
               ->get(), []
        );

        $ah->setArray($load);

        $this->assertSame(
            $ah->slice(0, 1)
               ->get(), ['el1' => 0]
        );

        $ah->setArray($load);

        $this->assertSame(
            $ah->slice(0, 3)
               ->get(), [
                'el1' => 0,
                'el2' => 2,
                'el3' => 3,
            ]
        );

        $ah->setArray($load);

        $this->assertSame(
            $ah->slice(2, 3)
               ->get(), [
                'el3' => 3,
                'el4' => 4,
                'el5' => 5,
            ]
        );
    }

    public function testInsertFirstLast()
    {
        $ah = ArrayHelper::load([9])
                         ->insertFirst(8)
                         ->insertFirst(7)
                         ->insertFirst(6)
                         ->insertFirst(5)
                         ->insertFirst(4)
                         ->insertFirst(3)
                         ->insertFirst(2)
                         ->insertFirst(1)
                         ->insertFirst(0);

        $this->assertSame($ah->get(), [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]);

        $ah = ArrayHelper::load([])
                         ->insertFirst(9)
                         ->insertFirst(8)
                         ->insertFirst(7)
                         ->insertFirst(6)
                         ->insertFirst(5)
                         ->insertFirst(4)
                         ->insertFirst(3)
                         ->insertFirst(2)
                         ->insertFirst(1)
                         ->insertFirst(0);

        $this->assertSame($ah->get(), [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]);

        $ah = ArrayHelper::load([0])
                         ->insertLast(1)
                         ->insertLast(2)
                         ->insertLast(3)
                         ->insertLast(4)
                         ->insertLast(5)
                         ->insertLast(6)
                         ->insertLast(7)
                         ->insertLast(8)
                         ->insertLast(9);

        $this->assertSame($ah->get(), [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]);

        $ah = ArrayHelper::load([])
                         ->insertLast(0)
                         ->insertLast(1)
                         ->insertLast(2)
                         ->insertLast(3)
                         ->insertLast(4)
                         ->insertLast(5)
                         ->insertLast(6)
                         ->insertLast(7)
                         ->insertLast(8)
                         ->insertLast(9);

        $this->assertSame($ah->get(), [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]);

        $ah = ArrayHelper::load([])
                         ->insertLast(5)
                         ->insertFirst(4)
                         ->insertLast(6)
                         ->insertLast(7)
                         ->insertFirst(3)
                         ->insertFirst(2)
                         ->insertLast(8)
                         ->insertFirst(1)
                         ->insertFirst(0)
                         ->insertLast(9);

        $this->assertSame($ah->get(), [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]);
    }

    public function testSideIndexesExceptions3()
    {
        $this->expectException(Exception::class);
        ArrayHelper::load([0])
                   ->sliceFromCenter(0, 'string');
    }

    public function testSideIndexesExceptions2()
    {
        $this->expectException(Exception::class);
        ArrayHelper::load([0])
                   ->sliceFromCenter(0, -3);
    }

    public function testSideIndexesExceptions()
    {
        $this->expectException(Exception::class);

        ArrayHelper::load([])
                   ->sliceFromCenter(-1, 3);

    }

    public function testSideIndexes()
    {

        $testData = [
            [
                'source'       => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                'centerIndex'  => 0,
                'itemsPerSide' => 3,
                'result'       => [0, 1, 2, 3, 4, 5, 6],
            ],
            [
                'source'       => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                'centerIndex'  => 1,
                'itemsPerSide' => 3,
                'result'       => [0, 1, 2, 3, 4, 5, 6],
            ],
            [
                'source'       => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                'centerIndex'  => 2,
                'itemsPerSide' => 3,
                'result'       => [0, 1, 2, 3, 4, 5, 6],
            ],
            [
                'source'       => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                'centerIndex'  => 3,
                'itemsPerSide' => 3,
                'result'       => [0, 1, 2, 3, 4, 5, 6],
            ],
            [
                'source'       => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                'centerIndex'  => 4,
                'itemsPerSide' => 3,
                'result'       => [1, 2, 3, 4, 5, 6, 7],
            ],
            [
                'source'       => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                'centerIndex'  => 5,
                'itemsPerSide' => 3,
                'result'       => [2, 3, 4, 5, 6, 7, 8],
            ],
            [
                'source'       => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                'centerIndex'  => 6,
                'itemsPerSide' => 3,
                'result'       => [3, 4, 5, 6, 7, 8, 9],
            ],
            [
                'source'       => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                'centerIndex'  => 7,
                'itemsPerSide' => 3,
                'result'       => [3, 4, 5, 6, 7, 8, 9],
            ],
            [
                'source'       => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                'centerIndex'  => 8,
                'itemsPerSide' => 3,
                'result'       => [3, 4, 5, 6, 7, 8, 9],
            ],
            [
                'source'       => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                'centerIndex'  => 9,
                'itemsPerSide' => 3,
                'result'       => [3, 4, 5, 6, 7, 8, 9],
            ],

            [
                'source'       => [0, 1, 2],
                'centerIndex'  => 0,
                'itemsPerSide' => 1,
                'result'       => [0, 1, 2],
            ],
            [
                'source'       => [0, 1, 2],
                'centerIndex'  => 1,
                'itemsPerSide' => 1,
                'result'       => [0, 1, 2],
            ],
            [
                'source'       => [0, 1, 2],
                'centerIndex'  => 2,
                'itemsPerSide' => 1,
                'result'       => [0, 1, 2],
            ],

            [
                'source'       => [0, 1, 2, 3],
                'centerIndex'  => 0,
                'itemsPerSide' => 1,
                'result'       => [0, 1, 2],
            ],

            [
                'source'       => [0, 1, 2, 3],
                'centerIndex'  => 1,
                'itemsPerSide' => 1,
                'result'       => [0, 1, 2],
            ],
            [
                'source'       => [0, 1, 2, 3],
                'centerIndex'  => 2,
                'itemsPerSide' => 1,
                'result'       => [1, 2, 3],
            ],
            [
                'source'       => [0, 1, 2, 3],
                'centerIndex'  => 3,
                'itemsPerSide' => 1,
                'result'       => [1, 2, 3],
            ],
        ];

        foreach ($testData as $test) {
            $this->assertSame(
                ArrayHelper::load($test['source'])
                           ->sliceFromCenter($test['centerIndex'], $test['itemsPerSide'])
                           ->get(), $test['result']
            );
        }

    }

    public function testExplode()
    {
        $this->foreachSame([
                               [
                                   ArrayHelper::explode(' ', 'e x p l o d e')
                                              ->get(),
                                   [
                                       'e',
                                       'x',
                                       'p',
                                       'l',
                                       'o',
                                       'd',
                                       'e',
                                   ],
                               ],
                               [
                                   ArrayHelper::explode(' ', 'e x p l  o d e')
                                              ->get(),
                                   [
                                       'e',
                                       'x',
                                       'p',
                                       'l',
                                       '',
                                       'o',
                                       'd',
                                       'e',
                                   ],
                               ],
                           ]);
    }

    public function testCombine()
    {
        $keys = [
            'XL',
            'L',
            'S',
            'M',
            'XS',
        ];

        $values = [
            '1200',
            '700',
            '670',
            '450',
            '300',
            '',
        ];

        $combined = [
            "XL" => '1200',
            "L"  => '700',
            "S"  => '670',
            "M"  => '450',
            "XS" => '300',
        ];

        $this->foreachSame([
                               [
                                   ArrayHelper::combine($keys, $values)
                                              ->get(),
                                   $combined,
                               ],
                           ]);
    }

    public function testMap()
    {
        $this->foreachSame([
                               [
                                   ArrayHelper::load([1, 2, 3])
                                              ->map(function ($value) {
                                                  return $value + $value;
                                              })
                                              ->get(),
                                   [2, 4, 6],
                               ],
                               [
                                   ArrayHelper::load([1, 2, 3])
                                              ->map(function ($value) {
                                                  return $value * 3;
                                              })
                                              ->get(),
                                   [3, 6, 9],
                               ],
                               [
                                   ArrayHelper::load([1, 2, 3])
                                              ->map(function ($value, $key) {
                                                  return $value * 3;
                                              })
                                              ->get(),
                                   [3, 6, 9],
                               ],
                               [
                                   ArrayHelper::load([])
                                              ->map(function ($value) {
                                                  return $value * 3;
                                              })
                                              ->get(),
                                   [],
                               ],
                               [
                                   ArrayHelper::load([])
                                              ->map(function ($value, $key) {
                                                  return $value * 3;
                                              })
                                              ->get(),
                                   [],
                               ],
                               [
                                   ArrayHelper::load([1 => 1, 6 => 6, 9 => 9])
                                              ->map(function ($value, $key) {
                                                  return $key * $value;
                                              })
                                              ->get(),
                                   [1 => 1, 6 => 36, 9 => 81],
                               ],
                           ]);
    }

}
