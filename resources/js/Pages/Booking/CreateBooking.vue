<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Card from '@/Components/Card.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import Dropdown from '@/Components/Dropdown.vue';
import { onMounted } from 'vue';

const props = defineProps({
    film: Object,
    status: String,
    message: String,
});

const form = useForm({
    numOfTickets: 1,
    scheduleId: 0,
});

const saveSchedule = (scheduleId) => {
    form.scheduleId = scheduleId;
}

const numberRange = (end) => {
  return new Array(end).fill().map((d, i) => i + 1);
}

const submit = () => {
    form.transform(data => ({
        ...data,
        scheduleId: form.scheduleId,
    })).post(route('bookings.saveBooking'), {
       
    });
};
</script>

<template>
    <AppLayout>
        <div v-if="film.schedules?.length == null || film.schedules?.length  < 1">
            <h2 class="font-semibold text-xl text-red-500 text-center mt-10">
                There are no upcoming schedules available for this film
            </h2>
        </div>
        <div class="grid grid-cols-3 gap-8">
            <div v-for="schedule in film.schedules" :key="schedule.id" class="max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 ml-10 mt-10">
                <a href="#">
                    <img class="rounded-t-lg" :src="film.cover_photo" alt="here" />
                </a>
                <form @submit.prevent="submit">
                    <div class="p-5">
                        <a href="#">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ film.title }}</h5>
                        </a>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Date & Time: {{ schedule.starts_at }}</p>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Cinema: {{ schedule.theatre.cinema.name }}</p>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Theatre: {{ schedule.theatre.name }} </p>
                        <div>
                        <InputLabel for="numOfTickets" value="Choose Number of Tickets" />
                       
                        <div>
                            <select id="numOfTicekts" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required
                                v-model = "form.numOfTickets"
                            >
                                <option v-for="seat in numberRange(schedule.seats_remaining)" 
                                    :key="seat" 
                                    :value="seat"
                                >
                                    {{ seat }}
                                </option>
                            </select>
                        </div>
                        <br/>
                    </div>
                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" :disabled="schedule.seats_remaining < 1" @click="saveSchedule(schedule.id)">
                            Submit
                            <svg aria-hidden="true" class="w-4 h-4 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
