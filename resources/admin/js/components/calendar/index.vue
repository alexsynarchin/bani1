<template>
    <div class="calendar-month">
        <div style="width: 290px; margin-right: auto; margin-left: auto;">
        <div class="calendar-month-header">
            <CalendarDateSelector
                :current-date="today"
                :selected-date="selectedDate"
                @dateSelected="selectDate"
            />
        </div>

        <CalendarWeekdays/>

        <ul class="days-grid">
            <CalendarMonthDayItem
                v-for="day in days"
                :key="day.date"
                :day="day"
                :is-woman="isWomanDay(day.date)"
                :is-today="day.date === today"
                :is-previous="day.date < today"
                :is-event="isEventDate(day.date)"
                :is-selected-day="day.date === reserveData.selectedDay"
                @select-day="selectDay"
            />
        </ul>
        <calendar-legend></calendar-legend>
        </div>
        <calendar-time
            ref="calendar_time"
            @select-time="selectTime"
            :min-start-time="minStartTime"
            :max-end-time="maxEndTime"
        ></calendar-time>
        <div class="calendar-btn__wrap">
            <button class="calendar-btn" @click.prevent="selectDayAndTime">Выбрать</button>
        </div>
        <div class="calendar-time__descr">
            Минимальное время брони — 2 часа
        </div>
    </div>
