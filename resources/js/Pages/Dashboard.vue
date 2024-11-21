<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

// Props proporcionadas desde el backend
defineProps({
    isAdmin: {
        type: Boolean,
        required: true,
    },
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        <!-- Mostrar información relevante para admins -->
                        <template v-if="isAdmin">
                            <h3 class="text-lg font-bold mb-4">Bienvenido, Administrador</h3>
                            <ul class="list-disc pl-5">
                                <li>
                                    <a href="/courses" class="text-blue-500 hover:underline">
                                        Gestionar Cursos
                                    </a>
                                </li>
                                <li>
                                    <a href="/comments" class="text-blue-500 hover:underline">
                                        Revisar Comentarios Pendientes
                                    </a>
                                </li>
                            </ul>
                        </template>

                        <!-- Mostrar información relevante para usuarios regulares -->
                        <template v-else>
                            <h3 class="text-lg font-bold mb-4">Bienvenido al Dashboard</h3>
                            <ul class="list-disc pl-5">
                                <li>
                                    <a href="/my-courses" class="text-blue-500 hover:underline">
                                        Ver Mis Cursos
                                    </a>
                                </li>
                                <li>
                                    <a href="/profile" class="text-blue-500 hover:underline">
                                        Editar Perfil
                                    </a>
                                </li>
                            </ul>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
