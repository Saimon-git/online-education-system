<template>
    <div class="container mx-auto p-6 max-w-4xl border border-gray-200 rounded-lg bg-white shadow-md">
        <!-- Header del curso -->
        <div class="flex items-center justify-between mb-4">
            <span class="bg-blue-500 text-white px-4 py-auto rounded text-sm">
                {{ userProgress }} %
            </span>
            <h1 class="text-2xl font-bold text-gray-800">{{ course.name }}</h1>
            <span class="bg-blue-500 text-white px-4 py-1 rounded text-sm">{{ course.category.name }}</span>
        </div>
        <!-- Descripción -->
        <p class="text-gray-600 mb-6">{{ course.description }}</p>

        <!-- Botón de acción -->
        <div class="flex items-center space-x-4">
            <button
                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded"
                @click="continueCourse"
            >
                Continuar curso
            </button>

        </div>

    </div>
    <div class="container mx-auto my-3 p-6 max-w-4xl border border-gray-200 rounded-lg bg-white shadow-md">
        <!-- Componentes Independientes -->
        <CourseVideos :videos="course.videos" />
    </div>
    <div v-show="!isUser" class="container mx-auto my-3 p-6 max-w-4xl border border-gray-200 rounded-lg bg-white shadow-md">
        <CourseProgress :users="course.users" />
    </div>
    <div v-show="!isUser" class="container mx-auto my-3 p-6 max-w-4xl border border-gray-200 rounded-lg bg-white shadow-md">
        <CourseStatistics :videos="course.videos" />
    </div>
</template>

<script>
import CourseProgress from '@/Components/CourseProgress.vue';
import CourseVideos from '@/Components/CourseVideos.vue';
import CourseComments from '@/Components/CourseComments.vue';
import CourseStatistics from '@/Components/CourseStatistics.vue';

export default {
    components: {
        CourseProgress,
        CourseComments,
        CourseStatistics,
        CourseVideos,
    },
    inject: ['auth'], // Inyectar el objeto `auth` desde el padre
    props: {
        courseId: {
            type: String,
            required: true,
        },
        isUser: {
            type: Boolean,
            required: true,
            default: false,
        },
    },
    data() {
        return {
            course: {
                name: '',
                description: '',
                category: {},
                videos: [],
                users: [],
                comments: [],
            },
            user_progress: 0
        };
    },
    async mounted() {
        await this.fetchCourseDetails(this.courseId);
    },
    computed: {
        userProgress() {
            // Aquí podrías calcular el progreso del usuario en el curso
            // Puede usar la información de `this.course.users` y `this.course.videos`
            return this.calculateUserProgress();
        },
    },
    methods: {
        async fetchCourseDetails(courseId) {
            try {
                const response = await fetch(`/api/courses/${courseId}`);
                if (!response.ok) throw new Error('Error al obtener los detalles del curso');
                this.course = await response.json();
               //this.calculateUserProgress();
            } catch (error) {
                console.error('Error al cargar el curso:', error);
            }
        },
        calculateUserProgress() {
            let current_user = this.auth.user;
            console.log('Current user', current_user.id);
            let current_progress = 0
           this.course.users.forEach(user => {
               if(user.id === current_user.id){
                   this.user_progress = user.pivot.progress;
                   current_progress = this.user_progress
               }
           })
            return current_progress;
        }

    },
};
</script>
