<template>
    <div class="space-y-4">
        <div
            v-for="(video, index) in getVideos"
            :key="video.id"
            class="flex items-start p-4 border border-gray-200 rounded-lg shadow hover:shadow-lg transition-shadow bg-white"
        >
            <div class="flex items-center justify-center w-12 h-12 bg-blue-100 text-blue-600 font-bold rounded-full text-xl mr-4">
                {{ index + 1 }}
            </div>

            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-800">{{ video.title }}</h3>
                <p class="text-gray-600 text-sm mb-2">{{ video.description }}</p>

                <div class="flex items-center text-sm text-gray-500 space-x-4">
                    <div class="flex items-center rounded bg-black">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            class="w-4 h-4 mr-1"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 6v6l4 2"
                            />
                        </svg>
                    </div>
                    <div class="flex items-center">
                    {{ video.duration }}
                    </div>
                    <div class="flex items-center">
                        <span>{{ video.likes }} Likes</span>
                        <button
                            @click="toggleLike(video)"
                            class="ml-2 text-blue-500 hover:underline"
                        >
                            {{ video.liked ? 'Unlike' : 'Like' }}
                        </button>
                    </div>
                </div>
            </div>

            <div>
                <Link :href="route('show.video',[video.course.id,video.title])" class="text-blue-500 font-semibold hover:underline">
                    Ver
                </Link>
            </div>
        </div>
    </div>
</template>

<script>
import {Link} from "@inertiajs/vue3";

export default {
    components: {Link},
    inject: ['auth'],
    props: {
        videos: {
            type: Array,
            required: true,
        },
    },
    computed:{
        getVideos(){
            let current_user = this.auth.user;
            let all_videos = []
            this.videos.forEach(video =>{
                video.liked = false;
                if(video.liked_by_users.length > 0) {
                    video.liked_by_users.forEach(user => {
                        if(user.id === current_user.id) {
                            video.liked = true;
                        }
                    })

                }
                all_videos.push(video)
            })
            return all_videos;
        }

    },
    methods: {
        async toggleLike(video) {
            try {
                const response = await fetchWithCsrf(`/api/videos/${video.id}/like`, {
                    method: 'POST',
                    credentials: 'include',
                });
                const data = await response.json();
                video.liked = !video.liked;
                video.likes = data.likes;
            } catch (error) {
                console.error('Error al dar like/unlike:', error);
            }
        },
    },
};
</script>
