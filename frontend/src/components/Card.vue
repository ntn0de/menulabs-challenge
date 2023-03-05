<template>
  <div>
    <div
      class="rounded-lg bg-white p-4 text-[0.8125rem] leading-5 shadow-xl shadow-black/5 hover:bg-slate-50 ring-1 ring-blue-300"
    >
      <div class="flex justify-between">
        <div class="font-medium text-slate-900">{{ title }}</div>
      </div>
      <div class="mt-1 text-slate-700">{{ value }} {{ unit }}</div>
      <!-- <div class="mt-6 font-medium text-slate-900">621 users</div> -->
    </div>
  </div>
</template>
<script>
export default {
  props: {
    title: null,
    value: null,
    unit: null,
  },
};
</script>
