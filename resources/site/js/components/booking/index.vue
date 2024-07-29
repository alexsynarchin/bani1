<template>
    <div class="booking__wrap">
        <section class="booking">
            <figure class="booking__logo">
                <img src="/assets/images/logo.svg">
            </figure>
            <h3 class="booking__title">Онлайн-бронирование</h3>

            <section class="booking-step">
                <div class="booking-step__num">
                    1
                </div>
                <div class="booking-step__body">
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
            </div>

            </section>
            <section class="booking-step">
                <div class="booking-step__num">
                    2
                </div>
                <div class="booking-step__body">
                    <button class="booking-link" @click.prevent="openMapModal">
                        Выберите любые свободные места
                    </button>
                </div>
            </section>
            <section class="booking-step">
                <div class="booking-step__num">
                   3
                </div>
                <div class="booking-step__body">
                    <button class="booking-link" @click.prevent="openContactModal">
                        Введите контактные данные
                    </button>
                </div>
            </section>
            <section class="booking-step">
                <div class="booking-step__num booking-step__num--last">
                    4
                </div>
                <div class="booking-step__body">
                    <button class="booking-link" @click.prevent="orderReservation">
                        Произведите оплату
                    </button>
                </div>
            </section>
            <div class="text-center">



            </div>

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

                <reservation-order
                    :client="client"
                    :reserve-data="reserveData"
                    :reservations="reservations"
                ></reservation-order>
            </el-dialog>
        </section>
        <reserved-places
            ref="reserved_places">
        </reserved-places>
        <el-dialog
            :visible.sync="contactModalVisible"
            class="contact-reserve-modal"
        >
        <contact-reserve
            :client="client"
            @enter-contact="enterContactData"
        ></contact-reserve>
        </el-dialog>
    </div>

</template>
<script>
    import calendar from '../calendar/index'
    import ReserveMap from "../map/index"
    import ReservationInformation from "./ReservationInformation";
    import ReservationOrder from "./ReservationOrder";
    import ReservedPlaces from "../reserved-places/index.vue";
    import ContactReserve from "../contact-reserve/index.vue";
    import dayjs from "dayjs";
    export default {
     components: {
         ReservationInformation,
         'calendar':calendar,
        'ReserveMap':ReserveMap,
         ReservationOrder,
         ReservedPlaces,
         ContactReserve,
     },
        data() {
         return {
             client: {},
             selectedPlacesArrFirst: [],
             selectedPlacesArrSecond: [],
             selectedCabinsArr: [],
             duration:0,
             resultVisible:false,
             contactModalVisible:false,
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
         showReservedPlaces() {
             this.$refs.reserved_places.showPlaces(this.reserveData)
         },
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

             if(this.client.name && this.client.phone && this.client.pers && this.client.offerta) {
                 this.orderModalVisible = true;
             }
             else if(this.reserveData.count > 0)  {
                 this.$notify({
                     title: 'Введите контактные данные',
                     message: '',
                     type: 'warning'
                 });
             }
             else if(this.reserveData.duration > 0) {
                 this.$notify({
                     title: 'Выберите места',
                     message: '',
                     type: 'warning'
                 });
             } else {
                 this.$notify({
                     title: 'Выберите дату и время',
                     message: '',
                     type: 'warning'
                 });
             }
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
        enterContactData(data) {
             this.client = data;
             this.contactModalVisible = false;
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
            openContactModal() {
                if (this.reserveData.count > 0) {
                    this.contactModalVisible=true
                } else {
                    this.$notify({
                        title: 'Выберите Места',
                        message: '',
                        type: 'warning'
                    });
                }

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
.reserve-inf__btn {
    cursor: pointer;
}
    .booking {
        &__logo {
            margin-bottom: 67px;
        }
        &__wrap {
            display: flex;
            justify-content: center;
            text-align: center;
        }
        &__title {
            font-weight: 400;
            margin-bottom: 60px;
            font-size: 40px;
            text-align: center;
            text-transform: uppercase;
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
    .contact-reserve-modal {
        .el-dialog {
            max-width: 550px;
            min-width: 380px;
            padding-left: 60px;
            padding-right: 60px;
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
            max-width: 550px;
            min-width: 380px;
            padding-left: 60px;
            padding-right: 60px;
        }
    }
    .booking-link {
        font-size: 20px;

        padding: 0;
        border: none;
        color: #777777;
        text-decoration: underline;
       text-transform: none;
        /* отображаем курсор в виде руки при наведении; некоторые
        считают, что необходимо оставлять стрелочный вид для кнопок */
        cursor: pointer;
    }
    .booking-step {
        font-family: "Jost", sans-serif;
        font-weight: 400;
        font-size: 20px;
        color: #777777;
        display: flex;
        align-items: center;
        padding-bottom: 54px;
        column-gap: 39px;
        &__num {
            font-weight: 300;
            font-size: 40px;
            line-height: 1;
            width: 51px;
            height: 51px;
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            color: #CAA769;
            &:before {
                content:"";
                width: 2px;
                height: 54px;
                position: absolute;
                top: 100%;
                left: 25px;
                background-color: #CAA769;
            }
            &--last {
                &:before {
                    content: none;
                }
            }

        }
        &__body {
            flex: 1;
            text-align: left;
        }

    }


</style>

