<template>
    <div class="container mx-auto p-6 max-w-3xl bg-white border border-gray-200 shadow-md rounded-lg">
        <h1 class="text-2xl font-bold mb-6">Crear Nuevo Curso</h1>

        <form  v-if="!createdCourseId" @submit.prevent="createCourse">
            <!-- Nombre del Curso -->
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium mb-2">Nombre del Curso</label>
                <input
                    type="text"
                    id="name"
                    v-model="form.name"
                    class="w-full border border-gray-300 rounded-lg p-2"
                    placeholder="Ingrese el nombre del curso"
                    required
                />
            </div>

            <!-- Descripción del Curso -->
            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-medium mb-2">Descripción</label>
                <textarea
                    id="description"
                    v-model="form.description"
                    class="w-full border border-gray-300 rounded-lg p-2"
                    placeholder="Ingrese la descripción del curso"
                    rows="4"
                    required
                ></textarea>
            </div>

            <!-- Seleccionar Categoría -->
            <div class="mb-4">
                <label for="category" class="block text-gray-700 font-medium mb-2">Categoría</label>
                <select
                    id="category"
                    v-model="form.category_id"
                    class="w-full border border-gray-300 rounded-lg p-2"
                    required
                >
                    <option value="" disabled>Seleccione una categoría</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">
                        {{ category.name }}
                    </option>
                </select>
            </div>

            <!-- Rango de Edades -->
            <div class="mb-4">
                <label for="age_group" class="block text-gray-700 font-medium mb-2">Rango de Edades</label>
                <select
                    id="age_group"
                    v-model="form.age_group"
                    class="w-full border border-gray-300 rounded-lg p-2"
                    required
                >
                    <option value="" disabled>Seleccione un rango de edades</option>
                    <option v-for="range in ageRanges" :key="range" :value="range">
                        {{ range }}
                    </option>
                </select>
            </div>


            <!-- Botón Crear -->
            <div class="flex justify-end">
                <button

                    type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg"
                >
                    Crear Curso
                </button>
            </div>
        </form>
        <div class="mb-4">
            <AddVideos v-if="createdCourseId" :courseId="createdCourseId" />
        </div>
    </div>
</template>

<script>
import AddVideos from "@/Components/AddVideos.vue";

export default {
    components: {AddVideos},
    props: {
        isUser: {
            type: Boolean,
            required: true,
            default: false,
        },
        userId: {
            type: Number,
            required: true,
        }
    },
    data() {
        return {
            form: {
                name: "",
                description: "",
                category_id: "",
                age_group: "",
            },
            categories: [], // Lista de categorías
            ageRanges: ["5-8", "9-13", "14-16", "16+"], // Rangos de edades
            createdCourseId: false, // ID del curso creado
        };
    },
    mounted() {
        this.fetchCategories();
    },
    methods: {
        async fetchCategories() {
            try {
                const response = await fetch("/api/categories");
                this.categories = await response.json();
            } catch (error) {
                console.error("Error al cargar categorías:", error);
            }
        },
        async createCourse() {
            try {
                const response = await fetchWithCsrf("/api/courses", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: `Bearer ${localStorage.getItem("token")}`,
                    },
                    body: JSON.stringify(this.form),
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    alert(errorData.message || "Error al crear el curso.");
                    return;
                }

                alert("Curso creado exitosamente!");
                // Limpiar el formulario después de crear el curso
                this.form = {
                    name: "",
                    description: "",
                    category_id: "",
                    age_group: "",
                };
                const xhr = await response.json();
                console.log('response',xhr);
                this.createdCourseId = xhr.course.id;
            } catch (error) {
                console.error("Error al crear el curso:", error);
                alert("Ocurrió un error al crear el curso.");
            }
        },
    },
};
</script>

<style scoped>
/* Estilos adicionales, si es necesario */
</style>
