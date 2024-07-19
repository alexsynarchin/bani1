<template>
    <ul class="day-of-week">
        <li v-for="weekday in weekdays" :key="weekday" class="day-of-week__item">{{ weekday }}</li>
    </ul>
</template>

<script>
const WEEKDAYS = ["Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс"];

export default {
    name: "CalendarWeekdays",

    computed: {
        weekdays() {
            return WEEKDAYS;
        }
    }
};
</script>

<style lang="scss" scoped>
.day-of-week {
    list-style-type: none;
    padding-left: 0;
    margin-left: 0;
    display: grid;
    margin-bottom: 0;
    grid-template-columns: repeat(7, 1fr);
}

.day-of-week > * {
    text-align: right;
    padding-right: 5px;
}
</style>
