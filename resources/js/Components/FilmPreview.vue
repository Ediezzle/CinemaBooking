<script setup>
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    id: Number,
    cover_photo: String,
    title: String,
    genre: String,
    summary: String,
    duration: String,
    rating: Number,
    schedules: Object
})

</script>

<template>
    <Head title="Film Previews" />

    <div class="mx-auto md:ml-5 bg-white rounded-xl shadow-md md:max-w-screen">
        <div class="md:flex">
            <div class="md:shrink-0">
            <img class="h-48 w-full object-cover md:h-full md:w-80" :src="cover_photo" alt="cover photo">
            </div>
            <div class="p-8">
                <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold">{{ title }}</div>
                <p class="mt-2 text-slate-500">{{ summary }}</p>
                <div class="flex items-center mt-2.5 mb-5">
                    <svg v-for="i in rating" 
                        :key="i" 
                        aria-hidden="true" 
                        class="w-5 h-5 text-yellow-300" 
                        fill="currentColor" 
                        viewBox="0 0 20 20" 
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <title>star</title>
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                        </path>
                    </svg>
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800 ml-3">
                        {{ rating }}.0
                    </span>
                </div>
                <div class="flex items-center mt-2.5 mb-5">
                    <p class="text-sm font-bold text-gray-900">{{ genre }}</p>
                </div>
                <div class="flex items-center mt-2.5 mb-5">
                    <p>{{ duration }}</p>
                </div>
                <a :href="route('bookings.create', {filmId: id})" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Book</a>
       
            </div>
        </div>
    </div>

    
<!-- <div class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <a href="#">
        <img class="p-8 rounded-t-lg w-40 h-40" :src='cover_photo' alt="cover photo" />
    </a>
    <div class="px-5 pb-5">
        <a href="#">
            <h5 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ title }}</h5>
        </a>
        <div class="flex items-center mt-2.5 mb-5">
            <svg v-for="i in rating" :key="i" aria-hidden="true" class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>First star</title><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            
            <span class="bg-blue-100 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800 ml-3">{{ rating }}.0</span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ genre }} | {{ duration }}</span>
            <a href="#" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">View</a>
        </div>
    </div>
</div> -->

</template>

