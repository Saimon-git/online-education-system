<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Video') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Contenido del listado de cursos -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-full">
                    <!-- Aquí invocamos el componente Livewire -->
                    @livewire('video-detail', ['isUser' => $is_user, 'courseId' => $video->course->id,'video' => $video])
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
