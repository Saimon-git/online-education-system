<div>
    <!-- Header del curso -->

    <!-- Mensaje de Éxito -->
    <x-flash-message on="storeVideos" type="success" :message="session('message')" />
    @if (session()->has('message'))
        <div class="my-4 text-green-500 font-semibold">
            {{ session('message') }}
        </div>
    @endif
    <div class="container mx-auto p-6 max-w-4xl border border-gray-200 rounded-lg bg-white shadow-md">
        <div class="flex items-center justify-between mb-4">
            <span class="bg-blue-500 text-white px-4 py-auto rounded text-sm">
                {{ $userProgress }} %
            </span>
            <h1 class="text-2xl font-bold text-gray-800">{{ $course->name }}</h1>
            <span class="bg-blue-500 text-white px-4 py-1 rounded text-sm">{{ $course->category->name }}</span>
        </div>
        <p class="text-gray-600 mb-6">{{ $course->description }}</p>

        <div class="flex items-center space-x-4">
            <button
                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded"
                wire:click="continueCourse"
            >
                Continuar curso
            </button>
        </div>
    </div>

    <!-- Videos del curso -->
    <div class="container mx-auto my-3 p-6 max-w-4xl border border-gray-200 rounded-lg bg-white shadow-md">
        <livewire:course-videos :videos="$course->videos" />
    </div>

    <!-- Progreso y estadísticas -->
    @if (!$isUser)
        <div class="container mx-auto my-3 p-6 max-w-4xl border border-gray-200 rounded-lg bg-white shadow-md">
            <livewire:course-progress :users="$course->users" :courseId="$course->id" />
        </div>
        <div class="container mx-auto my-3 p-6 max-w-4xl border border-gray-200 rounded-lg bg-white shadow-md">
            <livewire:course-statistics :videos="$course->videos" />
        </div>
    @endif

</div>
