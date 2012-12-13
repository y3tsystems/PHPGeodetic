<?php


class ECEFTest extends PHPUnit_Framework_TestCase
{

    protected $_distance;
    protected $_xDdistance;
    protected $_yDistance;
    protected $_zDistance;
    protected $_xyz;

    protected function setUp()
    {
        $this->_distance = $this->getMock('Geodetic_Distance');
        $this->_distance->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue(12345.67890));

        $this->_xDistance = $this->getMock('Geodetic_Distance');
        $this->_xDistance->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue(3805.0701543279));
        $this->_yDistance = $this->getMock('Geodetic_Distance');
        $this->_yDistance->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue(-198.86566320382));
        $this->_zDistance = $this->getMock('Geodetic_Distance');
        $this->_zDistance->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue(5097.7821917469));

        $this->_xyz = $this->getMock('Geodetic_ECEF_CoordinateValues');
        $this->_xyz->expects($this->any())
            ->method('getX')
            ->will($this->returnValue($this->_xDistance));
        $this->_xyz->expects($this->any())
            ->method('getY')
            ->will($this->returnValue($this->_yDistance));
        $this->_xyz->expects($this->any())
            ->method('getZ')
            ->will($this->returnValue($this->_zDistance));
    }


    public function testInstantiate()
    {
        $ecefObject = new Geodetic_ECEF();
        //    Must return an object...
        $this->assertTrue(is_object($ecefObject));
        //    ... of the correct type
        $this->assertTrue(is_a($ecefObject, 'Geodetic_ECEF'));

        $matrixDefaultX = $ecefObject->getX();
        $this->assertTrue(is_object($matrixDefaultX));
        $this->assertTrue(is_a($matrixDefaultX, 'Geodetic_Distance'));
        $this->assertEquals(0.0, $matrixDefaultX->getValue());

        $matrixDefaultY = $ecefObject->getY();
        $this->assertTrue(is_object($matrixDefaultY));
        $this->assertTrue(is_a($matrixDefaultY, 'Geodetic_Distance'));
        $this->assertEquals(0.0, $matrixDefaultY->getValue());

        $matrixDefaultZ = $ecefObject->getZ();
        $this->assertTrue(is_object($matrixDefaultZ));
        $this->assertTrue(is_a($matrixDefaultZ, 'Geodetic_Distance'));
        $this->assertEquals(0.0, $matrixDefaultZ->getValue());
    }

    public function testInstantiateWithValues()
    {
        $ecefObject = new Geodetic_ECEF($this->_xyz);

        $matrixXValue = $ecefObject->getX()->getValue();
        $this->assertEquals(3805.0701543279, $matrixXValue);

        $matrixYValue = $ecefObject->getY()->getValue();
        $this->assertEquals(-198.86566320382, $matrixYValue);

        $matrixZValue = $ecefObject->getZ()->getValue();
        $this->assertEquals(5097.7821917469, $matrixZValue);
    }

    public function testSetXValue()
    {
        $ecefObject = new Geodetic_ECEF($this->_xyz);

        $fluidReturn = $ecefObject->setX($this->_distance);
        $matrixXValue = $ecefObject->getX();
        $this->assertTrue(is_object($matrixXValue));
        $this->assertTrue(is_a($matrixXValue, 'Geodetic_Distance'));
        $this->assertEquals(12345.67890, $matrixXValue->getValue());

        //    Test fluid return object
        $this->assertTrue(is_object($fluidReturn));
        //    ... of the correct type
        $this->assertTrue(is_a($fluidReturn, 'Geodetic_ECEF'));
    }

    /**
     * @expectedException Geodetic_Exception
     */
    public function testSetXValueInvalid()
    {
        $ecefObject = new Geodetic_ECEF($this->_xyz);

        $fluidReturn = $ecefObject->setX();
    }

    public function testSetYValue()
    {
        $ecefObject = new Geodetic_ECEF($this->_xyz);

        $fluidReturn = $ecefObject->setY($this->_distance);
        $matrixYValue = $ecefObject->getY();
        $this->assertTrue(is_object($matrixYValue));
        $this->assertTrue(is_a($matrixYValue, 'Geodetic_Distance'));
        $this->assertEquals(12345.67890, $matrixYValue->getValue());

        //    Test fluid return object
        $this->assertTrue(is_object($fluidReturn));
        //    ... of the correct type
        $this->assertTrue(is_a($fluidReturn, 'Geodetic_ECEF'));
    }

    /**
     * @expectedException Geodetic_Exception
     */
    public function testSetYValueInvalid()
    {
        $ecefObject = new Geodetic_ECEF($this->_xyz);

        $fluidReturn = $ecefObject->setY();
    }

    public function testSetZValue()
    {
        $ecefObject = new Geodetic_ECEF($this->_xyz);

        $fluidReturn = $ecefObject->setZ($this->_distance);
        $matrixZValue = $ecefObject->getZ();
        $this->assertTrue(is_object($matrixZValue));
        $this->assertTrue(is_a($matrixZValue, 'Geodetic_Distance'));
        $this->assertEquals(12345.67890, $matrixZValue->getValue());

        //    Test fluid return object
        $this->assertTrue(is_object($fluidReturn));
        //    ... of the correct type
        $this->assertTrue(is_a($fluidReturn, 'Geodetic_ECEF'));
    }

    /**
     * @expectedException Geodetic_Exception
     */
    public function testSetZValueInvalid()
    {
        $ecefObject = new Geodetic_ECEF($this->_xyz);

        $fluidReturn = $ecefObject->setZ();
    }

    public function testConvertToLatLong()
    {
        $ecefObject = new Geodetic_ECEF($this->_xyz);

        $datum = new Geodetic_Datum(Geodetic_Datum::WGS84);
        $latLong = $ecefObject->toLatLong($datum);
        $this->assertTrue(is_object($latLong));
        $this->assertTrue(is_a($latLong, 'Geodetic_LatLong'));
    }

    /**
     * @expectedException Geodetic_Exception
     */
    public function testConvertToLatLongNoDatum()
    {
        $ecefObject = new Geodetic_ECEF($this->_xyz);

        $latLong = $ecefObject->toLatLong();
    }

    public function testConvertToWGS84()
    {
        $ecefObject = new Geodetic_ECEF($this->_xyz);

        $datum = new Geodetic_Datum(Geodetic_Datum::OSGB36);
        $ecefObject->toWGS84($datum);
        $this->assertEquals(3805.0701543279, $ecefObject->getX()->getValue());
        $this->assertEquals(-198.86566320382, $ecefObject->getY()->getValue());
        $this->assertEquals(5097.7821917469, $ecefObject->getZ()->getValue());
    }

    /**
     * @expectedException Geodetic_Exception
     */
    public function testConvertToWGS84Invalid()
    {
        $ecefObject = new Geodetic_ECEF($this->_xyz);

        $ecefObject->toWGS84();
    }

    public function testConvertFromWGS84()
    {
        $ecefObject = new Geodetic_ECEF($this->_xyz);

        $datum = new Geodetic_Datum(Geodetic_Datum::OSGB36);
        $ecefObject->fromWGS84($datum);
        $this->assertEquals(3805.0701543279, $ecefObject->getX()->getValue());
        $this->assertEquals(-198.86566320382, $ecefObject->getY()->getValue());
        $this->assertEquals(5097.7821917469, $ecefObject->getZ()->getValue());
    }

    /**
     * @expectedException Geodetic_Exception
     */
    public function testConvertFromWGS84Invalid()
    {
        $ecefObject = new Geodetic_ECEF($this->_xyz);

        $ecefObject->fromWGS84();
    }

}
