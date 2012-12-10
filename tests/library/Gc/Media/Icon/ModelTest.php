<?php
namespace Gc\Media\Icon;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2012-10-17 at 20:40:09.
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class ModelTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Model
     */
    protected $_object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_object = new Model;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Gc\Media\Icon\Model::fromArray
     */
    public function testFromArray()
    {
        $this->assertInstanceOf('Gc\Media\Icon\Model', Model::fromArray(array()));
    }

    /**
     * @covers Gc\Media\Icon\Model::fromId
     */
    public function testFromId()
    {
        $this->assertInstanceOf('Gc\Media\Icon\Model', Model::fromId(1));
    }

    /**
     * @covers Gc\Media\Icon\Model::fromId
     */
    public function testFromWithWrongId()
    {
        $this->assertFalse(Model::fromId('undefined'));
    }

    /**
     * @covers Gc\Media\Icon\Model::save
     */
    public function testSave()
    {
        $this->_object->setData(array(
            'name' => 'IconTest',
            'url' => 'IconTest'
        ));
        $this->assertTrue(is_numeric($this->_object->save()));
        //Code coverage
        $this->assertTrue(is_numeric($this->_object->save()));
    }

    /**
     * @covers Gc\Media\Icon\Model::save
     */
    public function testSaveWithWrongValues()
    {
        $this->setExpectedException('Gc\Exception');
        $this->assertFalse($this->_object->save());
    }

    /**
     * @covers Gc\Media\Icon\Model::delete
     */
    public function testDelete()
    {
        $this->_object->setData(array(
            'name' => 'IconTest',
            'url' => 'IconTest'
        ));
        $this->_object->save();

        $this->assertTrue($this->_object->delete());
    }

    /**
     * @covers Gc\Media\Icon\Model::delete
     */
    public function testDeleteWithNoData()
    {
        $this->assertFalse($this->_object->delete());
    }

    /**
     * @covers Gc\Media\Icon\Model::delete
     */
    public function testDeleteWithWrongValues()
    {
        $this->setExpectedException('Gc\Exception');
        $this->_object->setId('undefined');
        $this->assertFalse($this->_object->delete());
    }
}