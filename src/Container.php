<?php

namespace De\Idrinth\TestGenerator;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionParameter;

class Container implements ContainerInterface
{
    /**
     * @var string
     */
    private static $skipped = 'SKIPPINGTHISOPTIONALPARAM';

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
        if (!class_exists($id) && !interface_exists($id)) {
            throw new InvalidArgumentException("Can't wire id $id");
        }
        $class = new ReflectionClass($id);
        if ($class->isInterface()) {
            $this->implements[$id] = $this->get(str_replace('\\Interfaces\\', '\\Implementations\\', $id));
            return $this->implements[$id];
        }
        $this->implements[$id] = $class->newInstanceArgs($this->getArgs($class));
        return $this->implements[$id];
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
            $param = $this->handleParam($parameter, $class);
            if ($param === self::$skipped) {
                $isSkipping = true;
                continue;
            }
            if ($param !== self::$skipped && $isSkipping) {
                throw new InvalidArgumentException("Can't wire params for {$class->getName()}");
            }
            $args[] = $this->get($param);
        }
        return $args;
    }

    /**
     * @param ReflectionParameter $parameter
     * @param ReflectionClass $class
     * @return mixed
     */
    private function handleParam(ReflectionParameter $parameter, ReflectionClass $class)
    {
        if ($parameter->getClass()) {
            return $parameter->getClass()->getName();
        }
        if ($this->has("{$class->getName()}.{$parameter->getName()}")) {
            return "{$class->getName()}.{$parameter->getName()}";
        }
        foreach ($class->getInterfaceNames() as $interface) {
            if ($this->has("$interface.{$parameter->getName()}")) {
                return "$interface.{$parameter->getName()}";
            }
        }
        if ($this->has("{$parameter->getName()}")) {
            return $parameter->getName();
        }
        if ($parameter->isOptional()) {
            return self::$skipped;
        }
        throw new InvalidArgumentException("Can't wire param {$parameter->getName()} for {$class->getName()}");
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
