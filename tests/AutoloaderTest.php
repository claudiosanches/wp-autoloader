<?php

namespace ClaudioSanches\WPAutoloader\Tests;

class AutoloaderTest extends \PHPUnit_Framework_TestCase
{
    protected $loader;

    protected function setUp()
    {
        $this->loader = new MockAutoloader;

        $this->loader->setFiles(array(
            '/vendor/foo.bar/src/class-name.php',
            '/vendor/foo.bar/src/class-foo-bar.php',
            '/vendor/foo.bar/tests/class-name-test.php',
            '/vendor/foo.bardoom/src/class-name.php',
            '/vendor/foo.bar.baz.dib/src/class-name.php',
            '/vendor/foo.bar.baz.dib.zim.gir/src/class-name.php',
        ));

        $this->loader->addNamespace(
            'Foo\Bar',
            '/vendor/foo.bar/src'
        );

        $this->loader->addNamespace(
            'Foo\Bar',
            '/vendor/foo.bar/tests'
        );

        $this->loader->addNamespace(
            'Foo\BarDoom',
            '/vendor/foo.bardoom/src'
        );

        $this->loader->addNamespace(
            'Foo\Bar\Baz\Dib',
            '/vendor/foo.bar.baz.dib/src'
        );

        $this->loader->addNamespace(
            'Foo\Bar\Baz\Dib\Zim\Gir',
            '/vendor/foo.bar.baz.dib.zim.gir/src'
        );
    }

    public function testExistingFile()
    {
        $actual = $this->loader->loadClass('Foo\Bar\Name');
        $expect = '/vendor/foo.bar/src/class-name.php';
        $this->assertSame($expect, $actual);

        $actual = $this->loader->loadClass('Foo\Bar\Name_Test');
        $expect = '/vendor/foo.bar/tests/class-name-test.php';
        $this->assertSame($expect, $actual);
    }

    public function testMissingFile()
    {
        $actual = $this->loader->loadClass('No_Vendor\No_Package\No_Class');
        $this->assertEquals('', $actual);
    }

    public function testWrongFile()
    {
        $actual = $this->loader->loadClass('Foo\Bar\Name_Test\Wrong.php');
        $this->assertEquals('', $actual);
    }

    public function testDeepFile()
    {
        $actual = $this->loader->loadClass('Foo\Bar\Baz\Dib\Zim\Gir\Name');
        $expect = '/vendor/foo.bar.baz.dib.zim.gir/src/class-name.php';
        $this->assertSame($expect, $actual);
    }

    public function testConfusion()
    {
        $actual = $this->loader->loadClass('Foo\Bar\Foo_Bar');
        $expect = '/vendor/foo.bar/src/class-foo-bar.php';
        $this->assertSame($expect, $actual);

        $actual = $this->loader->loadClass('Foo\BarDoom\Name');
        $expect = '/vendor/foo.bardoom/src/class-name.php';
        $this->assertSame($expect, $actual);
    }
}
