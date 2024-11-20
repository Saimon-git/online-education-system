<template>
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
            <tr v-for="user in users" :key="user.id" class="border-b hover:bg-gray-50">
                <td class="px-4 py-2">{{ user.name }}</td>
                <td class="px-4 py-2 ">{{ user.pivot.progress }}%</td>
                <td class="px-4 py-2 ">
                    <Link v-if="user.completed_videos.length > 0"
                          :href="route('show.video', [user.completed_videos[user.completed_videos.length - 1].course_id, user.completed_videos[user.completed_videos.length - 1].title])"
                          class="text-blue-500 font-semibold hover:underline">
                        {{ user.completed_videos[user.completed_videos.length - 1].title }}
                    </Link>
                </td>
            </tr>
            <tr v-if="users.length === 0">
                <td colspan="4" class="px-6 py-3 text-center text-gray-500">
                    No hay usuarios registrados en este curso.
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import {Link} from "@inertiajs/vue3";

export default {
    components: {Link},
    props: {
        users: {
            type: Array,
            required: true,
        },
    },
};
</script>
