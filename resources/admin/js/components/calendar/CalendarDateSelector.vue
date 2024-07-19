<template>
    <div class="calendar-date-selector">
        <span @click="selectPrevious"><</span>
        <span class="calendar-date-selector__month">{{selectedMonth}}</span>
        <span @click="selectNext">></span>
    </div>
</template>

<script>
import dayjs from "dayjs";

export default {
    name: "CalendarModeSelector",

    props: {
        currentDate: {
            type: String,
            required: true
        },

        selectedDate: {
            type: Object,
            required: true
        }
    },
    computed: {
        selectedMonth() {
            return this.selectedDate.locale('ru').format("MMMM YYYY");
        }
    },

    methods: {
        selectPrevious() {
            let newSelectedDate = dayjs(this.selectedDate).subtract(1, "month");
            this.$emit("dateSelected", newSelectedDate);
        },

        selectCurrent() {
            let newSelectedDate = dayjs(this.currentDate);
            this.$emit("dateSelected", newSelectedDate);
        },

        selectNext() {
            let newSelectedDate = dayjs(this.selectedDate).add(1, "month");
            this.$emit("dateSelected", newSelectedDate);
        }
    }
};
</script>

<style lang="scss" scoped>
.calendar-date-selector {
    display: flex;
    justify-content: center;
    color: #006672;
    font-size: 17px;
    &__month {
        text-transform: capitalize;
        margin-left: 15px;
        margin-right: 15px;
    }
}

.calendar-date-selector > * {
    cursor: pointer;
    user-select: none;
}
</style>
