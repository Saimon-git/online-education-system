<div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Mostrar información relevante para admins -->
                @if ($isAdmin)
                    <h3 class="text-lg font-bold mb-4">Bienvenido, Administrador</h3>
                    <ul class="list-disc pl-5">
                        <li>
                            <a href="{{ route('courses.index') }}" class="text-blue-500 hover:underline">
                                Gestionar Cursos
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('comments.index') }}" class="text-blue-500 hover:underline">
                                Revisar Comentarios Pendientes
                            </a>
                        </li>
                    </ul>
                @else
                    <!-- Mostrar información relevante para usuarios regulares -->
                    <h3 class="text-lg font-bold mb-4">Bienvenido al Dashboard</h3>
                    <ul class="list-disc pl-5">
                        <li>
                            <a href="{{ route('courses.my') }}" class="text-blue-500 hover:underline">
                                Ver Mis Cursos
                            </a>
                        </li>
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
