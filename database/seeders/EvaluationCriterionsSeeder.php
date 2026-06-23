<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Domains\Evaluations\EvaluationCriterion;
use Illuminate\Database\Seeder;

class EvaluationCriterionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $evaluation_criterions = collect();
        $evaluation_criterions->push(EvaluationCriterion::factory()->create([
            'name' => 'Montage et réalisation',
            'description' => 'Produit par Francis Ford Coppola ou Même ma cousine de 5 ans a fait mieux avec iMovie',
        ]));
        $evaluation_criterions->push(EvaluationCriterion::factory()->create([
            'name' => 'Qualité graphique',
            'description' => 'Animations, illustrations des propos, etc.',
        ]));
        $evaluation_criterions->push(EvaluationCriterion::factory()->create([
            'name' => 'Vulgarisation',
            'description' => '',
        ]));
        $evaluation_criterions->push(EvaluationCriterion::factory()->create([
            'name' => 'Facilité de compréhension',
            'description' => 'Woaw, Mamy aurait tout compris ! ou Même l\'intervenant n\'a pas compris le docu',
        ]));
        $evaluation_criterions->push(EvaluationCriterion::factory()->create([
            'name' => 'Précision des informations',
            'description' => 'Est-ce que les intervenants du documentaires sont des experts du sujets ou des randoms qu\'on a pris dans la rue ?',
        ]));
        $evaluation_criterions->push(EvaluationCriterion::factory()->create([
            'name' => 'Capacité à susciter l\'intérêt',
            'description' => 'Ca m\'a scotché ou J\'ai mis en x2 histoire de l\'avoir vite fini et de l\'ajouter dans la liste des docus")',
        ]));
        $evaluation_criterions->push(EvaluationCriterion::factory()->create([
            'name' => 'Durée',
            'description' => 'C\'est parfait pour comprendre suffisement bien le propos ou Je me suis endormi deux fois devant et c\'est toujours pas fini',
        ]));
        $evaluation_criterions->push(EvaluationCriterion::factory()->create([
            'name' => 'Intérêt pour le Festival',
            'description' => 'Prix du Jury ou Même si le festival est gratuit, faudra me payer pour aller le voir',
        ]));
        $evaluation_criterions->push(EvaluationCriterion::factory()->create([
            'name' => 'Accès publique',
            'description' => 'Le documentaire est un exclusivité qu\'on est les seul.e.s à avoir vu ou On trouve le docu en 2 secondes en tapant le sujet sur Google, même Théo a vu ce documentaire"',
        ]));
        $evaluation_criterions->push(EvaluationCriterion::factory()->create([
            'name' => 'Impact général',
            'description' => 'Ok, je change d\'études pour aller dans ce domaine ou Je souhaite que le rideau de douche froid colle au cul du réalisateur à chaque fois qu\'il se lave pour le restant de ses jours',
        ]));
    }
}
