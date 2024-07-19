<template>
    <div class="calendar-date-indicator">{{ selectedMonth }}</div>
</template>

<script>
export default {
    props: {
        selectedDate: {
            type: Object,
            required: true
        }
    },

    computed: {
        selectedMonth() {
            return this.selectedDate.locale('ru').format("MMMM YYYY");
        }
    }
};
</script>

<style scoped>
.calendar-date-indicator {
    font-size: 24px;
    font-weight: 600;
}
</style>

