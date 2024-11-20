<template>
    <div>
        <button
            v-if="!isRegistered"
            @click="registerToCourse"
            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded"
        >
            Registrarse
        </button>
        <Link v-else :href="route('courses.show',courseId)" class="underline">
            <PrimaryButton >Detalle del curso</PrimaryButton>
        </Link>
    </div>
</template>

<script>
import {Link} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";

export default {
    components: {PrimaryButton, Link},
    props: {
        courseId: {
            type: String,
            required: true,
        },
        isInitiallyRegistered: {
            type: Boolean,
            required: false,
            default: false,
        },
    },
    data() {
        return {
            isRegistered: this.isInitiallyRegistered,
        };
    },
    methods: {
        async registerToCourse() {
            try {
                // Llama al endpoint de registro
                const response = await fetchWithCsrf(`/api/courses/${this.courseId}/register`, {
                    method: 'POST',
                    credentials: 'include', // Enviar cookies
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });

                if (!response.ok) {
                    const error = await response.json();
                    alert(error.message || 'No se pudo registrar en el curso.');
                    return;
                }

                const data = await response.json();
                this.isRegistered = true; // Actualiza el estado del componente
                alert(data.message || 'Te has registrado correctamente.');

                // Emitir un evento para informar al padre que el usuario se ha registrado
                this.$emit('registered', this.courseId);
            } catch (error) {
                console.error('Error al registrarse:', error);
                alert('Ocurrió un error al intentar registrarse en el curso.');
            }
        },
    },
};
</script>
