<script setup>
import { Head, Link } from '@inertiajs/vue3';
import  FilmPreview  from '../Components/FilmPreview.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
    films: Array,
});
</script>

    <template>
       
            <Head title="Welcome" />

            <div class="sm:top-0 p-6 text-right bg-zinc-300 min-w-screen sm:min-w-screen md:min-w-screen lg:min-w-screen xl:min-w-screen mb-10">
                <template v-if="! $page.props.auth.user">
                    <Link 
                        :href="route('login')" 
                        class="ml-4 font-semibold text-black focus:outline focus:outline-2 focus:rounded-sm hover:text-gray-500"
                    >
                        Log in
                    </Link>

                    <Link 
                        v-if="canRegister" 
                        :href="route('register')" 
                        class="ml-4 font-semibold text-black focus:outline focus:outline-2 focus:rounded-sm hover:text-gray-500"
                    >
                        Register
                    </Link>
                </template>
            </div>

        <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-10 mt-5 mb-5 md:mt-10 md:mb-10">
            <div v-if="films.length < 1">
                <h2 class="font-semibold text-xl text-red-500 text-center mt-10">
                    There are no films with upcoming schedules
                </h2>
            </div>
            <div v-for="film in films" :key="film.id">
                <FilmPreview 
                    :id="film.id" 
                    :cover_photo="film.cover_photo" 
                    :title="film.title" 
                    :genre="film.genre" 
                    :duration="film.duration" 
                    :rating="film.rating" 
                    :summary="film.summary"
                    :schedules="film.schedules"
                    :is_bookable="film.is_bookable"
                />
            </div>
        </div>

</template>

<style>
body {
    --tw-bg-opacity: 1;
    background-color: rgb(243 244 246 / var(--tw-bg-opacity));
}
</style>
