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
                <td class="px-4 py-2 text-center">{{ user.pivot.progress }}%</td>
                <td class="px-4 py-2 text-center">{{ user.current_video }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: {
        users: {
            type: Array,
            required: true,
        },
    },
};
</script>
