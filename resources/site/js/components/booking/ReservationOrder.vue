<template>
    <div   v-loading="loading">
        <h4 class="reservation-order__title">Оплата</h4>
        <p class="reservation-order__text">
            Информация о бронировании
        </p>
        <ul class="reservation-order-list">
            <li class="reservation-order-list__item">
                <label class="reservation-order-list__label">
                    Мест
                </label>
                <span class="reservation-order-list__value">
                                {{reserveData.count}}
                            </span>
            </li>
            <li class="reservation-order-list__item">
                <label class="reservation-order-list__label">
                    Дата:
                </label>
                <span class="reservation-order-list__value">
                                {{reserveData.selectedDayString}}
                            </span>
            </li>
            <li class="reservation-order-list__item">
                <label class="reservation-order-list__label">
                    Время:
                </label>
                <span class="reservation-order-list__value">
                            <span v-if="reserveData.startTime">c</span>
                            {{reserveData.startTime}}
                            <span v-if="reserveData.endTime">
                                      по
                            </span>
                              {{reserveData.endTime}}
                            </span>
            </li>
            <li class="reservation-order-list__item">
                <label class="reservation-order-list__label">
                    Сумма:
                </label>
                <span class="reservation-order-list__value">
                                {{reserveData.price}} ₽
                </span>
            </li>
        </ul>
        <p class="reservation-order__text">
            Контактные данные
        </p>
        <form>
            <div class="reservation-form__group">
                <div class="alert alert-danger" role="alert"
                     v-if="errors.has('reservation')"
                     v-text="errors.get('reservation')"
                     style="word-wrap: break-word; word-break: normal;"></div>
                <label class="reservation-form__label">
                    Имя
                </label>
                <input name="name" type="text" v-model="form.name"
                       :class="{'is-invalid': errors.has('client.name')}"
                       class="form-control"  placeholder="Ваше имя">
                <div class="invalid-feedback" v-text="errors.get('client.name')"></div>
            </div>
            <div class="reservation-form__group">
                <label class="reservation-form__label">
                   Телефон
                </label>
                <input autocomplete="tel" name="phone" type="tel"
                       class="form-control"
                       v-model="form.phone"
                       :class="{'is-invalid': errors.has('client.phone')}"
                       placeholder="Телефон">
                <div class="invalid-feedback" v-text="errors.get('client.phone')"></div>
            </div>
           <button class="reservation-form__btn" @click.prevent="submitForm">
               Оплатить
           </button>
        </form>
    </div>
</template>
<script>
import { Errors } from "../../services/errors"
export default {
    props: {
        reserveData: {
            required:true,
            type:Object,
        },
        reservations: {
            required: true,
            type:Array,
        },
    },
    data() {
        return {
            loading:false,
            form: {
                phone: '',
                name: ''
            },
            errors: new Errors(),
        }
    },
    methods: {
        submitForm() {
          /*  this.loading = true;
            axios.post(this.$root.api_url + '/api/reservation-order', {client:this.form, reservation:this.reserveData, reservations:this.reservations})
            .then((response) => {
                console.log(response.data);
                window.location.href=response.data.formUrl;
                this.loading.false;
            })
            .catch((error) => {
                this.loading = false;
                this.errors.record(error.response.data.errors);
            })*/
        }
    }
}
</script>
<style lang="scss">
.reservation-order {
    &__title {
        font-family: 'Metro', sans-serif;
        text-align: center;
        margin-bottom: 20px;
        color: #006672;
        font-size: 26px;
    }
    &__text {
        text-align: center;
        color: #898989;
        font-size: 15px;
        margin-bottom: 15px;
    }
    .el-dialog {
        background: #f1f1f1;
        min-width: 320px;
        max-width: 414px;
        &__body {
            padding-left: 60px;
            padding-right: 60px;
        }
    }
}
.reservation-order-list {
    list-style-type: none;
    margin-left: 0;
    padding-left: 0;
    color: #007583;
    margin-bottom: 25px;
    &__item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #E2E2E2;
    }
    &__label {

    }
    &__value {
        font-weight: 600;
    }
}
.form-control {
    display: block;
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    border-radius: 0.25rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
.form-control {
    background-color: #E2E2E2;
    height: 60px;
    &::placeholder {
        color: #898989;
        font-size: 17px;
    }
    &:focus {
        outline: none;
        box-shadow: none !important;

    }
}
.reservation-form {
    &__label {
        display: block;
        color: #006672;
        font-size: 17px;
        margin-bottom: 10px;
    }
    &__group {
        margin-bottom: 20px;
    }
    &__btn {
        margin-top: 35px;
        color: #fff;
        background: #3097A1;
        box-shadow: 0 0 0 transparent;
        border: 0 solid transparent;
        border-radius: 6px;
        padding: 15px 30px;
        text-shadow: 0 0 0 transparent;
        width: 100%;
        font-size: 16px;
        cursor:pointer;
    }
}

</style>
