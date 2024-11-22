<div class="container mx-auto p-6 bg-white border border-gray-200 shadow-md rounded-lg max-w-4xl">
    <h2 class="text-2xl font-bold mb-4">Agregar Videos al Curso</h2>

    <!-- Lista de Videos -->
    @foreach ($videos as $index => $video)
        <div class="mb-6 border-b pb-4">
            <h3 class="text-lg font-semibold mb-2">Video {{ $index + 1 }}</h3>

            <!-- Título del Video -->
            <div class="mb-4">
                <label for="title-{{ $index }}" class="block text-gray-700 font-medium mb-2">Título</label>
                <input
                    type="text"
                    id="title-{{ $index }}"
                    wire:model="videos.{{ $index }}.title"
                    class="w-full border border-gray-300 rounded-lg p-2"
                    placeholder="Ingrese el título del video"
                    required
                />
                @error("videos.{$index}.title") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- URL del Video -->
            <div class="mb-4">
                <label for="url-{{ $index }}" class="block text-gray-700 font-medium mb-2">URL</label>
                <input
                    type="text"
                    id="url-{{ $index }}"
                    wire:model="videos.{{ $index }}.url"
                    class="w-full border border-gray-300 rounded-lg p-2"
                    placeholder="Ingrese la URL del video"
                    required
                />
                @error("videos.{$index}.url") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Duración -->
            <div class="mb-4">
                <label for="duration-{{ $index }}" class="block text-gray-700 font-medium mb-2">Duración</label>
                <input
                    type="text"
                    id="duration-{{ $index }}"
                    wire:model="videos.{{ $index }}.duration"
                    class="w-full border border-gray-300 rounded-lg p-2"
                    placeholder="Duración del video (e.g., 5:32)"
                    required
                />
                @error("videos.{$index}.duration") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Botón para Eliminar Video -->
            <button
                type="button"
                wire:click="removeVideo({{ $index }})"
                class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded"
            >
                Eliminar Video
            </button>
        </div>
    @endforeach

    <!-- Botón para Agregar Nuevo Video -->
    <x-secondary-button
        wire:click="addVideo"
        :type="'button'">
        Agregar Nuevo Video
    </x-secondary-button>

    <!-- Botón para Guardar Videos -->
    <div class="flex justify-end">
        <x-primary-button
            wire:click="saveVideos"
            :type="'button'">
            Guardar Videos
        </x-primary-button>

    </div>

</div>
