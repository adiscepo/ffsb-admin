<?php

namespace Database\Seeders;

use App\Domains\Kanban\Kanban;
use App\Domains\Kanban\Services\CardService;
use App\Domains\Kanban\Services\KanbanService;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

class KanbanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(KanbanService $kanban_service, CardService $card_service): void
    {
        $kanban = $kanban_service->createKanban(1, 'FFSB 2026', 'L\'endroit où sont condensées toutes les tâches à effectuer pour l\'organisation du FFSB 2026');
        $column_id = $kanban->columns[0]->id;
        $card_service->createCard($kanban->id, $column_id, 'Trier le drive', 1);
        $card_service->createCard($kanban->id, $column_id, 'Faire le BP', 1);
        $card_service->createCard($kanban->id, $column_id, 'Mettre à jour le subside tracker', 1, deadline: CarbonImmutable::parse('2026-12-12 12:00:00'));
    }
}
