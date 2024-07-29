<template>
    <li
        class="calendar-day"

    >
        <span class="calendar-day__num"
          :class="{
          'calendar-day__num--not-current': !day.isCurrentMonth,
          'calendar-day__num--today': isToday,
          'calendar-day__num--event': isEvent,
          'calendar-day__num--selected': isSelectedDay,
          'calendar-day__num--previous':isPrevious,
          'calendar-day__num--woman': isWoman,
    }"
        @click.prevent="selectDay"
        >{{ label }}</span>
    </li>
</template>

<script>
import dayjs from "dayjs";

export default {
    name: "CalendarMonthDayItem",

    props: {
        isWoman: {
            type: Boolean,
            default: false
        },
        isPrevious: {
            type: Boolean,
            default: false
        },
        isEvent: {
            type: Boolean,
            default: false
        },
        day: {
            type: Object,
            required: true
        },

        isCurrentMonth: {
            type: Boolean,
            default: false
        },

        isToday: {
            type: Boolean,
            default: false
        },
        isSelectedDay : {
            type: Boolean,
            default: false
        },
    },
    methods:{
        selectDay() {
            this.$emit('select-day', this.day.date)
        }
    },
    computed: {
        label() {
            return dayjs(this.day.date).format("D");
        }
    }
};
</script>

<style lang="scss" scoped>

.calendar-day {
    text-align: center;
    position: relative;
    font-size: 16px;
    font-family: "Jost", sans-serif;

    color: #2E2E2E;
    padding: 2.5px;
    height: 36px;
    &__num {
        cursor: pointer;
        height: 100%;
        align-items: center;
        justify-content: center;
        display: flex;
        border: 1px solid transparent;
        border-radius: 0;
        color: #2E2E2E;
        &--today {
            color: #006672;
            font-weight: bold;
        }
        &--not-current {
           // color: rgba(0,102,114,0.6);
            //border-color:rgba(0,102,114,0.6);
        }



        &--previous {
            color: gray;
            //border-color:rgba(0,102,114,0.6);
        }
        &--event {

        }
        &--woman {
            //background-color: pink;
            //border-color:  pink;
            //color: #fff;
        }
        &--selected {
            background-color: #CAA769;
            border-color:  #CAA769;
            color: #fff;
        }
    }
}

.calendar-day--today > span {
    color: #fff;
    border-radius: 9999px;
    background-color: var(--grey-800);
}
</style>
