<template>
    <div class="my-6">
        <h2 v-if="!isAdmin" class="text-2xl font-semibold text-gray-700 mb-4">Comentarios</h2>

        <!-- Formulario para Crear Comentario -->
        <form v-if="isUser" @submit.prevent="createComment" class="mb-16">
            <div class="mb-4">
                <textarea
                    v-model="newComment"
                    placeholder="Escribe un comentario..."
                    class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    rows="3"
                    required
                ></textarea>
            </div>
            <button
                type="submit"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded float-right"
            >
                Publicar Comentario
            </button>
        </form>

        <!-- Mostrar los comentarios -->
        <div class="space-y-4 mt-2">
            <div
                v-for="comment in getCommentsApproved"
                :key="comment.id"
                class="p-4 rounded-lg shadow-md flex items-start gap-4 my-2"
            >
                <!-- Foto del usuario -->
                <img
                    :src="comment.user.avatar || 'https://via.placeholder.com/50'"
                    alt="Avatar"
                    class="w-12 h-12 rounded-full"
                />

                <!-- Contenido del comentario -->
                <div class="flex-1">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold">{{ comment.user.name }}</h3>
                            <p class="text-sm text-gray-400">
                                {{ comment.user.level ? `Level ${comment.user.level}` : '' }}
                                <span v-if="comment.user.is_subscriber" class="text-blue-400">Subscriber</span>
                            </p>
                        </div>
                        <p class="text-xs ">{{ formatDate(comment.created_at) }}</p>
                    </div>
                    <p class="mt-2 ">{{ comment.content }}</p>
                </div>
            </div>
        </div>

        <!-- Lista de Comentarios -->
        <h2 class="text-2xl font-semibold text-gray-700 my-4">Comentarios sin aprobar</h2>
        <table v-if="!isUser"  class="min-w-full bg-white border border-gray-200 rounded-lg my-4">
            <thead class="bg-gray-100 border-b border-gray-300">
            <tr>
                <th class="px-4 py-2 text-left text-gray-600 font-medium">Usuario</th>
                <th class="px-4 py-2 text-left text-gray-600 font-medium">Comentario</th>
                <th v-show="!isUser" class="px-4 py-2 text-left text-gray-600 font-medium">Acciones</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="comment in getCommentsNotApproved" :key="comment.id" class="border-b hover:bg-gray-50">
                <td class="px-4 py-2">{{ comment.user.name }}</td>
                <td class="px-4 py-2">{{ comment.content }}</td>
                <td v-show="!isUser" class="px-4 py-2 flex gap-2">
                    <button
                        @click="approveComment(comment.id)"
                        class="bg-green-500 text-white px-4 py-2 rounded"
                    >
                        Aprobar
                    </button>
                    <button
                        @click="declineComment(comment.id)"
                        class="bg-red-500 text-white px-4 py-2 rounded"
                    >
                        Rechazar
                    </button>
                </td>
            </tr>
            <tr v-if="comments.length === 0">
                <td colspan="3" class="px-4 py-2 text-center text-gray-500">No hay comentarios.</td>
            </tr>
            </tbody>
        </table>
        <!-- Paginación -->
        <div class="flex justify-between items-center mt-4">
            <button
                @click="currentPage--"
                :disabled="currentPage === 1"
                class="px-4 py-2 bg-gray-300 text-gray-700 font-bold rounded hover:bg-gray-400 disabled:opacity-50"
            >
                Anterior
            </button>
            <span>Página {{ currentPage }} de {{ totalPages }}</span>
            <button
                @click="currentPage++"
                :disabled="currentPage === totalPages"
                class="px-4 py-2 bg-gray-300 text-gray-700 font-bold rounded hover:bg-gray-400 disabled:opacity-50"
            >
                Siguiente
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        comments: {
            type: Array,
            required: true,
        },
        videoId: {
            type: String,
        },
        isUser: {
            type: Boolean,
            required: true,
            default: false,
        },
        isAdmin:{
            type: Boolean,
            default: false,
        },
        itemsPerPage: {
            type: Number,
            default: 10, // Número de comentarios por página
        },
    },
    data() {
        return {
            newComment: '', // Modelo para el nuevo comentario
            currentPage: 1, // Página actual
        };
    },
    computed: {
        getCommentsApproved() {
            return this.comments.filter(comment => comment.is_approved);
        },
        /*getCommentsNotApproved() {
            return this.comments.filter(comment => !comment.is_approved);
        },*/
        totalPages() {
            return Math.ceil(this.comments.length / this.itemsPerPage); // Total de páginas
        },
        getCommentsNotApproved() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return this.comments.slice(start, end); // Comentarios de la página actual
        },
    },
    methods: {
        formatDate(date) {
            return new Date(date).toLocaleDateString();
        },
        async createComment() {
            if (!this.newComment.trim()) return; // No permitir comentarios vacíos

            try {
                const response = await fetchWithCsrf(`/api/video/${this.videoId}/comments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        text: this.newComment,
                    }),
                });

                if (!response.ok) {
                    const error = await response.json();
                    alert(error.message || 'Error al publicar el comentario.');
                    return;
                }

                const data = await response.json();

                // Agregar el nuevo comentario a la lista
                this.comments.unshift(data.comment);

                // Limpiar el campo de comentario
                this.newComment = '';
            } catch (error) {
                console.error('Error al crear el comentario:', error);
                alert('Ocurrió un error al publicar el comentario.');
            }
        },
        async approveComment(commentId) {
            try {
                const response = await fetchWithCsrf(`/api/comments/${commentId}/approve`, { method: 'POST' });
                if (!response.ok) throw new Error('Error al aprobar el comentario');
                alert('Comentario aprobado correctamente');
                 // actualizar el comentario
                const xhr = await response.json();
                const index = this.comments.findIndex(comment => comment.id === commentId);
                this.comments[index].is_approved = true;

            } catch (error) {
                console.error('Error al aprobar el comentario:', error);
                alert('Error al aprobar el comentario');
            }
        },
        async declineComment(commentId) {
            try {
                const response = await fetchWithCsrf(`/api/comments/${commentId}/decline`, { method: 'POST' });
                if (!response.ok) throw new Error('Error al rechazar el comentario');
                alert('Comentario rechazado correctamente');
                //eliminar comentario
                const xhr = await response.json();
                const index = this.comments.findIndex(comment => comment.id === commentId);
                this.comments.splice(index, 1);

            } catch (error) {
                console.error('Error al rechazar el comentario:', error);
                alert('Error al rechazar el comentario');
            }
        },
    },
};
</script>
