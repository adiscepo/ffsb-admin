<?php

namespace Database\Seeders;

use App\Domains\Bugs\Bug;
use App\Domains\Evaluations\Evaluation;
use App\Domains\Evaluations\EvaluationField;
use App\Domains\Docus\Docu;
use App\Domains\Docus\DocuLink;
use App\Domains\Docus\Field;
use App\Domains\Evaluations\EvaluationCriterion;
use App\Domains\Programs\Enum\ProgramEventKind;
use App\Domains\Programs\Program;
use App\Domains\Programs\ProgramEvent;
use App\Models\EditionYear;
use App\Models\ProductionHouse;
use App\Models\Status;
use App\Domains\Tags\Tag;
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
        $docu_tag_remove = Tag::factory()->create(['name' => 'Supprimé', 'color' => 'red', 'model' => Docu::class]);
        $docu_tags = collect();
        $docu_tags->push(Tag::factory()->create(['name' => 'Sélection Jury', 'color' => 'yellow', 'model' => Docu::class]));
        $docu_tags->push(Tag::factory()->create(['name' => 'Bonus', 'color' => 'lime', 'model' => Docu::class]));

        $docus = Docu::factory(150)->create([
            'user_id' => fn() => $users->random()->id,
            'edition_year_id' => fn() => $edition_years->random()->id,
        ]);

        foreach ($docus as $docu) {
            $docu->from()->attach(
                $production_houses->random(rand(1, 2))->pluck('id')
            );
            $docu->fields()->attach(
                $docu_fields->random(rand(1, 2))->pluck('id')
            );
            if (random_int(0, 50) == 0) {
                $docu->tags()->attach($docu_tag_remove);
            }
            if (random_int(0, 100) == 0) {
                $docu->tags()->attach($docu_tags->random());
            }
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

        foreach (EvaluationCriterion::all() as $criterion) {
            foreach ($evaluations as $evaluation) {
                EvaluationField::factory()->create([
                    'evaluation_criterion_id' => $criterion->id,
                    'evaluation_id' => $evaluation->id,
                ]);
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

        // Support - bugs

        $bug_tags = collect();
        $bug_tags->push(Tag::factory()->create(['name' => 'Affichage', 'color' => 'blue', 'model' => Bug::class]));
        $bug_tags->push(Tag::factory()->create(['name' => 'Serveur', 'color' => 'green', 'model' => Bug::class]));
        $bug_tags->push(Tag::factory()->create(['name' => 'Logique', 'color' => 'green', 'model' => Bug::class]));
        $bug_tags->push(Tag::factory()->create(['name' => 'Urgent', 'color' => 'red', 'model' => Bug::class]));
        $bug_tags->push(Tag::factory()->create(['name' => 'Amélioration', 'color' => 'yellow', 'model' => Bug::class]));

        // $bug_statuses = collect();
        // $bug_statuses->push(Status::factory()->create(['name' => 'Ouvert', 'color' => 'zinc', 'model' => Bug::class]));
        // $bug_statuses->push(Status::factory()->create(['name' => 'Résolu', 'color' => 'green', 'model' => Bug::class]));
        // $bug_statuses->push(Status::factory()->create(['name' => 'Clôturé', 'color' => 'red', 'model' => Bug::class]));
        // $bug_statuses->push(Status::factory()->create(['name' => 'Fermé', 'color' => 'red', 'model' => Bug::class]));

        $bugs = Bug::factory(10)->create([
            'user_id' => fn() => $users->random()->id,
            'assigned_to' => fn() => random_int(0, 4) == 0 ? 1 : null,
        ]);

        foreach ($bugs as $bug) {
            // $bug->statuses()->attach($bug_statuses->random());
            if (random_int(0, 5) == 0) {
                $bug->tags()->attach($bug_tags->random());
            }
            if (random_int(0, 10) == 0) {
                $bug->tags()->attach($bug_tags->random());
            }
        }

        // Program
        $program = Program::factory(1)->create([
            'name' => 'FFSB 2024',
            'start_date' => date_create('2024-03-18'),
            'end_date' => date_create('2024-03-24'),
            'edition_year_id' => $edition_years->last(),
            'user_id' => 1,
        ]);

        ProgramEvent::factory(1)->create([
            'start' => date_create('2024-03-18 18:00:00'),
            'kind' => ProgramEventKind::OTHER,
            'payload' => [
                'name' => 'Inauguration',
                'description' => "Nous vous convions à la soirée d'inauguration du Festival du Film Scientifique de Bruxelles et du Printemps des Sciences 2024 ! La soirée commencera à 18h30 et un drink et fingerfoods vous y sera offert.",
                'duration' => 30,
            ],
            'program_id' => $program->first()->id,
        ]);
    }
}
