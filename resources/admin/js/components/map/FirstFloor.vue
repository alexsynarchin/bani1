<template>
    <div class="map-floor">
        <h4 class="map-floor__title">
            1 ЭТАЖ
        </h4>
        <div style="font-weight: bold; color:red;">{{timeTitle}}</div>
        <section class="reserve-map">
            <img :src="'/assets/images/first-floor.png'">
            <div v-for="(place, index) in places"
                 @click.prevent="handleSelectPlace(place, index)"
                 class="reserve-map__place"
                 :style="{left:place.posX + 'px',top:place.posY + 'px'}"
                :class="{
                     'reserve-map__place--selected': place.select,
                }"
            >
                <span class="reserve-map__place-number" :class="{
                    'reserve-map__place-number--left' : place.type === 'left',
                    'reserve-map__place-number--right' : place.type === 'right',
                    'reserve-map__place-number--top' : place.type === 'top',
                    'reserve-map__place-number--down' : place.type === 'down',
                }">{{place.number}}</span>
                <img v-if="place.reserved" :src="'/assets/images/places/place-' + place.type  + '-res.svg' ">
                <img  v-else  :src="'/assets/images/places/place-' + place.type  + '.svg' ">
                <!--
                <svg viewBox="0 0 44 44" v-if="place.reserved">
                    <use :xlink:href="'/assets/site/images/sprites.svg?ver=29#sprite-place-' + place.type + '-res'"></use>
                </svg>
                <svg viewBox="0 0 44 44" v-else>
                    <use :xlink:href="'/assets/site/images/sprites.svg?ver=28#sprite-place-' + place.type"></use>
                </svg>
                -->
            </div>
        </section>
    </div>
</template>
<script>
export default {
    props: {
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
            type: Boolean,
            default: false
        },
        selectedPlacesArr: {
            type:Array,
            default: function (){
                return [];
            }
        },
        timeTitle: {
            type:String,
            default: "",
        }
    },
    data() {
        return {
            places: [],
        }
    },

    methods: {
        getPlaces() {
            axios.get( '/api/places/list/' + 1, {params:{startDate:this.startDate, endDate:this.endDate,date:this.date}})
            .then((response) => {
                this.places = response.data;
                this.places.forEach( (item, index)=> {
                    let selectIndex = this.selectedPlacesArr.findIndex(function(selected) {
                        return selected === index;
                    } )
                    if(selectIndex !==-1){
                        this.places[index].select = true;
                    }
                })
                console.log(this.places);
            })
        },
        handleSelectPlace(place, index) {
            if(this.canSelect && !place.reserved) {
                this.places[index].select =  !this.places[index].select;
                if(this.places[index].select) {
                    this.selectedPlacesArr.push(index);
                } else {
                   let selectedArrIndex =  this.selectedPlacesArr.findIndex(function (item) {
                       return item === index
                   });
                   this.selectedPlacesArr.splice(selectedArrIndex, 1);
                }

                let data = {
                    id: place.id,
                    type:'place',
                    price: place.price,
                    total_price:0,
                };
                let total_price = 0;
                let price = 0;
                if(this.duration > 2) {
                    let discount_time = this.duration - 2;
                    let discount_price = data['price'] - 100;
                    price = data['price'] * 2;
                    price = price + (discount_price * discount_time)
                } else {
                    price = data['price'] * this.duration;
                }
                data.total_price = price;
                this.$emit('select-item', data);

            } else if(place.reserved) {
                this.$notify({
                    title: 'Место занято',
                    message: '',
                    type: 'warning'
                });
            }
            else {
                this.$notify({
                    title: 'Выберите дату и время',
                    message: '',
                    type: 'warning'
                });
            }
        }
    },
    mounted() {
        this.getPlaces();
    }
}

</script>
