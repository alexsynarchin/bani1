<template>
    <div class="places-dashboard__wrap">
        <section class="places-dashboard">
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
                                  @click.prevent="selectReservation(reservation, place.number, 'place', index)"
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
                    </section>
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

                    <button v-if="user.id === 2" class="reserve-inf__btn mb-3" @click.prevent="cancelOrder(reservation.order_id)">
                        Отменить весь заказ
                    </button>

                </div>
            </section>
    </div>
</template>
<script>
import Navigation from "./components/navigation";
import dayjs from "dayjs";
import weekday from "dayjs/plugin/weekday";
require('dayjs/locale/ru');

    export default {
        props:{
            user: {
                type: Object,
            }
        },
        components: {
            Navigation,
        },
        data() {
            return {
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
            }
        },
        methods: {
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
                axios.get('/admin/api/reservations', {params:{date:date}})
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



    }
</script>
