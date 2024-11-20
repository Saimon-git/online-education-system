<template>
    <div class="container mx-auto p-6 bg-white border border-gray-200 shadow-md rounded-lg max-w-4xl">
        <h2 class="text-2xl font-bold mb-4">Agregar Videos al Curso</h2>

        <!-- Lista de Videos -->
        <div v-for="(video, index) in videos" :key="index" class="mb-6 border-b pb-4">
            <h3 class="text-lg font-semibold mb-2">Video {{ index + 1 }}</h3>

            <!-- Título del Video -->
            <div class="mb-4">
                <label for="title" class="block text-gray-700 font-medium mb-2">Título</label>
                <input
                    type="text"
                    v-model="video.title"
                    class="w-full border border-gray-300 rounded-lg p-2"
                    placeholder="Ingrese el título del video"
                    required
                />
            </div>

            <!-- URL del Video -->
            <div class="mb-4">
                <label for="url" class="block text-gray-700 font-medium mb-2">URL</label>
                <input
                    type="text"
                    v-model="video.url"
                    class="w-full border border-gray-300 rounded-lg p-2"
                    placeholder="Ingrese la URL del video"
                    required
                />
            </div>

            <!-- Duración -->
            <div class="mb-4">
                <label for="duration" class="block text-gray-700 font-medium mb-2">Duración</label>
                <input
                    type="text"
                    v-model="video.duration"
                    class="w-full border border-gray-300 rounded-lg p-2"
                    placeholder="Duración del video (e.g., 5:32)"
                    required
                />
            </div>

            <!-- Botón para Eliminar Video -->
            <button
                type="button"
                @click="removeVideo(index)"
                class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded"
            >
                Eliminar Video
            </button>
        </div>

        <!-- Botón para Agregar Nuevo Video -->
        <button
            type="button"
            @click="addVideo"
            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mb-6"
        >
            Agregar Nuevo Video
        </button>

        <!-- Botón para Guardar Videos -->
        <div class="flex justify-end">
            <button
                type="button"
                @click="saveVideos"
                class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded"
            >
                Guardar Videos
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        courseId: {
            type: Number,
            //required: true,
        },
    },
    data() {
        return {
            videos: [
                {
                    title: "",
                    url: "",
                    duration: "",
                },
            ],
        };
    },
    methods: {
        addVideo() {
            this.videos.push({
                title: "",
                url: "",
                duration: "",
            });
        },
        removeVideo(index) {
            this.videos.splice(index, 1);
        },
        async saveVideos() {
            try {
                const response = await fetchWithCsrf(`/api/courses/${this.courseId}/videos`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: `Bearer ${localStorage.getItem("token")}`,
                    },
                    body: JSON.stringify({ videos: this.videos }),
                });

                if (!response.ok) {
                    const error = await response.json();
                    alert(error.message || "Error al guardar los videos.");
                    return;
                }

                alert("Videos asociados al curso exitosamente!");
                this.videos = [
                    {
                        title: "",
                        url: "",
                        duration: "",
                    },
                ];
                window.location.href = '/courses/' + this.courseId
            } catch (error) {
                console.error("Error al guardar los videos:", error);
                alert("Ocurrió un error al guardar los videos.");
            }
        },
    },
};
</script>

<style scoped>
/* Estilos adicionales, si es necesario */
</style>
