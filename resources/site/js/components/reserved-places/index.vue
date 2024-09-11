<template>
    <el-dialog
        title="Забронированнные места"
        :visible.sync="dialogVisible"
        width="90%"
        :before-close="handleClose" append-to-body>
        <div class="places-dashboard__wrap" style="width: 100%">
            <section class="places-dashboard" style="margin-left: auto; margin-right: auto">
                <el-date-picker
                    style="width:250px;margin-bottom: 20px"
                    v-model="selected_date"
                    type="date"
                    @change="pickDate"

                    placeholder="Выбрать дату">
                </el-date-picker>
                <navigation @select-date="selectDate"></navigation>
                <section class="places-dashboard__content">
                    <h1 class="text-center places-dashboard__title">
                        {{date.date_string}}
                    </h1>
                    <section class="d-flex justify-content-between">
                        <ul class="places-reserv-list"  style="margin-right: 20px">
                            <li class="places-reserv-list__item" v-for="(place, index) in places">
                                <label class="places-reserv-list__label">Место № {{place.number}} </label>
                                <div class="places-reserv-list__reserv-wrap">
                            <span class="places-reserv-list__reserv" v-for="(reservation, index) in place.reservations"
                                  :class="{
                                'places-reserv-list__reserv--selected': (place.number === typeNumber) && (selectedType === 'place') && (selectedIndex === index)
                                  }"

                            >
                                с {{reservation.start_time}} по {{reservation.end_time}}
                            </span>
                                </div>
                            </li>
                        </ul>
                        <ul class="places-reserv-list" >
                            <li class="places-reserv-list__item" v-for="(place, index) in cabinets">
                                <label class="places-reserv-list__label">Кабинка № {{place.number}} </label>
                                <div class="places-reserv-list__reserv-wrap">
                            <span class="places-reserv-list__reserv" v-for="(reservation, index) in place.reservations"
                                  @click.prevent="selectReservation(reservation, place.number, 'cabinet', index)"
                                  :class="{
                                'places-reserv-list__reserv--selected': (place.number === typeNumber) && (selectedType === 'cabinet') && (selectedIndex === index)
                                  }"
                            >
                                с {{reservation.start_time}} по {{reservation.end_time}}
                            </span>
                                </div>
                            </li>
                        </ul>
                    </section>


                </section>
            </section>
            <section class="reservation-inf__wrap">
                <div class="reservation-inf" v-if="reservationInf.number">
                    <h3 class="reservation-inf__title">
                        {{reservationInf.title}} № {{reservationInf.number}}
                    </h3>
                    <div class="reservation-inf__item">
                        <label class="reservation-inf__label">
                            Время брони:
                        </label>
                        <span class="reservation-inf__value">
                        с {{reservationInf.start}} по {{reservationInf.end}}
                    </span>
                    </div>
                    <div class="reservation-inf__item">
                        <label class="reservation-inf__label">
                            Имя:
                        </label>
                        <span class="reservation-inf__value">
                        {{reservationInf.client_name}}
                    </span>
                    </div>
                    <div class="reservation-inf__item">
                        <label class="reservation-inf__label">
                            Номер телефона:
                        </label>
                        <span class="reservation-inf__value">
                        {{reservationInf.phone}}
                    </span>
                    </div>
                    <div class="reservation-inf__item">
                        <label class="reservation-inf__label">
                            Стоимость:
                        </label>
                        <span class="reservation-inf__value">
                             <template v-if="order.type === 'invitation'"></template>
                            <template v-else> {{reservationInf.price}}  руб.</template>

                    </span>
                    </div>
                    <div class="reservation-inf__item">
                        <label class="reservation-inf__label">
                            Тип заказа:
                        </label>
                        <span class="reservation-inf__value">
                           <template v-if="order.type === 'standard'"> Онлайн оплата</template>
                            <template v-if="order.type === 'invitation'"> Пригласительный</template>
                            <template v-if="order.type === 'cash'"> Наличные</template>
                            <template v-if="order.type === 'transfer'"> Перенос</template>
                            <template v-if="order.type === 'sert'"> Подарочная карта</template>
                            <template v-if="order.type === 'founder'"> Учредитель</template>
                             <template v-if="order.type === 'offsetting'"> Взаимозачет</template>
                            <template v-if="order.type === 'non-cash'">Безнал</template>
                    </span>
                    </div>
                    <section v-if="user.id === 2">
                        <div class="mt-4">
                            <button class="reserve-inf__btn mb-3" @click.prevent = cancelReservation(reservation.id)>
                                Отменить бронирование
                            </button>


                        </div>
                        <h3 class="reservation-inf__title">
                            Заказ №  {{order.id}}
                        </h3>
                        <div class="d-flex flex-wrap justify-content-center">
                            <div v-for="reservation in order.reservations" style="padding: 10px 20px;
             border: 1px dashed #fff;
             margin-right: 10px;
             margin-bottom: 10px;
            border-radius: 6px;">
                            <span v-if="reservation.reservationable_type === 'App\\Models\\Cabinet'">
                                Кабинка
                            </span>
                                <span v-if="reservation.reservationable_type === 'App\\Models\\Place'">
                                Место
                            </span>

                                {{reservation.reservationable.number}}
                            </div>
                        </div>

                        <button class="reserve-inf__btn mb-3" @click.prevent="cancelOrder(reservation.order_id)">
                            Отменить весь заказ
                        </button>
                    </section>


                </div>
            </section>
        </div>
        <span slot="footer" class="dialog-footer">
    <el-button @click="dialogVisible = false">Закрыть</el-button>

  </span>
    </el-dialog>
