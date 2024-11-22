<div class="container mx-auto p-6 max-w-4xl bg-white border border-gray-200 shadow-md rounded-lg">
    <!-- Título del video -->
    <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ $video->title }}</h1>

    <!-- Reproductor de Video -->
    <div class="aspect-w-16 aspect-h-16 mb-6">
        <iframe
            src="{{ $embedUrl }}"
            frameborder="0"
            allow="autoplay; encrypted-media"
            allowfullscreen
            class="w-full h-full rounded-lg"
        ></iframe>
    </div>

    <!-- Descripción del Video -->
    <p class="text-gray-600 mb-6">{{ $video->description }}</p>

    <!-- Botones de acción -->
    @if($isUser)
        @if(!$isCompleted)
            <x-primary-button
                wire:click="markAsCompleted"
                :type="'button'"
                :class="'float-right'">
                Marcar como completado
            </x-primary-button>
        @else
            <button
                class="flex bg-green-500 text-white font-bold py-2 px-4 rounded float-right"
            >
                Completado
            </button>
        @endif
    @endif

    <!-- Sección de Comentarios -->
    <livewire:course-comments :videoId="$video->id" :isUser="$isUser" />
</div>
