<template>
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Comentarios</h2>

        <!-- Formulario para Crear Comentario -->
        <form @submit.prevent="createComment" class="mb-6">
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
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded"
            >
                Publicar Comentario
            </button>
        </form>

        <!-- Lista de Comentarios -->
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead class="bg-gray-100 border-b border-gray-300">
            <tr>
                <th class="px-4 py-2 text-left text-gray-600 font-medium">Usuario</th>
                <th class="px-4 py-2 text-left text-gray-600 font-medium">Comentario</th>
                <th v-show="!isUser" class="px-4 py-2 text-left text-gray-600 font-medium">Acciones</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="comment in getComments" :key="comment.id" class="border-b hover:bg-gray-50">
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
            required: true,
        },
        isUser: {
            type: Boolean,
            required: true,
            default: false,
        }
    },
    data() {
        return {
            newComment: '', // Modelo para el nuevo comentario
        };
    },
    computed: {
        getComments() {
            return this.comments.filter(comment => comment.is_approved);
        }
    },
    methods: {
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
                this.$emit('refresh'); // Emitir evento para refrescar datos
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
                this.$emit('refresh');
            } catch (error) {
                console.error('Error al rechazar el comentario:', error);
                alert('Error al rechazar el comentario');
            }
        },
    },
};
</script>
