<?php

namespace App\Domains\Kanban\Services;

use App\Domains\Kanban\Kanban;
use App\Domains\Kanban\KanbanCard as Card;
use DateTimeImmutable;

class CardService
{
    public function createCard(
        int $kanban_id,
        int $kanban_column_id,
        string $title,
        int $author_id,
        ?string $description = null,
        ?DateTimeImmutable $deadline = null,
    ): Card {
        $kanban = Kanban::findOrFail($kanban_id);

        // Find the highest position in the column
        $maxPosition = Card::where('kanban_column_id', $kanban_column_id)->max('position') ?? 0;
        return Card::create([
            'title' => $title,
            'description' => $description,
            'deadline' => $deadline?->format('Y-m-d H:i:s'),
            'kanban_id' => $kanban->id,
            'kanban_column_id' => $kanban_column_id,
            'user_id' => $author_id,
            'position' => $maxPosition + 1,
        ]);
    }

    public function updateCard(
        Card $card,
        string $title,
        ?string $description = null,
    ): Card {
        $card->update([
            'title' => $title,
            'description' => $description,
        ]);

        return $card->fresh();
    }

    public function moveCard(Card $card, string $newColumnId, int $newPosition): Card
    {
        $oldColumnId = $card->board_column_id;

        if ($oldColumnId !== $newColumnId) {
            $card->update(['kanban_column_id' => $newColumnId]);
        }

        // Reorder cards in the new column
        $this->reorderCardsInColumn($newColumnId, $card->id, $newPosition);

        return $card->fresh();
    }

    public function assignUserCard(Card $card, int $user_id)
    {
        $card->assignee()->attach($user_id);
    }

    private function reorderCardsInColumn(string $columnId, string $cardId, int $newPosition): void
    {
        // Get all cards in the column except the one being moved
        $cards = Card::where('kanban_column_id', $columnId)
            ->where('id', '!=', $cardId)
            ->orderBy('position')
            ->get();

        // Insert the moved card at the new position
        $cards->splice($newPosition, 0, [Card::find($cardId)]);

        // Update positions
        foreach ($cards as $index => $card) {
            $card->update(['position' => $index]);
        }
    }
}
