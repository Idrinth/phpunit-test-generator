<?php

namespace De\Idrinth\TestGenerator\Implementations;

use Composer\Semver\Semver;
use De\Idrinth\TestGenerator\Interfaces\TestClassDecider as TCDI;
use InvalidArgumentException;

class TestClassDecider implements TCDI
{
    /**
     * @var string[]
     */
    private static $newVersions = array(
        '4.8.35',
        '4.8.99',
        '5.4.3',
        '5.7.99',
        '6.0.0',
        '6.99.99',
        '7.0.0',
        '7.99.99',
        '8.0.0',
        '8.99.99'
    );

    /**
     * @var string[]
     */
    private static $oldVersions = array(
        '1.0.0',
        '1.0.99',
        '1.99.99',
        '2.0.0',
        '2.0.99',
        '2.99.99',
        '3.0.0',
        '3.0.99',
        '3.99.99',
        '4.0.0',
        '4.0.99',
        '4.99.99',
        '5.0.0',
        '5.0.99',
        '5.99.99',
    );

    /**
     * @param string $constraints
     * @return string
     * @throws InvalidArgumentException if constraints are unusable
     */
    public function get($constraints)
    {
        $orLessConstraints = explode('|', $constraints);
        $old = 0;
        $new = 0;
        $amount = count($orLessConstraints);
        foreach ($orLessConstraints as $constraint) {
            $old += Semver::satisfiedBy(self::$oldVersions, $constraint)?1:0;
            $new += Semver::satisfiedBy(self::$newVersions, $constraint)?1:0;
        }
        if ($new === $amount) {
            return 'PHPUnit\Framework\TestCase';
        }
        if ($old === $amount) {
            return 'PHPUnit_Framework_TestCase';
        }
        throw new InvalidArgumentException("No possibility to determine PHPunit TestCase class found");
    }
}