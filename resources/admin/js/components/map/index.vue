<template>
<section>
    <div style="color: #006672; margin-bottom: 20px; font-size: 17px; " v-if="reserveData.count > 0">
        Забронировано мест: <span style="font-weight: bold;">{{reserveData.count}}</span><br>
        На сумму: <span style="font-weight: bold;">{{reserveData.price}} ₽</span>
    </div>
    <first-floor
        :date="date"
        :duration="duration"
        :can-select="canSelect"
        :selected-places-arr="selectedPlacesArrFirst"
        :start-date="startDate"
        :end-date="endDate"
        @select-item="selectReservationItem"
        :time-title="reserveData.selectedDayString +  ' с ' + reserveData.startTime + ' до '  + reserveData.endTime"
    ></first-floor>

</section>
</template>
<script>
import FirstFloor from "./FirstFloor";
import SecondFloor from "./SecondFloor";
    export default {
        props: {
            selectedPlacesArrFirst: {
                type:Array,
                default: function (){
                    return [];
                }
            },
            selectedPlacesArrSecond:{
                type:Array,
                default: function (){
                    return [];
                }
            },
            selectedCabinsArr: {
                type:Array,
                default: function (){
                    return [];
                }
            },
            reserveData: {
                type:Object,
                required:true,
            },
            duration: {
                type:Number,
                default: 0,
            },
            date: {
                type:String,
                required:true
            },
            startDate:{
                type:String,
                required:true
            },
            endDate: {
              type:String,
              required: true
            },
            canSelect: {
                type:Boolean,
                default:false,
            }
        },
        components: {
            FirstFloor,
            SecondFloor,
        },
        data() {
            return {

            }
        },
        methods: {
            selectReservationItem(data) {
                this.$emit('select-item', data);
            }
        },
        mounted() {

        },
    }
