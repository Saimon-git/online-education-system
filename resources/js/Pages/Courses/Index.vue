<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import CourseList from "@/Components/CourseList.vue";
defineProps({
    is_user: {
        type: Boolean,
    },
    user_id: {
        type: String,
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
                List Courses
            </h2>
        </template>

        <div class="py-12">
            <div
                class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
            >
                <CourseList
                    :isUser="is_user"
                    :userId="user_id"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

