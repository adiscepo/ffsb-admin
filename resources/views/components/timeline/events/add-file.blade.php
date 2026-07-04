@use('App\Domains\Files\File')

@php
    $file = File::find($event->payload['file_id']);
@endphp
<x-timeline-item icon="document-plus" color="green" :author="$event->author->name" :time="$event->created_at->diffForHumans()">
    <div class="flex gap-x-1 items-center">
        {{-- <flux:avatar size="xs" circle initials="{{ $member->initials() }}" /> --}}
        @if ($file != null)
            <p>a ajouté le fichier</p>
            <a href="{{ Storage::url($file->full_path) }}" download="{{ $file->client_filename }}"
                class="underline">{{ $file->client_name }}</a>
        @else
            <p>a ajouté un fichier</p>
        @endif
    </div>
</x-timeline-item>