</script>
<style lang="scss">
.reserve-map {
    width: 865px;
    max-width: 865px;
    min-width: 865px;
    position: relative;
    background-color: #C5C6C6;
    img {
        max-width: 100%;
    }
    &--second {
        background-color: transparent;
    }
    &__cabinet {
        position: absolute;
        z-index: 3;
        cursor: pointer;
        &--selected {
            &:after {
                content: "";
                position: absolute;
                top:0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,255,102,0.6);;
            }
        }
        svg {
            max-width: 100%;
            max-height: 100%;
        }
        &-name {
            display: block;
            position: absolute;
            font-size: 12px;
            font-weight: bold;
            &--1 {
                left: 50%;
                transform: translateX(-50%);
                top:50px;
            }
            &--2 {
                right: 10px;
                bottom:26px;
            }
            &--3 {
                left: 15px;
                bottom:26px;
            }
            &--4 {
                right: 20px;
                bottom:26px;
            }
            &--5 {
                left: 20px;
                bottom:26px;
            }
            &--6 {
                left: 15px;
                bottom:36px;
            }
            &--7 {
                left: 20px;
                bottom: 61px;
            }
        }
    }
    &__place {
        cursor: pointer;
        position: absolute;
        z-index: 2;
        width: 44px;
        height: 44px;
        svg {
            width: 44px;
            height: 44px;
        }
        &--selected {
            &:after {
                content: "";
                position: absolute;
                top:0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,255,102,0.6);;
            }
        }

        &-number {
            position: absolute;
            display: block;
            font-size: 13px;
            line-height: 13px;
            font-weight: bold;
            &--left {
                left: 6px;
                top:50%;
                transform: translateY(-50%);
            }
            &--right {
                right: 6px;
                top:50%;
                transform: translateY(-50%);
            }
            &--top {
                top:6px;
                left: 50%;
                transform: translateX(-50%);
            }
            &--down {
                bottom:6px;
                left: 50%;
                transform: translateX(-50%);
            }
        }

    }
    &__place-2 {
        cursor: pointer;
        position: absolute;
        z-index: 2;
        width: 35px;
        height: 35px;
        svg {
            width: 35px;
            height: 35px;
        }
        &--selected {
            &:after {
                content: "";
                position: absolute;
                top:0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,255,102,0.6)
            }
        }

        &-number {
            position: absolute;
            display: block;
            font-size: 12px;
            line-height: 12px;
            font-weight: bold;
            &--left {
                left: 6px;
                top:50%;
                transform: translateY(-50%);
            }
            &--right {
                right: 6px;
                top:50%;
                transform: translateY(-50%);
            }
            &--top {
                top:6px;
                left: 50%;
                transform: translateX(-50%);
            }
            &--down {
                bottom:6px;
                left: 50%;
                transform: translateX(-50%);
            }
        }
    }
}
.map-floor {
    width: 865px;
    max-width: 865px;
    min-width: 865px;
    &--second {
        margin-top: 40px;
    }
    &__title {
        font-family: 'Metro', sans-serif;
        text-align: center;
        font-weight: 400;
        font-size: 17px;
        color: #006672;
    }

}
.reserve-map {
    width: 865px;
    max-width: 865px;
    min-width: 865px;
    position: relative;
    background-color: #C5C6C6;
    &--second {
        background-color: transparent;
    }
    &__cabinet {
        position: absolute;
        z-index: 3;
        cursor: pointer;
        &--selected {
            &:after {
                content: "";
                position: absolute;
                top:0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,255,102,0.6);;
            }
        }
        svg {
            max-width: 100%;
            max-height: 100%;
        }
        &-name {
            display: block;
            position: absolute;
            font-size: 12px;
            font-weight: bold;
            &--1 {
                left: 50%;
                transform: translateX(-50%);
                top:50px;
            }
            &--2 {
                right: 10px;
                bottom:26px;
            }
            &--3 {
                left: 15px;
                bottom:26px;
            }
            &--4 {
                right: 20px;
                bottom:26px;
            }
            &--5 {
                left: 20px;
                bottom:26px;
            }
            &--6 {
                left: 15px;
                bottom:36px;
            }
            &--7 {
                left: 20px;
                bottom: 61px;
            }
        }
    }
    &__place {
        cursor: pointer;
        position: absolute;
        z-index: 2;
        svg {
            width: 44px;
            height: 44px;
        }
        &--selected {
            &:after {
                content: "";
                position: absolute;
                top:0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,255,102,0.6);;
            }
        }

        &-number {
            position: absolute;
            display: block;
            font-size: 13px;
            line-height: 13px;
            font-weight: bold;
            &--left {
                left: 6px;
                top:50%;
                transform: translateY(-50%);
            }
            &--right {
                right: 6px;
                top:50%;
                transform: translateY(-50%);
            }
            &--top {
                top:6px;
                left: 50%;
                transform: translateX(-50%);
            }
            &--down {
                bottom:6px;
                left: 50%;
                transform: translateX(-50%);
            }
        }

    }
    &__place-2 {
        cursor: pointer;
        position: absolute;
        z-index: 2;
        svg {
            width: 35px;
            height: 35px;
        }
        &--selected {
            &:after {
                content: "";
                position: absolute;
                top:0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,255,102,0.6)
            }
        }

        &-number {
            position: absolute;
            display: block;
            font-size: 12px;
            line-height: 12px;
            font-weight: bold;
            &--left {
                left: 6px;
                top:50%;
                transform: translateY(-50%);
            }
            &--right {
                right: 6px;
                top:50%;
                transform: translateY(-50%);
            }
            &--top {
                top:6px;
                left: 50%;
                transform: translateX(-50%);
            }
            &--down {
                bottom:6px;
                left: 50%;
                transform: translateX(-50%);
            }
        }
    }
}
.map-floor {
    width: 865px;
    max-width: 865px;
    min-width: 865px;
    &--second {
        margin-top: 40px;
    }
    &__title {
        font-family: 'Metro', sans-serif;
        text-align: center;
        font-weight: 400;
        font-size: 17px;
        color: #006672;
    }

}

</style>


