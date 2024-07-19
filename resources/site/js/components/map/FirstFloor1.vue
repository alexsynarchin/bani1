<template>
    <div class="map-floor">

        <div style="font-weight: bold; color:red;">{{timeTitle}}</div>
        <section class="reserve-map">
            <img :src="$root.api_url + '/assets/images/first-floor.png'">
            <div v-for="(cabinet, index) in cabinets"
                 @click.prevent="handleSelectCabinet(cabinet, index)"
                 class="reserve-map__cabinet"
                 :class="{
                     'reserve-map__cabinet--selected': cabinet.select,
                 }"
                 :style="{
                width : cabinet.width,

                height : cabinet.height,
                left:cabinet.posX + 'px',
                top:cabinet.posY + 'px'
            }">

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
            axios.get(this.$root.api_url + '/api/places/list/' + 1, {params:{startDate:this.startDate, endDate:this.endDate,date:this.date}})
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
