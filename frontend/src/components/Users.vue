<template>
  <div class="container mx-auto flex" v-if="!users">
    <svg
      class="animate-spin -ml-1 mr-3 h-5 w-5 text-black"
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24"
    >
      <circle
        class="opacity-25"
        cx="12"
        cy="12"
        r="10"
        stroke="currentColor"
        stroke-width="4"
      ></circle>
      <path
        class="opacity-75"
        fill="currentColor"
        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
      ></path>
    </svg>
    Fetching Weather Data..
  </div>
  <div class="container mx-auto bg-white shadow-lg mt-4">
    <table
      v-if="users"
      class="min-w-full divide-y rounded divide-gray-200 table-auto"
      :class="{ 'animate-pulse': !users }"
    >
      <thead class="bg-gray-50 sticky top-0">
        <tr>
          <th
            class="px-6 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider"
          >
            #
          </th>
          <th
            class="px-6 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider"
          >
            Name
          </th>
          <th
            class="px-6 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider"
          >
            Location
          </th>
          <th
            class="px-6 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider"
          >
            Weather
          </th>
          <th
            class="px-6 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-900 uppercase tracking-wider"
          >
            Last Updated
          </th>
          <th
            class="px-6 py-3 bg-gray-50 text-center text-xs font-semibold text-gray-900 uppercase tracking-wider"
          >
            Actions
          </th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        <tr
          v-for="user in users"
          :key="user.id"
          :class="{
            'bg-green-200 transition duration-500 delay-100 ease-out':
              user.forceUpdating,
            'hover:bg-gray-50': !user.forceUpdating,
          }"
        >
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ user.id }}
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ user.name }}
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            {{}}
            {{ user.longitude }}
            <br />
            {{ user.latitude }}
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            <WeatherCard
              v-if="user.weather.weather_data"
              :weather_data="user.weather.weather_data"
            />
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            <i>Refreshed : </i>
            {{ moment(user.weather.updated_time).fromNow() }}
            <br />
            <i>Weather Station :</i>
            {{ moment.unix(user.weather.weather_data.dt).fromNow() }}
          </td>
          <td class="text-center text-sm font-medium gap">
            <button
              title="View Detail"
              @click="openModal(user)"
              class="text-gray-500 hover:text-slate-700 font-bold py-2 px-2 rounded"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-6 h-6"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                />
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                />
              </svg>
            </button>
            <button
              title="Refresh"
              @click="forceUpdate(user)"
              class="text-blue-500 hover:text-blue-900 font-bold py-2 px-2 rounded"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-6 h-6"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"
                />
              </svg>
            </button>
          </td>
        </tr>
      </tbody>
    </table>
    <user-detail
      v-if="isModalVisible"
      :user="selectedUser"
      :showModal="isModalVisible"
      @close-modal="closeModal"
    />
  </div>
</template>
<script>
import UserDetail from "@/components/UserDetail.vue";
import WeatherCard from "@/components/WeatherCard.vue";

import moment from "moment";
export default {
  components: {
    UserDetail,
    WeatherCard,
  },
  data() {
    return {
      users: null,
      selectedUser: null,
      isModalVisible: false,
      forceUpdating: false,
      moment,
    };
  },

  created() {
    this.fetchData();
  },

  methods: {
    async fetchData() {
      const url = "http://localhost/";

      await (
        await fetch(url)
      )
        .json()
        .then((data) => {
          console.log(data);
          this.users = data.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    async forceUpdate(user) {
      const url = "http://localhost/forceUpdate/" + user.id;
      user.forceUpdating = true;
      await (
        await fetch(url, { method: "PUT" })
      )
        .json()
        .then((data) => {
          console.log(data);

          const index = this.users.findIndex((row) => row.id === data.data.id);

          this.users.splice(index, 1, data.data);
          data.data.forceUpdating = false;
        })
        .catch((error) => {
          console.log(error);
          user.forceUpdating = false;
        });
    },
    openModal(user) {
      this.selectedUser = user;
      this.isModalVisible = true;
    },
    closeModal() {
      this.selectedUser = null;
      this.isModalVisible = false;
    },
  },
};
</script>
