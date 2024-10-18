<template>
    <section class="reserve-contact">
        <h4 class="reserve-contact__title">Контактные данные</h4>

        <form>
            <div class="reservation-form__group">
                <div class="alert alert-danger" role="alert"
                     v-if="errors.has('reservation')"
                     v-text="errors.get('reservation')"
                     style="word-wrap: break-word; word-break: normal;"></div>

                <input name="name" type="text" v-model="form.name"
                       :class="{'is-invalid': errors.has('name')}"
                       class="form-control"  placeholder="Ваше имя">
                <div class="invalid-feedback" v-text="errors.get('name')"></div>
            </div>
            <div class="reservation-form__group">

                <input autocomplete="tel" name="phone" type="tel"
                       class="form-control"
                       v-model="form.phone"
                       :class="{'is-invalid': errors.has('phone')}"
                       placeholder="+7 (999)-999-99-99">
                <div class="invalid-feedback" v-text="errors.get('phone')"></div>
            </div>
            <label class="form-checkbox">
                        <span class="modal__conf-text">
                           Согласие на обработку персональных данных
                        </span>
                <input name="conf_policy" v-model="form.pers" type="checkbox" class="form-checkbox__input"  :class="{'is-invalid': errors.has('pers')}">
                <span class="form-checkbox__checkmark"></span>  <br>
                <div class="invalid-feedback invalid-feedback--conf_agree" v-text="errors.get('pers')"></div>
            </label>
            <label class="form-checkbox">
                        <span class="modal__conf-text">
                          Принимаю публичную оферту
                        </span>
                <input name="conf_policy" v-model="form.offerta" type="checkbox" class="form-checkbox__input"  :class="{'is-invalid': errors.has('offerta')}">
                <span class="form-checkbox__checkmark"></span>  <br>
                <div class="invalid-feedback invalid-feedback--conf_agree" v-text="errors.get('offerta')"></div>
            </label>
            <div class="text-center">
                <button class="reservation-form__btn" @click.prevent="submitForm">
                    Дальше
                </button>
            </div>

        </form>
    </section>
</template>
<script>
    import {Errors} from "../../services/errors";

    export default {
        props: {
            client: {
                required:true,
                type:Object,
            }
        },
        data() {
            return {
                form: {
                    phone: '',
                    name: '',
                    pers:false,
                    offerta:false,
                },

                errors: new Errors(),
            }
        },
        methods: {
            submitForm() {
                axios.post('/api/reservation/check-contact', this.form)
                    .then((response) => {
                        this.$emit('enter-contact', this.form)
                    })
                    .catch((error) => {
                        this.errors.record(error.response.data.errors);
                    })

            },
        },
        mounted() {
            this.form = this.client;
        }
    }
</script>
<style lang="scss">
.modal__conf-text {
    color: #8D8D8D;
    font-size: 16px;
    font-family: "Jost", sans-serif;
}
    .reserve-contact {
        text-align: left;
        &__title {
            color: #2E2E2E;
            font-family: "Forum", serif;
            font-size: 40px;
            font-weight: 400;
            text-transform: uppercase;
            margin-bottom: 55px;
        }
    }
    .form-checkbox {
        display: block;
        margin-bottom: 10px;
        $form-checkbox: &;
        position: relative;
        font-weight: 400;
        padding-left: 36px;
        &--big {
            font-size: 1rem;
            padding-left: 50px;
        }

        a {
            color: #000000;
            text-decoration: underline;
            &:hover, &:focus{
                color:  #CAA769;
                text-decoration: none;
            }
        }
        &__input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
            &:checked ~ #{$form-checkbox}__checkmark:after {
                display: block;
            }
            &:checked ~ #{$form-checkbox}__checkmark {
                background-color: #CAA769;
            }
        }
        &__checkmark {
            cursor: pointer;
            position: absolute;
            top: 0;
            left: 0;
            height: 24px;
            width: 24px;
            border: 1px solid #CAA769;
            &:after {
                content: "";
                position: absolute;
                display: none;
                left: 7px;
                top: 2px;
                width: 8px;
                height: 15px;
                border: solid #ffffff;
                border-width: 0 2px 2px 0;
                -webkit-transform: rotate(45deg);
                -ms-transform: rotate(45deg);
                transform: rotate(45deg);
            }
            &--big {
                height:40px;
                width: 40px;
                border: solid 1px #d1d1d1;
                &:after {
                    left: 12px;
                    width: 18px;
                    height: 25px;
                    border-width: 0 6px 6px 0;
                }
            }
            &--white {
                border-color: #fff;
                background-color: #fff;
            }
        }

    }

</style>
