<div class="mb-6">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Estadísticas de los Videos</h2>
    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
        <thead class="bg-gray-100 border-b border-gray-300">
        <tr>
            <th class="px-4 py-2 text-left text-gray-600 font-medium">Video</th>
            <th class="px-4 py-2 text-left text-gray-600 font-medium">Vistas</th>
            <th class="px-4 py-2 text-left text-gray-600 font-medium">Likes</th>
        </tr>
        </thead>
        <tbody>
        @forelse($videos as $video)
            <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-2">{{ $video['title'] }}</td>
                <td class="px-4 py-2">{{ $video['views'] }}</td>
                <td class="px-4 py-2">{{ $video['likes'] }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="px-6 py-3 text-center text-gray-500">
                    No hay estadísticas disponibles en el momento.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
