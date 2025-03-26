<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class PerformanceUtilsTest extends TestCase
{
    /**
     * @test
     */
    public function array_operations_are_performant()
    {
        $startTime = microtime(true);
        
        $largeArray = [];
        
        // Build a large array
        for ($i = 0; $i < 1000; $i++) {
            $largeArray[] = "Item " . $i;
        }
        
        // Process the array with various operations
        $mappedArray = array_map(function($item) {
            return strtoupper($item);
        }, $largeArray);
        
        $filteredArray = array_filter($largeArray, function($item) {
            return strpos($item, "5") !== false;
        });
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime);
        
        $this->assertLessThan(0.5, $executionTime, 'Array operations should be performant');
    }
    
    /**
     * @test
     */
    public function string_operations_are_performant()
    {
        $startTime = microtime(true);
        
        $longString = str_repeat("This is a test string. ", 1000);
        
        // Process the string with various operations
        $uppercase = strtoupper($longString);
        $replaced = str_replace("test", "sample", $longString);
        $tokens = explode(" ", $longString);
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime);
        
        $this->assertLessThan(0.5, $executionTime, 'String operations should be performant');
    }
    
    /**
     * @test
     * @dataProvider sortingDataProvider
     */
    public function sorting_algorithms_performance($array, $description)
    {
        $startTime = microtime(true);
        
        // Sort the array
        sort($array);
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime);
        
        $this->assertLessThan(0.5, $executionTime, "Sorting {$description} should be fast");
    }
    
    /**
     * @test
     */
    public function hash_generation_performance()
    {
        $startTime = microtime(true);
        
        for ($i = 0; $i < 100; $i++) {
            $data = "Data to hash " . $i;
            $hash = md5($data);
            $hash2 = sha1($data);
            $hash3 = hash('sha256', $data);
        }
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime);
        
        $this->assertLessThan(0.5, $executionTime, 'Hash generation should be performant');
    }
    
    /**
     * @test
     */
    public function json_encoding_performance()
    {
        $startTime = microtime(true);
        
        $largeArray = [];
        
        // Build a complex array structure
        for ($i = 0; $i < 100; $i++) {
            $largeArray[] = [
                'id' => $i,
                'name' => 'Item ' . $i,
                'attributes' => [
                    'color' => 'red',
                    'size' => $i % 3,
                    'tags' => ['tag1', 'tag2', 'tag3']
                ],
                'active' => ($i % 2 === 0)
            ];
        }
        
        // Encode and decode multiple times
        for ($i = 0; $i < 10; $i++) {
            $jsonString = json_encode($largeArray);
            $decodedArray = json_decode($jsonString, true);
        }
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime);
        
        $this->assertLessThan(0.5, $executionTime, 'JSON operations should be performant');
    }
    
    public static function sortingDataProvider()
    {
        // Generate arrays of different sizes and types
        $smallArray = range(1, 100);
        shuffle($smallArray);
        
        $mediumArray = range(1, 1000);
        shuffle($mediumArray);
        
        $stringArray = [];
        for ($i = 0; $i < 100; $i++) {
            $stringArray[] = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 10);
        }
        
        return [
            'small numeric array' => [$smallArray, 'small numeric array'],
            'medium numeric array' => [$mediumArray, 'medium numeric array'],
            'string array' => [$stringArray, 'string array'],
        ];
    }
} 