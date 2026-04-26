<?php

/**
 * Configuración de Pest.
 *
 * Pest es un framework de tests que se construye por encima de PHPUnit.
 * Su ventaja es una sintaxis mucho más corta y legible.
 *
 * Este fichero es su "bootstrap": le digo a Pest qué clase TestCase usar
 * y qué traits aplicar automáticamente en cada test de cada carpeta.
 *
 * El PDF menciona Pest como alternativa valorable:
 *   "Pest | The elegant PHP testing framework"
 *
 * @author  Alumno DWES
 */

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Todos los tests de tests/Pest/** usan TestCase y refrescan la BD
pest()->extend(TestCase::class)
      ->use(RefreshDatabase::class)
      ->in('Pest');
