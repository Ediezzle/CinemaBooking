<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, onMounted } from 'vue';

const props = defineProps({
    booking: Object,
    status: String
})

let isCancelable = computed(() => {
    return props.booking.is_cancelable & props.status === 'upcoming'
})

const submit = () => {
    router.delete(`/bookings/${props.booking.id}/cancel`)
}

</script>

<template>
    <Head title="Upcoming Bookings" />
    <a href="#">
        <img class="rounded-t-lg w-full" :src="booking.schedule.film.cover_photo" alt="here" />
    </a>
    <div class="p-5">
        <a href="#">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ booking.schedule.film.title }}</h5>
        </a>
        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Date & Time: {{ booking.schedule.starts_at }}</p>
        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Cinema: {{ booking.schedule.theatre.cinema.name }}</p>
        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Theatre: {{ booking.schedule.theatre.name }} </p>
        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Tickets: {{ booking.number_of_tickets }} </p>
    </div>
    <Link :href="route('showFilm', {film: booking.schedule.film.id})" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        Go to film
        <svg aria-hidden="true" class="w-4 h-4 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
    </Link>
    <form @submit.prevent="submit">
        <button v-if="isCancelable" class="inline-flex items-center mt-2 mb-5 px-3 py-2 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-400">
            Cancel Booking
            <svg aria-hidden="true" class="w-4 h-4 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </button>
    </form>
</template>

