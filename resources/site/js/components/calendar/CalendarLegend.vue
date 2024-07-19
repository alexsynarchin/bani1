<template>
    <ul class="calendar-legend">
        <li class="calendar-legend__item" v-for="legend in legends">
            <span class="calendar-legend__marker" :style="{backgroundColor:legend.color}"></span>
            <span class="calendar-legend__text" >
                {{legend.text}}
            </span>
        </li>
    </ul>
</template>
<script>
    export default {
        data() {
            return {
                legends:[
                    {
                        text: "Женский день",
                        color: 'pink'
                    }
                ]
            }
        }
    }
</script>
<style lang="scss" scoped>
    .calendar-legend {
        list-style-type: none;
        margin-left: 0;
        padding-left: 0;
        margin-top: 15px;
        margin-bottom: 15px;
       &__item {
           display: flex;
           justify-content: center;
           align-items: center;
           margin-bottom: 11px;
       }
        &__marker {
            margin-right: 5px;
            display: block;
            width: 16px;
            height: 16px;
            border-radius: 2px;
        }
        &__text {
            font-size: 17px;
        }
    }
</style>
