<template>
    <div class="booking__wrap">
        {{order_id}}
        <section class="booking">
            <h3 class="booking__title">Забронировать место test</h3>
            <p class="booking__description">
                Дорогие гости, для бронирования вам необходимо <br>
                выбрать дату, время, место и произвести оплату
            </p>
            <h4 class="booking-step text-center">
                1 шаг
            </h4>
            <div class="text-center">
                <div v-if="firstStep" @click.prevent="calendarVisible=true" style="cursor: pointer">
            <span class="booking-first__value">
                {{reserveData.selectedDayString}}
            </span>
                    <span class="booking-first__label">
                с
            </span>
                    <span class="booking-first__value">
                {{reserveData.startTime}}
            </span>
                    <span class="booking-first__label">
                до
            </span>
                    <span class="booking-first__value">
                {{reserveData.endTime}}
            </span>
                </div>
                <button class="booking-link" v-else @click.prevent="calendarVisible=true">
                    Выберите дату и время
                </button>
                <div style="margin-top: 30px; margin-bottom: 30px;">
                    <h4 class="booking-step">
                        2 шаг
                    </h4>
                    <button class="booking-link" @click.prevent="openMapModal">
                        Выберите любые свободные места
                    </button>
                </div>
            </div>
            <reservation-information
                v-if="this.reserveData.count > 0"
                :reserve-data="reserveData"
                @order-reservation="orderReservation"
            ></reservation-information>
            <el-dialog
                @closed="resultClosed"
                @beffore-close="handleResultClose"
                :visible.sync="resultVisible"
                class="result-modal"
            >
                <div slot="title"></div>
                <p style="text-align: center">
                    {{resultText}}
                </p>
            </el-dialog>
            <el-dialog

                :visible.sync="calendarVisible"
                class="calendar-modal"
            >
                <div slot="title"></div>
                <calendar
                    @select-day-and-time="selectReserveTime"
                    v-if="calendarVisible"
                ></calendar>
            </el-dialog>
            <el-dialog
                :visible.sync="mapVisible"
                class="map-modal"
            >
                <reserve-map ref="reserve_map"
                             @select-item = "selectReserveItem"
                             :selected-cabins-arr="selectedCabinsArr"
                             :selected-places-arr-first="selectedPlacesArrFirst"
                             :selected-places-arr-second="selectedPlacesArrSecond"
                             :reserve-data="reserveData"
                             :duration="duration"
                             :can-select="canSelectMap"
                             :date="reserveData.selectedDay"
                             :start-date="reserveData.selectedDay + ' ' +  reserveData.startTime"
                             :end-date="endDate"
                             v-if="mapVisible && reserveData.startTime && reserveData.endTime && reserveData.selectedDay && showMap"
                ></reserve-map>
                <div class="reserve-inf__btn-wrap mt-3 pb-3" style="max-width: 300px; margin-right: auto; margin-left: auto">
                    <button class="reserve-inf__btn" @click.prevent="mapVisible = false">
                        Продолжить
                    </button>
                </div>
            </el-dialog>


            <el-dialog
                :visible.sync="orderModalVisible"
                class="reservation-order"
            >
                <div slot="title"> </div>
                <reservation-order
                    :reserve-data="reserveData"
                    :reservations="reservations"
                ></reservation-order>
            </el-dialog>
        </section>
    </div>

