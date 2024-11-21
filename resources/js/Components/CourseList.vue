<template>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Listado de Cursos</h1>

        <Link v-if="!isUser" :href="route('courses.create')" class="underline float-right -mt-10">
            <PrimaryButton>Crear curso</PrimaryButton>
        </Link>

        <!-- Filtros -->
        <div class="flex flex-col md:flex-row items-center gap-4 mb-6 mt-16">
            <!-- Buscar por Nombre -->
            <input
                type="text"
                v-model="filters.name"
                placeholder="Buscar por nombre"
                class="border border-gray-300 rounded-lg p-2 w-full md:w-1/3"
            />

            <!-- Filtrar por Categoría -->
            <select
                v-model="filters.category_id"
                class="border border-gray-300 rounded-lg p-2 w-full md:w-1/3"
            >
                <option value="">Todas las Categorías</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                    {{ category.name }}
                </option>
            </select>

            <!-- Filtrar por Rango de Edades -->
            <select
                v-model="filters.age_group"
                class="border border-gray-300 rounded-lg p-2 w-full md:w-1/3"
            >
                <option value="">Todos los Rangos de Edades</option>
                <option v-for="range in ageRanges" :key="range" :value="range">
                    {{ range }}
                </option>
            </select>
        </div>

        <!-- Tabla de Cursos -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead>
                <tr class="bg-gray-100 border-b border-gray-300">
                    <th class="px-6 py-3 text-left text-gray-600 font-medium">Nombre</th>
                    <th class="px-6 py-3 text-left text-gray-600 font-medium">Descripción</th>
                    <th class="px-6 py-3 text-left text-gray-600 font-medium">Categoría</th>
                    <th class="px-6 py-3 text-left text-gray-600 font-medium">Rango de Edades</th>
                    <th class="px-6 py-3 text-left text-gray-600 font-medium">Acciones</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="course in filteredCourses"
                    :key="course.id"
                    class="border-b border-gray-200 hover:bg-gray-50"
                >
                    <td class="px-6 py-3">{{ course.name }}</td>
                    <td class="px-6 py-3">{{ course.description }}</td>
                    <td class="px-6 py-3">{{ course.category.name }}</td>
                    <td class="px-6 py-3">{{ course.age_group }}</td>
                    <td class="px-6 py-3" v-if="isUser">

                        <CourseRegister
                            :courseId="course.id"
                            :isInitiallyRegistered="course.is_registered"
                            @registered="handleRegistered"
                        />
                    </td>
                    <td class="px-6 py-3" v-else>
                        <Link :href="route('courses.show',course)" class="underline">
                            <PrimaryButton>Detalle del curso</PrimaryButton>
                        </Link>
                    </td>
                </tr>
                <tr v-if="filteredCourses.length === 0">
                    <td colspan="4" class="px-6 py-3 text-center text-gray-500">
                        No se encontraron cursos.
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <!-- Botones de Paginación -->
        <div class="flex justify-between items-center mt-4">
            <button
                @click="fetchCourses(pagination.prev_page_url)"
                :disabled="!pagination.prev_page_url"
                class="px-4 py-2 bg-gray-300 text-gray-700 font-bold rounded hover:bg-gray-400"
            >
                Anterior
            </button>
            <span>Página {{ pagination.current_page }} de {{ pagination.last_page }}</span>
            <button
                @click="fetchCourses(pagination.next_page_url)"
                :disabled="!pagination.next_page_url"
                class="px-4 py-2 bg-gray-300 text-gray-700 font-bold rounded hover:bg-gray-400"
            >
                Siguiente
            </button>
        </div>
    </div>
</template>

<script>
import {Link} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import CourseRegister from "@/Components/CourseRegister.vue";

export default {
    components: {PrimaryButton, CourseRegister, Link},
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
            courses: [], // Lista de cursos
            categories: [], // Lista de categorías
            filters: {
                name: '',
                category_id: '',
                age_group: '',
            },
            ageRanges: ['5-8', '9-13', '14-16', '16+'], // Rangos de edades
            user_courses: [], // Lista de cursos del usuario
            pagination: {},
        };
    },
    computed: {
        filteredCourses() {

            let courses = []
            if (this.user_courses.length > 0) {
                this.courses.forEach(c => {
                    if (this.user_courses.find(uc => uc.id === c.id)) {
                        c.is_registered = true
                        courses.push(c)
                    } else {
                        c.is_registered = false
                        courses.push(c)
                    }
                })
            }else{
                this.courses.forEach(c => {
                    c.is_registered = false
                    courses.push(c)
                })
            }
            return courses.filter((course) => {
                const matchesName =
                    !this.filters.name || course.name.toLowerCase().includes(this.filters.name.toLowerCase());
                const matchesCategory =
                    !this.filters.category_id || course.category_id === parseInt(this.filters.category_id);
                const matchesAgeGroup =
                    !this.filters.age_group || course.age_group === this.filters.age_group;
                return matchesName && matchesCategory && matchesAgeGroup;
            });

        },
    },
    mounted() {
        // Cargar los cursos y categorías al montar el componente
        Promise.all([this.fetchCourses(), this.fetchCategories()]);
    },
    methods: {
        async fetchCourses(url = "/api/courses") {
            const response = await fetch(url);
            const data = await response.json();

            // Actualizar cursos y datos de paginación
            this.courses = data.data; // Los cursos están en "data" debido a paginate()
            this.pagination = {
                current_page: data.current_page,
                last_page: data.last_page,
                next_page_url: data.next_page_url,
                prev_page_url: data.prev_page_url,
            };

            // Si es un usuario autenticado, cargar sus cursos registrados
            if (this.isUser) {
                const userResponse = await fetch(`/api/user/${this.userId}/courses`);
                const userData = await userResponse.json();
                this.user_courses = userData.courses;
            }
        },

        async fetchCategories() {
            const response = await fetch('/api/categories');
            this.categories = await response.json();
        },

        handleRegistered(courseId) {
            // Actualizar el estado de los cursos cuando un usuario se registre
            const courseIndex = this.courses.findIndex((course) => course.id === courseId);
            if (courseIndex !== -1) {
                this.courses[courseIndex].is_registered = true;
            }
        },
    },
};
</script>

<style scoped>
/* Agrega cualquier estilo adicional aquí si es necesario */
button:disabled {
    background-color: #e2e8f0;
    cursor: not-allowed;
}
</style>
