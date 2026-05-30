<?php

namespace Database\Seeders;

use App\Domains\Evaluations\Evaluation;
use App\Domains\Evaluations\EvaluationCriterion;
use App\Domains\Evaluations\EvaluationField;
use App\Models\Docu;
use App\Models\DocuLink;
use App\Models\EditionYear;
use App\Models\Field;
use App\Models\ProductionHouse;
use App\Models\Tag;
use App\Models\User;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $users = collect();
        $users = $users->push(
            User::factory()->create([
                'name' => 'Attilio',
                'email' => 'attiliodiscepoli@hotmail.be',
                'password' => Hash::make('Epanadiplose'),
            ])
        );
        $users = $users->push(User::factory()->create([
            'name' => 'Margaux',
            'email' => 'margaux.vandererven@ulb.be',
            'password' => Hash::make('Epanadiplose'),
        ]));

        $users = $users->merge(User::factory(4)->create());


        $edition_years = collect();
        $edition_years = $edition_years->push(EditionYear::factory()->create());
        $edition_years = $edition_years->push(EditionYear::factory()->create(['year' => 2024]));

        $production_houses = ProductionHouse::factory(25)->create();

        $docu_fields = Field::factory(5)->create();

        $docus = Docu::factory(150)->create([
            'user_id' => fn() => $users->random()->id,
            'edition_year_id' => fn() => $edition_years->random()->id,
        ]);

        foreach ($docus as $docu) {
            $docu->from()->attach(
                $production_houses->random(rand(1, 3))->pluck('id')
            );
            $docu->fields()->attach(
                $docu_fields->random(rand(1, 3))->pluck('id')
            );
        }

        $evaluations = collect();
        foreach ($docus as $docu) {
            foreach ($users as $user) {
                if (rand(0, 1) % 2 == 0) {
                    $evaluations = $evaluations->merge(
                        Evaluation::factory(1)->create([
                            'user_id' => $user->id,
                            'docu_id' => $docu->id,
                        ])
                    );
                }
            }
        }

        foreach ($docus as $docu) {
            DocuLink::factory(random_int(0, 2))->create(['docu_id' => $docu->id]);
        }

        // $docu = Docu::factory()->create([
        //     'title' => 'Fire Of Love',
        //     'subtitles' => 'fr',
        //     'lang' => 'bil',
        //     'comment' => 'Disponible sur Disney+',
        //     'duration' => 93,
        //     'summary' => ' Fire of Love tells the story of two French lovers, Katia and Maurice Krafft, who died in a volcanic explosion doing the very thing that brought them together: unraveling the mysteries of our planet, while simultaneously capturing the most explosive volcano imagery ever recorded. Along the way, they changed our understanding of the natural world, and saved tens of thousands of lives. Previously unseen hours of pristine 16-millimeter film and thousands of photographs reveal the birth of modern volcanology through an unlikely lens — the love of its two pioneers. ',
        //     'target' => 'evening',
        //     'user_id' => $users[0]->id,
        //     'year' => 2022
        // ]);

        // Evaluation::factory()->create([
        //     'user_id' => 2,
        //     'docu_id' => 1,
        //     "comment" => "j'ai peur de la fin, j'ai eu raison."
        // ]);

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
            'description' => 'C\'est parfait pour comprendre suffisement bien le propos ou je me suis endormi deux fois devant et c\'est toujours pas fini',
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

        foreach ($evaluation_criterions as $criterion) {
            foreach ($evaluations as $evaluation) {
                EvaluationField::factory()->create([
                    'evaluation_criterion_id' => $criterion->id,
                    'evaluation_id' => $evaluation->id,
                ]);
            }
        }


        // // $production_houses = ProductionHouse::factory()->count(10)->create();
        // $docu_tags = Tag::factory()->count(3)->create();

        // $docu->fields()->attach($docu_fields->find(2));

        // // $evaluations = Evaluation::factory()->recycle($users)->recycle($docus)->has(EvaluationField::factory(count($evaluation_criterions))->recycle($evaluation_criterions), 'fields')->create();

        // $evaluation_fields = [];
        // foreach ($evaluation_criterions as $id => $criterion) {
        //     $evaluation_fields[] = EvaluationField::factory()->for($criterion, 'criterion')->create();
        // }

        // $docus = Docu::factory()->count(50)
        //     ->has(Evaluation::factory()->count(1)->recycle($users)->has($evaluation_fields))
        //     ->has(ProductionHouse::factory()->count(rand(1, 3)), 'from')
        //     ->has(DocuLink::factory()->count(rand(1, 2)), 'see_at')
        //     ->create();

        // // Evaluation::factory()->count(10)->for($user)->for($docus->random())->create();

        // // use ($var) is needed to include $var to the closure's environment
        // // $docus->each(function (Docu $docu) use ($docu_fields, $docu_tags) {
        // //     $docu->fields()
        // //         ->attach($docu_fields->random(random_int(1, 2)));
        // //     if (fake()->boolean()) {
        // //         $docu->tags()->attach($docu_tags->random(random_int(0, 2)));
        // //     }
        // // });
    }
}
