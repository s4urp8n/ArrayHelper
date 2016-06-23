<?php

use Zver\ArrayHelper;

class ArrayHelperCest
{
    
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
    
    public function _before()
    {
        $this->assoc = static::$assocArray;
        $this->auto = static::$autoArray;
        $this->empty = [];
        $this->multi = static::$multiArray;
    }
    
    public function rangeTest(UnitTester $I)
    {
        $I->assertSame(
            ArrayHelper::range(0, 10)
                       ->getArray(), [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
        );
        
        $I->assertSame(
            ArrayHelper::range(0, 10, 2)
                       ->getArray(), [0, 2, 4, 6, 8, 10]
        );
    }
    
    public function loadReloadTest(UnitTester $I)
    {
        //load
        $test = ArrayHelper::load($this->assoc);
        $I->assertSame($this->assoc, $test->getArray());
        
        //set
        $test->set($this->assoc);
        $I->assertSame($this->assoc, $test->getArray());
        
        //set array
        $test->setArray($this->assoc);
        $I->assertSame($this->assoc, $test->getArray());
        
        //set array
        $test->replaceArray($this->assoc);
        $I->assertSame($this->assoc, $test->getArray());
        
    }
    
    public function reverseTest(UnitTester $I)
    {
        $I->assertNotSame(
            ArrayHelper::load($this->assoc)
                       ->reverse()
                       ->getArray(), ArrayHelper::load($this->assoc)
                                                ->getArray()
        );
        
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->reverse()
                       ->getArray(), ArrayHelper::load($this->assoc)
                                                ->reverseKeys()
                                                ->reverseValues()
                                                ->getArray()
        );
        
        //twice
        $arrays = [$this->multi, $this->assoc, $this->empty, $this->auto];
        
        foreach ($arrays as $key => $array)
        {
            $arrayReversed = ArrayHelper::load($array)
                                        ->reverse()
                                        ->reverse()
                                        ->getArray();
            
            $I->assertSame($array, $arrayReversed);
        }
        
        //twice combo
        $arrays = [$this->multi, $this->assoc, $this->empty, $this->auto];
        
        foreach ($arrays as $key => $array)
        {
            $arrayReversed = ArrayHelper::load($array)
                                        ->reverseKeys()
                                        ->reverseValues()
                                        ->reverseKeys()
                                        ->reverseValues()
                                        ->getArray();
            
            $I->assertSame($array, $arrayReversed);
        }
        
        //twice combo order
        $arrays = [$this->multi, $this->assoc, $this->empty, $this->auto];
        
        foreach ($arrays as $key => $array)
        {
            $arrayReversed = ArrayHelper::load($array)
                                        ->reverseValues()
                                        ->reverseKeys()
                                        ->reverseKeys()
                                        ->reverseValues()
                                        ->getArray();
            
            $I->assertSame($array, $arrayReversed);
        }
        
        //precise test
        $array = $this->assoc;
        $arrayReversed = ArrayHelper::load($this->assoc)
                                    ->reverse()
                                    ->getArray();
        
        $I->assertSame(array_keys($array), array_reverse(array_keys($arrayReversed)));
        $I->assertSame(array_values($array), array_reverse(array_values($arrayReversed)));
        
    }
    
    public function reverseValuesTest(UnitTester $I)
    {
        $test = ArrayHelper::load($this->assoc)
                           ->reverseValues()
                           ->getArray();
        
        $I->assertSame(
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
        
        $I->assertSame(
            $test, $this->assoc
        );
        
    }
    
    public function reverseKeysTest(UnitTester $I)
    {
        $test = ArrayHelper::load($this->assoc)
                           ->reverseKeys()
                           ->getArray();
        
        $I->assertSame(
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
        
        $I->assertSame(
            $test, $this->assoc
        );
        
        $test = ArrayHelper::load($this->auto)
                           ->reverseKeys()
                           ->reverseKeys()
                           ->getArray();
        
        $I->assertSame(
            $test, $this->auto
        );
    }
    
    public function getAtTest(UnitTester $I)
    {
        $test = ArrayHelper::load($this->assoc);
        $I->assertSame($test->getAt(0), 0);
        $I->assertSame($test->getAt(1), 1);
        $I->assertSame($test->getAt(2), 2);
        $I->assertSame($test->getAt(3), 3);
        
        $test = ArrayHelper::load($this->auto);
        $I->assertSame($test->getAt(0), 'zero');
        $I->assertSame($test->getAt(1), 'one');
        $I->assertSame($test->getAt(2), 'two');
        $I->assertSame($test->getAt(3), 'three');
    }
    
    public function loadAndGetTest(UnitTester $I)
    {
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->get(), $this->assoc
        );
        
        $I->assertSame(
            ArrayHelper::load($this->auto)
                       ->get(), $this->auto
        );
        
        $I->assertSame(
            ArrayHelper::load($this->empty)
                       ->get(), $this->empty
        );
        
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->getArray(), $this->assoc
        );
        
        $I->assertSame(
            ArrayHelper::load($this->auto)
                       ->getArray(), $this->auto
        );
        
        $I->assertSame(
            ArrayHelper::load($this->empty)
                       ->getArray(), $this->empty
        );
    }
    
