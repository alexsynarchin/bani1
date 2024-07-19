<template>
<div class="calendar-time__wrap">
<section class="calendar-time">
        <div class="calendar-time__item">
            <label class="calendar-time__label">
                Приход
            </label>
            <el-time-select
                :editable="false"
                placeholder="-- : --"
                :clearable="false"
                v-model="startTime"
                style="margin-right: 10px"
                @change="startTimeIsSelected"
                :picker-options="{
                  start: minStartTime,
                  step: '00:30',
                  end: '22:00'
}">
            </el-time-select>
        </div>
        <div class="calendar-time__item">
            <label class="calendar-time__label">
                Уход
            </label>
            <el-time-select
                :editable="false"
                placeholder="-- : --"
                :disabled="endTimeDisabled"
                @change="timeIsSelected"
                v-model="endTime"
                :picker-options="{
                  start: minEndTime,
                  step: '00:30',
                  end:maxEndTime,


}">
            </el-time-select>
        </div>
</section>
</div>
</template>
<script>
export default {
    props: {
        minStartTime:{
            type:String,
            default: '17:00'
        },
        maxEndTime: {
            type:String,
            default: '24:00'
        }
    },
    data() {
        return {

            startTime: '',
            endTime: '',
            endTimeDisabled:true,
        };
    },

    computed: {
        minEndTime() {
            let startHours = new Date("01/01/2018 " + this.startTime).getHours();
            let startMinutes = new Date("01/01/2018 " + this.startTime).getMinutes();
            let minTime = startHours * 60 + startMinutes + 120;
            let minHours = parseInt(minTime / 60);
            let minMinutes = minTime % 60
            if(minMinutes === 0) {
                minMinutes = "00";
            }
            return minHours + ":" + minMinutes;
        }
    },
    methods: {
        startTimeIsSelected() {
            if(this.endTimeDisabled) {
                this.endTimeDisabled = false;
            }
            this.endTime = '';

        },
        timeIsSelected() {
          if(this.startTime && this.endTime) {
           this.$emit('select-time', {startTime:this.startTime,endTime:this.endTime})
          }
        }
    },

}
</script>
<style lang="scss" >
.calendar-time {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    &__label {
        margin-bottom: 0;
        color: #006672;
        text-transform: uppercase;
        margin-right: 10px;
        font-family: 'Metro', sans-serif;
        font-size: 17px;
    }
    &__item {
        display: flex;
        align-items: center;
        &:first-child {
            margin-right: 10px;
        }
        .el-input__icon {
            display: none;
        }
    }
    &__descr {
          text-align: center;
          margin-top:20px;
        color:#A3A3A3;
        font-size: 14px;
      }
}
.el-input--prefix .el-input__inner {
    padding-left: 8px;
}
.el-input--suffix .el-input__inner {
    padding-right: 8px;
}
.el-date-editor.el-input, .el-date-editor.el-input__inner {
    width: 65px;
}
.el-input.is-active .el-input__inner, .el-input__inner {
    &::placeholder {
        text-align: center;
    }
    &:focus {
        border-color: #006672 !important;
    }
}
</style>
