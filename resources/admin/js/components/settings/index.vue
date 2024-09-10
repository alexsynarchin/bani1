<template>
<section>
    <el-form :model="form" label-position="top"  class="mb-4">
                <el-form-item label="Время старта бронирования по умолчанию" prop="default" :error="errors.get('default')">
                    <el-time-select
                        style="min-width: 220px"
                        v-model="form.default"
                        :picker-options="{
                start: '10:00',
                step: '00:30',
                end: '21:00'
  }"
                        placeholder="Выбрать время">
                    </el-time-select>
                </el-form-item>

                <el-form-item label="Время старта бронирования для выходных и праздников" prop="day_off" :error="errors.get('day_off')">
                    <el-time-select
                        style="min-width: 220px"
                        v-model="form.day_off"
                        :picker-options="{
                start: '10:00',
                step: '00:30',
                end: '21:00'
  }"
                        placeholder="Выбрать время">
                    </el-time-select>
                </el-form-item>
        <el-form-item label="Время  старта бронирования - суббота" prop="saturday" :error="errors.get('saturday')">
            <el-time-select
                style="min-width: 220px"
                v-model="form.saturday"
                :picker-options="{
                start: '10:00',
                step: '00:30',
                end: '21:00'
  }"
                placeholder="Выбрать время">
            </el-time-select>
        </el-form-item>
        <el-form-item label="Время старта бронирования - воскресенье" prop="sunday" :error="errors.get('sunday')">
            <el-time-select
                style="min-width: 220px"
                v-model="form.sunday"
                :picker-options="{
                start: '10:00',
                step: '00:30',
                end: '21:00'
  }"
                placeholder="Выбрать время">
            </el-time-select>
        </el-form-item>

        <el-button type="success" @click.prevent="setStartTime">Сохранить</el-button>
    </el-form>
    <el-form v-model="prices" label-position="top" class="mb-4">

        <el-form-item label="Стоимость мест">
            <el-input-number :min="0" :controls="false" v-model="prices.places"></el-input-number>
        </el-form-item>
        <el-form-item v-for="(cabinet, index) in prices.cabinets"
                      :key="cabinet.id"
                      :label="'Кабинка №' + cabinet.number"
        >
            <el-input-number :min="0" :controls="false" v-model="prices.cabinets[index].price"></el-input-number>
        </el-form-item>
        <el-button type="success" @click.prevent="setPrices">Сохранить</el-button>
    </el-form>
    <event-dates></event-dates>
</section>
</template>
<script>
import {Errors} from "../../services/errors";
import EventDates from "./event-dates";
export default {
    components: {
        EventDates,
    },
        data() {
            return {
                form : {
                    default: '17:00',
                    day_off:'12:00',
                    saturday: '12:00',
                    sunday: '11:00',
                },
                prices: {
                    places: 0,
                    cabinets: []
                },
                errors: new Errors(),
            }
        },

        methods: {
            getStartTime() {
                axios.get('/admin/api/settings/get-start-time')
                    .then((response) => {
                        let form = response.data;
                        console.log(response.data)
                        Object.keys(form).forEach(((key) =>{
                            if(form[key]) {
                                this.form[key] = form[key];
                            }
                        }));
                    })
            },
            setStartTime() {
                axios.post('/admin/api/setting/set-start-time', this.form)
                    .then((response) => {
                        this.form = response.data
                    })
                    .catch((error) => {
                        this.errors.record(error.response.data.errors)
                    })
            },
            getPrices() {
                axios.get('/admin/api/settings/get-prices')
                    .then((response) => {
                        this.prices = response.data;
                    })
            },
            setPrices() {
                axios.post('/admin/api/setting/set-prices', this.prices)
                    .then((response) => {
                        //this.prices = response.data
                    })
                    .catch((error) => {
                        this.errors.record(error.response.data.errors)
                    })
            }
        },
    mounted() {
        this.getStartTime();
        this.getPrices();
    }
}
</script>

