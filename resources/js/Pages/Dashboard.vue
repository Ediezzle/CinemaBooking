<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import  FilmPreview  from '../Components/FilmPreview.vue';

defineProps({
    canRegister: Boolean,
    films: Array,
});
</script>

<template>
    <AppLayout>
        <template>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-10 mt-5 mb-5 md:mt-10 md:mb-10">
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
    </AppLayout>
</template>

<style>
    body, html {
        background-color: #f7fafc;
    }
</style>
