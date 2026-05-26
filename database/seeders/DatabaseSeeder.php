<?php

namespace Database\Seeders;

use App\Models\Docu;
use App\Models\DocuLink;
use App\Models\EditionYear;
use App\Models\Evaluation;
use App\Models\Field;
use App\Models\ProductionHouse;
use App\Models\Tag;
use App\Models\User;
use App\Models\EvaluationCriterion;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        $user = User::factory()->create([
            'name' => 'Attilio',
            'email' => 'attiliodiscepoli@hotmail.be',
            'password' => Hash::make('Epanadiplose'),
        ]);
        User::factory()->create([
            'name' => 'Margaux',
            'email' => 'margaux.vandererven@ulb.be',
            'password' => Hash::make('Epanadiplose'),
        ]);

        EditionYear::factory()->create();

        $docu = Docu::factory()->create([
            'title' => 'Fire Of Love',
            'subtitles' => 'fr',
            'lang' => 'bil',
            'comment' => 'Disponible sur Disney+',
            'duration' => 93,
            'summary' => ' Fire of Love tells the story of two French lovers, Katia and Maurice Krafft, who died in a volcanic explosion doing the very thing that brought them together: unraveling the mysteries of our planet, while simultaneously capturing the most explosive volcano imagery ever recorded. Along the way, they changed our understanding of the natural world, and saved tens of thousands of lives. Previously unseen hours of pristine 16-millimeter film and thousands of photographs reveal the birth of modern volcanology through an unlikely lens — the love of its two pioneers. ',
            'target' => 'evening',
            'user_id' => $user->id,
            'year' => 2022
        ]);

        Evaluation::factory()->create([
            'user_id' => 2,
            'docu_id' => 1,
            'evaluation' => '{"1":{"note":"5","comment":"les images d\'archives, destin\u00e9es \u00e0 une documentation pareille et les voir ensemble comme ca c\'est magnifique"},"2":{"note":"5","comment":"juste waw, la lave est si envoutante"},"3":{"note":"3","comment":"on ne parle pas bcp bcp de science directement"},"4":{"note":"5"},"5":{"comment":"une g\u00e9ochimiste et un g\u00e9ologue, toustes deux vulcanologues, rien de plus","note":"5"},"6":{"comment":"le film est magnifique, c\'est un sujet sous-cot\u00e9 et voir une telle oeuvre sur ce sujet c\'est g\u00e9nial","note":"5"},"7":{"comment":"pour une soir\u00e9e compl\u00e8te c\'est g\u00e9nial","note":"5"},"8":{"comment":"je le verrai parfaitement le lundi d\'inauguration, il est top (ou alors le mardi en tant que blockbuster, qui sont moins scientifiques, mais n\'importe dans tous les cas il est g\u00e9nial)","note":"5"},"9":{"comment":"sur disney+ et \u00e0 \u00e9t\u00e9 au cin\u00e9 toute l\'ann\u00e9e pass\u00e9e","note":"3"},"10":{"comment":"je change d\'\u00e9tudes.","note":"5"}}',
            "comment" => "j'ai peur de la fin, j'ai eu raison."
        ]);

        Evaluation::factory()->create([
            'user_id' => 1,
            'docu_id' => 1,
            'evaluation' => '{"1":{"note":"5","comment":"Splendide"},"2":{"note":"6","comment":"Ces images d\'archives combinées aux animations sur les cartes postales, je suis fan"},"3":{"note":"4","comment":"Un peu de vulgarisation sur la tectonique des plaques et compagnie mais pas centré la dessus"},"4":{"note":"5", "comment":"C\'est un beau volcan, c\'est une belle histoire 🎶"},"5":{"comment":"Ce sont eux, leur vie, leurs archives","note":"5"},"6":{"comment":"Même mon frère qui faisait des va et vient dans le kot m\'a demandé de mettre sur pause quand il allait aux toilettes","note":"5"},"7":{"comment":"Parfait","note":"5"},"8":{"comment":"Alors les petit.e.s potes, si il est pas pris pour le programe je quitte le FFSB","note":"6"},"9":{"comment":"Alors j\'aurais pu mettre moins pcq c\'est sur Disney+ mais j\'avais pas envie qu\'il y ait de l\'orange sur l\'évaluation
Biaisé ? Complètement","note":"3"},"10":{"comment":"💖","note":"6"}}'
        ]);

        EvaluationCriterion::factory()->create([
            'name' => 'Montage et réalisation',
            'description' => 'Produit par Francis Ford Coppola ou Même ma cousine de 5 ans a fait mieux avec iMovie',
        ]);
        EvaluationCriterion::factory()->create([
            'name' => 'Qualité graphique',
            'description' => 'Animations, illustrations des propos, etc.',
        ]);
        EvaluationCriterion::factory()->create([
            'name' => 'Vulgarisation',
            'description' => '',
        ]);
        EvaluationCriterion::factory()->create([
            'name' => 'Facilité de compréhension',
            'description' => 'Woaw, Mamy aurait tout compris ! ou Même l\'intervenant n\'a pas compris le docu',
        ]);
        EvaluationCriterion::factory()->create([
            'name' => 'Précision des informations',
            'description' => 'Est-ce que les intervenants du documentaires sont des experts du sujets ou des randoms qu\'on a pris dans la rue ?',
        ]);
        EvaluationCriterion::factory()->create([
            'name' => 'Capacité à susciter l\'intérêt',
            'description' => 'C\'est parfait pour comprendre suffisement bien le propos ou je me suis endormi deux fois devant et c\'est toujours pas fini',
        ]);
        EvaluationCriterion::factory()->create([
            'name' => 'Durée',
            'description' => 'C\'est parfait pour comprendre suffisement bien le propos ou Je me suis endormi deux fois devant et c\'est toujours pas fini',
        ]);
        EvaluationCriterion::factory()->create([
            'name' => 'Intérêt pour le Festival',
            'description' => 'Prix du Jury ou Même si le festival est gratuit, faudra me payer pour aller le voir',
        ]);
        EvaluationCriterion::factory()->create([
            'name' => 'Accès publique',
            'description' => 'Le documentaire est un exclusivité qu\'on est les seul.e.s à avoir vu ou On trouve le docu en 2 secondes en tapant le sujet sur Google, même Théo a vu ce documentaire"',
        ]);
        EvaluationCriterion::factory()->create([
            'name' => 'Impact général',
            'description' => 'Ok, je change d\'études pour aller dans ce domaine ou Je souhaite que le rideau de douche froid colle au cul du réalisateur à chaque fois qu\'il se lave pour le restant de ses jours',
        ]);


        $production_house = ProductionHouse::factory()->create([

        ]);

        // $production_houses = ProductionHouse::factory()->count(10)->create();
        $docu_fields = Field::factory()->count(5)->create();
        $docu_tags = Tag::factory()->count(3)->create();

        $docu->fields()->attach($docu_fields->find(2));

        $docus = Docu::factory()->count(50)
            // ->hasAttached($production_houses->random(1), [], 'from')
            ->has(Evaluation::factory()->count(1)->for($user))
            ->has(ProductionHouse::factory()->count(rand(1, 3)), 'from')
            ->has(DocuLink::factory()->count(rand(1, 2)), 'see_at')
            ->create();

        // Evaluation::factory()->count(10)->for($user)->for($docus->random())->create();

        // use ($var) is needed to include $var to the closure's environment
        $docus->each(function (Docu $docu) use ($docu_fields, $docu_tags) {
            $docu->fields()
                 ->attach($docu_fields->random(random_int(1, 2)));
            if (fake()->boolean()) {
                $docu->tags()->attach($docu_tags->random(random_int(0, 2)));
            }
        });
    }
}
