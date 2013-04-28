<?php
/**
 * This source file is part of GotCms.
 *
 * GotCms is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * GotCms is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License along
 * with GotCms. If not, see <http://www.gnu.org/licenses/lgpl-3.0.html>.
 *
 * PHP Version >=5.3
 *
 * @category Gc_Tests
 * @package  Library
 * @author   Pierre Rambaud (GoT) <pierre.rambaud86@gmail.com>
 * @license  GNU/LGPL http://www.gnu.org/licenses/lgpl-3.0.html
 * @link     http://www.got-cms.com
 */

namespace Gc\Media\Icon;

use Gc\Registry;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2012-10-17 at 20:40:09.
 *
 * @group Gc
 * @category Gc_Tests
 * @package  Library
 */
class ModelTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Model
     *
     * @return void
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->object = new Model;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return void
     */
    protected function tearDown()
    {
        unset($this->object);
    }

    /**
     * Test
     *
     * @covers Gc\Media\Icon\Model::fromArray
     *
     * @return void
     */
    public function testFromArray()
    {
        $this->assertInstanceOf('Gc\Media\Icon\Model', Model::fromArray(array()));
    }

    /**
     * Test
     *
     * @covers Gc\Media\Icon\Model::fromId
     *
     * @return void
     */
    public function testFromId()
    {
        $this->assertInstanceOf('Gc\Media\Icon\Model', Model::fromId(1));
    }

    /**
     * Test
     *
     * @covers Gc\Media\Icon\Model::fromId
     *
     * @return void
     */
    public function testFromWithWrongId()
    {
        $this->assertFalse(Model::fromId('undefined'));
    }

    /**
     * Test
     *
     * @covers Gc\Media\Icon\Model::save
     *
     * @return void
     */
    public function testSave()
    {
        $this->object->setData(
            array(
                'name' => 'IconTest',
                'url' => 'IconTest'
            )
        );
        $this->assertInternalType('integer', (int) $this->object->save());
        //Code coverage
        $this->assertInternalType('integer', (int) $this->object->save());
    }

    /**
     * Test
     *
     * @covers Gc\Media\Icon\Model::save
     *
     * @return void
     */
    public function testSaveWithWrongValues()
    {
        $this->setExpectedException('Gc\Exception');
        $this->assertFalse($this->object->save());
    }

    /**
     * Test
     *
     * @covers Gc\Media\Icon\Model::delete
     *
     * @return void
     */
    public function testDelete()
    {
        $this->object->setData(
            array(
                'name' => 'IconTest',
                'url' => 'IconTest'
            )
        );
        $this->object->save();

        $this->assertTrue($this->object->delete());
    }

    /**
     * Test
     *
     * @covers Gc\Media\Icon\Model::delete
     *
     * @return void
     */
    public function testDeleteWithNoData()
    {
        $this->assertFalse($this->object->delete());
    }

    /**
     * Test
     *
     * @covers Gc\Media\Icon\Model::delete
     *
     * @return void
     */
    public function testDeleteWithWrongValues()
    {
        $configuration = Registry::get('Configuration');
        if ($configuration['db']['driver'] == 'pdo_mysql') {
            $this->markTestSkipped('Mysql does not thrown exception.');
        }

        $this->setExpectedException('Gc\Exception');
        $this->object->setId('undefined');
        $this->assertFalse($this->object->delete());
    }
}