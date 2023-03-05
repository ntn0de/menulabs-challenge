<template>
  <div class="relative flex flex-col-reverse rounded-lg p-2">
    <figcaption class="flex items-center space-x-4">
      <img
        v-if="weather_data.weather[0].icon"
        :src="getIMG(weather_data.weather[0].icon)"
        alt=""
      />
      <div class="flex-auto">
        <div class="text-xl text-slate-900 font-semibold">
          {{ weather_data.main.temp }} °C
        </div>
        <div class="mt-0.5">
          {{ weather_data.main.temp_min }} /
          {{ weather_data.main.temp_max }} Feels like
          {{ weather_data.main.feels_like }} °C
        </div>
        <blockquote class="mt-2 text-slate-700">
          {{ weather_data.weather[0].main }}
          <i>({{ weather_data.weather[0].description }}) </i>
        </blockquote>
      </div>
    </figcaption>
  </div>
</template>
<script>
export default {
  props: {
    weather_data: Object,
  },
  methods: {
    getIMG(ico) {
      return "http://openweathermap.org/img/wn/" + ico + "@2x.png";
    },
  },
};
</script>