</template>
<script>
import dayjs from "dayjs";
import Navigation from "./navigation.vue";
require('dayjs/locale/ru');
    export default {
        components: {Navigation},
        data() {
            return {
                dialogVisible: false,
                selected_date: "",
                date: {

                },
                selectedType:'',
                selectedIndex: null,
                typeNumber:null,
                places:[],
                cabinets:[],
                reservation:{},
                order:{},
                reservationInf: {
                    number:null,
                    title:"",
                    start:'',
                    end:'',
                    price: '',
                    client_name:"",
                    phone: "",
                    status:''
                }
            };
        },
        methods: {
            handleClose() {
                this.dialogVisible=false;
            },
            showPlaces(reserveData) {
                console.log(reserveData)
                this.dialogVisible=true;
                console.log(dayjs().format("YYYY-MM-DD"))
            },
            pickDate(date) {
                let data = {
                    date: dayjs(date).format('YYYY-MM-DD'),
                    date_string: dayjs(date).locale('ru').format('DD MMMM'),
                }
                this.selectDate(data);


            },
            cancelReservation(id) {
                this.$confirm('Вы уверенты что хотите отменить бронирование', 'Отмена бронирования', {
                    confirmButtonText: 'Отменить бронирование',
                    cancelButtonText: 'Закрыть',
                    type: 'warning'
                }).then(() => {
                    axios.post('/admin/api/reservation/cancel/' + id)
                        .then((response) => {
                            this.getReservations(this.date);
                            this.reservationInf = {};
                            this.selectedIndex = null;
                            this.$message({
                                type: 'success',
                                message: 'Бронирование отменено'
                            });
                        })

                }).catch(() => {

                });
            },
            cancelOrder(id) {
                this.$confirm('Вы уверенты что хотите отменить заказ полностью', 'Отмена заказа', {
                    confirmButtonText: 'Отменить заказ',
                    cancelButtonText: 'Закрыть',
                    type: 'warning'
                }).then(() => {
                    axios.post('/admin/api/reservation/cancel-order/' + id)
                        .then((response) => {
                            this.getReservations(this.date);
                            this.reservationInf = {};
                            this.selectedIndex = null;
                            this.$message({
                                type: 'success',
                                message: 'Бронирование отменено'
                            });
                        })

                }).catch(() => {

                });
            },
            selectDate(date) {
                this.date = date;
                this.reservationInf = {
                    number:null,
                    title:"",
                    start:'',
                    end:'',
                    client_name:"",
                    phone: "",
                    status:''
                };
                this.selectedType = '';
                this.getReservations(date);
            },
            getReservations(date) {
                axios.get(this.$root.api_url +  '/api/reservations', {params:{date:date}})
                    .then((response) => {
                        this.places = response.data.places;
                        this.cabinets = response.data.cabinets;
                    })
            },
            selectReservation(reservation, number, type, index) {
                this.selectedType = type;
                this.typeNumber = number;
                this.selectedIndex = index;
                if(type === 'place')
                {
                    this.reservationInf.title = "Место";
                } else {
                    this.reservationInf.title = "Кабинка";
                }
                this.reservation = reservation;
                this.reservationInf.number = number;
                this.reservationInf.price = reservation.price
                this.reservationInf.start = reservation.start_time;
                this.reservationInf.end = reservation.end_time;
                this.reservationInf.client_name = reservation.order.client.name;
                this.reservationInf.phone = reservation.order.client.phone;
                this.reservationInf.status =reservation.order.status;
                axios.get('/admin/api/reservation/other', {params:{id:reservation.order_id}})
                    .then((response) => {
                        this.order = response.data;
                    })
            }
        },
        mounted() {

        }
    }