</template>
<script>
import dayjs from "dayjs";
require('dayjs/locale/ru');
import weekday from "dayjs/plugin/weekday";
import weekOfYear from "dayjs/plugin/weekOfYear";
import CalendarMonthDayItem from "./CalendarMonthDayItem";
import CalendarDateIndicator from "./CalendarDateIndicator";
import CalendarDateSelector from "./CalendarDateSelector";
import CalendarWeekdays from "./CalendarWeekdays";
import CalendarLegend from "./CalendarLegend";
import CalendarTime from "./CalendarTime";
dayjs.extend(weekday);
dayjs.extend(weekOfYear);
export default {
    components: {
        CalendarMonthDayItem,
        CalendarDateIndicator,
        CalendarDateSelector,
        CalendarWeekdays,
        CalendarLegend,
        CalendarTime
    },

    data() {
        return {
            selectedDate: dayjs(),
            today: dayjs().format("YYYY-MM-DD"),
            eventDay:"2022-10-11",
            reserveData: {
                selectedDay:null,
                selectedDayString:'',
                startTime:'',
                endTime:'',
            },
            minTimes: {
                default: '17:00',
                day_off: '12:00',
                saturday: '12:00',
                sunday: '11:00'
            },
            event_dates:[],
        };
    },

    computed: {
        maxEndTime() {
            let time;
            if(this.reserveData.selectedDay === '2022-12-31') {
                time= '20:00'
            } else {
                time= '24:00'
            }
            return time;
        },
        minStartTime() {
            let time;
            let weekday = this.getWeekday(this.reserveData.selectedDay);
            //console.log(this.reserveData.selectedDay);
            if(this.isEventDate(this.reserveData.selectedDay)) {
                let index = this.event_dates.findIndex(item => item.date === this.reserveData.selectedDay);
                time = this.event_dates[index]['start_time'];
            }
            else if(weekday === 6) {
                time = this.minTimes.saturday;
            }
            else if( weekday ===  0) {
                time = this.minTimes.sunday;
            } else {
                time = this.minTimes.default;
            }
            return time;
        },
        selectedDay() {
           return  dayjs(this.reserveData.selectedDay).locale('ru').format('DD MMMM');
        },
        days() {
            return [
                ...this.previousMonthDays,
                ...this.currentMonthDays,
                ...this.nextMonthDays
            ];
        },


        month() {
            return Number(this.selectedDate.format("M"));
        },

        year() {
            return Number(this.selectedDate.format("YYYY"));
        },

        numberOfDaysInMonth() {
            return dayjs(this.selectedDate).daysInMonth();
        },

        currentMonthDays() {
            return [...Array(this.numberOfDaysInMonth)].map((day, index) => {
                return {
                    date: dayjs(`${this.year}-${this.month}-${index + 1}`).format(
                        "YYYY-MM-DD"
                    ),
                    isCurrentMonth: true
                };
            });
        },

        previousMonthDays() {
            const firstDayOfTheMonthWeekday = this.getWeekday(
                this.currentMonthDays[0].date
            );
            const previousMonth = dayjs(`${this.year}-${this.month}-01`).subtract(
                1,
                "month"
            );

            // Cover first day of the month being sunday (firstDayOfTheMonthWeekday === 0)
            const visibleNumberOfDaysFromPreviousMonth = firstDayOfTheMonthWeekday
                ? firstDayOfTheMonthWeekday - 1
                : 6;

            const previousMonthLastMondayDayOfMonth = dayjs(
                this.currentMonthDays[0].date
            )
                .subtract(visibleNumberOfDaysFromPreviousMonth, "day")
                .date();

            return [...Array(visibleNumberOfDaysFromPreviousMonth)].map(
                (day, index) => {
                    return {
                        date: dayjs(
                            `${previousMonth.year()}-${previousMonth.month() +
                            1}-${previousMonthLastMondayDayOfMonth + index}`
                        ).format("YYYY-MM-DD"),
                        isCurrentMonth: false
                    };
                }
            );
        },

        nextMonthDays() {
            const lastDayOfTheMonthWeekday = this.getWeekday(
                `${this.year}-${this.month}-${this.currentMonthDays.length}`
            );

            const nextMonth = dayjs(`${this.year}-${this.month}-01`).add(1, "month");

            const visibleNumberOfDaysFromNextMonth = lastDayOfTheMonthWeekday
                ? 7 - lastDayOfTheMonthWeekday
                : lastDayOfTheMonthWeekday;

            return [...Array(visibleNumberOfDaysFromNextMonth)].map((day, index) => {
                return {
                    date: dayjs(
                        `${nextMonth.year()}-${nextMonth.month() + 1}-${index + 1}`
                    ).format("YYYY-MM-DD"),
                    isCurrentMonth: false
                };
            });
        }
    },

    methods: {
        isWomanDay(date) {
            console.log(date);
            let weekday = this.getWeekday(date);
            if(weekday === 1) {
                if(date === '2023-01-02') {
                    return false;
                }
                return true;
            } else {
                return false;
            }
        },
        isEventDate(date) {

            let index = this.event_dates.findIndex(item => item.date === date);
            if(index === -1) {
                return false;
            } else {
                return  true;
            }
        },
        selectDayAndTime() {
            if(this.reserveData.selectedDay && this.reserveData.startTime && this.reserveData.endTime) {
                this.$emit('select-day-and-time', this.reserveData);
            }
        },
        checkIsOffDay(date) {
            let index = this.event_dates.findIndex(item => item.date === date);
            if(index === -1) {
                return true;
            } else {
                if(this.event_dates[index].day_off) {
                    return false;
                } else {
                    return true;
                }
            }
        },
        async selectDay(date) {
            if(this.today <= date && this.checkIsOffDay(date)) {
                this.reserveData.selectedDay = date;
                this.reserveData.selectedDayString = this.selectedDay;
                this.reserveData.startTime = '';
                this.reserveData.endTime='';
                this.$refs.calendar_time.startTime= '';
                this.$refs.calendar_time.endTime='';
            }
        },
        selectTime(data) {
            this.reserveData.endTime = data.endTime;
            this.reserveData.startTime = data.startTime;
        },
        getWeekday(date) {
            return dayjs(date).weekday();
        },

        async selectDate(newSelectedDate) {
            this.selectedDate = newSelectedDate;
            this.dataReady = false;
            await this.getEventDates();
            this.dataReady = true;
        },
        getCurrentDate() {
            //this.$root.api_url
            axios.get('/api/get-current-date')
                .then((response) => {
                    this.today = response.data;
                    this.selectDay(this.today);
                    this.dataReady = true;
                })
        },
        getStartTime() {
            axios.get('/api/settings/get-start-time')
                .then((response) => {
                    let form = response.data;
                    Object.keys(form).forEach(((key) =>{
                        if(form[key]) {
                            this.minTimes[key] = form[key];
                        }
                    }));
                })
        },
        getEventDates() {
            axios.get('/api/event-dates', {params: {'start': this.days[0]['date'], end: this.days[this.days.length - 1]['date']}})
                .then((response) => {
                    this.event_dates = response.data;
                })
        },
        async getStartData() {
            await this.getEventDates();
            await this.getStartTime();
            //await this.getCurrentDate();
            this.dataReady = true;
        }
    },
    async mounted() {
        await this.getStartData();
    }
}
</script>
<style lang="scss" scoped>
.calendar-month {
    position: relative;
    background-color: var(--grey-200);
    border: solid 1px var(--grey-300);
}

.day-of-week {
    color: var(--grey-800);
    font-size: 18px;
    background-color: #fff;
    padding-bottom: 5px;
    padding-top: 10px;
}

.day-of-week,
.days-grid {
    list-style-type: none;
    margin-left: 0;
    padding-left: 0;
    display: grid;
    grid-template-columns: repeat(7, 1fr);
}

.day-of-week > * {
    text-align: right;
    padding-right: 5px;
}

.days-grid {
    height: 100%;
    position: relative;
    grid-column-gap: var(--grid-gap);
    grid-row-gap: var(--grid-gap);
    border-top: solid 1px var(--grey-200);
}
.calendar-btn {
    color: #fff;
    background: #3097A1;
    box-shadow: 0 0 0 transparent;
    border: 0 solid transparent;
    border-radius: 6px;
    padding: 6px 30px;
    font-size: 16px;
    text-shadow: 0 0 0 transparent;
    &__wrap {
        margin-top: 20px;
        text-align: center;
    }
}
</style>

