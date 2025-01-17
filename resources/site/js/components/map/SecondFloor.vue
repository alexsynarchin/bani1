<template>
    <div class="map-floor map-floor--second" >
        <h4 class="map-floor__title">
            2 ЭТАЖ
        </h4>
        <div style="font-weight: bold; color:red;">{{timeTitle}}</div>
        <section class="reserve-map reserve-map--second">
            <img :src="$root.api_url + '/assets/images/second-floor-1.png'">
            <div v-for="(cabinet, index) in cabinets"
                 @click.prevent="handleSelectCabinet(cabinet, index)"
                 class="reserve-map__cabinet"
                 :class="{
                     'reserve-map__cabinet--selected2': cabinet.select,
                 }"
                 :style="{
                width : cabinet.width,

                height : cabinet.height,
                left:cabinet.posX + 'px',
                top:cabinet.posY + 'px'
            }">

                <img v-if="cabinet.reserved" :src="$root.api_url + '/assets/images/places/cabinet-' + cabinet.number  + '-res.png' "
                     :width="cabinet.width"
                     :height="cabinet.height">
                <img v-else-if="cabinet.select" :src="$root.api_url + '/assets/images/places/cabinet-' + cabinet.number  + '-sel.png' "
                     :width="cabinet.width"
                     :height="cabinet.height">
                <img v-else  :src="$root.api_url + '/assets/images/places/cabinet-' + cabinet.number  + '.png' "
                     :width="cabinet.width"
                     :height="cabinet.height">

            </div>
            <div v-for="(place, index) in places"

                 @click.prevent="handleSelectPlace(place, index)"
                 class="reserve-map__place"

                 :style="{left:place.posX + 'px',top:place.posY + 'px'}"
                 :class="{
                     'reserve-map__place--selected2': place.select,
                }"
            >
                <span class="reserve-map__place-number" :class="{
                    'reserve-map__place-number--left' : place.type === 'left',
                    'reserve-map__place-number--right' : place.type === 'right',
                    'reserve-map__place-number--top' : place.type === 'top',
                    'reserve-map__place-number--down' : place.type === 'down',
                }">{{place.number}}</span>
                <img
                    style="width: 28px;height: 28px"
                    v-if="place.reserved" :src="$root.api_url + '/assets/images/places/place-' + place.type  + '-res.png' ">
                <img
                    style="width: 28px;height: 28px"
                    v-else-if="place.select" :src="$root.api_url + '/assets/images/places/place-' + place.type  + '-sel.png' ">
                <img
                    style="width: 28px;height: 28px"
                    v-else  :src="$root.api_url + '/assets/images/places/place-' + place.type  + '.png' ">

            </div>
        </section>
    </div>
</template>
<script>
export default {
    props: {
        timeTitle: {
            type:String,
            default: "",
        },
        selectedPlacesArr:{
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
    },
    data() {
        return {
            places: [],
            cabinets: [],
        }
    },
    methods: {
        getCabinets() {
            axios.get( this.$root.api_url + '/api/cabinets/list/' + 2, {params:{startDate:this.startDate, endDate:this.endDate, date:this.date}})
                .then((response) => {
                    this.cabinets = response.data;
                    this.cabinets.forEach( (item, index)=> {
                        let selectIndex = this.selectedCabinsArr.findIndex(function(selected) {
                            return selected === index;
                        } )
                        if(selectIndex !==-1){
                            this.cabinets[index].select = true;
                        }
                    })
                })
        },
        getPlaces() {
            axios.get(this.$root.api_url + '/api/places/list/' + 2, {params:{startDate:this.startDate, endDate:this.endDate, date:this.date}})
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
                    total_price:0
                };
                let total_price = 0;
                let price = 0;
                if(this.duration > 2) {
                    let discount_time = this.duration - 2;
                    let discount_price = data['price'] - 150;
                    price = data['price'] * 2;
                    price = price + (discount_price * discount_time)
                } else {
                    price = data['price'] * this.duration;
                }
                data.total_price = price;
                this.$emit('select-item', data)
            }
            else if(place.reserved) {
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
        },
         handleSelectCabinet(cabinet, index) {
             if(this.canSelect && !cabinet.reserved) {
                 this.cabinets[index].select =  !this.cabinets[index].select;
                 if(this.cabinets[index].select) {
                     this.selectedCabinsArr.push(index);
                 } else {
                     let selectedArrIndex =  this.selectedCabinsArr.findIndex(function (item) {
                         return item === index
                     });
                     this.this.selectedCabinsArr.splice(selectedArrIndex, 1);
                 }
                 let data = {
                     id: cabinet.id,
                     type:'cabinet',
                     price: cabinet.price,
                     total_price:0
                 };
                 let total_price = data['price'] * this.duration;
                 data.total_price = total_price;
                 this.$emit('select-item', data)
             }
             else if(cabinet.reserved) {
                 this.$notify({
                     title: 'Кабинка занята',
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
        this.getCabinets()
    }
}
</script>
