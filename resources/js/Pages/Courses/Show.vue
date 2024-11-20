<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { provide } from 'vue';
import CourseDetail from "@/Components/CourseDetail.vue";
const props = defineProps({
    course: {
        type: String,
    },
    is_user: {
        type: Boolean,
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
                Course Detail
            </h2>
        </template>

        <div class="py-12">
            <div
                class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
            >
                <h1>{{ Course }}</h1>
                <CourseDetail
                    :courseId="course"
                    :isUser="is_user"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

