<?php

namespace De\Idrinth\TestGenerator\Test\Implementations;

use De\Idrinth\TestGenerator\Implementations\ClassDescriptorFactory;
use De\Idrinth\TestGenerator\Interfaces\MethodFactory;
use De\Idrinth\TestGenerator\Interfaces\TypeResolver;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPUnit\Framework\TestCase;

class ClassDescriptorFactoryTest extends TestCase
{
    /**
     * @return array
     */
    public function provideCreate()
    {
        return [
            [
                new ClassDescriptorFactory($this->getMethodFactoryInstance(0)),
                $this->getClass(0),
                '',
                false,
                0
            ],
            [
                new ClassDescriptorFactory($this->getMethodFactoryInstance(1)),
                $this->getClass(0, true),
                '',
                false,
                0
            ],
            [
                new ClassDescriptorFactory($this->getMethodFactoryInstance(1)),
                $this->getClass(0, false, true),
                '',
                false,
                0
            ],
            [
                new ClassDescriptorFactory($this->getMethodFactoryInstance(2)),
                $this->getClass(0, true, true),
                '',
                false,
                0
            ],
            [
                new ClassDescriptorFactory($this->getMethodFactoryInstance(2)),
                $this->getClass(1, true),
                '',
                false,
                1
            ],
            [
                new ClassDescriptorFactory($this->getMethodFactoryInstance(2)),
                $this->getClass(1, false, true),
                '',
                false,
                1
            ],
            [
                new ClassDescriptorFactory($this->getMethodFactoryInstance(5)),
                $this->getClass(3, true, true),
                '',
                false,
                3
            ],
            [
                new ClassDescriptorFactory($this->getMethodFactoryInstance(4)),
                $this->getClass(2, true, true, 'Mine'),
                'Mine',
                false,
                2
            ]
        ];
    }

    /**
     * @param int $countMethods
     * @param boolean $hasCurConstructor
     * @param boolean $hasOldConstructor
     * @param string $extends
     * @return Class_
     */
    private function getClass(
        $countMethods,
        $hasCurConstructor = false,
        $hasOldConstructor = false,
        $extends = ''
    ) {
        $data = ['stmts' => []];
        for ($pos = 0; $pos < $countMethods; $pos++) {
            $data['stmts'][] = new ClassMethod("method$pos");
        }
        if ($hasCurConstructor) {
            $data['stmts'][] = new ClassMethod("__construct");
        }
        if ($hasOldConstructor) {
            $data['stmts'][] = new ClassMethod("Example");
        }
        if ($extends) {
            $data['extends'] = $extends;
        }
        return new Class_('Example', $data);
    }

    /**
     * @param int $calls
     * @return MethodFactory
     */
    private function getMethodFactoryInstance($calls)
    {
        $factory = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\MethodFactory')
            ->getMock();
        $factory->expects($this->exactly($calls))
            ->method('create')
            ->willReturn($this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\MethodDescriptor')->getMock());
        return $factory;
    }

    /**
     * @return TypeResolver
     */
    private function getTypeResolverInstance()
    {
        $resolver = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\TypeResolver')
            ->getMock();
        $self = $this;
        $resolver->expects($this->any())
            ->method('getNamespace')
            ->willReturn('HiWorld');
        $resolver->expects($this->any())
            ->method('toType')
            ->willReturnCallback(function ($name, $alternative) use ($self) {
                $type = $self->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\Type')->getMock();
                $type->expects($self->once())->method('getClassName')->willReturn($name.$alternative);
                return $type;
            });
        return $resolver;
    }

    /**
     * @test
     * @dataProvider provideCreate
     * @param ClassDescriptorFactory $factory
     * @param Class_ $class
     * @param string $extends
     * @param boolean $isAbstract
     * @param int $methods
     */
    public function testCreate(ClassDescriptorFactory $factory, Class_ $class, $extends, $isAbstract, $methods)
    {
        $result = $factory->create($class, $this->getTypeResolverInstance());
        $this->assertEquals('Example', $result->getName());
        $this->assertEquals('HiWorld', $result->getNamespace());
        $this->assertEquals($isAbstract, $result->isAbstract());
        $this->assertEquals($extends, $result->getExtends());
        $this->assertCount($methods, $result->getMethods());
        foreach ($result->getMethods() as $method) {
            $this->assertInstanceOf('De\Idrinth\TestGenerator\Interfaces\MethodDescriptor', $method);
        }
    }
}