</template>
<script>
    import calendar from '../calendar/index'
    import ReserveMap from "../map/index"
    import ReservationInformation from "./ReservationInformation";
    import ReservationOrder from "./ReservationOrder";
    import dayjs from "dayjs";
    export default {
     components: {
         ReservationInformation,
         'calendar':calendar,
        'ReserveMap':ReserveMap,
         ReservationOrder
     },
        data() {
         return {
             selectedPlacesArrFirst: [],
             selectedPlacesArrSecond: [],
             selectedCabinsArr: [],
             duration:0,
             resultVisible:false,
             resultText:'',
             order_id:null,
             canSelectMap:false,
             mapVisible:false,
             calendarVisible:false,
             firstStep:false,
            orderModalVisible:false,
             showMap:true,
             reserveData: {
                 selectedDay:null,
                 selectedDayString:'',
                 startTime:'',
                 endTime:'',
                 duration: 0,
                 price: 0,
                 count:0,
             },
             reservations: [],
         }
        },
        computed: {
            endDate: function () {
                let endDay = this.reserveData.selectedDay;
                let time = this.reserveData.endTime;
                if(this.reserveData.endTime === '24:00') {
                    time = '23:59';
                }
                return endDay + ' ' +  time;
            }
        },
        methods: {
         openMapModal() {
             if (this.canSelectMap) {
                 this.mapVisible = true;
             } else {
                 this.$notify({
                     title: 'Выберите дату и время',
                     message: '',
                     type: 'warning'
                 });
             }
         },
         orderReservation() {
            this.orderModalVisible = true;
         },
         selectReserveItem(data) {
             console.log(data);
             var add = true;
             for(var i = 0; i < this.reservations.length; i++) {
                 if((this.reservations[i].type ===  data.type) && (this.reservations[i].id === data.id) ) {
                     this.reservations.splice(i, 1);
                     this.reserveData.price = this.reserveData.price - data.total_price;
                     add = false;
                     break;
                 }
             }
             if(add) {
                 this.reservations.push(data);
                 this.reserveData.price = this.reserveData.price + data.total_price;
             }
             this.reserveData.count = this.reservations.length;
             this.$notify({
                 title: 'Забронировано:',
                 message: 'Мест - ' + this.reserveData.count + ' на сумму ' + this.reserveData.price + ' ₽' ,
                 type: 'success'
             });
         },
         handleCalendarClose() {

         },
         selectReserveTime(data) {
             this.reservations = [];
             this.reserveData = {
                 selectedDay:null,
                 selectedDayString:'',
                 startTime:'',
                 endTime:'',
                 duration: 0,
                 price: 0,
                 count:0,
             };
             this.showMap = false;
             this.showMap = true;
            this.reserveData.startTime = data.startTime;
            this.reserveData.endTime = data.endTime;
            this.reserveData.selectedDayString = data.selectedDayString;
            this.reserveData.selectedDay = data.selectedDay;
            this.firstStep = true;
            this.calendarVisible = false;
            this.canSelectMap = true;
             let startHours = new Date("01/01/2018 " + data.startTime).getHours();
             let startMinutes = new Date("01/01/2018 " + data.startTime).getMinutes();
             let endHours = 0;
             let endMinutes=0;
             if(data.endTime === '24:00') {
                 endHours = 24;
                 endMinutes = 0;
             } else {
                endHours = new Date("01/01/2018 " + data.endTime).getHours();
                 endMinutes = new Date("01/01/2018 " + data.endTime).getMinutes();
             }

             this.reserveData.duration = (endHours * 60 + endMinutes - startHours * 60 - startMinutes) / 60;
            this.duration = this.reserveData.duration;
            },
            getPaymentResult(form) {
             axios.post(this.$root.api_url + '/api/payment-result', form)
                .then((response) => {
                    console.log(response.data);
                    this.resultVisible = true;
                   this.resultText = response.data;
                })
            },
            handleResultClose() {
             this.resultClosed();
            },
            resultClosed() {
               window.location.href=('https://baniufa.ru/reservation.html');
                //window.location.href=('http://bani.loc');
            },
            getOrderInf(order_id) {
                axios.post(this.$root.api_url + '/api/reservation-order-inf', {order_id:order_id})
                .then((response) => {
                    console.log(response.data);
                })
            },
            orderTest() {
            axios.post('/api/order-test')
                .then((response)=> {

                })
            }
        },
        created() {
            let uri = window.location.search.substring(1);
            let params = new URLSearchParams(uri);
            this.order_id = params.get("orderId");
        },
        mounted() {
         if(this.order_id) {
            // this.getOrderInf(this.order_id);
             this.getPaymentResult({order_id:this.order_id, success:true})
         }

        }
    }
</script>
<style lang="scss">
    .booking {
        &__wrap {
            display: flex;
            justify-content: center;
            text-align: center;
        }
        &__title {
            margin-bottom: 15px;
            font-size: 26px;
            text-align: center;
        }
        &__description {
            margin-bottom: 25px;
            font-size: 17px;
            text-align: center;
        }
    }
    .booking-first {
        font-size: 17px;
        &__value {
            font-weight: 600;
            color: #006672;
        }
    }
    .calendar-modal {
        .el-dialog {
            min-width: 380px;
            max-width: 520px;
        }
    }
    .map-modal {
        .el-dialog__body {
            padding: 0;
        }
        .el-dialog {
            min-width: 1039px;
            max-width: 1039px;
        }
    }
    .result-modal {
        .el-dialog {
            min-width: 380px;
            max-width: 460px;
        }
    }
    .booking-link {
        font-size: 17px;
        font-weight: 600;
        padding: 0;
        border: none;
        color: #006672;
        text-decoration: underline;
        background-color: transparent;
        /* отображаем курсор в виде руки при наведении; некоторые
        считают, что необходимо оставлять стрелочный вид для кнопок */
        cursor: pointer;
    }
    .booking-step {
        margin-bottom: 15px;
        font-size: 26px;
        color: #006672;
        font-family: 'Metro', sans-serif;
    }


</style>

