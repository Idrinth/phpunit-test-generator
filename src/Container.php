<?php

namespace De\Idrinth\TestGenerator;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionParameter;

class Container implements ContainerInterface
{
    /**
     * @var mixed[]
     */
    private $values = array();

    /**
     * @var object[]
     */
    private $implements = array();

    /**
     * @param string $id
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function get($id)
    {
        if (array_key_exists($id, $this->values)) {
            return $this->values[$id];
        }
        if (isset($this->implements[$id])) {
            return $this->implements[$id];
        }
        $this->implements[$id] = $this->getUncached($id);
        return $this->implements[$id];
    }

    /**
     * @param string $identifier
     * @return object
     * @throws InvalidArgumentException
     */
    private function getUncached($identifier)
    {
        if (!class_exists($identifier)) {
            throw new InvalidArgumentException("Can't wire id $identifier");
        }
        $class = new ReflectionClass($identifier);
        return $class->newInstanceArgs($this->getArgs($class));;
    }

    /**
     * @param ReflectionClass $class
     * @return array
     */
    private function getArgs(ReflectionClass $class)
    {
        if (!$class->getConstructor()) {
            return array();
        }
        $args = array();
        $isSkipping = false;
        foreach ($class->getConstructor()->getParameters() as $parameter) {
            $param = $this->handleParam($parameter, $class, $isSkipping);
            if ($isSkipping === false) {
                $args[] = $this->get($param);
            }
        }
        return $args;
    }

    /**
     * @param ReflectionParameter $parameter
     * @param ReflectionClass $class
     * @param boolean $isSkipping
     * @return mixed
     */
    private function handleParam(ReflectionParameter $parameter, ReflectionClass $class, &$isSkipping)
    {
        if ($isSkipping && !$parameter->isOptional()) {
            throw new InvalidArgumentException("Can't wire param {$parameter->getName()} for {$class->getName()}");
        }
        if ($parameter->getClass()) {
            return $parameter->getClass()->getName();
        }
        return $this->handleSimpleTypeParam($parameter, $class, $isSkipping);
    }

    /**
     * @param ReflectionParameter $parameter
     * @param ReflectionClass $class
     * @param boolean $isSkipping
     * @return mixed
     */
    private function handleSimpleTypeParam(ReflectionParameter $parameter, ReflectionClass $class, &$isSkipping)
    {
        foreach (array_merge(array($class->getName()), $class->getInterfaceNames(), array('')) as $interface) {
            $key = trim("$interface.{$parameter->getName()}", '.');
            if ($this->has($key)) {
                return $key;
            }
        }
        if (!$parameter->isOptional()) {
            throw new InvalidArgumentException("Can't wire param {$parameter->getName()} for {$class->getName()}");
        }
        $isSkipping = true;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has($id)
    {
        return isset($this->implements[$id]) || array_key_exists($id, $this->values);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function addValue($key, $value)
    {
        $this->values[$key] = $value;
        return $this;
    }

    /**
     * @return Container
     */
    public static function create()
    {
        return new self();
    }
}
