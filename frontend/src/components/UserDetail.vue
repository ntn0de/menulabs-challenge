<template>
  <div
    class="fixed z-10 inset-0 overflow-y-auto"
    :class="{ hidden: !showModal }"
  >
    <div
      class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
    >
      <div class="fixed inset-0 transition-opacity" aria-hidden="true">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
      </div>

      <div
        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-headline"
      >
        <div
          class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex justify-between items-center"
        >
          <div class="sm:items-start">
            <b>{{ user.name }}</b>
          </div>
          <button
            type="button"
            @click="closeModal"
            class="text-white font-bold py-2 px-4 rounded-full bg-gray-400"
          >
            X
          </button>
        </div>
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 ">
          <div class="sm:flex sm:items-start">
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
              <WeatherCard :weather_data="user.weather.weather_data" />

              <div class="mt-2">
                <div class="grid grid-cols-3 gap-3 justify-between">
                  <Card
                    title="Sunrise"
                    :value="
                      moment
                        .unix(user.weather.weather_data.sys.sunrise)
                        .utcOffset(user.weather.weather_data.timezone / 60)
                        .format('LT')
                    "
                    unit=""
                  />
                  <Card
                    title="Sunset"
                    :value="
                      moment
                        .unix(user.weather.weather_data.sys.sunset)
                        .utcOffset(user.weather.weather_data.timezone / 60)
                        .format('LT')
                    "
                    unit=""
                  />
                  <Card
                    title="Humidity"
                    :value="user.weather.weather_data.main.humidity"
                    unit="%"
                  />
                  <Card
                    title="Pressure"
                    :value="user.weather.weather_data.main.pressure"
                    unit="hPa"
                  />

                  <Card
                    title="Sea Level(Pressure)"
                    :value="user.weather.weather_data.main.sea_level"
                    unit="hPa"
                  />
                  <Card
                    title="Ground Level(Pressure)"
                    :value="user.weather.weather_data.main.grnd_level"
                    unit="hPa"
                  />
                  <Card
                    title="Visibility"
                    :value="user.weather.weather_data.visibility"
                    unit="m"
                  />
                  <Card
                    title="Wind Speed"
                    :value="user.weather.weather_data.wind.speed"
                    unit="m/sec"
                  />
                  <Card
                    title="Wind Direction"
                    :value="user.weather.weather_data.wind.deg"
                    unit="°"
                  />
                  <Card
                    title="Wind Gust"
                    :value="user.weather.weather_data.wind.gust"
                    unit="m/sec"
                  />
                  <Card
                    title="Cloud"
                    :value="user.weather.weather_data.clouds.all"
                    unit="%"
                  />
                  <!-- <Card
                    title="Rain (last 1 hr)"
                    v-if="user.weather.weather_data.rain"
                    :value="user.weather.weather_data.rain.1h"
                    unit="mm"
                  />
                  <Card
                    title="Rain (last 3 hrs)"
                    v-if="user.weather.weather_data.rain"
                    :value="user.weather.weather_data.rain.3h"
                    unit="mm"
                  /> -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import WeatherCard from "@/components/WeatherCard.vue";
import Card from "@/components/Card.vue";
import moment from "moment";

export default {
  components: {
    WeatherCard,
    Card,
  },
  props: {
    user: Object,
    showModal: Boolean,
  },
  methods: {
    closeModal() {
      this.$emit("close-modal");
    },
  },
  data() {
    return {
      moment,
    };
  },
};
</script>
