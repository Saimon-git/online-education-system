<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { provide } from 'vue';
import VideoDetail from "@/Components/VideoDetail.vue";
const props = defineProps({
    video: {
        type: Object,
        required: true,
    },
    is_user: {
        type: Boolean,
        default: false,
    },
    auth: {
        type: Object,
        required: true,
    },
});
// Proveer `auth` para los hijos
provide('auth', props.auth);
</script>
<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >

            </h2>
        </template>

        <div class="py-12">
            <div
                class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
            >
                <VideoDetail
                    :video="video"
                    :courseId="video.course.id"
                    :isUser="is_user"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

