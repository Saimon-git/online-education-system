<template>
    <div class="container mx-auto p-6 max-w-4xl bg-white border border-gray-200 shadow-md rounded-lg">
        <!-- Título del video -->
        <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ video.title }}</h1>

        <!-- Reproductor de Video -->
        <div class="aspect-w-16 aspect-h-9 mb-6">
            <p class=" text-2xl font-bold text-gray-800">video</p>
            <iframe
                :src="video.url"
                frameborder="0"
                allow="autoplay; encrypted-media"
                allowfullscreen
                class="w-full h-full rounded-lg"
            ></iframe>
        </div>

        <!-- Descripción del Video -->
        <p class="text-gray-600 mb-6">{{ video.description }}</p>
        <button
            v-if="!video.is_completed"
            class="flex bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded float-right"
            @click="markAsCompleted"
        >
            <svg x="0px" y="0px" viewBox="0 0 191.667 191.667" xml:space="preserve" width="15" class="mr-[11px] flex-shrink-0"><path d="M95.833,0C42.991,0,0,42.99,0,95.833s42.991,95.834,95.833,95.834s95.833-42.991,95.833-95.834S148.676,0,95.833,0z   M150.862,79.646l-60.207,60.207c-2.56,2.56-5.963,3.969-9.583,3.969c-3.62,0-7.023-1.409-9.583-3.969l-30.685-30.685  c-2.56-2.56-3.97-5.963-3.97-9.583c0-3.621,1.41-7.024,3.97-9.584c2.559-2.56,5.962-3.97,9.583-3.97c3.62,0,7.024,1.41,9.583,3.971  l21.101,21.1l50.623-50.623c2.56-2.56,5.963-3.969,9.583-3.969c3.62,0,7.023,1.409,9.583,3.969  C156.146,65.765,156.146,74.362,150.862,79.646z" class="fill-current"></path></svg>
            Marcar como completado
        </button>
        <button
            v-else
            class="flex bg-green-500 hover:bg-blue-600 text-white font-bold py-2 px-4 float-right rounded-full"
        >
            <svg x="0px" y="0px" viewBox="0 0 191.667 191.667" xml:space="preserve" width="15" class="mr-[11px] flex-shrink-0"><path d="M95.833,0C42.991,0,0,42.99,0,95.833s42.991,95.834,95.833,95.834s95.833-42.991,95.833-95.834S148.676,0,95.833,0z   M150.862,79.646l-60.207,60.207c-2.56,2.56-5.963,3.969-9.583,3.969c-3.62,0-7.023-1.409-9.583-3.969l-30.685-30.685  c-2.56-2.56-3.97-5.963-3.97-9.583c0-3.621,1.41-7.024,3.97-9.584c2.559-2.56,5.962-3.97,9.583-3.97c3.62,0,7.024,1.41,9.583,3.971  l21.101,21.1l50.623-50.623c2.56-2.56,5.963-3.969,9.583-3.969c3.62,0,7.023,1.409,9.583,3.969  C156.146,65.765,156.146,74.362,150.862,79.646z" class="fill-current"></path></svg>
            completado
        </button>

        <!-- Sección de Comentarios -->
        <CourseComments :comments="video.comments" :videoId="video.id" isUser="isUser" />
    </div>
</template>

<script>
import CourseComments from '@/Components/CourseComments.vue';

export default {
    components: {
        CourseComments,
    },
    inject: ['auth'],
    props: {
        video: {
            type: Object,
            required: true,
        },
        courseId: {
            type: String,
            required: true,
        },
        isUser:{
            type: Boolean,
            default: false,
        }
    },
    data() {
        return {
        };
    },
    methods: {
        async markAsCompleted() {
            try {
                const response = await fetchWithCsrf(`/api/videos/${this.video.id}/complete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });

                if (!response.ok) {
                    const error = await response.json();
                    alert(error.message || 'Error marking video as completed');
                    return;
                }

                const data = await response.json();
                alert('Video marked as completed!');
                console.log(`Progress updated to ${data.progress}%`);
                //redirigir a la ruta de detalle del curso
                window.location.href = `/courses/${this.courseId}`;
            } catch (error) {
                console.error('Error marking video as completed:', error);
            }
        },
    },
};
</script>

<style scoped>
/* Agregar estilos adicionales si es necesario */
</style>
