<div class="container mx-auto p-6 max-w-3xl bg-white border border-gray-200 shadow-md rounded-lg">
    <h1 class="text-2xl font-bold mb-6">Crear Nuevo Curso</h1>

    <!-- Mostrar mensaje de éxito -->
    <x-flash-message on="storeCourse" type="success" :message="session('message')" />

    @if (!$createdCourseId)
        <form wire:submit.prevent="createCourse">
            <!-- Nombre del Curso -->
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium mb-2">Nombre del Curso</label>
                <input
                    type="text"
                    id="name"
                    wire:model="name"
                    class="w-full border border-gray-300 rounded-lg p-2"
                    placeholder="Ingrese el nombre del curso"
                    required
                />
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Descripción del Curso -->
            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-medium mb-2">Descripción</label>
                <textarea
                    id="description"
                    wire:model="description"
                    class="w-full border border-gray-300 rounded-lg p-2"
                    placeholder="Ingrese la descripción del curso"
                    rows="4"
                    required
                ></textarea>
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Seleccionar Categoría -->
            <div class="mb-4">
                <label for="category" class="block text-gray-700 font-medium mb-2">Categoría</label>
                <select
                    id="category"
                    wire:model="category_id"
                    class="w-full border border-gray-300 rounded-lg p-2"
                    required
                >
                    <option value="">Seleccione una categoría</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Rango de Edades -->
            <div class="mb-4">
                <label for="age_group" class="block text-gray-700 font-medium mb-2">Rango de Edades</label>
                <select
                    id="age_group"
                    wire:model="age_group"
                    class="w-full border border-gray-300 rounded-lg p-2"
                    required
                >
                    <option value="" >Seleccione un rango de edades</option>
                    @foreach ($ageRanges as $range)
                        <option value="{{ $range }}">{{ $range }}</option>
                    @endforeach
                </select>
                @error('age_group') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Botón Crear -->
            <div class="flex justify-end">
                <x-primary-button>
                    Crear curso
                </x-primary-button>

            </div>
        </form>
    @else
        <!-- Mostrar el componente AddVideos -->
        @livewire('add-videos', ['courseId' => $createdCourseId])
    @endif
</div>
