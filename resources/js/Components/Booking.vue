<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed, onMounted } from 'vue';

const props = defineProps({
    booking: Object,
    status: String
})

let isCancelable = computed(() => {
    return props.booking.is_cancelable & props.status === 'upcoming'
})

</script>

<template>
    <Head title="Upcoming Bookings" />

    <div class="mx-auto md:ml-5 bg-white rounded-xl shadow-md md:max-w-screen">
        <div class="md:flex">
            <div class="md:shrink-0">
            <img class="h-48 w-full object-cover md:h-full md:w-80" :src="booking.schedule.film.cover_photo" alt="cover photo">
            </div>
            <div class="p-8">
                <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold">{{ booking.schedule.film.title }}</div>
                <p class="mt-2 text-slate-500">{{ booking.schedule.film.summary }}</p>
                <div class="flex items-center mt-2.5 mb-5">
                    <svg v-for="i in booking.schedule.film.rating" 
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
                        {{ booking.schedule.film.rating }}.0
                    </span>
                </div>
                <div class="flex items-center mt-2.5 mb-5">
                    <p class="text-sm font-bold text-gray-900">{{ booking.schedule.film.genre }}</p>
                </div>
                <div class="flex items-center mt-2.5 mb-5">
                    <p>{{ booking.schedule.film.duration }}</p>
                </div>
                <div class="flex items-center mt-2.5 mb-5">
                    <p>{{ booking.schedule.starts_at }}</p>
                </div>
                <div class="flex items-center mt-2.5 mb-5">
                    <p>Ref: {{ booking.reference_number }}</p>
                </div>
                <a  v-if="isCancelable" :href="route('bookings.cancel', {bookingId: booking.id})" class="text-white bg-red-400 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Cancel</a>
       
            </div>
        </div>
    </div>

</template>

