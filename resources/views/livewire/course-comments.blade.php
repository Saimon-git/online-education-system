<div class="my-6">
    @if($isUser)
    <h2  class="text-2xl font-semibold text-gray-700 mb-4">Comentarios</h2>
    @endif
    <x-flash-message on="flashMessageUpdated" type="success" :message="session('success')" />
    <x-flash-message on="flashMessageUpdatedError" type="error" :message="session('error')" />


    <!-- Formulario para Crear Comentario -->
    @if($isUser)
        <form wire:submit.prevent="createComment" class="mb-16">
            <div class="mb-4">
                <textarea
                    wire:model.defer="newComment"
                    placeholder="Escribe un comentario..."
                    class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    rows="3"
                    required
                ></textarea>
            </div>
            <x-primary-button :class="'float-right'">Publicar Comentario</x-primary-button>
        </form>
    @endif

    <!-- Mostrar los comentarios -->
    <div class="space-y-4 mt-2">
        @foreach($paginatedComments as $comment)
            <div class="p-4 rounded-lg shadow-md flex items-start gap-4 my-2">
                <img
                    src="{{ $comment['user']['avatar'] ?? 'https://via.placeholder.com/50' }}"
                    alt="Avatar"
                    class="w-12 h-12 rounded-full"
                />
                <div class="flex-1">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold">{{ $comment['user']['name'] }}</h3>
                            {{--<p class="text-sm text-gray-400">
                                {{ $comment['user']['level'] ? "Level {$comment['user']['level']}" : '' }}
                                @if($comment['user']['is_subscriber'])
                                    <span class="text-blue-400">Subscriber</span>
                                @endif
                            </p>--}}
                        </div>
                        <p class="text-xs">{{ \Carbon\Carbon::parse($comment['created_at'])->format('d/m/Y') }}</p>
                    </div>
                    <p class="mt-2">{{ $comment['content'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
    @if (sizeof($paginatedComments) > 10)
    <!-- Paginación -->
    <div class="flex justify-between items-center mt-4">
        <button
            wire:click="$set('currentPage', {{ $currentPage - 1 }})"
            :disabled="$currentPage === 1"
            class="px-4 py-2 bg-gray-300 text-gray-700 font-bold rounded hover:bg-gray-400 disabled:opacity-50"
        >
            Anterior
        </button>
        <span>Página {{ $currentPage }} de {{ $totalPages }}</span>
        <button
            wire:click="$set('currentPage', {{ $currentPage + 1 }})"
            :disabled="$currentPage === $totalPages"
            class="px-4 py-2 bg-gray-300 text-gray-700 font-bold rounded hover:bg-gray-400 disabled:opacity-50"
        >
            Siguiente
        </button>
    </div>
    @endif

    <!-- Tabla de Comentarios sin Aprobar -->
    @if($isAdmin)
        <h2 class="text-2xl font-semibold text-gray-700 my-4">Comentarios sin aprobar</h2>
        <table class="min-w-full bg-white border border-gray-200 rounded-lg my-4">
            <thead class="bg-gray-100 border-b border-gray-300">
            <tr>
                <th class="px-4 py-2 text-left text-gray-600 font-medium">Usuario</th>
                <th class="px-4 py-2 text-left text-gray-600 font-medium">Comentario</th>
                <th class="px-4 py-2 text-left text-gray-600 font-medium">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($comments as $comment)
                @if(!$comment['is_approved'])
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $comment['user']['name'] }}</td>
                        <td class="px-4 py-2">{{ $comment['content'] }}</td>
                        <td class="px-4 py-2 flex gap-2">
                            <button
                                wire:click="approveComment({{ $comment['id'] }})"
                                wire:confirm="Estas seguro de Aprobar este comentario?"
                                class="bg-green-500 text-white px-4 py-2 rounded"
                            >
                                Aprobar
                            </button>
                            <button
                                wire:click="declineComment({{ $comment['id'] }})"
                                wire:confirm="Estas seguro de Declinar este comentario?"
                                class="bg-red-500 text-white px-4 py-2 rounded"
                            >
                                Rechazar
                            </button>
                        </td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>

        @if(sizeof($comments) > 10)

        <!-- Paginación -->
        <div class="flex justify-between items-center mt-4">
            <button
                wire:click="$set('currentPage', {{ $currentPage - 1 }})"
                :disabled="$currentPage === 1"
                class="px-4 py-2 bg-gray-300 text-gray-700 font-bold rounded hover:bg-gray-400 disabled:opacity-50"
            >
                Anterior
            </button>
            <span>Página {{ $currentPage }} de {{ $totalPages }}</span>
            <button
                wire:click="$set('currentPage', {{ $currentPage + 1 }})"
                :disabled="$currentPage === $totalPagesUnapproved"
                class="px-4 py-2 bg-gray-300 text-gray-700 font-bold rounded hover:bg-gray-400 disabled:opacity-50"
            >
                Siguiente
            </button>
        </div>
        @endif
    @endif
</div>

