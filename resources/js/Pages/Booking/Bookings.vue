<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import Booking  from '@/Components/Booking.vue';

defineProps({
    bookings: Array,
    status: String
});
</script>

<template>
    <AppLayout>
        <template>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Bookings
            </h2>
        </template>

        <!-- <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-10 mt-5 mb-5 md:mt-10 md:mb-10">
            <div v-for="booking in bookings" :key="booking.id">
                <Booking 
                    :booking="booking" 
                    :status="status"
                />
            </div>
        </div> -->


        <div class="grid grid-cols-1 mr-10 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div v-for="booking in bookings" :key="booking.id" class="max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 ml-10 mt-10">
                <Booking
                    :booking="booking"
                    :status="status"
                />
            </div>
        </div>
    </AppLayout>
</template>

<style>
    body, html {
        background-color: #f7fafc;
    }
</style>
