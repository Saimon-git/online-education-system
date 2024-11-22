<div class="space-y-4">
    @foreach ($videos as $index => $video)
        <div class="flex items-start p-4 border border-gray-200 rounded-lg shadow hover:shadow-lg transition-shadow bg-white">
            <div class="flex items-center justify-center w-12 h-12 bg-blue-100 text-blue-600 font-bold rounded-full text-xl mr-4">
                {{ $index + 1 }}
            </div>

            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-800">{{ $video['title'] }}</h3>
                <p class="text-gray-600 text-sm mb-2">{{ $video['description'] }}</p>

                <div class="flex items-center text-sm text-gray-500 space-x-4">
                    <div class="flex items-center rounded bg-black">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            class="w-4 h-4 mr-1"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 6v6l4 2"
                            />
                        </svg>
                    </div>
                    <div class="flex items-center">
                        {{ $video['duration'] }}
                    </div>
                    <div class="flex items-center">
                        <span>{{ $video['likes'] }} Likes</span>
                        <button
                            wire:click="toggleLike({{ $video['id'] }})"
                            class="ml-2 text-blue-500 hover:underline"
                        >
                            {{ $video['liked'] ? 'Unlike' : 'Like' }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Estado de Finalización -->
            <div class="flex flex-col items-center">
                <a href="{{ route('show.video', [$video['course'], $video['title']]) }}" class="text-blue-500 font-semibold hover:underline">
                    Ver
                </a>
                @if ($video['is_completed'])
                    <span class="mt-2 bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded-full">
                        Completado
                    </span>
                @endif
            </div>
        </div>
    @endforeach
</div>