    public function keysTest(UnitTester $I)
    {
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->getKeys(), ['zero', 'one', 'two', 'three']
        );
        
        $I->assertSame(
            ArrayHelper::load($this->auto)
                       ->getKeys(), [0, 1, 2, 3]
        );
        
        $I->assertSame(
            ArrayHelper::load($this->empty)
                       ->getKeys(), []
        );
    }
    
    public function valuesTest(UnitTester $I)
    {
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->getValues(), [0, 1, 2, 3]
        );
        
        $I->assertSame(
            ArrayHelper::load($this->auto)
                       ->getValues(), $this->auto
        );
        
        $I->assertSame(
            ArrayHelper::load($this->empty)
                       ->getValues(), []
        );
    }
    
    public function emptyTest(UnitTester $I)
    {
        $I->assertFalse(
            ArrayHelper::load($this->assoc)
                       ->isEmpty()
        );
        
        $I->assertFalse(
            ArrayHelper::load($this->auto)
                       ->isEmpty()
        );
        
        $I->assertTrue(
            ArrayHelper::load($this->empty)
                       ->isEmpty()
        );
    }
    
    public function countTest(UnitTester $I)
    {
        //length
        $I->assertSame(
            ArrayHelper::load($this->empty)
                       ->getLength(), 0
        );
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->getLength(), 4
        );
        $I->assertSame(
            ArrayHelper::load($this->auto)
                       ->getLength(), 4
        );
        
        //length
        $I->assertSame(
            ArrayHelper::load($this->empty)
                       ->length(), 0
        );
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->length(), 4
        );
        $I->assertSame(
            ArrayHelper::load($this->auto)
                       ->length(), 4
        );
        
        //count
        $I->assertSame(
            ArrayHelper::load($this->empty)
                       ->count(), 0
        );
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->count(), 4
        );
        $I->assertSame(
            ArrayHelper::load($this->auto)
                       ->count(), 4
        );
        
        //getCount
        $I->assertSame(
            ArrayHelper::load($this->empty)
                       ->getCount(), 0
        );
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->getCount(), 4
        );
        $I->assertSame(
            ArrayHelper::load($this->auto)
                       ->getCount(), 4
        );
    }
    
    public function getFirstKeyTest(UnitTester $I)
    {
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->getFirstKey(), 'zero'
        );
        $I->assertSame(
            ArrayHelper::load($this->auto)
                       ->getFirstKey(), 0
        );
        $I->assertSame(
            ArrayHelper::load($this->multi)
                       ->getFirstKey(), 0
        );
    }
    
    public function getFirstValueTest(UnitTester $I)
    {
        
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->getFirstValue(), 0
        );
        $I->assertSame(
            ArrayHelper::load($this->auto)
                       ->getFirstValue(), 'zero'
        );
        $I->assertSame(
            ArrayHelper::load($this->multi)
                       ->getFirstValue(), 0
        );
    }
    
    public function flipTest(UnitTester $I)
    {
        $I->assertSame(
            ArrayHelper::load($this->empty)
                       ->flip()
                       ->get(), []
        );
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->flip()
                       ->get(), ['zero', 'one', 'two', 'three']
        );
        $I->assertSame(
            ArrayHelper::load($this->auto)
                       ->flip()
                       ->get(), ['zero' => 0, 'one' => 1, 'two' => 2, 'three' => 3]
        );
        
    }
    
    public function getLastKeyTest(UnitTester $I)
    {
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->getLastKey(), 'three'
        );
        $I->assertSame(
            ArrayHelper::load($this->auto)
                       ->getLastKey(), 3
        );
        $I->assertSame(
            ArrayHelper::load($this->multi)
                       ->getLastKey(), 4
        );
    }
    
    public function getLastValueTest(UnitTester $I)
    {
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->getLastValue(), 3
        );
        $I->assertSame(
            ArrayHelper::load($this->auto)
                       ->getLastValue(), 'three'
        );
        $I->assertSame(
            ArrayHelper::load($this->multi)
                       ->getLastValue(), 'last'
        );
    }
    
    public function walkEmptyTest(UnitTester $I)
    {
        $test = ArrayHelper::load($this->multi)
                           ->walk(
                               function ()
                               {
                               }
                           )
                           ->getArray();
        
        $I->assertSame($test, $this->multi);
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
    
    public function walkFlattenTest(UnitTester $I)
    {
        $flatten = [];
        $test = ArrayHelper::load($this->multi)
                           ->walk(
                               function ($key, $element) use (&$flatten)
                               {
                                   if (!is_array($element))
                                   {
                                       $flatten[$key] = $element;
                                   }
                               }
                           )
                           ->getArray();
        
        $I->assertSame(
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
    
    public function implodeTest(UnitTester $I)
    {
        //empty
        $imploded = '01450120123zeroonetwothreelast';
        
        $I->assertSame(
            ArrayHelper::load($this->multi)
                       ->implode(), $imploded
        );
        
        //space
        $imploded = '0 1 4 5 0 1 2 0 1 2 3 zero one two three last';
        
        $I->assertSame(
            ArrayHelper::load($this->multi)
                       ->implode(' '), $imploded
        );
        
        //double dash
        $imploded = '0--1--4--5--0--1--2--0--1--2--3--zero--one--two--three--last';
        
        $I->assertSame(
            ArrayHelper::load($this->multi)
                       ->implode('--'), $imploded
        );
        
        //join
        
        //empty
        $imploded = '01450120123zeroonetwothreelast';
        
        $I->assertSame(
            ArrayHelper::load($this->multi)
                       ->join(), $imploded
        );
        
        //space
        $imploded = '0 1 4 5 0 1 2 0 1 2 3 zero one two three last';
        
        $I->assertSame(
            ArrayHelper::load($this->multi)
                       ->join(' '), $imploded
        );
        
        //double dash
        $imploded = '0--1--4--5--0--1--2--0--1--2--3--zero--one--two--three--last';
        
        $I->assertSame(
            ArrayHelper::load($this->multi)
                       ->join('--'), $imploded
        );
        
        //with depth
        
        $imploded = '0--1--4--5--last';
        
        $I->assertSame(
            ArrayHelper::load($this->multi)
                       ->join('--', 1), $imploded
        );
        
        $imploded = '0--1--4--5--0--1--2--last';
        
        $I->assertSame(
            ArrayHelper::load($this->multi)
                       ->join('--', 2), $imploded
        );
    }
    
    public function cloneTest(UnitTester $I)
    {
        $test = ArrayHelper::load($this->multi);
        $clone = $test->getClone();
        
        $I->assertSame($test->get(), $clone->get());
        $I->assertSame($test->getKeys(), $clone->getKeys());
        $I->assertSame($test->getValues(), $clone->getValues());
        
        //change one
        //not same
        $clone->reverseKeys();
        $I->assertNotSame($test->get(), $clone->get());
        $I->assertNotSame($test->getKeys(), $clone->getKeys());
        //not same
        $clone->reverseValues();
        $I->assertNotSame($test->getValues(), $clone->getValues());
        
        //same again
        $clone->reverse();
        $I->assertSame($test->get(), $clone->get());
        $I->assertSame($test->getKeys(), $clone->getKeys());
        $I->assertSame($test->getValues(), $clone->getValues());
    }
    
    public function iteratorTest(UnitTester $I)
    {
        //auto
        $testForeach = '';
        $testArr = '';
        
        foreach ($this->auto as $element)
        {
            $testForeach .= $element;
        }
        
        foreach (ArrayHelper::load($this->auto) as $element)
        {
            $testArr .= $element;
        }
        
        $I->assertSame($testArr, $testForeach);
        
        //assoc
        $testForeach = '';
        $testArr = '';
        
        foreach ($this->assoc as $element)
        {
            $testForeach .= $element;
        }
        
        foreach (ArrayHelper::load($this->assoc) as $element)
        {
            $testArr .= $element;
        }
        
        $I->assertSame($testArr, $testForeach);
    }
    
    public function arrayAccessTest(UnitTester $I)
    {
        $test = ArrayHelper::load($this->assoc);
        $I->assertSame($test->get(), $this->assoc);
        
        //change
        $test['zero'] = 1;
        
        $I->assertSame(
            $test->get(), [
                            'zero'  => 1,
                            'one'   => 1,
                            'two'   => 2,
                            'three' => 3,
                        ]
        );
        
        //isset
        $I->assertFalse(isset($test['five']));
        
        //unset
        unset($test['zero']);
        
        $I->assertSame(
            $test->get(), [
                            'one'   => 1,
                            'two'   => 2,
                            'three' => 3,
                        ]
        );
        
        //add
        $test[] = 5;
        $I->assertSame(
            $test->get(), [
                            'one'   => 1,
                            'two'   => 2,
                            'three' => 3,
                            0       => 5,
                        ]
        );
        
        //add assoc
        $test['five'] = 5;
        $I->assertSame(
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
        $I->assertSame($test->get(), []);
        
        $test[] = 5;
        $I->assertSame($test->get(), [5]);
        
        //get key
        $I->assertSame($test[0], 5);
        
    }
    
    public function exceptionsTest(UnitTester $I)
    {
        $I->expectException(
            '\Zver\Exceptions\ArrayHelper\UndefinedOffsetException', function ()
        {
            ArrayHelper::load([1, 2, 3])
                       ->getAt(5);
        }
        );
        
        $I->expectException(
            '\Zver\Exceptions\ArrayHelper\EmptyArrayException', function ()
        {
            ArrayHelper::load()
                       ->getAt(5);
        }
        );
        
        $I->expectException(
            '\Zver\Exceptions\ArrayHelper\EmptyArrayException', function ()
        {
            ArrayHelper::load()
                       ->getFirstKey();
        }
        );
        
        $I->expectException(
            '\Zver\Exceptions\ArrayHelper\EmptyArrayException', function ()
        {
            ArrayHelper::load()
                       ->getFirstValue();
        }
        );
        
        $I->expectException(
            '\Zver\Exceptions\ArrayHelper\EmptyArrayException', function ()
        {
            ArrayHelper::load()
                       ->getLastKey();
        }
        );
        
        $I->expectException(
            '\Zver\Exceptions\ArrayHelper\EmptyArrayException', function ()
        {
            ArrayHelper::load()
                       ->getLastValue();
        }
        );
        
        $I->expectException(
            '\Zver\Exceptions\ArrayHelper\EmptyArrayException', function ()
        {
            ArrayHelper::load()
                       ->getFirstValueUnset();
        }
        );
        
        $I->expectException(
            '\Zver\Exceptions\ArrayHelper\EmptyArrayException', function ()
        {
            ArrayHelper::load()
                       ->getLastValueUnset();
        }
        );
    }
    
    public function keyExistsTest(UnitTester $I)
    {
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->isKeyExists('one'), [['one']]
        );
        
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->isKeyExists('two'), [['two']]
        );
        
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->isKeyExists('three'), [['three']]
        );
        
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->isKeyExists('zero'), [['zero']]
        );
        
        $I->assertFalse(
            ArrayHelper::load($this->assoc)
                       ->isKeyExists('one1')
        );
        
        $I->assertFalse(
            ArrayHelper::load($this->assoc)
                       ->isKeyExists('two1')
        );
        
        $I->assertFalse(
            ArrayHelper::load($this->assoc)
                       ->isKeyExists('three1')
        );
        
        $I->assertFalse(
            ArrayHelper::load($this->assoc)
                       ->isKeyExists('zero1')
        );
        
        for ($i = -10; $i < 10; $i++)
        {
            $I->assertFalse(
                ArrayHelper::load($this->assoc)
                           ->isKeyExists($i)
            );
        }
        
        $I->assertSame(
            ArrayHelper::load($this->multi)
                       ->isKeyExists('zero'), [
                ['multi', 0, 'zero'],
                ['multi', 0, 'three', 0, 'zero'],
            ]
        );
        
        //with max depth
        $I->assertSame(
            ArrayHelper::load($this->multi)
                       ->isKeyExists('zero', true, 1), []
        );
        
        $I->assertSame(
            ArrayHelper::load($this->multi)
                       ->isKeyExists('zero', true, 2), []
        );
        
        $I->assertSame(
            ArrayHelper::load($this->multi)
                       ->isKeyExists('zero', true, 3), [
                ['multi', 0, 'zero'],
            ]
        );
        
        $I->assertSame(
            ArrayHelper::load($this->multi)
                       ->isKeyExists('zero', true, 4), [
                ['multi', 0, 'zero'],
            ]
        );
        
        $I->assertSame(
            ArrayHelper::load($this->multi)
                       ->isKeyExists('zero', true, 5), [
                ['multi', 0, 'zero'],
                ['multi', 0, 'three', 0, 'zero'],
            ]
        );
    }
    
    public function valueExistsTest(UnitTester $I)
    {
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->isValueExists(3), [['three']]
        );
        
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->isValueExists(3, true), [['three']]
        );
        
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->isValueExists('3', false), [['three']]
        );
        
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->isValueExists('3', true), false
        );
        
        $I->assertSame(
            ArrayHelper::load([0, 0, 0, 0])
                       ->isValueExists(0), [[0], [1], [2], [3]]
        );
        
        $I->assertSame(
            ArrayHelper::load($this->multi)
                       ->isValueExists(0), [
                [0],
                ['multi', 0, 'zero'],
                ['multi', 0, 'three', 0, 'zero'],
            ]
        );
        
        $I->assertSame(
            ArrayHelper::load([])
                       ->isValueExists(0), false
        );
        
        $I->assertSame(
            ArrayHelper::load([])
                       ->isValueExists(0, false), false
        );
        
        $I->assertSame(
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
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->isValueExists(3, true, 2), [['three']]
        );
        
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->isValueExists(3, true, 2), [['three']]
        );
        
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->isValueExists('3', false, 2), [['three']]
        );
        
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->isValueExists('3', true, 2), false
        );
        
        $I->assertSame(
            ArrayHelper::load([0, 0, 0, 0])
                       ->isValueExists(0, true, 2), [[0], [1], [2], [3]]
        );
        
        $I->assertSame(
            ArrayHelper::load($this->multi)
                       ->isValueExists(0, true, 1), [
                [0],
                //['multi', 0, 'zero'],
                //['multi', 0, 'three', 0, 'zero'],
            ]
        );
        
        $I->assertSame(
            ArrayHelper::load($this->multi)
                       ->isValueExists(0, true, 2), [
                [0],
            ]
        );
        
        $I->assertSame(
            ArrayHelper::load($this->multi)
                       ->isValueExists(0, true, 3), [
                [0],
                ['multi', 0, 'zero'],
                //['multi', 0, 'three', 0, 'zero'],
            ]
        );
        
        $I->assertSame(
            ArrayHelper::load($this->multi)
                       ->isValueExists(0, true, 4), [
                [0],
                ['multi', 0, 'zero'],
                //['multi', 0, 'three', 0, 'zero'],
            ]
        );
        
        $I->assertSame(
            ArrayHelper::load($this->multi)
                       ->isValueExists(0, true, 5), [
                [0],
                ['multi', 0, 'zero'],
                ['multi', 0, 'three', 0, 'zero'],
            ]
        );
        
        $I->assertSame(
            ArrayHelper::load($this->multi)
                       ->isValueExists(0, true, 6), [
                [0],
                ['multi', 0, 'zero'],
                ['multi', 0, 'three', 0, 'zero'],
            ]
        );
        
        $I->assertSame(
            ArrayHelper::load([])
                       ->isValueExists(0, false, 1), false
        );
        
    }
    
    public function convertToIndexedTest(UnitTester $I)
    {
        $I->assertSame(
            ArrayHelper::load([])
                       ->convertToIndexed(0)
                       ->getArray(), []
        );
        
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->convertToIndexed(0)
                       ->getArray(), [0, 1, 2, 3]
        );
        
        //start index
        for ($i = -1; $i < 10; $i++)
        {
            $I->assertSame(
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
    //
    //public function filterTest(UnitTester $I)
    //{
    //    $I->assertSame(
    //        Arr::load($this->multi)
    //           ->filter(
    //               function ($key, $element, $path)
    //               {
    //                   return !is_array($element);
    //               }
    //           )
    //           ->getArray(), [
    //            0,
    //            1,
    //            4,
    //            5,
    //            'last',
    //        ]
    //    );
    //
    //    $I->assertSame(
    //        Arr::load($this->multi)
    //           ->filter(
    //               function ($key, $element, $path)
    //               {
    //                   return is_array($element);
    //               }
    //           )
    //           ->getArray(), [
    //            'multi' => [
    //                [
    //                    'zero'  => 0,
    //                    'one'   => 1,
    //                    'two'   => 2,
    //                    'three' => [
    //                        [
    //                            'zero'  => 0,
    //                            'one'   => 1,
    //                            'two'   => 2,
    //                            'three' => 3,
    //                        ],
    //                    ],
    //                ],
    //                [],
    //                [
    //                    'zero',
    //                    'one',
    //                    'two',
    //                    'three',
    //                ],
    //            ],
    //        ]
    //    );
    //
    //    $I->assertSame(
    //        Arr::load($this->multi)
    //           ->filter(
    //               function ($key, $element, $path)
    //               {
    //                   if (is_array($element))
    //                   {
    //                       return true;
    //                   }
    //                   else
    //                   {
    //                       return $element === 0;
    //                   }
    //               }
    //           )
    //           ->getArray(), [
    //            0,
    //            1,
    //            4,
    //            5,
    //            'multi' => [
    //                [
    //                    'zero'  => 0,
    //                    'one'   => 1,
    //                    'two'   => 2,
    //                    'three' => [
    //                        [
    //                            'zero'  => 0,
    //                            'one'   => 1,
    //                            'two'   => 2,
    //                            'three' => 3,
    //                        ],
    //                    ],
    //                ],
    //                [],
    //                [
    //                    'zero',
    //                    'one',
    //                    'two',
    //                    'three',
    //                ],
    //            ],
    //            'last',
    //        ]
    //    );
    //}
    
    public function firstKeyUnset(UnitTester $I)
    {
        $arr = ArrayHelper::load($this->assoc);
        $I->assertSame(
            $arr->getCount(), ArrayHelper::load($this->assoc)
                                         ->count()
        );
        $I->assertSame($arr->getFirstValueUnset(), 0);
        $I->assertSame(
            $arr->getCount(), ArrayHelper::load($this->assoc)
                                         ->count() - 1
        );
        
        $arr->replaceArray($this->auto);
        $I->assertSame(
            $arr->getCount(), ArrayHelper::load($this->auto)
                                         ->count()
        );
        $I->assertSame($arr->getFirstValueUnset(), 'zero');
        $I->assertSame(
            $arr->getCount(), ArrayHelper::load($this->auto)
                                         ->count() - 1
        );
        
        $arr->replaceArray($this->multi);
        $I->assertSame(
            $arr->getCount(), ArrayHelper::load($this->multi)
                                         ->count()
        );
        $I->assertSame($arr->getFirstValueUnset(), 0);
        $I->assertSame(
            $arr->getCount(), ArrayHelper::load($this->multi)
                                         ->count() - 1
        );
    }
    
    public function lastKeyUnset(UnitTester $I)
    {
        $arr = ArrayHelper::load($this->assoc);
        $I->assertSame(
            $arr->getCount(), ArrayHelper::load($this->assoc)
                                         ->count()
        );
        $I->assertSame($arr->getLastValueUnset(), 3);
        $I->assertSame(
            $arr->getCount(), ArrayHelper::load($this->assoc)
                                         ->count() - 1
        );
        
        $arr->replaceArray($this->auto);
        $I->assertSame(
            $arr->getCount(), ArrayHelper::load($this->auto)
                                         ->count()
        );
        $I->assertSame($arr->getLastValueUnset(), 'three');
        $I->assertSame(
            $arr->getCount(), ArrayHelper::load($this->auto)
                                         ->count() - 1
        );
        
        $arr->replaceArray($this->multi);
        $I->assertSame(
            $arr->getCount(), ArrayHelper::load($this->multi)
                                         ->count()
        );
        $I->assertSame($arr->getLastValueUnset(), 'last');
        $I->assertSame(
            $arr->getCount(), ArrayHelper::load($this->multi)
                                         ->count() - 1
        );
    }
    
    public function splitPartsTest(UnitTester $I)
    {
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->splitParts(2)
                       ->getArray(), [
                ['zero' => 0, 'one' => 1],
                ['two' => 2, 'three' => 3],
            ]
        );
        
        $I->assertSame(
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
        
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->splitParts(3)
                       ->getArray(), [
                ['zero' => 0, 'one' => 1],
                ['two' => 2,],
                ['three' => 3],
            ]
        );
        
        $I->assertSame(
            ArrayHelper::load($this->assoc)
                       ->splitParts(4)
                       ->getArray(), [
                ['zero' => 0],
                ['one' => 1],
                ['two' => 2,],
                ['three' => 3],
            ]
        );
        
        $I->assertSame(
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
        
        $I->assertSame(
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
        
        $I->assertSame(
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
    
    public function combineTest(UnitTester $I)
    {
        $keys = Array(
            '1' => 'XL',
            '2' => 'L',
            '3' => 'S',
            '4' => 'M',
            '6' => 'XS',
        );
        
        $values = Array(
            '1' => 1200,
            '2' => 700,
            '3' => 670,
            '4' => 450,
            '6' => 300,
            '7' => '',
        );
        
        $combined = Array(
            'XL' => 1200,
            'L'  => 700,
            'S'  => 670,
            'M'  => 450,
            'XS' => 300,
        );
        
        $I->assertSame(
            ArrayHelper::combine($keys, $values)
                       ->getArray(), $combined
        );
    }
    
    public function explodeTest(UnitTester $I)
    {
        $I->assertSame(
            explode('/', 'id/id/id/id'), ['id', 'id', 'id', 'id']
        );
        
        $I->assertSame(
            ArrayHelper::explode('/', 'id/id/id/id')
                       ->getArray(), ['id', 'id', 'id', 'id']
        );
        
        $I->assertSame(
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
        
        $I->assertSame(
            ArrayHelper::explode('.', '/id/id/id/id//')
                       ->getArray(), [
                '/id/id/id/id//',
            ]
        );
    }
    
    public function mapTest(UnitTester $I)
    {
        $data = [
            [
                'original' => [],
                'callback' => function ($key, $value)
                {
                    return $value;
                },
                'mapped'   => [],
            ],
            [
                'original' => [1, 2, 3, 4, 5],
                'callback' => function ($key, $value)
                {
                    return $value * 2;
                },
                'mapped'   => [2, 4, 6, 8, 10],
            ],
            [
                'original' => [1, 2, 3, 4, 5],
                'callback' => function ($key, $value)
                {
                    return $value + $key;
                },
                'mapped'   => [1, 3, 5, 7, 9],
            ],
        ];
        
        foreach ($data as $item)
        {
            $I->assertSame(
                ArrayHelper::load($item['original'])
                           ->map($item['callback'])
                           ->getArray(), $item['mapped']
            );
        }
    }
    
    public function filterTest(UnitTester $I)
    {
        $data = [
            [
                'original' => [],
                'callback' => function ($key, $value)
                {
                    return $value;
                },
                'filtered' => [],
            ],
            [
                'original' => [1, 2, 3, 4, 5],
                'callback' => function ($key, $value)
                {
                    return $value < 10;
                },
                'filtered' => [1, 2, 3, 4, 5],
            ],
            [
                'original' => [1, 2, 3, 4, 5],
                'callback' => function ($key, $value)
                {
                    return $value > 3;
                },
                'filtered' => [3 => 4, 5],
            ],
            [
                'original' => [1, 2, 3, 4, 5],
                'callback' => function ($key, $value)
                {
                    return false;
                },
                'filtered' => [],
            ],
        ];
        
        foreach ($data as $item)
        {
            $I->assertSame(
                ArrayHelper::load($item['original'])
                           ->filter($item['callback'])
                           ->getArray(), $item['filtered']
            );
        }
    }
    
}
