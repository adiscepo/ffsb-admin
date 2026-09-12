<?php

namespace App\Domains\Kanban\Services;

use App\Domains\Kanban\Events\UserAssignedCard;
use App\Domains\Kanban\Kanban;
use App\Domains\Kanban\KanbanCard as Card;
use App\Domains\Kanban\KanbanColumn;
use App\Models\User;
use DateTime;

class CardService
{
    public function createCard(
        int $kanban_column_id,
        string $title,
        int $author_id,
        ?string $description = null,
        ?DateTime $deadline = null,
    ): Card {
        $kanban_column = KanbanColumn::findOrFail($kanban_column_id);
        assert($kanban_column->isActive());

        // Find the highest position in the column
        $maxPosition = Card::where('kanban_column_id', $kanban_column_id)->max('position') ?? 0;
        return Card::create([
            'title' => $title,
            'description' => $description,
            'deadline' => $deadline?->format('Y-m-d H:i:s'),
            'kanban_id' => $kanban_column->kanban->id,
            'kanban_column_id' => $kanban_column_id,
            'user_id' => $author_id,
            'position' => $maxPosition + 1,
        ]);
    }

    public function updateCard(
        Card $card,
        string $title,
        ?string $description = null,
        ?DateTime $deadline = null,
    ): Card {
        $card->update([
            'title' => $title,
            'description' => $description,
            'deadline' => $deadline,
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
        UserAssignedCard::dispatch(User::findOrFail($user_id), $card);
    }

    public function unassignUserCard(Card $card, int $user_id)
    {
        $card->assignee()->detach($user_id);
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
