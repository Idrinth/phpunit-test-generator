<?php

namespace De\Idrinth\TestGenerator\Implementations;

use Composer\Semver\Semver;
use De\Idrinth\TestGenerator\Interfaces\Composer as ComposerInterface;
use InvalidArgumentException;
use De\Idrinth\TestGenerator\Interfaces\JsonFile as JFI;

class Composer implements ComposerInterface
{
    /**
     * @var string[] namespace => folder
     */
    private $autoloadProd = array();

    /**
     * @var string[] namespace => folder
     */
    private $autoloadDev = array();

    /**
     * @var string
     */
    private $testClass = array();

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
     * @param JFI $file
     * @throws InvalidArgumentException if file is unusable
     */
    public function __construct(JFI $file)
    {
        $data = $file->getContent();
        $this->autoloadProd = $this->handleKey($data, 'autoload', $file->getPath());
        $this->autoloadDev = $this->handleKey($data, 'autoload-dev', $file->getPath());
        if(!isset($data['require-dev']) || !isset($data['require-dev']['phpunit/phpunit'])) {
            throw new InvalidArgumentException("No possibility to determine PHPunit TestCase class found");
        }
        $this->testClass = $this->findTestClass($data['require-dev']['phpunit/phpunit']);
    }

    /**
     * @param null|string $constraints
     * @return string the class name
     * @throws InvalidArgumentException if constraints are unusable
     */
    private function findTestClass($constraints)
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

    /**
     * @return string
     */
    public function getTestClass()
    {
        return $this->testClass;
    }

    /**
     * @param array $data
     * @param string $key
     * @param string $rootDir
     * @return string[]
     */
    private function handleKey(array $data, $key, $rootDir)
    {
        if (!isset($data[$key])) {
            return array();
        }
        $autoloaders = $data[$key];
        $folders = array();
        foreach (array('psr-0', 'psr-4') as $method) {
            if (isset($autoloaders[$method])) {
                foreach ($autoloaders[$method] as $namespace => $folder) {
                    $folders[trim($namespace, '\\')] = $rootDir.DIRECTORY_SEPARATOR.$folder;
                }
            }
        }
        return $folders;
    }

    /**
     * @return string[] namespace => folder
     */
    public function getProductionNamespacesToFolders()
    {
        return $this->autoloadProd;
    }

    /**
     * @return string[] namespace => folder
     */
    public function getDevelopmentNamespacesToFolders()
    {
        return $this->autoloadDev;
    }
}
