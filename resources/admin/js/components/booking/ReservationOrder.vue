<template>
    <div   v-loading="loading">
        <h4 class="reservation-order__title">Бронирование</h4>
        <ul class="reservation-order-list">
            <li class="reservation-order-list__item">

                <span class="reservation-order-list__value">
                                {{client.name}}
                            </span>
            </li>
            <li class="reservation-order-list__item">
                <span class="reservation-order-list__value">
                                {{client.phone}}
                            </span>
            </li>
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

        </ul>
        <div class="reservation-form__group">
            <label class="reservation-form__label">
                Тип бронирования
            </label>
            <select class="form-select form-control" name="type"  v-model="form.type"  :class="{'is-invalid': errors.has('client.type')}">
                <option :value="item.value" v-for="item in types">{{item.label}}</option>
            </select>
            <div class="invalid-feedback" v-text="errors.get('client.type')"></div>
        </div>
        <div class="reservation-order__price">
            СУММА  : {{reserveData.price}} ₽
        </div>

        <button class="reservation-form__btn" @click.prevent="submitForm">
           Забронировать
        </button>
    </div>
</template>
<script>
import { Errors } from "../../services/errors"
export default {
    props: {
        client:{
            required:true,
            type:Object,
        },
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
            types: [
                {
                    label:'Безнал',
                    value:"non-cash"
                },
                {
                    label:'Взаимозачет',
                    value:"offsetting"
                },
                {
                    label:'Перенос',
                    value:"transfer"
                },
                {
                    label:'Подарочные карты',
                    value:"sert"
                },
                {
                    label:'Учредитель',
                    value:"founder"
                },
                {
                    label:'Пригласительный',
                    value:'invitation'
                },
            ],
            loading:false,
            form: {
                phone: '',
                name: '',
                type: ""
            },
            errors: new Errors(),
        }
    },
    methods: {
        submitForm() {
             let client = this.client;
             client.type = this.form.type;
            axios.post( '/admin/api/reservation/order', {client:client,
                reservation:this.reserveData, reservations:this.reservations})
                .then((response) => {
                    this.$notify({
                        title: 'Места забронированы',
                        type: 'success'
                    });
                    this.$emit('close-reserve-modal');
                })
                .catch((error) => {
                    this.errors.record(error.response.data.errors);
                })
        }
    }
}
</script>
<style lang="scss">
.reservation-order {
    &__price {
        font-family: "Jost", sans-serif;
        font-weight: 400;
        font-size: 20px;
        text-transform: uppercase;
        margin-bottom: 40px;
    }
    &__title {
        font-family: 'Forum', sans-serif;
        text-align: center;
        margin-bottom: 35px;
        color: #2E2E2E;
        font-size: 40px;
        text-transform: uppercase;
    }
    &__text {
        text-align: center;
        color: #898989;
        font-size: 15px;
        margin-bottom: 15px;
    }
    .el-dialog {
        background: #f1f1f1;
        max-width: 550px;
        min-width: 380px;

        &__body {

        }
    }
}
.reservation-order-list {
    list-style-type: none;
    margin-left: 0;
    padding-left: 0;
    color: rgba(48,48,48,0.55);
    font-family: "Jost", sans-serif;
    font-size: 16px;
    text-transform: uppercase;
    margin-bottom: 45px;
    &__item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 24px 28px;
        border-radius: 10px;
        border: 1px solid #CAA769;
        margin-bottom: 10px;
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
    font-size: 16px;
    font-family: "Jost", sans-serif;
    text-transform: uppercase;
    font-weight: 400;
    line-height: 1.5;
    color: rgba(48,48,48,0.55);
    background-color: transparent;
    background-clip: padding-box;
    border: 1px solid #CAA769;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    border-radius: 10px;

}
.form-control {

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
        width: 100%;
        margin-top: 35px;
        color: #fff;
        background: #CAA769;
        box-shadow: 0 0 0 transparent;
        border: 0 solid transparent;
        padding: 14px 25px;
        text-shadow: 0 0 0 transparent;
        font-family: "Jost", sans-serif;
        font-weight: 500;
        font-size: 16px;
        border-radius: 10px;
        text-transform: uppercase;
        cursor:pointer;
    }
}

</style>
