<template>
    <event-form
        :form="form"
        :action_type="action_type"
        :action_url="action_url"
        @close-modal="closeModal"
        @submit-form="submitForm">
    </event-form>
</template>
<script>
import EventForm from './components/form'
    export default {
        components: {
            EventForm,
        },
        data() {
            return {
                action_type:'post',
                action_url: '/admin/api/event-dates',
                form: {
                    date:null,
                    start_time:"",
                    day_off:false,
                },
            }
        },
        methods: {
            closeModal() {
                this.$emit('close-modal');
            },
            submitForm(event_date) {
                this.$emit('create-event-date', event_date);
            },
        },
    }
</script>
