<?php

namespace Database\Seeders;

use App\Models\Materia;
use Illuminate\Database\Seeder;

class MateriaSeeder extends Seeder
{
    public function run(): void
    {
        // Plan de estudios de la Licenciatura en Desarrollo de Sistemas Web
        // Cada materia indica las claves de sus prerrequisitos.
        $materias = [
            // Semestre 1
            ['clave' => 'LDSW101', 'nombre' => 'Visión sistémica de las tecnologías', 'semestre' => 1, 'creditos' => 8, 'prerrequisitos' => []],
            ['clave' => 'LDSW102', 'nombre' => 'Fundamentos de programación', 'semestre' => 1, 'creditos' => 8, 'prerrequisitos' => []],
            ['clave' => 'LDSW103', 'nombre' => 'Fundamentación de la internet', 'semestre' => 1, 'creditos' => 8, 'prerrequisitos' => []],
            ['clave' => 'LDSW104', 'nombre' => 'Principios de diseño web', 'semestre' => 1, 'creditos' => 8, 'prerrequisitos' => []],
            ['clave' => 'LDSW105', 'nombre' => 'Proyecto I', 'semestre' => 1, 'creditos' => 8, 'prerrequisitos' => []],

            // Semestre 2
            ['clave' => 'LDSW201', 'nombre' => 'Ciberseguridad', 'semestre' => 2, 'creditos' => 8, 'prerrequisitos' => []],
            ['clave' => 'LDSW202', 'nombre' => 'Técnicas de composición y diseño web', 'semestre' => 2, 'creditos' => 8, 'prerrequisitos' => ['LDSW104']], // supuesto
            ['clave' => 'LDSW203', 'nombre' => 'Experiencia de usuario y diseño', 'semestre' => 2, 'creditos' => 8, 'prerrequisitos' => []],
            ['clave' => 'LDSW204', 'nombre' => 'Fundamentación de diseño gráfico para la web', 'semestre' => 2, 'creditos' => 8, 'prerrequisitos' => []],
            ['clave' => 'LDSW205', 'nombre' => 'Lengua extranjera I', 'semestre' => 2, 'creditos' => 8, 'prerrequisitos' => []],
            ['clave' => 'LDSW206', 'nombre' => 'Proyecto II', 'semestre' => 2, 'creditos' => 8, 'prerrequisitos' => ['LDSW105']],

            // Semestre 3
            ['clave' => 'LDSW301', 'nombre' => 'Diseño y gestión de base de datos', 'semestre' => 3, 'creditos' => 8, 'prerrequisitos' => []],
            ['clave' => 'LDSW302', 'nombre' => 'Lenguajes de programación back end', 'semestre' => 3, 'creditos' => 8, 'prerrequisitos' => ['LDSW102']], // supuesto
            ['clave' => 'LDSW303', 'nombre' => 'Optimización de medios digitales para la web: imágenes gráficas', 'semestre' => 3, 'creditos' => 8, 'prerrequisitos' => []],
            ['clave' => 'LDSW304', 'nombre' => 'Lengua extranjera II', 'semestre' => 3, 'creditos' => 8, 'prerrequisitos' => ['LDSW205']],
            ['clave' => 'LDSW305', 'nombre' => 'Diseño de prototipo de interfaz de usuario', 'semestre' => 3, 'creditos' => 8, 'prerrequisitos' => ['LDSW203']], // supuesto
            ['clave' => 'LDSW306', 'nombre' => 'Proyecto III', 'semestre' => 3, 'creditos' => 8, 'prerrequisitos' => ['LDSW206']],

            // Semestre 4
            ['clave' => 'LDSW401', 'nombre' => 'Desarrollo para front end', 'semestre' => 4, 'creditos' => 8, 'prerrequisitos' => ['LDSW202']], // supuesto
            ['clave' => 'LDSW402', 'nombre' => 'Optimización de medios digitales para la web: audio y video', 'semestre' => 4, 'creditos' => 8, 'prerrequisitos' => ['LDSW303']], // supuesto
            ['clave' => 'LDSW403', 'nombre' => 'Optativa abierta I: Ciberseguridad avanzada', 'semestre' => 4, 'creditos' => 8, 'prerrequisitos' => ['LDSW201']], // supuesto
            ['clave' => 'LDSW404', 'nombre' => 'Lengua extranjera III', 'semestre' => 4, 'creditos' => 8, 'prerrequisitos' => ['LDSW304']],
            ['clave' => 'LDSW405', 'nombre' => 'Proyecto IV', 'semestre' => 4, 'creditos' => 8, 'prerrequisitos' => ['LDSW306']],
            ['clave' => 'LDSW406', 'nombre' => 'Optativa: Innovation and Entrepreneurship', 'semestre' => 4, 'creditos' => 8, 'prerrequisitos' => []],

            // Semestre 5
            ['clave' => 'LDSW501', 'nombre' => 'Conceptualización de servicios en la nube', 'semestre' => 5, 'creditos' => 8, 'prerrequisitos' => []],
            ['clave' => 'LDSW502', 'nombre' => 'Conceptualización de entornos de desarrollo de aplicaciones y servicios', 'semestre' => 5, 'creditos' => 8, 'prerrequisitos' => []],
            ['clave' => 'LDSW503', 'nombre' => 'Implementación de sistemas de gestión de contenido', 'semestre' => 5, 'creditos' => 8, 'prerrequisitos' => []],
            ['clave' => 'LDSW504', 'nombre' => 'Optativa abierta', 'semestre' => 5, 'creditos' => 8, 'prerrequisitos' => []],
            ['clave' => 'LDSW505', 'nombre' => 'Lengua extranjera IV', 'semestre' => 5, 'creditos' => 8, 'prerrequisitos' => ['LDSW404']],
            ['clave' => 'LDSW506', 'nombre' => 'Proyecto V', 'semestre' => 5, 'creditos' => 8, 'prerrequisitos' => ['LDSW405']],

            // Semestre 6
            ['clave' => 'LDSW601', 'nombre' => 'Modelado de bases de datos NoSQL', 'semestre' => 6, 'creditos' => 8, 'prerrequisitos' => ['LDSW301']], // supuesto
            ['clave' => 'LDSW602', 'nombre' => 'Diseño de aplicaciones móviles', 'semestre' => 6, 'creditos' => 8, 'prerrequisitos' => []],
            ['clave' => 'LDSW603', 'nombre' => 'Optimización de diseño para múltiples dispositivos', 'semestre' => 6, 'creditos' => 8, 'prerrequisitos' => []],
            ['clave' => 'LDSW604', 'nombre' => 'Optativa abierta', 'semestre' => 6, 'creditos' => 8, 'prerrequisitos' => []],
            ['clave' => 'LDSW605', 'nombre' => 'Proyecto VI', 'semestre' => 6, 'creditos' => 8, 'prerrequisitos' => ['LDSW506']],

            // Semestre 7
            ['clave' => 'LDSW701', 'nombre' => 'Diseño de interoperabilidad de servicios con IoT', 'semestre' => 7, 'creditos' => 8, 'prerrequisitos' => []],
            ['clave' => 'LDSW702', 'nombre' => 'Uso de big data para toma de decisiones', 'semestre' => 7, 'creditos' => 8, 'prerrequisitos' => []],
            ['clave' => 'LDSW703', 'nombre' => 'Tendencias en entornos de desarrollo de aplicaciones y servicios', 'semestre' => 7, 'creditos' => 8, 'prerrequisitos' => ['LDSW502']], // supuesto
            ['clave' => 'LDSW704', 'nombre' => 'Tendencias de diseño de servicios', 'semestre' => 7, 'creditos' => 8, 'prerrequisitos' => []],
            ['clave' => 'LDSW705', 'nombre' => 'Optativa abierta', 'semestre' => 7, 'creditos' => 8, 'prerrequisitos' => []],
            ['clave' => 'LDSW706', 'nombre' => 'Proyecto VII', 'semestre' => 7, 'creditos' => 8, 'prerrequisitos' => ['LDSW605']],

            // Semestre 8
            ['clave' => 'LDSW801', 'nombre' => 'Desarrollo para el IoT', 'semestre' => 8, 'creditos' => 8, 'prerrequisitos' => ['LDSW701']], // supuesto
            ['clave' => 'LDSW802', 'nombre' => 'Optativa abierta', 'semestre' => 8, 'creditos' => 8, 'prerrequisitos' => []],
            ['clave' => 'LDSW803', 'nombre' => 'Proyecto VIII', 'semestre' => 8, 'creditos' => 8, 'prerrequisitos' => ['LDSW706']],
            ['clave' => 'LDSW804', 'nombre' => 'Comunicación y gestión profesional', 'semestre' => 8, 'creditos' => 8, 'prerrequisitos' => []],
        ];

        // Paso 1: crear todas las materias
        foreach ($materias as $datos) {
            Materia::updateOrCreate(
                ['clave' => $datos['clave']],
                [
                    'nombre' => $datos['nombre'],
                    'semestre' => $datos['semestre'],
                    'creditos' => $datos['creditos'],
                ]
            );
        }

        // Paso 2: conectar los prerrequisitos
        foreach ($materias as $datos) {
            $materia = Materia::where('clave', $datos['clave'])->first();

            $ids = Materia::whereIn('clave', $datos['prerrequisitos'])->pluck('id');

            $materia->prerrequisitos()->sync($ids);
        }
    }
}
