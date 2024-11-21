<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import CourseComments from "@/Components/CourseComments.vue";
import {comment} from "postcss";
defineProps({
    is_user: {
        type: Boolean,
    },
    all_comments: {
        type: Array,
        required: true,
    },
    isAdmin:{
        type: Boolean,
        required: true,
    }
});
</script>
<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Lista de Comentarios pendientes
            </h2>
        </template>

        <div class="py-12">
            <div
                class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
            >
                <CourseComments
                    :comments="all_comments"
                    :videoId="1"
                    :isUser="is_user"
                    :isAdmin="isAdmin"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

