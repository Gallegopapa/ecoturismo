<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Rules\NoProfanity;

class NoProfanityTest extends TestCase
{
    public function test_bloquea_palabrotas_conocidas()
    {
        $rule = new NoProfanity();

        $this->assertFalse($rule->passes('comment', 'una mierda de atencion'));
        $this->assertFalse($rule->passes('comment', 'hijo de puta, cabron'));
        $this->assertFalse($rule->passes('comment', 'p.u.t.a'));
        $this->assertFalse($rule->passes('comment', 'pútá')); // accent
        $this->assertFalse($rule->passes('comment', 'jódete')); // accent
        $this->assertFalse($rule->passes('comment', 'ese sitio es una chimba'));
        $this->assertFalse($rule->passes('comment', 'ese sitio es una chimbaaa'));
        $this->assertFalse($rule->passes('comment', 'ese sitio es una chimbita'));
        $this->assertFalse($rule->passes('comment', 'ese sitio es una chi.mba'));
        $this->assertFalse($rule->passes('comment', 'ese sitio es una chiiimba'));
        $this->assertFalse($rule->passes('comment', 'ese sitio es una chimb4'));
    }

    public function test_permite_texto_limpio()
    {
        $rule = new NoProfanity();

        $this->assertTrue($rule->passes('comment', 'me trataron muy bien'));
        $this->assertTrue($rule->passes('comment', 'fantastico lugar, recomendado'));
    }
}
