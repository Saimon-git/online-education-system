<template>
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Estadísticas del Curso</h2>
        <ul>
            <li v-for="video in videos" :key="video.id" class="mb-2">
                <span class="font-semibold">{{ video.title }}:</span> {{ video.views }} vistas, {{ video.likes }} likes.
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: {
        videos: {
            type: Array,
            required: true,
        },
    },
};
</script>
