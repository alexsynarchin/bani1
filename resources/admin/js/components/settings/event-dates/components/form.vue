<template>
    <el-form :model="form" label-position="top">
        <el-form-item class="" prop="date" label="Дата праздника" :error="errors.get('date')">
            <el-date-picker
                style="min-width: 220px"
                v-model="form.date"
                value-format="yyyy-M-d"
                type="date"
                placeholder="Выбрать дату">
            </el-date-picker>
        </el-form-item>
        <el-form-item prop="start_time"  label="Стартовое время" :error="errors.get('start_time')">
            <el-time-select
                style="min-width: 220px"
                v-model="form.start_time"
                :picker-options="{
                start: '10:00',
                step: '00:30',
                end: '21:00'
  }"
                placeholder="Выбрать время">
            </el-time-select>
        </el-form-item>
        <el-form-item label="Сделать нерабочим днем">
            <el-switch v-model="form.day_off"></el-switch>
        </el-form-item>
        <div class="d-flex justify-content-center">
            <el-button icon="el-icon-plus" type="success" style="margin-right: 1rem" @click.prevent="submitForm('promoForm')">Сохранить</el-button>
            <el-button @click="closeModal">Отмена</el-button>
        </div>
    </el-form>
</template>
<script>
    import {Errors} from "../../../../services/errors";
    export default {
        props:{
            form: {
                type:Object
            },
            action_type: {
                type:String
            },
            action_url: {
                type:String
            },
        },
        data() {
            return {
                errors: new Errors(),
            }
        },
        methods: {
            closeModal() {
                this.$emit('close-modal');
            },
            submitForm(form) {
                axios({
                    method: this.action_type,
                    url: this.action_url,
                    data: this.form
                })
                    .then((response) => {
                        this.$emit('submit-form', response.data);
                    })
                    .catch((error) => {
                        this.errors.record(error.response.data.errors);
                    });
            },
        },
    }
</script>
