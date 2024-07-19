<template>
    <div class="reserve-inf__wrap">
        <div class="reserve-inf">
            <h4 class="reserve-inf__title">
                3 шаг
            </h4>
            <p class="reserve-inf__descr">
                Произведите оплату <br> выбранных вами мест
            </p>
            <p class="reserve-inf__selected">
                Выбрано:
            </p>
            <ul class="reserve-inf-list">
                <li class="reserve-inf-list__item">
                    <label class="reserve-inf-list__label">
                        Мест
                    </label>
                    <span class="reserve-inf-list__value">
                                {{reserveData.count}}
                            </span>
                </li>
                <li class="reserve-inf-list__item">
                    <label class="reserve-inf-list__label">
                        Дата:
                    </label>
                    <span class="reserve-inf-list__value">
                                {{reserveData.selectedDayString}}
                            </span>
                </li>
                <li class="reserve-inf-list__item">
                    <label class="reserve-inf-list__label">
                        Время:
                    </label>
                    <span class="reserve-inf-list__value">
                            <span v-if="reserveData.startTime">c</span>
                            {{reserveData.startTime}}
                            <span v-if="reserveData.endTime">
                                      по
                            </span>
                              {{reserveData.endTime}}
                            </span>
                </li>
                <li class="reserve-inf-list__item">
                    <label class="reserve-inf-list__label">
                        Сумма:
                    </label>
                    <span class="reserve-inf-list__value">
                                {{reserveData.price}} ₽
                            </span>
                </li>
            </ul>
            <div class="reserve-inf__btn-wrap">
                <button class="reserve-inf__btn" @click.prevent="orderReservation">
                    Забронировать
                </button>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            reserveData: {
                required:true,
                type:Object,
            }
        },
        methods: {
            orderReservation() {
                if(this.reserveData.count > 0) {
                    this.$emit('order-reservation');
                } else if(this.reserveData.duration > 0) {
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

            }
        }
    }
</script>
<style lang="scss">
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
        margin-bottom: 20px;
        color: #fff;
        background: #3097A1;
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
    margin-bottom: 25px;
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

</style>