</script>
<style lang="scss">
.places-reserv-list {
    list-style-type: none;
    margin-left: 0;
    padding-left: 0;
    &__item {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }
    &__label {
        margin-bottom: 0;
        padding-right: 10px;
        width: 200px;
        max-width: 120px;
    }
    &__reserv-wrap {
        display: flex;
        flex-wrap: wrap;
    }
    &__reserv{
        cursor: pointer;
        font-weight: 400;
        padding: 5px;
        display: block;
        border: 1px solid #FFFFFF;
        box-sizing: border-box;
        border-radius: 5px;
        font-size: 16px;
        margin-right: 10px;
        &--selected {
            border-color: rgba(255, 255, 255, 0.5);
            background-color: rgba(255, 255, 255, 0.5);
        }
    }
}
.dashboard-places-list {
    list-style-type: none !important;
    margin-left: 0;
    padding-left: 0;
    margin-bottom: 20px;
    display: flex;
    flex-wrap: wrap;
    &__item {
        cursor: pointer;
        font-weight: 600;
        font-size: 17px;
        margin-right: 15px;
        color: rgba(255,255,255, 0.5);
        &--selected {
            display: none;
            margin-right: 0;
        }
    }

}
.reserve-inf {
    padding: 30px;
    background: #006672;
    border-radius: 15px;
    color: #fff;
    &__title {
        font-family: 'Metro', sans-serif;
        font-size: 26px;
        margin-bottom: 10px;
    }
    &__descr {
        font-size: 16px;
        margin-bottom: 25px;
    }
    &__selected {
        font-size: 26px;
        margin-bottom: 15px;
    }
    &__wrap {
        padding-left: 20px;
        flex:1;
    }
    &__btn {
        color: #fff;
        background: #dfb05c;
        box-shadow: 0 0 0 transparent;
        border: 0 solid transparent;
        border-radius: 6px;
        padding: 15px 30px;
        text-shadow: 0 0 0 transparent;
        width: 100%;
        font-size: 16px;
        &__wrap {
            margin-top: 20px;
            text-align: center;
        }
    }
}
.reserve-inf-list {
    list-style-type: none;
    padding-left: 0;
    margin-left: 0;
    &__item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #007583;
    }
    &__label {
        opacity: 50%;
    }
    &__value {
        color: #fff;
        font-size: 16px;
        font-weight: 600;
    }
}
.places-dashboard {
    margin-bottom: 20px;
    padding: 40px;
    background-color: #007583;
    border-radius: 10px;
    color: #fff;
    &__wrap {
        display: flex;

    }
    &__title {
        font-family: 'Metro', sans-serif;
        font-size: 17px;
        font-weight: normal;
        margin-bottom: 35px;
        text-align: center;
    }
}


</style>
