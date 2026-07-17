<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Competencia;

class CompetenciaSeeder extends Seeder
{
    public function run()
    {
        $data = array (
  0 => 
  array (
    'id' => 1,
    'nivel_id' => 1,
    'nombre' => 'Aprendizaje continuo',
    'descripcion' => 'Identificar, incorporar y aplicar nuevos conocimientos sobre regulaciones vigentes, tecnologías disponibles, métodos y programas de trabajo, para entender actualizada la efectividad de sus prácticas laborales y su visión del contexto. ',
    'activo' => true,
  ),
  1 => 
  array (
    'id' => 2,
    'nivel_id' => 1,
    'nombre' => 'Orientación a resultados',
    'descripcion' => 'Realizar las funciones y cumplir los compromisos organizacionales con eficacia, calidad y oportunidad',
    'activo' => true,
  ),
  2 => 
  array (
    'id' => 3,
    'nivel_id' => 1,
    'nombre' => 'Orientación al usuario y al ciudadano',
    'descripcion' => 'Dirigir las decisiones y acciones a la satisfacción de las necesidades e intereses de los usuarios (internos y externos) y de los ciudadanos, de conformidad con las responsabilidades públicas asignadas a la entidad',
    'activo' => true,
  ),
  3 => 
  array (
    'id' => 4,
    'nivel_id' => 1,
    'nombre' => 'Compromiso con la organización',
    'descripcion' => 'Alinear el propio comportamiento a las necesidades, prioridades y metas organizacionales',
    'activo' => true,
  ),
  4 => 
  array (
    'id' => 5,
    'nivel_id' => 1,
    'nombre' => 'Trabajo en equipo',
    'descripcion' => 'Trabajar con otros de forma integrada y armónica para la consecución de metas institucionales comunes',
    'activo' => true,
  ),
  5 => 
  array (
    'id' => 6,
    'nivel_id' => 1,
    'nombre' => 'Adaptación al cambio',
    'descripcion' => 'Enfrentar con flexibilidad las situaciones nuevas asumiendo un manejo positivo y constructivo de los cambios',
    'activo' => true,
  ),
  6 => 
  array (
    'id' => 7,
    'nivel_id' => 3,
    'nombre' => 'Confiabilidad técnica',
    'descripcion' => 'Contar con los conocimientos técnicos requeridos y aplicarlos a situaciones concretas de trabajo, con altos entidad estándares de calidad',
    'activo' => true,
  ),
  7 => 
  array (
    'id' => 8,
    'nivel_id' => 3,
    'nombre' => 'Creatividad e innovación',
    'descripcion' => 'Generar y desarrollar nuevas ideas, conceptos, métodos y soluciones orientados a mantener la competitividad de -la entidad y el uso eficiente de los recursos',
    'activo' => true,
  ),
  8 => 
  array (
    'id' => 9,
    'nivel_id' => 3,
    'nombre' => 'Iniciativa',
    'descripcion' => 'Anticiparse a los problemas proponiendo alternativas de solución',
    'activo' => true,
  ),
  9 => 
  array (
    'id' => 10,
    'nivel_id' => 3,
    'nombre' => 'Construcción de relaciones',
    'descripcion' => 'Capacidad para relacionarse en diferentes entornos con el fin de cumplir los objetivos institucionales',
    'activo' => true,
  ),
  10 => 
  array (
    'id' => 11,
    'nivel_id' => 3,
    'nombre' => 'Conocimiento del entorno',
    'descripcion' => 'Conocer e interpretar la organización. su funcionamiento y sus relaciones con el entorno',
    'activo' => true,
  ),
  11 => 
  array (
    'id' => 12,
    'nivel_id' => 4,
    'nombre' => 'Aporte técnico-profesional',
    'descripcion' => 'Poner a disposición de la Administración sus saberes profesionales específicos y sus experiencias previas, gestionando la actualización de sus saberes expertos',
    'activo' => true,
  ),
  12 => 
  array (
    'id' => 13,
    'nivel_id' => 4,
    'nombre' => 'Comunicación efectiva',
    'descripcion' => 'Establecer comunicación efectiva y positiva con superiores jerárquicos, pares y ciudadanos, tanto en la expresión escrita, como verbal y gestual',
    'activo' => true,
  ),
  13 => 
  array (
    'id' => 14,
    'nivel_id' => 4,
    'nombre' => 'Gestión de procedimientos',
    'descripcion' => 'Desarrollar las tareas a cargo en el marco de los procedimientos vigentes calidad establecidos y proponer e introducir acciones para acelerar la mejora continua y la productividad',
    'activo' => true,
  ),
  14 => 
  array (
    'id' => 15,
    'nivel_id' => 4,
    'nombre' => 'Instrumentación de decisiones',
    'descripcion' => 'Decidir sobre las cuestiones en las que Discrimina con efectividad entre las es responsable con criterios de economía, eficacia, eficiencia y transparencia de la decisión',
    'activo' => true,
  ),
  15 => 
  array (
    'id' => 16,
    'nivel_id' => 5,
    'nombre' => 'Confiabilidad Técnica',
    'descripcion' => 'Contar con los conocimientos técnicos requeridos y aplicarlos a situaciones concretas de trabajo, con altos estándares de calidad',
    'activo' => true,
  ),
  16 => 
  array (
    'id' => 17,
    'nivel_id' => 5,
    'nombre' => 'Disciplina',
    'descripcion' => 'Adaptarse a las políticas institucionales y generar información acorde con los procesos',
    'activo' => true,
  ),
  17 => 
  array (
    'id' => 18,
    'nivel_id' => 6,
    'nombre' => 'Manejo de la información',
    'descripcion' => 'Manejar con responsabilidad la información personal e institucional de que dispone',
    'activo' => true,
  ),
  18 => 
  array (
    'id' => 19,
    'nivel_id' => 6,
    'nombre' => 'Relaciones interpersonales',
    'descripcion' => 'Establecer y mantener relaciones de trabajo positivas, basadas en la comunicación abierta y fluida y en el respeto por los demás',
    'activo' => true,
  ),
);
        foreach ($data as $item) {
            Competencia::firstOrCreate(["id" => $item["id"]], $item);
        }
    }
}
