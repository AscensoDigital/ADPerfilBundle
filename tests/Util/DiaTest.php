<?php

namespace Tests\AscensoDigital\PerfilBundle\Util;

use AscensoDigital\PerfilBundle\Util\Dia;
use PHPUnit\Framework\TestCase;

class DiaTest extends TestCase
{
    public function testConstructorSetsProperties()
    {
        $dia = new Dia(1, 'Lunes');
        $this->assertSame(1, $dia->getId());
        $this->assertSame('Lunes', $dia->getNombre());
    }

    public function testGettersReturnInitialValues()
    {
        $dia = new Dia(5, 'Viernes');
        $this->assertEquals(5, $dia->getId());
        $this->assertEquals('Viernes', $dia->getNombre());
    }

    public function testSettersModifyProperties()
    {
        $dia = new Dia(2, 'Martes');
        $dia->setId(3);
        $dia->setNombre('Miércoles');
        $this->assertEquals(3, $dia->getId());
        $this->assertEquals('Miércoles', $dia->getNombre());
    }

    public function testToStringReturnsNombre()
    {
        $dia = new Dia(4, 'Jueves');
        $this->assertEquals('Jueves', (string)$dia);
    }

    public function testSetIdWithNullOrEdgeValues()
    {
        $dia = new Dia(0, 'Domingo');
        $dia->setId(null);
        $this->assertNull($dia->getId());

        $dia->setId(999999);
        $this->assertEquals(999999, $dia->getId());
    }

    public function testSetNombreWithEmptyString()
    {
        $dia = new Dia(6, 'Sábado');
        $dia->setNombre('');
        $this->assertSame('', $dia->getNombre());

        $dia->setNombre('  ');
        $this->assertSame('  ', $dia->getNombre());
    }
}
