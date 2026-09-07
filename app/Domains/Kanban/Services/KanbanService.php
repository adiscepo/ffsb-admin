<?php

declare(strict_types=1);

namespace App\Domains\Kanban\Services;

use App\Domains\Kanban\Kanban;
use App\Domains\Kanban\KanbanColumn;
use Illuminate\Support\Facades\DB;

class KanbanService
{
    public function createKanban(int $userId, string $name, ?string $description = null): Kanban
    {
        $board = Kanban::create([
            'name' => $name,
            'description' => $description,
            'user_id' => $userId,
        ]);

        $this->createDefaultColumns($board);

        return $board;
    }

    public function updateKanban(Kanban $kanban, string $name, ?string $description = null, ?string $status = null, ?array $columns = null): Kanban
    {
        DB::transaction(function () use ($kanban, $name, $description, $status, $columns): void {
            // Update board properties
            $updateData = [
                'name' => $name,
                'description' => $description,
            ];

            if ($status !== null) {
                $updateData['status'] = $status;
            }

            $kanban->update($updateData);

            // Update columns if provided
            if ($columns !== null) {
                $this->updateBoardColumns($kanban, $columns);
            }
        });

        return $kanban->fresh(['columns']);
    }

    /**
     * Update board columns (create, update, delete, reorder)
     * Handles all column operations in a single transaction
     *
     * @param  array  $columnsData  Array of column data with id (nullable), name, position
     */
    public function updateBoardColumns(Kanban $board, array $columnsData): void
    {
        $existingColumnIds = array_filter(array_column($columnsData, 'id'));

        // Delete columns that are not in the new list (validation already checked for cards)
        // Only delete if there are existing column IDs, otherwise whereNotIn([]) would delete all
        if ($existingColumnIds !== []) {
            $board->columns()->whereNotIn('id', $existingColumnIds)->delete();
        } else {
            // If no existing IDs, all columns are new, so delete all old columns
            $board->columns()->delete();
        }

        // Process each column (create or update)
        foreach ($columnsData as $columnData) {
            if (empty($columnData['id'])) {
                // Create new column
                KanbanColumn::create([
                    'kanban_id' => $board->id,
                    'name' => $columnData['name'],
                    'position' => $columnData['position'],
                ]);
            } else {
                // Update existing column
                KanbanColumn::where('id', $columnData['id'])
                    ->where('kanban_id', $board->id)
                    ->update([
                        'name' => $columnData['name'],
                        'position' => $columnData['position'],
                    ]);
            }
        }
    }

    public function deleteBoard(Kanban $kanban): bool
    {
        return $kanban->delete();
    }

    public function reorderColumns(Kanban $kanban, array $columns): void
    {
        foreach ($columns as $column) {
            $kanban->columns()->where('id', $column['id'])->update([
                'position' => $column['position'],
            ]);
        }
    }

    private function createDefaultColumns(Kanban $kanban): void
    {
        $defaultColumns = [
            ['name' => 'À faire', 'position' => 0, 'color' => 'blue', 'type' => null],
            ['name' => 'En cours', 'position' => 1, 'color' => 'purple', 'type' => null],
            ['name' => 'Terminées', 'position' => 2, 'color' => 'emerald', 'type' => 'fulfilled'],
            ['name' => 'Abandonnées', 'position' => 3, 'color' => 'red', 'type' => 'dropped'],
        ];

        foreach ($defaultColumns as $defaultColumn) {
            KanbanColumn::create([
                'name' => $defaultColumn['name'],
                'position' => $defaultColumn['position'],
                'kanban_id' => $kanban->id,
                'color' => $defaultColumn['color'],
                'type' => $defaultColumn['type'],
            ]);
        }
    }
}
