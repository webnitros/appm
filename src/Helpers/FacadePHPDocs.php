<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 19.11.2022
 * Time: 13:12
 */

namespace AppM\Helpers;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;

class FacadePHPDocs
{

    /**
     * @var ReflectionClass
     */
    private ReflectionClass $reflectionClass;

    /**
     * FacadePHPDocs constructor.
     * @throws ReflectionException
     */
    public function __construct(string $classname)
    {
        $this->reflectionClass = new ReflectionClass($classname);
    }

    /**
     * @return ReflectionClass
     */
    protected function getReflectionClass(): ReflectionClass
    {
        return $this->reflectionClass;
    }

    /**
     * @param ReflectionMethod $method
     * @return string|null
     */
    protected function getReturnType(ReflectionMethod $method): ?string
    {
        $type = $method->getReturnType();
        if (class_exists($type)) {
            return '\\' . $type . ' ';
        } elseif (is_null($type)) {
            return '';
        } else {
            return $type . ' ';
        }
    }

    /**
     * @param ReflectionParameter[] $parameters
     * @throws ReflectionException
     */
    protected function processParameter(array $parameters): string
    {
        $output = [];
        $processValue = function ( $value) {
            return var_export($value, true);
        };
        foreach ($parameters as $parameter) {
            if ($parameter->isOptional()) {
                if ($parameter->isDefaultValueConstant()) {
                    $output[] = (string)$parameter->getType() . ' $' . $parameter->getName() . ' = ' . $parameter->getDefaultValueConstantName();
                } else {
                    $output[] = (string)$parameter->getType() . ' $' . $parameter->getName() . ' = ' . $processValue($parameter->getDefaultValue());
                }
            } else {
                $output[] = (string)$parameter->getType() . ' $' . $parameter->getName();
            }
        }
        return '(' . implode(', ', $output) . ')';
    }

    /**
     * @throws ReflectionException
     */
    function generate()
    {
        $methods = $this->getReflectionClass()->getMethods();

        foreach ($methods as $method) {
            echo $this->arrayToString([
                ' * @method static',
                $this->getReturnType($method) . $method->getName() . $this->processParameter($method->getParameters()),
                PHP_EOL
            ]);
        }
    }

    /**
     * @throws ReflectionException
     */
    public static function make(string $classname)
    {
        return new static($classname);
    }

    /**
     * @param array $array
     * @return string
     */
    protected function arrayToString(array $array): string
    {
        return implode(' ', $array);
    }
}
