<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import  Booking  from '@/Components/Booking.vue';

defineProps({
    bookings: Array,
});
</script>

<template>
    <AppLayout>
        <template>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Upcoming Bookings
            </h2>
        </template>

        <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-10 mt-5 mb-5 md:mt-10 md:mb-10">
            <div v-for="booking in bookings" :key="booking.id">
                <Booking 
                    :booking="booking" 
                    :status="'upcoming'"
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
