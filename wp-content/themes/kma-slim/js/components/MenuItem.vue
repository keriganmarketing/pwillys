<template>
  <div class="card menu-item" :class="{ 'has-image': item.photo }">

    <div v-if="item.photo" class="card-image" tabindex="0" >
      <figure class="image is-16by9">

        <div class="card-content item-price-info columns is-multiline is-mobile is-gapless">
          <div class="column has-text-left">
            <span v-if="language == 'english'" class="item-name dimbo" v-html="item.name_english"></span>
            <span v-if="language == 'spanish'" class="item-name dimbo" v-html="item.name_spanish"></span>
          </div>
          <div class="column is-narrow has-text-right">
            <span v-if="item.price" class="item-price dimbo" v-html="item.price"></span>
          </div>
        </div>

        <img :src="item.photo.sizes.large ? item.photo.sizes.large : item.photo.url" :alt="item.photo.alt">
      </figure>
      <div class="card-content" >
        <div 
          v-if="item.description_english && language == 'english'" 
          v-html="item.description_english"
        ></div>

        <div 
          v-if="item.description_spanish && language == 'spanish'" 
          v-html="item.description_spanish"
        ></div>
      </div>
    </div>
    <div v-else >
      <div class="card-content">
        <div class="columns is-mobile is-gapless item-price-info">
          <div class="column has-text-left">
            <span v-if="language == 'english'" class="item-name dimbo" v-html="item.name_english"></span>
            <span v-if="language == 'spanish'" class="item-name dimbo" v-html="item.name_spanish"></span>
          </div>
          <div class="column is-narrow has-text-right">
            <span class="item-price dimbo" v-html="item.price"></span>
          </div>
        </div>

        <div 
          v-if="item.description_english && language == 'english'" 
          v-html="item.description_english"
        ></div>

        <div 
          v-if="item.description_spanish && language == 'spanish'" 
          v-html="item.description_spanish"
        ></div>

      </div>

    </div>

  </div>
</template>
<script>
export default {
  name: "MenuItem",
  props: {
    item: {
      type: Object,
      default: () => {}
    },
    language: {
      type: String,
      default: 'english'
    }
  }
}
</script>