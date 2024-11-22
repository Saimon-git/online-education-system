<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">
        @if(!$myCourses)
            Listado de Cursos
        @else
            Mis Cursos
        @endif

    </h1>

    @if(!$isUser)
        <a href="{{ route('courses.create') }}" class="underline float-right -mt-10">
            <x-primary-button
                :type="'button'">
                Crear curso
            </x-primary-button>
        </a>
    @endif


    <x-flash-message on="registered" type="success" :message="session('message')"/>


    <!-- Filtros -->
    <div class="flex flex-col md:flex-row items-center gap-4 mb-6 mt-16">
        <!-- Buscar por Nombre -->
        <x-text-input
            wire:model.live.debounce.500ms="filters.name"
            class="border border-gray-300 rounded-lg p-2 w-full md:w-1/3"
            type="text"
            placeholder="Buscar por nombre"
        />


        <!-- Filtrar por Categoría -->
        <select
            wire:model.live="filters.category_id"
            class="border border-gray-300 rounded-lg p-2 w-full md:w-1/3"
        >
            <option value="">Todas las Categorías</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>

        <!-- Filtrar por Rango de Edades -->
        <select
            wire:model.live="filters.age_group"
            class="border border-gray-300 rounded-lg p-2 w-full md:w-1/3"
        >
            <option value="">Todos los Rangos de Edades</option>
            @foreach($ageRanges as $range)
                <option value="{{ $range }}">{{ $range }}</option>
            @endforeach
        </select>
    </div>

    <!-- Tabla de Cursos -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead>
            <tr class="bg-gray-100 border-b border-gray-300">
                <th class="px-6 py-3 text-left text-gray-600 font-medium">Nombre</th>
                <th class="px-6 py-3 text-left text-gray-600 font-medium">Descripción</th>
                <th class="px-6 py-3 text-left text-gray-600 font-medium">Categoría</th>
                <th class="px-6 py-3 text-left text-gray-600 font-medium">Rango de Edades</th>
                <th class="px-6 py-3 text-left text-gray-600 font-medium">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @forelse($courses as $course)
                <tr class="border-b border-gray-200 hover:bg-gray-50" wire:key="course-{{ $course->id }}">
                    <td class="px-6 py-3">{{ $course->name }}</td>
                    <td class="px-6 py-3">{{ $course->description }}</td>
                    <td class="px-6 py-3">{{ $course->category->name }}</td>
                    <td class="px-6 py-3">{{ $course->age_group }}</td>
                    <td class="px-6 py-3">
                        @if($isUser)
                            @if($course->is_registered)
                                <!-- Botón de Ver Detalles -->
                                <a href="{{ route('courses.show', $course->id) }}" class="underline">
                                    <x-secondary-button
                                        :type="'button'">
                                        Ver Detalles
                                    </x-secondary-button>
                                </a>

                            @else
                                <!-- Botón de Registro -->
                                <x-primary-button
                                    wire:click="register({{ $course->id }})"
                                    :type="'button'">
                                    Registrarse
                                </x-primary-button>
                            @endif
                            <x-action-message class="me-3" on="registered">
                                Registrado!
                            </x-action-message>
                        @else
                            <a href="{{ route('courses.show', $course->id) }}" class="underline">
                                <x-secondary-button
                                    :type="'button'">
                                    Ver Detalles
                                </x-secondary-button>
                            </a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-3 text-center text-gray-500">No se encontraron cursos.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="flex justify-between items-center mt-4">
        {{ $courses->links() }}
    </div>
</div>

