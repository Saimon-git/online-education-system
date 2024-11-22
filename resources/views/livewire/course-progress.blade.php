<div class="mb-6">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Progreso de Usuarios</h2>
    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
        <thead class="bg-gray-100 border-b border-gray-300">
        <tr>
            <th class="px-4 py-2 text-left text-gray-600 font-medium">Nombre</th>
            <th class="px-4 py-2 text-left text-gray-600 font-medium">Progreso</th>
            <th class="px-4 py-2 text-left text-gray-600 font-medium">Video Actual</th>
        </tr>
        </thead>
        <tbody>
        @forelse($users as $user)
            <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-2">{{ $user['name'] }}</td>
                <td class="px-4 py-2">{{ $user['progress'] }}%</td>
                <td class="px-4 py-2">
                    @if(isset($user['last_completed_video']))
                        <a href="{{ route('show.video', [$user['last_completed_video']['course_id'], $user['last_completed_video']['title']]) }}"
                           class="text-blue-500 font-semibold hover:underline">
                            {{ $user['last_completed_video']['title'] }}
                        </a>
                    @else
                        <span class="text-gray-500">N/A</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="px-6 py-3 text-center text-gray-500">
                    No hay usuarios registrados en este curso.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
